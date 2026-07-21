<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class OtpVerificationController extends Controller
{
    protected const OTP_TTL_MINUTES = 10;
    protected const RESEND_COOLDOWN_SECONDS = 60;
    protected const MAX_ATTEMPTS = 5;

    public function show(Request $request, string $token)
    {
        $otp = Otp::where('token', $token)
            ->where('purpose', 'email_verification')
            ->whereNull('verified_at')
            ->first();

        if (!$otp || $otp->user_id !== session('pending_otp_user_id')) {
            abort(404);
        }

        $user = User::find($otp->user_id);

        if (!$user || $user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Email already verified. Please log in.');
        }

        $expiresInSeconds = (int) max(0, now()->diffInSeconds($otp->expires_at, false));
        $resendAvailableInSeconds = $otp->last_sent_at
            ? (int) max(0, self::RESEND_COOLDOWN_SECONDS - abs(now()->diffInSeconds($otp->last_sent_at)))
            : 0;

        return view('auth.verify-otp', [
            'token' => $token,
            'maskedEmail' => $this->maskEmail($user->email),
            'expiresInSeconds' => $expiresInSeconds,
            'resendAvailableInSeconds' => $resendAvailableInSeconds,
        ]);
    }

    public function verify(Request $request, string $token)
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $otp = Otp::where('token', $token)
            ->where('purpose', 'email_verification')
            ->whereNull('verified_at')
            ->first();

        if (!$otp || $otp->user_id !== session('pending_otp_user_id')) {
            return response()->json(['message' => 'Invalid verification session. Please register again.'], 404);
        }

        if ($otp->expires_at->isPast()) {
            return response()->json(['message' => 'This code has expired. Please request a new one.'], 422);
        }

        if ($otp->attempts >= self::MAX_ATTEMPTS) {
            return response()->json(['message' => 'Too many attempts. Please request a new code.'], 429);
        }

        if (!Hash::check($request->code, $otp->code)) {
            $otp->increment('attempts');
            $remaining = self::MAX_ATTEMPTS - $otp->attempts;

            if ($remaining <= 0) {
                return response()->json([
                    'message' => 'Too many attempts. Please request a new code.',
                ], 429);
            }

            return response()->json([
                'message' => "Incorrect code. {$remaining} attempt(s) remaining.",
            ], 422);
        }

        $otp->update(['verified_at' => now()]);

        $user = User::findOrFail($otp->user_id);
        $user->forceFill(['email_verified_at' => now()])->save();

        session()->forget('pending_otp_user_id');
        $request->session()->regenerate();

        Auth::login($user);

        session()->flash('success', 'Email verified successfully. Welcome to your Dashboard!');

        return response()->json([
            'message' => 'Verified successfully. Redirecting...',
            'redirect' => $this->redirectPathForUser($user),
        ]);
    }

    public function resend(Request $request, string $token)
    {
        $otp = Otp::where('token', $token)
            ->where('purpose', 'email_verification')
            ->whereNull('verified_at')
            ->first();

        if (!$otp || $otp->user_id !== session('pending_otp_user_id')) {
            return response()->json(['message' => 'Invalid verification session.'], 404);
        }

        $secondsSinceLastSend = $otp->last_sent_at
            ? (int) abs(now()->diffInSeconds($otp->last_sent_at))
            : self::RESEND_COOLDOWN_SECONDS;

        if ($secondsSinceLastSend < self::RESEND_COOLDOWN_SECONDS) {
            return response()->json([
                'message' => 'Please wait before requesting another code.',
                'resendAvailableInSeconds' => self::RESEND_COOLDOWN_SECONDS - $secondsSinceLastSend,
            ], 429);
        }

        $rateLimitKey = 'otp-resend:' . $otp->user_id;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return response()->json(['message' => 'Too many resend requests. Try again later.'], 429);
        }
        RateLimiter::hit($rateLimitKey, 3600);

        $code = (string) random_int(100000, 999999);

        $otp->update([
            'code' => Hash::make($code),
            'attempts' => 0,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
        ]);

        User::findOrFail($otp->user_id)->notify(new sendOtpNotification($code));

        return response()->json([
            'message' => 'A new code has been sent to your email.',
            'expiresInSeconds' => self::OTP_TTL_MINUTES * 60,
            'resendAvailableInSeconds' => self::RESEND_COOLDOWN_SECONDS,
        ]);
    }

    protected function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email);
        $visible = mb_substr($name, 0, 2);

        return $visible . str_repeat('*', max(mb_strlen($name) - 2, 1)) . '@' . $domain;
    }

    protected function redirectPathForUser(User $user): string
    {
        return match (true) {
            $user->isAdmin() => route('admin.dashboard'),
            $user->isEventManager() => route('dashboard.index'),
            default => route('home'),
        };
    }
}

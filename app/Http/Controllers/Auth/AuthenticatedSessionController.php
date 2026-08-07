<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, \App\Services\OtpService $otpService): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Block banned users
        if ($user->is_banned) {
            Auth::guard('web')->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been permanently disabled. Contact support if you believe this is an error.',
            ]);
        }

        // Block suspended users
        if (! $user->is_active) {
            Auth::guard('web')->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended. Please contact support for assistance.',
            ]);
        }

        // Block unverified users — route them back into OTP verification
        if (! $user->hasVerifiedEmail()) {
            Auth::guard('web')->logout();

            $result = $otpService->generate($user, \App\Models\Otp::PURPOSE_EMAIL_VERIFICATION);

            session(['pending_otp_user_id' => $user->id]);

            $user->notify(new \App\Notifications\SendOtpNotification($result['code']));

            return redirect()->route('verification.otp.show', ['token' => $result['otp']->token])
                ->with('success', 'Please verify your email to continue. A new code has been sent.');
        }

        // Role-based redirect
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard.index');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Notifications\SendOtpNotification;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'string', 'max:20'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'organization_name' => $request->organization_name,
            'password' => Hash::make($request->password),
            'role' => 'event_manager',
        ]);

        $result = $otpService->generate(
            $user,
            Otp::PURPOSE_EMAIL_VERIFICATION
        );

        session([
            'pending_otp_user_id' => $user->id,
        ]);

        $user->notify(
            new SendOtpNotification($result['code'])
        );


        return redirect()
            ->route('verification.otp.show', [
                'token' => $result['otp']->token,
            ])
            ->with('success', 'Verification code sent to your email.');
    }
}

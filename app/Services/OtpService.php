<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpService
{
    public function generate(User $user, string $purpose): array
    {
        // Remove existing OTPs for this purpose
        Otp::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->delete();

        // Generate a 6 digit OTP
        $plainOtp = random_int(100000, 999999);

        // Store hashed OTP
        $otp = Otp::create([
            'token' => Str::uuid(),
            'user_id' => $user->id,
            'purpose' => $purpose,
            'code' => Hash::make($plainOtp),
            'attempts' => 0,
            'last_sent_at' => now(),
            'expires_at' => now()->addMinutes(10),
        ]);

        return [
            'otp' => $otp,
            'code' => $plainOtp,
        ];
    }

    public function verify(User $user, string $purpose, string $code): bool
    {
        $otp = Otp::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->latest()
            ->first();

        if (! $otp) {
            return false;
        }

        // OTP already used
        if ($otp->verified_at) {
            return false;
        }

        // OTP expired
        if (now()->greaterThan($otp->expires_at)) {
            return false;
        }

        // Maximum attempts reached
        if ($otp->attempts >= 5) {
            return false;
        }

        // Wrong OTP
        if (! Hash::check($code, $otp->code)) {
            $otp->increment('attempts');

            return false;
        }

        // Successful verification
        $otp->update([
            'verified_at' => now(),
        ]);

        return true;
    }
}

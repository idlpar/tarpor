<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;

trait OtpHandler
{
    protected function generateOtp(User $user)
    {
        $otp = Str::random(6); // Or use rand(100000, 999999)
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(15)
        ]);
        return $otp;
    }

    protected function sendEmailOtp(User $user, $otp)
    {
        // Implement email sending logic
    }

    // Add sendMobileOtp() later for SMS
}

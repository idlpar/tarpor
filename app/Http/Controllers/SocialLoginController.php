<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Mail;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('provider', 'google')->where('provider_id', $googleUser->id)->first();

        if (!$user) {
            $otp_code = random_int(100000, 999999);
            $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'provider' => 'google',
                    'provider_id' => $googleUser->id,
                    'role' => 'user',
                    'otp_code' => Hash::make($otp_code),
                    'otp_expires_at' => now()->addMinutes(15),
                    'last_otp_sent_at' => now(),
                    'is_verified' => false,
            ]);
            Mail::to($user->email)->queue(new SendOtp($otp_code));
            session()->put('otp_email', $user->email);
        }

        Auth::login($user);

        if (!$user->is_verified) {
            return redirect()->route('verify.otp.form');
        }

        return redirect()->route('dashboard');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->user();
        $user = User::where('provider', 'facebook')->where('provider_id', $facebookUser->id)->first();

        if (!$user) {
            $otp_code = random_int(100000, 999999);
            $user = User::create([
                'name' => $facebookUser->name,
                'email' => $facebookUser->email,
                'provider' => 'facebook',
                'provider_id' => $facebookUser->id,
                'role' => 'user',
                'otp_code' => Hash::make($otp_code),
                'otp_expires_at' => now()->addMinutes(15),
                'last_otp_sent_at' => now(),
                'is_verified' => false,
            ]);
            Mail::to($user->email)->queue(new SendOtp($otp_code));
            session()->put('otp_email', $user->email);
        }

        Auth::login($user);

        if (!$user->is_verified) {
            return redirect()->route('verify.otp.form');
        }

        return redirect()->route('dashboard');
    }
}

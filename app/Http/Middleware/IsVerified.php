<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use Symfony\Component\HttpFoundation\Response;

class IsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_verified) {
            // Allow access to essential routes for unverified users
            $allowedRoutes = [
                'dashboard',
                'verify.otp.form',
                'verify.otp',
                'resend.otp',
                'logout',
                'password.change.form',
                'password.change',
                'profile.show',
                'profile.update', // Allow profile viewing/updating
            ];

            if (!in_array($request->route()->getName(), $allowedRoutes, true)) {
                // Log the attempt for debugging
                \Log::info('Unverified user attempted restricted route', [
                    'user_id' => Auth::user()->id,
                    'email' => Auth::user()->email,
                    'route' => $request->route()->getName(),
                ]);

                // Store email in session for OTP verification
                $request->session()->put('otp_email', Auth::user()->email);

                // Send OTP using AuthController's resendOtp method
                $authController = app(AuthController::class);
                $otpResponse = $authController->resendOtp(new Request(['email' => Auth::user()->email]));

                // Check if OTP resend was successful
                if ($otpResponse->getSession()->has('error')) {
                    return redirect()->route('verify.otp.form')->withErrors([
                        'verify' => $otpResponse->getSession()->get('error'),
                    ]);
                }

                return redirect()->route('verify.otp.form')->with([
                    'success' => 'A new OTP has been sent to your email. Please verify your account.',
                    'verify' => 'You need to verify your account before performing this action.',
                ]);
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\OtpVerificationSuccess;
use App\Mail\PasswordResetSuccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtp;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            Auth::logoutOtherDevices($credentials['password']);
            $user = Auth::user();
            $request->session()->put('otp_email', $user->email);

            if (!$user->is_verified) {
                $this->resendOtp(new Request(['email' => $user->email]));
                return redirect()->route('verify.otp.form')
                    ->with('error', 'Please verify your OTP.');
            }

            return redirect()->intended(route('dashboard'))->with('success', 'Login successful! Welcome back.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully.');
    }

    public function showVerifyOtpForm(Request $request)
    {
        $email = $request->session()->get('otp_email');
        if (!$email && Auth::check()) {
            $email = Auth::user()->email;
            $request->session()->put('otp_email', $email);
        }

        if (!$email) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $email = $request->session()->get('otp_email');
        if (!$email) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $user = User::where('email', $email)
            ->where('otp_expires_at', '>', now())
            ->first();

        if ($user && Hash::check($request->otp_code, $user->otp_code)) {
            Auth::login($user);
            $user->update([
                'is_verified' => true,
                'otp_code' => null,
                'otp_expires_at' => null,
                'last_otp_sent_at' => null,
            ]);

            Mail::to($user->email)->send(new OtpVerificationSuccess($user));
            $request->session()->forget('otp_email');

            return redirect()->route('dashboard')
                ->with('success', 'Your account has been successfully verified!');
        }

        return back()->with('error', 'Invalid OTP or OTP expired');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|regex:/^01[0-9]{9}$/|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $otp_code = random_int(100000, 999999);
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
                'otp_code' => Hash::make($otp_code),
                'otp_expires_at' => now()->addMinutes(15),
                'last_otp_sent_at' => now(),
                'is_verified' => false,
            ]);

            Mail::to($user->email)->queue(new SendOtp($otp_code));
            Auth::login($user);
            session()->put('otp_email', $user->email);

            return redirect()->route('verify.otp.form')->with([
                'success' => 'OTP sent to your email! Please verify to continue.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Registration failed. Please try again later.',
            ])->withInput();
        }
    }

    public function showForgotPasswordForm()
    {
        return view('auth.passwords.email');
    }

    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        if ($user->role === 'guest') {
            return back()->with('error', 'Password reset not available for guest accounts');
        }

        $otp = random_int(100000, 999999);
        $user->update([
            'password_reset_otp' => Hash::make($otp),
            'password_reset_otp_expires_at' => now()->addMinutes(15),
            'last_password_reset_otp_sent_at' => now(),
        ]);

        Mail::to($user->email)->queue(new SendOtp($otp, 'password-reset'));
        $request->session()->put('password_reset_email', $user->email);

        return redirect()->route('password.reset')->with([
            'success' => 'Password reset OTP sent to your email!'
        ]);
    }

    public function showResetPasswordForm(Request $request)
    {
        if (!$request->session()->has('password_reset_email')) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please request a new OTP.');
        }

        return view('auth.passwords.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits gmin:6',
            'password' => 'required|confirmed|min:8',
        ]);

        $email = $request->session()->get('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please request a new OTP.');
        }

        $user = User::where('email', $email)
            ->where('password_reset_otp_expires_at', '>', now())
            ->first();

        if (!$user || !Hash::check($request->otp, $user->password_reset_otp)) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_reset_otp' => null,
            'password_reset_otp_expires_at' => null,
        ]);

        Mail::to($user->email)->send(new PasswordResetSuccess($user));
        $request->session()->forget('password_reset_email');
        Auth::logout();

        return redirect()->route('login')->with('success', 'Password reset successfully! Please log in with your new password.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        if ($user->role === 'guest') {
            return back()->with('error', 'OTP resend not available for guest accounts.');
        }

        $cooldownSeconds = 60;
        $lastSentTime = $user->last_otp_sent_at;
        if ($lastSentTime && $lastSentTime->addSeconds($cooldownSeconds)->isFuture()) {
            $remainingSeconds = now()->diffInSeconds($lastSentTime->addSeconds($cooldownSeconds));
            return back()->with('error', "Please wait {$remainingSeconds} seconds before resending.");
        }

        $otp = random_int(100000, 999999);
        $user->update([
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(15),
            'last_otp_sent_at' => now(),
        ]);

        Mail::to($user->email)->queue(new SendOtp($otp, 'login'));
        return back()->with('success', 'New OTP sent to your email!');
    }

    public function resendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        if ($user->role === 'guest') {
            return back()->with('error', 'Password reset not available for guest accounts.');
        }

        $cooldownSeconds = 60;
        $lastSentTime = $user->last_password_reset_otp_sent_at;
        if ($lastSentTime && $lastSentTime->addSeconds($cooldownSeconds)->isFuture()) {
            $remainingSeconds = now()->diffInSeconds($lastSentTime->addSeconds($cooldownSeconds));
            return back()->with('error', "Please wait {$remainingSeconds} seconds before resending.");
        }

        $otp = random_int(100000, 999999);
        $user->update([
            'password_reset_otp' => Hash::make($otp),
            'password_reset_otp_expires_at' => now()->addMinutes(15),
            'last_password_reset_otp_sent_at' => now(),
        ]);

        Mail::to($user->email)->queue(new SendOtp($otp, 'password-reset'));
        $request->session()->put('password_reset_email', $user->email);

        return back()->with('success', 'New OTP sent to your email!');
    }

    public function showChangePasswordForm()
    {
        return view('auth.passwords.change');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'The current password is incorrect.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        Mail::to($user->email)->send(new PasswordResetSuccess($user));
        Auth::logout();

        return redirect()->route('login')->with('success', 'Password changed successfully. Please log in with your new password.');
    }
}

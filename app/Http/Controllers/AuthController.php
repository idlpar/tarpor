<?php

namespace App\Http\Controllers;

use App\Mail\OtpVerificationSuccess;
use App\Mail\PasswordResetSuccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtp;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle Login Request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Attempt to log in the user
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Store email for OTP verification
            $request->session()->put('otp_email', $user->email);

            // If user is not verified, send them to OTP verification page
            if (!$user->is_verified) {
//                Auth::logout(); // Log out to prevent access before OTP
                return redirect()->route('verify.otp.form')
                    ->with('error', 'Please verify your OTP.');
            }

            // Redirect based on user role after verification
            return redirect()->intended($this->redirectTo())->with('success', 'Login successful! Welcome back.');
        }

        // If login fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Logout the user
    public function logout(Request $request)
    {
        Auth::logout(); // Log out the user

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/'); // Redirect to the homepage
    }


    // Show OTP verification form
    public function showVerifyOtpForm(Request $request)
    {
        $email = $request->session()->get('otp_email');

        if (!$email) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        // Retrieve the email from the session
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
                'otp_code' => null,
                'otp_expires_at' => null,
                'last_otp_sent_at' => null,
                'is_verified' => true,
            ]);

            // Send OTP verification success email
            Mail::to($user->email)->send(new OtpVerificationSuccess($user));

            // Clear the OTP email from the session
            $request->session()->forget('otp_email');

            return redirect()->intended($this->redirectTo())
                ->with('success', 'Your account has been successfully verified!');
        }

        return back()->with('error', 'Invalid OTP or OTP expired');
    }

    // Redirect based on role
    protected function redirectTo()
    {
        return match (Auth::user()->role) {
            'super' => route('super.dashboard'),
            'admin' => route('admin.dashboard'),
            'user' => route('user.dashboard'),
            default => route('login')
        };
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:11'
        ]);
        $otp_code = random_int(100000, 999999);
        // Create user but mark as unverified
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'role' => 'user', // Default role
            'otp_code' => Hash::make($otp_code),
            'otp_expires_at' => now()->addMinutes(15),
            'last_otp_sent_at' => now(),
        ]);

        // Send OTP email
        Mail::to($user->email)->queue(new SendOtp($otp_code));

        // Log in the user temporarily
        Auth::login($user);

        // ✅ Store email in session
        session()->put('otp_email', $user->email);

        return redirect()->route('verify.otp.form')->with([
            'success' => 'OTP sent to your email! Please verify to continue.'
        ]);
    }

    private function sendSmsOtp($phone, $otp)
    {
        // Implement your SMS gateway logic here
        // Example using Twilio:
        /*
        $twilio = new \Twilio\Rest\Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $twilio->messages->create(
            $phone,
            [
                'from' => config('services.twilio.from'),
                'body' => "Your OTP code is: $otp"
            ]
        );
        */
    }


    // Show forgot password form
    public function showForgotPasswordForm()
    {
        return view('auth.passwords.email');
    }

    // Send password reset OTP
    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->role === 'guest') {
            return back()->with('error', 'Password reset not available for guest accounts');
        }

        // Generate password reset specific OTP
        $otp = random_int(100000, 999999);
        $user->update([
            'password_reset_otp' => Hash::make($otp),
            'password_reset_otp_expires_at' => now()->addMinutes(15),
            'last_password_reset_otp_sent_at' => now(),
        ]);
        // Send OTP via email
        Mail::to($user->email)->queue(new SendOtp($otp, 'password-reset'));

        // Store email in session for verification
        $request->session()->put('password_reset_email', $user->email);


        return redirect()->route('password.reset')->with([
            'success' => 'Password reset OTP sent to your email!'
        ]);
    }

    // Show password reset form
    public function showResetPasswordForm(Request $request)
    {
        // Ensure the user has requested a password reset
        if (!$request->session()->has('password_reset_email')) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please request a new OTP.');
        }

        return view('auth.passwords.reset');
    }

    // Reset password after verification
    public function resetPassword(Request $request)
    {

        $request->validate([
            'otp' => 'required|digits:6',
            'password' => 'required|confirmed|min:8',
        ]);
        // Retrieve the email from the session
        $email = $request->session()->get('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please request a new OTP.');
        }

        // Find the user and validate the OTP
        $user = User::where('email', $email)
            ->where('password_reset_otp_expires_at', '>', now())
            ->first();


        if (!$user || !Hash::check($request->otp, $user->password_reset_otp)) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        // Update the user's password and clear the OTP
        $user->update([
            'password' => bcrypt($request->password),
            'password_reset_otp' => null,
            'password_reset_otp_expires_at' => null,
        ]);

        // Send Password Reset Success Email
        Mail::to($user->email)->send(new PasswordResetSuccess($user));
        // Clear the session
        $request->session()->forget('password_reset_email');

        Auth::logout(); // Ensure user is logged out after password reset
        return redirect()->route('login')->with('success', 'Password reset successfully! Please log in with your new password.');
    }
    // Resend Otp is for Registered Login Form
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Check if the user is a guest (if applicable)
        if ($user->role === 'guest') {
            return back()->with('error', 'OTP resend not available for guest accounts.');
        }

        // Check OTP resend cooldown (60 seconds)
        $cooldownSeconds = 60;
        $lastSentTime = $user->last_otp_sent_at;

        if ($lastSentTime) {
            $nextAllowedTime = $lastSentTime->addSeconds($cooldownSeconds);
            if ($nextAllowedTime->isFuture()) {
                $remainingSeconds = now()->diffInSeconds($nextAllowedTime);
                return back()->with('error', "Please wait {$remainingSeconds} seconds before resending.");
            }
        }

        // Generate a new OTP
        $otp = random_int(100000, 999999);

        // Update user with the new OTP
        $user->update([
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(15),
            'last_otp_sent_at' => now(),
        ]);

        // Send the new OTP via email
        Mail::to($user->email)->queue(new SendOtp($otp, 'login'));

        return back()->with('success', 'New OTP sent to your email!');
    }

    // Resend Password Reset is for Guest user to change password.
    public function resendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Check if the user is a guest (if applicable)
        if ($user->role === 'guest') {
            return back()->with('error', 'Password reset not available for guest accounts.');
        }

        // Check OTP resend cooldown (60 seconds)
        $cooldownSeconds = 60;
        $lastSentTime = $user->last_password_reset_otp_sent_at;

        if ($lastSentTime) {
            $nextAllowedTime = $lastSentTime->addSeconds($cooldownSeconds);
            if ($nextAllowedTime->isFuture()) {
                $remainingSeconds = now()->diffInSeconds($nextAllowedTime);
                return back()->with('error', "Please wait {$remainingSeconds} seconds before resending.");
            }
        }

        // Generate a new OTP
        $otp = random_int(100000, 999999);

        // Update user with the new OTP
        $user->update([
            'password_reset_otp' => Hash::make($otp),
            'password_reset_otp_expires_at' => now()->addMinutes(15),
            'last_password_reset_otp_sent_at' => now(),
        ]);

        // Send the new OTP via email
        Mail::to($user->email)->queue(new SendOtp($otp, 'password-reset'));

        // Store email in session for verification
        $request->session()->put('password_reset_email', $user->email);

        return back()->with('success', 'New OTP sent to your email!');
    }

    public function showChangePasswordForm()
    {
        return view('auth.passwords.change');
    }

    public function changePassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Verify the current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'The current password is incorrect.');
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        // Send Password Reset Success Email
        Mail::to($user->email)->send(new PasswordResetSuccess($user));
        // Log out the user (optional, for security)
        Auth::logout();

        // Redirect to login with a success message
        return redirect()->route('login')->with('success', 'Password changed successfully. Please log in with your new password.');
    }
}

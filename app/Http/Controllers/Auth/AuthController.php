<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserCreatedNotification;
use App\Mail\WelcomeNewUserMail;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->getDashboardRoute());
        }
        
        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');
        
        // Check if user exists and is active
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No account found with this email address.'],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated. Please contact support.'],
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Redirect based on user role
            return redirect()->intended(route($user->getDashboardRoute()));
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->getDashboardRoute());
        }
        
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'fullName' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phoneNumber' => ['nullable', 'string', 'max:20'],
            'referralCode' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phoneNumber' => $request->phoneNumber,
            'role' => 'client', // All registrations are automatically client accounts
            'status' => 'active',
        ]);

        // 🎁 Process referral if code was provided
        if ($request->filled('referralCode')) {
            try {
                $referralService = app(\App\Services\ReferralService::class);
                $metadata = [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'source' => 'registration_form',
                ];
                $referralService->processRegistrationReferral($user, $request->referralCode, $metadata);
            } catch (\Exception $e) {
                \Log::error('Failed to process referral during registration', [
                    'user_id' => $user->id,
                    'referral_code' => $request->referralCode,
                    'error' => $e->getMessage()
                ]);
                // Don't fail registration if referral processing fails
            }
        }

        // 🔔 Notify all admins about new user registration
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new UserCreatedNotification($user, 'Self-Registration', false));
        }

        // 📧 Send welcome email to new user
        try {
            Mail::to($user->email)->send(new WelcomeNewUserMail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email to new registered user', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }

        Auth::login($user);

        // Show referral welcome message if referred
        $message = 'Welcome to Treis Adiutor! Your account has been created successfully.';
        if ($request->filled('referralCode') && $user->isReferred()) {
            $message .= ' 🎉 Your referral bonus has been credited!';
        }

        return redirect()->route($user->getDashboardRoute())->with('success', $message);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Return success message even if user doesn't exist (security best practice)
            return back()->with('success', 'If an account exists with that email, you will receive a password reset link shortly.');
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated. Please contact support.'],
            ]);
        }

        // Generate password reset token
        $token = Str::random(64);
        
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Send password reset email
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);
        
        try {
            Mail::to($user->email)->send(new PasswordResetMail($resetUrl, $user->fullName));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }

        return back()->with('success', 'If an account exists with that email, you will receive a password reset link shortly.');
    }

    /**
     * Show the reset password form
     */
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify the token
        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            throw ValidationException::withMessages([
                'email' => ['Invalid or expired password reset token.'],
            ]);
        }

        // Check if token matches
        if (!Hash::check($request->token, $resetRecord->token)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid or expired password reset token.'],
            ]);
        }

        // Check if token is expired (60 minutes)
        $tokenAge = now()->diffInMinutes($resetRecord->created_at);
        if ($tokenAge > 60) {
            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            throw ValidationException::withMessages([
                'email' => ['This password reset link has expired. Please request a new one.'],
            ]);
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No account found with this email address.'],
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please log in with your new password.');
    }
}
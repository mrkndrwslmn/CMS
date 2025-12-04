<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\UserCreatedNotification;
use App\Mail\WelcomeNewUserMail;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Handles user authentication operations.
 * 
 * This controller manages login, registration, logout, and password reset
 * functionality. It includes security features like:
 * - Rate limiting (via route middleware)
 * - Account lockout after failed attempts
 * - Login attempt logging for security auditing
 * - Session regeneration to prevent fixation attacks
 * 
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    /**
     * Maximum number of failed login attempts before lockout.
     * 
     * @var int
     */
    protected const MAX_LOGIN_ATTEMPTS = 5;

    /**
     * Lockout duration in minutes.
     * 
     * @var int
     */
    protected const LOCKOUT_DURATION = 15;

    /**
     * Display the login form.
     * 
     * Redirects authenticated users to their dashboard.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->getDashboardRoute());
        }
        
        return view('auth.login');
    }

    /**
     * Handle login attempt.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $email = $request->email;
        
        // Check if account is locked out
        if ($this->isLockedOut($email)) {
            $this->logLoginAttempt($email, false, 'Account locked out', $request);
            $remainingMinutes = $this->getRemainingLockoutMinutes($email);
            throw ValidationException::withMessages([
                'email' => ["Too many failed login attempts. Please try again in {$remainingMinutes} minute(s)."],
            ]);
        }

        // Check if user exists and is active
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->incrementLoginAttempts($email);
            $this->logLoginAttempt($email, false, 'User not found', $request);
            throw ValidationException::withMessages([
                'email' => ['No account found with this email address.'],
            ]);
        }

        if ($user->status !== 'active') {
            $this->logLoginAttempt($email, false, 'Account inactive', $request);
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated. Please contact support.'],
            ]);
        }

        if (Auth::attempt($request->credentials(), $request->rememberMe())) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($email);
            $this->logLoginAttempt($email, true, 'Login successful', $request);
            
            // Redirect based on user role
            return redirect()->intended(route($user->getDashboardRoute()));
        }

        $this->incrementLoginAttempts($email);
        $attempts = $this->getLoginAttempts($email);
        $remaining = self::MAX_LOGIN_ATTEMPTS - $attempts;
        
        $this->logLoginAttempt($email, false, "Invalid credentials (attempt {$attempts})", $request);
        
        $message = 'The provided credentials do not match our records.';
        if ($remaining > 0 && $remaining <= 2) {
            $message .= " {$remaining} attempt(s) remaining before lockout.";
        }
        
        throw ValidationException::withMessages([
            'email' => [$message],
        ]);
    }

    /**
     * Get the cache key for login attempts.
     */
    protected function getLoginAttemptsKey(string $email): string
    {
        return 'login_attempts:' . strtolower($email);
    }

    /**
     * Get the cache key for lockout timestamp.
     */
    protected function getLockoutKey(string $email): string
    {
        return 'login_lockout:' . strtolower($email);
    }

    /**
     * Check if the account is locked out.
     */
    protected function isLockedOut(string $email): bool
    {
        return Cache::has($this->getLockoutKey($email));
    }

    /**
     * Get remaining lockout time in minutes.
     */
    protected function getRemainingLockoutMinutes(string $email): int
    {
        $lockoutTime = Cache::get($this->getLockoutKey($email));
        if (!$lockoutTime) {
            return 0;
        }
        
        $remaining = now()->diffInMinutes($lockoutTime, false);
        return max(1, $remaining);
    }

    /**
     * Get the number of login attempts.
     */
    protected function getLoginAttempts(string $email): int
    {
        return (int) Cache::get($this->getLoginAttemptsKey($email), 0);
    }

    /**
     * Increment the login attempts counter.
     */
    protected function incrementLoginAttempts(string $email): void
    {
        $key = $this->getLoginAttemptsKey($email);
        $attempts = $this->getLoginAttempts($email) + 1;
        
        // Store attempts for lockout duration + buffer
        Cache::put($key, $attempts, now()->addMinutes(self::LOCKOUT_DURATION + 5));
        
        // If max attempts reached, set lockout
        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            Cache::put(
                $this->getLockoutKey($email),
                now()->addMinutes(self::LOCKOUT_DURATION),
                now()->addMinutes(self::LOCKOUT_DURATION)
            );
            
            \Log::channel('daily')->warning('Account locked out due to failed login attempts', [
                'email' => $email,
                'attempts' => $attempts,
                'lockout_duration' => self::LOCKOUT_DURATION,
            ]);
        }
    }

    /**
     * Clear login attempts after successful login.
     */
    protected function clearLoginAttempts(string $email): void
    {
        Cache::forget($this->getLoginAttemptsKey($email));
        Cache::forget($this->getLockoutKey($email));
    }

    /**
     * Log a login attempt for security auditing.
     *
     * @param  string  $email
     * @param  bool  $successful
     * @param  string  $reason
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function logLoginAttempt(string $email, bool $successful, string $reason, $request): void
    {
        \Log::channel('daily')->info('Login attempt', [
            'email' => $email,
            'successful' => $successful,
            'reason' => $reason,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Display the registration form.
     * 
     * Redirects authenticated users to their dashboard.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->getDashboardRoute());
        }
        
        return view('auth.register');
    }

    /**
     * Handle user registration.
     * 
     * Creates a new client account, processes referral codes if provided,
     * sends notifications to admins, and logs the user in.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        // 🔐 Use transaction to ensure user creation and referral are atomic
        $user = DB::transaction(function () use ($request) {
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

            return $user;
        });

        // 🔔 Notify all admins about new user registration (outside transaction - non-critical)
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new UserCreatedNotification($user, 'Self-Registration', false));
        }

        // 📧 Send welcome email to new user (outside transaction - non-critical)
        try {
            Mail::to($user->email)->send(new WelcomeNewUserMail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email to new registered user', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }

        // 🔐 Login user and regenerate session to prevent session fixation
        Auth::login($user);
        $request->session()->regenerate();

        // Show referral welcome message if referred
        $message = 'Welcome to Treis Adiutor! Your account has been created successfully.';
        if ($request->filled('referralCode') && $user->isReferred()) {
            $message .= ' 🎉 Your referral bonus has been credited!';
        }

        return redirect()->route($user->getDashboardRoute())->with('success', $message);
    }

    /**
     * Log the user out and invalidate their session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Display the forgot password form.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     *
     * @param  \App\Http\Requests\Auth\ForgotPasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(ForgotPasswordRequest $request)
    {
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
     * Display the password reset form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token  The password reset token
     * @return \Illuminate\View\View
     */
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset.
     * 
     * Validates the reset token, updates the password, and logs the action.
     *
     * @param  \App\Http\Requests\Auth\ResetPasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
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

        // Use transaction to ensure password update and token deletion are atomic
        DB::transaction(function () use ($user, $request) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete the reset token only after password update succeeds
            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        });

        // Log the password reset for security auditing
        \Log::channel('daily')->info('Password reset completed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'timestamp' => now()->toIso8601String(),
        ]);

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please log in with your new password.');
    }
}
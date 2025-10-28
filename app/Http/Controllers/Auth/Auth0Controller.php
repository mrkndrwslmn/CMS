<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Auth0UserRepository;
use Auth0\Login\Facade\Auth0;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Auth0Controller extends Controller
{
    protected Auth0UserRepository $userRepository;

    public function __construct(Auth0UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Redirect to Auth0 for authentication
     */
    public function login(): RedirectResponse
    {
        // Check if Auth0 is enabled
        if (!config('auth0.enabled', true)) {
            return redirect()->route('login')
                ->with('error', 'Auth0 authentication is currently disabled.');
        }

        try {
            // Store the intended URL before redirecting to Auth0
            if (request()->has('redirect')) {
                Session::put('auth0_intended_url', request()->get('redirect'));
            }

            return Auth0::login(route('auth0.callback'));
        } catch (\Exception $e) {
            Log::error('Auth0 login failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('error', 'Authentication service is temporarily unavailable. Please try traditional login.');
        }
    }

    /**
     * Handle Auth0 callback after authentication
     */
    public function callback(): RedirectResponse
    {
        try {
            // Get user info from Auth0
            $userInfo = Auth0::getUser();
            
            if (!$userInfo) {
                throw new \Exception('No user information received from Auth0');
            }

            // Check if we're in account linking mode
            $linkedUser = $this->handleAccountLinking($userInfo);
            
            if ($linkedUser) {
                // Account linking successful
                $user = $linkedUser;
            } else {
                // Normal authentication flow
                $user = $this->userRepository->getUserByUserInfo($userInfo);
            }
            
            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Account registration is disabled. Please contact support.');
            }

            // Check if user is active
            if (!$user->isActive()) {
                return redirect()->route('login')
                    ->with('error', 'Your account has been deactivated. Please contact support.');
            }

            // Log the user in
            Auth::login($user, true);

            // Regenerate session for security
            request()->session()->regenerate();

            // Determine redirect URL
            $redirectUrl = $this->getRedirectUrl($user);

            Log::info('Auth0 authentication successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'auth0_id' => $user->auth0_id
            ]);

            $message = $linkedUser 
                ? 'Account linked successfully! You can now use Auth0 to sign in.'
                : 'Welcome back! You have been logged in successfully.';

            return redirect($redirectUrl)->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Auth0 callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('error', 'Authentication failed. Please try again or use traditional login.');
        }
    }

    /**
     * Handle Auth0 logout
     */
    public function logout(): RedirectResponse
    {
        try {
            // Log out from Laravel
            Auth::logout();

            // Invalidate the session
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            // Redirect to Auth0 logout URL with return URL
            $logoutUrl = config('auth0.logoutUrl', url('/'));
            
            return Auth0::logout($logoutUrl);

        } catch (\Exception $e) {
            Log::error('Auth0 logout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fall back to regular logout
            return redirect()->route('login')
                ->with('success', 'You have been logged out successfully.');
        }
    }

    /**
     * Link current user's account to Auth0
     */
    public function linkAccount(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to link your account.');
        }

        if (!$user->canLinkAuth0()) {
            return redirect()->back()
                ->with('error', 'Your account is already linked to Auth0 or cannot be linked.');
        }

        try {
            // Store user ID in session for linking after Auth0 authentication
            Session::put('auth0_link_user_id', $user->id);
            Session::put('auth0_linking_mode', true);

            return Auth0::login(route('auth0.callback'));

        } catch (\Exception $e) {
            Log::error('Auth0 account linking failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to initiate account linking. Please try again.');
        }
    }

    /**
     * Unlink current user's account from Auth0
     */
    public function unlinkAccount(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to unlink your account.');
        }

        if (!$user->canUnlinkAuth0()) {
            return redirect()->back()
                ->with('error', 'Your account cannot be unlinked from Auth0. Please set a password first.');
        }

        try {
            $this->userRepository->unlinkUserFromAuth0($user);

            Log::info('Auth0 account unlinked', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()->back()
                ->with('success', 'Your account has been unlinked from Auth0 successfully.');

        } catch (\Exception $e) {
            Log::error('Auth0 account unlinking failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to unlink your account. Please try again.');
        }
    }

    /**
     * Get the appropriate redirect URL after authentication
     */
    private function getRedirectUrl($user): string
    {
        // Check for stored intended URL from Auth0 login
        if (Session::has('auth0_intended_url')) {
            $url = Session::pull('auth0_intended_url');
            return $url;
        }

        // Check for Laravel's intended URL
        if (Session::has('url.intended')) {
            return Session::pull('url.intended');
        }

        // Default to user's dashboard
        return route($user->getDashboardRoute());
    }

    /**
     * Handle account linking during callback
     */
    private function handleAccountLinking(array $userInfo)
    {
        $userId = Session::pull('auth0_link_user_id');
        $linkingMode = Session::pull('auth0_linking_mode');

        if ($linkingMode && $userId) {
            $user = User::find($userId);
            
            if ($user && $user->canLinkAuth0()) {
                // Check if Auth0 account is already linked to another user
                $existingUser = $this->userRepository->findByAuth0Id($userInfo['sub']);
                
                if ($existingUser && $existingUser->id !== $user->id) {
                    throw new \Exception('This Auth0 account is already linked to another user.');
                }

                // Check if email matches
                if ($user->email !== ($userInfo['email'] ?? '')) {
                    throw new \Exception('Email addresses do not match. Cannot link accounts.');
                }

                $this->userRepository->linkUserToAuth0($user, $userInfo);
                return $user;
            }
        }

        return null;
    }
}
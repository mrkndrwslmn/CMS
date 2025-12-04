<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FirebaseAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/**
 * Handles Firebase-based social authentication.
 * 
 * This controller manages OAuth authentication through Firebase for
 * social providers (Google, Apple, Twitter). It supports:
 * - New user registration via social login
 * - Existing user login via linked social accounts
 * - Account linking for existing users
 * - Account unlinking
 * 
 * @package App\Http\Controllers\Auth
 */
class FirebaseAuthController extends Controller
{
    /**
     * The Firebase authentication service instance.
     *
     * @var \App\Services\FirebaseAuthService
     */
    protected FirebaseAuthService $firebaseAuth;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\FirebaseAuthService  $firebaseAuth
     */
    public function __construct(FirebaseAuthService $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    /**
     * Handle Firebase authentication callback.
     * 
     * Receives and verifies a Firebase ID token from the client,
     * then either logs in an existing user or creates a new account.
     * Also handles account linking when initiated by an authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleCallback(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'idToken' => 'required|string',
            ]);

            $idToken = $request->input('idToken');
            
            // Verify the Firebase ID token and get user data
            $firebaseData = $this->firebaseAuth->verifyIdToken($idToken);
            
            if (!$firebaseData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid authentication token'
                ], 401);
            }

            // Check if this is an account linking request
            $shouldLink = Session::pull('firebase_link_account', false);
            
            if ($shouldLink && Auth::check()) {
                return $this->linkAccountToFirebase($firebaseData);
            }

            // Find or create user based on Firebase data
            $user = $this->firebaseAuth->findOrCreateUser($firebaseData);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to create or find your account. Please contact support.'
                ], 400);
            }

            // Check if user is active
            if (!$user->isActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been deactivated. Please contact support.'
                ], 403);
            }

            // Log the user in
            Auth::login($user, true);

            // Regenerate session for security
            $request->session()->regenerate();

            // Determine redirect URL
            $redirectUrl = $this->getRedirectUrl($user);

            Log::info('Firebase authentication successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'provider' => $firebaseData['provider_id'] ?? 'unknown',
            ]);

            $providerName = $this->firebaseAuth->getProviderDisplayName($firebaseData['provider_id'] ?? '');
            
            return response()->json([
                'success' => true,
                'message' => "Welcome! You've been logged in successfully via {$providerName}.",
                'redirect' => $redirectUrl,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->fullName,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);

        } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
            Log::warning('Firebase token verification failed', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Your authentication token is invalid or has expired. Please try signing in again.'
            ], 401);

        } catch (\Kreait\Firebase\Exception\Auth\RevokedIdToken $e) {
            Log::warning('Firebase token was revoked', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Your session has been revoked. Please sign in again.'
            ], 401);

        } catch (\Exception $e) {
            Log::error('Firebase authentication failed', [
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);

            $errorMessage = 'Authentication failed. Please try again or use email/password login.';
            
            if (str_contains($e->getMessage(), 'No email provided')) {
                $errorMessage = 'The social provider didn\'t provide your email address. Please try signing in with email/password instead.';
            } elseif (str_contains($e->getMessage(), 'already exists but is linked')) {
                $errorMessage = 'An account with this email is already linked to a different social provider.';
            } elseif (str_contains($e->getMessage(), 'expired') || str_contains($e->getMessage(), 'Expired')) {
                $errorMessage = 'Your authentication session has expired. Please try signing in again.';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 400);
        }
    }

    /**
     * Initiate account linking for logged-in users.
     * 
     * Sets a session flag that triggers account linking mode
     * when handleCallback is called next.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiateLink(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to link your account.'
            ], 401);
        }

        if (!$user->canLinkFirebase()) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is already linked to Firebase or cannot be linked.'
            ], 400);
        }

        // Store flag in session for linking mode
        Session::put('firebase_link_account', true);

        return response()->json([
            'success' => true,
            'message' => 'Ready to link account. Please authenticate with your social provider.'
        ]);
    }

    /**
     * Handle account linking for existing users
     */
    protected function linkAccountToFirebase(array $firebaseData): JsonResponse
    {
        try {
            $currentUser = Auth::user();
            
            // Check if this Firebase account is already linked to another user
            $existingUser = User::where('firebase_uid', $firebaseData['uid'])->first();
            
            if ($existingUser && $existingUser->id !== $currentUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'This social account is already linked to another user.'
                ], 400);
            }
            
            // Link the account
            $this->firebaseAuth->linkFirebaseProfile($currentUser, $firebaseData);
            
            Log::info('Account linked to Firebase', [
                'user_id' => $currentUser->id,
                'email' => $currentUser->email,
                'provider' => $firebaseData['provider_id'] ?? 'unknown',
            ]);
            
            $providerDisplayName = $this->firebaseAuth->getProviderDisplayName($firebaseData['provider_id'] ?? '');
            
            return response()->json([
                'success' => true,
                'message' => "Your {$providerDisplayName} account has been successfully linked!",
                'redirect' => route($currentUser->getDashboardRoute())
            ]);
            
        } catch (\Exception $e) {
            Log::error('Account linking error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to link your social account. Please try again.'
            ], 400);
        }
    }

    /**
     * Unlink Firebase account from current user
     */
    public function unlinkAccount(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to unlink your account.');
        }

        if (!$user->canUnlinkFirebase()) {
            return redirect()->back()
                ->with('error', 'Your account cannot be unlinked from Firebase. Please set a password first.');
        }

        try {
            // Revoke Firebase refresh tokens
            if ($user->firebase_uid) {
                $this->firebaseAuth->revokeRefreshTokens($user->firebase_uid);
            }

            // Unlink from database
            $user->unlinkFirebase();

            Log::info('Firebase account unlinked', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()->back()
                ->with('success', 'Your account has been unlinked from Firebase successfully.');

        } catch (\Exception $e) {
            Log::error('Firebase account unlinking failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to unlink your account. Please try again.');
        }
    }

    /**
     * Handle logout with Firebase token revocation
     */
    public function logout(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();

            // Revoke Firebase refresh tokens if user is authenticated via Firebase
            if ($user && $user->isFirebaseUser() && $user->firebase_uid) {
                $this->firebaseAuth->revokeRefreshTokens($user->firebase_uid);
            }

            // Log out from Laravel
            Auth::logout();

            // Invalidate the session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'You have been logged out successfully.');

        } catch (\Exception $e) {
            Log::error('Firebase logout failed', [
                'error' => $e->getMessage()
            ]);

            // Fall back to regular logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('success', 'You have been logged out successfully.');
        }
    }

    /**
     * Get the appropriate redirect URL after authentication
     */
    protected function getRedirectUrl(User $user): string
    {
        // Check for stored intended URL
        if (Session::has('intended_url')) {
            return Session::pull('intended_url');
        }

        // Check for Laravel's intended URL
        if (Session::has('url.intended')) {
            return Session::pull('url.intended');
        }

        // Default to user's dashboard
        return route($user->getDashboardRoute());
    }

    /**
     * Get Firebase configuration for client-side
     */
    public function getConfig(): JsonResponse
    {
        return response()->json([
            'apiKey' => config('firebase.api_key'),
            'authDomain' => config('firebase.auth_domain'),
            'projectId' => config('firebase.project_id'),
            'storageBucket' => config('firebase.storage_bucket'),
            'messagingSenderId' => config('firebase.messaging_sender_id'),
            'appId' => config('firebase.app_id'),
            'socialProviders' => config('firebase.social_providers', [
                'google' => true,
                'apple' => true,
                'twitter' => true,
            ]),
        ]);
    }
}

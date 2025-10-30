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

class FirebaseAuthController extends Controller
{
    protected FirebaseAuthService $firebaseAuth;

    public function __construct(FirebaseAuthService $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    /**
     * Handle Firebase authentication callback
     * This endpoint receives the Firebase ID token from the client
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

        } catch (\Exception $e) {
            Log::error('Firebase authentication failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Authentication failed. Please try again or use email/password login.';
            
            if (str_contains($e->getMessage(), 'No email provided')) {
                $errorMessage = 'The social provider didn\'t provide your email address. Please try signing in with email/password instead.';
            } elseif (str_contains($e->getMessage(), 'already exists but is linked')) {
                $errorMessage = 'An account with this email is already linked to a different social provider.';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 400);
        }
    }

    /**
     * Initiate account linking for logged-in users
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

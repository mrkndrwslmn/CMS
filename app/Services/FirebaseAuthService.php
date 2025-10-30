<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Exception\FirebaseException;

class FirebaseAuthService
{
    protected FirebaseAuth $auth;

    public function __construct()
    {
        try {
            $serviceAccountPath = config('firebase.service_account_path');
            
            if (!file_exists($serviceAccountPath)) {
                Log::error('Firebase service account file not found: ' . $serviceAccountPath);
                throw new \Exception('Firebase service account not configured');
            }
            
            $factory = (new Factory)->withServiceAccount($serviceAccountPath);
            $this->auth = $factory->createAuth();
        } catch (\Exception $e) {
            Log::error('Failed to initialize Firebase Auth: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify Firebase ID token and return user data
     */
    public function verifyIdToken(string $idToken): array
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            $uid = $verifiedIdToken->claims()->get('sub');
            
            // Get user data from Firebase
            $firebaseUser = $this->auth->getUser($uid);
            
            // Convert providerData objects to arrays
            $providerData = [];
            foreach ($firebaseUser->providerData as $provider) {
                $providerData[] = [
                    'providerId' => $provider->providerId,
                    'uid' => $provider->uid,
                    'displayName' => $provider->displayName,
                    'email' => $provider->email,
                    'photoUrl' => $provider->photoUrl,
                    'phoneNumber' => $provider->phoneNumber,
                ];
            }
            
            return [
                'uid' => $firebaseUser->uid,
                'email' => $firebaseUser->email,
                'email_verified' => $firebaseUser->emailVerified,
                'display_name' => $firebaseUser->displayName,
                'photo_url' => $firebaseUser->photoUrl,
                'phone_number' => $firebaseUser->phoneNumber,
                'provider_id' => $this->extractProvider($providerData),
                'provider_data' => $providerData,
            ];
        } catch (FailedToVerifyToken $e) {
            Log::error('Failed to verify Firebase token', [
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Invalid Firebase token');
        } catch (FirebaseException $e) {
            Log::error('Firebase error during token verification', [
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Firebase authentication error');
        }
    }

    /**
     * Find or create user from Firebase data
     */
    public function findOrCreateUser(array $firebaseData): ?User
    {
        $uid = $firebaseData['uid'];
        $email = $firebaseData['email'] ?? null;

        // Log the received data for debugging
        Log::info('Firebase authentication attempt', [
            'uid' => $uid,
            'email' => $email,
            'provider' => $firebaseData['provider_id'] ?? 'unknown',
            'email_verified' => $firebaseData['email_verified'] ?? false,
        ]);

        // First, try to find user by Firebase UID
        $user = User::where('firebase_uid', $uid)->first();
        
        if ($user) {
            // Update the user's Firebase profile data
            $this->syncFirebaseProfile($user, $firebaseData);
            
            Log::info('Found existing user by Firebase UID', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            
            return $user;
        }

        // For new users, we need at least an email
        if (!$email) {
            // Handle Apple "Hide My Email" or other providers without email
            $provider = $firebaseData['provider_id'] ?? 'unknown';
            
            if ($provider === 'apple.com') {
                return $this->createAppleUserWithoutEmail($firebaseData);
            } else {
                throw new \Exception('No email provided by social provider for new user registration.');
            }
        }

        // Try to find existing user by email for account linking
        $user = User::where('email', $email)->first();
        
        if ($user) {
            // Link existing account to Firebase
            if ($user->canLinkFirebase()) {
                $this->linkFirebaseProfile($user, $firebaseData);
                
                Log::info('Linked existing account to Firebase', [
                    'user_id' => $user->id,
                    'email' => $email,
                    'provider' => $firebaseData['provider_id'] ?? 'unknown',
                ]);
                
                return $user;
            } else {
                // User exists but can't be linked (already has Firebase account)
                throw new \Exception('An account with this email already exists but is linked to a different provider.');
            }
        }

        // Create new user from Firebase data
        return $this->createUserFromFirebase($firebaseData);
    }

    /**
     * Create a new user from Firebase data
     */
    protected function createUserFromFirebase(array $firebaseData): User
    {
        $userData = [
            'fullName' => $this->extractName($firebaseData),
            'email' => $firebaseData['email'],
            'password' => Hash::make(\Illuminate\Support\Str::random(32)), // Random password
            'role' => 'client', // Default role for social signups
            'status' => 'active',
            'firebase_uid' => $firebaseData['uid'],
            'auth_provider' => 'firebase',
            'firebase_profile' => $firebaseData,
            'last_firebase_sync' => now(),
            'email_verified_at' => ($firebaseData['email_verified'] ?? false) ? now() : null,
        ];

        // Extract phone number if available
        if (!empty($firebaseData['phone_number'])) {
            $userData['phoneNumber'] = $firebaseData['phone_number'];
        }

        $user = User::create($userData);

        Log::info('Created new user from Firebase', [
            'user_id' => $user->id,
            'email' => $user->email,
            'provider' => $firebaseData['provider_id'] ?? 'unknown',
        ]);

        return $user;
    }

    /**
     * Create Apple user without email (handles "Hide My Email")
     */
    protected function createAppleUserWithoutEmail(array $firebaseData): User
    {
        // Generate a unique email based on the Firebase UID
        $uid = str_replace(['.', '@', '#', '$', '[', ']'], '_', $firebaseData['uid']);
        $generatedEmail = "apple_{$uid}@app.private";
        
        // Ensure email is not too long (database constraint might be 255 chars)
        if (strlen($generatedEmail) > 190) {
            // Use hash if too long
            $uidHash = substr(md5($firebaseData['uid']), 0, 20);
            $generatedEmail = "apple_{$uidHash}@app.private";
        }
        
        // Check if this generated email already exists (shouldn't happen, but just in case)
        $existingUser = User::where('email', $generatedEmail)->first();
        if ($existingUser) {
            throw new \Exception('A user with this Apple ID already exists but with different credentials.');
        }

        $userData = [
            'fullName' => $this->extractName($firebaseData) ?: 'Apple User',
            'email' => $generatedEmail,
            'password' => Hash::make(\Illuminate\Support\Str::random(32)),
            'role' => 'client',
            'status' => 'active',
            'firebase_uid' => $firebaseData['uid'],
            'auth_provider' => 'firebase',
            'firebase_profile' => $firebaseData,
            'last_firebase_sync' => now(),
            'email_verified_at' => now(), // Consider Apple-verified
        ];

        $user = User::create($userData);

        Log::info('Created new Apple user without email', [
            'user_id' => $user->id,
            'generated_email' => $generatedEmail,
            'firebase_uid' => $firebaseData['uid'],
        ]);

        return $user;
    }

    /**
     * Link existing user to Firebase
     */
    public function linkFirebaseProfile(User $user, array $firebaseData): void
    {
        $user->update([
            'firebase_uid' => $firebaseData['uid'],
            'auth_provider' => 'firebase',
            'firebase_profile' => $firebaseData,
            'last_firebase_sync' => now(),
            'email_verified_at' => ($firebaseData['email_verified'] ?? false) ? now() : $user->email_verified_at,
        ]);
    }

    /**
     * Sync user profile with Firebase data
     */
    public function syncFirebaseProfile(User $user, array $firebaseData): void
    {
        $updates = [
            'firebase_profile' => $firebaseData,
            'last_firebase_sync' => now(),
        ];

        // Optionally sync email if it changed in Firebase
        if (isset($firebaseData['email']) && $firebaseData['email'] !== $user->email) {
            $updates['email'] = $firebaseData['email'];
        }

        // Optionally sync name if it changed in Firebase
        $firebaseName = $this->extractName($firebaseData);
        if ($firebaseName && $firebaseName !== $user->fullName) {
            $updates['fullName'] = $firebaseName;
        }

        $user->update($updates);
    }

    /**
     * Extract user's full name from Firebase profile
     */
    protected function extractName(array $firebaseData): string
    {
        if (!empty($firebaseData['display_name'])) {
            return $firebaseData['display_name'];
        }

        if (!empty($firebaseData['email'])) {
            return explode('@', $firebaseData['email'])[0];
        }

        return 'User';
    }

    /**
     * Extract social provider from Firebase provider data
     */
    protected function extractProvider(array $providerData): string
    {
        if (empty($providerData)) {
            return 'password';
        }

        $providerId = $providerData[0]['providerId'] ?? 'password';
        
        return match($providerId) {
            'google.com' => 'google',
            'apple.com' => 'apple',
            'twitter.com' => 'twitter',
            'facebook.com' => 'facebook',
            default => $providerId,
        };
    }

    /**
     * Get display name for provider
     */
    public function getProviderDisplayName(string $providerId): string
    {
        return match($providerId) {
            'google.com', 'google' => 'Google',
            'apple.com', 'apple' => 'Apple',
            'twitter.com', 'twitter' => 'Twitter',
            'facebook.com', 'facebook' => 'Facebook',
            default => 'Social Login'
        };
    }

    /**
     * Revoke refresh tokens for a user (logout from Firebase)
     */
    public function revokeRefreshTokens(string $firebaseUid): void
    {
        try {
            $this->auth->revokeRefreshTokens($firebaseUid);
            Log::info('Revoked Firebase refresh tokens', ['firebase_uid' => $firebaseUid]);
        } catch (FirebaseException $e) {
            Log::error('Failed to revoke Firebase refresh tokens', [
                'firebase_uid' => $firebaseUid,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Create a custom token for a user (useful for server-side auth)
     */
    public function createCustomToken(string $firebaseUid, array $claims = [])
    {
        try {
            return $this->auth->createCustomToken($firebaseUid, $claims);
        } catch (FirebaseException $e) {
            Log::error('Failed to create custom token', [
                'firebase_uid' => $firebaseUid,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Failed to create authentication token');
        }
    }
}

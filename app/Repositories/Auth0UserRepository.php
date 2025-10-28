<?php

namespace App\Repositories;

use App\Models\User;
use Auth0\Laravel\UserRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Auth0UserRepository implements UserRepositoryContract
{
    /**
     * Generate a stateless User instance from a parsed Access Token.
     */
    public function fromAccessToken(array $user): ?Authenticatable
    {
        return $this->findOrCreateUser($user);
    }

    /**
     * Generate a stateful User instance from an available Auth0-PHP user session.
     */
    public function fromSession(array $user): ?Authenticatable
    {
        return $this->findOrCreateUser($user);
    }
    /**
     * Find or create a user based on Auth0 profile
     */
    private function findOrCreateUser(array $userInfo): ?User
    {
        // First, try to find user by Auth0 ID
        $user = User::where('auth0_id', $userInfo['sub'])->first();
        
        if ($user) {
            // Sync the user profile with latest Auth0 data
            $user->syncAuth0Profile($userInfo);
            return $user;
        }

        // If not found by Auth0 ID, try to find by email for account linking
        $user = User::where('email', $userInfo['email'] ?? null)->first();
        
        if ($user && config('auth0.autoLinkAccounts', false)) {
            // Auto-link existing account to Auth0
            $user->linkAuth0Profile($userInfo);
            return $user;
        }

        // If no existing user found and registration is allowed, create new user
        if (!$user && config('auth0.allowRegistration', true)) {
            return $this->createUserFromAuth0($userInfo);
        }

        return null;
    }

    /**
     * Find or create a user based on Auth0 profile (public method for backward compatibility)
     */
    public function getUserByUserInfo(array $userInfo): ?User
    {
        return $this->findOrCreateUser($userInfo);
    }

    /**
     * Create a new user from Auth0 profile
     */
    public function createUserFromAuth0(array $userInfo): User
    {
        // Extract user data from Auth0 profile
        $userData = [
            'fullName' => $this->extractName($userInfo),
            'email' => $userInfo['email'] ?? null,
            'password' => Hash::make(Str::random(32)), // Random password for Auth0 users
            'role' => config('auth0.defaultRole', 'client'),
            'status' => 'active',
            'auth0_id' => $userInfo['sub'],
            'auth_provider' => 'auth0',
            'auth0_profile' => $userInfo,
            'last_auth0_sync' => now(),
            'email_verified_at' => ($userInfo['email_verified'] ?? false) ? now() : null,
        ];

        // Extract phone number if available
        if (!empty($userInfo['phone_number'])) {
            $userData['phoneNumber'] = $userInfo['phone_number'];
        }

        return User::create($userData);
    }

    /**
     * Extract user's full name from Auth0 profile
     */
    private function extractName(array $userInfo): string
    {
        // Try different name fields from Auth0
        if (!empty($userInfo['name'])) {
            return $userInfo['name'];
        }

        if (!empty($userInfo['given_name']) && !empty($userInfo['family_name'])) {
            return trim($userInfo['given_name'] . ' ' . $userInfo['family_name']);
        }

        if (!empty($userInfo['nickname'])) {
            return $userInfo['nickname'];
        }

        if (!empty($userInfo['email'])) {
            return explode('@', $userInfo['email'])[0];
        }

        return 'Auth0 User';
    }

    /**
     * Find user by Auth0 ID
     */
    public function findByAuth0Id(string $auth0Id): ?User
    {
        return User::where('auth0_id', $auth0Id)->first();
    }

    /**
     * Link existing user to Auth0
     */
    public function linkUserToAuth0(User $user, array $auth0Profile): void
    {
        if ($user->canLinkAuth0()) {
            $user->linkAuth0Profile($auth0Profile);
        }
    }

    /**
     * Unlink user from Auth0
     */
    public function unlinkUserFromAuth0(User $user): void
    {
        if ($user->canUnlinkAuth0()) {
            $user->unlinkAuth0();
        }
    }

    /**
     * Check if a user can be auto-linked to Auth0
     */
    public function canAutoLink(string $email): bool
    {
        $user = User::where('email', $email)->first();
        return $user && $user->canLinkAuth0() && config('auth0.autoLinkAccounts', false);
    }
}
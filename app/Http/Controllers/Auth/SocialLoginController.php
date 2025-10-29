<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Auth0UserRepository;
use Auth0\Laravel\Facade\Auth0;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SocialLoginController extends Controller
{
    protected Auth0UserRepository $userRepository;

    public function __construct(Auth0UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Redirect to Auth0 for social authentication
     */
    public function redirectToProvider(Request $request): RedirectResponse
    {
        try {
            // Store any intended URL
            if ($request->has('redirect')) {
                Session::put('intended_url', $request->get('redirect'));
            }

            // Check if this is an account linking request
            if ($request->has('link') && $request->get('link') === 'true' && Auth::check()) {
                Session::put('auth0_link_account', true);
            }

            // Store that this is a social login (not traditional auth)
            Session::put('social_login_attempt', true);

            // Build the Auth0 authorization URL manually
            $domain = env('AUTH0_DOMAIN');
            $clientId = env('AUTH0_CLIENT_ID');
            $redirectUri = route('social.callback');
            $scope = env('AUTH0_SCOPE', 'openid profile email');
            
            // Generate state for CSRF protection
            $state = \Illuminate\Support\Str::random(40);
            Session::put('auth0_state', $state);
            
            $authUrl = "https://{$domain}/authorize?" . http_build_query([
                'response_type' => 'code',
                'client_id' => $clientId,
                'redirect_uri' => $redirectUri,
                'scope' => $scope,
                'state' => $state,
            ]);

            return redirect($authUrl);

        } catch (\Exception $e) {
            Log::error('Social login redirect failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('error', 'Social login is temporarily unavailable. Please try email/password login.');
        }
    }

    /**
     * Handle social login callback
     */
    public function handleCallback(Request $request): RedirectResponse
    {
        try {
            // Verify state parameter for CSRF protection
            $state = $request->get('state');
            $sessionState = Session::pull('auth0_state');
            
            if (!$state || $state !== $sessionState) {
                throw new \Exception('Invalid state parameter');
            }
            
            // Get authorization code
            $code = $request->get('code');
            if (!$code) {
                throw new \Exception('No authorization code received');
            }
            
            // Exchange code for tokens and get user info
            $auth0User = $this->getAuth0UserFromCode($code);
            
            if (!$auth0User) {
                throw new \Exception('No user information received from social provider');
            }

            // Check if this is an account linking request
            $shouldLink = Session::pull('auth0_link_account', false);
            
            if ($shouldLink && Auth::check()) {
                return $this->linkAccountToSocial($auth0User);
            }

            // Check if this was a social login attempt
            $isSocialLogin = Session::pull('social_login_attempt', false);
            
            if (!$isSocialLogin) {
                return redirect()->route('login')
                    ->with('error', 'Invalid authentication attempt.');
            }

            // Try to find existing user by email first
            $user = $this->findOrCreateUserFromSocial($auth0User);
            
            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Unable to create or link your social account. Please contact support.');
            }

            // Check if user is active
            if (!$user->isActive()) {
                return redirect()->route('login')
                    ->with('error', 'Your account has been deactivated. Please contact support.');
            }

            // Log the user in using Laravel's standard auth
            Auth::login($user, true);

            // Regenerate session for security
            request()->session()->regenerate();

            // Get redirect URL
            $redirectUrl = $this->getRedirectUrl($user);

            Log::info('Social login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'provider' => $this->extractProvider($auth0User),
                'auth0_id' => $user->auth0_id
            ]);

            $providerName = $this->getProviderDisplayName($auth0User);
            
            return redirect($redirectUrl)
                ->with('success', "Welcome! You've been logged in successfully via {$providerName}.");

        } catch (\Exception $e) {
            Log::error('Social login callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => [
                    'state' => $request->get('state'),
                    'code' => $request->has('code') ? 'present' : 'missing',
                    'error' => $request->get('error'),
                    'error_description' => $request->get('error_description')
                ]
            ]);

            // Provide more specific error messages for common issues
            $errorMessage = 'Social login failed. Please try again or use email/password login.';
            
            if (str_contains($e->getMessage(), 'No email provided by social provider')) {
                $errorMessage = 'The social provider didn\'t provide your email address. Please try signing in with email/password instead, or contact support for assistance.';
            } elseif (str_contains($e->getMessage(), 'already exists but is linked')) {
                $errorMessage = 'An account with this email is already linked to a different social provider. Please try signing in with your email/password or the original social provider.';
            } elseif (str_contains($e->getMessage(), 'Apple ID already exists but with different credentials')) {
                $errorMessage = 'There appears to be an issue with your Apple ID authentication. Please contact support for assistance.';
            }

            return redirect()->route('login')->with('error', $errorMessage);
        }
    }

    /**
     * Find or create user from social login
     */
    private function findOrCreateUserFromSocial(array $auth0User): ?User
    {
        $email = $auth0User['email'] ?? null;
        $auth0Id = $auth0User['sub'] ?? null;

        // Log the received data for debugging
        Log::info('Social login attempt with data', [
            'auth0_id' => $auth0Id,
            'email' => $email,
            'has_name' => !empty($auth0User['name']),
            'has_given_name' => !empty($auth0User['given_name']),
            'has_family_name' => !empty($auth0User['family_name']),
            'has_nickname' => !empty($auth0User['nickname']),
            'email_verified' => $auth0User['email_verified'] ?? false,
            'provider' => $this->extractProvider($auth0User),
            'available_fields' => array_keys($auth0User)
        ]);

        // Auth0 ID is essential for all social logins
        if (!$auth0Id) {
            throw new \Exception('Missing Auth0 ID from social provider');
        }

        // First, try to find user by Auth0 ID (already linked)
        $user = User::where('auth0_id', $auth0Id)->first();
        
        if ($user) {
            // Update the user's Auth0 profile data
            $user->syncAuth0Profile($auth0User);
            
            Log::info('Found existing user by Auth0 ID', [
                'user_id' => $user->id,
                'email' => $user->email,
                'provider' => $this->extractProvider($auth0User)
            ]);
            
            return $user;
        }

        // For new users, we need at least an email to create an account
        // However, Apple Sign-In has special handling
        if (!$email) {
            $provider = $this->extractProvider($auth0User);
            
            if ($provider === 'apple') {
                // For Apple, try to create a user with a generated email if we have sufficient info
                return $this->createAppleUserWithoutEmail($auth0User);
            } else {
                throw new \Exception('No email provided by social provider for new user registration.');
            }
        }

        // Try to find existing user by email for account linking
        $user = User::where('email', $email)->first();
        
        if ($user) {
            // Link existing account to Auth0
            if ($user->canLinkAuth0()) {
                $user->linkAuth0Profile($auth0User);
                
                Log::info('Linked existing account to social login', [
                    'user_id' => $user->id,
                    'email' => $email,
                    'provider' => $this->extractProvider($auth0User)
                ]);
                
                return $user;
            } else {
                // User exists but can't be linked (already has Auth0 account)
                throw new \Exception('An account with this email already exists but is linked to a different social provider.');
            }
        }

        // Create new user from social login
        return $this->createUserFromSocial($auth0User);
    }

    /**
     * Create a new user from social login data
     */
    private function createUserFromSocial(array $auth0User): User
    {
        $userData = [
            'fullName' => $this->extractName($auth0User),
            'email' => $auth0User['email'],
            'password' => bcrypt(\Illuminate\Support\Str::random(32)), // Random password
            'role' => 'client', // Default role for social signups
            'status' => 'active',
            'auth0_id' => $auth0User['sub'],
            'auth_provider' => 'auth0',
            'auth0_profile' => $auth0User,
            'last_auth0_sync' => now(),
            'email_verified_at' => ($auth0User['email_verified'] ?? false) ? now() : null,
        ];

        // Extract phone number if available
        if (!empty($auth0User['phone_number'])) {
            $userData['phoneNumber'] = $auth0User['phone_number'];
        }

        $user = User::create($userData);

        Log::info('Created new user from social login', [
            'user_id' => $user->id,
            'email' => $user->email,
            'provider' => $this->extractProvider($auth0User)
        ]);

        return $user;
    }

    /**
     * Create a new Apple user when email is not provided
     * This handles Apple's "Hide My Email" feature
     */
    private function createAppleUserWithoutEmail(array $auth0User): User
    {
        // Generate a unique email based on the Apple ID
        // Apple IDs look like: apple|001112.9efac3ba28ff49a7ad352a1055f81950.0015
        $appleId = str_replace(['apple|', '.'], ['', '_'], $auth0User['sub']);
        $generatedEmail = "apple_{$appleId}@app.private";
        
        // Ensure email is not too long (database constraint might be 255 chars)
        if (strlen($generatedEmail) > 190) {
            // Use hash if too long
            $appleIdHash = substr(md5($auth0User['sub']), 0, 20);
            $generatedEmail = "apple_{$appleIdHash}@app.private";
        }
        
        // Check if this generated email already exists (shouldn't happen, but just in case)
        $existingUser = User::where('email', $generatedEmail)->first();
        if ($existingUser) {
            throw new \Exception('A user with this Apple ID already exists but with different credentials. Please contact support.');
        }

        $userData = [
            'fullName' => $this->extractName($auth0User) ?: 'Apple User',
            'email' => $generatedEmail,
            'password' => bcrypt(\Illuminate\Support\Str::random(32)), // Random password
            'role' => 'client', // Default role for social signups
            'status' => 'active',
            'auth0_id' => $auth0User['sub'],
            'auth_provider' => 'auth0',
            'auth0_profile' => $auth0User,
            'last_auth0_sync' => now(),
            'email_verified_at' => now(), // Consider Apple-verified
        ];

        // Extract phone number if available
        if (!empty($auth0User['phone_number'])) {
            $userData['phoneNumber'] = $auth0User['phone_number'];
        }

        $user = User::create($userData);

        Log::info('Created new Apple user without email', [
            'user_id' => $user->id,
            'generated_email' => $generatedEmail,
            'auth0_id' => $auth0User['sub'],
            'available_fields' => array_keys($auth0User)
        ]);

        return $user;
    }

    /**
     * Extract user's full name from social profile
     */
    private function extractName(array $auth0User): string
    {
        if (!empty($auth0User['name'])) {
            return $auth0User['name'];
        }

        if (!empty($auth0User['given_name']) && !empty($auth0User['family_name'])) {
            return trim($auth0User['given_name'] . ' ' . $auth0User['family_name']);
        }

        if (!empty($auth0User['nickname'])) {
            return $auth0User['nickname'];
        }

        if (!empty($auth0User['email'])) {
            return explode('@', $auth0User['email'])[0];
        }

        // For Apple users without any name info, generate a friendly name
        $provider = $this->extractProvider($auth0User);
        if ($provider === 'apple') {
            return 'Apple User';
        }

        return 'Social User';
    }

    /**
     * Extract social provider from Auth0 user data
     */
    private function extractProvider(array $auth0User): string
    {
        $sub = $auth0User['sub'] ?? '';
        
        if (str_contains($sub, 'google')) return 'google';
        if (str_contains($sub, 'apple')) return 'apple';
        if (str_contains($sub, 'twitter')) return 'twitter';
        if (str_contains($sub, 'facebook')) return 'facebook';
        
        return 'social';
    }

    /**
     * Get display name for provider
     */
    private function getProviderDisplayName(array $auth0User): string
    {
        $provider = $this->extractProvider($auth0User);
        
        return match($provider) {
            'google' => 'Google',
            'apple' => 'Apple',
            'twitter' => 'Twitter',
            'facebook' => 'Facebook',
            default => 'Social Login'
        };
    }

    /**
     * Get redirect URL after social login
     */
    private function getRedirectUrl(User $user): string
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
     * Link existing user account to social provider
     */
    private function linkAccountToSocial(array $auth0User): RedirectResponse
    {
        try {
            $currentUser = Auth::user();
            
            // Check if this social account is already linked to another user
            $existingUser = User::where('auth0_id', $auth0User['sub'])->first();
            
            if ($existingUser && $existingUser->id !== $currentUser->id) {
                return redirect()->back()->with('error', 'This social account is already linked to another user.');
            }
            
            // Extract provider information
            $provider = $this->extractProvider($auth0User);
            
            // Link the account
            $currentUser->linkAuth0Profile($auth0User);
            
            Log::info('Account linked to social provider', [
                'user_id' => $currentUser->id,
                'email' => $currentUser->email,
                'provider' => $provider,
                'auth0_id' => $auth0User['sub']
            ]);
            
            $providerDisplayName = $this->getProviderDisplayName($auth0User);
            
            return redirect()->route($currentUser->getDashboardRoute())->with('success', "Your {$providerDisplayName} account has been successfully linked!");
            
        } catch (\Exception $e) {
            Log::error('Account linking error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to link your social account. Please try again.');
        }
    }

    /**
     * Exchange authorization code for user information
     */
    private function getAuth0UserFromCode(string $code): ?array
    {
        try {
            $domain = env('AUTH0_DOMAIN');
            $clientId = env('AUTH0_CLIENT_ID');
            $clientSecret = env('AUTH0_CLIENT_SECRET');
            $redirectUri = route('social.callback');

            // Exchange code for access token
            $tokenResponse = \Illuminate\Support\Facades\Http::post("https://{$domain}/oauth/token", [
                'grant_type' => 'authorization_code',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'code' => $code,
                'redirect_uri' => $redirectUri,
            ]);

            if (!$tokenResponse->successful()) {
                throw new \Exception('Failed to exchange code for token: ' . $tokenResponse->body());
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                throw new \Exception('No access token received');
            }

            // Get user info using access token
            $userResponse = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->get("https://{$domain}/userinfo");

            if (!$userResponse->successful()) {
                Log::error('Failed to get user info from Auth0', [
                    'status' => $userResponse->status(),
                    'response' => $userResponse->body()
                ]);
                throw new \Exception('Failed to get user info: ' . $userResponse->body());
            }

            $userData = $userResponse->json();
            
            // Log what data we received for debugging purposes
            Log::info('Received user data from Auth0', [
                'auth0_id' => $userData['sub'] ?? 'missing',
                'email' => $userData['email'] ?? 'missing',
                'email_verified' => $userData['email_verified'] ?? 'unknown',
                'name' => $userData['name'] ?? 'missing',
                'given_name' => $userData['given_name'] ?? 'missing',
                'family_name' => $userData['family_name'] ?? 'missing',
                'nickname' => $userData['nickname'] ?? 'missing',
                'picture' => isset($userData['picture']) ? 'present' : 'missing',
                'all_fields' => array_keys($userData)
            ]);

            return $userData;

        } catch (\Exception $e) {
            Log::error('Failed to get Auth0 user from code', [
                'error' => $e->getMessage(),
                'code' => substr($code, 0, 10) . '...' // Log partial code for debugging
            ]);
            
            return null;
        }
    }
}
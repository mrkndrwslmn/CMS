@if(config('firebase.authentication.enabled', true))
<div class="bg-white border border-neutral-200 rounded-lg p-6 shadow-sm">
    <h3 class="text-lg font-semibold text-neutral-900 mb-4">
        <i class="fas fa-link text-primary-600 mr-2"></i>
        Social Account Integration
    </h3>
    
    @if(auth()->user()->isFirebaseUser())
        <!-- User is logged in via social login -->
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @php
                            $provider = 'social';
                            if (auth()->user()->firebase_profile) {
                                $providerId = auth()->user()->firebase_profile['firebase']['sign_in_provider'] ?? '';
                                if (str_contains($providerId, 'google')) $provider = 'google';
                                elseif (str_contains($providerId, 'apple')) $provider = 'apple';
                                elseif (str_contains($providerId, 'twitter')) $provider = 'twitter';
                            }
                        @endphp
                        
                        @if($provider === 'google')
                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                        @elseif($provider === 'apple')
                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path fill="#000000" d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                        @elseif($provider === 'twitter')
                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path fill="#1DA1F2" d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        @else
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        @endif
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-green-800">{{ ucfirst($provider) }} Account Connected</h4>
                        <p class="text-sm text-green-700">
                            Your account is connected via {{ ucfirst($provider) }} for easy sign-in.
                        </p>
                    </div>
                </div>
                <div class="text-sm text-green-600 font-medium">
                    Connected
                </div>
            </div>
            
            <div class="pt-4 border-t border-neutral-200">
                <p class="text-sm text-neutral-600 mb-4">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    You can still use your email and password to log in as a backup method.
                </p>
            </div>
        </div>
        
    @elseif(auth()->user()->canLinkFirebase())
        <!-- User can link to social accounts -->
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800">Link Social Accounts</h4>
                        <p class="text-sm text-blue-700">
                            Connect your account to social providers for easier sign-in options.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-3">
                <h5 class="text-sm font-medium text-neutral-700">Available social login options:</h5>
                
                <div class="grid grid-cols-1 gap-3">
                    @if(config('firebase.authentication.social_providers.google', true))
                    <button 
                        onclick="linkFirebaseAccount('google')" 
                        class="flex items-center justify-between p-3 border border-neutral-200 rounded-lg hover:bg-neutral-50 transition-colors"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="text-sm font-medium">Connect Google Account</span>
                        </div>
                        <i class="fas fa-arrow-right text-neutral-400"></i>
                    </button>
                    @endif
                    
                    @if(config('firebase.authentication.social_providers.apple', true))
                    <button 
                        onclick="linkFirebaseAccount('apple')" 
                        class="flex items-center justify-between p-3 border border-neutral-200 rounded-lg hover:bg-neutral-50 transition-colors"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                <path fill="#000000" d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                            <span class="text-sm font-medium">Connect Apple Account</span>
                        </div>
                        <i class="fas fa-arrow-right text-neutral-400"></i>
                    </button>
                    @endif
                    
                    @if(config('firebase.authentication.social_providers.twitter', true))
                    <button 
                        onclick="linkFirebaseAccount('twitter')" 
                        class="flex items-center justify-between p-3 border border-neutral-200 rounded-lg hover:bg-neutral-50 transition-colors"
                    >
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                <path fill="#1DA1F2" d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            <span class="text-sm font-medium">Connect Twitter Account</span>
                        </div>
                        <i class="fas fa-arrow-right text-neutral-400"></i>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        
    @else
        <!-- User already linked or cannot link -->
        <div class="p-4 bg-neutral-50 border border-neutral-200 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-neutral-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-neutral-800">Social Account Integration</h4>
                    <p class="text-sm text-neutral-600">
                        Your account configuration doesn't support social account linking at this time.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<script type="module">
import { linkAccount } from '@/firebase-auth.js';

window.linkFirebaseAccount = async function(provider) {
    try {
        const result = await linkAccount(provider);
        if (result.success) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Failed to link account:', error);
        alert('Failed to link account. Please try again.');
    }
};
</script>
@endif

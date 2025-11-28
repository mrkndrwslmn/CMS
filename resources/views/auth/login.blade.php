@extends('layouts.public')

@section('title', 'Login - Treis Adiutor')

@section('content')
<div class="min-h-screen bg-primary-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-5xl">
        <div class="rounded-2xl overflow-hidden shadow-lg bg-white border border-primary-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                <!-- Left side (Features) -->
                <div class="bg-gradient-to-br from-primary-600 to-primary-700 text-white p-8 md:p-12 flex flex-col justify-center">
                    <div>
                        <h2 class="heading-serif text-4xl font-bold mb-6">Welcome Back</h2>
                        <p class="text-primary-100 text-base leading-relaxed mb-12">Access your projects, manage tasks, and track progress all in one place.</p>
                        
                        <div class="space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-300 flex items-center justify-center mt-1">
                                    <i class="fas fa-project-diagram text-primary-700 text-sm"></i>
                                </div>
                                <span class="text-primary-50 text-sm">Access your projects and tasks</span>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-300 flex items-center justify-center mt-1">
                                    <i class="fas fa-clipboard-list text-primary-700 text-sm"></i>
                                </div>
                                <span class="text-primary-50 text-sm">Update project details and requirements</span>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-300 flex items-center justify-center mt-1">
                                    <i class="fas fa-chart-line text-primary-700 text-sm"></i>
                                </div>
                                <span class="text-primary-50 text-sm">Track your project's progress</span>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-300 flex items-center justify-center mt-1">
                                    <i class="fas fa-bell text-primary-700 text-sm"></i>
                                </div>
                                <span class="text-primary-50 text-sm">Stay updated with notifications</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right side (Login form) -->
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    <div>
                        <h1 class="heading-serif text-3xl font-bold text-primary-900 mb-2">Sign In</h1>
                        <p class="text-primary-600 text-sm mb-8">Enter your credentials to access your account</p>
                        
                        <!-- Error/Success Messages -->
                        <div id="auth-error" class="hidden mb-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm"></div>
                        <div id="auth-success" class="hidden mb-5 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm"></div>
                        
                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-primary-900 mb-2">Email Address</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    placeholder="you@example.com" 
                                    required
                                    value="{{ old('email') }}"
                                    class="w-full px-4 py-3 text-sm rounded-lg border border-primary-200 bg-primary-50 text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 bg-red-50 focus:ring-red-500 @enderror"
                                >
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-primary-900 mb-2">Password</label>
                                <div class="relative">
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        placeholder="••••••••" 
                                        required
                                        class="w-full px-4 py-3 text-sm rounded-lg border border-primary-200 bg-primary-50 text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 bg-red-50 focus:ring-red-500 @enderror"
                                    >
                                    <button 
                                        type="button" 
                                        class="absolute inset-y-0 right-0 px-4 flex items-center text-primary-500 hover:text-primary-700 transition-colors focus:outline-none"
                                        onclick="togglePassword()"
                                    >
                                        <i id="toggleIcon" class="fas fa-eye text-sm"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between pt-2">
                                <div class="flex items-center">
                                    <input 
                                        id="remember" 
                                        name="remember" 
                                        type="checkbox" 
                                        class="h-4 w-4 text-primary-600 bg-primary-50 border-primary-300 rounded focus:ring-primary-500"
                                    >
                                    <label for="remember" class="ml-2 block text-sm text-primary-700">
                                        Remember me
                                    </label>
                                </div>
                                <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">Forgot password?</a>
                            </div>
                            
                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="btn-primary w-full py-3 mt-6"
                            >
                                Sign In
                            </button>
                            
                            <!-- Social Login Options -->
                            <div class="pt-6">
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-primary-200"></div>
                                    </div>
                                    <div class="relative flex justify-center text-xs">
                                        <span class="px-3 bg-white text-primary-500 font-medium">Or continue with</span>
                                    </div>
                                </div>
                                
                                <div class="mt-6 grid grid-cols-1 gap-3">
                                    <!-- Google Login -->
                                    @if(config('firebase.social_providers.google', true))
                                    <button 
                                        type="button"
                                        onclick="handleSocialLogin('google')"
                                        id="google-login-btn"
                                        class="w-full inline-flex justify-center items-center px-4 py-3 border border-primary-200 rounded-lg text-sm font-medium text-primary-900 bg-primary-50 hover:bg-primary-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 transition-all duration-200"
                                    >
                                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                        </svg>
                                        Google
                                    </button>
                                    @endif
                                    
                                    <!-- Apple Login -->
                                    @if(config('firebase.social_providers.apple', true))
                                    <button 
                                        type="button"
                                        onclick="handleSocialLogin('apple')"
                                        id="apple-login-btn"
                                        class="w-full inline-flex justify-center items-center px-4 py-3 border border-primary-200 rounded-lg text-sm font-medium text-primary-900 bg-primary-50 hover:bg-primary-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 transition-all duration-200"
                                    >
                                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                            <path fill="#000000" d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                                        </svg>
                                        Apple
                                    </button>
                                    @endif
                                    
                                    <!-- Twitter Login -->
                                    @if(config('firebase.social_providers.twitter', true))
                                    <button 
                                        type="button"
                                        onclick="handleSocialLogin('twitter')"
                                        id="twitter-login-btn"
                                        class="w-full inline-flex justify-center items-center px-4 py-3 border border-primary-200 rounded-lg text-sm font-medium text-primary-900 bg-primary-50 hover:bg-primary-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-0 transition-all duration-200"
                                    >
                                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                            <path fill="#1DA1F2" d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417a9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                        Twitter
                                    </button>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Register Link -->
                            <div class="text-center pt-2">
                                <p class="text-primary-700 text-sm">
                                    Don't have an account? 
                                    <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">Sign up</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/firebase-auth.js'])

<script>
// Handle social login button clicks
window.handleSocialLogin = async function(provider) {
    const buttonId = `${provider}-login-btn`;
    const button = document.getElementById(buttonId);
    
    if (!button) return;
    
    try {
        // Wait for firebaseAuthService to be ready
        if (!window.firebaseAuthService) {
            throw new Error('Firebase Auth not initialized');
        }
        
        // Set initial loading state on button
        window.firebaseAuthService.setButtonLoading(button, true, 'Processing...');
        
        // Hide any previous messages
        document.getElementById('auth-error').classList.add('hidden');
        document.getElementById('auth-success').classList.add('hidden');
        
        // Call the appropriate sign-in method
        let result;
        switch(provider) {
            case 'google':
                result = await window.firebaseAuthService.signInWithGoogle();
                break;
            case 'apple':
                result = await window.firebaseAuthService.signInWithApple();
                break;
            case 'twitter':
                result = await window.firebaseAuthService.signInWithTwitter();
                break;
            default:
                throw new Error('Unsupported provider');
        }
        
        // Update button message during backend processing
        window.firebaseAuthService.updateButtonMessage(button, 'Completing sign in...');
        
        // Show success and redirect
        if (result.success) {
            window.firebaseAuthService.updateButtonMessage(button, 'Redirecting...');
            window.firebaseAuthService.showSuccess(result.message || 'Successfully signed in!');
            
            // Redirect immediately
            window.location.href = result.redirect;
        } else {
            throw new Error(result.message);
        }
        
    } catch (error) {
        console.error('Social login error:', error);
        if (window.firebaseAuthService) {
            window.firebaseAuthService.showError(error.message);
            window.firebaseAuthService.setButtonLoading(button, false);
        } else {
            alert(error.message);
            if (button) {
                button.disabled = false;
                if (button.dataset.originalText) {
                    button.innerHTML = button.dataset.originalText;
                }
            }
        }
    }
};

function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

window.togglePassword = togglePassword;

// Form submission handling
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        submitBtn.innerHTML = `
            <div class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Signing In...</span>
            </div>
        `;
        submitBtn.disabled = true;
    });
});
</script>
@endpush
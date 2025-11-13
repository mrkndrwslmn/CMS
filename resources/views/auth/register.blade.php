@extends('layouts.public')

@section('title', 'Register - Treis Adiutor')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-center min-h-full">
        <div class="glass-card soft-shadow w-full max-w-6xl flex flex-col md:flex-row overflow-hidden">
        <!-- Left side (image/features) -->
        <div class="bg-gradient-to-br from-primary-600 to-primary-200 p-8 md:w-1/2">
            <div class="h-full flex flex-col justify-center">
                <h2 class="heading-serif text-3xl mb-4">Create an Account</h2>
                <p class="text-lg mb-8 text-primary-50">Create your account to start managing your tasks and projects.</p>
                
                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="text-accent-400 text-xl w-8">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <span class="text-primary-100">Access your projects and tasks</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-accent-400 text-xl w-8">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="text-primary-100">Update your project details and requirements</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-accent-400 text-xl w-8">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="text-primary-100">Track your project's progress</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-accent-400 text-xl w-8">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span class="text-primary-100">Stay updated with notifications</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right side (register form) -->
        <div class="p-8 md:w-1/2 bg-white">
            <h2 class="heading-serif text-3xl text-primary-700 mb-8 text-center">Sign Up</h2>
            
            <form method="POST" action="{{ route('register') }}" class="space-y-6 smooth-transition">
                @csrf
                
                <!-- Full Name -->
                <div class="space-y-2">
                    <label for="fullName" class="block text-sm font-semibold text-neutral-700">Full Name</label>
                    <input 
                        type="text" 
                        id="fullName" 
                        name="fullName" 
                        placeholder="Your Name" 
                        required
                        value="{{ old('fullName') }}"
                        class="w-full px-4 py-3 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('fullName') border-red-500 focus:ring-red-500 @enderror"
                    >
                    @error('fullName')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-neutral-700">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="example@treisadiutor.com" 
                        required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('email') border-red-500 focus:ring-red-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="space-y-2">
                    <label for="phoneNumber" class="block text-sm font-semibold text-neutral-700">Phone Number</label>
                    <input 
                        type="tel" 
                        id="phoneNumber" 
                        name="phoneNumber" 
                        placeholder="+63 (917) 123-4567" 
                        required
                        oninput="formatPhoneNumber(event)"
                        value="{{ old('phoneNumber') }}"
                        class="w-full px-4 py-3 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('phoneNumber') border-red-500 focus:ring-red-500 @enderror"
                    >
                    @error('phoneNumber')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Referral Code -->
                <div class="space-y-2">
                    <label for="referralCode" class="block text-sm font-semibold text-neutral-700">
                        Referral Code 
                        <span class="text-neutral-500 font-normal">(Optional)</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="referralCode" 
                            name="referralCode" 
                            placeholder="Enter referral code" 
                            value="{{ old('referralCode', request()->query('ref')) }}"
                            class="w-full px-4 py-3 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 uppercase @error('referralCode') border-red-500 focus:ring-red-500 @enderror"
                        >
                        <div id="referralCodeStatus" class="hidden absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg id="referralCodeValid" class="hidden h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <svg id="referralCodeInvalid" class="hidden h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <svg id="referralCodeLoading" class="hidden animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <p id="referralCodeMessage" class="mt-2 text-sm hidden"></p>
                    @error('referralCode')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if(request()->query('ref'))
                    <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg flex items-start">
                        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-green-800">
                            <p class="font-semibold">You've been referred!</p>
                            <p>Complete registration to unlock your welcome bonus: <strong>500 points + 15% off coupon</strong></p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Role Selection -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-neutral-700">Account Type</label>
                    <div class="space-y-4">
                        <div class="flex items-start glass-button p-4 rounded-xl border border-neutral-200 hover-lift">
                            <input 
                                id="role-client" 
                                name="role" 
                                type="radio" 
                                value="client" 
                                {{ old('role') == 'client' ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300">
                            <div class="ml-3">
                                <label for="role-client" class="block text-sm font-semibold text-neutral-700">
                                    Client
                                </label>
                                <p class="text-xs text-neutral-500">I need professional services and want to submit requests</p>
                            </div>
                        </div>
                        <div class="flex items-start glass-button p-4 rounded-xl border border-neutral-200 hover-lift">
                            <input 
                                id="role-adiutor" 
                                name="role" 
                                type="radio" 
                                value="adiutor" 
                                {{ old('role') == 'adiutor' ? 'checked' : '' }}
                                class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300">
                            <div class="ml-3">
                                <label for="role-adiutor" class="block text-sm font-semibold text-neutral-700">
                                    Adiutor (Service Provider)
                                </label>
                                <p class="text-xs text-neutral-500">I want to provide professional services to clients</p>
                            </div>
                        </div>
                    </div>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-neutral-700">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 @error('password') border-red-500 focus:ring-red-500 @enderror"
                        >
                        <span onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-neutral-500 hover:text-neutral-700 transition-colors">
                            <i id="password-toggle-icon" class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-semibold text-neutral-700">Confirm Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="••••••••" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                        >
                        <span onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-neutral-500 hover:text-neutral-700 transition-colors">
                            <i id="password_confirmation-toggle-icon" class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn-primary w-full"
                >
                    Create Account
                </button>
                
                <!-- Firebase Registration Options -->
                @if(config('firebase.authentication.enabled', true))
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-neutral-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-neutral-500">Or sign up with</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 gap-3">
                        <!-- Google Signup -->
                        @if(config('firebase.authentication.social_providers.google', true))
                        <button 
                            onclick="window.firebaseAuthService.signInWithGoogle().then(data => { if (data.success) window.location.href = data.redirect; }).catch(err => window.firebaseAuthService.showError(err.message));" 
                            class="w-full inline-flex justify-center items-center px-4 py-3 border border-neutral-300 rounded-xl shadow-sm text-sm font-medium text-neutral-700 bg-white hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                        >
                            <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Continue with Google
                        </button>
                        @endif
                        
                        <!-- Apple Signup -->
                        @if(config('firebase.authentication.social_providers.apple', true))
                        <button 
                            onclick="window.firebaseAuthService.signInWithApple().then(data => { if (data.success) window.location.href = data.redirect; }).catch(err => window.firebaseAuthService.showError(err.message));" 
                            class="w-full inline-flex justify-center items-center px-4 py-3 border border-neutral-300 rounded-xl shadow-sm text-sm font-medium text-neutral-700 bg-white hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                        >
                            <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                <path fill="#000000" d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                            Continue with Apple
                        </button>
                        @endif
                        
                        <!-- Twitter Signup -->
                        @if(config('firebase.authentication.social_providers.twitter', true))
                        <button 
                            onclick="window.firebaseAuthService.signInWithTwitter().then(data => { if (data.success) window.location.href = data.redirect; }).catch(err => window.firebaseAuthService.showError(err.message));" 
                            class="w-full inline-flex justify-center items-center px-4 py-3 border border-neutral-300 rounded-xl shadow-sm text-sm font-medium text-neutral-700 bg-white hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                        >
                            <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                <path fill="#1DA1F2" d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            Continue with Twitter
                        </button>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Login Link -->
                <div class="text-center mt-6">
                    <p class="text-neutral-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium animated-link">Login here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId) {
    var input = document.getElementById(fieldId);
    input.type = (input.type === "password") ? "text" : "password";
    var icon = document.getElementById(fieldId + '-toggle-icon');
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}

function formatPhoneNumber(event) {
    const input = event.target;
    let newValue = input.value.replace(/[^\d+]/g, '').replace(/\s+/g, '');
    if (!newValue.startsWith('+63')) {
        newValue = '+63' + newValue.replace('+63', '');
    }
    const raw = newValue.replace('+63', '');
    const part1 = raw.substring(0, 3);
    const part2 = raw.substring(3, 6);
    const part3 = raw.substring(6, 10);
    let formatted = '+63';
    if (part1) formatted += ' ' + part1;
    if (part2) formatted += ' ' + part2;
    if (part3) formatted += ' ' + part3;
    input.value = formatted;
}

// Referral code validation
let referralCodeTimeout;
const referralCodeInput = document.getElementById('referralCode');
const referralCodeStatus = document.getElementById('referralCodeStatus');
const referralCodeValid = document.getElementById('referralCodeValid');
const referralCodeInvalid = document.getElementById('referralCodeInvalid');
const referralCodeLoading = document.getElementById('referralCodeLoading');
const referralCodeMessage = document.getElementById('referralCodeMessage');

referralCodeInput.addEventListener('input', function() {
    const code = this.value.trim().toUpperCase();
    this.value = code;
    
    clearTimeout(referralCodeTimeout);
    
    if (code.length === 0) {
        referralCodeStatus.classList.add('hidden');
        referralCodeMessage.classList.add('hidden');
        return;
    }
    
    // Show loading
    referralCodeStatus.classList.remove('hidden');
    referralCodeValid.classList.add('hidden');
    referralCodeInvalid.classList.add('hidden');
    referralCodeLoading.classList.remove('hidden');
    referralCodeMessage.classList.add('hidden');
    
    referralCodeTimeout = setTimeout(() => {
        fetch('{{ route("client.referrals.validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            referralCodeLoading.classList.add('hidden');
            
            if (data.valid) {
                referralCodeValid.classList.remove('hidden');
                referralCodeMessage.textContent = `✓ Valid code from ${data.referrer_name}`;
                referralCodeMessage.className = 'mt-2 text-sm text-green-600 block';
            } else {
                referralCodeInvalid.classList.remove('hidden');
                referralCodeMessage.textContent = data.message || '✗ Invalid referral code';
                referralCodeMessage.className = 'mt-2 text-sm text-red-600 block';
            }
        })
        .catch(() => {
            referralCodeLoading.classList.add('hidden');
            referralCodeInvalid.classList.remove('hidden');
            referralCodeMessage.textContent = '✗ Unable to validate code';
            referralCodeMessage.className = 'mt-2 text-sm text-red-600 block';
        });
    }, 500);
});
</script>
@endpush

@push('scripts')
<script>
    // Add form validation and interactions
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        
        // Real-time password confirmation
        confirmPassword.addEventListener('input', function() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
        
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating Account...
            `;
            submitBtn.disabled = true;
        });
    });
</script>
@endpush

@push('scripts')
@vite(['resources/js/firebase-auth.js'])
@endpush
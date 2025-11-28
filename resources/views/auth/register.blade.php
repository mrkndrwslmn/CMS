@extends('layouts.public')

@section('title', 'Register - Treis Adiutor')

@section('content')
<div class="min-h-screen pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-center min-h-full">
        <div class="w-full max-w-6xl flex flex-col md:flex-row overflow-hidden rounded-2xl shadow-2xl">
        <!-- Left side (features) -->
        <div class="bg-primary-600 p-12 md:w-1/2 flex flex-col justify-center">
            <div class="space-y-8">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-3">Join Treis Adiutor</h2>
                    <p class="text-primary-100 text-lg">Create your account to access professional project management services.</p>
                </div>
                
                <div class="space-y-5 pt-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center">
                            <i class="fas fa-project-diagram text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Project Access</h3>
                            <p class="text-primary-200 text-sm">Manage all your projects and tasks in one place</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Collaboration</h3>
                            <p class="text-primary-200 text-sm">Update requirements and communicate with your team</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Progress Tracking</h3>
                            <p class="text-primary-200 text-sm">Monitor real-time project status and milestones</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center">
                            <i class="fas fa-bell text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-1">Smart Notifications</h3>
                            <p class="text-primary-200 text-sm">Stay informed with instant updates and alerts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right side (form) -->
        <div class="p-10 md:w-1/2 bg-white">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-primary-700 mb-2">Create Account</h2>
                <p class="text-neutral-600 text-sm">Fill in your details to get started</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                
                <!-- Full Name -->
                <div>
                    <label for="fullName" class="block text-sm font-medium text-primary-700 mb-1.5">Full Name</label>
                    <input 
                        type="text" 
                        id="fullName" 
                        name="fullName" 
                        placeholder="John Doe" 
                        required
                        value="{{ old('fullName') }}"
                        class="w-full px-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-lg text-primary-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all @error('fullName') border-red-400 focus:ring-red-400 @enderror"
                    >
                    @error('fullName')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-primary-700 mb-1.5">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="you@example.com" 
                        required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-lg text-primary-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all @error('email') border-red-400 focus:ring-red-400 @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phoneNumber" class="block text-sm font-medium text-primary-700 mb-1.5">Phone Number</label>
                    <input 
                        type="tel" 
                        id="phoneNumber" 
                        name="phoneNumber" 
                        placeholder="+63 917 123 4567" 
                        required
                        oninput="formatPhoneNumber(event)"
                        value="{{ old('phoneNumber') }}"
                        class="w-full px-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-lg text-primary-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all @error('phoneNumber') border-red-400 focus:ring-red-400 @enderror"
                    >
                    @error('phoneNumber')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Referral Code -->
                <div>
                    <label for="referralCode" class="block text-sm font-medium text-primary-700 mb-1.5">
                        Referral Code <span class="text-neutral-500 font-normal">(Optional)</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="referralCode" 
                            name="referralCode" 
                            placeholder="Enter code" 
                            value="{{ old('referralCode', request()->query('ref')) }}"
                            class="w-full px-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-lg text-primary-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all uppercase @error('referralCode') border-red-400 focus:ring-red-400 @enderror"
                        >
                        <div id="referralCodeStatus" class="hidden absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg id="referralCodeValid" class="hidden h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <svg id="referralCodeInvalid" class="hidden h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <svg id="referralCodeLoading" class="hidden animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <p id="referralCodeMessage" class="mt-1.5 text-xs hidden"></p>
                    @error('referralCode')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @if(request()->query('ref'))
                    <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg flex items-start gap-2">
                        <svg class="w-4 h-4 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-xs text-green-800">
                            <p class="font-semibold">Referral Applied!</p>
                            <p class="mt-0.5">Complete registration to receive: <strong>500 points + 15% off coupon</strong></p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-primary-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••" 
                            required
                            class="w-full px-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-lg text-primary-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all @error('password') border-red-400 focus:ring-red-400 @enderror"
                        >
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-500 hover:text-primary-700 transition-colors">
                            <i id="password-toggle-icon" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-primary-700 mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="••••••••" 
                            required
                            class="w-full px-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-lg text-primary-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all"
                        >
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-500 hover:text-primary-700 transition-colors">
                            <i id="password_confirmation-toggle-icon" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 mt-6"
                >
                    Create Account
                </button>
                
                <!-- Firebase Registration Options -->
                @if(config('firebase.authentication.enabled', true))
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-neutral-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-neutral-500">Or continue with</span>
                        </div>
                    </div>
                    
                    <div class="mt-5 grid grid-cols-1 gap-3">
                        @if(config('firebase.authentication.social_providers.google', true))
                        <button 
                            type="button"
                            onclick="window.firebaseAuthService.signInWithGoogle().then(data => { if (data.success) window.location.href = data.redirect; }).catch(err => window.firebaseAuthService.showError(err.message));" 
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-white border border-neutral-300 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-primary-400 transition-all"
                        >
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Continue with Google
                        </button>
                        @endif
                        
                        @if(config('firebase.authentication.social_providers.apple', true))
                        <button 
                            type="button"
                            onclick="window.firebaseAuthService.signInWithApple().then(data => { if (data.success) window.location.href = data.redirect; }).catch(err => window.firebaseAuthService.showError(err.message));" 
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-white border border-neutral-300 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-primary-400 transition-all"
                        >
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="#000000" d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                            </svg>
                            Continue with Apple
                        </button>
                        @endif
                        
                        @if(config('firebase.authentication.social_providers.twitter', true))
                        <button 
                            type="button"
                            onclick="window.firebaseAuthService.signInWithTwitter().then(data => { if (data.success) window.location.href = data.redirect; }).catch(err => window.firebaseAuthService.showError(err.message));" 
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-white border border-neutral-300 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-primary-400 transition-all"
                        >
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="#1DA1F2" d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            Continue with Twitter
                        </button>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Login Link -->
                <div class="text-center pt-4">
                    <p class="text-sm text-neutral-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">Sign in</a>
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
                referralCodeMessage.textContent = `Valid code from ${data.referrer_name}`;
                referralCodeMessage.className = 'mt-1.5 text-xs text-green-600 block';
            } else {
                referralCodeInvalid.classList.remove('hidden');
                referralCodeMessage.textContent = data.message || 'Invalid referral code';
                referralCodeMessage.className = 'mt-1.5 text-xs text-red-600 block';
            }
        })
        .catch(() => {
            referralCodeLoading.classList.add('hidden');
            referralCodeInvalid.classList.remove('hidden');
            referralCodeMessage.textContent = 'Unable to validate code';
            referralCodeMessage.className = 'mt-1.5 text-xs text-red-600 block';
        });
    }, 500);
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    
    confirmPassword.addEventListener('input', function() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
        } else {
            confirmPassword.setCustomValidity('');
        }
    });
    
    form.addEventListener('submit', function() {
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

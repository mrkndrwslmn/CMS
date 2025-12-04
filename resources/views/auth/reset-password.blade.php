@extends('layouts.public')

@section('title', 'Reset Password - Treis Adiutor')

@section('content')
<div class="min-h-screen bg-neutral-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="rounded-2xl overflow-hidden shadow-sm bg-white border border-neutral-100">
            <div class="p-8 md:p-12">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 mb-4">
                        <x-lucide-lock class="w-7 h-7 text-primary-600" />
                    </div>
                    <h1 class="text-2xl font-semibold text-neutral-800 mb-2">Reset Password</h1>
                    <p class="text-neutral-500 text-sm">Create a new secure password for your account.</p>
                </div>
                
                <!-- Error Message -->
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-error-50 border border-error-200">
                        <div class="flex items-start">
                            <x-lucide-alert-circle class="w-5 h-5 text-error-500 mt-0.5 mr-3 flex-shrink-0" />
                            <div class="flex-1">
                                @foreach($errors->all() as $error)
                                    <p class="text-error-700 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Hidden token and email -->
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <!-- Email (display only) -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">Email Address</label>
                        <div class="w-full px-4 py-2.5 text-sm rounded-lg border border-neutral-200 bg-neutral-50 text-neutral-600">
                            {{ $email }}
                        </div>
                    </div>
                    
                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-neutral-700 mb-1.5">New Password</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Enter your new password" 
                                required
                                minlength="8"
                                class="w-full px-4 py-2.5 text-sm rounded-lg border border-neutral-200 bg-white text-neutral-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all @error('password') border-error-500 bg-error-50 focus:ring-error-500/20 @enderror"
                            >
                            <button 
                                type="button" 
                                class="absolute inset-y-0 right-0 px-4 flex items-center text-neutral-400 hover:text-neutral-600 transition-colors focus:outline-none"
                                onclick="togglePassword('password', 'toggleIcon1')"
                            >
                                <x-lucide-eye id="toggleIcon1Eye" class="w-4 h-4" />
                                <x-lucide-eye-off id="toggleIcon1EyeOff" class="w-4 h-4 hidden" />
                            </button>
                        </div>
                        <p class="mt-1.5 text-xs text-neutral-500">Must be at least 8 characters long</p>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-1.5">Confirm New Password</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="Confirm your new password" 
                                required
                                minlength="8"
                                class="w-full px-4 py-2.5 text-sm rounded-lg border border-neutral-200 bg-white text-neutral-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                            >
                            <button 
                                type="button" 
                                class="absolute inset-y-0 right-0 px-4 flex items-center text-neutral-400 hover:text-neutral-600 transition-colors focus:outline-none"
                                onclick="togglePassword('password_confirmation', 'toggleIcon2')"
                            >
                                <x-lucide-eye id="toggleIcon2Eye" class="w-4 h-4" />
                                <x-lucide-eye-off id="toggleIcon2EyeOff" class="w-4 h-4 hidden" />
                            </button>
                        </div>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div id="password-strength" class="hidden">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex-1 h-2 rounded-full bg-neutral-200 overflow-hidden">
                                <div id="strength-bar" class="h-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <span id="strength-text" class="text-xs font-medium"></span>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all"
                        id="submit-btn"
                    >
                        Reset Password
                    </button>
                    
                    <!-- Back to Login -->
                    <div class="text-center pt-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                            <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId, iconPrefix) {
    const pwd = document.getElementById(inputId);
    const iconEye = document.getElementById(iconPrefix + 'Eye');
    const iconEyeOff = document.getElementById(iconPrefix + 'EyeOff');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        iconEye.classList.add('hidden');
        iconEyeOff.classList.remove('hidden');
    } else {
        pwd.type = 'password';
        iconEye.classList.remove('hidden');
        iconEyeOff.classList.add('hidden');
    }
}

window.togglePassword = togglePassword;

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('password-strength');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    
    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length === 0) {
            strengthIndicator.classList.add('hidden');
            return;
        }
        
        strengthIndicator.classList.remove('hidden');
        
        let strength = 0;
        let color = '';
        let text = '';
        
        // Check password strength
        if (password.length >= 8) strength += 25;
        if (password.length >= 12) strength += 25;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
        if (/[0-9]/.test(password)) strength += 12.5;
        if (/[^a-zA-Z0-9]/.test(password)) strength += 12.5;
        
        if (strength < 40) {
            color = 'bg-error-500';
            text = 'Weak';
        } else if (strength < 70) {
            color = 'bg-warning-500';
            text = 'Medium';
        } else {
            color = 'bg-success-500';
            text = 'Strong';
        }
        
        strengthBar.style.width = strength + '%';
        strengthBar.className = 'h-full transition-all duration-300 ' + color;
        strengthText.textContent = text;
        strengthText.className = 'text-xs font-medium ' + (color === 'bg-error-500' ? 'text-error-600' : color === 'bg-warning-500' ? 'text-warning-600' : 'text-success-600');
    });
    
    // Form submission
    form.addEventListener('submit', function() {
        submitBtn.innerHTML = `
            <div class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Resetting Password...</span>
            </div>
        `;
        submitBtn.disabled = true;
    });
});
</script>
@endpush

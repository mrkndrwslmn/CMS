@extends('layouts.public')

@section('title', 'Reset Password - Treis Adiutor')

@section('content')
<div class="min-h-screen bg-primary-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="rounded-2xl overflow-hidden shadow-lg bg-white border border-primary-100">
            <div class="p-8 md:p-12">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 mb-4">
                        <i class="fas fa-lock text-primary-600 text-2xl"></i>
                    </div>
                    <h1 class="heading-serif text-3xl font-bold text-primary-900 mb-2">Reset Password</h1>
                    <p class="text-primary-600 text-sm">Create a new secure password for your account.</p>
                </div>
                
                <!-- Error Message -->
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                            <div class="flex-1">
                                @foreach($errors->all() as $error)
                                    <p class="text-red-700 text-sm">{{ $error }}</p>
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
                        <label class="block text-sm font-semibold text-primary-900 mb-2">Email Address</label>
                        <div class="w-full px-4 py-3 text-sm rounded-lg border border-primary-200 bg-gray-100 text-primary-700">
                            {{ $email }}
                        </div>
                    </div>
                    
                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-primary-900 mb-2">New Password</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="••••••••" 
                                required
                                minlength="8"
                                class="w-full px-4 py-3 text-sm rounded-lg border border-primary-200 bg-primary-50 text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 bg-red-50 focus:ring-red-500 @enderror"
                            >
                            <button 
                                type="button" 
                                class="absolute inset-y-0 right-0 px-4 flex items-center text-primary-500 hover:text-primary-700 transition-colors focus:outline-none"
                                onclick="togglePassword('password', 'toggleIcon1')"
                            >
                                <i id="toggleIcon1" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-primary-600">Must be at least 8 characters long</p>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-primary-900 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                placeholder="••••••••" 
                                required
                                minlength="8"
                                class="w-full px-4 py-3 text-sm rounded-lg border border-primary-200 bg-primary-50 text-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                            >
                            <button 
                                type="button" 
                                class="absolute inset-y-0 right-0 px-4 flex items-center text-primary-500 hover:text-primary-700 transition-colors focus:outline-none"
                                onclick="togglePassword('password_confirmation', 'toggleIcon2')"
                            >
                                <i id="toggleIcon2" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div id="password-strength" class="hidden">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex-1 h-2 rounded-full bg-gray-200 overflow-hidden">
                                <div id="strength-bar" class="h-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <span id="strength-text" class="text-xs font-medium"></span>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-primary w-full py-3"
                        id="submit-btn"
                    >
                        Reset Password
                    </button>
                    
                    <!-- Back to Login -->
                    <div class="text-center pt-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>
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
function togglePassword(inputId, iconId) {
    const pwd = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
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
            color = 'bg-red-500';
            text = 'Weak';
        } else if (strength < 70) {
            color = 'bg-yellow-500';
            text = 'Medium';
        } else {
            color = 'bg-green-500';
            text = 'Strong';
        }
        
        strengthBar.style.width = strength + '%';
        strengthBar.className = 'h-full transition-all duration-300 ' + color;
        strengthText.textContent = text;
        strengthText.className = 'text-xs font-medium ' + (color === 'bg-red-500' ? 'text-red-600' : color === 'bg-yellow-500' ? 'text-yellow-600' : 'text-green-600');
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

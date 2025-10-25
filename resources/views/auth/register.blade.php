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
@extends('layouts.public')

@section('title', 'Login - Treis Adiutor')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="glass-card soft-shadow w-full max-w-6xl flex flex-col md:flex-row overflow-hidden">
        <!-- Left side (image/features) -->
        <div class="bg-gradient-to-br from-primary-600 to-primary-800 text-white p-8 md:w-1/2">
            <div class="h-full flex flex-col justify-center">
                <h2 class="heading-serif text-3xl mb-4">Welcome Back</h2>
                <p class="text-lg mb-8 text-primary-50">Log in to your account to see and manage your tasks and projects.</p>
                
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
        
        <!-- Right side (login form) -->
        <div class="p-8 md:w-1/2 bg-white">
            <h2 class="heading-serif text-3xl text-primary-700 mb-8 text-center">Sign In</h2>
            
            <form method="POST" action="{{ route('login') }}" class="smooth-transition">
                @csrf
                
                <!-- Email -->
                <div class="space-y-2 mb-6">
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
                
                <!-- Password -->
                <div class="space-y-2 mb-6">
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
                        <button 
                            type="button" 
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-neutral-500 hover:text-neutral-700 transition-colors"
                            onclick="togglePassword()"
                        >
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-neutral-700">
                            Remember me
                        </label>
                    </div>
                    <div>
                        <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium animated-link">Forgot password?</a>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn-primary w-full"
                >
                    Sign In
                </button>
                
                <!-- Register Link -->
                <div class="text-center mt-6">
                    <p class="text-neutral-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium animated-link">Sign up</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush

@push('scripts')
<script>
    // Add some nice form interactions
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Signing In...
            `;
            submitBtn.disabled = true;
        });
    });
</script>
@endpush
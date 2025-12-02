@extends('layouts.public')

@section('title', 'Forgot Password - Treis Adiutor')

@section('content')
<div class="min-h-screen bg-primary-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="rounded-2xl overflow-hidden shadow-lg bg-white border border-primary-100">
            <div class="p-8 md:p-12">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 mb-4">
                        <i class="fas fa-key text-primary-600 text-2xl"></i>
                    </div>
                    <h1 class="heading-serif text-3xl font-bold text-primary-900 mb-2">Forgot Password?</h1>
                    <p class="text-primary-600 text-sm">No worries! Enter your email and we'll send you reset instructions.</p>
                </div>
                
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                            <p class="text-green-700 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
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
                
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-primary w-full py-3"
                        id="submit-btn"
                    >
                        Send Reset Link
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
        
        <!-- Additional Help -->
        <div class="mt-6 text-center">
            <p class="text-primary-600 text-sm">
                Still having trouble? 
                <a href="mailto:support@treisadiutor.com" class="text-primary-700 hover:text-primary-800 font-semibold transition-colors">Contact Support</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    
    form.addEventListener('submit', function() {
        submitBtn.innerHTML = `
            <div class="flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Sending...</span>
            </div>
        `;
        submitBtn.disabled = true;
    });
});
</script>
@endpush

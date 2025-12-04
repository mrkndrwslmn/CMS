@extends('layouts.public')

@section('title', 'Forgot Password - Treis Adiutor')

@section('content')
<div class="min-h-screen bg-neutral-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="rounded-2xl overflow-hidden shadow-sm bg-white border border-neutral-100">
            <div class="p-8 md:p-12">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 mb-4">
                        <x-lucide-key-round class="w-7 h-7 text-primary-600" />
                    </div>
                    <h1 class="text-2xl font-semibold text-neutral-800 mb-2">Forgot Password?</h1>
                    <p class="text-neutral-500 text-sm">No worries! Enter your email and we'll send you reset instructions.</p>
                </div>
                
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-success-50 border border-success-200">
                        <div class="flex items-start">
                            <x-lucide-check-circle class="w-5 h-5 text-success-500 mt-0.5 mr-3 flex-shrink-0" />
                            <p class="text-success-700 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
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
                
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700 mb-1.5">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="you@example.com" 
                            required
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-neutral-200 bg-white text-neutral-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all @error('email') border-error-500 bg-error-50 focus:ring-error-500/20 @enderror"
                        >
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all"
                        id="submit-btn"
                    >
                        Send Reset Link
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
        
        <!-- Additional Help -->
        <div class="mt-6 text-center">
            <p class="text-neutral-500 text-sm">
                Still having trouble? 
                <a href="mailto:support@treisadiutor.com" class="text-primary-600 hover:text-primary-700 font-medium transition-colors">Contact Support</a>
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

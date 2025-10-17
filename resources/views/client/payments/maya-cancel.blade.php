@extends('client.layout')

@section('title', 'Payment Cancelled')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Warning Icon and Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-warning-100 rounded-full mb-6">
            <svg class="w-12 h-12 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        
        <h1 class="text-4xl font-bold text-neutral-900 mb-3">Payment Cancelled</h1>
        <p class="text-lg text-neutral-600">You have cancelled the payment process.</p>
    </div>

    <!-- Cancellation Details Card -->
    <div class="bg-white rounded-xl shadow-lg border border-warning-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-warning-500 to-warning-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Transaction Cancelled</h2>
            <p class="text-warning-100 mt-1">{{ now()->format('F j, Y \a\t g:i A') }}</p>
        </div>
        
        <div class="p-8">
            @if($referenceNumber)
            <div class="mb-6">
                <dt class="text-sm font-medium text-neutral-500 mb-1">Reference Number</dt>
                <dd class="text-sm font-mono text-neutral-700 bg-neutral-50 px-3 py-2 rounded border border-neutral-200 inline-block">
                    {{ $referenceNumber }}
                </dd>
            </div>
            @endif

            <div class="bg-warning-50 border border-warning-200 rounded-lg p-6">
                <h3 class="font-semibold text-warning-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    What Happened?
                </h3>
                <p class="text-warning-800">You chose to cancel the payment process. No charges have been made to your account.</p>
                <p class="text-warning-800 mt-2">Your service request is still pending payment and you can complete the payment whenever you're ready.</p>
            </div>
        </div>
    </div>

    <!-- Information Section -->
    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-8 mb-6 border border-primary-200">
        <h3 class="text-2xl font-bold text-neutral-900 mb-4 flex items-center">
            <svg class="w-7 h-7 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Next Steps
        </h3>
        
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    1
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Review Your Request</h4>
                    <p class="text-neutral-700">Your service request is safe and hasn't been affected by this cancellation.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    2
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Complete Payment When Ready</h4>
                    <p class="text-neutral-700">You can return to your service request and complete the payment whenever you're ready.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    3
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Questions?</h4>
                    <p class="text-neutral-700">If you have any questions or concerns, our support team is here to help.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('client.requests') }}" class="btn-primary text-center">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Complete Payment
        </a>
        
        <a href="{{ route('client.dashboard') }}" class="btn-secondary text-center">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Support Section -->
    <div class="text-center mt-12 pt-8 border-t border-neutral-200">
        <p class="text-neutral-600 mb-2">
            Changed your mind or need assistance?
        </p>
        <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">
            Contact our support team →
        </a>
    </div>
</div>
@endsection

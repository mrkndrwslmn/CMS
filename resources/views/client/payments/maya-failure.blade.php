@extends('client.layout')

@section('title', 'Payment Failed')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Error Icon and Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-error-100 rounded-full mb-6">
            <svg class="w-12 h-12 text-error-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        
        <h1 class="text-4xl font-bold text-neutral-900 mb-3">Payment Failed</h1>
        <p class="text-lg text-neutral-600">Unfortunately, your payment could not be processed.</p>
    </div>

    <!-- Error Details Card -->
    <div class="bg-white rounded-xl shadow-lg border border-error-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-error-500 to-error-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Transaction Failed</h2>
            <p class="text-error-100 mt-1">{{ now()->format('F j, Y \a\t g:i A') }}</p>
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

            <div class="bg-error-50 border border-error-200 rounded-lg p-6">
                <h3 class="font-semibold text-error-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    What went wrong?
                </h3>
                <p class="text-error-800">Your payment was not completed. This could be due to:</p>
                <ul class="list-disc list-inside mt-3 space-y-2 text-error-800">
                    <li>Insufficient funds in your account</li>
                    <li>Card declined by your bank</li>
                    <li>Incorrect payment details</li>
                    <li>Network connection issues</li>
                    <li>Payment timeout</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- What to Do Next -->
    <div class="bg-gradient-to-br from-neutral-50 to-neutral-100 rounded-xl p-8 mb-6 border border-neutral-200">
        <h3 class="text-2xl font-bold text-neutral-900 mb-4 flex items-center">
            <svg class="w-7 h-7 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            What to Do Next
        </h3>
        
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    1
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Check Your Payment Details</h4>
                    <p class="text-neutral-700">Verify that your card information and billing details are correct.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    2
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Contact Your Bank</h4>
                    <p class="text-neutral-700">If the issue persists, contact your bank to ensure there are no restrictions on your card.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    3
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Try Again</h4>
                    <p class="text-neutral-700">Once resolved, you can retry the payment from your service request page.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('client.requests') }}" class="btn-primary text-center">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Try Payment Again
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
            Need help with your payment?
        </p>
        <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">
            Contact our support team →
        </a>
    </div>
</div>
@endsection

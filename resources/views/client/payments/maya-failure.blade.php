@extends('client.layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Error Icon and Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-error-50 rounded-full mb-6">
            <x-lucide-x class="w-12 h-12 text-error-500" />
        </div>
        
        <h1 class="text-3xl font-bold text-neutral-800 mb-3">Payment Failed</h1>
        <p class="text-lg text-neutral-500">Unfortunately, your payment could not be processed.</p>
    </div>

    <!-- Error Details Card -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden mb-6">
        <div class="bg-error-600 px-8 py-6">
            <h2 class="text-2xl font-semibold text-white">Transaction Failed</h2>
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

            <div class="bg-error-50 border border-error-100 rounded-xl p-6">
                <h3 class="font-semibold text-error-800 mb-3 flex items-center">
                    <x-lucide-alert-triangle class="w-5 h-5 mr-2" />
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
    <div class="bg-neutral-50 rounded-2xl p-8 mb-6 border border-neutral-100">
        <h3 class="text-xl font-semibold text-neutral-800 mb-6 flex items-center">
            <x-lucide-check-circle class="w-6 h-6 mr-3 text-primary-500" />
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
        <a href="{{ route('client.requests') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
            <x-lucide-refresh-cw class="w-5 h-5" />
            Try Payment Again
        </a>
        
        <a href="{{ route('client.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 rounded-lg font-medium transition-colors">
            <x-lucide-layout-dashboard class="w-5 h-5" />
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

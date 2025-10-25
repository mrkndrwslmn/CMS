@extends('client.layout')

@section('title', 'Payment Successful')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Success Icon and Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-success-100 rounded-full mb-6">
            <svg class="w-12 h-12 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        
        <h1 class="text-4xl font-bold text-neutral-900 mb-3">Payment Successful!</h1>
        <p class="text-lg text-neutral-600">Your payment has been confirmed and your project is ready to start.</p>
    </div>

    <!-- Payment Details Card -->
    <div class="bg-white rounded-xl shadow-lg border border-neutral-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-success-500 to-success-600 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Payment Confirmation</h2>
            <p class="text-success-100 mt-1">Transaction completed on {{ now()->format('F j, Y \a\t g:i A') }}</p>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Name -->
                <div>
                    <dt class="text-sm font-medium text-neutral-500 mb-1">Project Name</dt>
                    <dd class="text-lg font-semibold text-neutral-900">{{ $serviceRequest->project_name }}</dd>
                </div>

                <!-- Amount Paid -->
                <div>
                    <dt class="text-sm font-medium text-neutral-500 mb-1">Amount Paid</dt>
                    <dd class="text-lg font-semibold text-success-600">₱{{ number_format($payment->amount, 2) }}</dd>
                </div>

                <!-- Payment Method -->
                <div>
                    <dt class="text-sm font-medium text-neutral-500 mb-1">Payment Method</dt>
                    <dd class="text-lg font-medium text-neutral-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Maya Payment Gateway
                    </dd>
                </div>

                <!-- Reference Number -->
                <div>
                    <dt class="text-sm font-medium text-neutral-500 mb-1">Reference Number</dt>
                    <dd class="text-sm font-mono text-neutral-700 bg-neutral-50 px-3 py-2 rounded border border-neutral-200">
                        {{ $payment->payment_reference }}
                    </dd>
                </div>

                @if($payment->transaction_id)
                <!-- Transaction ID -->
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-neutral-500 mb-1">Transaction ID</dt>
                    <dd class="text-sm font-mono text-neutral-700 bg-neutral-50 px-3 py-2 rounded border border-neutral-200">
                        {{ $payment->transaction_id }}
                    </dd>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- What's Next Section -->
    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-8 mb-6 border border-primary-200">
        <h3 class="text-2xl font-bold text-neutral-900 mb-4 flex items-center">
            <svg class="w-7 h-7 mr-3 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            What Happens Next?
        </h3>
        
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    1
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Project Creation</h4>
                    <p class="text-neutral-700">Your service request has been automatically converted into a project.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    2
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Team Assignment</h4>
                    <p class="text-neutral-700">Our admin will assign the best adiutors to work on your project.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    3
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Project Kickoff</h4>
                    <p class="text-neutral-700">You'll receive updates as your project progresses.</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                    4
                </div>
                <div>
                    <h4 class="font-semibold text-neutral-900 mb-1">Stay Updated</h4>
                    <p class="text-neutral-700">Track your project progress from your dashboard.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Confirmation Notice -->
    <div class="bg-info-50 border border-info-200 rounded-lg p-6 mb-8">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-info-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <div>
                <h4 class="font-semibold text-info-900 mb-1">Confirmation Email Sent</h4>
                <p class="text-info-800">A payment confirmation email has been sent to your registered email address. Please check your inbox (and spam folder) for the receipt.</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('client.dashboard') }}" class="btn-primary text-center">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Go to Dashboard
        </a>
        
        <a href="{{ route('client.requests') }}" class="btn-secondary text-center">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            View My Requests
        </a>
    </div>

    <!-- Support Section -->
    <div class="text-center mt-12 pt-8 border-t border-neutral-200">
        <p class="text-neutral-600 mb-2">
            Need help or have questions?
        </p>
        <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">
            Contact our support team →
        </a>
    </div>
</div>
@endsection

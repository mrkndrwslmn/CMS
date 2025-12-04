@extends('client.layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Success Icon and Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-success-50 rounded-full mb-6">
            <x-lucide-check class="w-12 h-12 text-success-500" />
        </div>
        
        <h1 class="text-3xl font-bold text-neutral-800 mb-3">Payment Successful!</h1>
        <p class="text-lg text-neutral-500">Your payment has been confirmed and your project is ready to start.</p>
    </div>

    <!-- Payment Details Card -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden mb-6">
        <div class="bg-success-600 px-8 py-6">
            <h2 class="text-2xl font-semibold text-white">Payment Confirmation</h2>
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
                    <dd class="text-lg font-medium text-neutral-800 flex items-center">
                        <x-lucide-credit-card class="w-5 h-5 mr-2 text-primary-500" />
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
    <div class="bg-neutral-50 rounded-2xl p-8 mb-6 border border-neutral-100">
        <h3 class="text-xl font-semibold text-neutral-800 mb-6 flex items-center">
            <x-lucide-trending-up class="w-6 h-6 mr-3 text-primary-500" />
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
    <div class="bg-primary-50 border border-primary-100 rounded-xl p-6 mb-8">
        <div class="flex items-start">
            <x-lucide-mail class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-0.5" />
            <div>
                <h4 class="font-semibold text-neutral-800 mb-1">Confirmation Email Sent</h4>
                <p class="text-neutral-600">A payment confirmation email has been sent to your registered email address. Please check your inbox (and spam folder) for the receipt.</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('client.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
            <x-lucide-layout-dashboard class="w-5 h-5" />
            Go to Dashboard
        </a>
        
        <a href="{{ route('client.requests') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 rounded-lg font-medium transition-colors">
            <x-lucide-file-text class="w-5 h-5" />
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

@extends('client.layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-neutral-50 to-primary-50/30">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm border-b border-neutral-200/60">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('client.payments.history') }}" 
                       class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-neutral-100 hover:bg-neutral-200 text-neutral-600 hover:text-neutral-800 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-semibold text-neutral-900 mb-1">Payment Details</h1>
                        <p class="text-neutral-600 text-sm">Reference: {{ $payment->payment_reference }}</p>
                    </div>
                </div>
                @if($payment->status === 'confirmed')
                    <a href="{{ route('client.payments.receipt', $payment->id) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Receipt
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="max-w-4xl mx-auto px-6 py-8">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl border border-neutral-200/60 shadow-sm overflow-hidden">
            <!-- Status Header -->
            <div class="p-6 border-b border-neutral-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        @php
                            $statusConfig = [
                                'confirmed' => ['bg' => 'bg-success-100', 'text' => 'text-success-800', 'dot' => 'bg-success-500', 'label' => 'Payment Confirmed', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-800', 'dot' => 'bg-warning-500', 'label' => 'Payment Pending', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'failed' => ['bg' => 'bg-error-100', 'text' => 'text-error-800', 'dot' => 'bg-error-500', 'label' => 'Payment Failed', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ];
                            $config = $statusConfig[$payment->status] ?? ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'dot' => 'bg-neutral-500', 'label' => ucfirst($payment->status), 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'];
                        @endphp
                        <div class="w-16 h-16 {{ $config['bg'] }} rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 {{ $config['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-neutral-900">{{ $config['label'] }}</h2>
                            <p class="text-neutral-600">{{ $payment->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-neutral-900">₱{{ number_format($payment->amount, 2) }}</p>
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <div class="w-2 h-2 rounded-full {{ $config['dot'] }}"></div>
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Payment Details -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Payment Information</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-neutral-600">Payment Reference</span>
                                    <span class="font-medium text-neutral-900">{{ $payment->payment_reference }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-neutral-600">Payment Method</span>
                                    <span class="font-medium text-neutral-900">{{ $payment->payment_method ?? 'Online Payment' }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-neutral-600">Amount</span>
                                    <span class="font-medium text-neutral-900">₱{{ number_format($payment->amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-neutral-600">Status</span>
                                    <span class="font-medium text-neutral-900">{{ ucfirst($payment->status) }}</span>
                                </div>
                                <div class="flex justify-between py-3 border-b border-neutral-100">
                                    <span class="text-neutral-600">Transaction Date</span>
                                    <span class="font-medium text-neutral-900">{{ $payment->created_at->format('M d, Y g:i A') }}</span>
                                </div>
                                @if($payment->confirmed_at)
                                    <div class="flex justify-between py-3 border-b border-neutral-100">
                                        <span class="text-neutral-600">Confirmed Date</span>
                                        <span class="font-medium text-neutral-900">{{ $payment->confirmed_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Project Details -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Project Information</h3>
                            @if($payment->serviceRequest)
                                <div class="bg-gradient-to-r from-primary-50/50 to-accent-50/30 rounded-xl p-6 border border-primary-100/50">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-neutral-900 mb-2">{{ $payment->serviceRequest->project_name }}</h4>
                                            <p class="text-sm text-neutral-600 mb-3">Project #{{ $payment->service_request_id }}</p>
                                            @if($payment->serviceRequest->description)
                                                <p class="text-sm text-neutral-700 line-clamp-3">{{ $payment->serviceRequest->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-neutral-50 rounded-xl p-6 border border-neutral-200">
                                    <p class="text-neutral-600 text-center">Project information not available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                @if($payment->notes)
                    <div class="mt-8 pt-6 border-t border-neutral-100">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">Additional Notes</h3>
                        <div class="bg-neutral-50 rounded-xl p-4">
                            <p class="text-neutral-700">{{ $payment->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="mt-8 pt-6 border-t border-neutral-100 flex justify-between items-center">
                    <a href="{{ route('client.payments.history') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 text-neutral-600 hover:text-neutral-800 hover:bg-neutral-100 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Payment History
                    </a>

                    @if($payment->status === 'confirmed')
                        <a href="{{ route('client.payments.receipt', $payment->id) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-accent-600 to-secondary-600 hover:from-accent-700 hover:to-secondary-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H9.5a2 2 0 01-2-2V5a2 2 0 00-2-2H3a2 2 0 00-2 2v4a2 2 0 002 2h2.5a2 2 0 012 2v4a2 2 0 002 2H17z"></path>
                            </svg>
                            View Receipt
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
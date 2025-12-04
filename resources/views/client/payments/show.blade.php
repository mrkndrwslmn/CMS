@extends('client.layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Payment History', 'route' => 'client.payments.history', 'icon' => 'credit-card'],
        ['label' => 'Payment Details', 'icon' => 'receipt'],
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Payment Details</h1>
                <p class="text-sm text-neutral-500 mt-1">Reference: {{ $payment->payment_reference }}</p>
            </div>
            @if($payment->status === 'confirmed')
                <a href="{{ route('client.payments.receipt', $payment->id) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                    <x-lucide-download class="w-4 h-4" />
                    Download Receipt
                </a>
            @endif
        </div>
    </div>

    <!-- Payment Details Card -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        <!-- Status Header -->
        <div class="p-6 border-b border-neutral-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    @php
                        $statusConfig = [
                            'confirmed' => ['bg' => 'bg-success-50', 'text' => 'text-success-600', 'dot' => 'bg-success-500', 'label' => 'Payment Confirmed', 'icon' => 'check-circle'],
                            'pending' => ['bg' => 'bg-warning-50', 'text' => 'text-warning-600', 'dot' => 'bg-warning-500', 'label' => 'Payment Pending', 'icon' => 'clock'],
                            'failed' => ['bg' => 'bg-error-50', 'text' => 'text-error-600', 'dot' => 'bg-error-500', 'label' => 'Payment Failed', 'icon' => 'x-circle'],
                        ];
                        $config = $statusConfig[$payment->status] ?? ['bg' => 'bg-neutral-50', 'text' => 'text-neutral-600', 'dot' => 'bg-neutral-500', 'label' => ucfirst($payment->status), 'icon' => 'circle'];
                    @endphp
                    <div class="w-16 h-16 {{ $config['bg'] }} rounded-2xl flex items-center justify-center">
                        @if($payment->status === 'confirmed')
                            <x-lucide-check-circle class="w-8 h-8 {{ $config['text'] }}" />
                        @elseif($payment->status === 'pending')
                            <x-lucide-clock class="w-8 h-8 {{ $config['text'] }}" />
                        @elseif($payment->status === 'failed')
                            <x-lucide-x-circle class="w-8 h-8 {{ $config['text'] }}" />
                        @else
                            <x-lucide-circle class="w-8 h-8 {{ $config['text'] }}" />
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-neutral-800">{{ $config['label'] }}</h2>
                        <p class="text-neutral-500">{{ $payment->created_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-neutral-800">₱{{ number_format($payment->amount, 2) }}</p>
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
                            <h3 class="text-lg font-semibold text-neutral-800 mb-4">Payment Information</h3>
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
                            <h3 class="text-lg font-semibold text-neutral-800 mb-4">Project Information</h3>
                            @if($payment->serviceRequest)
                                <div class="bg-neutral-50 rounded-xl p-6 border border-neutral-100">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <x-lucide-building-2 class="w-6 h-6 text-primary-500" />
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-neutral-800 mb-2">{{ $payment->serviceRequest->project_name }}</h4>
                                            <p class="text-sm text-neutral-500 mb-3">Project #{{ $payment->service_request_id }}</p>
                                            @if($payment->serviceRequest->description)
                                                <p class="text-sm text-neutral-600 line-clamp-3">{{ $payment->serviceRequest->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-neutral-50 rounded-xl p-6 border border-neutral-100">
                                    <p class="text-neutral-500 text-center">Project information not available</p>
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
                       class="inline-flex items-center gap-2 px-4 py-2 text-neutral-600 hover:text-neutral-800 hover:bg-neutral-100 rounded-lg transition-colors">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Back to Payment History
                    </a>

                    @if($payment->status === 'confirmed')
                        <a href="{{ route('client.payments.receipt', $payment->id) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                            <x-lucide-file-text class="w-4 h-4" />
                            View Receipt
                        </a>
                    @endif
                </div>
            </div>
        </div>
</div>
@endsection
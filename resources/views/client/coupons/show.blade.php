@extends('client.layouts.app')

@section('title', $coupon->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('client.coupons.index') }}" class="inline-flex items-center text-neutral-600 hover:text-primary-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Coupons
        </a>
    </div>

    <!-- Coupon Details Card -->
    <div class="bg-gradient-to-br from-white to-primary-50 border-2 border-primary-200 rounded-2xl shadow-lg overflow-hidden mb-8">
        <!-- Header Section -->
        <div class="relative bg-gradient-to-r from-primary-500 to-accent-600 px-8 py-12 text-center">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="absolute top-4 left-4 w-20 h-20 border-4 border-white rounded-full"></div>
                <div class="absolute bottom-4 right-4 w-32 h-32 border-4 border-white rounded-full"></div>
            </div>
            
            <div class="relative z-10">
                <h1 class="text-4xl font-bold text-white mb-2">{{ $coupon->name }}</h1>
                @if($coupon->description)
                <p class="text-primary-100 text-lg max-w-2xl mx-auto">{{ $coupon->description }}</p>
                @endif
            </div>
        </div>

        <!-- Discount Display -->
        <div class="relative -mt-8 px-8">
            <div class="bg-white rounded-xl shadow-xl border-2 border-primary-200 p-8 text-center">
                <div class="flex items-center justify-center gap-8">
                    <!-- Discount Amount -->
                    <div>
                        <p class="text-sm text-neutral-600 mb-2 uppercase tracking-wide">Save</p>
                        <div class="text-5xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
                            {{ $coupon->getDiscountLabel() }}
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="h-20 w-px bg-neutral-200"></div>

                    <!-- Coupon Code -->
                    <div>
                        <p class="text-sm text-neutral-600 mb-2 uppercase tracking-wide">Code</p>
                        <div class="bg-primary-50 border-2 border-dashed border-primary-300 rounded-lg px-6 py-3 mb-3">
                            <p class="font-mono text-3xl font-bold text-primary-700 tracking-wider">{{ $coupon->code }}</p>
                        </div>
                        <button onclick="copyCouponCode('{{ $coupon->code }}')" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-medium text-sm rounded-lg hover:shadow-md transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Copy Code
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="px-8 py-8">
            <h2 class="text-xl font-semibold text-neutral-900 mb-4">Terms & Conditions</h2>
            <div class="grid md:grid-cols-2 gap-4">
                @if($coupon->min_purchase_amount > 0)
                <div class="flex items-start gap-3 bg-white rounded-lg border border-neutral-200 p-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-secondary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-secondary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-neutral-900">Minimum Purchase</p>
                        <p class="text-sm text-neutral-600">₱{{ number_format($coupon->min_purchase_amount, 2) }}</p>
                    </div>
                </div>
                @endif

                @if($coupon->max_discount_amount)
                <div class="flex items-start gap-3 bg-white rounded-lg border border-neutral-200 p-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-warning-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-warning-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-neutral-900">Maximum Discount</p>
                        <p class="text-sm text-neutral-600">₱{{ number_format($coupon->max_discount_amount, 2) }}</p>
                    </div>
                </div>
                @endif

                @if($coupon->valid_until)
                <div class="flex items-start gap-3 bg-white rounded-lg border border-neutral-200 p-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-error-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-error-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-neutral-900">Valid Until</p>
                        <p class="text-sm text-neutral-600">{{ $coupon->valid_until->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif

                <div class="flex items-start gap-3 bg-white rounded-lg border border-neutral-200 p-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-success-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-neutral-900">Usage Limit</p>
                        <p class="text-sm text-neutral-600">{{ $coupon->max_uses_per_user }} use{{ $coupon->max_uses_per_user > 1 ? 's' : '' }} per customer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage History -->
    @if($userUsages->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
        <h2 class="text-xl font-semibold text-neutral-900 mb-6">Your Usage History</h2>
        
        <div class="space-y-4">
            @foreach($userUsages as $usage)
            <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-lg border border-neutral-200 hover:border-primary-300 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-neutral-900">{{ $usage->serviceRequest->project_name }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-sm text-neutral-600">{{ $usage->used_at->format('M d, Y h:i A') }}</span>
                            <span class="text-sm font-semibold text-success-600">-₱{{ number_format($usage->discount_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    @if($usage->payment_status === 'completed')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Completed
                        </span>
                    @elseif($usage->payment_status === 'pending')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                            <svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Pending
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-error-100 text-error-700">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Failed
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-8 text-center">
        <div class="w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-neutral-900 mb-2">No Usage History</h3>
        <p class="text-neutral-600">You haven't used this coupon yet</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
function copyCouponCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        // Show success toast
        const toast = document.createElement('div');
        toast.className = 'fixed top-20 right-5 z-50 flex items-center gap-3 bg-success-600 text-white px-6 py-3 rounded-lg shadow-lg';
        toast.innerHTML = `
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">Coupon code "${code}" copied!</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }).catch(err => {
        console.error('Failed to copy:', err);
        alert('Failed to copy coupon code. Please try again.');
    });
}
</script>
@endpush
@endsection

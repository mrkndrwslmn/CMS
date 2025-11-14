@extends('client.layouts.app')

@section('title', 'Available Coupons')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900">My Coupons</h1>
        <p class="text-neutral-600 mt-2">Save on your projects with exclusive discount coupons</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Available Coupons</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $stats['total_coupons_available'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-success-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Coupons Used</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $stats['total_coupons_used'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Total Savings</p>
                    <p class="text-2xl font-semibold text-neutral-900">₱{{ number_format($stats['total_savings'], 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Coupons -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-neutral-900">Available for You</h2>
            <span class="text-sm text-neutral-600">{{ $availableCoupons->count() }} coupon{{ $availableCoupons->count() !== 1 ? 's' : '' }}</span>
        </div>

        @if($availableCoupons->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($availableCoupons as $coupon)
            <div class="group relative bg-gradient-to-br from-white to-primary-50 border-2 border-primary-200 rounded-xl p-6 hover:shadow-lg hover:border-primary-300 transition-all duration-300">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-primary-500/10 to-accent-500/10 rounded-bl-full"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 bg-gradient-to-tr from-primary-500/5 to-accent-500/5 rounded-tr-full"></div>
                
                <!-- Discount Badge -->
                <div class="relative text-center mb-4">
                    <div class="inline-flex items-center justify-center px-5 py-2 bg-gradient-to-r from-primary-500 to-accent-600 text-white rounded-full shadow-md">
                        <span class="text-2xl font-bold">{{ $coupon->getDiscountLabel() }}</span>
                    </div>
                </div>

                <!-- Coupon Code -->
                <div class="relative text-center mb-4">
                    <div class="bg-white border-2 border-dashed border-primary-300 rounded-lg p-3 shadow-sm">
                        <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">Code</p>
                        <p class="font-mono text-xl font-bold text-primary-700 tracking-wider">{{ $coupon->code }}</p>
                    </div>
                </div>

                <!-- Coupon Details -->
                <div class="relative mb-4">
                    <h3 class="font-semibold text-lg text-neutral-900 mb-1">{{ $coupon->name }}</h3>
                    @if($coupon->description)
                    <p class="text-sm text-neutral-600 line-clamp-2">{{ $coupon->description }}</p>
                    @endif
                </div>

                <!-- Terms -->
                <div class="relative space-y-1.5 mb-5">
                    @if($coupon->min_purchase_amount > 0)
                    <div class="flex items-center text-xs text-neutral-600">
                        <svg class="w-4 h-4 mr-2 text-secondary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>Min. purchase: ₱{{ number_format($coupon->min_purchase_amount, 0) }}</span>
                    </div>
                    @endif
                    @if($coupon->max_discount_amount)
                    <div class="flex items-center text-xs text-neutral-600">
                        <svg class="w-4 h-4 mr-2 text-secondary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>Max. discount: ₱{{ number_format($coupon->max_discount_amount, 0) }}</span>
                    </div>
                    @endif
                    @if($coupon->valid_until)
                    <div class="flex items-center text-xs text-neutral-600">
                        <svg class="w-4 h-4 mr-2 text-warning-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Valid until {{ $coupon->valid_until->format('M d, Y') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-xs text-neutral-600">
                        <svg class="w-4 h-4 mr-2 text-success-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <span>{{ $coupon->max_uses_per_user }} use{{ $coupon->max_uses_per_user > 1 ? 's' : '' }} per customer</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="relative flex gap-2">
                    <button onclick="copyCouponCode('{{ $coupon->code }}')" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-white border border-primary-300 text-primary-700 font-medium text-sm rounded-lg hover:bg-primary-50 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy
                    </button>
                    <a href="{{ route('client.coupons.show', $coupon) }}" 
                       class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-medium text-sm rounded-lg hover:shadow-md transition-all duration-300">
                        Details
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">No Coupons Available</h3>
            <p class="text-neutral-600">Check back later for exclusive deals and discounts!</p>
        </div>
        @endif
    </div>

    <!-- Usage History -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-neutral-900">Coupon History</h2>
            @if($usageHistory->count() > 0)
            <span class="text-sm text-neutral-600">{{ $usageHistory->total() }} transaction{{ $usageHistory->total() !== 1 ? 's' : '' }}</span>
            @endif
        </div>

        @if($usageHistory->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead>
                    <tr class="text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">
                        <th class="pb-3 pr-4">Date</th>
                        <th class="pb-3 px-4">Coupon</th>
                        <th class="pb-3 px-4">Project</th>
                        <th class="pb-3 px-4">Discount</th>
                        <th class="pb-3 pl-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @foreach($usageHistory as $usage)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="py-4 pr-4">
                            <span class="text-sm text-neutral-900">{{ $usage->used_at->format('M d, Y') }}</span>
                            <p class="text-xs text-neutral-500">{{ $usage->used_at->format('h:i A') }}</p>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-primary-50 border border-primary-200 rounded-md font-mono text-sm font-semibold text-primary-700">
                                {{ $usage->coupon->code }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-sm text-neutral-900 font-medium">{{ $usage->serviceRequest->project_name }}</span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center text-success-600 font-semibold text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                                ₱{{ number_format($usage->discount_amount, 2) }}
                            </span>
                        </td>
                        <td class="py-4 pl-4">
                            @if($usage->payment_status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Completed
                                </span>
                            @elseif($usage->payment_status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                    <svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-error-100 text-error-700">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Failed
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($usageHistory->hasPages())
        <div class="mt-6">
            {{ $usageHistory->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">No Usage History</h3>
            <p class="text-neutral-600">You haven't used any coupons yet</p>
        </div>
        @endif
    </div>
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

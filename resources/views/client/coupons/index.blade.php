@extends('client.layouts.app')

@section('title', 'Available Coupons')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Coupons', 'icon' => 'ticket'],
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">My Coupons</h1>
        <p class="text-sm text-neutral-500 mt-1">Save on your projects with exclusive discount coupons</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Available Coupons</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['total_coupons_available'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-ticket class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Coupons Used</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['total_coupons_used'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Savings</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($stats['total_savings'], 0) }}</p>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl">
                    <x-lucide-piggy-bank class="w-5 h-5 text-neutral-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Available Coupons -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-medium text-neutral-700">Available for You</h2>
            <span class="text-sm text-neutral-400">{{ $availableCoupons->count() }} coupon{{ $availableCoupons->count() !== 1 ? 's' : '' }}</span>
        </div>

        @if($availableCoupons->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($availableCoupons as $coupon)
            <div class="bg-white border border-neutral-200 rounded-2xl p-6 hover:shadow-md hover:border-primary-200 transition-all">
                <!-- Discount Badge -->
                <div class="text-center mb-4">
                    <span class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-lg font-semibold rounded-full">
                        {{ $coupon->getDiscountLabel() }}
                    </span>
                </div>

                <!-- Coupon Code -->
                <div class="text-center mb-4">
                    <div class="bg-neutral-50 border-2 border-dashed border-neutral-300 rounded-lg p-3">
                        <p class="text-xs text-neutral-400 uppercase tracking-wide mb-1">Code</p>
                        <p class="font-mono text-lg font-semibold text-neutral-800 tracking-wider">{{ $coupon->code }}</p>
                    </div>
                </div>

                <!-- Coupon Details -->
                <div class="mb-4">
                    <h3 class="text-base font-medium text-neutral-700 mb-1">{{ $coupon->name }}</h3>
                    @if($coupon->description)
                    <p class="text-sm text-neutral-500 line-clamp-2">{{ $coupon->description }}</p>
                    @endif
                </div>

                <!-- Terms -->
                <div class="space-y-2 mb-5">
                    @if($coupon->min_purchase_amount > 0)
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-info class="w-4 h-4 mr-2 text-neutral-400 flex-shrink-0" />
                        <span>Min. purchase: ₱{{ number_format($coupon->min_purchase_amount, 0) }}</span>
                    </div>
                    @endif
                    @if($coupon->max_discount_amount)
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-info class="w-4 h-4 mr-2 text-neutral-400 flex-shrink-0" />
                        <span>Max. discount: ₱{{ number_format($coupon->max_discount_amount, 0) }}</span>
                    </div>
                    @endif
                    @if($coupon->valid_until)
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-clock class="w-4 h-4 mr-2 text-warning-500 flex-shrink-0" />
                        <span>Valid until {{ $coupon->valid_until->format('M d, Y') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-xs text-neutral-500">
                        <x-lucide-ticket class="w-4 h-4 mr-2 text-neutral-400 flex-shrink-0" />
                        <span>{{ $coupon->max_uses_per_user }} use{{ $coupon->max_uses_per_user > 1 ? 's' : '' }} per customer</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button onclick="copyCouponCode('{{ $coupon->code }}')" 
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-lg border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                        <x-lucide-copy class="w-4 h-4" />
                        Copy
                    </button>
                    <a href="{{ route('client.coupons.show', $coupon) }}" 
                       class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        Details
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-14 h-14 bg-neutral-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <x-lucide-ticket class="w-7 h-7 text-neutral-400" />
            </div>
            <h3 class="text-base font-medium text-neutral-700 mb-1">No Coupons Available</h3>
            <p class="text-sm text-neutral-500">Check back later for exclusive deals and discounts!</p>
        </div>
        @endif
    </div>

    <!-- Usage History -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-medium text-neutral-700">Coupon History</h2>
            @if($usageHistory->count() > 0)
            <span class="text-sm text-neutral-400">{{ $usageHistory->total() }} transaction{{ $usageHistory->total() !== 1 ? 's' : '' }}</span>
            @endif
        </div>

        @if($usageHistory->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Coupon</th>
                        <th class="px-4 py-3">Project</th>
                        <th class="px-4 py-3">Discount</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($usageHistory as $usage)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-4">
                            <span class="text-sm text-neutral-700">{{ $usage->used_at->format('M d, Y') }}</span>
                            <p class="text-xs text-neutral-400">{{ $usage->used_at->format('h:i A') }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-primary-50 rounded-lg font-mono text-sm font-medium text-primary-700">
                                {{ $usage->coupon->code }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-sm text-neutral-700 font-medium">{{ $usage->serviceRequest->project_name }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1 text-success-600 font-medium text-sm">
                                <x-lucide-minus class="w-3 h-3" />
                                ₱{{ number_format($usage->discount_amount, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @if($usage->payment_status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                    Completed
                                </span>
                            @elseif($usage->payment_status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-700">
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
        <div class="text-center py-12">
            <div class="w-14 h-14 bg-neutral-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <x-lucide-clock class="w-7 h-7 text-neutral-400" />
            </div>
            <h3 class="text-base font-medium text-neutral-700 mb-1">No Usage History</h3>
            <p class="text-sm text-neutral-500">You haven't used any coupons yet</p>
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

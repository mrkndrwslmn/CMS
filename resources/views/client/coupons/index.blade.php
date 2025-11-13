@extends('layouts.client')

@section('title', 'Available Coupons')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-800 mb-2">Available Coupons</h1>
        <p class="text-neutral-600">Browse and use discount coupons for your projects</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6 bg-gradient-to-br from-primary-50 to-primary-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-ticket-alt text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Available Coupons</p>
                    <p class="text-3xl font-bold text-primary-700">{{ $stats['total_coupons_available'] }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 bg-gradient-to-br from-success-50 to-success-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-success-500 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-success-600 mb-1">Coupons Used</p>
                    <p class="text-3xl font-bold text-success-700">{{ $stats['total_coupons_used'] }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 bg-gradient-to-br from-warning-50 to-warning-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-warning-500 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-piggy-bank text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-warning-600 mb-1">Total Savings</p>
                    <p class="text-3xl font-bold text-warning-700">₱{{ number_format($stats['total_savings'], 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Coupons -->
    <div class="glass-card p-6 mb-8">
        <h2 class="text-xl font-bold text-neutral-800 mb-6">
            <i class="fas fa-gift text-primary-600 mr-2"></i>Available for You
        </h2>

        @if($availableCoupons->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($availableCoupons as $coupon)
            <div class="border-2 border-primary-200 rounded-lg p-6 bg-gradient-to-br from-white to-primary-50 hover:shadow-lg transition-shadow relative overflow-hidden">
                <!-- Decorative corner -->
                <div class="absolute top-0 right-0 w-20 h-20 bg-primary-500 opacity-10 rounded-bl-full"></div>
                
                <!-- Discount Badge -->
                <div class="text-center mb-4">
                    <div class="inline-block px-4 py-2 bg-primary-600 text-white rounded-full text-2xl font-bold">
                        {{ $coupon->getDiscountLabel() }}
                    </div>
                </div>

                <!-- Coupon Code -->
                <div class="text-center mb-4">
                    <div class="bg-white border-2 border-dashed border-primary-400 rounded-lg p-3">
                        <p class="text-xs text-neutral-500 mb-1">Coupon Code</p>
                        <p class="font-mono text-2xl font-bold text-primary-700">{{ $coupon->code }}</p>
                    </div>
                </div>

                <!-- Coupon Details -->
                <div class="mb-4">
                    <h3 class="font-bold text-lg text-neutral-800 mb-2">{{ $coupon->name }}</h3>
                    @if($coupon->description)
                    <p class="text-sm text-neutral-600 mb-3">{{ $coupon->description }}</p>
                    @endif
                </div>

                <!-- Terms -->
                <div class="space-y-2 mb-4">
                    @if($coupon->min_purchase_amount > 0)
                    <div class="flex items-center text-xs text-neutral-600">
                        <i class="fas fa-info-circle text-info-500 mr-2"></i>
                        <span>Min. purchase: ₱{{ number_format($coupon->min_purchase_amount, 0) }}</span>
                    </div>
                    @endif
                    @if($coupon->max_discount_amount)
                    <div class="flex items-center text-xs text-neutral-600">
                        <i class="fas fa-info-circle text-info-500 mr-2"></i>
                        <span>Max. discount: ₱{{ number_format($coupon->max_discount_amount, 0) }}</span>
                    </div>
                    @endif
                    @if($coupon->valid_until)
                    <div class="flex items-center text-xs text-neutral-600">
                        <i class="fas fa-clock text-warning-500 mr-2"></i>
                        <span>Valid until {{ $coupon->valid_until->format('M d, Y') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-xs text-neutral-600">
                        <i class="fas fa-ticket-alt text-success-500 mr-2"></i>
                        <span>{{ $coupon->max_uses_per_user }} use{{ $coupon->max_uses_per_user > 1 ? 's' : '' }} per customer</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button onclick="copyCouponCode('{{ $coupon->code }}')" 
                            class="btn btn-secondary flex-1 text-sm">
                        <i class="fas fa-copy mr-2"></i>Copy Code
                    </button>
                    <a href="{{ route('client.coupons.show', $coupon) }}" 
                       class="btn btn-primary flex-1 text-sm">
                        <i class="fas fa-arrow-right mr-2"></i>View Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-ticket-alt text-neutral-300 text-6xl mb-4"></i>
            <p class="text-neutral-500 text-lg">No coupons available at the moment</p>
            <p class="text-neutral-400 text-sm mt-2">Check back later for new deals!</p>
        </div>
        @endif
    </div>

    <!-- Usage History -->
    <div class="glass-card p-6">
        <h2 class="text-xl font-bold text-neutral-800 mb-6">
            <i class="fas fa-history text-info-600 mr-2"></i>My Coupon History
        </h2>

        @if($usageHistory->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Coupon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Project</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Discount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @foreach($usageHistory as $usage)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            {{ $usage->used_at->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-mono font-bold text-primary-600">{{ $usage->coupon->code }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-900">
                            {{ $usage->serviceRequest->project_name }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-success-600 font-semibold">-₱{{ number_format($usage->discount_amount, 2) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $usage->payment_status === 'completed' ? 'bg-success-100 text-success-800' : '' }}
                                {{ $usage->payment_status === 'pending' ? 'bg-warning-100 text-warning-800' : '' }}
                                {{ $usage->payment_status === 'failed' ? 'bg-error-100 text-error-800' : '' }}">
                                {{ ucfirst($usage->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($usageHistory->hasPages())
        <div class="mt-4">
            {{ $usageHistory->links() }}
        </div>
        @endif
        @else
        <p class="text-center text-neutral-500 py-8">No coupon usage history yet</p>
        @endif
    </div>
</div>

@push('scripts')
<script>
function copyCouponCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        // Show success notification
        alert('Coupon code "' + code + '" copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>
@endpush
@endsection

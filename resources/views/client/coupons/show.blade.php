@extends('client.layouts.app')

@section('title', $coupon->name)

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Coupons', 'route' => 'client.coupons.index', 'icon' => 'ticket'],
        ['label' => $coupon->code, 'icon' => 'tag'],
    ]" />

    <!-- Coupon Details Card -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden mb-8">
        <!-- Header Section -->
        <div class="bg-primary-600 px-8 py-10 text-center">
            <h1 class="text-2xl font-semibold text-white mb-2">{{ $coupon->name }}</h1>
            @if($coupon->description)
            <p class="text-primary-100 text-sm max-w-2xl mx-auto">{{ $coupon->description }}</p>
            @endif
        </div>

        <!-- Discount Display -->
        <div class="relative -mt-6 px-8">
            <div class="bg-white rounded-2xl shadow-md border border-neutral-100 p-8 text-center">
                <div class="flex items-center justify-center gap-8">
                    <!-- Discount Amount -->
                    <div>
                        <p class="text-xs text-neutral-400 mb-2 uppercase tracking-wide">Save</p>
                        <div class="text-4xl font-semibold text-primary-600">
                            {{ $coupon->getDiscountLabel() }}
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="h-16 w-px bg-neutral-200"></div>

                    <!-- Coupon Code -->
                    <div>
                        <p class="text-xs text-neutral-400 mb-2 uppercase tracking-wide">Code</p>
                        <div class="bg-neutral-50 border-2 border-dashed border-neutral-300 rounded-lg px-6 py-3 mb-3">
                            <p class="font-mono text-2xl font-semibold text-neutral-800 tracking-wider">{{ $coupon->code }}</p>
                        </div>
                        <button onclick="copyCouponCode('{{ $coupon->code }}')" 
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                            <x-lucide-copy class="w-4 h-4" />
                            Copy Code
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="px-8 py-8">
            <h2 class="text-lg font-medium text-neutral-700 mb-4">Terms & Conditions</h2>
            <div class="grid md:grid-cols-2 gap-4">
                @if($coupon->min_purchase_amount > 0)
                <div class="flex items-start gap-3 bg-neutral-50 rounded-xl border border-neutral-100 p-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white rounded-lg border border-neutral-200 flex items-center justify-center">
                            <x-lucide-wallet class="w-5 h-5 text-neutral-500" />
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-700">Minimum Purchase</p>
                        <p class="text-sm text-neutral-500">₱{{ number_format($coupon->min_purchase_amount, 2) }}</p>
                    </div>
                </div>
                @endif

                @if($coupon->max_discount_amount)
                <div class="flex items-start gap-3 bg-neutral-50 rounded-xl border border-neutral-100 p-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white rounded-lg border border-neutral-200 flex items-center justify-center">
                            <x-lucide-trending-up class="w-5 h-5 text-warning-500" />
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-700">Maximum Discount</p>
                        <p class="text-sm text-neutral-500">₱{{ number_format($coupon->max_discount_amount, 2) }}</p>
                    </div>
                </div>
                @endif

                @if($coupon->valid_until)
                <div class="flex items-start gap-3 bg-neutral-50 rounded-xl border border-neutral-100 p-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white rounded-lg border border-neutral-200 flex items-center justify-center">
                            <x-lucide-calendar class="w-5 h-5 text-error-500" />
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-700">Valid Until</p>
                        <p class="text-sm text-neutral-500">{{ $coupon->valid_until->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif

                <div class="flex items-start gap-3 bg-neutral-50 rounded-xl border border-neutral-100 p-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white rounded-lg border border-neutral-200 flex items-center justify-center">
                            <x-lucide-ticket class="w-5 h-5 text-success-500" />
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-700">Usage Limit</p>
                        <p class="text-sm text-neutral-500">{{ $coupon->max_uses_per_user }} use{{ $coupon->max_uses_per_user > 1 ? 's' : '' }} per customer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage History -->
    @if($userUsages->count() > 0)
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
        <h2 class="text-lg font-medium text-neutral-700 mb-6">Your Usage History</h2>
        
        <div class="space-y-4">
            @foreach($userUsages as $usage)
            <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-xl border border-neutral-100 hover:border-primary-200 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white rounded-lg border border-neutral-200 flex items-center justify-center">
                            <x-lucide-file-text class="w-5 h-5 text-neutral-500" />
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-700">{{ $usage->serviceRequest->project_name }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs text-neutral-400">{{ $usage->used_at->format('M d, Y h:i A') }}</span>
                            <span class="text-xs font-medium text-success-600">-₱{{ number_format($usage->discount_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div>
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
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-8 text-center">
        <div class="w-14 h-14 bg-neutral-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <x-lucide-clock class="w-7 h-7 text-neutral-400" />
        </div>
        <h3 class="text-base font-medium text-neutral-700 mb-1">No Usage History</h3>
        <p class="text-sm text-neutral-500">You haven't used this coupon yet</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
function copyCouponCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        window.toast.success(`Coupon code "${code}" copied!`);
    }).catch(err => {
        console.error('Failed to copy:', err);
        window.toast.error('Failed to copy coupon code. Please try again.');
    });
}
</script>
@endpush
@endsection

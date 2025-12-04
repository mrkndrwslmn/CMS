@extends('client.layouts.app')

@section('title', 'Referral Program')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Referral Program', 'icon' => 'users'],
    ]" />

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Referral Program</h1>
                <p class="text-neutral-500 mt-2">Invite friends and earn rewards together!</p>
            </div>
            <a href="{{ route('client.referrals.share') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg shadow-sm hover:bg-primary-700 transition-colors">
                <x-lucide-share-2 class="w-5 h-5" />
                Share Your Code
            </a>
        </div>
    </div>

    
    <!-- Navigation Tabs -->
    <div class="mb-8">
        <div class="border-b border-neutral-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('client.referrals.dashboard') }}" 
                   class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center gap-2">
                    <x-lucide-layout-dashboard class="w-4 h-4" />
                    Dashboard
                </a>
                <a href="{{ route('client.referrals.credits') }}" 
                    class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-circle-dollar-sign class="w-4 h-4" />
                    Credits & Withdrawals
                </a>
                <a href="{{ route('client.referrals.history') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-clock class="w-4 h-4" />
                    History
                </a>
                <a href="{{ route('client.referrals.share') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-share-2 class="w-4 h-4" />
                    Share
                </a>
            </nav>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Referrals -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                        <x-lucide-users class="w-6 h-6 text-primary-600" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-500">Total Referrals</p>
                    <p class="text-2xl font-semibold text-neutral-800">{{ $stats['total_referrals'] }}</p>
                </div>
            </div>
        </div>

        <!-- Successful Referrals -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-success-50 rounded-xl flex items-center justify-center">
                        <x-lucide-check-circle class="w-6 h-6 text-success-600" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-500">Successful</p>
                    <p class="text-2xl font-semibold text-neutral-800">{{ $stats['successful_referrals'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Referrals -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-warning-50 rounded-xl flex items-center justify-center">
                        <x-lucide-clock class="w-6 h-6 text-warning-600" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-neutral-800">{{ $stats['pending_referrals'] }}</p>
                </div>
            </div>
        </div>

        <!-- Lifetime Earnings -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                        <x-lucide-gift class="w-6 h-6 text-primary-600" />
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-500">Lifetime Earnings</p>
                    <p class="text-2xl font-semibold text-neutral-800">{{ number_format($stats['lifetime_earnings']) }} pts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Code Card -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-neutral-800">Your Referral Code</h2>
                @if($stats['conversion_rate'] > 0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success-50 text-success-700">
                        {{ number_format($stats['conversion_rate'], 1) }}% conversion
                    </span>
                @endif
            </div>
            <div class="text-center mb-6">
                <div class="mb-3">
                    <h2 class="text-4xl font-semibold text-primary-600 tracking-wider mb-2" id="referralCode">{{ $referralCode->code }}</h2>
                    <p class="text-neutral-500">Share this code with friends to earn rewards</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mb-6">
                    <button type="button" onclick="copyReferralCode()" class="inline-flex items-center gap-2 px-5 py-2.5 border border-primary-200 text-primary-700 bg-white hover:bg-primary-50 font-medium rounded-lg transition-colors">
                        <x-lucide-copy class="w-5 h-5" />
                        Copy Code
                    </button>
                    <a href="{{ route('client.referrals.share') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <x-lucide-share-2 class="w-5 h-5" />
                        Share Link
                    </a>
                </div>

                <div class="bg-primary-50 border border-primary-100 rounded-xl p-4 text-left">
                    <p class="font-medium text-primary-900 mb-2">How it works:</p>
                    <ul class="space-y-2 text-sm text-primary-800">
                        <li class="flex items-start gap-2">
                            <x-lucide-user-plus class="w-5 h-5 mt-0.5 text-primary-600 flex-shrink-0" />
                            <span>Friend signs up using your code → They get <strong>500 points + 15% coupon</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-wallet class="w-5 h-5 mt-0.5 text-primary-600 flex-shrink-0" />
                            <span>They complete payment → You get <strong>withdrawable credits</strong> (1-3% based on amount)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-gift class="w-5 h-5 mt-0.5 text-primary-600 flex-shrink-0" />
                            <span>They receive a <strong>discount coupon</strong> (5-10% off) for their next project</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-check-circle class="w-5 h-5 mt-0.5 text-success-600 flex-shrink-0" />
                            <span><strong>Higher payments = Higher rewards!</strong> Tiered percentages up to ₱1M+</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pending Rewards Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
            <h2 class="text-lg font-semibold text-neutral-800 mb-6">Pending Rewards</h2>
            @if($pendingReferrals->count() > 0)
                <div class="text-center mb-6">
                    <p class="text-4xl font-semibold text-warning-600 mb-1">{{ number_format($potentialEarnings) }}</p>
                    <p class="text-neutral-500">potential points from {{ $pendingReferrals->count() }} pending referral(s)</p>
                </div>

                <div class="space-y-3">
                    @foreach($pendingReferrals as $referral)
                        <div class="border border-neutral-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-neutral-800">{{ $referral->referred->fullName }}</h3>
                                    <p class="text-sm text-neutral-500">Signed up {{ $referral->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-warning-50 text-warning-700">
                                    Pending Payment
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <x-lucide-inbox class="w-12 h-12 mx-auto text-neutral-300 mb-4" />
                    <p class="text-neutral-500 mb-4">No pending referrals</p>
                    <a href="{{ route('client.referrals.share') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <x-lucide-share-2 class="w-4 h-4" />
                        Share Your Code
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Referrals -->
    <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-neutral-800">Recent Referrals</h2>
            <a href="{{ route('client.referrals.history') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium inline-flex items-center gap-1">
                View All
                <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>

        @if($completedReferrals->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-100">
                    <thead>
                        <tr class="text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <th class="pb-3">Friend</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Points Earned</th>
                            <th class="pb-3">Coupon</th>
                            <th class="pb-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($completedReferrals as $referral)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-4">
                                    <div>
                                        <p class="font-medium text-neutral-800">{{ $referral->referred->fullName }}</p>
                                        <p class="text-sm text-neutral-500">{{ $referral->referred->email }}</p>
                                    </div>
                                </td>
                                <td class="py-4">
                                    @if($referral->status === 'rewarded')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-50 text-success-700">
                                            <x-lucide-check-circle class="w-3.5 h-3.5" />
                                            Completed
                                        </span>
                                    @elseif($referral->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700">
                                            <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                            Processing
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-50 text-warning-700">
                                            <x-lucide-clock class="w-3.5 h-3.5" />
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <span class="font-medium text-success-600">+{{ number_format($referral->referrer_points_earned) }}</span>
                                    <span class="text-neutral-500 text-sm"> points</span>
                                </td>
                                <td class="py-4">
                                    @if($referral->referrerCoupon)
                                        <div>
                                            <code class="px-2 py-1 bg-neutral-50 text-neutral-700 rounded text-sm font-mono">{{ $referral->referrerCoupon->code }}</code>
                                            <p class="text-xs text-neutral-500 mt-1">{{ $referral->referrerCoupon->discount_value }}% off</p>
                                        </div>
                                    @else
                                        <span class="text-neutral-400 text-sm">N/A</span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <span class="text-sm text-neutral-500">
                                        {{ $referral->completed_at ? $referral->completed_at->format('M d, Y') : 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <x-lucide-users class="w-12 h-12 mx-auto text-neutral-300 mb-4" />
                <p class="text-neutral-600 mb-2">No successful referrals yet</p>
                <p class="text-sm text-neutral-400">Share your code to start earning rewards!</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function copyReferralCode() {
    const code = document.getElementById('referralCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        // Show success toast
        const toast = document.createElement('div');
        toast.className = 'fixed top-20 right-5 z-50 flex items-center gap-3 bg-success-600 text-white px-6 py-3 rounded-lg shadow-lg';
        toast.innerHTML = `
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">Referral code copied!</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}
</script>
@endpush
@endsection

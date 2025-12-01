@extends('client.layouts.app')

@section('title', 'Referral Program')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">Referral Program</h1>
                <p class="text-neutral-600 mt-2">Invite friends and earn rewards together!</p>
            </div>
            <a href="{{ route('client.referrals.share') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                Share Your Code
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Referrals -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Total Referrals</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $stats['total_referrals'] }}</p>
                </div>
            </div>
        </div>

        <!-- Successful Referrals -->
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
                    <p class="text-sm font-medium text-neutral-600">Successful</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $stats['successful_referrals'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Referrals -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-warning-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Pending</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $stats['pending_referrals'] }}</p>
                </div>
            </div>
        </div>

        <!-- Lifetime Earnings -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Lifetime Earnings</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ number_format($stats['lifetime_earnings']) }} pts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Code Card -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-neutral-900">Your Referral Code</h2>
                @if($stats['conversion_rate'] > 0)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success-100 text-success-700">
                        {{ number_format($stats['conversion_rate'], 1) }}% conversion
                    </span>
                @endif
            </div>
            <div class="text-center mb-6">
                <div class="mb-3">
                    <h2 class="text-5xl font-bold text-primary-600 tracking-wider mb-2" id="referralCode">{{ $referralCode->code }}</h2>
                    <p class="text-neutral-600">Share this code with friends to earn rewards</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mb-6">
                    <button type="button" onclick="copyReferralCode()" class="inline-flex items-center px-5 py-2.5 border border-primary-300 text-primary-700 bg-white hover:bg-primary-50 font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copy Code
                    </button>
                    <a href="{{ route('client.referrals.share') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-medium rounded-lg hover:shadow-lg transition-shadow duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Share Link
                    </a>
                </div>

                <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 text-left">
                    <p class="font-semibold text-primary-900 mb-2">How it works:</p>
                    <ul class="space-y-2 text-sm text-primary-800">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span>Friend signs up using your code → They get <strong>500 points + 15% coupon</strong></span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <span>They complete first payment → You get <strong>1,000 points + 20% coupon</strong></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pending Rewards Card -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <h2 class="text-xl font-semibold text-neutral-900 mb-6">Pending Rewards</h2>
            @if($pendingReferrals->count() > 0)
                <div class="text-center mb-6">
                    <p class="text-4xl font-bold text-warning-600 mb-1">{{ number_format($potentialEarnings) }}</p>
                    <p class="text-neutral-600">potential points from {{ $pendingReferrals->count() }} pending referral(s)</p>
                </div>

                <div class="space-y-3">
                    @foreach($pendingReferrals as $referral)
                        <div class="border border-neutral-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-neutral-900">{{ $referral->referred->fullName }}</h3>
                                    <p class="text-sm text-neutral-600">Signed up {{ $referral->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                    Pending Payment
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-neutral-600 mb-4">No pending referrals</p>
                    <a href="{{ route('client.referrals.share') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        Share Your Code
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Referrals -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-neutral-900">Recent Referrals</h2>
            <a href="{{ route('client.referrals.history') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                View All →
            </a>
        </div>

        @if($completedReferrals->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead>
                        <tr class="text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">
                            <th class="pb-3">Friend</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Points Earned</th>
                            <th class="pb-3">Coupon</th>
                            <th class="pb-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200">
                        @foreach($completedReferrals as $referral)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-4">
                                    <div>
                                        <p class="font-semibold text-neutral-900">{{ $referral->referred->fullName }}</p>
                                        <p class="text-sm text-neutral-600">{{ $referral->referred->email }}</p>
                                    </div>
                                </td>
                                <td class="py-4">
                                    @if($referral->status === 'rewarded')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Completed
                                        </span>
                                    @elseif($referral->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-700">
                                            <svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Processing
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <span class="font-semibold text-success-600">+{{ number_format($referral->referrer_points_earned) }}</span>
                                    <span class="text-neutral-600 text-sm"> points</span>
                                </td>
                                <td class="py-4">
                                    @if($referral->referrerCoupon)
                                        <div>
                                            <code class="px-2 py-1 bg-neutral-100 text-neutral-800 rounded text-sm font-mono">{{ $referral->referrerCoupon->code }}</code>
                                            <p class="text-xs text-neutral-600 mt-1">{{ $referral->referrerCoupon->discount_value }}% off</p>
                                        </div>
                                    @else
                                        <span class="text-neutral-500 text-sm">N/A</span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <span class="text-sm text-neutral-600">
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
                <svg class="w-16 h-16 mx-auto text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-neutral-600 mb-2">No successful referrals yet</p>
                <p class="text-sm text-neutral-500">Share your code to start earning rewards!</p>
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

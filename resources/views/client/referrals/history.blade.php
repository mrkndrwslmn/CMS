@extends('layouts.client')

@section('title', 'My Referral History')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Referral History</h1>
                <p class="mt-2 text-gray-600">Track all your referrals and their status</p>
            </div>
            <a href="{{ route('client.referrals.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('client.referrals.history') }}" class="flex flex-wrap gap-4 items-end">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search by name or email..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>

            <!-- Status Filter -->
            <div class="w-48">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select 
                    id="status" 
                    name="status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rewarded" {{ request('status') == 'rewarded' ? 'selected' : '' }}>Rewarded</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    Apply Filters
                </button>
                <a 
                    href="{{ route('client.referrals.history') }}" 
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-4 text-sm text-gray-600">
        Showing {{ $referrals->firstItem() ?? 0 }} to {{ $referrals->lastItem() ?? 0 }} of {{ $referrals->total() }} referrals
    </div>

    <!-- Referrals List -->
    @if($referrals->count() > 0)
        <div class="space-y-4">
            @foreach($referrals as $referral)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <!-- Left Side: User Info -->
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                    {{ substr($referral->referred->fullName ?? 'U', 0, 1) }}
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $referral->referred->fullName ?? 'User' }}
                                    </h3>
                                    @if($referral->status === 'rewarded')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Rewarded
                                        </span>
                                    @elseif($referral->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Completed
                                        </span>
                                    @elseif($referral->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($referral->status) }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-600 mb-2">
                                    {{ $referral->referred->email ?? 'N/A' }}
                                </p>

                                <!-- Timeline -->
                                <div class="space-y-2">
                                    <!-- Signup -->
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Signed up on 
                                            <span class="font-medium text-gray-900">
                                                {{ $referral->referred_user_signup_at?->format('M d, Y h:i A') ?? $referral->created_at->format('M d, Y h:i A') }}
                                            </span>
                                        </span>
                                    </div>

                                    <!-- Payment -->
                                    @if($referral->referred_user_payment_at)
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-600">Made payment on 
                                                <span class="font-medium text-gray-900">
                                                    {{ $referral->referred_user_payment_at->format('M d, Y h:i A') }}
                                                </span>
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-500">Payment pending</span>
                                        </div>
                                    @endif

                                    <!-- Reward -->
                                    @if($referral->rewarded_at)
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-600">Rewarded on 
                                                <span class="font-medium text-gray-900">
                                                    {{ $referral->rewarded_at->format('M d, Y h:i A') }}
                                                </span>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Rewards -->
                        <div class="flex-shrink-0 text-right ml-4">
                            @if($referral->status === 'rewarded' && $referral->referrer_points_earned > 0)
                                <div class="mb-3">
                                    <div class="text-2xl font-bold text-green-600">
                                        +{{ number_format($referral->referrer_points_earned) }}
                                    </div>
                                    <div class="text-xs text-gray-500">Points Earned</div>
                                </div>
                            @elseif($referral->status === 'pending' && $referral->referrer_points_pending > 0)
                                <div class="mb-3">
                                    <div class="text-2xl font-bold text-yellow-600">
                                        {{ number_format($referral->referrer_points_pending) }}
                                    </div>
                                    <div class="text-xs text-gray-500">Pending Points</div>
                                </div>
                            @endif

                            @if($referral->referrerCoupon)
                                <div class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z"/>
                                    </svg>
                                    {{ $referral->referrerCoupon->discount_percentage }}% Coupon
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $referrals->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Referrals Yet</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                You haven't referred anyone yet. Start sharing your referral code to earn rewards!
            </p>
            <a 
                href="{{ route('client.referrals.share') }}" 
                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                Start Referring Now
            </a>
        </div>
    @endif
</div>
@endsection

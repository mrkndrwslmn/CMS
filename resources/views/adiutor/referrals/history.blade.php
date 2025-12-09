@extends('adiutor.layouts.app')

@section('title', 'My Referral History')
@section('page-title', 'Referral History')

@section('content')
<div class="max-w-8xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Referral History</h1>
                <p class="text-neutral-500 mt-2">Track all your referrals and their status</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-8">
        <div class="border-b border-neutral-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('adiutor.referrals.dashboard') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-layout-dashboard class="w-4 h-4" />
                    Dashboard
                </a>
                <a href="{{ route('adiutor.referrals.credits') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-circle-dollar-sign class="w-4 h-4" />
                    Credits
                </a>
                <a href="{{ route('adiutor.referrals.history') }}" 
                   class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center gap-2">
                    <x-lucide-clock class="w-4 h-4" />
                    History
                </a>
                <a href="{{ route('adiutor.referrals.share') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-share-2 class="w-4 h-4" />
                    Share
                </a>
            </nav>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-4 mb-6">
        <form method="GET" action="{{ route('adiutor.referrals.history') }}" class="flex flex-wrap gap-4 items-end">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search by name or email..." 
                    class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
            </div>

            <!-- Status Filter -->
            <div class="w-48">
                <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                <select 
                    id="status" 
                    name="status" 
                    class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
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
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                >
                    <x-lucide-filter class="w-4 h-4 mr-2" />
                    Apply Filters
                </button>
                <a 
                    href="{{ route('adiutor.referrals.history') }}" 
                    class="px-4 py-2 bg-neutral-100 text-neutral-700 font-medium rounded-lg hover:bg-neutral-200 transition-colors"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-4 text-sm text-neutral-600">
        Showing {{ $referrals->firstItem() ?? 0 }} to {{ $referrals->lastItem() ?? 0 }} of {{ $referrals->total() }} referrals
    </div>

    <!-- Referrals List -->
    @if($referrals->count() > 0)
        <div class="space-y-4">
            @foreach($referrals as $referral)
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <!-- Left Side: User Info -->
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                @if($referral->referred && $referral->referred->profilePic)
                                    <img src="{{ $referral->referred->getProfilePictureUrl() }}" 
                                         alt="{{ $referral->referred->fullName }}" 
                                         class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                        {{ substr($referral->referred->fullName ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-lg font-semibold text-neutral-900">
                                        {{ $referral->referred->fullName ?? 'User' }}
                                    </h3>
                                    @if($referral->status === 'rewarded')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                            <x-lucide-check-circle class="w-3 h-3" />
                                            Rewarded
                                        </span>
                                    @elseif($referral->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
                                            <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                            Processing
                                        </span>
                                    @elseif($referral->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                            <x-lucide-clock class="w-3 h-3" />
                                            Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                            {{ ucfirst($referral->status) }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-neutral-600 mb-2">
                                    {{ $referral->referred->email ?? 'N/A' }}
                                </p>

                                <!-- Timeline -->
                                <div class="space-y-2">
                                    <!-- Signup -->
                                    <div class="flex items-center text-sm">
                                        <x-lucide-check-circle class="w-4 h-4 mr-2 text-success-500" />
                                        <span class="text-neutral-600">Signed up on 
                                            <span class="font-medium text-neutral-900">
                                                {{ $referral->referred_user_signup_at?->format('M d, Y h:i A') ?? $referral->created_at->format('M d, Y h:i A') }}
                                            </span>
                                        </span>
                                    </div>

                                    <!-- Payment -->
                                    @if($referral->referred_user_payment_at)
                                        <div class="flex items-center text-sm">
                                            <x-lucide-check-circle class="w-4 h-4 mr-2 text-success-500" />
                                            <span class="text-neutral-600">Made payment on 
                                                <span class="font-medium text-neutral-900">
                                                    {{ $referral->referred_user_payment_at->format('M d, Y h:i A') }}
                                                </span>
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-sm">
                                            <x-lucide-x-circle class="w-4 h-4 mr-2 text-neutral-400" />
                                            <span class="text-neutral-500">Payment pending</span>
                                        </div>
                                    @endif

                                    <!-- Reward -->
                                    @if($referral->rewarded_at)
                                        <div class="flex items-center text-sm">
                                            <x-lucide-check-circle class="w-4 h-4 mr-2 text-success-500" />
                                            <span class="text-neutral-600">Rewarded on 
                                                <span class="font-medium text-neutral-900">
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
                                    <div class="text-2xl font-bold text-success-600">
                                        +{{ number_format($referral->referrer_points_earned) }}
                                    </div>
                                    <div class="text-xs text-neutral-500">Points Earned</div>
                                </div>
                            @elseif($referral->status === 'pending' && $referral->referrer_points_pending > 0)
                                <div class="mb-3">
                                    <div class="text-2xl font-bold text-warning-600">
                                        {{ number_format($referral->referrer_points_pending) }}
                                    </div>
                                    <div class="text-xs text-neutral-500">Pending Points</div>
                                </div>
                            @endif

                            @if($referral->referrerCoupon)
                                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-700">
                                    <x-lucide-ticket class="w-3 h-3" />
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
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-12 text-center">
            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <x-lucide-users class="w-8 h-8 text-neutral-400" />
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">No Referrals Yet</h3>
            <p class="text-neutral-600 mb-6 max-w-md mx-auto">
                You haven't referred anyone yet. Start sharing your referral code to earn rewards!
            </p>
            <a 
                href="{{ route('adiutor.referrals.share') }}" 
                class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg shadow-sm hover:bg-primary-700 hover:-translate-y-0.5 transition-all duration-300"
            >
                <x-lucide-share-2 class="w-5 h-5 mr-2" />
                Start Referring Now
            </a>
        </div>
    @endif
</div>
@endsection

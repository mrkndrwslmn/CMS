@extends('adiutor.layouts.app')

@section('title', 'Referral Credits')
@section('page-title', 'Referral Credits')

@section('content')
<div class="max-w-8xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Referral Credits</h1>
                <p class="text-neutral-500 mt-2">Track your referral earnings</p>
            </div>
            <a href="{{ route('adiutor.earnings.wallet') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                <x-lucide-wallet class="w-4 h-4" />
                Go to Wallet
            </a>
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
                   class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center gap-2">
                    <x-lucide-circle-dollar-sign class="w-4 h-4" />
                    Credits
                </a>
                <a href="{{ route('adiutor.referrals.history') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
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

    <!-- Credits Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Available Credits -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Available Credits</p>
                    <p class="text-2xl font-semibold text-success-600 mt-1">₱{{ number_format($availableCredits, 2) }}</p>
                    <p class="text-sm text-neutral-400 mt-2">Ready for withdrawal</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-wallet class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>

        <!-- Potential Pending Credits -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Potential Earnings</p>
                    <p class="text-2xl font-semibold text-warning-600 mt-1">~₱{{ number_format($potentialPendingCredits, 2) }}</p>
                    <p class="text-sm text-neutral-400 mt-2">{{ $pendingReferrals }} pending {{ Str::plural('referral', $pendingReferrals) }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>

        <!-- Total Earned -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Earned</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($availableCredits + $withdrawnCredits, 2) }}</p>
                    <p class="text-sm text-neutral-400 mt-2">All-time referral earnings</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-trending-up class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>
    </div>

    @if($withdrawalPendingCredits > 0)
    <!-- Withdrawal Pending Notice -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8">
        <div class="flex items-center gap-3">
            <x-lucide-timer class="w-5 h-5 text-amber-600" />
            <div>
                <p class="text-amber-800 font-medium">Withdrawal in Progress</p>
                <p class="text-amber-600 text-sm">₱{{ number_format($withdrawalPendingCredits, 2) }} is pending withdrawal processing</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Left Column: Payout Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Payout Info Card -->
            <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <x-lucide-banknote class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Request Payout</h3>
                        <p class="text-sm opacity-90">Withdraw your earnings</p>
                    </div>
                </div>
                
                <p class="text-sm opacity-90 mb-4">
                    Your referral credits are combined with your work earnings in your unified wallet. Request a payout from your wallet to withdraw all your available funds.
                </p>
                
                <a href="{{ route('adiutor.earnings.wallet') }}" 
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-white text-primary-700 font-semibold rounded-xl hover:bg-primary-50 transition-colors">
                    <x-lucide-wallet class="w-5 h-5" />
                    View Wallet & Request Payout
                </a>
            </div>

            <!-- How It Works -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
                <h3 class="font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <x-lucide-lightbulb class="w-5 h-5 text-warning-500" />
                    How to Earn Credits
                </h3>
                <ul class="space-y-3 text-sm text-neutral-600">
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-xs font-bold text-primary-600">1</span>
                        </div>
                        <span>Share your unique referral code with potential clients</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-xs font-bold text-primary-600">2</span>
                        </div>
                        <span>They sign up using your code and start a project</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-xs font-bold text-primary-600">3</span>
                        </div>
                        <span>When they pay ≥₱100,000, you earn 1-3% as credits</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-success-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <x-lucide-check class="w-3 h-3 text-success-600" />
                        </div>
                        <span>Credits added to your wallet for payout</span>
                    </li>
                </ul>
                
                <div class="mt-4 pt-4 border-t border-neutral-100">
                    <a href="{{ route('adiutor.referrals.share') }}" 
                       class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium text-sm">
                        <x-lucide-share-2 class="w-4 h-4" />
                        Share Your Code Now
                    </a>
                </div>
            </div>

            <!-- Reward Tiers -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
                <h3 class="font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <x-lucide-gift class="w-5 h-5 text-success-500" />
                    Reward Tiers
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-neutral-100">
                        <span class="text-sm text-neutral-600">₱100K - ₱200K</span>
                        <span class="text-sm font-semibold text-success-600">3% credits</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-neutral-100">
                        <span class="text-sm text-neutral-600">₱200K - ₱500K</span>
                        <span class="text-sm font-semibold text-success-600">2% credits</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-neutral-100">
                        <span class="text-sm text-neutral-600">₱500K - ₱1M</span>
                        <span class="text-sm font-semibold text-success-600">1.5% credits</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-neutral-600">₱1M+</span>
                        <span class="text-sm font-semibold text-success-600">1% credits</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Transaction History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-neutral-800">Recent Credit Transactions</h2>
                    <a href="{{ route('adiutor.earnings.wallet') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        View All in Wallet →
                    </a>
                </div>
                
                @if($transactions->count() > 0)
                    <div class="space-y-3">
                        @foreach($transactions as $transaction)
                            <div class="flex items-center justify-between py-3 border-b border-neutral-100 last:border-0">
                                <div class="flex items-center gap-3">
                                    @if($transaction->transaction_type === 'earned' || $transaction->amount > 0)
                                        <div class="w-10 h-10 bg-success-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <x-lucide-plus class="w-5 h-5 text-success-600" />
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <x-lucide-minus class="w-5 h-5 text-primary-600" />
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <p class="font-medium text-neutral-800">
                                            {{ $transaction->description ?? ucfirst($transaction->transaction_type ?? 'Credit') }}
                                        </p>
                                        <p class="text-xs text-neutral-400">{{ $transaction->created_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <p class="font-semibold {{ $transaction->amount >= 0 ? 'text-success-600' : 'text-error-600' }}">
                                        {{ $transaction->amount >= 0 ? '+' : '' }}₱{{ number_format(abs($transaction->amount), 2) }}
                                    </p>
                                    @if(isset($transaction->balance_after))
                                        <p class="text-xs text-neutral-400">Balance: ₱{{ number_format($transaction->balance_after, 2) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <x-lucide-coins class="w-8 h-8 text-neutral-400" />
                        </div>
                        <h3 class="text-lg font-medium text-neutral-800 mb-2">No Credit Transactions Yet</h3>
                        <p class="text-neutral-500 mb-6 max-w-sm mx-auto">
                            Start referring clients to earn credits. Your transactions will appear here.
                        </p>
                        <a href="{{ route('adiutor.referrals.share') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <x-lucide-share-2 class="w-4 h-4" />
                            Share Your Referral Code
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

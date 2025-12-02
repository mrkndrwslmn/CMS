@extends('adiutor.layouts.app')

@section('title', 'My Wallet')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">My Wallet</h1>
                <p class="text-sm text-gray-500 mt-1">Track your earnings and balances</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center gap-3">
                <a href="{{ route('adiutor.earnings.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Time Entries
                </a>
                @if($totalAvailable >= 500)
                <a href="{{ route('adiutor.earnings.request-form') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Request Payout
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Work Earnings Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl overflow-hidden shadow-lg">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-100">Work Earnings</p>
                            <p class="text-xs text-blue-200">From approved time entries & fixed rates</p>
                        </div>
                    </div>
                </div>
                <p class="text-4xl font-bold">₱{{ number_format($workEarningsBalance, 2) }}</p>
                <p class="text-sm text-blue-200 mt-2">Available Balance</p>
                
                <div class="mt-4 pt-4 border-t border-white/20 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-blue-200">Pending</p>
                        <p class="font-semibold">₱{{ number_format($workEarningsPending, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-blue-200">Withdrawn</p>
                        <p class="font-semibold">₱{{ number_format($workEarningsWithdrawn, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Credits Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl overflow-hidden shadow-lg">
            <div class="p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-purple-100">Referral Credits</p>
                            <p class="text-xs text-purple-200">From successful referrals</p>
                        </div>
                    </div>
                </div>
                <p class="text-4xl font-bold">₱{{ number_format($referralCreditsBalance, 2) }}</p>
                <p class="text-sm text-purple-200 mt-2">Available Balance</p>
                
                <div class="mt-4 pt-4 border-t border-white/20 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-purple-200">Pending</p>
                        <p class="font-semibold">₱{{ number_format($referralCreditsPending, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-purple-200">Withdrawn</p>
                        <p class="font-semibold">₱{{ number_format($referralCreditsWithdrawn, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Balance Summary -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-8">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Available Balance</p>
                    <p class="text-4xl font-bold text-gray-900 mt-1">₱{{ number_format($totalAvailable, 2) }}</p>
                </div>
                <div class="flex flex-col sm:items-end gap-2">
                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span class="text-gray-600">Pending: ₱{{ number_format($totalPending, 2) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <span class="text-gray-600">Withdrawn: ₱{{ number_format($totalWithdrawn, 2) }}</span>
                        </div>
                    </div>
                    @if($totalAvailable >= 500)
                    <a href="{{ route('adiutor.earnings.request-form') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Request Payout
                    </a>
                    @else
                    <p class="text-sm text-gray-500">Minimum ₱500 required for payout</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">This Month's Earnings</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($monthlyEarnings, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            @if($earningsChange != 0)
            <div class="mt-3 flex items-center gap-1 text-sm">
                @if($earningsChange > 0)
                <span class="text-green-600">↑ {{ abs($earningsChange) }}%</span>
                @else
                <span class="text-red-600">↓ {{ abs($earningsChange) }}%</span>
                @endif
                <span class="text-gray-500">vs last month</span>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Last Month's Earnings</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($lastMonthEarnings, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Lifetime Earnings</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($totalAvailable + $totalPending + $totalWithdrawn, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-900">Transaction History</h2>
                
                <!-- Filters -->
                <form method="GET" class="flex items-center gap-3">
                    <select name="wallet_type" onchange="this.form.submit()"
                            class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="all" {{ $walletType === 'all' ? 'selected' : '' }}>All Wallets</option>
                        <option value="work_earnings" {{ $walletType === 'work_earnings' ? 'selected' : '' }}>Work Earnings</option>
                        <option value="referral_credits" {{ $walletType === 'referral_credits' ? 'selected' : '' }}>Referral Credits</option>
                    </select>
                    
                    <select name="transaction_type" onchange="this.form.submit()"
                            class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="all" {{ $transactionType === 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="work_earned" {{ $transactionType === 'work_earned' ? 'selected' : '' }}>Work Earned</option>
                        <option value="referral_earned" {{ $transactionType === 'referral_earned' ? 'selected' : '' }}>Referral Earned</option>
                        <option value="withdrawn" {{ $transactionType === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                        <option value="adjusted" {{ $transactionType === 'adjusted' ? 'selected' : '' }}>Adjustments</option>
                    </select>
                </form>
            </div>
        </div>

        @if($transactions->isEmpty())
        <div class="p-12 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No transactions yet</h3>
            <p class="text-sm text-gray-500">Your transaction history will appear here once you start earning.</p>
        </div>
        @else
        <div class="divide-y divide-gray-200">
            @foreach($transactions as $transaction)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl
                            @if($transaction->amount > 0) bg-green-100 @else bg-red-100 @endif">
                            {{ $transaction->icon }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $transaction->type_label }}</p>
                            <p class="text-sm text-gray-500">{{ $transaction->description }}</p>
                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                                <span>{{ $transaction->created_at->format('M d, Y g:i A') }}</span>
                                <span>•</span>
                                <span class="capitalize">{{ str_replace('_', ' ', $transaction->wallet_type) }}</span>
                                @if($transaction->performer)
                                <span>•</span>
                                <span>By {{ $transaction->performer->fullName }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold {{ $transaction->css_class }}">
                            {{ $transaction->formatted_amount }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Balance: ₱{{ number_format($transaction->balance_after, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $transactions->withQueryString()->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection

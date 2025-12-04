@extends('adiutor.layouts.app')

@section('title', 'My Wallet')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Earnings', 'route' => 'adiutor.earnings.index', 'icon' => 'wallet'],
        ['label' => 'My Wallet', 'icon' => 'credit-card'],
    ]" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">My Wallet</h1>
                <p class="text-sm text-neutral-500 mt-1">Track your earnings and balances</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="secondary" href="{{ route('adiutor.earnings.index') }}">
                    <x-lucide-clock class="w-4 h-4" />
                    Time Entries
                </x-ui.button>
                @if($totalAvailable >= 500)
                <x-ui.button variant="primary" href="{{ route('adiutor.earnings.request-form') }}">
                    <x-lucide-banknote class="w-4 h-4" />
                    Request Payout
                </x-ui.button>
                @endif
            </div>
        </div>
    </div>

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Work Earnings Card -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-primary-50 rounded-xl">
                            <x-lucide-briefcase class="w-6 h-6 text-primary-500" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-700">Work Earnings</p>
                            <p class="text-xs text-neutral-500">From approved time entries & fixed rates</p>
                        </div>
                    </div>
                </div>
                <p class="text-3xl font-semibold text-neutral-800">₱{{ number_format($workEarningsBalance, 2) }}</p>
                <p class="text-sm text-neutral-500 mt-1">Available Balance</p>
                
                <div class="mt-4 pt-4 border-t border-neutral-100 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-neutral-500">Pending</p>
                        <p class="font-semibold text-neutral-800">₱{{ number_format($workEarningsPending, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-neutral-500">Withdrawn</p>
                        <p class="font-semibold text-neutral-800">₱{{ number_format($workEarningsWithdrawn, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referral Credits Card -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-success-50 rounded-xl">
                            <x-lucide-users class="w-6 h-6 text-success-500" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-700">Referral Credits</p>
                            <p class="text-xs text-neutral-500">From successful referrals</p>
                        </div>
                    </div>
                </div>
                <p class="text-3xl font-semibold text-neutral-800">₱{{ number_format($referralCreditsBalance, 2) }}</p>
                <p class="text-sm text-neutral-500 mt-1">Available Balance</p>
                
                <div class="mt-4 pt-4 border-t border-neutral-100 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-neutral-500">Pending</p>
                        <p class="font-semibold text-neutral-800">₱{{ number_format($referralCreditsPending, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-neutral-500">Withdrawn</p>
                        <p class="font-semibold text-neutral-800">₱{{ number_format($referralCreditsWithdrawn, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Balance Summary -->
    <x-ui.card class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-neutral-500 uppercase tracking-wider">Total Available Balance</p>
                <p class="text-3xl font-semibold text-neutral-800 mt-1">₱{{ number_format($totalAvailable, 2) }}</p>
            </div>
            <div class="flex flex-col lg:items-end gap-3">
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-warning-400"></span>
                        <span class="text-neutral-600">Pending: ₱{{ number_format($totalPending, 2) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-success-400"></span>
                        <span class="text-neutral-600">Withdrawn: ₱{{ number_format($totalWithdrawn, 2) }}</span>
                    </div>
                </div>
                @if($totalAvailable >= 500)
                <x-ui.button variant="primary" href="{{ route('adiutor.earnings.request-form') }}">
                    <x-lucide-banknote class="w-5 h-5" />
                    Request Payout
                </x-ui.button>
                @else
                <p class="text-sm text-neutral-500">Minimum ₱500 required for payout</p>
                @endif
            </div>
        </div>
    </x-ui.card>

    <!-- Monthly Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">This Month's Earnings</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($monthlyEarnings, 2) }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-trending-up class="w-5 h-5 text-success-500" />
                </div>
            </div>
            @if($earningsChange != 0)
            <div class="mt-3 flex items-center gap-1 text-sm">
                @if($earningsChange > 0)
                <span class="flex items-center gap-1 text-success-600">
                    <x-lucide-arrow-up class="w-3 h-3" />
                    {{ abs($earningsChange) }}%
                </span>
                @else
                <span class="flex items-center gap-1 text-error-600">
                    <x-lucide-arrow-down class="w-3 h-3" />
                    {{ abs($earningsChange) }}%
                </span>
                @endif
                <span class="text-neutral-500">vs last month</span>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Last Month's Earnings</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($lastMonthEarnings, 2) }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-calendar class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Lifetime Earnings</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($totalAvailable + $totalPending + $totalWithdrawn, 2) }}</p>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl">
                    <x-lucide-coins class="w-5 h-5 text-neutral-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <h2 class="text-lg font-semibold text-neutral-800">Transaction History</h2>
                
                <!-- Filters -->
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <select name="wallet_type" onchange="this.form.submit()"
                            class="text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="all" {{ $walletType === 'all' ? 'selected' : '' }}>All Wallets</option>
                        <option value="work_earnings" {{ $walletType === 'work_earnings' ? 'selected' : '' }}>Work Earnings</option>
                        <option value="referral_credits" {{ $walletType === 'referral_credits' ? 'selected' : '' }}>Referral Credits</option>
                    </select>
                    
                    <select name="transaction_type" onchange="this.form.submit()"
                            class="text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
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
            <div class="w-16 h-16 mx-auto bg-neutral-100 rounded-2xl flex items-center justify-center mb-4">
                <x-lucide-receipt class="w-8 h-8 text-neutral-400" />
            </div>
            <h3 class="text-lg font-semibold text-neutral-800 mb-1">No transactions yet</h3>
            <p class="text-sm text-neutral-500">Your transaction history will appear here once you start earning.</p>
        </div>
        @else
        <div class="divide-y divide-neutral-100">
            @foreach($transactions as $transaction)
            <div class="px-6 py-4 hover:bg-neutral-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center
                            @if($transaction->amount > 0) bg-success-50 @else bg-error-50 @endif">
                            @if($transaction->amount > 0)
                                <x-lucide-arrow-down-left class="w-5 h-5 text-success-500" />
                            @else
                                <x-lucide-arrow-up-right class="w-5 h-5 text-error-500" />
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-neutral-800">{{ $transaction->type_label }}</p>
                            <p class="text-sm text-neutral-500">{{ $transaction->description }}</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-neutral-400">
                                <span>{{ $transaction->created_at->format('M d, Y g:i A') }}</span>
                                <span class="text-neutral-300">|</span>
                                <span class="capitalize">{{ str_replace('_', ' ', $transaction->wallet_type) }}</span>
                                @if($transaction->performer)
                                <span class="text-neutral-300">|</span>
                                <span>By {{ $transaction->performer->fullName }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold {{ $transaction->css_class }}">
                            {{ $transaction->formatted_amount }}
                        </p>
                        <p class="text-xs text-neutral-400 mt-1">
                            Balance: ₱{{ number_format($transaction->balance_after, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100">
            {{ $transactions->withQueryString()->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection

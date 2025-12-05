@extends('client.layouts.app')

@section('title', 'Referral Credits')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Referral Program', 'route' => 'client.referrals.dashboard', 'icon' => 'users'],
        ['label' => 'Credits', 'icon' => 'circle-dollar-sign'],
    ]" />

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Referral Credits</h1>
                <p class="text-neutral-500 mt-2">Manage your withdrawable referral earnings</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-8">
        <div class="border-b border-neutral-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('client.referrals.dashboard') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-layout-dashboard class="w-4 h-4" />
                    Dashboard
                </a>
                <a href="{{ route('client.referrals.credits') }}" 
                   class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center gap-2">
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

    <!-- Credits Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Available Credits -->
        <div class="bg-success-600 rounded-2xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Available to Withdraw</h3>
                <x-lucide-wallet class="w-5 h-5 opacity-80" />
            </div>
            <p class="text-3xl font-semibold mb-1">₱{{ number_format($availableCredits, 2) }}</p>
            <p class="text-sm opacity-90">Ready for withdrawal</p>
        </div>

        <!-- Pending Credits -->
        <div class="bg-warning-600 rounded-2xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Pending Approval</h3>
                <x-lucide-clock class="w-5 h-5 opacity-80" />
            </div>
            <p class="text-3xl font-semibold mb-1">₱{{ number_format($pendingCredits, 2) }}</p>
            <p class="text-sm opacity-90">In withdrawal requests</p>
        </div>

        <!-- Total Withdrawn -->
        <div class="bg-primary-600 rounded-2xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Total Withdrawn</h3>
                <x-lucide-check-circle class="w-5 h-5 opacity-80" />
            </div>
            <p class="text-3xl font-semibold mb-1">₱{{ number_format($withdrawnCredits, 2) }}</p>
            <p class="text-sm opacity-90">All-time withdrawals</p>
        </div>
    </div>

    <!-- Withdrawal Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Request Withdrawal Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
                <h2 class="text-lg font-semibold text-neutral-800 mb-6">Request Withdrawal</h2>
                
                @if($availableCredits >= $minWithdrawal)
                    <form method="POST" action="{{ route('client.referrals.withdrawals.request') }}" id="withdrawalForm">
                        @csrf
                        
                        <!-- Amount -->
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-neutral-600 mb-2">
                                Amount (₱)
                            </label>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   step="0.01" 
                                   min="{{ $minWithdrawal }}" 
                                   max="{{ $availableCredits }}"
                                   value="{{ old('amount') }}"
                                   class="w-full px-4 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                   required>
                            <p class="text-xs text-neutral-500 mt-1">
                                Min: ₱{{ number_format($minWithdrawal, 2) }} • Max: ₱{{ number_format($availableCredits, 2) }}
                            </p>
                            @error('amount')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Withdrawal Method -->
                        <div class="mb-4">
                            <label for="withdrawal_method" class="block text-sm font-medium text-neutral-600 mb-2">
                                Withdrawal Method
                            </label>
                            <select name="withdrawal_method" 
                                    id="withdrawal_method" 
                                    class="w-full px-4 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                    required>
                                <option value="">Select method...</option>
                                @foreach($withdrawalMethods as $key => $label)
                                    <option value="{{ $key }}" {{ old('withdrawal_method') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('withdrawal_method')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Account Details -->
                        <div class="mb-6">
                            <label for="account_details" class="block text-sm font-medium text-neutral-600 mb-2">
                                Account Details
                            </label>
                            <textarea name="account_details" 
                                      id="account_details" 
                                      rows="3" 
                                      class="w-full px-4 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                      placeholder="Enter account number, mobile number, or email..."
                                      required>{{ old('account_details') }}</textarea>
                            <p class="text-xs text-neutral-500 mt-1">
                                Provide details for receiving payment (e.g., account number, mobile number)
                            </p>
                            @error('account_details')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <x-lucide-banknote class="w-5 h-5" />
                            Submit Withdrawal Request
                        </button>
                    </form>
                @else
                    <div class="bg-warning-50 border border-warning-100 rounded-xl p-4 text-center">
                        <x-lucide-alert-triangle class="w-10 h-10 mx-auto text-warning-500 mb-3" />
                        <p class="text-neutral-800 font-medium mb-2">Insufficient Balance</p>
                        <p class="text-sm text-neutral-600 mb-3">
                            You need at least <strong>₱{{ number_format($minWithdrawal, 2) }}</strong> to request a withdrawal.
                        </p>
                        <p class="text-sm text-neutral-500">
                            Current balance: <strong>₱{{ number_format($availableCredits, 2) }}</strong>
                        </p>
                    </div>
                @endif

                <div class="mt-6 bg-primary-50 border border-primary-100 rounded-xl p-4">
                    <p class="font-medium text-primary-900 mb-2 text-sm flex items-center gap-1">
                        <x-lucide-lightbulb class="w-4 h-4 text-warning-600" />
                        How to earn credits:
                    </p>
                    <ul class="space-y-1 text-xs text-primary-800">
                        <li class="flex items-start gap-1">
                            <x-lucide-check-circle class="w-4 h-4 mt-0.5 flex-shrink-0 text-success-600" />
                            <span>Share your referral code</span>
                        </li>
                        <li class="flex items-start gap-1">
                            <x-lucide-check-circle class="w-4 h-4 mt-0.5 flex-shrink-0 text-success-600" />
                            <span>Friend completes a project payment</span>
                        </li>
                        <li class="flex items-start gap-1">
                            <x-lucide-check-circle class="w-4 h-4 mt-0.5 flex-shrink-0 text-success-600" />
                            <span>Get 1-3% credits based on payment tier</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Withdrawal History & Transaction Log -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Withdrawal Requests -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
                <h2 class="text-lg font-semibold text-neutral-800 mb-6">Withdrawal Requests</h2>
                
                @if($withdrawals->count() > 0)
                    <div class="space-y-4">
                        @foreach($withdrawals as $withdrawal)
                            <div class="border border-neutral-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-medium text-neutral-800">
                                                ₱{{ number_format($withdrawal->amount, 2) }}
                                            </h3>
                                            @if($withdrawal->status === 'pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-50 text-warning-700">
                                                    <x-lucide-clock class="w-3.5 h-3.5" />
                                                    Pending Review
                                                </span>
                                            @elseif($withdrawal->status === 'processing')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700">
                                                    <x-lucide-loader-2 class="w-3.5 h-3.5 animate-spin" />
                                                    Processing
                                                </span>
                                            @elseif($withdrawal->status === 'completed')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-50 text-success-700">
                                                    <x-lucide-check-circle class="w-3.5 h-3.5" />
                                                    Completed
                                                </span>
                                            @elseif($withdrawal->status === 'rejected')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-50 text-error-700">
                                                    <x-lucide-x-circle class="w-3.5 h-3.5" />
                                                    Rejected
                                                </span>
                                            @elseif($withdrawal->status === 'cancelled')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                                    Cancelled
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-neutral-600 mb-1">
                                            <span class="font-medium">Method:</span> {{ $withdrawalMethods[$withdrawal->withdrawal_method] ?? $withdrawal->withdrawal_method }}
                                        </p>
                                        <p class="text-sm text-neutral-600 mb-1">
                                            <span class="font-medium">Reference:</span> <code class="px-2 py-0.5 bg-neutral-50 rounded text-xs">{{ $withdrawal->withdrawal_number }}</code>
                                        </p>
                                        <p class="text-xs text-neutral-400">
                                            Requested {{ $withdrawal->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('client.referrals.withdrawals.show', $withdrawal) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-neutral-200 text-neutral-700 bg-white hover:bg-neutral-50 text-sm font-medium rounded-lg transition-colors">
                                            View Details
                                        </a>
                                        @if($withdrawal->status === 'pending')
                                            <form method="POST" action="{{ route('client.referrals.withdrawals.cancel', $withdrawal) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return window.Alerts.confirmForm(event, 'Cancel Withdrawal', 'Are you sure you want to cancel this withdrawal request?')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-error-200 text-error-700 bg-white hover:bg-error-50 text-sm font-medium rounded-lg transition-colors">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($withdrawal->admin_notes && in_array($withdrawal->status, ['completed', 'rejected']))
                                    <div class="mt-3 bg-neutral-50 border border-neutral-100 rounded-lg p-3">
                                        <p class="text-xs font-medium text-neutral-600 mb-1">Admin Note:</p>
                                        <p class="text-sm text-neutral-700">{{ $withdrawal->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <x-lucide-receipt class="w-12 h-12 mx-auto text-neutral-300 mb-4" />
                        <p class="text-neutral-600 mb-2">No withdrawal requests yet</p>
                        <p class="text-sm text-neutral-400">Request your first withdrawal when you have enough credits</p>
                    </div>
                @endif
            </div>

            <!-- Transaction History -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
                <h2 class="text-lg font-semibold text-neutral-800 mb-6">Recent Transactions</h2>
                
                @if($transactions->count() > 0)
                    <div class="space-y-3">
                        @foreach($transactions as $transaction)
                            <div class="flex items-center justify-between py-3 border-b border-neutral-100 last:border-0">
                                <div class="flex items-center gap-3">
                                    @if($transaction->transaction_type === 'earned')
                                        <div class="w-10 h-10 bg-success-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <x-lucide-plus class="w-5 h-5 text-success-600" />
                                        </div>
                                    @elseif($transaction->transaction_type === 'withdrawn')
                                        <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <x-lucide-minus class="w-5 h-5 text-primary-600" />
                                        </div>
                                    @elseif($transaction->transaction_type === 'refunded')
                                        <div class="w-10 h-10 bg-warning-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <x-lucide-undo class="w-5 h-5 text-warning-600" />
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-neutral-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <x-lucide-settings class="w-5 h-5 text-neutral-500" />
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <p class="font-medium text-neutral-800">
                                            {{ ucfirst($transaction->transaction_type) }}
                                            @if($transaction->referral)
                                                <span class="text-neutral-500 font-normal">from {{ $transaction->referral->referred->fullName }}</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-neutral-400">{{ $transaction->created_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <p class="font-medium {{ $transaction->amount >= 0 ? 'text-success-600' : 'text-error-600' }}">
                                        {{ $transaction->amount >= 0 ? '+' : '' }}₱{{ number_format(abs($transaction->amount), 2) }}
                                    </p>
                                    <p class="text-xs text-neutral-400">Balance: ₱{{ number_format($transaction->balance_after, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <x-lucide-clipboard-list class="w-16 h-16 mx-auto text-neutral-400 mb-4" />
                        <p class="text-neutral-600 mb-2">No transactions yet</p>
                        <p class="text-sm text-neutral-500">Your credit transactions will appear here</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

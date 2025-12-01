@extends('client.layouts.app')

@section('title', 'Referral Credits')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">💰 Referral Credits</h1>
                <p class="text-neutral-600 mt-2">Manage your withdrawable referral earnings</p>
            </div>
            <a href="{{ route('client.referrals.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-neutral-300 text-neutral-700 bg-white hover:bg-neutral-50 font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Credits Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Available Credits -->
        <div class="bg-gradient-to-br from-success-500 to-success-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Available to Withdraw</h3>
                <svg class="w-6 h-6 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1">₱{{ number_format($availableCredits, 2) }}</p>
            <p class="text-sm opacity-90">Ready for withdrawal</p>
        </div>

        <!-- Pending Credits -->
        <div class="bg-gradient-to-br from-warning-500 to-warning-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Pending Approval</h3>
                <svg class="w-6 h-6 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1">₱{{ number_format($pendingCredits, 2) }}</p>
            <p class="text-sm opacity-90">In withdrawal requests</p>
        </div>

        <!-- Total Withdrawn -->
        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium opacity-90">Total Withdrawn</h3>
                <svg class="w-6 h-6 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold mb-1">₱{{ number_format($withdrawnCredits, 2) }}</p>
            <p class="text-sm opacity-90">All-time withdrawals</p>
        </div>
    </div>

    <!-- Withdrawal Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Request Withdrawal Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6">Request Withdrawal</h2>
                
                @if($availableCredits >= $minWithdrawal)
                    <form method="POST" action="{{ route('client.referrals.withdrawals.request') }}" id="withdrawalForm">
                        @csrf
                        
                        <!-- Amount -->
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-neutral-700 mb-2">
                                Amount (₱)
                            </label>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   step="0.01" 
                                   min="{{ $minWithdrawal }}" 
                                   max="{{ $availableCredits }}"
                                   value="{{ old('amount') }}"
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                   required>
                            <p class="text-xs text-neutral-600 mt-1">
                                Min: ₱{{ number_format($minWithdrawal, 2) }} • Max: ₱{{ number_format($availableCredits, 2) }}
                            </p>
                            @error('amount')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Withdrawal Method -->
                        <div class="mb-4">
                            <label for="withdrawal_method" class="block text-sm font-medium text-neutral-700 mb-2">
                                Withdrawal Method
                            </label>
                            <select name="withdrawal_method" 
                                    id="withdrawal_method" 
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                    required>
                                <option value="">Select method...</option>
                                @foreach($withdrawalMethods as $key => $label)
                                    <option value="{{ $key }}" {{ old('withdrawal_method') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('withdrawal_method')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Account Details -->
                        <div class="mb-6">
                            <label for="account_details" class="block text-sm font-medium text-neutral-700 mb-2">
                                Account Details
                            </label>
                            <textarea name="account_details" 
                                      id="account_details" 
                                      rows="3" 
                                      class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                      placeholder="Enter account number, mobile number, or email..."
                                      required>{{ old('account_details') }}</textarea>
                            <p class="text-xs text-neutral-600 mt-1">
                                Provide details for receiving payment (e.g., account number, mobile number)
                            </p>
                            @error('account_details')
                                <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Submit Withdrawal Request
                        </button>
                    </form>
                @else
                    <div class="bg-warning-50 border border-warning-200 rounded-lg p-4 text-center">
                        <svg class="w-12 h-12 mx-auto text-warning-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-neutral-900 font-semibold mb-2">Insufficient Balance</p>
                        <p class="text-sm text-neutral-700 mb-3">
                            You need at least <strong>₱{{ number_format($minWithdrawal, 2) }}</strong> to request a withdrawal.
                        </p>
                        <p class="text-sm text-neutral-600">
                            Current balance: <strong>₱{{ number_format($availableCredits, 2) }}</strong>
                        </p>
                    </div>
                @endif

                <div class="mt-6 bg-primary-50 border border-primary-200 rounded-lg p-4">
                    <p class="font-semibold text-primary-900 mb-2 text-sm">💡 How to earn credits:</p>
                    <ul class="space-y-1 text-xs text-primary-800">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Share your referral code</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Friend completes a project payment</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Get 1-3% credits based on payment tier</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Withdrawal History & Transaction Log -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Withdrawal Requests -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6">Withdrawal Requests</h2>
                
                @if($withdrawals->count() > 0)
                    <div class="space-y-4">
                        @foreach($withdrawals as $withdrawal)
                            <div class="border border-neutral-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-semibold text-neutral-900">
                                                ₱{{ number_format($withdrawal->amount, 2) }}
                                            </h3>
                                            @if($withdrawal->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Pending Review
                                                </span>
                                            @elseif($withdrawal->status === 'processing')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-700">
                                                    <svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    Processing
                                                </span>
                                            @elseif($withdrawal->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Completed
                                                </span>
                                            @elseif($withdrawal->status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 text-danger-700">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Rejected
                                                </span>
                                            @elseif($withdrawal->status === 'cancelled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-700">
                                                    Cancelled
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-neutral-600 mb-1">
                                            <span class="font-medium">Method:</span> {{ $withdrawalMethods[$withdrawal->withdrawal_method] ?? $withdrawal->withdrawal_method }}
                                        </p>
                                        <p class="text-sm text-neutral-600 mb-1">
                                            <span class="font-medium">Reference:</span> <code class="px-2 py-0.5 bg-neutral-100 rounded text-xs">{{ $withdrawal->withdrawal_number }}</code>
                                        </p>
                                        <p class="text-xs text-neutral-500">
                                            Requested {{ $withdrawal->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('client.referrals.withdrawals.show', $withdrawal) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-neutral-300 text-neutral-700 bg-white hover:bg-neutral-50 text-sm font-medium rounded-lg transition-colors">
                                            View Details
                                        </a>
                                        @if($withdrawal->status === 'pending')
                                            <form method="POST" action="{{ route('client.referrals.withdrawals.cancel', $withdrawal) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to cancel this withdrawal request?')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-danger-300 text-danger-700 bg-white hover:bg-danger-50 text-sm font-medium rounded-lg transition-colors">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($withdrawal->admin_notes && in_array($withdrawal->status, ['completed', 'rejected']))
                                    <div class="mt-3 bg-neutral-50 border border-neutral-200 rounded p-3">
                                        <p class="text-xs font-medium text-neutral-700 mb-1">Admin Note:</p>
                                        <p class="text-sm text-neutral-800">{{ $withdrawal->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                        <p class="text-neutral-600 mb-2">No withdrawal requests yet</p>
                        <p class="text-sm text-neutral-500">Request your first withdrawal when you have enough credits</p>
                    </div>
                @endif
            </div>

            <!-- Transaction History -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6">Recent Transactions</h2>
                
                @if($transactions->count() > 0)
                    <div class="space-y-3">
                        @foreach($transactions as $transaction)
                            <div class="flex items-center justify-between py-3 border-b border-neutral-100 last:border-0">
                                <div class="flex items-center gap-3">
                                    @if($transaction->transaction_type === 'earned')
                                        <div class="w-10 h-10 bg-success-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </div>
                                    @elseif($transaction->transaction_type === 'withdrawn')
                                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                            </svg>
                                        </div>
                                    @elseif($transaction->transaction_type === 'refunded')
                                        <div class="w-10 h-10 bg-warning-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <p class="font-medium text-neutral-900">
                                            {{ ucfirst($transaction->transaction_type) }}
                                            @if($transaction->referral)
                                                <span class="text-neutral-600 font-normal">from {{ $transaction->referral->referred->fullName }}</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-neutral-500">{{ $transaction->created_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <p class="font-semibold {{ $transaction->amount >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                                        {{ $transaction->amount >= 0 ? '+' : '' }}₱{{ number_format(abs($transaction->amount), 2) }}
                                    </p>
                                    <p class="text-xs text-neutral-500">Balance: ₱{{ number_format($transaction->balance_after, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-neutral-600 mb-2">No transactions yet</p>
                        <p class="text-sm text-neutral-500">Your credit transactions will appear here</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.createElement('div');
            toast.className = 'fixed top-20 right-5 z-50 flex items-center gap-3 bg-success-600 text-white px-6 py-3 rounded-lg shadow-lg';
            toast.innerHTML = `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        });
    </script>
    @endpush
@endif

@if(session('error'))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.createElement('div');
            toast.className = 'fixed top-20 right-5 z-50 flex items-center gap-3 bg-danger-600 text-white px-6 py-3 rounded-lg shadow-lg';
            toast.innerHTML = `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        });
    </script>
    @endpush
@endif
@endsection

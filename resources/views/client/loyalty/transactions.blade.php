@extends('client.layouts.app')

@section('title', 'Transaction History')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-neutral-800 mb-2">Transaction History</h1>
            <p class="text-neutral-600">View all your loyalty points transactions</p>
        </div>
        <a href="{{ route('client.loyalty.dashboard') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-neutral-600">Total Earned</p>
                <i class="fas fa-arrow-up text-success-500"></i>
            </div>
            <p class="text-3xl font-bold text-success-600">{{ number_format($stats['total_earned']) }}</p>
            <p class="text-xs text-neutral-500 mt-1">All time</p>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-neutral-600">Total Redeemed</p>
                <i class="fas fa-arrow-down text-warning-500"></i>
            </div>
            <p class="text-3xl font-bold text-warning-600">{{ number_format($stats['total_redeemed']) }}</p>
            <p class="text-xs text-neutral-500 mt-1">All time</p>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-neutral-600">Expired</p>
                <i class="fas fa-clock text-error-500"></i>
            </div>
            <p class="text-3xl font-bold text-error-600">{{ number_format($stats['total_expired']) }}</p>
            <p class="text-xs text-neutral-500 mt-1">All time</p>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-neutral-600">Available</p>
                <i class="fas fa-wallet text-primary-500"></i>
            </div>
            <p class="text-3xl font-bold text-primary-600">{{ number_format($stats['available_points']) }}</p>
            <p class="text-xs text-neutral-500 mt-1">Current balance</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card p-6 mb-8">
        <form method="GET" action="{{ route('client.loyalty.transactions') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Transaction Type -->
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-2">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>Earned</option>
                    <option value="redeemed" {{ request('type') === 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                    <option value="expired" {{ request('type') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="adjusted" {{ request('type') === 'adjusted' ? 'selected' : '' }}>Adjusted</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-2">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-input">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-2">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-input">
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('client.loyalty.transactions') }}" class="btn-secondary">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="glass-card p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-neutral-800">
                <i class="fas fa-list text-primary-600 mr-2"></i>All Transactions
            </h3>
            <div class="flex items-center gap-3">
                <span class="text-sm text-neutral-600">{{ $transactions->total() }} transactions</span>
                <a href="{{ route('client.loyalty.export') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold">
                    <i class="fas fa-download mr-1"></i>Export
                </a>
            </div>
        </div>

        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="text-left">Date</th>
                        <th class="text-left">Type</th>
                        <th class="text-left">Description</th>
                        <th class="text-right">Points</th>
                        <th class="text-right">Balance After</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td class="text-sm text-neutral-600">
                            {{ $transaction->created_at->format('M d, Y') }}
                            <br>
                            <span class="text-xs text-neutral-400">{{ $transaction->created_at->format('g:i A') }}</span>
                        </td>
                        <td>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                {{ $transaction->transaction_type === 'earned' ? 'bg-success-100 text-success-700' : '' }}
                                {{ $transaction->transaction_type === 'redeemed' ? 'bg-warning-100 text-warning-700' : '' }}
                                {{ $transaction->transaction_type === 'expired' ? 'bg-error-100 text-error-700' : '' }}
                                {{ $transaction->transaction_type === 'adjusted' ? 'bg-info-100 text-info-700' : '' }}">
                                <i class="fas fa-{{ 
                                    $transaction->transaction_type === 'earned' ? 'plus' : 
                                    ($transaction->transaction_type === 'redeemed' ? 'minus' : 
                                    ($transaction->transaction_type === 'expired' ? 'clock' : 'edit'))
                                }} mr-2"></i>
                                {{ ucfirst($transaction->transaction_type) }}
                            </div>
                        </td>
                        <td>
                            <p class="text-sm text-neutral-800 font-medium">{{ $transaction->description }}</p>
                            @if($transaction->reference_type)
                            <p class="text-xs text-neutral-500 mt-1">
                                <i class="fas fa-link mr-1"></i>
                                {{ class_basename($transaction->reference_type) }} #{{ $transaction->reference_id }}
                            </p>
                            @endif
                        </td>
                        <td class="text-right">
                            <span class="text-lg font-bold
                                {{ $transaction->points > 0 ? 'text-success-600' : 'text-error-600' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                            </span>
                        </td>
                        <td class="text-right">
                            <span class="text-sm font-semibold text-neutral-700">
                                {{ number_format($transaction->balance_after) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-inbox text-6xl text-neutral-300 mb-4"></i>
            <p class="text-neutral-500 text-lg">No transactions found</p>
            <p class="text-neutral-400 text-sm mt-2">
                @if(request()->hasAny(['type', 'from_date', 'to_date']))
                    Try adjusting your filters
                @else
                    Start earning points by completing payments!
                @endif
            </p>
        </div>
        @endif
    </div>

    <!-- Monthly Summary -->
    @if($monthlyStats->count() > 0)
    <div class="glass-card p-6 mt-8">
        <h3 class="text-lg font-bold text-neutral-800 mb-6">
            <i class="fas fa-chart-line text-success-600 mr-2"></i>Monthly Summary
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($monthlyStats as $month)
            <div class="border border-neutral-200 rounded-lg p-4">
                <p class="text-xs text-neutral-500 mb-2">{{ $month->month }}</p>
                <p class="text-2xl font-bold text-success-600">+{{ number_format($month->earned) }}</p>
                @if($month->redeemed > 0)
                <p class="text-sm text-warning-600 mt-1">-{{ number_format($month->redeemed) }} used</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

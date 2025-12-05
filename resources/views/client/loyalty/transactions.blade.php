@extends('client.layouts.app')

@section('title', 'Transaction History')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Loyalty Rewards', 'route' => 'client.loyalty.dashboard', 'icon' => 'award'],
        ['label' => 'Transaction History', 'icon' => 'receipt'],
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Transaction History</h1>
        <p class="text-sm text-neutral-500 mt-1">View all your loyalty points transactions</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Earned</p>
                    <p class="text-2xl font-semibold text-success-600 mt-1">{{ number_format($stats['total_earned']) }}</p>
                    <p class="text-xs text-neutral-400 mt-2">All time</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-trending-up class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Redeemed</p>
                    <p class="text-2xl font-semibold text-warning-600 mt-1">{{ number_format($stats['total_redeemed']) }}</p>
                    <p class="text-xs text-neutral-400 mt-2">All time</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-trending-down class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Expired</p>
                    <p class="text-2xl font-semibold text-error-600 mt-1">{{ number_format($stats['total_expired']) }}</p>
                    <p class="text-xs text-neutral-400 mt-2">All time</p>
                </div>
                <div class="p-3 bg-error-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-error-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Available</p>
                    <p class="text-2xl font-semibold text-primary-600 mt-1">{{ number_format($stats['available_points']) }}</p>
                    <p class="text-xs text-neutral-400 mt-2">Current balance</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-wallet class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-8">
        <form method="GET" action="{{ route('client.loyalty.transactions') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Transaction Type -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-neutral-700">Type</label>
                <select name="type" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <option value="">All Types</option>
                    <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>Earned</option>
                    <option value="redeemed" {{ request('type') === 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                    <option value="expired" {{ request('type') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="adjusted" {{ request('type') === 'adjusted' ? 'selected' : '' }}>Adjusted</option>
                </select>
            </div>

            <!-- Date From -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-neutral-700">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
            </div>

            <!-- Date To -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-neutral-700">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                    <x-lucide-filter class="w-4 h-4" />
                    Filter
                </button>
                <a href="{{ route('client.loyalty.transactions') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-lg border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                    <x-lucide-rotate-ccw class="w-4 h-4" />
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-neutral-100">
            <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-2">
                <x-lucide-list class="w-5 h-5 text-neutral-400" />
                All Transactions
            </h3>
            <div class="flex items-center gap-4">
                <span class="text-sm text-neutral-500">{{ $transactions->total() }} transactions</span>
            </div>
        </div>

        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Points</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Balance After</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-sm text-neutral-700">{{ $transaction->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-neutral-400">{{ $transaction->created_at->format('g:i A') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $transaction->transaction_type === 'earned' ? 'bg-success-100 text-success-700' : '' }}
                                {{ $transaction->transaction_type === 'redeemed' ? 'bg-warning-100 text-warning-700' : '' }}
                                {{ $transaction->transaction_type === 'expired' ? 'bg-error-100 text-error-700' : '' }}
                                {{ $transaction->transaction_type === 'adjusted' ? 'bg-primary-100 text-primary-700' : '' }}">
                                @if($transaction->transaction_type === 'earned')
                                    <x-lucide-plus class="w-3 h-3" />
                                @elseif($transaction->transaction_type === 'redeemed')
                                    <x-lucide-minus class="w-3 h-3" />
                                @elseif($transaction->transaction_type === 'expired')
                                    <x-lucide-clock class="w-3 h-3" />
                                @else
                                    <x-lucide-pencil class="w-3 h-3" />
                                @endif
                                {{ ucfirst($transaction->transaction_type) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-neutral-700">{{ $transaction->description }}</p>
                            @if($transaction->reference_type)
                            <p class="text-xs text-neutral-400 mt-1 flex items-center gap-1">
                                <x-lucide-link class="w-3 h-3" />
                                {{ class_basename($transaction->reference_type) }} #{{ $transaction->reference_id }}
                            </p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-base font-semibold
                                {{ $transaction->points > 0 ? 'text-success-600' : 'text-error-600' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-medium text-neutral-700">
                                {{ number_format($transaction->balance_after) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-neutral-100">
            <x-ui.pagination :paginator="$transactions" />
        </div>
        @else
        <div class="text-center py-12 px-6">
            <div class="w-14 h-14 bg-neutral-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <x-lucide-inbox class="w-7 h-7 text-neutral-400" />
            </div>
            <p class="text-base font-medium text-neutral-700 mb-1">No transactions found</p>
            <p class="text-sm text-neutral-500">
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
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mt-8">
        <h3 class="text-lg font-medium text-neutral-700 mb-6 flex items-center gap-2">
            <x-lucide-bar-chart-2 class="w-5 h-5 text-success-500" />
            Monthly Summary
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($monthlyStats as $month)
            <div class="bg-neutral-50 border border-neutral-100 rounded-xl p-4">
                <p class="text-xs text-neutral-500 mb-2">{{ $month->month }}</p>
                <p class="text-xl font-semibold text-success-600">+{{ number_format($month->earned) }}</p>
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

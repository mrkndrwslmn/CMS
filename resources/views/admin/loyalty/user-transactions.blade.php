@extends('admin.layouts.app')

@section('title', 'Transactions - ' . $user->fullName)

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Loyalty Program', 'route' => 'admin.loyalty.index', 'icon' => 'award'],
        ['label' => $user->fullName, 'route' => 'admin.loyalty.show', 'routeParams' => ['user' => $user->id], 'icon' => 'user'],
        ['label' => 'Transactions', 'icon' => 'list'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <x-ui.page-header 
                :title="$user->fullName . \"'s Transactions\""
            />
            <div class="flex items-center gap-3 mt-2">
                <x-loyalty.tier-badge :tier="$loyaltyPoint->tier" size="sm" />
                <span class="text-sm text-neutral-500">{{ number_format($loyaltyPoint->available_points) }} points available</span>
            </div>
        </div>
        <div class="flex gap-3">
            <x-ui.button href="{{ route('admin.loyalty.show', $user) }}" variant="secondary">
                <x-lucide-arrow-left class="w-4 h-4" />
                Back to Profile
            </x-ui.button>
            <x-ui.button href="{{ route('admin.loyalty.export-user', $user) }}" variant="primary">
                <x-lucide-download class="w-4 h-4" />
                Export CSV
            </x-ui.button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Earned</p>
                    <p class="text-2xl font-semibold text-success-600 mt-1">{{ number_format($stats['total_earned']) }}</p>
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
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-trending-down class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Expired</p>
                    <p class="text-2xl font-semibold text-error-600 mt-1">{{ number_format($stats['total_expired']) }}</p>
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
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-wallet class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <form action="{{ route('admin.loyalty.user-transactions', $user) }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Transaction Type -->
                <div>
                    <x-ui.select label="Type" name="type">
                        <option value="">All Types</option>
                        <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>Earned</option>
                        <option value="redeemed" {{ request('type') === 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                        <option value="expired" {{ request('type') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="adjusted" {{ request('type') === 'adjusted' ? 'selected' : '' }}>Adjusted</option>
                        <option value="refunded" {{ request('type') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </x-ui.select>
                </div>

                <!-- Date From -->
                <div>
                    <x-ui.input 
                        type="date" 
                        name="date_from" 
                        label="From Date"
                        :value="request('date_from')"
                    />
                </div>

                <!-- Date To -->
                <div>
                    <x-ui.input 
                        type="date" 
                        name="date_to" 
                        label="To Date"
                        :value="request('date_to')"
                    />
                </div>

                <!-- Filter Buttons -->
                <div class="flex items-end gap-2 lg:col-span-2">
                    <x-ui.button type="submit" variant="primary">
                        <x-lucide-search class="w-4 h-4" />
                        Filter
                    </x-ui.button>
                    <x-ui.button href="{{ route('admin.loyalty.user-transactions', $user) }}" variant="secondary">
                        <x-lucide-x class="w-4 h-4" />
                        Clear
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Transactions Table -->
    <x-ui.card>
        <div class="p-6">
            @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Source</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Points</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Balance After</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($transactions as $transaction)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-neutral-600">
                                {{ $transaction->created_at->format('M d, Y') }}<br>
                                <span class="text-xs text-neutral-400">{{ $transaction->created_at->format('g:i A') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge 
                                    :variant="$transaction->transaction_type === 'earned' ? 'success' : ($transaction->transaction_type === 'redeemed' ? 'warning' : ($transaction->transaction_type === 'expired' ? 'error' : ($transaction->transaction_type === 'refunded' ? 'primary' : 'info')))">
                                    {{ ucfirst($transaction->transaction_type) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-700 max-w-xs">
                                <p class="truncate">{{ $transaction->description }}</p>
                                @if($transaction->performedBy)
                                <p class="text-xs text-neutral-400 mt-1">
                                    by {{ $transaction->performedBy->fullName }}
                                </p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-600">
                                {{ str_replace('_', ' ', ucfirst($transaction->source ?? 'N/A')) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-lg font-bold {{ $transaction->points > 0 ? 'text-success-600' : 'text-error-600' }}">
                                    {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-neutral-700">
                                {{ number_format($transaction->balance_after) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <x-ui.pagination :paginator="$transactions->withQueryString()" />
            </div>
            @else
            <div class="text-center py-12">
                <x-lucide-inbox class="w-12 h-12 mx-auto text-neutral-300 mb-4" />
                <p class="text-neutral-500">No transactions found</p>
                <p class="text-sm text-neutral-400 mt-1">Try adjusting your filters</p>
            </div>
            @endif
        </div>
    </x-ui.card>
</div>
@endsection

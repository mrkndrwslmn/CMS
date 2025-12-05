@extends('admin.layouts.app')

@section('title', 'All Loyalty Transactions')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Loyalty Program', 'route' => 'admin.loyalty.index', 'icon' => 'award'],
        ['label' => 'All Transactions', 'icon' => 'list'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="All Transactions" 
            description="View and filter all loyalty point transactions"
        />
        <x-ui.button href="{{ route('admin.loyalty.index') }}" variant="secondary">
            <x-lucide-arrow-left class="w-4 h-4" />
            Back to Loyalty
        </x-ui.button>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <form action="{{ route('admin.loyalty.transactions') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <x-ui.input 
                        type="text" 
                        name="search" 
                        placeholder="Search by user name or email"
                        value="{{ request('search') }}"
                    />
                </div>

                <!-- Transaction Type -->
                <div>
                    <x-ui.select name="type">
                        <option value="">All Types</option>
                        <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>Earned</option>
                        <option value="redeemed" {{ request('type') === 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                        <option value="expired" {{ request('type') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="adjusted" {{ request('type') === 'adjusted' ? 'selected' : '' }}>Adjusted</option>
                        <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Refund</option>
                    </x-ui.select>
                </div>

                <!-- Date From -->
                <div>
                    <x-ui.input 
                        type="date" 
                        name="date_from" 
                        placeholder="From date"
                        value="{{ request('date_from') }}"
                    />
                </div>

                <!-- Date To -->
                <div>
                    <x-ui.input 
                        type="date" 
                        name="date_to" 
                        placeholder="To date"
                        value="{{ request('date_to') }}"
                    />
                </div>

                <!-- Filter Button -->
                <div class="flex gap-2">
                    <x-ui.button type="submit" variant="primary" class="flex-1">
                        <x-lucide-search class="w-4 h-4" />
                        Filter
                    </x-ui.button>
                    <x-ui.button href="{{ route('admin.loyalty.transactions') }}" variant="secondary">
                        <x-lucide-x class="w-4 h-4" />
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Points</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Balance After</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
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
                                @if($transaction->user)
                                <div>
                                    <p class="text-sm font-medium text-neutral-800">{{ $transaction->user->fullName }}</p>
                                    <p class="text-xs text-neutral-500">{{ $transaction->user->email }}</p>
                                </div>
                                @else
                                <span class="text-sm text-neutral-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.badge 
                                    :variant="$transaction->transaction_type === 'earned' ? 'success' : ($transaction->transaction_type === 'redeemed' ? 'warning' : ($transaction->transaction_type === 'expired' ? 'error' : 'info'))">
                                    {{ ucfirst($transaction->transaction_type) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-700 max-w-xs truncate">
                                {{ $transaction->description }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-lg font-bold {{ $transaction->points > 0 ? 'text-success-600' : 'text-error-600' }}">
                                    {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-neutral-700">
                                {{ number_format($transaction->balance_after) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($transaction->user)
                                <x-ui.button href="{{ route('admin.loyalty.show', $transaction->user) }}" variant="secondary" size="sm">
                                    <x-lucide-eye class="w-4 h-4" />
                                </x-ui.button>
                                @endif
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

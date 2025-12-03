@extends('admin.layouts.app')

@section('title', 'Payment Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Payments', 'icon' => 'credit-card'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <x-ui.page-header 
            title="Payment Management" 
            description="Track and manage all payments"
        />
        
        <a href="{{ route('admin.payments.export', request()->query()) }}">
            <x-ui.button variant="secondary">
                <x-lucide-download class="w-4 h-4" />
                Export CSV
            </x-ui.button>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Total Payments</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-credit-card class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-warning-600 mt-1">{{ $stats['pending'] }}</p>
                    <p class="text-xs text-neutral-400 mt-1">₱{{ number_format($stats['pending_amount'], 2) }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Confirmed</p>
                    <p class="text-2xl font-semibold text-success-600 mt-1">{{ $stats['confirmed'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Total Revenue</p>
                    <p class="text-2xl font-semibold text-success-600 mt-1">₱{{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-banknote class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Filters -->
    <x-ui.card class="p-6 mb-6">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Reference, Transaction ID, Project..."
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Payment Method</label>
                    <select name="payment_method" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="">All Methods</option>
                        <option value="maya" {{ request('payment_method') == 'maya' ? 'selected' : '' }}>Maya</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.payments.index') }}">
                    <x-ui.button type="button" variant="ghost">
                        Clear Filters
                    </x-ui.button>
                </a>
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-filter class="w-4 h-4" />
                    Apply Filters
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <!-- Payments Table -->
    <x-ui.card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                            #{{ $payment->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-800">{{ $payment->serviceRequest->project_name ?? 'N/A' }}</div>
                            <div class="text-xs text-neutral-400">Request #{{ $payment->service_request_id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-700">{{ $payment->payment_reference }}</div>
                            @if($payment->transaction_id)
                            <div class="text-xs text-neutral-400">TXN: {{ $payment->transaction_id }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-neutral-800">
                            ₱{{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                            <span class="capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payment->status == 'confirmed') bg-success-100 text-success-700
                                @elseif($payment->status == 'pending') bg-warning-100 text-warning-700
                                @elseif($payment->status == 'failed') bg-error-100 text-error-700
                                @elseif($payment->status == 'refunded') bg-primary-100 text-primary-700
                                @else bg-neutral-100 text-neutral-600
                                @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            {{ $payment->created_at->format('M d, Y') }}
                            <div class="text-xs text-neutral-400">{{ $payment->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('admin.payments.show', $payment->id) }}" 
                               class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-medium transition-colors">
                                View Details
                                <x-lucide-chevron-right class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neutral-100 mb-4">
                                <x-lucide-credit-card class="w-8 h-8 text-neutral-400" />
                            </div>
                            <p class="text-neutral-600 text-base font-medium">No payments found</p>
                            <p class="text-neutral-400 text-sm mt-1">Try adjusting your filters</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100">
            {{ $payments->links() }}
        </div>
        @endif
    </x-ui.card>
</div>
@endsection

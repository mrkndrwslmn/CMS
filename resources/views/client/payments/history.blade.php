@extends('client.layouts.app')

@section('title', 'Payment History')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Payment History', 'icon' => 'credit-card'],
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Payment History</h1>
        <p class="text-sm text-neutral-500 mt-1">Track your payment transactions and invoices</p>
    </div>

    <!-- Statistics Cards -->
    <div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Payments -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Payments</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-neutral-50 rounded-xl">
                        <x-lucide-receipt class="w-5 h-5 text-neutral-400" />
                    </div>
                </div>
            </div>

            <!-- Confirmed Payments -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Confirmed</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['confirmed'] }}</p>
                    </div>
                    <div class="p-3 bg-success-50 rounded-xl">
                        <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Pending</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="p-3 bg-warning-50 rounded-xl">
                        <x-lucide-clock class="w-5 h-5 text-warning-500" />
                    </div>
                </div>
            </div>

            <!-- Total Spent -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Spent</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($stats['total_spent'], 2) }}</p>
                    </div>
                    <div class="p-3 bg-primary-50 rounded-xl">
                        <x-lucide-wallet class="w-5 h-5 text-primary-500" />
                    </div>
                </div>
            </div>
        </div>            <!-- Filters -->
            <div class="bg-white/90 backdrop-blur-sm rounded-xl border border-neutral-200/60 p-6 shadow-sm mb-6">
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Search</label>
                            <input type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Reference or project name..."
                                class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Date From</label>
                            <input type="date" 
                                name="date_from" 
                                value="{{ request('date_from') }}"
                                class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Date To</label>
                            <input type="date" 
                                name="date_to" 
                                value="{{ request('date_to') }}"
                                class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        </div>

                        <!-- Filter Button -->
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                                <x-lucide-filter class="w-4 h-4" />
                                Filter
                            </button>
                        </div>
                    </div>

                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <div class="flex justify-end">
                            <a href="{{ route('client.payments.history') }}" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-neutral-600 hover:text-neutral-800 transition-colors">
                                <x-lucide-x class="w-4 h-4" />
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Payments List -->
            <div class="bg-white/90 backdrop-blur-sm rounded-xl border border-neutral-200/60 shadow-sm overflow-hidden">
                @if($payments->isEmpty())
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center py-16 px-8 text-center">
                        <div class="w-16 h-16 bg-neutral-100 rounded-2xl flex items-center justify-center mb-6">
                            <x-lucide-receipt class="w-8 h-8 text-neutral-400" />
                        </div>
                        <h3 class="text-lg font-semibold text-neutral-800 mb-2">No payments found</h3>
                        <p class="text-neutral-500 mb-6 max-w-sm">
                            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                                No payments match your current filters. Try adjusting your search criteria.
                            @else
                                You haven't made any payments yet. Payment history will appear here once you make transactions.
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('client.payments.history') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                                <x-lucide-x class="w-4 h-4" />
                                Clear Filters
                            </a>
                        @endif
                    </div>
                @else
                    <!-- Payments Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-neutral-50/80 border-b border-neutral-200/60">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Payment Details</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Project</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-neutral-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                @foreach($payments as $payment)
                                    <tr class="hover:bg-neutral-50 transition-colors">
                                        <!-- Payment Details -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-10 bg-neutral-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                    <x-lucide-receipt class="w-5 h-5 text-neutral-500" />
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-neutral-900">{{ $payment->payment_reference }}</p>
                                                    <p class="text-sm text-neutral-600">{{ $payment->payment_method ?? 'Online Payment' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Project -->
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="font-medium text-neutral-900">{{ $payment->serviceRequest->project_name ?? 'N/A' }}</p>
                                                <p class="text-sm text-neutral-600">#{{ $payment->service_request_id }}</p>
                                            </div>
                                        </td>

                                        <!-- Amount -->
                                        <td class="px-6 py-4">
                                            <p class="text-lg font-bold text-neutral-900">₱{{ number_format($payment->amount, 2) }}</p>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-4">
                                            @php
                                                $statusConfig = [
                                                    'confirmed' => ['bg' => 'bg-success-100', 'text' => 'text-success-800', 'dot' => 'bg-success-500', 'label' => 'Confirmed'],
                                                    'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-800', 'dot' => 'bg-warning-500', 'label' => 'Pending'],
                                                    'failed' => ['bg' => 'bg-error-100', 'text' => 'text-error-800', 'dot' => 'bg-error-500', 'label' => 'Failed'],
                                                ];
                                                $config = $statusConfig[$payment->status] ?? ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'dot' => 'bg-neutral-500', 'label' => ucfirst($payment->status)];
                                            @endphp
                                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                                <div class="w-2 h-2 rounded-full {{ $config['dot'] }}"></div>
                                                {{ $config['label'] }}
                                            </span>
                                        </td>

                                        <!-- Date -->
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="font-medium text-neutral-900">{{ $payment->created_at->format('M d, Y') }}</p>
                                                <p class="text-sm text-neutral-600">{{ $payment->created_at->format('g:i A') }}</p>
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('client.payments.show', $payment->id) }}" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors text-sm font-medium">
                                                    <x-lucide-eye class="w-4 h-4" />
                                                    View
                                                </a>
                                                @if($payment->status === 'confirmed')
                                                    <a href="{{ route('client.payments.receipt', $payment->id) }}" 
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-neutral-600 hover:text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors text-sm font-medium">
                                                        <x-lucide-download class="w-4 h-4" />
                                                        Receipt
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($payments->hasPages())
                        <div class="px-6 py-4 bg-neutral-50/50 border-t border-neutral-100">
                            <div class="flex items-center justify-between">
                                <x-ui.pagination :paginator="$payments" />
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
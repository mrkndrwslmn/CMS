@extends('client.layouts.app')

@section('title', 'Payment History')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb Navigation -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ route('client.dashboard') }}" class="hover:text-primary-600 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium text-primary-600">Payment History</span>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">Payment History</h1>
            <p class="text-gray-600 mt-1">Track your payment transactions and invoices</p>
        </div>

        <!-- Statistics Cards -->
        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Payments -->
                <div class="bg-white/90 backdrop-blur-sm rounded-xl border border-neutral-200/60 p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-neutral-900">{{ $stats['total'] }}</p>
                            <p class="text-sm text-neutral-600">Total Payments</p>
                        </div>
                    </div>
                </div>

                <!-- Confirmed Payments -->
                <div class="bg-white/90 backdrop-blur-sm rounded-xl border border-neutral-200/60 p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-success-100 to-success-200 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-neutral-900">{{ $stats['confirmed'] }}</p>
                            <p class="text-sm text-neutral-600">Confirmed</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-white/90 backdrop-blur-sm rounded-xl border border-neutral-200/60 p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-warning-100 to-warning-200 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-neutral-900">{{ $stats['pending'] }}</p>
                            <p class="text-sm text-neutral-600">Pending</p>
                        </div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-white/90 backdrop-blur-sm rounded-xl border border-neutral-200/60 p-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-accent-100 to-secondary-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-neutral-900">₱{{ number_format($stats['total_spent'], 2) }}</p>
                            <p class="text-sm text-neutral-600">Total Spent</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
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
                                    class="w-full px-4 py-2 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                Filter
                            </button>
                        </div>
                    </div>

                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <div class="flex justify-end">
                            <a href="{{ route('client.payments.history') }}" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-neutral-600 hover:text-neutral-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
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
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-accent-100 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-2">No payments found</h3>
                        <p class="text-neutral-600 mb-6 max-w-sm">
                            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                                No payments match your current filters. Try adjusting your search criteria.
                            @else
                                You haven't made any payments yet. Payment history will appear here once you make transactions.
                            @endif
                        </p>
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('client.payments.history') }}" 
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
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
                                    <tr class="hover:bg-gradient-to-r hover:from-primary-50/30 hover:to-accent-50/20 transition-all duration-200">
                                        <!-- Payment Details -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-neutral-100 to-neutral-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
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
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-all duration-200 text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View
                                                </a>
                                                @if($payment->status === 'confirmed')
                                                    <a href="{{ route('client.payments.receipt', $payment->id) }}" 
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-accent-600 hover:text-accent-700 hover:bg-accent-50 rounded-lg transition-all duration-200 text-sm font-medium">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
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
                                <div class="text-sm text-neutral-600">
                                    Showing {{ $payments->firstItem() }}-{{ $payments->lastItem() }} of {{ $payments->total() }} payments
                                </div>
                                <div class="flex items-center gap-2">
                                    {{ $payments->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
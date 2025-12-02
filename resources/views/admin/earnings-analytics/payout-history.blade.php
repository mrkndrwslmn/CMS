@extends('admin.layouts.app')

@section('title', 'Payout History')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 200px;
    }
</style>
@endpush

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 text-sm text-neutral-500">
                            <li><a href="{{ route('admin.earnings-analytics.index') }}" class="hover:text-accent-500">Earnings Analytics</a></li>
                            <li><i class="fas fa-chevron-right mx-2 text-xs"></i></li>
                            <li class="text-primary-500 font-medium">Payout History</li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-semibold text-primary-500">
                        <i class="fas fa-wallet text-tertiary-500 mr-2"></i>
                        Payout History
                    </h1>
                    <p class="text-neutral-500 mt-1">Complete history of all adiutor payouts</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.earnings-analytics.export', ['type' => 'payouts', 'period' => request('period', 90)]) }}" 
                       class="glass-button-accent rounded-lg px-4 py-2 flex items-center text-sm">
                        <i class="fas fa-download mr-2"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-primary-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Total Payouts</div>
                <div class="text-xl font-bold text-primary-500">{{ number_format($stats['total_payouts']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-success-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Completed</div>
                <div class="text-xl font-bold text-success-500">{{ number_format($stats['completed_payouts']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-warning-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Pending</div>
                <div class="text-xl font-bold text-warning-500">{{ number_format($stats['pending_payouts']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-accent-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Total Paid</div>
                <div class="text-xl font-bold text-accent-500">₱{{ number_format($stats['total_paid'], 0) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-tertiary-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Pending Amount</div>
                <div class="text-xl font-bold text-tertiary-500">₱{{ number_format($stats['pending_amount'], 0) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-secondary-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Avg Payout</div>
                <div class="text-xl font-bold text-secondary-500">₱{{ number_format($stats['average_payout'] ?? 0, 0) }}</div>
            </div>
        </div>

        <!-- Monthly Trend Chart -->
        @if($monthlyTrend->count() > 0)
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
            <h5 class="text-lg font-bold text-primary-500 mb-4">
                <i class="fas fa-chart-bar text-accent-500 mr-2"></i>
                Monthly Payout Trend
            </h5>
            <div class="chart-container">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Period</label>
                    <select name="period" class="form-select rounded-lg border-neutral-200 w-full text-sm">
                        <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ request('period', '90') == '90' ? 'selected' : '' }}>Last 90 days</option>
                        <option value="180" {{ request('period') == '180' ? 'selected' : '' }}>Last 6 months</option>
                        <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <select name="status" class="form-select rounded-lg border-neutral-200 w-full text-sm">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Adiutor</label>
                    <select name="adiutor_id" class="form-select rounded-lg border-neutral-200 w-full text-sm">
                        <option value="">All Adiutors</option>
                        @foreach($adiutors as $adiutor)
                            <option value="{{ $adiutor->id }}" {{ $adiutorId == $adiutor->id ? 'selected' : '' }}>
                                {{ $adiutor->fullName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="glass-button-accent rounded-lg px-4 py-2 text-sm">
                        <i class="fas fa-filter mr-2"></i> Apply
                    </button>
                </div>
                <div>
                    <a href="{{ route('admin.earnings-analytics.payout-history') }}" class="glass-button rounded-lg px-4 py-2 text-sm">
                        <i class="fas fa-times mr-2"></i> Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Payouts Table -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-100">
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Payout #</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Adiutor</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Amount</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-center">Status</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Method</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Reference</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Requested</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Processed By</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                            <tr class="border-b border-neutral-50 hover:bg-neutral-50">
                                <td class="py-3 px-5">
                                    <span class="font-medium font-mono text-primary-500">{{ $payout->payout_number }}</span>
                                </td>
                                <td class="py-3 px-5">
                                    <div class="font-medium text-primary-500">{{ $payout->adiutor?->fullName ?? 'N/A' }}</div>
                                    <div class="text-xs text-neutral-500">{{ $payout->adiutor?->email }}</div>
                                </td>
                                <td class="py-3 px-5 text-right font-bold text-success-500">
                                    {{ $payout->getFormattedAmount() }}
                                </td>
                                <td class="py-3 px-5 text-center">
                                    @php
                                        $statusIcons = [
                                            'pending' => 'fas fa-clock',
                                            'processing' => 'fas fa-spinner',
                                            'completed' => 'fas fa-check-circle',
                                            'cancelled' => 'fas fa-times-circle',
                                            'failed' => 'fas fa-exclamation-circle',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $payout->getStatusBadgeClass() }}">
                                        <i class="{{ $statusIcons[$payout->status] ?? 'fas fa-question-circle' }} mr-1"></i>
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 text-neutral-600">
                                    {{ $payout->payout_method ?? '-' }}
                                </td>
                                <td class="py-3 px-5">
                                    @if($payout->reference_number)
                                        <span class="font-mono text-xs bg-neutral-100 px-2 py-1 rounded">{{ $payout->reference_number }}</span>
                                    @else
                                        <span class="text-neutral-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-5 text-xs text-neutral-600">
                                    @if($payout->requested_at)
                                        <div>{{ $payout->requested_at->format('M d, Y') }}</div>
                                        <div class="text-neutral-400">{{ $payout->requested_at->format('H:i') }}</div>
                                    @else
                                        <span class="text-neutral-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-5">
                                    @if($payout->processedBy)
                                        <div class="text-sm text-neutral-600">{{ $payout->processedBy->fullName }}</div>
                                        @if($payout->completed_at)
                                            <div class="text-xs text-neutral-400">{{ $payout->completed_at->format('M d, Y') }}</div>
                                        @endif
                                    @else
                                        <span class="text-neutral-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-5 text-right">
                                    <a href="{{ route('admin.payouts.show', $payout->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-accent-50 text-accent-600 rounded-lg hover:bg-accent-100 transition-colors text-xs font-medium">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-neutral-500">
                                    <i class="fas fa-wallet text-5xl mb-4 opacity-30"></i>
                                    <p>No payouts found for the selected filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($payouts->hasPages())
                <div class="p-5 border-t border-neutral-100">
                    {{ $payouts->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($monthlyTrend->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
    const trendData = @json($monthlyTrend);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: trendData.map(d => d.month),
            datasets: [{
                label: 'Payout Amount',
                data: trendData.map(d => d.total),
                backgroundColor: 'rgba(14, 165, 233, 0.7)',
                borderColor: '#0ea5e9',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const data = trendData[context.dataIndex];
                            return [
                                'Amount: ₱' + context.raw.toLocaleString('en-US', {minimumFractionDigits: 2}),
                                'Payouts: ' + data.count
                            ];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endif
@endpush

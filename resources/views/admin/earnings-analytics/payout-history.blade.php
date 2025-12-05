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
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Earnings Analytics', 'route' => 'admin.earnings-analytics.index', 'icon' => 'bar-chart-2'],
            ['label' => 'Payout History', 'icon' => 'wallet'],
        ]" />

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800">
                        Payout History
                    </h1>
                    <p class="text-sm text-neutral-500 mt-1">Complete history of all adiutor payouts</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.earnings-analytics.export', ['type' => 'payouts', 'period' => request('period', 90)]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <x-lucide-download class="w-4 h-4" /> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Total Payouts</p>
                        <p class="text-xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_payouts']) }}</p>
                    </div>
                    <div class="p-2 bg-primary-50 rounded-lg">
                        <x-lucide-list class="w-4 h-4 text-primary-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Completed</p>
                        <p class="text-xl font-semibold text-success-600 mt-1">{{ number_format($stats['completed_payouts']) }}</p>
                    </div>
                    <div class="p-2 bg-success-50 rounded-lg">
                        <x-lucide-check-circle class="w-4 h-4 text-success-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Pending</p>
                        <p class="text-xl font-semibold text-warning-600 mt-1">{{ number_format($stats['pending_payouts']) }}</p>
                    </div>
                    <div class="p-2 bg-warning-50 rounded-lg">
                        <x-lucide-clock class="w-4 h-4 text-warning-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Total Paid</p>
                        <p class="text-xl font-semibold text-primary-600 mt-1">₱{{ number_format($stats['total_paid'], 0) }}</p>
                    </div>
                    <div class="p-2 bg-primary-50 rounded-lg">
                        <x-lucide-banknote class="w-4 h-4 text-primary-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Pending Amount</p>
                        <p class="text-xl font-semibold text-neutral-600 mt-1">₱{{ number_format($stats['pending_amount'], 0) }}</p>
                    </div>
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <x-lucide-hourglass class="w-4 h-4 text-neutral-400" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Avg Payout</p>
                        <p class="text-xl font-semibold text-neutral-600 mt-1">₱{{ number_format($stats['average_payout'] ?? 0, 0) }}</p>
                    </div>
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <x-lucide-calculator class="w-4 h-4 text-neutral-400" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Trend Chart -->
        @if($monthlyTrend->count() > 0)
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5 mb-6">
            <h5 class="text-lg font-medium text-neutral-700 mb-4 flex items-center gap-2">
                <x-lucide-bar-chart-2 class="w-5 h-5 text-neutral-400" />
                Monthly Payout Trend
            </h5>
            <div class="chart-container">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Period</label>
                    <select name="period" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ request('period', '90') == '90' ? 'selected' : '' }}>Last 90 days</option>
                        <option value="180" {{ request('period') == '180' ? 'selected' : '' }}>Last 6 months</option>
                        <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Adiutor</label>
                    <select name="adiutor_id" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="">All Adiutors</option>
                        @foreach($adiutors as $adiutor)
                            <option value="{{ $adiutor->id }}" {{ $adiutorId == $adiutor->id ? 'selected' : '' }}>
                                {{ $adiutor->fullName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <x-lucide-filter class="w-4 h-4" /> Apply
                    </button>
                </div>
                <div>
                    <a href="{{ route('admin.earnings-analytics.payout-history') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-lg border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                        <x-lucide-x class="w-4 h-4" /> Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Payouts Table -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Payout #</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Adiutor</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Amount</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center">Status</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Method</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Reference</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Requested</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Processed By</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($payouts as $payout)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-3 px-5">
                                    <span class="font-medium font-mono text-neutral-700">{{ $payout->payout_number }}</span>
                                </td>
                                <td class="py-3 px-5">
                                    <div class="font-medium text-neutral-700">{{ $payout->adiutor?->fullName ?? 'N/A' }}</div>
                                    <div class="text-xs text-neutral-500">{{ $payout->adiutor?->email }}</div>
                                </td>
                                <td class="py-3 px-5 text-right font-bold text-success-600">
                                    {{ $payout->getFormattedAmount() }}
                                </td>
                                <td class="py-3 px-5 text-center">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['icon' => 'clock', 'class' => 'bg-warning-100 text-warning-700'],
                                            'processing' => ['icon' => 'loader', 'class' => 'bg-primary-100 text-primary-700'],
                                            'completed' => ['icon' => 'check-circle', 'class' => 'bg-success-100 text-success-700'],
                                            'cancelled' => ['icon' => 'x-circle', 'class' => 'bg-error-100 text-error-700'],
                                            'failed' => ['icon' => 'alert-circle', 'class' => 'bg-error-100 text-error-700'],
                                        ];
                                        $config = $statusConfig[$payout->status] ?? ['icon' => 'help-circle', 'class' => 'bg-neutral-100 text-neutral-700'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $config['class'] }}">
                                        <x-dynamic-component :component="'lucide-' . $config['icon']" class="w-3 h-3" />
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
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-neutral-50 text-neutral-600 rounded-lg hover:bg-neutral-100 transition-colors text-xs font-medium">
                                        <x-lucide-eye class="w-3 h-3" /> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-neutral-500">
                                    <x-lucide-wallet class="w-12 h-12 mx-auto mb-3 text-neutral-300" />
                                    <p class="text-sm">No payouts found for the selected filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($payouts->hasPages())
                <div class="p-5 border-t border-neutral-100">
                    <x-ui.pagination :paginator="$payouts->withQueryString()" />
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

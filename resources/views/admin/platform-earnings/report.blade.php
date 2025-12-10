@extends('admin.layouts.app')

@section('title', 'Platform Earnings Report')

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Platform Earnings', 'route' => 'admin.platform-earnings.index', 'icon' => 'trending-up'],
            ['label' => 'Report', 'icon' => 'file-text'],
        ]" />

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800 flex items-center gap-3">
                        <div class="p-2 bg-primary-50 rounded-xl">
                            <x-lucide-file-text class="w-6 h-6 text-primary-600" />
                        </div>
                        Platform Earnings Report
                    </h1>
                    <p class="text-neutral-500 mt-1">Comprehensive analysis of platform revenue</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.platform-earnings.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-xl transition-colors">
                        <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                        Back to Dashboard
                    </a>
                    <a href="{{ route('admin.platform-earnings.export') }}?year={{ $year }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-xl transition-colors">
                        <x-lucide-download class="w-4 h-4 mr-2" />
                        Export Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Period Filter -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5 mb-6">
            <form method="GET" class="flex flex-wrap items-center gap-4">
                <div>
                    <label class="block text-sm text-neutral-500 mb-1">Period</label>
                    <select name="period" onchange="this.form.submit()" 
                            class="border border-neutral-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                        <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-neutral-500 mb-1">Year</label>
                    <select name="year" onchange="this.form.submit()" 
                            class="border border-neutral-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                @if($period === 'daily' || $period === 'weekly')
                    <div>
                        <label class="block text-sm text-neutral-500 mb-1">Month</label>
                        <select name="month" onchange="this.form.submit()" 
                                class="border border-neutral-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-success-500 to-success-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-success-100 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold mt-2">₱{{ number_format($stats['total_platform_revenue'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <p class="text-neutral-500 text-sm font-medium">Platform Fees</p>
                <p class="text-2xl font-bold text-blue-600 mt-2">₱{{ number_format($stats['total_platform_fee'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <p class="text-neutral-500 text-sm font-medium">Margin Earnings</p>
                <p class="text-2xl font-bold text-purple-600 mt-2">₱{{ number_format($stats['total_margin_earnings'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <p class="text-neutral-500 text-sm font-medium">Adiutor Payouts</p>
                <p class="text-2xl font-bold text-warning-600 mt-2">₱{{ number_format($stats['total_adiutor_cost'] ?? 0, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Revenue Over Time Chart -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
                <div class="p-5 border-b border-neutral-100">
                    <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <x-lucide-line-chart class="w-5 h-5 text-primary-500" />
                        Revenue Over Time
                    </h3>
                </div>
                <div class="p-5">
                    @if(count($revenueData) > 0)
                        <div class="h-64">
                            <canvas id="revenueLineChart"></canvas>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-neutral-500">
                            <x-lucide-line-chart class="w-12 h-12 mb-3 text-neutral-300" />
                            <p>No data for this period</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Revenue Composition -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
                <div class="p-5 border-b border-neutral-100">
                    <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <x-lucide-pie-chart class="w-5 h-5 text-primary-500" />
                        Revenue Composition
                    </h3>
                </div>
                <div class="p-5">
                    @php
                        $totalRev = ($stats['total_platform_fee'] ?? 0) + ($stats['total_margin_earnings'] ?? 0);
                    @endphp
                    @if($totalRev > 0)
                        <div class="h-64">
                            <canvas id="compositionChart"></canvas>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-neutral-500">
                            <x-lucide-pie-chart class="w-12 h-12 mb-3 text-neutral-300" />
                            <p>No revenue data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Earning Projects -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
            <div class="p-5 border-b border-neutral-100">
                <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                    <x-lucide-trophy class="w-5 h-5 text-warning-500" />
                    Top Earning Projects
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Rank</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Project</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Budget</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Platform Fee</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Margin</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Total Revenue</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($topProjects as $index => $earning)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $index < 3 ? 'bg-warning-100 text-warning-700' : 'bg-neutral-100 text-neutral-600' }} font-bold">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <a href="{{ route('admin.platform-earnings.show', $earning->project_id) }}" 
                                       class="font-medium text-neutral-800 hover:text-primary-600">
                                        {{ Str::limit($earning->project->title ?? 'Unknown', 40) }}
                                    </a>
                                    <div class="text-xs text-neutral-500">{{ $earning->project->client->fullName ?? 'No Client' }}</div>
                                </td>
                                <td class="py-4 px-5 text-right text-neutral-600">
                                    ₱{{ number_format($earning->project_budget ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-blue-600 font-medium">
                                    ₱{{ number_format($earning->platform_fee ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-purple-600 font-medium">
                                    ₱{{ number_format($earning->margin_earnings ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-success-600 font-bold">
                                    ₱{{ number_format($earning->total_platform_revenue ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-50 text-success-700">
                                        Finalized
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-neutral-500">
                                    <x-lucide-inbox class="w-12 h-12 mx-auto mb-3 text-neutral-300" />
                                    <p>No finalized projects yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Line Chart
    const lineCtx = document.getElementById('revenueLineChart');
    if (lineCtx) {
        const revenueData = @json($revenueData);
        
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(d => d.period),
                datasets: [
                    {
                        label: 'Total Revenue',
                        data: revenueData.map(d => parseFloat(d.total_revenue || 0)),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'Platform Fees',
                        data: revenueData.map(d => parseFloat(d.platform_fee || 0)),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'transparent',
                        tension: 0.3,
                    },
                    {
                        label: 'Margin',
                        data: revenueData.map(d => parseFloat(d.margin_earnings || 0)),
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'transparent',
                        tension: 0.3,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.raw.toLocaleString('en-PH', {minimumFractionDigits: 2});
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
    }

    // Composition Pie Chart
    const pieCtx = document.getElementById('compositionChart');
    if (pieCtx) {
        const platformFee = {{ $stats['total_platform_fee'] ?? 0 }};
        const marginEarnings = {{ $stats['total_margin_earnings'] ?? 0 }};
        
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Platform Fees', 'Margin Earnings'],
                datasets: [{
                    data: [platformFee, marginEarnings],
                    backgroundColor: ['rgb(59, 130, 246)', 'rgb(147, 51, 234)'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.raw / total) * 100).toFixed(1);
                                return context.label + ': ₱' + context.raw.toLocaleString('en-PH', {minimumFractionDigits: 2}) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection

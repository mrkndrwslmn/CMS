@extends('admin.layouts.app')

@section('title', 'Platform Earnings')

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Platform Earnings', 'icon' => 'trending-up'],
        ]" />

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800 flex items-center gap-3">
                        <div class="p-2 bg-success-50 rounded-xl">
                            <x-lucide-trending-up class="w-6 h-6 text-success-600" />
                        </div>
                        Platform Earnings
                    </h1>
                    <p class="text-neutral-500 mt-1">Track platform revenue from fees and project margins</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.platform-earnings.report') }}" 
                       class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-xl transition-colors">
                        <x-lucide-file-text class="w-4 h-4 mr-2" />
                        Reports
                    </a>
                    <a href="{{ route('admin.platform-earnings.export') }}" 
                       class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-xl transition-colors">
                        <x-lucide-download class="w-4 h-4 mr-2" />
                        Export
                    </a>
                    <form action="{{ route('admin.platform-earnings.recalculate-all') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-xl transition-colors"
                                onclick="return confirm('This will recalculate earnings for all projects. Continue?')">
                            <x-lucide-refresh-cw class="w-4 h-4 mr-2" />
                            Recalculate All
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-success-500 to-success-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-success-100 text-sm font-medium">Total Platform Revenue</p>
                        <p class="text-3xl font-bold mt-2">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                        <p class="text-success-100 text-sm mt-1">All time earnings</p>
                    </div>
                    <div class="p-3 bg-white/20 rounded-xl">
                        <x-lucide-wallet class="w-6 h-6" />
                    </div>
                </div>
            </div>

            <!-- This Month Revenue -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-neutral-500 text-sm font-medium">This Month</p>
                        <p class="text-2xl font-bold text-neutral-800 mt-2">₱{{ number_format($stats['this_month_revenue'] ?? 0, 2) }}</p>
                        @if(($stats['revenue_growth'] ?? 0) > 0)
                            <p class="text-success-600 text-sm mt-1 flex items-center">
                                <x-lucide-trending-up class="w-3 h-3 mr-1" />
                                +{{ number_format($stats['revenue_growth'], 1) }}% vs last month
                            </p>
                        @elseif(($stats['revenue_growth'] ?? 0) < 0)
                            <p class="text-error-600 text-sm mt-1 flex items-center">
                                <x-lucide-trending-down class="w-3 h-3 mr-1" />
                                {{ number_format($stats['revenue_growth'], 1) }}% vs last month
                            </p>
                        @else
                            <p class="text-neutral-500 text-sm mt-1">Same as last month</p>
                        @endif
                    </div>
                    <div class="p-3 bg-primary-50 rounded-xl">
                        <x-lucide-calendar class="w-5 h-5 text-primary-500" />
                    </div>
                </div>
            </div>

            <!-- Platform Fees -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-neutral-500 text-sm font-medium">Platform Fees ({{ config('financial.platform.fee_percentage', 15) }}%)</p>
                        <p class="text-2xl font-bold text-neutral-800 mt-2">₱{{ number_format($stats['total_platform_fees'] ?? 0, 2) }}</p>
                        <p class="text-neutral-500 text-sm mt-1">Guaranteed revenue</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <x-lucide-percent class="w-5 h-5 text-blue-500" />
                    </div>
                </div>
            </div>

            <!-- Margin Earnings -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-neutral-500 text-sm font-medium">Margin Earnings</p>
                        <p class="text-2xl font-bold text-neutral-800 mt-2">₱{{ number_format($stats['total_margin'] ?? 0, 2) }}</p>
                        <p class="text-neutral-500 text-sm mt-1">Budget surplus profit</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-xl">
                        <x-lucide-piggy-bank class="w-5 h-5 text-purple-500" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Adiutor Costs -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-warning-50 rounded-xl">
                        <x-lucide-users class="w-5 h-5 text-warning-500" />
                    </div>
                    <div>
                        <p class="text-neutral-500 text-sm">Total Adiutor Costs</p>
                        <p class="text-xl font-bold text-neutral-800">₱{{ number_format($stats['total_adiutor_costs'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Avg Profit Margin -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-success-50 rounded-xl">
                        <x-lucide-target class="w-5 h-5 text-success-500" />
                    </div>
                    <div>
                        <p class="text-neutral-500 text-sm">Avg Profit Margin</p>
                        <p class="text-xl font-bold text-neutral-800">{{ number_format($stats['avg_profit_margin'] ?? 0, 1) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Projects Tracked -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-neutral-100 rounded-xl">
                        <x-lucide-folder-kanban class="w-5 h-5 text-neutral-500" />
                    </div>
                    <div>
                        <p class="text-neutral-500 text-sm">Projects Tracked</p>
                        <p class="text-xl font-bold text-neutral-800">{{ $stats['projects_count'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-neutral-100 shadow-sm">
                <div class="p-5 border-b border-neutral-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                            <x-lucide-bar-chart-3 class="w-5 h-5 text-primary-500" />
                            Revenue Trend
                        </h3>
                        <form method="GET" class="flex items-center gap-2">
                            <select name="period" onchange="this.form.submit()" 
                                    class="text-sm border border-neutral-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="p-5">
                    @if(count($revenueData) > 0)
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 text-neutral-500">
                            <x-lucide-bar-chart class="w-12 h-12 mb-3 text-neutral-300" />
                            <p>No revenue data available for this period</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Revenue Breakdown -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
                <div class="p-5 border-b border-neutral-100">
                    <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <x-lucide-pie-chart class="w-5 h-5 text-primary-500" />
                        Revenue Breakdown
                    </h3>
                </div>
                <div class="p-5">
                    @php
                        $totalRev = ($stats['total_platform_fees'] ?? 0) + ($stats['total_margin'] ?? 0);
                        $feePercent = $totalRev > 0 ? (($stats['total_platform_fees'] ?? 0) / $totalRev) * 100 : 0;
                        $marginPercent = $totalRev > 0 ? (($stats['total_margin'] ?? 0) / $totalRev) * 100 : 0;
                    @endphp
                    
                    <div class="space-y-4">
                        <!-- Platform Fees -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-neutral-600">Platform Fees</span>
                                <span class="font-medium text-neutral-800">{{ number_format($feePercent, 1) }}%</span>
                            </div>
                            <div class="h-3 bg-neutral-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ $feePercent }}%"></div>
                            </div>
                            <p class="text-xs text-neutral-500 mt-1">₱{{ number_format($stats['total_platform_fees'] ?? 0, 2) }}</p>
                        </div>

                        <!-- Margin Earnings -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-neutral-600">Margin Earnings</span>
                                <span class="font-medium text-neutral-800">{{ number_format($marginPercent, 1) }}%</span>
                            </div>
                            <div class="h-3 bg-neutral-100 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500 rounded-full" style="width: {{ $marginPercent }}%"></div>
                            </div>
                            <p class="text-xs text-neutral-500 mt-1">₱{{ number_format($stats['total_margin'] ?? 0, 2) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-neutral-100">
                        <div class="flex justify-between items-center">
                            <span class="text-neutral-600 font-medium">Total Revenue</span>
                            <span class="text-lg font-bold text-success-600">₱{{ number_format($totalRev, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Breakdown Table -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
            <div class="p-5 border-b border-neutral-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <x-lucide-folder-kanban class="w-5 h-5 text-primary-500" />
                        Project Earnings Breakdown
                    </h3>
                    <div class="flex items-center gap-2">
                        <form method="GET" class="flex items-center gap-2">
                            <input type="hidden" name="period" value="{{ $period }}">
                            <select name="project_status" onchange="this.form.submit()" 
                                    class="text-sm border border-neutral-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                                <option value="">All Projects</option>
                                <option value="active" {{ request('project_status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="in_progress" {{ request('project_status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('project_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Project</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Budget</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Platform Fee</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Adiutor Costs</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Margin</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Total Earnings</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center">Status</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($projectBreakdown as $item)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="font-medium text-neutral-800">{{ Str::limit($item->project->title ?? 'Unknown', 40) }}</div>
                                    <div class="text-xs text-neutral-500">{{ $item->project->client->fullName ?? 'No Client' }}</div>
                                </td>
                                <td class="py-4 px-5 text-right font-medium text-neutral-600">
                                    ₱{{ number_format($item->project_budget ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-blue-600 font-medium">
                                    ₱{{ number_format($item->platform_fee ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-warning-600 font-medium">
                                    ₱{{ number_format($item->total_adiutor_cost ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-purple-600 font-medium">
                                    ₱{{ number_format($item->margin_earnings ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-success-600 font-bold">
                                    ₱{{ number_format($item->total_earnings ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-5 text-center">
                                    @php
                                        $statusConfig = match($item->status) {
                                            'finalized' => ['class' => 'bg-success-50 text-success-700', 'label' => 'Finalized'],
                                            'in_progress' => ['class' => 'bg-blue-50 text-blue-700', 'label' => 'In Progress'],
                                            'adjusted' => ['class' => 'bg-warning-50 text-warning-700', 'label' => 'Adjusted'],
                                            default => ['class' => 'bg-neutral-100 text-neutral-600', 'label' => 'Pending']
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.platform-earnings.show', $item->project_id) }}" 
                                           class="p-1.5 text-neutral-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                                           title="View Details">
                                            <x-lucide-eye class="w-4 h-4" />
                                        </a>
                                        @if($item->status !== 'finalized' && $item->project && $item->project->status === 'completed')
                                            <form action="{{ route('admin.platform-earnings.finalize', $item->project_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="p-1.5 text-neutral-500 hover:text-success-600 hover:bg-success-50 rounded-lg transition-colors"
                                                        title="Finalize Earnings">
                                                    <x-lucide-check-circle class="w-4 h-4" />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-neutral-500">
                                    <x-lucide-inbox class="w-12 h-12 mx-auto mb-3 text-neutral-300" />
                                    <p>No project earnings recorded yet</p>
                                    <p class="text-xs mt-1">Earnings are calculated when projects are created or completed</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($projectBreakdown->hasPages())
                <div class="p-5 border-t border-neutral-100">
                    {{ $projectBreakdown->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    const revenueData = @json($revenueData);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: revenueData.map(d => d.period),
            datasets: [
                {
                    label: 'Platform Fees',
                    data: revenueData.map(d => d.platform_fee),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderRadius: 4,
                },
                {
                    label: 'Margin Earnings',
                    data: revenueData.map(d => d.margin),
                    backgroundColor: 'rgba(147, 51, 234, 0.8)',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₱' + context.raw.toLocaleString('en-PH', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false }
                },
                y: {
                    stacked: true,
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
@endpush
@endsection

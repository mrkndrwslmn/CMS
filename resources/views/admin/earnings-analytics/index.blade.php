@extends('admin.layouts.app')

@section('title', 'Earnings Analytics')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endpush

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Earnings Analytics', 'icon' => 'bar-chart-2'],
        ]" />

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800 mb-2">
                        Earnings Analytics
                    </h1>
                    <p class="text-sm text-neutral-500">Comprehensive overview of adiutor earnings, payouts, and project costs</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Period Filter -->
                    <form method="GET" class="flex items-center gap-2">
                        <select name="period" class="form-select rounded-lg border-neutral-200 text-sm" onchange="this.form.submit()">
                            <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ request('period', '30') == '30' ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                            <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                        </select>
                    </form>
                    
                    <!-- Export Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                            <x-lucide-download class="w-4 h-4" />
                            Export
                            <x-lucide-chevron-down class="w-4 h-4" />
                        </button>
                        <div x-show="open" @click.outside="open = false" 
                             x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-lg z-10 py-1 border border-neutral-100">
                            <a href="{{ route('admin.earnings-analytics.export', ['type' => 'earnings', 'period' => request('period', 30)]) }}" 
                               class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                <x-lucide-file-spreadsheet class="w-4 h-4 text-success-500" /> Summary CSV
                            </a>
                            <a href="{{ route('admin.earnings-analytics.export', ['type' => 'time_entries', 'period' => request('period', 30)]) }}" 
                               class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                <x-lucide-clock class="w-4 h-4 text-primary-500" /> Time Entries CSV
                            </a>
                            <a href="{{ route('admin.earnings-analytics.export', ['type' => 'adiutor_earnings', 'period' => request('period', 30)]) }}" 
                               class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                <x-lucide-users class="w-4 h-4 text-neutral-500" /> Adiutor Earnings CSV
                            </a>
                            <a href="{{ route('admin.earnings-analytics.export', ['type' => 'payouts', 'period' => request('period', 30)]) }}" 
                               class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                <x-lucide-wallet class="w-4 h-4 text-warning-500" /> Payouts CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <!-- Total Earnings -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Earnings</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($stats['total_earnings'], 2) }}</p>
                        <p class="text-sm text-neutral-400 mt-2">
                            <span class="text-success-600">₱{{ number_format($stats['hourly_earnings'], 2) }}</span> hourly +
                            <span class="text-primary-600">₱{{ number_format($stats['fixed_rate_earnings'], 2) }}</span> fixed
                        </p>
                    </div>
                    <div class="p-3 bg-success-50 rounded-xl">
                        <x-lucide-banknote class="w-5 h-5 text-success-500" />
                    </div>
                </div>
            </div>

            <!-- Pending Earnings -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Pending Earnings</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($stats['pending_earnings'], 2) }}</p>
                        <p class="text-sm text-neutral-400 mt-2">Awaiting approval</p>
                    </div>
                    <div class="p-3 bg-warning-50 rounded-xl">
                        <x-lucide-hourglass class="w-5 h-5 text-warning-500" />
                    </div>
                </div>
            </div>

            <!-- Total Hours -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Hours Tracked</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_hours'], 1) }} hrs</p>
                        <p class="text-sm text-neutral-400 mt-2">
                            <span class="text-success-600">{{ number_format($stats['billable_hours'], 1) }}</span> billable,
                            <span class="text-neutral-400">{{ number_format($stats['non_billable_hours'], 1) }}</span> non-billable
                        </p>
                    </div>
                    <div class="p-3 bg-primary-50 rounded-xl">
                        <x-lucide-clock class="w-5 h-5 text-primary-500" />
                    </div>
                </div>
            </div>

            <!-- Active Adiutors -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Active Adiutors</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['active_adiutors'] }}</p>
                        <p class="text-sm text-neutral-400 mt-2">Avg rate: ₱{{ number_format($stats['average_hourly_rate'], 2) }}/hr</p>
                    </div>
                    <div class="p-3 bg-neutral-50 rounded-xl">
                        <x-lucide-users class="w-5 h-5 text-neutral-400" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Alert -->
        @if($pendingApprovals['time_entries'] > 0 || $pendingApprovals['fixed_rates'] > 0 || $pendingApprovals['hour_requests'] > 0 || $pendingApprovals['payout_requests'] > 0)
        <div class="bg-warning-50 border border-warning-200 rounded-2xl p-4 mb-6">
            <div class="flex items-center">
                <x-lucide-alert-triangle class="w-5 h-5 text-warning-500 mr-4" />
                <div class="flex-1">
                    <h4 class="font-semibold text-warning-700">Pending Approvals</h4>
                    <div class="flex flex-wrap gap-4 mt-2 text-sm">
                        @if($pendingApprovals['time_entries'] > 0)
                            <a href="{{ route('admin.payouts.index') }}?filter=pending" class="inline-flex items-center gap-1 text-warning-600 hover:underline">
                                <x-lucide-clock class="w-4 h-4" /> {{ $pendingApprovals['time_entries'] }} time entries
                            </a>
                        @endif
                        @if($pendingApprovals['fixed_rates'] > 0)
                            <a href="{{ route('admin.projects.index') }}?filter=pending_fixed" class="inline-flex items-center gap-1 text-warning-600 hover:underline">
                                <x-lucide-file-text class="w-4 h-4" /> {{ $pendingApprovals['fixed_rates'] }} fixed rates
                            </a>
                        @endif
                        @if($pendingApprovals['hour_requests'] > 0)
                            <a href="{{ route('admin.hour-requests.index') }}" class="inline-flex items-center gap-1 text-warning-600 hover:underline">
                                <x-lucide-plus-circle class="w-4 h-4" /> {{ $pendingApprovals['hour_requests'] }} hour requests
                            </a>
                        @endif
                        @if($pendingApprovals['payout_requests'] > 0)
                            <a href="{{ route('admin.payouts.index') }}?status=pending" class="inline-flex items-center gap-1 text-warning-600 hover:underline">
                                <x-lucide-wallet class="w-4 h-4" /> {{ $pendingApprovals['payout_requests'] }} payout requests
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Navigation -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <a href="{{ route('admin.platform-earnings.index') }}" 
               class="bg-gradient-to-br from-success-50 to-success-100 rounded-2xl border border-success-200 shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-3 group">
                <div class="bg-success-100 group-hover:bg-success-200 p-3 rounded-xl transition-colors">
                    <x-lucide-trending-up class="w-5 h-5 text-success-600" />
                </div>
                <div>
                    <div class="font-medium text-success-800">Platform Earnings</div>
                    <div class="text-xs text-success-600">Fees & margin</div>
                </div>
            </a>
            <a href="{{ route('admin.earnings-analytics.leaderboard') }}" 
               class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-3 group">
                <div class="bg-neutral-50 group-hover:bg-primary-50 p-3 rounded-xl transition-colors">
                    <x-lucide-trophy class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" />
                </div>
                <div>
                    <div class="font-medium text-neutral-700">Leaderboard</div>
                    <div class="text-xs text-neutral-500">Top earners</div>
                </div>
            </a>
            <a href="{{ route('admin.earnings-analytics.project-costs') }}" 
               class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-3 group">
                <div class="bg-neutral-50 group-hover:bg-primary-50 p-3 rounded-xl transition-colors">
                    <x-lucide-folder-kanban class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" />
                </div>
                <div>
                    <div class="font-medium text-neutral-700">Project Costs</div>
                    <div class="text-xs text-neutral-500">Budget analysis</div>
                </div>
            </a>
            <a href="{{ route('admin.earnings-analytics.audit-log') }}" 
               class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-3 group">
                <div class="bg-neutral-50 group-hover:bg-primary-50 p-3 rounded-xl transition-colors">
                    <x-lucide-history class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" />
                </div>
                <div>
                    <div class="font-medium text-neutral-700">Audit Log</div>
                    <div class="text-xs text-neutral-500">Time entries</div>
                </div>
            </a>
            <a href="{{ route('admin.earnings-analytics.payout-history') }}" 
               class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-3 group">
                <div class="bg-neutral-50 group-hover:bg-primary-50 p-3 rounded-xl transition-colors">
                    <x-lucide-wallet class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" />
                </div>
                <div>
                    <div class="font-medium text-neutral-700">Payout History</div>
                    <div class="text-xs text-neutral-500">All payouts</div>
                </div>
            </a>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Earnings Trend Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-neutral-100 shadow-sm">
                <div class="p-5 border-b border-neutral-100">
                    <h5 class="text-lg font-medium text-neutral-700 flex items-center gap-2">
                        <x-lucide-trending-up class="w-5 h-5 text-neutral-400" />
                        Earnings Trend
                    </h5>
                </div>
                <div class="p-5">
                    <div class="chart-container">
                        <canvas id="earningsTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Payment Type Distribution -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
                <div class="p-5 border-b border-neutral-100">
                    <h5 class="text-lg font-medium text-neutral-700 flex items-center gap-2">
                        <x-lucide-pie-chart class="w-5 h-5 text-neutral-400" />
                        Payment Distribution
                    </h5>
                </div>
                <div class="p-5">
                    <div class="chart-container h-48">
                        <canvas id="paymentTypeChart"></canvas>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between p-3 bg-primary-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-primary-500 rounded-full mr-2"></div>
                                <span class="text-sm text-neutral-700">Hourly Rate</span>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-neutral-800">₱{{ number_format($paymentTypeDistribution['hourly']['earnings'], 2) }}</div>
                                <div class="text-xs text-neutral-500">{{ $paymentTypeDistribution['hourly']['percentage'] }}%</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-neutral-500 rounded-full mr-2"></div>
                                <span class="text-sm text-neutral-700">Fixed Rate</span>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-neutral-800">₱{{ number_format($paymentTypeDistribution['fixed']['earnings'], 2) }}</div>
                                <div class="text-xs text-neutral-500">{{ $paymentTypeDistribution['fixed']['percentage'] }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row: Top Earners & Project Costs -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top Earners -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
                <div class="p-5 border-b border-neutral-100 flex justify-between items-center">
                    <h5 class="text-lg font-medium text-neutral-700 flex items-center gap-2">
                        <x-lucide-trophy class="w-5 h-5 text-neutral-400" />
                        Top Earners
                    </h5>
                    <a href="{{ route('admin.earnings-analytics.leaderboard') }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                        View All <x-lucide-arrow-right class="w-3 h-3" />
                    </a>
                </div>
                <div class="p-5">
                    @forelse($topEarners as $index => $earner)
                        <div class="flex items-center py-3 {{ !$loop->last ? 'border-b border-neutral-100' : '' }}">
                            <div class="w-8 h-8 flex items-center justify-center rounded-full 
                                {{ $index === 0 ? 'bg-warning-100 text-warning-600' : ($index === 1 ? 'bg-neutral-100 text-neutral-600' : ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-neutral-100 text-neutral-500')) }}
                                font-bold text-sm mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-neutral-700">{{ $earner->fullName }}</div>
                                <div class="text-xs text-neutral-500">{{ number_format($earner->total_hours, 1) }} hrs</div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-success-600">₱{{ number_format($earner->total_earnings, 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-neutral-500">
                            <x-lucide-users class="w-12 h-12 mx-auto mb-3 text-neutral-300" />
                            <p class="text-sm">No earnings data for this period</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Project Cost Overview -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
                <div class="p-5 border-b border-neutral-100 flex justify-between items-center">
                    <h5 class="text-lg font-medium text-neutral-700 flex items-center gap-2">
                        <x-lucide-folder-kanban class="w-5 h-5 text-neutral-400" />
                        Top Project Costs
                    </h5>
                    <a href="{{ route('admin.earnings-analytics.project-costs') }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                        View All <x-lucide-arrow-right class="w-3 h-3" />
                    </a>
                </div>
                <div class="p-5">
                    @forelse($projectCostAnalysis as $project)
                        <div class="py-3 {{ !$loop->last ? 'border-b border-neutral-100' : '' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-medium text-neutral-700 truncate pr-4">{{ $project->title }}</div>
                                <div class="font-semibold text-neutral-800">₱{{ number_format($project->total_cost, 2) }}</div>
                            </div>
                            <div class="flex items-center gap-4 text-xs text-neutral-500">
                                <span>Budget: ₱{{ number_format($project->budget ?? 0, 2) }}</span>
                                <span>{{ $project->adiutor_count }} adiutors</span>
                                @if($project->budget > 0)
                                    <span class="{{ $project->budget_utilization > 100 ? 'text-error-500' : ($project->budget_utilization > 80 ? 'text-warning-500' : 'text-success-500') }}">
                                        {{ $project->budget_utilization }}% used
                                    </span>
                                @endif
                            </div>
                            @if($project->budget > 0)
                                <div class="mt-2 bg-neutral-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-full {{ $project->budget_utilization > 100 ? 'bg-error-500' : ($project->budget_utilization > 80 ? 'bg-warning-500' : 'bg-success-500') }}" 
                                         style="width: {{ min($project->budget_utilization, 100) }}%"></div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-neutral-500">
                            <x-lucide-folder-open class="w-12 h-12 mx-auto mb-3 text-neutral-300" />
                            <p class="text-sm">No project cost data for this period</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Payouts -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
            <div class="p-5 border-b border-neutral-100 flex justify-between items-center">
                <h5 class="text-lg font-medium text-neutral-700 flex items-center gap-2">
                    <x-lucide-wallet class="w-5 h-5 text-neutral-400" />
                    Recent Payouts
                </h5>
                <a href="{{ route('admin.earnings-analytics.payout-history') }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                    View All <x-lucide-arrow-right class="w-3 h-3" />
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Payout #</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Adiutor</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Amount</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Status</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Date</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($recentPayouts as $payout)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-3 px-5 font-medium text-neutral-700">{{ $payout->payout_number }}</td>
                                <td class="py-3 px-5">
                                    <div class="font-medium text-neutral-700">{{ $payout->adiutor?->fullName ?? 'N/A' }}</div>
                                    <div class="text-xs text-neutral-500">{{ $payout->adiutor?->email }}</div>
                                </td>
                                <td class="py-3 px-5 font-semibold text-success-600">{{ $payout->getFormattedAmount() }}</td>
                                <td class="py-3 px-5">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $payout->getStatusBadgeClass() }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 text-neutral-500">{{ $payout->created_at->format('M d, Y') }}</td>
                                <td class="py-3 px-5 text-right">
                                    <a href="{{ route('admin.payouts.show', $payout->id) }}" class="text-neutral-400 hover:text-primary-600 transition-colors">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-neutral-500">
                                    <x-lucide-wallet class="w-12 h-12 mx-auto mb-3 text-neutral-300" />
                                    <p class="text-sm">No recent payouts</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Earnings Trend Chart
    const earningsTrendCtx = document.getElementById('earningsTrendChart').getContext('2d');
    const trendData = @json($earningsTrend);
    
    new Chart(earningsTrendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(d => d.period),
            datasets: [
                {
                    label: 'Hourly Earnings',
                    data: trendData.map(d => d.hourly_earnings),
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Fixed Rate Earnings',
                    data: trendData.map(d => d.fixed_earnings),
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ₱' + context.raw.toLocaleString('en-US', {minimumFractionDigits: 2});
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

    // Payment Type Chart
    const paymentTypeCtx = document.getElementById('paymentTypeChart').getContext('2d');
    const paymentData = @json($paymentTypeDistribution);
    
    new Chart(paymentTypeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hourly Rate', 'Fixed Rate'],
            datasets: [{
                data: [paymentData.hourly.earnings, paymentData.fixed.earnings],
                backgroundColor: ['#0ea5e9', '#8b5cf6'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ₱' + context.raw.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

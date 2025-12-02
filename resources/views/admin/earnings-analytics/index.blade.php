@extends('admin.layouts.app')

@section('title', 'Earnings Analytics')

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .trend-up { color: #10b981; }
    .trend-down { color: #ef4444; }
    .chart-container {
        position: relative;
        height: 300px;
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
                    <h1 class="text-2xl font-semibold text-primary-500 mb-2">
                        <i class="fas fa-chart-line text-accent-500 mr-2"></i>
                        Earnings Analytics
                    </h1>
                    <p class="text-neutral-500">Comprehensive overview of adiutor earnings, payouts, and project costs</p>
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
                        <button @click="open = !open" class="glass-button-accent rounded-lg px-4 py-2 flex items-center text-sm">
                            <i class="fas fa-download mr-2"></i> Export
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" 
                             x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 py-1 border border-neutral-100">
                            <a href="{{ route('admin.earnings-analytics.export', ['type' => 'earnings', 'period' => request('period', 30)]) }}" 
                               class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                <i class="fas fa-file-csv mr-2 text-green-500"></i> Summary CSV
                            </a>
                            <a href="{{ route('admin.earnings-analytics.export', ['type' => 'time_entries', 'period' => request('period', 30)]) }}" 
                               class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                <i class="fas fa-clock mr-2 text-blue-500"></i> Time Entries CSV
                            </a>
                            <a href="{{ route('admin.earnings-analytics.export', ['type' => 'adiutor_earnings', 'period' => request('period', 30)]) }}" 
                               class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                <i class="fas fa-users mr-2 text-purple-500"></i> Adiutor Earnings CSV
                            </a>
                            <a href="{{ route('admin.earnings-analytics.export', ['type' => 'payouts', 'period' => request('period', 30)]) }}" 
                               class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                <i class="fas fa-wallet mr-2 text-orange-500"></i> Payouts CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
            <!-- Total Earnings -->
            <div class="stat-card bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-l-4 border-success-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Total Earnings</div>
                            <div class="text-2xl font-bold text-primary-500">₱{{ number_format($stats['total_earnings'], 2) }}</div>
                            <div class="text-xs text-neutral-500 mt-2">
                                <span class="text-success-500">₱{{ number_format($stats['hourly_earnings'], 2) }}</span> hourly +
                                <span class="text-accent-500">₱{{ number_format($stats['fixed_rate_earnings'], 2) }}</span> fixed
                            </div>
                        </div>
                        <div class="bg-success-50 p-3 rounded-xl">
                            <i class="fas fa-money-bill-wave text-success-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Earnings -->
            <div class="stat-card bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-l-4 border-warning-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Pending Earnings</div>
                            <div class="text-2xl font-bold text-primary-500">₱{{ number_format($stats['pending_earnings'], 2) }}</div>
                            <div class="text-xs text-neutral-500 mt-2">
                                Awaiting approval
                            </div>
                        </div>
                        <div class="bg-warning-50 p-3 rounded-xl">
                            <i class="fas fa-hourglass-half text-warning-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Hours -->
            <div class="stat-card bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-l-4 border-accent-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Total Hours Tracked</div>
                            <div class="text-2xl font-bold text-primary-500">{{ number_format($stats['total_hours'], 1) }} hrs</div>
                            <div class="text-xs text-neutral-500 mt-2">
                                <span class="text-success-500">{{ number_format($stats['billable_hours'], 1) }}</span> billable,
                                <span class="text-neutral-400">{{ number_format($stats['non_billable_hours'], 1) }}</span> non-billable
                            </div>
                        </div>
                        <div class="bg-accent-50 p-3 rounded-xl">
                            <i class="fas fa-clock text-accent-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Adiutors -->
            <div class="stat-card bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-l-4 border-secondary-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Active Adiutors</div>
                            <div class="text-2xl font-bold text-primary-500">{{ $stats['active_adiutors'] }}</div>
                            <div class="text-xs text-neutral-500 mt-2">
                                Avg rate: ₱{{ number_format($stats['average_hourly_rate'], 2) }}/hr
                            </div>
                        </div>
                        <div class="bg-secondary-50 p-3 rounded-xl">
                            <i class="fas fa-user-tie text-secondary-500 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Alert -->
        @if($pendingApprovals['time_entries'] > 0 || $pendingApprovals['fixed_rates'] > 0 || $pendingApprovals['hour_requests'] > 0 || $pendingApprovals['payout_requests'] > 0)
        <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-warning-500 text-xl mr-4"></i>
                <div class="flex-1">
                    <h4 class="font-semibold text-warning-700">Pending Approvals</h4>
                    <div class="flex flex-wrap gap-4 mt-2 text-sm">
                        @if($pendingApprovals['time_entries'] > 0)
                            <a href="{{ route('admin.payouts.index') }}?filter=pending" class="text-warning-600 hover:underline">
                                <i class="fas fa-clock mr-1"></i> {{ $pendingApprovals['time_entries'] }} time entries
                            </a>
                        @endif
                        @if($pendingApprovals['fixed_rates'] > 0)
                            <a href="{{ route('admin.projects.index') }}?filter=pending_fixed" class="text-warning-600 hover:underline">
                                <i class="fas fa-file-invoice-dollar mr-1"></i> {{ $pendingApprovals['fixed_rates'] }} fixed rates
                            </a>
                        @endif
                        @if($pendingApprovals['hour_requests'] > 0)
                            <a href="{{ route('admin.hour-requests.index') }}" class="text-warning-600 hover:underline">
                                <i class="fas fa-plus-circle mr-1"></i> {{ $pendingApprovals['hour_requests'] }} hour requests
                            </a>
                        @endif
                        @if($pendingApprovals['payout_requests'] > 0)
                            <a href="{{ route('admin.payouts.index') }}?status=pending" class="text-warning-600 hover:underline">
                                <i class="fas fa-wallet mr-1"></i> {{ $pendingApprovals['payout_requests'] }} payout requests
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Navigation -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <a href="{{ route('admin.earnings-analytics.leaderboard') }}" 
               class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-3 group">
                <div class="bg-purple-50 group-hover:bg-purple-100 p-3 rounded-lg transition-colors">
                    <i class="fas fa-trophy text-purple-500"></i>
                </div>
                <div>
                    <div class="font-semibold text-primary-500">Leaderboard</div>
                    <div class="text-xs text-neutral-500">Top earners</div>
                </div>
            </a>
            <a href="{{ route('admin.earnings-analytics.project-costs') }}" 
               class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-3 group">
                <div class="bg-blue-50 group-hover:bg-blue-100 p-3 rounded-lg transition-colors">
                    <i class="fas fa-project-diagram text-blue-500"></i>
                </div>
                <div>
                    <div class="font-semibold text-primary-500">Project Costs</div>
                    <div class="text-xs text-neutral-500">Budget analysis</div>
                </div>
            </a>
            <a href="{{ route('admin.earnings-analytics.audit-log') }}" 
               class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-3 group">
                <div class="bg-green-50 group-hover:bg-green-100 p-3 rounded-lg transition-colors">
                    <i class="fas fa-history text-green-500"></i>
                </div>
                <div>
                    <div class="font-semibold text-primary-500">Audit Log</div>
                    <div class="text-xs text-neutral-500">Time entries</div>
                </div>
            </a>
            <a href="{{ route('admin.earnings-analytics.payout-history') }}" 
               class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow flex items-center gap-3 group">
                <div class="bg-orange-50 group-hover:bg-orange-100 p-3 rounded-lg transition-colors">
                    <i class="fas fa-wallet text-orange-500"></i>
                </div>
                <div>
                    <div class="font-semibold text-primary-500">Payout History</div>
                    <div class="text-xs text-neutral-500">All payouts</div>
                </div>
            </a>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Earnings Trend Chart -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm">
                <div class="p-5 border-b border-neutral-100">
                    <h5 class="text-lg font-bold text-primary-500">
                        <i class="fas fa-chart-area text-accent-500 mr-2"></i>
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
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-5 border-b border-neutral-100">
                    <h5 class="text-lg font-bold text-primary-500">
                        <i class="fas fa-chart-pie text-secondary-500 mr-2"></i>
                        Payment Distribution
                    </h5>
                </div>
                <div class="p-5">
                    <div class="chart-container h-48">
                        <canvas id="paymentTypeChart"></canvas>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between p-3 bg-accent-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-accent-500 rounded-full mr-2"></div>
                                <span class="text-sm text-neutral-700">Hourly Rate</span>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-primary-500">₱{{ number_format($paymentTypeDistribution['hourly']['earnings'], 2) }}</div>
                                <div class="text-xs text-neutral-500">{{ $paymentTypeDistribution['hourly']['percentage'] }}%</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-secondary-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-secondary-500 rounded-full mr-2"></div>
                                <span class="text-sm text-neutral-700">Fixed Rate</span>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-primary-500">₱{{ number_format($paymentTypeDistribution['fixed']['earnings'], 2) }}</div>
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
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-5 border-b border-neutral-100 flex justify-between items-center">
                    <h5 class="text-lg font-bold text-primary-500">
                        <i class="fas fa-trophy text-warning-500 mr-2"></i>
                        Top Earners
                    </h5>
                    <a href="{{ route('admin.earnings-analytics.leaderboard') }}" class="text-sm text-accent-500 hover:underline">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-5">
                    @forelse($topEarners as $index => $earner)
                        <div class="flex items-center py-3 {{ !$loop->last ? 'border-b border-neutral-100' : '' }}">
                            <div class="w-8 h-8 flex items-center justify-center rounded-full 
                                {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-neutral-100 text-neutral-500')) }}
                                font-bold text-sm mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-primary-500">{{ $earner->fullName }}</div>
                                <div class="text-xs text-neutral-500">{{ number_format($earner->total_hours, 1) }} hrs</div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-success-500">₱{{ number_format($earner->total_earnings, 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-neutral-500">
                            <i class="fas fa-users text-4xl mb-3 opacity-50"></i>
                            <p>No earnings data for this period</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Project Cost Overview -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-5 border-b border-neutral-100 flex justify-between items-center">
                    <h5 class="text-lg font-bold text-primary-500">
                        <i class="fas fa-project-diagram text-accent-500 mr-2"></i>
                        Top Project Costs
                    </h5>
                    <a href="{{ route('admin.earnings-analytics.project-costs') }}" class="text-sm text-accent-500 hover:underline">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-5">
                    @forelse($projectCostAnalysis as $project)
                        <div class="py-3 {{ !$loop->last ? 'border-b border-neutral-100' : '' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-medium text-primary-500 truncate pr-4">{{ $project->title }}</div>
                                <div class="font-semibold text-primary-500">₱{{ number_format($project->total_cost, 2) }}</div>
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
                            <i class="fas fa-folder-open text-4xl mb-3 opacity-50"></i>
                            <p>No project cost data for this period</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Payouts -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-5 border-b border-neutral-100 flex justify-between items-center">
                <h5 class="text-lg font-bold text-primary-500">
                    <i class="fas fa-wallet text-tertiary-500 mr-2"></i>
                    Recent Payouts
                </h5>
                <a href="{{ route('admin.earnings-analytics.payout-history') }}" class="text-sm text-accent-500 hover:underline">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-100">
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Payout #</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Adiutor</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Amount</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Status</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Date</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayouts as $payout)
                            <tr class="border-b border-neutral-50 hover:bg-neutral-50">
                                <td class="py-3 px-5 font-medium">{{ $payout->payout_number }}</td>
                                <td class="py-3 px-5">
                                    <div class="font-medium text-primary-500">{{ $payout->adiutor?->fullName ?? 'N/A' }}</div>
                                    <div class="text-xs text-neutral-500">{{ $payout->adiutor?->email }}</div>
                                </td>
                                <td class="py-3 px-5 font-semibold text-success-500">{{ $payout->getFormattedAmount() }}</td>
                                <td class="py-3 px-5">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $payout->getStatusBadgeClass() }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 text-neutral-500">{{ $payout->created_at->format('M d, Y') }}</td>
                                <td class="py-3 px-5 text-right">
                                    <a href="{{ route('admin.payouts.show', $payout->id) }}" class="text-accent-500 hover:text-accent-600">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-neutral-500">
                                    No recent payouts
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

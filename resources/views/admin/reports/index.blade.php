@extends('admin.layouts.app')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="max-w-8xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <nav class="flex items-center space-x-2 text-sm text-neutral-500 mt-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
                    <x-lucide-chevron-right class="w-3 h-3" />
                    <span class="text-neutral-700">Reports</span>
                </nav>
            </div>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                <x-lucide-printer class="w-4 h-4 mr-2" />
                Print Report
            </button>
        </div>
    </div>

    <!-- Time Period Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 mb-6">
        <div class="px-6 py-4 border-b border-neutral-200">
            <h2 class="text-sm font-semibold text-neutral-800">Filter Report Data</h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Time Period</label>
                    <select name="period" id="periodSelect" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                        <option value="7" {{ ($period ?? '30') == '7' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30" {{ ($period ?? '30') == '30' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90" {{ ($period ?? '30') == '90' ? 'selected' : '' }}>Last 90 Days</option>
                        <option value="365" {{ ($period ?? '30') == '365' ? 'selected' : '' }}>Last Year</option>
                        <option value="custom" {{ ($period ?? '30') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date', $filterStartDate->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date', $filterEndDate->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors">
                        <x-lucide-filter class="w-4 h-4 inline mr-2" />Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Users -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Users</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($kpis['total_users']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        @if($kpis['new_users'] > 0)
                        <span class="flex h-4 w-4 rounded-full bg-success-50 text-success-600 items-center justify-center mr-1">
                            <x-lucide-arrow-up class="w-2.5 h-2.5" />
                        </span>
                        <span class="text-success-600">+{{ $kpis['new_users'] }} this period</span>
                        @else
                        <span class="flex h-4 w-4 rounded-full bg-neutral-50 text-neutral-500 items-center justify-center mr-1">
                            <x-lucide-minus class="w-2.5 h-2.5" />
                        </span>
                        <span>No new users</span>
                        @endif
                    </p>
                </div>
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center">
                    <x-lucide-users class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Active Tasks -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Active Tasks</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($kpis['active_tasks']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-warning-50 text-warning-600 items-center justify-center mr-1">
                            <x-lucide-percent class="w-2.5 h-2.5" />
                        </span>
                        {{ $kpis['completion_rate'] }}% completion rate
                    </p>
                </div>
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-warning-50 text-warning-500 flex items-center justify-center">
                    <x-lucide-list-todo class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Total Clients -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Clients</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($kpis['total_clients']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        @if($kpis['new_clients'] > 0)
                        <span class="flex h-4 w-4 rounded-full bg-success-50 text-success-600 items-center justify-center mr-1">
                            <x-lucide-arrow-up class="w-2.5 h-2.5" />
                        </span>
                        <span class="text-success-600">+{{ $kpis['new_clients'] }} new clients</span>
                        @else
                        <span class="flex h-4 w-4 rounded-full bg-neutral-50 text-neutral-500 items-center justify-center mr-1">
                            <x-lucide-minus class="w-2.5 h-2.5" />
                        </span>
                        <span>No new clients</span>
                        @endif
                    </p>
                </div>
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-success-50 text-success-500 flex items-center justify-center">
                    <x-lucide-briefcase class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Total Documents -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Documents</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($kpis['total_documents']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-info-50 text-info-600 items-center justify-center mr-1">
                            <x-lucide-hard-drive class="w-2.5 h-2.5" />
                        </span>
                        {{ number_format($kpis['total_document_size'] / 1048576, 2) }} MB total
                    </p>
                </div>
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-info-50 text-info-500 flex items-center justify-center">
                    <x-lucide-file-text class="w-5 h-5" />
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <!-- User Activity Chart -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-trending-up class="w-4 h-4 text-primary-500 mr-2" />
                    User Registration Trend
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="userActivityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Task Status Chart -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-pie-chart class="w-4 h-4 text-primary-500 mr-2" />
                    Task Status Distribution
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="taskStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <!-- Recent Activities -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-history class="w-4 h-4 text-primary-500 mr-2" />
                    Recent Activities
                </h3>
            </div>
            <div class="p-5">
                @if($recentActivities->count() > 0)
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start gap-3">
                        <div class="h-8 w-8 rounded-full bg-{{ $activity['color'] }}-50 text-{{ $activity['color'] }}-500 flex items-center justify-center flex-shrink-0">
                            @if($activity['icon'] === 'user-plus')
                                <x-lucide-user-plus class="w-4 h-4" />
                            @elseif($activity['icon'] === 'list-todo')
                                <x-lucide-list-todo class="w-4 h-4" />
                            @elseif($activity['icon'] === 'check-circle')
                                <x-lucide-check-circle class="w-4 h-4" />
                            @else
                                <x-lucide-activity class="w-4 h-4" />
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-neutral-700 truncate">{{ $activity['message'] }}</p>
                            <p class="text-xs text-neutral-400 mt-0.5">{{ $activity['time']->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex items-center justify-center py-10 text-neutral-400">
                    <div class="text-center">
                        <div class="h-14 w-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
                            <x-lucide-inbox class="w-5 h-5 text-neutral-400" />
                        </div>
                        <p class="text-sm text-neutral-500">No recent activities</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Top Performers -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-trophy class="w-4 h-4 text-primary-500 mr-2" />
                    Top Performers
                </h3>
            </div>
            <div class="p-5">
                @if($topPerformers->count() > 0)
                <div class="space-y-4">
                    @foreach($topPerformers as $index => $performer)
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-6 text-center">
                            @if($index === 0)
                                <span class="text-yellow-500 font-bold">🥇</span>
                            @elseif($index === 1)
                                <span class="text-gray-400 font-bold">🥈</span>
                            @elseif($index === 2)
                                <span class="text-amber-600 font-bold">🥉</span>
                            @else
                                <span class="text-neutral-400 text-sm font-medium">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($performer->profilePic)
                                <img src="{{ str_starts_with($performer->profilePic, 'http') ? $performer->profilePic : asset('storage/' . $performer->profilePic) }}" alt="{{ $performer->fullName }}" class="h-8 w-8 rounded-full object-cover">
                            @else
                                <span class="text-primary-600 text-xs font-medium">{{ strtoupper(substr($performer->fullName, 0, 2)) }}</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-neutral-700 truncate">{{ $performer->fullName }}</p>
                            <p class="text-xs text-neutral-400">{{ $performer->completed_tasks_count }} tasks completed</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-medium text-success-600 bg-success-50 px-2 py-1 rounded-full">
                                {{ $performer->total_tasks_count > 0 ? round(($performer->completed_tasks_count / $performer->total_tasks_count) * 100) : 0 }}%
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex items-center justify-center py-10 text-neutral-400">
                    <div class="text-center">
                        <div class="h-14 w-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
                            <x-lucide-award class="w-5 h-5 text-neutral-400" />
                        </div>
                        <p class="text-sm text-neutral-500">No performance data available</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-neutral-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-table-2 class="w-4 h-4 text-primary-500 mr-2" />
                    Period Comparison Report
                </h3>
                <div class="text-xs text-neutral-500">
                    {{ $filterStartDate->format('M d, Y') }} - {{ $filterEndDate->format('M d, Y') }}
                </div>
            </div>
        </div>
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Metric</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Current Period</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Previous Period</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Change</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($reportMetrics as $metric)
                        <tr>
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-neutral-800">{{ $metric['name'] }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-neutral-600">{{ number_format($metric['current']) }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-neutral-600">{{ number_format($metric['previous']) }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                @if($metric['change'] > 0)
                                    <span class="inline-flex items-center text-success-600">
                                        <x-lucide-trending-up class="w-4 h-4 mr-1" />
                                        +{{ $metric['change'] }}%
                                    </span>
                                @elseif($metric['change'] < 0)
                                    <span class="inline-flex items-center text-error-600">
                                        <x-lucide-trending-down class="w-4 h-4 mr-1" />
                                        {{ $metric['change'] }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-neutral-500">
                                        <x-lucide-minus class="w-4 h-4 mr-1" />
                                        0%
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats Summary -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg p-4 text-white">
            <p class="text-sm text-primary-100">Total Projects</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($kpis['total_projects']) }}</p>
            <p class="text-xs text-primary-200 mt-1">{{ $kpis['active_projects'] }} active</p>
        </div>
        <div class="bg-gradient-to-br from-success-500 to-success-600 rounded-lg p-4 text-white">
            <p class="text-sm text-success-100">Completed Tasks</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($kpis['completed_tasks']) }}</p>
            <p class="text-xs text-success-200 mt-1">of {{ number_format($kpis['total_tasks']) }} total</p>
        </div>
        <div class="bg-gradient-to-br from-warning-500 to-warning-600 rounded-lg p-4 text-white">
            <p class="text-sm text-warning-100">Service Requests</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($kpis['total_requests']) }}</p>
            <p class="text-xs text-warning-200 mt-1">{{ $kpis['pending_requests'] }} pending</p>
        </div>
        <div class="bg-gradient-to-br from-info-500 to-info-600 rounded-lg p-4 text-white">
            <p class="text-sm text-info-100">Storage Used</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($kpis['total_document_size'] / 1048576, 1) }} MB</p>
            <p class="text-xs text-info-200 mt-1">{{ number_format($kpis['total_documents']) }} files</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // User Activity Chart
    const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
    const userActivityData = @json($userActivity);
    
    new Chart(userActivityCtx, {
        type: 'line',
        data: {
            labels: userActivityData.map(d => d.date),
            datasets: [{
                label: 'New Users',
                data: userActivityData.map(d => d.count),
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Task Status Chart
    const taskStatusCtx = document.getElementById('taskStatusChart').getContext('2d');
    const taskStatusRaw = @json($taskStatusDistribution->toArray());
    
    const statusColors = {
        'pending': '#f59e0b',
        'in_progress': '#3b82f6',
        'completed': '#10b981',
        'cancelled': '#ef4444',
        'on_hold': '#6b7280'
    };
    const statusLabels = {
        'pending': 'Pending',
        'in_progress': 'In Progress',
        'completed': 'Completed',
        'cancelled': 'Cancelled',
        'on_hold': 'On Hold'
    };
    
    const keys = Object.keys(taskStatusRaw);
    const labels = keys.map(k => statusLabels[k] || k);
    const data = keys.map(k => taskStatusRaw[k]?.count || 0);
    const colors = keys.map(k => statusColors[k] || '#9ca3af');

    new Chart(taskStatusCtx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { boxWidth: 12, padding: 15 }
                }
            }
        }
    });
</script>
@endpush
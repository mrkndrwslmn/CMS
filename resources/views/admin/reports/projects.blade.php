@extends('admin.layouts.app')

@section('title', 'Projects Report')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Projects Report</h1>
        <p class="text-gray-600">Comprehensive analysis of project portfolio and performance</p>
    </div>

    <!-- Time Period Filter -->
    <div class="mb-6">
        <form method="GET" class="flex items-center space-x-4">
            <label for="period" class="text-sm font-medium text-gray-700">Time Period:</label>
            <select name="period" id="period" class="border border-gray-300 rounded-md px-3 py-2" onchange="this.form.submit()">
                <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 days</option>
                <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
                <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
                <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last year</option>
            </select>
        </form>
    </div>

    <!-- Key Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Projects</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_projects']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Projects</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active_projects']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Budget</p>
                    <p class="text-2xl font-bold text-yellow-600">₱{{ number_format($stats['total_budget'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['completion_rate'], 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Overdue Projects</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['overdue_projects']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed Projects</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['completed_projects']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-teal-100 rounded-lg">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Average Budget</p>
                    <p class="text-2xl font-bold text-teal-600">₱{{ number_format($stats['average_budget'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Project Status Trend -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Status Trend</h3>
            <div class="h-64">
                <canvas id="projectTrendChart"></canvas>
            </div>
        </div>

        <!-- Budget Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Budget Distribution</h3>
            <div class="h-64">
                <canvas id="budgetChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Priority Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Priority Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Projects by Priority</h3>
            <div class="h-64">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>

        <!-- Duration Analysis -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Average Project Duration by Priority</h3>
            <div class="h-64">
                <canvas id="durationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Clients Table -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Top Clients by Project Count</h3>
            <a href="{{ route('admin.reports.export', ['type' => 'projects', 'period' => $period]) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Export Data
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topClients as $client)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $client->fullName }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $client->projects_count }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $client->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $client->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($client->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No clients found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Project Status Trend Chart
const projectTrendCtx = document.getElementById('projectTrendChart').getContext('2d');
const projectTrendData = @json($projectTrend);
const projectDates = [...new Set(projectTrendData.map(item => item.date))].sort();
const projectStatuses = [...new Set(projectTrendData.map(item => item.status))];

const projectTrendDatasets = projectStatuses.map(status => {
    const color = {
        'pending': 'rgb(253, 224, 71)',
        'in_progress': 'rgb(59, 130, 246)', 
        'completed': 'rgb(34, 197, 94)',
        'cancelled': 'rgb(239, 68, 68)'
    }[status] || 'rgb(156, 163, 175)';

    return {
        label: status.replace('_', ' ').toUpperCase(),
        data: projectDates.map(date => {
            const item = projectTrendData.find(d => d.date === date && d.status === status);
            return item ? item.count : 0;
        }),
        borderColor: color,
        backgroundColor: color + '20',
        tension: 0.4
    };
});

new Chart(projectTrendCtx, {
    type: 'line',
    data: {
        labels: projectDates,
        datasets: projectTrendDatasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Budget Distribution Chart
const budgetCtx = document.getElementById('budgetChart').getContext('2d');
const budgetData = @json($budgetRanges);
new Chart(budgetCtx, {
    type: 'doughnut',
    data: {
        labels: budgetData.map(item => item.budget_range),
        datasets: [{
            data: budgetData.map(item => item.count),
            backgroundColor: [
                'rgb(239, 68, 68)',
                'rgb(245, 158, 11)',
                'rgb(34, 197, 94)',
                'rgb(59, 130, 246)',
                'rgb(147, 51, 234)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Priority Distribution Chart
const priorityCtx = document.getElementById('priorityChart').getContext('2d');
const priorityData = @json($priorityDistribution);
new Chart(priorityCtx, {
    type: 'bar',
    data: {
        labels: priorityData.map(item => item.priority.toUpperCase()),
        datasets: [{
            label: 'Project Count',
            data: priorityData.map(item => item.count),
            backgroundColor: 'rgb(59, 130, 246)',
            borderColor: 'rgb(37, 99, 235)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Duration Analysis Chart
const durationCtx = document.getElementById('durationChart').getContext('2d');
const durationData = @json($durationAnalysis);
new Chart(durationCtx, {
    type: 'bar',
    data: {
        labels: durationData.map(item => item.priority.toUpperCase()),
        datasets: [{
            label: 'Average Days',
            data: durationData.map(item => Math.round(item.avg_days)),
            backgroundColor: 'rgb(34, 197, 94)',
            borderColor: 'rgb(21, 128, 61)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Days'
                }
            }
        }
    }
});
</script>
@endpush
@endsection
@extends('admin.layouts.app')

@section('title', 'Service Request Reports')
@section('page-title', 'Service Request Analytics')

@section('content')
<div class="max-w-8xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <nav class="flex items-center space-x-2 text-sm text-neutral-500 mt-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.reports.index') }}" class="hover:text-primary-600 transition-colors">Reports</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-neutral-700">Service Requests</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-300 hover:bg-neutral-50 text-neutral-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </button>
                <a href="{{ route('admin.reports.export', ['type' => 'requests', 'period' => $period]) }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Time Period Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.reports.requests') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Time Period</label>
                    <select name="period" onchange="this.form.submit()" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                        <option value="7" {{ $period == '7' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30" {{ $period == '30' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90" {{ $period == '90' ? 'selected' : '' }}>Last 90 Days</option>
                        <option value="365" {{ $period == '365' ? 'selected' : '' }}>Last Year</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Request Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Total Requests -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Requests</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_requests']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">All time requests</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Approved Requests -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Approved Requests</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['approved_requests']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-success-50 text-success-600 items-center justify-center mr-1">
                            <i class="fas fa-check text-[10px]"></i>
                        </span>
                        {{ number_format($stats['approval_rate'], 1) }}% approval rate
                    </p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-success-50 text-success-500 flex items-center justify-center">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending Requests</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['pending_requests']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">Awaiting review</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-warning-50 text-warning-500 flex items-center justify-center">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Rejected Requests -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Rejected Requests</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['rejected_requests']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">Not approved</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-error-50 text-error-500 flex items-center justify-center">
                    <i class="fas fa-times-circle text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Average Response Time -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Avg. Response Time</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">
                        {{ $stats['average_response_time'] ? number_format($stats['average_response_time'], 1) : '0' }}
                    </p>
                    <p class="text-xs text-neutral-500 mt-2">Days to review</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-info-50 text-info-500 flex items-center justify-center">
                    <i class="fas fa-stopwatch text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Approval Rate -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Approval Rate</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['approval_rate'], 1) }}%</p>
                    <p class="text-xs text-neutral-500 mt-2">Overall approval</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center">
                    <i class="fas fa-percentage text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Request Trend -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <i class="fas fa-chart-line text-primary-500 mr-2"></i>
                    Request Trend (Last {{ $period }} Days)
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="requestTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Service Type Distribution -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <i class="fas fa-chart-pie text-primary-500 mr-2"></i>
                    Service Type Distribution
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="serviceTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Distribution & Top Clients -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Priority Distribution -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <i class="fas fa-chart-bar text-primary-500 mr-2"></i>
                    Priority Distribution
                </h3>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($priorityDistribution as $priority)
                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-3
                                {{ $priority->priority === 'high' ? 'bg-error-100 text-error-800' : ($priority->priority === 'medium' ? 'bg-warning-100 text-warning-800' : 'bg-success-100 text-success-800') }}">
                                {{ ucfirst($priority->priority) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-semibold text-neutral-800">
                                {{ number_format($priority->count) }}
                            </span>
                            <span class="text-sm text-neutral-500 ml-1">requests</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-neutral-400">
                        <i class="fas fa-chart-bar text-3xl mb-2"></i>
                        <p class="text-sm">No priority data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Requesting Clients -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <i class="fas fa-trophy text-primary-500 mr-2"></i>
                    Top Requesting Clients
                </h3>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($topClients as $client)
                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-semibold text-sm mr-3">
                                {{ substr($client->fullName, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-medium text-neutral-800 text-sm">{{ $client->fullName }}</p>
                                <p class="text-xs text-neutral-500">{{ $client->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                {{ $client->service_requests_count }} requests
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-neutral-400">
                        <i class="fas fa-users text-3xl mb-2"></i>
                        <p class="text-sm">No client data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Request Details Table -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-neutral-200">
            <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                <i class="fas fa-list text-primary-500 mr-2"></i>
                Recent Service Requests
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Service Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Most Recent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status Mix</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($typeDistribution as $type)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-900">
                                {{ ucfirst(str_replace('_', ' ', $type->service_type)) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-neutral-800">{{ number_format($type->count) }}</span>
                            <span class="text-xs text-neutral-500 ml-1">requests</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            Recently active
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                    Approved
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                    Pending
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-neutral-400">
                                <i class="fas fa-clipboard-list text-3xl mb-2"></i>
                                <p class="text-sm">No service request data available</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Request Trend Chart
    const requestTrendCtx = document.getElementById('requestTrendChart').getContext('2d');
    
    // Process request trend data
    const trendData = @json($requestTrend);
    const groupedData = {};
    const statuses = ['pending', 'approved', 'rejected'];
    
    // Initialize data structure
    trendData.forEach(item => {
        if (!groupedData[item.date]) {
            groupedData[item.date] = { pending: 0, approved: 0, rejected: 0 };
        }
        groupedData[item.date][item.status] = item.count;
    });
    
    const dates = Object.keys(groupedData).sort();
    
    new Chart(requestTrendCtx, {
        type: 'line',
        data: {
            labels: dates.map(date => new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
            datasets: [
                {
                    label: 'Pending',
                    data: dates.map(date => groupedData[date].pending),
                    borderColor: 'rgb(234, 179, 8)',
                    backgroundColor: 'rgba(234, 179, 8, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Approved',
                    data: dates.map(date => groupedData[date].approved),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Rejected',
                    data: dates.map(date => groupedData[date].rejected),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Service Type Distribution Chart
    const serviceTypeCtx = document.getElementById('serviceTypeChart').getContext('2d');
    const typeData = @json($typeDistribution);
    
    new Chart(serviceTypeCtx, {
        type: 'doughnut',
        data: {
            labels: typeData.map(item => item.service_type ? item.service_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Not Set'),
            datasets: [{
                data: typeData.map(item => item.count),
                backgroundColor: [
                    'rgb(79, 70, 229)',
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)',
                    'rgb(234, 179, 8)',
                    'rgb(156, 163, 175)',
                    'rgb(168, 85, 247)'
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
</script>
@endpush
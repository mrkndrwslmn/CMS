@extends('admin.layouts.app')

@section('title', 'Task Reports')
@section('page-title', 'Task Analytics & Performance')

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
                    <span class="text-neutral-700">Task Analytics</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-300 hover:bg-neutral-50 text-neutral-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </button>
                <a href="{{ route('admin.reports.export', ['type' => 'tasks', 'period' => $period]) }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Time Period Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.reports.tasks') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
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

    <!-- Task Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Total Tasks -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Tasks</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_tasks']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">All time tasks</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center">
                    <i class="fas fa-tasks text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Completed Tasks</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['completed_tasks']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-success-50 text-success-600 items-center justify-center mr-1">
                            <i class="fas fa-check text-[10px]"></i>
                        </span>
                        {{ number_format(($stats['completed_tasks'] / max($stats['total_tasks'], 1)) * 100, 1) }}% completion rate
                    </p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-success-50 text-success-500 flex items-center justify-center">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
        </div>

        <!-- In Progress Tasks -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">In Progress</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['in_progress_tasks']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">Currently active</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-warning-50 text-warning-500 flex items-center justify-center">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending Tasks</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['pending_tasks']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">Awaiting assignment</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-info-50 text-info-500 flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Overdue Tasks -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Overdue Tasks</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['overdue_tasks']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-error-50 text-error-600 items-center justify-center mr-1">
                            <i class="fas fa-exclamation text-[10px]"></i>
                        </span>
                        Needs attention
                    </p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-error-50 text-error-500 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Average Completion Time -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Avg. Completion Time</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">
                        {{ $stats['average_completion_time'] ? number_format($stats['average_completion_time'], 1) : '0' }}
                    </p>
                    <p class="text-xs text-neutral-500 mt-2">Days to complete</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center">
                    <i class="fas fa-stopwatch text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Task Creation Trend -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <i class="fas fa-chart-line text-primary-500 mr-2"></i>
                    Task Creation Trend (Last {{ $period }} Days)
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="taskTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Priority Distribution -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <i class="fas fa-chart-pie text-primary-500 mr-2"></i>
                    Priority Distribution
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="priorityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Duration by Priority & Adiutor Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Average Duration by Priority -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <i class="fas fa-chart-bar text-primary-500 mr-2"></i>
                    Average Duration by Priority
                </h3>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($durationByPriority as $priority)
                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-3
                                {{ $priority->priority === 'high' ? 'bg-error-100 text-error-800' : ($priority->priority === 'medium' ? 'bg-warning-100 text-warning-800' : 'bg-success-100 text-success-800') }}">
                                {{ ucfirst($priority->priority) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-semibold text-neutral-800">
                                {{ number_format($priority->avg_days, 1) }}
                            </span>
                            <span class="text-sm text-neutral-500 ml-1">days</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-neutral-400">
                        <i class="fas fa-chart-bar text-3xl mb-2"></i>
                        <p class="text-sm">No duration data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Performing Adiutors -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <i class="fas fa-trophy text-primary-500 mr-2"></i>
                    Adiutor Performance Overview
                </h3>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($tasksByAdiutor->take(8) as $adiutor)
                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                        <div class="flex items-center flex-1">
                            <div class="h-10 w-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-semibold text-sm mr-3">
                                {{ substr($adiutor->fullName, 0, 2) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-neutral-800 text-sm">{{ $adiutor->fullName }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-neutral-500">{{ $adiutor->assigned_tasks_count }} total</span>
                                    <span class="text-xs text-success-600">{{ $adiutor->completed_count }} done</span>
                                    @if($adiutor->overdue_count > 0)
                                    <span class="text-xs text-error-600">{{ $adiutor->overdue_count }} overdue</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            @php
                                $completionRate = $adiutor->assigned_tasks_count > 0 
                                    ? round(($adiutor->completed_count / $adiutor->assigned_tasks_count) * 100) 
                                    : 0;
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $completionRate >= 80 ? 'bg-success-100 text-success-800' : ($completionRate >= 50 ? 'bg-warning-100 text-warning-800' : 'bg-error-100 text-error-800') }}">
                                {{ $completionRate }}%
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-neutral-400">
                        <i class="fas fa-user-tie text-3xl mb-2"></i>
                        <p class="text-sm">No adiutor data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Task Trend Chart
    const taskTrendCtx = document.getElementById('taskTrendChart').getContext('2d');
    
    // Process task trend data
    const trendData = @json($taskTrend);
    const groupedData = {};
    const statuses = ['pending', 'in_progress', 'completed'];
    
    // Initialize data structure
    trendData.forEach(item => {
        if (!groupedData[item.date]) {
            groupedData[item.date] = { pending: 0, in_progress: 0, completed: 0 };
        }
        groupedData[item.date][item.status] = item.count;
    });
    
    const dates = Object.keys(groupedData).sort();
    
    new Chart(taskTrendCtx, {
        type: 'line',
        data: {
            labels: dates.map(date => new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
            datasets: [
                {
                    label: 'Pending',
                    data: dates.map(date => groupedData[date].pending),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'In Progress',
                    data: dates.map(date => groupedData[date].in_progress),
                    borderColor: 'rgb(234, 179, 8)',
                    backgroundColor: 'rgba(234, 179, 8, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Completed',
                    data: dates.map(date => groupedData[date].completed),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
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

    // Priority Distribution Chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    const priorityData = @json($priorityDistribution);
    
    new Chart(priorityCtx, {
        type: 'doughnut',
        data: {
            labels: priorityData.map(item => item.priority ? item.priority.charAt(0).toUpperCase() + item.priority.slice(1) : 'Not Set'),
            datasets: [{
                data: priorityData.map(item => item.count),
                backgroundColor: [
                    'rgb(239, 68, 68)',   // High - Red
                    'rgb(234, 179, 8)',   // Medium - Yellow
                    'rgb(34, 197, 94)',   // Low - Green
                    'rgb(156, 163, 175)'  // Not Set - Gray
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
@extends('admin.layouts.app')

@section('title', 'Dashboard Reports')
@section('page-title', 'Dashboard Reports & Analytics')

@section('content')
<div class="max-w-8xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <nav class="flex items-center space-x-2 text-sm text-neutral-500 mt-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
                    <x-lucide-chevron-right class="w-3 h-3" />
                    <a href="{{ route('admin.reports.index') }}" class="hover:text-primary-600 transition-colors">Reports</a>
                    <x-lucide-chevron-right class="w-3 h-3" />
                    <span class="text-neutral-700">Dashboard Analytics</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-300 hover:bg-neutral-50 text-neutral-700 font-medium rounded-lg transition-colors">
                    <x-lucide-printer class="w-4 h-4 mr-2" />
                    Print
                </button>
                <a href="{{ route('admin.reports.export', ['type' => 'dashboard', 'period' => $period]) }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                    <x-lucide-download class="w-4 h-4 mr-2" />
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Time Period Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.reports.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
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

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Users -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Users</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($kpis['total_users']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-primary-50 text-primary-600 items-center justify-center mr-1">
                            <x-lucide-arrow-up class="w-2.5 h-2.5" />
                        </span>
                        +{{ number_format($kpis['new_users']) }} this period
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
                    <p class="text-sm font-medium text-neutral-500">Total Tasks</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($kpis['total_tasks']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-success-50 text-success-600 items-center justify-center mr-1">
                            <x-lucide-check class="w-2.5 h-2.5" />
                        </span>
                        {{ number_format($kpis['completed_tasks']) }} completed
                    </p>
                </div>
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-warning-50 text-warning-500 flex items-center justify-center">
                    <x-lucide-list-todo class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Service Requests -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Service Requests</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($kpis['total_requests']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-warning-50 text-warning-600 items-center justify-center mr-1">
                            <x-lucide-clock class="w-2.5 h-2.5" />
                        </span>
                        {{ number_format($kpis['pending_requests']) }} pending
                    </p>
                </div>
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-success-50 text-success-500 flex items-center justify-center">
                    <x-lucide-clipboard-list class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Documents</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($kpis['total_documents']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-info-50 text-info-600 items-center justify-center mr-1">
                            <x-lucide-hard-drive class="w-2.5 h-2.5" />
                        </span>
                        {{ number_format($kpis['document_size'] / 1024 / 1024, 2) }} MB
                    </p>
                </div>
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-info-50 text-info-500 flex items-center justify-center">
                    <x-lucide-file-text class="w-5 h-5" />
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- User Growth Chart -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-trending-up class="w-4 h-4 text-primary-500 mr-2" />
                    User Growth (Last 12 Months)
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Request Status Distribution -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-pie-chart class="w-4 h-4 text-primary-500 mr-2" />
                    Request Status Distribution
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="requestStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Completion Trend -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-neutral-200">
            <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                <x-lucide-bar-chart-3 class="w-4 h-4 text-primary-500 mr-2" />
                Task Completion Trend (Last 30 Days)
            </h3>
        </div>
        <div class="p-5">
            <div class="h-64">
                <canvas id="taskCompletionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Clients & Adiutor Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Clients -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-trophy class="w-4 h-4 text-primary-500 mr-2" />
                    Top Clients by Task Count
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
                                {{ $client->tasks_count }} tasks
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-neutral-400">
                        <x-lucide-users class="w-8 h-8 mx-auto mb-2" />
                        <p class="text-sm">No client data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Adiutor Performance -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-user-check class="w-4 h-4 text-primary-500 mr-2" />
                    Adiutor Performance
                </h3>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($adiutorPerformance->take(10) as $adiutor)
                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                        <div class="flex items-center flex-1">
                            <div class="h-10 w-10 rounded-full bg-success-100 text-success-600 flex items-center justify-center font-semibold text-sm mr-3">
                                {{ substr($adiutor->fullName, 0, 2) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-neutral-800 text-sm">{{ $adiutor->fullName }}</p>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs text-neutral-500">{{ $adiutor->assigned_tasks_count }} tasks</span>
                                    <span class="text-xs text-success-600">{{ $adiutor->completed_tasks_count }} completed</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            @php
                                $completionRate = $adiutor->assigned_tasks_count > 0 
                                    ? round(($adiutor->completed_tasks_count / $adiutor->assigned_tasks_count) * 100) 
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
                        <x-lucide-briefcase-business class="w-8 h-8 mx-auto mb-2" />
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
    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($userGrowth->pluck('month')) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($userGrowth->pluck('count')) !!},
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
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

    // Request Status Distribution
    const requestStatusCtx = document.getElementById('requestStatusChart').getContext('2d');
    new Chart(requestStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($requestStatus->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($requestStatus->pluck('count')) !!},
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(234, 179, 8)',
                    'rgb(239, 68, 68)',
                    'rgb(156, 163, 175)'
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

    // Task Completion Trend
    const taskCompletionCtx = document.getElementById('taskCompletionChart').getContext('2d');
    new Chart(taskCompletionCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($taskCompletion->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('M j'); })) !!},
            datasets: [{
                label: 'Total Tasks',
                data: {!! json_encode($taskCompletion->pluck('total')) !!},
                backgroundColor: 'rgba(156, 163, 175, 0.5)',
            }, {
                label: 'Completed',
                data: {!! json_encode($taskCompletion->pluck('completed')) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
            }]
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
</script>
@endpush

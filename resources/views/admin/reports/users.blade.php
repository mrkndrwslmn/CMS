@extends('admin.layouts.app')

@section('title', 'User Reports')
@section('page-title', 'User Analytics & Statistics')

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
                    <span class="text-neutral-700">User Analytics</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-300 hover:bg-neutral-50 text-neutral-700 font-medium rounded-lg transition-colors">
                    <x-lucide-printer class="w-4 h-4 mr-2" />
                    Print
                </button>
                <a href="{{ route('admin.reports.export', ['type' => 'users', 'period' => $period]) }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                    <x-lucide-download class="w-4 h-4 mr-2" />
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Time Period Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.reports.users') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
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

    <!-- User Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Total Users -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Users</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-primary-50 text-primary-600 items-center justify-center mr-1">
                            <x-lucide-arrow-up class="w-2.5 h-2.5" />
                        </span>
                        +{{ number_format($stats['new_registrations']) }} new this period
                    </p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center">
                    <x-lucide-users class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Clients -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Clients</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['clients']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">
                        {{ number_format(($stats['clients'] / max($stats['total_users'], 1)) * 100, 1) }}% of total users
                    </p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-success-50 text-success-500 flex items-center justify-center">
                    <x-lucide-briefcase-business class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Adiutors -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Adiutors</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['adiutors']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">
                        {{ number_format(($stats['adiutors'] / max($stats['total_users'], 1)) * 100, 1) }}% of total users
                    </p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-warning-50 text-warning-500 flex items-center justify-center">
                    <x-lucide-user-check class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Active Users</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['active_users']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">
                        {{ number_format(($stats['active_users'] / max($stats['total_users'], 1)) * 100, 1) }}% activation rate
                    </p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-info-50 text-info-500 flex items-center justify-center">
                    <x-lucide-user-cog class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Admins -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Admins</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['admins']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">System administrators</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-error-50 text-error-500 flex items-center justify-center">
                    <x-lucide-shield-check class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- New Registrations -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">New Registrations</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['new_registrations']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">In last {{ $period }} days</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center">
                    <x-lucide-user-plus class="w-5 h-5" />
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Registration Trend -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-trending-up class="w-4 h-4 text-primary-500 mr-2" />
                    Registration Trend (Last {{ $period }} Days)
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="registrationTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- User Status Distribution -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-pie-chart class="w-4 h-4 text-primary-500 mr-2" />
                    User Status by Role
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="statusDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Active Users -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-neutral-200">
            <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                <x-lucide-star class="w-4 h-4 text-primary-500 mr-2" />
                Most Active Users (by Task Participation)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Tasks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Joined</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($activeUsers as $user)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-semibold text-sm mr-3">
                                    {{ substr($user->fullName, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-neutral-900">{{ $user->fullName }}</div>
                                    <div class="text-sm text-neutral-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-error-100 text-error-800' : ($user->role === 'adiutor' ? 'bg-warning-100 text-warning-800' : 'bg-success-100 text-success-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->status === 'active' ? 'bg-success-100 text-success-800' : 'bg-neutral-100 text-neutral-800' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                            <span class="font-semibold">{{ $user->task_count ?? 0 }}</span> tasks
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            {{ $user->created_at->format('M j, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-neutral-400">
                                <x-lucide-users class="w-8 h-8 mx-auto mb-2" />
                                <p class="text-sm">No user data available</p>
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
    // Registration Trend Chart
    const registrationCtx = document.getElementById('registrationTrendChart').getContext('2d');
    
    // Process registration trend data
    const trendData = @json($registrationTrend);
    const groupedData = {};
    const roles = ['client', 'adiutor', 'admin'];
    
    // Initialize data structure
    trendData.forEach(item => {
        if (!groupedData[item.date]) {
            groupedData[item.date] = { client: 0, adiutor: 0, admin: 0 };
        }
        groupedData[item.date][item.role] = item.count;
    });
    
    const dates = Object.keys(groupedData).sort();
    
    new Chart(registrationCtx, {
        type: 'line',
        data: {
            labels: dates.map(date => new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
            datasets: [
                {
                    label: 'Clients',
                    data: dates.map(date => groupedData[date].client),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Adiutors',
                    data: dates.map(date => groupedData[date].adiutor),
                    borderColor: 'rgb(234, 179, 8)',
                    backgroundColor: 'rgba(234, 179, 8, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Admins',
                    data: dates.map(date => groupedData[date].admin),
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

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    const statusData = @json($statusDistribution);
    
    // Process status data
    const statusLabels = [];
    const statusCounts = [];
    const statusColors = [];
    
    statusData.forEach(item => {
        statusLabels.push(`${item.role} (${item.status})`);
        statusCounts.push(item.count);
        
        // Color coding
        if (item.status === 'active') {
            statusColors.push('rgb(34, 197, 94)');
        } else if (item.status === 'inactive') {
            statusColors.push('rgb(156, 163, 175)');
        } else {
            statusColors.push('rgb(239, 68, 68)');
        }
    });
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: statusColors
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
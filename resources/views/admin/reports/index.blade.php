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
                    <select name="period" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                        <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30" {{ request('period', '30') == '30' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 Days</option>
                        <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last Year</option>
                        <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-neutral-800">
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
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">0</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-primary-50 text-primary-600 items-center justify-center mr-1">
                            <x-lucide-arrow-up class="w-2.5 h-2.5" />
                        </span>
                        +0 this period
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
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">0</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-warning-50 text-warning-600 items-center justify-center mr-1">
                            <x-lucide-list-todo class="w-2.5 h-2.5" />
                        </span>
                        0% completion rate
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
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">0</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-success-50 text-success-600 items-center justify-center mr-1">
                            <x-lucide-arrow-up class="w-2.5 h-2.5" />
                        </span>
                        +0 new clients
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
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">0</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-info-50 text-info-600 items-center justify-center mr-1">
                            <x-lucide-file-text class="w-2.5 h-2.5" />
                        </span>
                        0 MB total size
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
                    User Activity Trend
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
                <div class="space-y-4">
                    <div class="flex items-center justify-center py-10 text-neutral-400">
                        <div class="text-center">
                            <div class="h-14 w-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
                                <x-lucide-inbox class="w-5 h-5 text-neutral-400" />
                            </div>
                            <p class="text-sm text-neutral-500">No recent activities</p>
                        </div>
                    </div>
                </div>
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
                <div class="space-y-4">
                    <div class="flex items-center justify-center py-10 text-neutral-400">
                        <div class="text-center">
                            <div class="h-14 w-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
                                <x-lucide-award class="w-5 h-5 text-neutral-400" />
                            </div>
                            <p class="text-sm text-neutral-500">No performance data available</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Tables -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-neutral-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-table-2 class="w-4 h-4 text-primary-500 mr-2" />
                    Detailed Report Data
                </h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1.5 text-xs border border-neutral-300 bg-white text-neutral-700 rounded-md hover:bg-neutral-50 transition-colors flex items-center">
                        <x-lucide-file-spreadsheet class="w-3.5 h-3.5 mr-1.5 text-success-600" />
                        Export Excel
                    </button>
                    <button class="px-3 py-1.5 text-xs border border-neutral-300 bg-white text-neutral-700 rounded-md hover:bg-neutral-50 transition-colors flex items-center">
                        <x-lucide-file-text class="w-3.5 h-3.5 mr-1.5 text-error-600" />
                        Export PDF
                    </button>
                </div>
            </div>
        </div>
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Metric</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Current</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Previous</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Change</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center justify-center text-neutral-400">
                                    <div class="h-16 w-16 rounded-full bg-neutral-100 flex items-center justify-center mb-3">
                                        <x-lucide-bar-chart-3 class="w-6 h-6 text-neutral-400" />
                                    </div>
                                    <p class="font-medium text-neutral-500">No report data available</p>
                                    <p class="text-xs text-neutral-400 mt-1">Select a time period and generate a report</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/admin/reports.js') }}"></script>
@endsection

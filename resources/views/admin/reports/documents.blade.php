@extends('admin.layouts.app')

@section('title', 'Document Reports')
@section('page-title', 'Document Analytics & Storage')

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
                    <span class="text-neutral-700">Document Analytics</span>
                </nav>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-300 hover:bg-neutral-50 text-neutral-700 font-medium rounded-lg transition-colors">
                    <x-lucide-printer class="w-4 h-4 mr-2" />
                    Print
                </button>
                <a href="{{ route('admin.reports.export', ['type' => 'documents', 'period' => $period]) }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                    <x-lucide-download class="w-4 h-4 mr-2" />
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Time Period Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.reports.documents') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
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

    <!-- Document Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Total Documents -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Documents</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_documents']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">All uploaded files</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center">
                    <x-lucide-file-text class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Total Storage -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Storage</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_size'] / 1024 / 1024, 2) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">MB used</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-success-50 text-success-500 flex items-center justify-center">
                    <x-lucide-hard-drive class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Uploads This Period -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Uploads This Period</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['uploaded_this_period']) }}</p>
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <span class="flex h-4 w-4 rounded-full bg-info-50 text-info-600 items-center justify-center mr-1">
                            <x-lucide-arrow-up class="w-2 h-2" />
                        </span>
                        {{ number_format($stats['size_this_period'] / 1024 / 1024, 2) }} MB
                    </p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-warning-50 text-warning-500 flex items-center justify-center">
                    <x-lucide-upload class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Average File Size -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Average File Size</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['average_size'] / 1024, 2) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">KB per file</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-info-50 text-info-500 flex items-center justify-center">
                    <x-lucide-scale class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Storage Growth -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Storage Growth</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['size_this_period'] / 1024 / 1024, 2) }}</p>
                    <p class="text-xs text-neutral-500 mt-2">MB this period</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-error-50 text-error-500 flex items-center justify-center">
                    <x-lucide-trending-up class="w-5 h-5" />
                </div>
            </div>
        </div>

        <!-- Upload Activity -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Upload Activity</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">
                        {{ $stats['uploaded_this_period'] > 0 ? number_format($stats['uploaded_this_period'] / max($period, 1), 1) : '0' }}
                    </p>
                    <p class="text-xs text-neutral-500 mt-2">Files per day</p>
                </div>
                <div class="h-12 w-12 shrink-0 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center">
                    <x-lucide-calendar class="w-5 h-5" />
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Upload Trend -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-trending-up class="w-4 h-4 text-primary-500 mr-2" />
                    Upload Trend (Last {{ $period }} Days)
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="uploadTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- File Type Distribution -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-pie-chart class="w-4 h-4 text-primary-500 mr-2" />
                    File Type Distribution
                </h3>
            </div>
            <div class="p-5">
                <div class="h-64">
                    <canvas id="fileTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Distribution & Top Uploaders -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Category Distribution -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-folder class="w-4 h-4 text-primary-500 mr-2" />
                    Category Distribution
                </h3>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($categoryDistribution as $category)
                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-info-100 text-info-600 flex items-center justify-center font-semibold text-sm mr-3">
                                <x-lucide-folder class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="font-medium text-neutral-800 text-sm">
                                    {{ $category->category ? ucfirst(str_replace('_', ' ', $category->category)) : 'Uncategorized' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-semibold text-neutral-800">
                                {{ number_format($category->count) }}
                            </span>
                            <span class="text-sm text-neutral-500 ml-1">files</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-neutral-400">
                        <x-lucide-folder class="w-8 h-8 mx-auto mb-2" />
                        <p class="text-sm">No category data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Uploaders -->
        <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                    <x-lucide-trophy class="w-4 h-4 text-primary-500 mr-2" />
                    Top Uploaders
                </h3>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($topUploaders as $uploader)
                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-semibold text-sm mr-3">
                                {{ substr($uploader->fullName, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-medium text-neutral-800 text-sm">{{ $uploader->fullName }}</p>
                                <p class="text-xs text-neutral-500">{{ ucfirst($uploader->role) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                {{ $uploader->uploaded_documents_count }} uploads
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-neutral-400">
                        <x-lucide-users class="w-8 h-8 mx-auto mb-2" />
                        <p class="text-sm">No uploader data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Storage Usage by Client -->
    <div class="bg-white border border-neutral-200 rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-neutral-200">
            <h3 class="text-sm font-semibold text-neutral-800 flex items-center">
                <x-lucide-bar-chart-3 class="w-5 h-5 text-primary-500 mr-2" />
                Storage Usage by Client
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Documents</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Storage Used</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Average Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Usage</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($storageByClient as $client)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-semibold text-sm mr-3">
                                    {{ substr($client->fullName, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-neutral-900">{{ $client->fullName }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-neutral-800">{{ number_format($client->document_count) }}</span>
                            <span class="text-xs text-neutral-500 ml-1">files</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-neutral-800">{{ number_format($client->total_size / 1024 / 1024, 2) }}</span>
                            <span class="text-xs text-neutral-500 ml-1">MB</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-neutral-800">
                                {{ $client->document_count > 0 ? number_format(($client->total_size / $client->document_count) / 1024, 2) : '0' }}
                            </span>
                            <span class="text-xs text-neutral-500 ml-1">KB</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $usagePercent = $stats['total_size'] > 0 ? ($client->total_size / $stats['total_size']) * 100 : 0;
                            @endphp
                            <div class="flex items-center">
                                <div class="flex-1 bg-neutral-200 rounded-full h-2 mr-2">
                                    <div class="bg-primary-500 h-2 rounded-full" style="width: {{ min($usagePercent, 100) }}%"></div>
                                </div>
                                <span class="text-xs text-neutral-500">{{ number_format($usagePercent, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-neutral-400">
                                <x-lucide-users class="w-8 h-8 mx-auto mb-2" />
                                <p class="text-sm">No storage data available</p>
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
    // Upload Trend Chart
    const uploadTrendCtx = document.getElementById('uploadTrendChart').getContext('2d');
    const uploadData = @json($uploadTrend);
    
    new Chart(uploadTrendCtx, {
        type: 'line',
        data: {
            labels: uploadData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
            datasets: [
                {
                    label: 'Files Uploaded',
                    data: uploadData.map(item => item.count),
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y'
                },
                {
                    label: 'Storage (MB)',
                    data: uploadData.map(item => (item.total_size / 1024 / 1024).toFixed(2)),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // File Type Distribution Chart
    const fileTypeCtx = document.getElementById('fileTypeChart').getContext('2d');
    const typeData = @json($typeDistribution);
    
    new Chart(fileTypeCtx, {
        type: 'doughnut',
        data: {
            labels: typeData.map(item => item.type || 'Unknown'),
            datasets: [{
                data: typeData.map(item => item.count),
                backgroundColor: [
                    'rgb(239, 68, 68)',   // PDF - Red
                    'rgb(34, 197, 94)',   // Images - Green
                    'rgb(59, 130, 246)',  // Documents - Blue
                    'rgb(234, 179, 8)',   // Spreadsheets - Yellow
                    'rgb(168, 85, 247)',  // Archives - Purple
                    'rgb(156, 163, 175)'  // Others - Gray
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
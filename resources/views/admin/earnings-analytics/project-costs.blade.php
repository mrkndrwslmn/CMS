@extends('admin.layouts.app')

@section('title', 'Project Cost Analysis')

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Earnings Analytics', 'route' => 'admin.earnings-analytics.index', 'icon' => 'bar-chart-2'],
            ['label' => 'Project Costs', 'icon' => 'folder-kanban'],
        ]" />

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800">
                        Project Cost Analysis
                    </h1>
                    <p class="text-sm text-neutral-500 mt-1">Compare project budgets with actual labor costs</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <select name="period" class="px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" onchange="this.form.submit()">
                            <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>All Time</option>
                            <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                            <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                        </select>
                    </form>
                    <a href="{{ route('admin.earnings-analytics.export', ['type' => 'project_costs', 'period' => request('period', 'all')]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <x-lucide-download class="w-4 h-4" /> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Budget</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($summary['total_budget'], 2) }}</p>
                        <p class="text-xs text-neutral-400 mt-1">{{ number_format($summary['total_projects']) }} projects</p>
                    </div>
                    <div class="p-3 bg-primary-50 rounded-xl">
                        <x-lucide-target class="w-5 h-5 text-primary-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Adiutor Costs</p>
                        <p class="text-2xl font-semibold text-warning-600 mt-1">₱{{ number_format($summary['total_spent'], 2) }}</p>
                        <p class="text-xs text-neutral-400 mt-1">Hourly + fixed rate</p>
                    </div>
                    <div class="p-3 bg-warning-50 rounded-xl">
                        <x-lucide-users class="w-5 h-5 text-warning-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Platform Fee ({{ $summary['fee_percentage'] ?? 15 }}%)</p>
                        <p class="text-2xl font-semibold text-blue-600 mt-1">₱{{ number_format($summary['total_platform_fee'] ?? 0, 2) }}</p>
                        <p class="text-xs text-neutral-400 mt-1">Guaranteed revenue</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <x-lucide-percent class="w-5 h-5 text-blue-500" />
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-success-50 to-success-100 rounded-2xl border border-success-200 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-success-700">Platform Revenue</p>
                        <p class="text-2xl font-semibold text-success-700 mt-1">₱{{ number_format($summary['total_platform_revenue'] ?? 0, 2) }}</p>
                        <p class="text-xs text-success-600 mt-1">
                            Fee + ₱{{ number_format($summary['total_margin'] ?? 0, 2) }} margin
                        </p>
                    </div>
                    <div class="p-3 bg-success-200 rounded-xl">
                        <x-lucide-trending-up class="w-5 h-5 text-success-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Table -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Project</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Budget</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Hourly Cost</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Fixed Cost</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Total Cost</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Hours</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center">Adiutors</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center">Status</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5">Budget Usage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($projects as $project)
                            @php
                                $totalCost = $project->hourly_cost + $project->fixed_cost;
                                $budgetUtilization = $project->budget > 0 
                                    ? round(($totalCost / $project->budget) * 100, 1) 
                                    : 0;
                            @endphp
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="font-medium text-neutral-700">{{ $project->title }}</div>
                                    <div class="text-xs text-neutral-500">
                                        {{ $project->client?->fullName ?? 'No Client' }}
                                    </div>
                                </td>
                                <td class="py-4 px-5 text-right font-medium text-neutral-600">
                                    @if($project->budget)
                                        ₱{{ number_format($project->budget, 2) }}
                                    @else
                                        <span class="text-neutral-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-right text-primary-600">
                                    ₱{{ number_format($project->hourly_cost, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-neutral-600">
                                    ₱{{ number_format($project->fixed_cost, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right font-bold text-success-600">
                                    ₱{{ number_format($totalCost, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-neutral-600">
                                    {{ number_format($project->total_hours, 1) }} hrs
                                </td>
                                <td class="py-4 px-5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                        {{ $project->assignments_count }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-center">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-warning-100 text-warning-700',
                                            'in_progress' => 'bg-primary-100 text-primary-700',
                                            'completed' => 'bg-success-100 text-success-700',
                                            'cancelled' => 'bg-error-100 text-error-700',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$project->status] ?? 'bg-neutral-100 text-neutral-600' }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 min-w-[150px]">
                                    @if($project->budget > 0)
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-neutral-100 rounded-full h-2 overflow-hidden">
                                                <div class="h-full transition-all {{ $budgetUtilization > 100 ? 'bg-error-500' : ($budgetUtilization > 80 ? 'bg-warning-500' : 'bg-success-500') }}" 
                                                     style="width: {{ min($budgetUtilization, 100) }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium {{ $budgetUtilization > 100 ? 'text-error-500' : ($budgetUtilization > 80 ? 'text-warning-500' : 'text-success-500') }}">
                                                {{ $budgetUtilization }}%
                                            </span>
                                        </div>
                                        @if($budgetUtilization > 100)
                                            <div class="inline-flex items-center gap-1 text-xs text-error-500 mt-1">
                                                <x-lucide-alert-triangle class="w-3 h-3" /> Over budget by ₱{{ number_format($totalCost - $project->budget, 2) }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-neutral-400">No budget set</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-neutral-500">
                                    <x-lucide-folder-open class="w-12 h-12 mx-auto mb-3 text-neutral-300" />
                                    <p class="text-sm">No projects found for the selected period</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($projects->hasPages())
                <div class="p-5 border-t border-neutral-100">
                    <x-ui.pagination :paginator="$projects->withQueryString()" />
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Project Cost Analysis')

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 text-sm text-neutral-500">
                            <li><a href="{{ route('admin.earnings-analytics.index') }}" class="hover:text-accent-500">Earnings Analytics</a></li>
                            <li><i class="fas fa-chevron-right mx-2 text-xs"></i></li>
                            <li class="text-primary-500 font-medium">Project Costs</li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-semibold text-primary-500">
                        <i class="fas fa-project-diagram text-accent-500 mr-2"></i>
                        Project Cost Analysis
                    </h1>
                    <p class="text-neutral-500 mt-1">Compare project budgets with actual labor costs</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <select name="period" class="form-select rounded-lg border-neutral-200 text-sm" onchange="this.form.submit()">
                            <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>All Time</option>
                            <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                            <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                        </select>
                    </form>
                    <a href="{{ route('admin.earnings-analytics.export', ['type' => 'project_costs', 'period' => request('period', 'all')]) }}" 
                       class="glass-button-accent rounded-lg px-4 py-2 flex items-center text-sm">
                        <i class="fas fa-download mr-2"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-primary-500">
                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Total Projects</div>
                <div class="text-2xl font-bold text-primary-500">{{ number_format($summary['total_projects']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-accent-500">
                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Total Budget</div>
                <div class="text-2xl font-bold text-accent-500">₱{{ number_format($summary['total_budget'], 2) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-success-500">
                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Total Spent</div>
                <div class="text-2xl font-bold text-success-500">₱{{ number_format($summary['total_spent'], 2) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-tertiary-500">
                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Avg Cost/Project</div>
                <div class="text-2xl font-bold text-tertiary-500">₱{{ number_format($summary['average_cost_per_project'], 2) }}</div>
            </div>
        </div>

        <!-- Projects Table -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-100">
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Project</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Budget</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Hourly Cost</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Fixed Cost</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Total Cost</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Hours</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-center">Adiutors</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-center">Status</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5">Budget Usage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            @php
                                $totalCost = $project->hourly_cost + $project->fixed_cost;
                                $budgetUtilization = $project->budget > 0 
                                    ? round(($totalCost / $project->budget) * 100, 1) 
                                    : 0;
                            @endphp
                            <tr class="border-b border-neutral-50 hover:bg-neutral-50">
                                <td class="py-4 px-5">
                                    <div class="font-medium text-primary-500">{{ $project->title }}</div>
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
                                <td class="py-4 px-5 text-right text-accent-500">
                                    ₱{{ number_format($project->hourly_cost, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-secondary-500">
                                    ₱{{ number_format($project->fixed_cost, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right font-bold text-success-500">
                                    ₱{{ number_format($totalCost, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-neutral-600">
                                    {{ number_format($project->total_hours, 1) }} hrs
                                </td>
                                <td class="py-4 px-5 text-center">
                                    <span class="px-2.5 py-1 bg-neutral-100 text-neutral-600 rounded-full text-xs font-medium">
                                        {{ $project->assignments_count }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-center">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-warning-50 text-warning-600',
                                            'in_progress' => 'bg-accent-50 text-accent-600',
                                            'completed' => 'bg-success-50 text-success-600',
                                            'cancelled' => 'bg-error-50 text-error-600',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$project->status] ?? 'bg-neutral-100 text-neutral-600' }}">
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
                                            <div class="text-xs text-error-500 mt-1">
                                                <i class="fas fa-exclamation-triangle"></i> Over budget by ₱{{ number_format($totalCost - $project->budget, 2) }}
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
                                    <i class="fas fa-folder-open text-5xl mb-4 opacity-30"></i>
                                    <p>No projects found for the selected period</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($projects->hasPages())
                <div class="p-5 border-t border-neutral-100">
                    {{ $projects->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

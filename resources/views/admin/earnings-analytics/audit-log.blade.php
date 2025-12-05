@extends('admin.layouts.app')

@section('title', 'Time Entry Audit Log')

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Earnings Analytics', 'route' => 'admin.earnings-analytics.index', 'icon' => 'bar-chart-2'],
            ['label' => 'Audit Log', 'icon' => 'history'],
        ]" />

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800">
                        Time Entry Audit Log
                    </h1>
                    <p class="text-sm text-neutral-500 mt-1">Track all time entries, approvals, and adjustments</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.earnings-analytics.export', ['type' => 'time_entries', 'period' => request('period', 30)]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <x-lucide-download class="w-4 h-4" /> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Total Entries</p>
                        <p class="text-xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_entries']) }}</p>
                    </div>
                    <div class="p-2 bg-primary-50 rounded-lg">
                        <x-lucide-list class="w-4 h-4 text-primary-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Adjusted</p>
                        <p class="text-xl font-semibold text-warning-600 mt-1">{{ number_format($stats['adjusted_entries']) }}</p>
                    </div>
                    <div class="p-2 bg-warning-50 rounded-lg">
                        <x-lucide-pencil class="w-4 h-4 text-warning-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Pending</p>
                        <p class="text-xl font-semibold text-neutral-600 mt-1">{{ number_format($stats['pending_entries']) }}</p>
                    </div>
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <x-lucide-hourglass class="w-4 h-4 text-neutral-400" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Total Hours</p>
                        <p class="text-xl font-semibold text-primary-600 mt-1">{{ number_format($stats['total_hours'], 1) }}</p>
                    </div>
                    <div class="p-2 bg-primary-50 rounded-lg">
                        <x-lucide-clock class="w-4 h-4 text-primary-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase">Total Amount</p>
                        <p class="text-xl font-semibold text-success-600 mt-1">₱{{ number_format($stats['total_amount'], 2) }}</p>
                    </div>
                    <div class="p-2 bg-success-50 rounded-lg">
                        <x-lucide-banknote class="w-4 h-4 text-success-500" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Period</label>
                    <select name="period" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30" {{ request('period', '30') == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                        <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <select name="filter" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Entries</option>
                        <option value="pending" {{ $filter == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="approved" {{ $filter == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="adjusted" {{ $filter == 'adjusted' ? 'selected' : '' }}>Admin Adjusted</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Adiutor</label>
                    <select name="adiutor_id" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="">All Adiutors</option>
                        @foreach($adiutors as $adiutor)
                            <option value="{{ $adiutor->id }}" {{ $adiutorId == $adiutor->id ? 'selected' : '' }}>
                                {{ $adiutor->fullName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Project</label>
                    <select name="project_id" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                                {{ $project->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <x-lucide-filter class="w-4 h-4" /> Apply
                    </button>
                </div>
                <div>
                    <a href="{{ route('admin.earnings-analytics.audit-log') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-lg border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                        <x-lucide-x class="w-4 h-4" /> Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Entries Table -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-4 text-left">ID</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-4 text-left">Adiutor</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-4 text-left">Project / Task</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-4 text-left">Time</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-4 text-right">Duration</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-4 text-right">Amount</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-4 text-center">Status</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-4 text-left">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($timeEntries as $entry)
                            <tr class="hover:bg-neutral-50 transition-colors {{ $entry->wasAdjusted() ? 'bg-warning-50' : '' }}">
                                <td class="py-3 px-4 font-mono text-xs text-neutral-500">#{{ $entry->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-neutral-700">{{ $entry->adiutor?->fullName ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-neutral-700">{{ $entry->project?->title ?? 'N/A' }}</div>
                                    <div class="text-xs text-neutral-500">{{ $entry->task?->taskName ?? 'No task' }}</div>
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    <div class="text-neutral-600">{{ $entry->start_time?->format('M d, Y') }}</div>
                                    <div class="text-neutral-500">
                                        {{ $entry->start_time?->format('H:i') }} - {{ $entry->end_time?->format('H:i') }}
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="font-medium">{{ $entry->getFormattedDuration() }}</div>
                                    @if($entry->wasCapped())
                                        <div class="inline-flex items-center gap-1 text-xs text-warning-500">
                                            <x-lucide-alert-triangle class="w-3 h-3" /> Capped
                                        </div>
                                    @endif
                                    @if($entry->wasAdjusted() && $entry->original_duration_minutes)
                                        <div class="text-xs text-warning-500">
                                            Original: {{ $entry->getOriginalFormattedDuration() }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="font-semibold {{ $entry->is_approved ? 'text-success-600' : 'text-neutral-500' }}">
                                        {{ $entry->getFormattedAmount() }}
                                    </div>
                                    @if($entry->wasAdjusted() && $entry->original_calculated_amount)
                                        <div class="text-xs text-warning-500">
                                            Was: {{ $entry->getOriginalFormattedAmount() }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($entry->is_approved)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                            <x-lucide-check-circle class="w-3 h-3" /> Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                            <x-lucide-clock class="w-3 h-3" /> Pending
                                        </span>
                                    @endif
                                    @if($entry->wasAdjusted())
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                                <x-lucide-pencil class="w-3 h-3" /> Adjusted
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 max-w-xs">
                                    @if($entry->description)
                                        <div class="text-xs text-neutral-600 truncate" title="{{ $entry->description }}">
                                            {{ Str::limit($entry->description, 50) }}
                                        </div>
                                    @endif
                                    @if($entry->wasAdjusted())
                                        <div class="inline-flex items-center gap-1 text-xs text-warning-600 mt-1" title="Adjusted by {{ $entry->adjuster?->fullName }}">
                                            <x-lucide-user-pen class="w-3 h-3" /> {{ $entry->adjuster?->fullName ?? 'Admin' }}
                                            @if($entry->adjustment_reason)
                                                : {{ Str::limit($entry->adjustment_reason, 30) }}
                                            @endif
                                        </div>
                                    @endif
                                    @if($entry->is_approved && $entry->approver)
                                        <div class="inline-flex items-center gap-1 text-xs text-success-600 mt-1">
                                            <x-lucide-user-check class="w-3 h-3" /> By {{ $entry->approver->fullName }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-neutral-500">
                                    <x-lucide-clock class="w-12 h-12 mx-auto mb-3 text-neutral-300" />
                                    <p class="text-sm">No time entries found for the selected filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($timeEntries->hasPages())
                <div class="p-5 border-t border-neutral-100">
                    <x-ui.pagination :paginator="$timeEntries->withQueryString()" />
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

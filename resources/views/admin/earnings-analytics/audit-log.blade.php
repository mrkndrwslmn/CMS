@extends('admin.layouts.app')

@section('title', 'Time Entry Audit Log')

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
                            <li class="text-primary-500 font-medium">Audit Log</li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-semibold text-primary-500">
                        <i class="fas fa-history text-accent-500 mr-2"></i>
                        Time Entry Audit Log
                    </h1>
                    <p class="text-neutral-500 mt-1">Track all time entries, approvals, and adjustments</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.earnings-analytics.export', ['type' => 'time_entries', 'period' => request('period', 30)]) }}" 
                       class="glass-button-accent rounded-lg px-4 py-2 flex items-center text-sm">
                        <i class="fas fa-download mr-2"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-primary-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Total Entries</div>
                <div class="text-xl font-bold text-primary-500">{{ number_format($stats['total_entries']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-warning-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Adjusted</div>
                <div class="text-xl font-bold text-warning-500">{{ number_format($stats['adjusted_entries']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-tertiary-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Pending</div>
                <div class="text-xl font-bold text-tertiary-500">{{ number_format($stats['pending_entries']) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-accent-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Total Hours</div>
                <div class="text-xl font-bold text-accent-500">{{ number_format($stats['total_hours'], 1) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-success-500">
                <div class="text-xs uppercase font-bold text-neutral-500">Total Amount</div>
                <div class="text-xl font-bold text-success-500">₱{{ number_format($stats['total_amount'], 2) }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Period</label>
                    <select name="period" class="form-select rounded-lg border-neutral-200 w-full text-sm">
                        <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30" {{ request('period', '30') == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                        <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <select name="filter" class="form-select rounded-lg border-neutral-200 w-full text-sm">
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Entries</option>
                        <option value="pending" {{ $filter == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="approved" {{ $filter == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="adjusted" {{ $filter == 'adjusted' ? 'selected' : '' }}>Admin Adjusted</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Adiutor</label>
                    <select name="adiutor_id" class="form-select rounded-lg border-neutral-200 w-full text-sm">
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
                    <select name="project_id" class="form-select rounded-lg border-neutral-200 w-full text-sm">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                                {{ $project->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="glass-button-accent rounded-lg px-4 py-2 text-sm">
                        <i class="fas fa-filter mr-2"></i> Apply
                    </button>
                </div>
                <div>
                    <a href="{{ route('admin.earnings-analytics.audit-log') }}" class="glass-button rounded-lg px-4 py-2 text-sm">
                        <i class="fas fa-times mr-2"></i> Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Entries Table -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-100">
                            <th class="text-neutral-500 font-semibold py-3 px-4 text-left">ID</th>
                            <th class="text-neutral-500 font-semibold py-3 px-4 text-left">Adiutor</th>
                            <th class="text-neutral-500 font-semibold py-3 px-4 text-left">Project / Task</th>
                            <th class="text-neutral-500 font-semibold py-3 px-4 text-left">Time</th>
                            <th class="text-neutral-500 font-semibold py-3 px-4 text-right">Duration</th>
                            <th class="text-neutral-500 font-semibold py-3 px-4 text-right">Amount</th>
                            <th class="text-neutral-500 font-semibold py-3 px-4 text-center">Status</th>
                            <th class="text-neutral-500 font-semibold py-3 px-4 text-left">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timeEntries as $entry)
                            <tr class="border-b border-neutral-50 hover:bg-neutral-50 {{ $entry->wasAdjusted() ? 'bg-warning-50' : '' }}">
                                <td class="py-3 px-4 font-mono text-xs text-neutral-500">#{{ $entry->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-primary-500">{{ $entry->adiutor?->fullName ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-primary-500">{{ $entry->project?->title ?? 'N/A' }}</div>
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
                                        <div class="text-xs text-warning-500">
                                            <i class="fas fa-exclamation-triangle"></i> Capped
                                        </div>
                                    @endif
                                    @if($entry->wasAdjusted() && $entry->original_duration_minutes)
                                        <div class="text-xs text-warning-500">
                                            Original: {{ $entry->getOriginalFormattedDuration() }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="font-semibold {{ $entry->is_approved ? 'text-success-500' : 'text-neutral-500' }}">
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
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success-50 text-success-600">
                                            <i class="fas fa-check-circle mr-1"></i> Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-warning-50 text-warning-600">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                    @if($entry->wasAdjusted())
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-50 text-orange-600">
                                                <i class="fas fa-edit mr-1"></i> Adjusted
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
                                        <div class="text-xs text-warning-600 mt-1" title="Adjusted by {{ $entry->adjuster?->fullName }}">
                                            <i class="fas fa-user-edit"></i> {{ $entry->adjuster?->fullName ?? 'Admin' }}
                                            @if($entry->adjustment_reason)
                                                : {{ Str::limit($entry->adjustment_reason, 30) }}
                                            @endif
                                        </div>
                                    @endif
                                    @if($entry->is_approved && $entry->approver)
                                        <div class="text-xs text-success-600 mt-1">
                                            <i class="fas fa-user-check"></i> By {{ $entry->approver->fullName }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-neutral-500">
                                    <i class="fas fa-clock text-5xl mb-4 opacity-30"></i>
                                    <p>No time entries found for the selected filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($timeEntries->hasPages())
                <div class="p-5 border-t border-neutral-100">
                    {{ $timeEntries->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

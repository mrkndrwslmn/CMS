@extends('adiutor.layouts.app')

@section('title', 'Revision Requests')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Revisions'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Revision Requests</h1>
            <p class="text-sm text-neutral-500 mt-1">View and manage revision requests assigned to you</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-2xl bg-success-50 border border-success-100 p-4">
        <div class="flex items-center gap-3">
            <div class="p-1.5 bg-success-100 rounded-lg">
                <x-lucide-check-circle-2 class="w-5 h-5 text-success-600" />
            </div>
            <p class="text-sm font-medium text-success-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 rounded-2xl bg-error-50 border border-error-100 p-4">
        <div class="flex items-center gap-3">
            <div class="p-1.5 bg-error-100 rounded-lg">
                <x-lucide-x-circle class="w-5 h-5 text-error-600" />
            </div>
            <p class="text-sm font-medium text-error-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-primary-50 border border-primary-100 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 rounded-xl">
                    <x-lucide-file-edit class="w-5 h-5 text-primary-600" />
                </div>
                <div>
                    <p class="text-2xl font-semibold text-primary-800">{{ $statusCounts['all'] }}</p>
                    <p class="text-sm text-primary-600">Total Revisions</p>
                </div>
            </div>
        </div>
        <div class="bg-warning-50 border border-warning-100 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-warning-100 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-600" />
                </div>
                <div>
                    <p class="text-2xl font-semibold text-warning-800">{{ $statusCounts['approved'] }}</p>
                    <p class="text-sm text-warning-600">Approved (Pending Work)</p>
                </div>
            </div>
        </div>
        <div class="bg-success-50 border border-success-100 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-success-100 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-600" />
                </div>
                <div>
                    <p class="text-2xl font-semibold text-success-800">{{ $statusCounts['completed'] }}</p>
                    <p class="text-sm text-success-600">Completed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revisions List -->
    <x-ui.card class="overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-neutral-800">Your Assigned Revisions</h2>
            
            <!-- Filter -->
            <form method="GET" action="{{ route('adiutor.revisions.index') }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" 
                        class="text-sm border-neutral-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </form>
        </div>

        @if($revisions->isEmpty())
        <div class="p-12 text-center">
            <div class="flex justify-center mb-4">
                <div class="p-4 bg-neutral-100 rounded-full">
                    <x-lucide-file-edit class="w-8 h-8 text-neutral-400" />
                </div>
            </div>
            <h3 class="text-lg font-semibold text-neutral-800 mb-1">No revision requests</h3>
            <p class="text-sm text-neutral-500">You don't have any revision requests assigned to you yet.</p>
        </div>
        @else
        <div class="divide-y divide-neutral-100">
            @foreach($revisions as $revision)
            <div class="px-6 py-4 hover:bg-neutral-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-neutral-800">
                                @if($revision->document)
                                    {{ $revision->document->filename ?? 'Document Revision' }}
                                @elseif($revision->task)
                                    {{ $revision->task->taskTitle ?? 'Task Revision' }}
                                @else
                                    Revision #{{ $revision->id }}
                                @endif
                            </h3>
                            @php
                                $statusClass = match($revision->status) {
                                    'pending' => 'bg-neutral-100 text-neutral-800',
                                    'approved' => 'bg-warning-100 text-warning-800',
                                    'completed' => 'bg-success-100 text-success-800',
                                    'rejected' => 'bg-error-100 text-error-800',
                                    default => 'bg-neutral-100 text-neutral-800'
                                };
                                $priorityClass = match($revision->priority ?? 'normal') {
                                    'urgent' => 'bg-error-100 text-error-800',
                                    'high' => 'bg-warning-100 text-warning-800',
                                    'normal' => 'bg-primary-100 text-primary-800',
                                    'low' => 'bg-neutral-100 text-neutral-800',
                                    default => 'bg-neutral-100 text-neutral-800'
                                };
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                {{ ucfirst($revision->status) }}
                            </span>
                            @if($revision->priority)
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $priorityClass }}">
                                {{ ucfirst($revision->priority) }}
                            </span>
                            @endif
                        </div>
                        <p class="text-sm text-neutral-500 mb-1">
                            @if($revision->project)
                                Project: {{ $revision->project->title }}
                            @elseif($revision->task && $revision->task->project)
                                Project: {{ $revision->task->project->title }}
                            @endif
                        </p>
                        <div class="flex items-center gap-4 text-sm text-neutral-500">
                            <span>Requested by: {{ $revision->requestedBy->name ?? 'Unknown' }}</span>
                            @if($revision->requested_due_date)
                            <span class="text-neutral-300">|</span>
                            <span class="{{ $revision->requested_due_date->isPast() ? 'text-error-600' : '' }}">
                                Due: {{ $revision->requested_due_date->format('M d, Y') }}
                            </span>
                            @endif
                            <span class="text-neutral-300">|</span>
                            <span>{{ $revision->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($revision->reason)
                        <p class="text-sm text-neutral-600 mt-2 line-clamp-2">
                            <span class="font-medium">Reason:</span> {{ Str::limit($revision->reason, 100) }}
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('adiutor.revisions.show', $revision->id) }}" 
                           class="px-3 py-1.5 text-sm text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors">
                            View Details
                        </a>
                        @if($revision->status === 'approved')
                        <a href="{{ route('adiutor.revisions.show', $revision->id) }}" 
                           class="px-3 py-1.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
                            Work on This
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($revisions->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100 bg-neutral-50">
            {{ $revisions->links() }}
        </div>
        @endif
        @endif
    </x-ui.card>
</div>
@endsection

@extends('adiutor.layouts.app')

@section('title', $task->taskTitle)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Tasks', 'route' => 'adiutor.tasks.index', 'icon' => 'clipboard-list'],
        ['label' => Str::limit($task->taskTitle, 40)],
    ]" />

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-start gap-4 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center">
                        <x-lucide-clipboard-check class="w-6 h-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-semibold text-neutral-800 mb-3">{{ $task->taskTitle }}</h1>
                        <div class="flex flex-wrap items-center gap-3">
                            @php
                                $statusVariant = match($task->status) {
                                    'completed' => 'success',
                                    'in_progress' => 'primary',
                                    'pending' => 'warning',
                                    'pending_approval' => 'info',
                                    default => 'neutral'
                                };
                            @endphp
                            <x-ui.badge variant="{{ $statusVariant }}">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </x-ui.badge>
                            
                            @php
                                $priorityVariant = match($task->priority) {
                                    'urgent' => 'error',
                                    'high' => 'warning',
                                    'medium' => 'primary',
                                    'low' => 'neutral',
                                    default => 'neutral'
                                };
                            @endphp
                            <x-ui.badge variant="{{ $priorityVariant }}">
                                <x-lucide-flag class="w-3 h-3 mr-1" />
                                {{ ucfirst($task->priority ?? 'Normal') }} Priority
                            </x-ui.badge>
                        </div>
                    </div>
                </div>
            </div>
            @if($task->status !== 'completed')
                <div class="flex-shrink-0">
                    <x-ui.button onclick="updateProgress()" variant="primary">
                        <x-lucide-zap class="w-4 h-4 mr-2" />
                        Update Progress
                    </x-ui.button>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Budget Card -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between mb-3">
                <span class="text-neutral-500 text-sm font-medium">Allocated Budget</span>
                <div class="p-2.5 bg-success-50 rounded-xl">
                    <x-lucide-banknote class="w-5 h-5 text-success-600" />
                </div>
            </div>
            <p class="text-2xl font-semibold text-neutral-800">₱{{ number_format($task->allocated_budget ?? 0, 2) }}</p>
            @if($task->actual_cost)
                <div class="mt-2 flex items-center text-xs">
                    <span class="text-neutral-500">Spent: ₱{{ number_format($task->actual_cost, 2) }}</span>
                    @php
                        $remaining = ($task->allocated_budget ?? 0) - $task->actual_cost;
                        $isOverBudget = $remaining < 0;
                    @endphp
                    <span class="ml-2 px-2 py-0.5 rounded-full {{ $isOverBudget ? 'bg-error-100 text-error-700' : 'bg-success-100 text-success-700' }}">
                        {{ $isOverBudget ? 'Over' : 'Remaining' }}: ₱{{ number_format(abs($remaining), 2) }}
                    </span>
                </div>
            @else
                <p class="text-neutral-500 text-xs mt-1">No expenses yet</p>
            @endif
        </x-ui.card>

        <!-- Deadline Card -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between mb-3">
                <span class="text-neutral-500 text-sm font-medium">Deadline</span>
                <div class="p-2.5 bg-primary-50 rounded-xl">
                    <x-lucide-calendar class="w-5 h-5 text-primary-600" />
                </div>
            </div>
            @if($task->deadline)
                <p class="text-2xl font-semibold text-neutral-800">{{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}</p>
                <p class="text-neutral-500 text-xs mt-1">{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</p>
            @else
                <p class="text-2xl font-semibold text-neutral-800">No deadline</p>
                <p class="text-neutral-500 text-xs mt-1">Flexible timeline</p>
            @endif
        </x-ui.card>

        <!-- Progress Card -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between mb-3">
                <span class="text-neutral-500 text-sm font-medium">Progress</span>
                <div class="p-2.5 bg-primary-50 rounded-xl">
                    <x-lucide-bar-chart-3 class="w-5 h-5 text-primary-600" />
                </div>
            </div>
            <p class="text-2xl font-semibold text-primary-600">{{ $task->progress_percentage ?? 0 }}%</p>
            <div class="w-full bg-neutral-200 rounded-full h-2 mt-3">
                <div class="bg-primary-500 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ $task->progress_percentage ?? 0 }}%"></div>
            </div>
        </x-ui.card>

        <!-- Project Card -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between mb-3">
                <span class="text-neutral-500 text-sm font-medium">Parent Project</span>
                <div class="p-2.5 bg-neutral-100 rounded-xl">
                    <x-lucide-folder-kanban class="w-5 h-5 text-neutral-600" />
                </div>
            </div>
            <p class="text-lg font-semibold text-neutral-800 mb-2">{{ Str::limit($task->project_title, 35) }}</p>
            <a href="{{ route('adiutor.projects.show', $task->project_id) }}" class="inline-flex items-center text-primary-600 text-sm font-medium hover:text-primary-700 transition-colors">
                View details 
                <x-lucide-arrow-right class="w-4 h-4 ml-1.5" />
            </a>
        </x-ui.card>
    </div>

    <!-- Phase Information (if applicable) -->
    @if($task->phase_id && $task->phase_name)
        <div class="bg-primary-50 rounded-2xl p-6 border border-primary-200 mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
                        <x-lucide-layers class="w-6 h-6 text-primary-600" />
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600 font-medium mb-1">Project Phase</p>
                        <h3 class="text-xl font-semibold text-neutral-800">{{ $task->phase_name }}</h3>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if($task->phase_budget)
                        <div class="bg-white px-4 py-2.5 rounded-xl border border-neutral-200">
                            <p class="text-xs text-neutral-500 mb-0.5">Phase Budget</p>
                            <p class="text-lg font-semibold text-neutral-800">₱{{ number_format($task->phase_budget, 2) }}</p>
                        </div>
                    @endif
                    <div class="bg-white px-4 py-2.5 rounded-xl border border-neutral-200">
                        <p class="text-xs text-neutral-500 mb-0.5">Payment Status</p>
                        @if($task->phase_is_paid)
                            <x-ui.badge variant="success">
                                <x-lucide-check class="w-3 h-3 mr-1" />
                                Paid
                            </x-ui.badge>
                        @else
                            <x-ui.badge variant="warning">
                                <x-lucide-clock class="w-3 h-3 mr-1" />
                                Pending
                            </x-ui.badge>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Task Description -->
            <x-ui.card>
                <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-xl flex items-center justify-center mr-3">
                            <x-lucide-align-left class="w-4 h-4 text-primary-600" />
                        </div>
                        Description
                    </h3>
                </div>
                <div class="px-6 py-5">
                    @if($task->taskDescription)
                        <div class="prose prose-sm max-w-none text-neutral-700 leading-relaxed">
                            {{ $task->taskDescription }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-lucide-file-text class="w-8 h-8 text-neutral-400" />
                            </div>
                            <p class="text-neutral-500 text-sm">No description provided</p>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Task Notes -->
            @if($task->notes)
                <x-ui.card>
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                            <div class="w-8 h-8 bg-warning-100 rounded-xl flex items-center justify-center mr-3">
                                <x-lucide-sticky-note class="w-4 h-4 text-warning-600" />
                            </div>
                            Notes & Comments
                        </h3>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-neutral-700 leading-relaxed whitespace-pre-wrap">{{ $task->notes }}</p>
                    </div>
                </x-ui.card>
            @endif

            <!-- Completion Notes -->
            @if($task->completion_notes && $task->status === 'completed')
                <x-ui.card>
                    <div class="px-6 py-4 bg-success-50 border-b border-success-200">
                        <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                            <div class="w-8 h-8 bg-success-100 rounded-xl flex items-center justify-center mr-3">
                                <x-lucide-check-circle class="w-4 h-4 text-success-600" />
                            </div>
                            Completion Notes
                        </h3>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-neutral-700 leading-relaxed whitespace-pre-wrap">{{ $task->completion_notes }}</p>
                    </div>
                </x-ui.card>
            @endif

            <!-- File Attachments -->
            <x-ui.card>
                <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                            <div class="w-8 h-8 bg-primary-100 rounded-xl flex items-center justify-center mr-3">
                                <x-lucide-paperclip class="w-4 h-4 text-primary-600" />
                            </div>
                            Attachments
                            @if(isset($taskFiles) && $taskFiles->count() > 0)
                                <span class="ml-2 px-2 py-0.5 bg-primary-100 text-primary-700 text-xs font-medium rounded-full">
                                    {{ $taskFiles->count() }}
                                </span>
                            @endif
                        </h3>
                        @if($task->status !== 'completed')
                            <x-ui.button onclick="openUploadModal()" variant="primary" size="sm">
                                <x-lucide-upload-cloud class="w-4 h-4 mr-2" />
                                Upload
                            </x-ui.button>
                        @endif
                    </div>
                </div>
                <div class="px-6 py-5">
                    @if(isset($taskFiles) && $taskFiles->count() > 0)
                        <div class="space-y-3">
                            @foreach($taskFiles as $file)
                                <div class="group flex items-center justify-between p-4 bg-neutral-50 hover:bg-neutral-100 rounded-xl transition-all border border-neutral-200 hover:border-primary-200">
                                    <div class="flex items-center space-x-4 flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-neutral-200 group-hover:border-primary-200 transition-colors">
                                            <x-lucide-file-text class="w-6 h-6 text-error-500" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-neutral-800 truncate">{{ $file->fileName }}</p>
                                            <p class="text-sm text-neutral-500 flex items-center">
                                                <x-lucide-clock class="w-3 h-3 mr-1" />
                                                {{ \Carbon\Carbon::parse($file->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-ui.button href="{{ route('adiutor.tasks.download-file', $file->documentID) }}" variant="primary" size="sm">
                                            <x-lucide-download class="w-4 h-4 mr-1" />
                                            Download
                                        </x-ui.button>
                                        @if($file->uploaded_by == Auth::id())
                                            <form action="{{ route('adiutor.tasks.delete-file', $file->documentID) }}" method="POST" onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete File', 'Are you sure you want to delete this file?')">
                                                @csrf
                                                @method('DELETE')
                                                <x-ui.button type="submit" variant="danger" size="sm">
                                                    <x-lucide-trash-2 class="w-4 h-4 mr-1" />
                                                    Delete
                                                </x-ui.button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-lucide-folder-open class="w-10 h-10 text-neutral-400" />
                            </div>
                            <p class="text-neutral-500 text-sm mb-4">No files uploaded yet</p>
                            @if($task->status !== 'completed')
                                <x-ui.button onclick="openUploadModal()" variant="primary">
                                    <x-lucide-upload-cloud class="w-4 h-4 mr-2" />
                                    Upload Your First File
                                </x-ui.button>
                            @endif
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Budget Change Request -->
            @if($task->status !== 'completed')
                <x-ui.card>
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                            <div class="w-8 h-8 bg-success-100 rounded-xl flex items-center justify-center mr-3">
                                <x-lucide-hand-coins class="w-4 h-4 text-success-600" />
                            </div>
                            Budget Adjustment
                        </h3>
                    </div>
                    <div class="px-6 py-5">
                        @if(isset($pendingBudgetRequest))
                            <div class="bg-warning-50 rounded-xl p-5 border border-warning-200">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-warning-100 rounded-xl flex items-center justify-center">
                                        <x-lucide-clock class="w-5 h-5 text-warning-600" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-warning-900 mb-2">Pending Budget Request</p>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-neutral-600">Requested Amount:</span>
                                                <span class="font-semibold text-neutral-800">₱{{ number_format($pendingBudgetRequest->requested_budget, 2) }}</span>
                                            </div>
                                            <div class="pt-2 border-t border-warning-200">
                                                <p class="text-neutral-600 mb-1">Reason:</p>
                                                <p class="text-neutral-700">{{ $pendingBudgetRequest->reason }}</p>
                                            </div>
                                            <p class="text-xs text-neutral-500 pt-2 flex items-center">
                                                <x-lucide-clock class="w-3 h-3 mr-1" />
                                                Submitted {{ \Carbon\Carbon::parse($pendingBudgetRequest->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-neutral-50 rounded-xl p-5 border border-neutral-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-sm text-neutral-600">Current Allocated Budget</p>
                                        <p class="text-2xl font-semibold text-neutral-800">₱{{ number_format($task->allocated_budget ?? 0, 2) }}</p>
                                    </div>
                                    <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                                        <x-lucide-wallet class="w-6 h-6 text-success-600" />
                                    </div>
                                </div>
                                <x-ui.button onclick="openBudgetModal()" variant="primary" class="w-full justify-center">
                                    <x-lucide-send class="w-4 h-4 mr-2" />
                                    Request Budget Change
                                </x-ui.button>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            @endif
        </div>

        <!-- Right Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Task Information Card -->
            <x-ui.card>
                <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-xl flex items-center justify-center mr-3">
                            <x-lucide-info class="w-4 h-4 text-primary-600" />
                        </div>
                        Task Information
                    </h3>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <!-- Deadline -->
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Deadline</p>
                        @if($task->deadline)
                            <div class="flex items-center text-sm">
                                <x-lucide-calendar class="w-4 h-4 text-primary-500 mr-2" />
                                <span class="font-semibold text-neutral-800">{{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}</span>
                            </div>
                            <p class="text-xs text-neutral-500 mt-1 ml-6">{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</p>
                        @else
                            <p class="text-sm text-neutral-500">No deadline set</p>
                        @endif
                    </div>

                    <!-- Completed Date (if completed) -->
                    @if($task->status === 'completed' && $task->completedAt)
                        <div class="pt-4 border-t border-neutral-200">
                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Completed On</p>
                            <div class="flex items-center text-sm">
                                <x-lucide-check-circle class="w-4 h-4 text-success-500 mr-2" />
                                <span class="font-semibold text-neutral-800">{{ \Carbon\Carbon::parse($task->completedAt)->format('M d, Y h:i A') }}</span>
                            </div>
                            <p class="text-xs text-neutral-500 mt-1 ml-6">{{ \Carbon\Carbon::parse($task->completedAt)->diffForHumans() }}</p>
                        </div>
                    @endif

                    <!-- Created Date -->
                    @if($task->created_at)
                        <div class="pt-4 border-t border-neutral-200">
                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Created On</p>
                            <div class="flex items-center text-sm">
                                <x-lucide-clock class="w-4 h-4 text-neutral-400 mr-2" />
                                <span class="text-neutral-700">{{ \Carbon\Carbon::parse($task->created_at)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Assigned To -->
                    @if($task->assignee_name)
                        <div class="pt-4 border-t border-neutral-200">
                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Assigned To</p>
                            <div class="flex items-center text-sm">
                                <x-lucide-user-check class="w-4 h-4 text-primary-500 mr-2" />
                                <span class="font-semibold text-neutral-800">{{ $task->assignee_name }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Quick Actions - Priority Placement -->
            @if($task->status !== 'completed')
                <x-ui.card class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-success-100 rounded-xl flex items-center justify-center mr-3">
                            <x-lucide-check-circle class="w-5 h-5 text-success-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-neutral-800">Complete Task</h3>
                    </div>
                    <p class="text-neutral-600 text-sm mb-4">
                        Ready to mark this task as complete? This action will notify the admin and client.
                    </p>
                    <form action="{{ route('adiutor.tasks.complete', $task->taskID) }}" method="POST">
                        @csrf
                        <x-ui.button type="submit" variant="success" class="w-full justify-center">
                            <x-lucide-check-check class="w-4 h-4 mr-2" />
                            Mark as Complete
                        </x-ui.button>
                    </form>
                </x-ui.card>
            @endif

            <!-- Client Information -->
            <x-ui.card>
                <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-xl flex items-center justify-center mr-3">
                            <x-lucide-user class="w-4 h-4 text-primary-600" />
                        </div>
                        Client
                    </h3>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Name</p>
                        <p class="text-base font-semibold text-neutral-800">{{ $task->client_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Email</p>
                        <a href="mailto:{{ $task->client_email }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium break-all group">
                            <x-lucide-mail class="w-4 h-4 mr-2 text-primary-500 group-hover:text-primary-600" />
                            {{ $task->client_email }}
                        </a>
                    </div>
                </div>
            </x-ui.card>

            <!-- Project Info -->
            <x-ui.card>
                <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-xl flex items-center justify-center mr-3">
                            <x-lucide-folder-kanban class="w-4 h-4 text-primary-600" />
                        </div>
                        Project
                    </h3>
                </div>
                <div class="px-6 py-5">
                    <div class="bg-neutral-50 rounded-xl p-4 border border-neutral-200">
                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Project Name</p>
                        <p class="text-base font-semibold text-neutral-800 mb-4">{{ $task->project_title }}</p>
                        <x-ui.button href="{{ route('adiutor.projects.show', $task->project_id) }}" variant="primary" class="w-full justify-center">
                            <x-lucide-external-link class="w-4 h-4 mr-2" />
                            View Full Project
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.card>

            <!-- Navigation Links -->
            <x-ui.card>
                <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                        <div class="w-8 h-8 bg-neutral-200 rounded-xl flex items-center justify-center mr-3">
                            <x-lucide-compass class="w-4 h-4 text-neutral-600" />
                        </div>
                        Navigate
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('adiutor.tasks.index') }}" class="flex items-center justify-between p-3 bg-neutral-50 hover:bg-primary-50 rounded-xl transition-all group border border-transparent hover:border-primary-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white group-hover:bg-primary-100 rounded-xl flex items-center justify-center mr-3 transition-colors">
                                <x-lucide-list class="w-4 h-4 text-neutral-600 group-hover:text-primary-600 transition-colors" />
                            </div>
                            <span class="font-medium text-neutral-700 group-hover:text-primary-700 transition-colors">All Tasks</span>
                        </div>
                        <x-lucide-arrow-right class="w-4 h-4 text-neutral-400 group-hover:text-primary-500 transition-colors" />
                    </a>

                    <a href="{{ route('adiutor.projects.show', $task->project_id) }}" class="flex items-center justify-between p-3 bg-neutral-50 hover:bg-primary-50 rounded-xl transition-all group border border-transparent hover:border-primary-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white group-hover:bg-primary-100 rounded-xl flex items-center justify-center mr-3 transition-colors">
                                <x-lucide-folder-kanban class="w-4 h-4 text-neutral-600 group-hover:text-primary-600 transition-colors" />
                            </div>
                            <span class="font-medium text-neutral-700 group-hover:text-primary-700 transition-colors">Parent Project</span>
                        </div>
                        <x-lucide-arrow-right class="w-4 h-4 text-neutral-400 group-hover:text-primary-500 transition-colors" />
                    </a>

                    <a href="{{ route('adiutor.dashboard') }}" class="flex items-center justify-between p-3 bg-neutral-50 hover:bg-primary-50 rounded-xl transition-all group border border-transparent hover:border-primary-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white group-hover:bg-primary-100 rounded-xl flex items-center justify-center mr-3 transition-colors">
                                <x-lucide-home class="w-4 h-4 text-neutral-600 group-hover:text-primary-600 transition-colors" />
                            </div>
                            <span class="font-medium text-neutral-700 group-hover:text-primary-700 transition-colors">Dashboard</span>
                        </div>
                        <x-lucide-arrow-right class="w-4 h-4 text-neutral-400 group-hover:text-primary-500 transition-colors" />
                    </a>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>

<!-- Upload File Modal -->
<div id="uploadFileModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-neutral-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-neutral-800">Upload File</h3>
                <button type="button" onclick="closeUploadModal()" class="text-neutral-400 hover:text-neutral-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
        </div>
        
        <form action="{{ route('adiutor.tasks.upload-file', $task->taskID) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Select File</label>
                    <input type="file" name="file" required class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="text-xs text-neutral-500 mt-2">Max file size: 10MB. Accepted formats: PDF, DOC, DOCX, XLS, XLSX, PNG, JPG, ZIP</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Description (Optional)</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Brief description of this file..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <x-ui.button type="button" onclick="closeUploadModal()" variant="secondary" class="flex-1 justify-center">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary" class="flex-1 justify-center">
                        <x-lucide-upload class="w-4 h-4 mr-2" />
                        Upload
                    </x-ui.button>
                </div>
            </form>
        </div>
</div>

<!-- Budget Change Request Modal -->
<div id="budgetChangeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full" onclick="event.stopPropagation()">
            <div class="p-6 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-neutral-800">Request Budget Change</h3>
                    <button type="button" onclick="closeBudgetModal()" class="text-neutral-400 hover:text-neutral-600">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
            </div>
            
            <form action="{{ route('adiutor.tasks.request-budget-change', $task->taskID) }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4 p-4 bg-primary-50 border-l-4 border-primary-500 rounded-xl">
                    <p class="text-sm text-primary-800">
                        <strong>Current Budget:</strong> ₱{{ number_format($task->allocated_budget ?? 0, 2) }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Requested Amount *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-neutral-500">₱</span>
                        <input type="number" name="requested_budget" step="0.01" min="0" required class="w-full pl-8 pr-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0.00">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Reason for Change *</label>
                    <textarea name="reason" rows="4" required class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Explain why you need a budget adjustment..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <x-ui.button type="button" onclick="closeBudgetModal()" variant="secondary" class="flex-1 justify-center">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" variant="warning" class="flex-1 justify-center">
                        <x-lucide-send class="w-4 h-4 mr-2" />
                        Submit Request
                    </x-ui.button>
                </div>
            </form>
        </div>
</div>

@endsection

@push('scripts')
    
<script>
function openUploadModal() {
    const modal = document.getElementById('uploadFileModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
}

function closeUploadModal() {
    const modal = document.getElementById('uploadFileModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
}

function openBudgetModal() {
    const modal = document.getElementById('budgetChangeModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
}

function closeBudgetModal() {
    const modal = document.getElementById('budgetChangeModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('uploadFileModal')?.addEventListener('click', closeUploadModal);
document.getElementById('budgetChangeModal')?.addEventListener('click', closeBudgetModal);

// Update Progress Function
function updateProgress() {
    // Create and show progress modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="p-6 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-neutral-800">Update Task Progress</h3>
                    <button type="button" onclick="this.closest('.fixed').remove()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            
            <form id="progressForm" class="p-6">
                <div class="mb-4 p-4 bg-primary-50 border-l-4 border-primary-500 rounded-xl">
                    <p class="text-sm text-primary-800">
                        <strong>Current Progress:</strong> {{ $task->progress_percentage ?? 0 }}%
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Progress Percentage *</label>
                    <input type="number" name="progress_percentage" id="progress_percentage" min="0" max="100" step="1" required 
                           class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                           placeholder="Enter progress percentage (0-100)"
                           value="{{ $task->progress_percentage ?? 0 }}">
                    <div class="mt-2">
                        <div class="w-full bg-neutral-200 rounded-full h-3">
                            <div id="progress-preview" class="bg-primary-500 h-3 rounded-full transition-all duration-300" style="width: {{ $task->progress_percentage ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="this.closest('.fixed').remove()" class="flex-1 px-4 py-2 bg-neutral-200 text-neutral-700 font-medium rounded-xl hover:bg-neutral-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Update Progress
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Add progress preview functionality
    const progressInput = modal.querySelector('#progress_percentage');
    const progressPreview = modal.querySelector('#progress-preview');
    
    progressInput.addEventListener('input', function() {
        const value = Math.min(100, Math.max(0, parseInt(this.value) || 0));
        progressPreview.style.width = value + '%';
    });
    
    // Handle form submission
    modal.querySelector('#progressForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const progressValue = parseInt(progressInput.value) || 0;
        formData.append('progress_percentage', progressValue);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Updating...';
        submitButton.disabled = true;
        
        try {
            const response = await fetch('{{ route("adiutor.tasks.update-progress", $task->taskID) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update progress display in page
                const progressCard = document.querySelector('.text-2xl.font-semibold.text-primary-600');
                if (progressCard) {
                    progressCard.textContent = progressValue + '%';
                }
                
                const progressBar = document.querySelector('.bg-primary-500.h-2.rounded-full');
                if (progressBar) {
                    progressBar.style.width = progressValue + '%';
                }
                
                // Show success message
                window.toast.success('Progress updated successfully!');
                
                // Close modal
                modal.remove();
                
                // If progress is 100%, reload page to show completed status
                if (progressValue === 100) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } else {
                throw new Error(data.message || 'Failed to update progress');
            }
        } catch (error) {
            console.error('Error updating progress:', error);
            
            // Show error message
            window.toast.error('Failed to update progress. Please try again.');
        } finally {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    });
}
</script>

@endpush
@extends('adiutor.layouts.app')

<<<<<<< HEAD
@section('title', $task->taskTitle)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Enhanced Header with Gradient -->
    <div class="mb-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-neutral-500 mb-4">
            <a href="{{ route('adiutor.dashboard') }}" class="hover:text-primary-600 transition-colors flex items-center">
                <i class="fas fa-home mr-1.5"></i>
                Dashboard
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('adiutor.tasks.index') }}" class="hover:text-primary-600 transition-colors">Tasks</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-neutral-900 font-medium">{{ Str::limit($task->taskTitle, 40) }}</span>
        </nav>

        <!-- Title Section -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-start gap-3 mb-3">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <i class="fas fa-tasks text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-neutral-900 mb-2">{{ $task->taskTitle }}</h1>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium shadow-sm
                                @if($task->status === 'completed') bg-green-50 text-green-700 ring-1 ring-green-600/20
                                @elseif($task->status === 'in_progress') bg-blue-50 text-blue-700 ring-1 ring-blue-600/20
                                @elseif($task->status === 'pending') bg-amber-50 text-amber-700 ring-1 ring-amber-600/20
                                @elseif($task->status === 'pending_approval') bg-purple-50 text-purple-700 ring-1 ring-purple-600/20
                                @else bg-neutral-50 text-neutral-700 ring-1 ring-neutral-600/20 @endif">
                                <span class="w-2 h-2 rounded-full mr-2
                                    @if($task->status === 'completed') bg-green-500
                                    @elseif($task->status === 'in_progress') bg-blue-500
                                    @elseif($task->status === 'pending') bg-amber-500
                                    @elseif($task->status === 'pending_approval') bg-purple-500
                                    @else bg-neutral-500 @endif"></span>
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                            
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium
                                @if($task->priority === 'urgent') bg-red-50 text-red-700 ring-1 ring-red-600/20
                                @elseif($task->priority === 'high') bg-orange-50 text-orange-700 ring-1 ring-orange-600/20
                                @elseif($task->priority === 'medium') bg-yellow-50 text-yellow-700 ring-1 ring-yellow-600/20
                                @else bg-neutral-50 text-neutral-700 ring-1 ring-neutral-600/20 @endif">
                                <i class="fas fa-flag mr-2"></i>
                                {{ ucfirst($task->priority ?? 'Normal') }} Priority
                            </span>
                        </div>
                    </div>
                </div>
=======
@section('title', 'Project Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $assignment->title }}</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('adiutor.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('adiutor.tasks') }}" class="hover:text-accent-500 transition-colors">Projects</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">{{ Str::limit($assignment->title, 30) }}</span>
                </nav>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($assignment->assignment_status === 'active') bg-green-100 text-green-800
                    @elseif($assignment->assignment_status === 'pending') bg-amber-100 text-amber-800
                    @elseif($assignment->assignment_status === 'completed') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800 @endif">
                    <i class="fas fa-circle text-[6px] mr-2"></i>
                    {{ ucfirst($assignment->assignment_status) }}
                </span>
                <a href="{{ route('adiutor.tasks') }}" class="px-5 py-2.5 bg-neutral-600 text-white font-medium rounded-lg hover:bg-neutral-700 transition-colors shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Projects
                </a>
>>>>>>> 7c71488 (Initial commit from Princess)
            </div>
        </div>
    </div>

<<<<<<< HEAD
    <!-- Modern Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Budget Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-neutral-500 text-sm font-medium">Allocated Budget</span>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet text-green-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-neutral-900">₱{{ number_format($task->allocated_budget ?? 0, 2) }}</p>
            @if($task->actual_cost)
                <div class="mt-2 flex items-center text-xs">
                    <span class="text-neutral-500">Spent: ₱{{ number_format($task->actual_cost, 2) }}</span>
                    @php
                        $remaining = ($task->allocated_budget ?? 0) - $task->actual_cost;
                        $isOverBudget = $remaining < 0;
                    @endphp
                    <span class="ml-2 px-2 py-0.5 rounded-full {{ $isOverBudget ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                        {{ $isOverBudget ? 'Over' : 'Remaining' }}: ₱{{ number_format(abs($remaining), 2) }}
                    </span>
                </div>
            @else
                <p class="text-neutral-500 text-xs mt-1">No expenses yet</p>
            @endif
        </div>

        <!-- Deadline Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-neutral-500 text-sm font-medium">Deadline</span>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                </div>
            </div>
            @if($task->deadline)
                <p class="text-2xl font-bold text-neutral-900">{{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}</p>
                <p class="text-neutral-500 text-xs mt-1">{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</p>
            @else
                <p class="text-2xl font-bold text-neutral-900">No deadline</p>
                <p class="text-neutral-500 text-xs mt-1">Flexible timeline</p>
            @endif
        </div>

        <!-- Progress Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-neutral-500 text-sm font-medium">Progress</span>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-neutral-900">{{ $task->progress_percentage ?? 0 }}%</p>
            <div class="w-full bg-neutral-200 rounded-full h-2 mt-3">
                <div class="bg-primary-500 h-2 rounded-full transition-all duration-300" style="width: {{ $task->progress_percentage ?? 0 }}%"></div>
            </div>
        </div>

        <!-- Project Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-neutral-500 text-sm font-medium">Parent Project</span>
                <div class="w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-project-diagram text-neutral-600"></i>
                </div>
            </div>
            <p class="text-lg font-bold text-neutral-900 line-clamp-2 mb-2">{{ Str::limit($task->project_title, 35) }}</p>
            <a href="{{ route('adiutor.projects.show', $task->project_id) }}" class="inline-flex items-center text-primary-600 text-sm font-medium hover:text-primary-700 transition-colors">
                View details <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Phase Information (if applicable) -->
    @if($task->phase_id && $task->phase_name)
        <div class="bg-gradient-to-r from-primary-50 to-blue-50 rounded-2xl p-6 shadow-sm border border-primary-200 mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center">
                        <i class="fas fa-layer-group text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-600 font-medium mb-1">Project Phase</p>
                        <h3 class="text-xl font-bold text-neutral-900">{{ $task->phase_name }}</h3>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if($task->phase_budget)
                        <div class="bg-white px-4 py-2.5 rounded-lg shadow-sm border border-neutral-200">
                            <p class="text-xs text-neutral-500 mb-0.5">Phase Budget</p>
                            <p class="text-lg font-bold text-neutral-900">₱{{ number_format($task->phase_budget, 2) }}</p>
                        </div>
                    @endif
                    <div class="bg-white px-4 py-2.5 rounded-lg shadow-sm border border-neutral-200">
                        <p class="text-xs text-neutral-500 mb-0.5">Payment Status</p>
                        @if($task->phase_is_paid)
                            <span class="inline-flex items-center px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                <i class="fas fa-check-circle mr-1.5"></i>
                                Paid
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                <i class="fas fa-clock mr-1.5"></i>
                                Pending
                            </span>
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
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-neutral-50 to-white border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-align-left text-primary-600 text-sm"></i>
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
                                <i class="fas fa-file-alt text-neutral-400 text-2xl"></i>
                            </div>
                            <p class="text-neutral-500 text-sm">No description provided</p>
=======
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Project Overview -->
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                    Project Overview
                </h3>
                @if($assignment->description)
                    <p class="text-neutral-700 leading-relaxed whitespace-pre-wrap">{{ $assignment->description }}</p>
                @else
                    <p class="text-neutral-500 italic">No description provided</p>
                @endif
            </div>

            <!-- Project Details -->
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-clipboard-list text-primary-600 mr-2"></i>
                    Project Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Budget</p>
                        <p class="text-lg font-semibold text-neutral-900">
                            @if($assignment->agreed_rate)
                                ${{ number_format($assignment->agreed_rate, 2) }}
                            @else
                                ${{ number_format($assignment->budget, 2) }}
                            @endif
                            <span class="text-sm font-normal text-neutral-600">/ {{ ucfirst($assignment->budget_type) }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Priority</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($assignment->priority === 'urgent') bg-red-100 text-red-800
                            @elseif($assignment->priority === 'high') bg-orange-100 text-orange-800
                            @elseif($assignment->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($assignment->priority ?? 'Normal') }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Start Date</p>
                        <p class="text-base text-neutral-900">
                            @if($assignment->start_date)
                                {{ \Carbon\Carbon::parse($assignment->start_date)->format('M d, Y') }}
                            @else
                                <span class="text-neutral-400">Not started</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Deadline</p>
                        <p class="text-base text-neutral-900">
                            @if($assignment->deadline)
                                {{ \Carbon\Carbon::parse($assignment->deadline)->format('M d, Y') }}
                                <span class="text-sm text-neutral-500">({{ \Carbon\Carbon::parse($assignment->deadline)->diffForHumans() }})</span>
                            @else
                                <span class="text-neutral-400">No deadline set</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Assigned Date</p>
                        <p class="text-base text-neutral-900">{{ \Carbon\Carbon::parse($assignment->assigned_at)->format('M d, Y') }}</p>
                    </div>
                    @if($assignment->expected_completion)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Expected Completion</p>
                            <p class="text-base text-neutral-900">{{ \Carbon\Carbon::parse($assignment->expected_completion)->format('M d, Y') }}</p>
>>>>>>> 7c71488 (Initial commit from Princess)
                        </div>
                    @endif
                </div>
            </div>

<<<<<<< HEAD
            <!-- Task Notes -->
            @if($task->notes)
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-sticky-note text-amber-600 text-sm"></i>
                            </div>
                            Notes & Comments
                        </h3>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-neutral-700 leading-relaxed whitespace-pre-wrap">{{ $task->notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Completion Notes -->
            @if($task->completion_notes && $task->status === 'completed')
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200">
                        <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600 text-sm"></i>
                            </div>
                            Completion Notes
                        </h3>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-neutral-700 leading-relaxed whitespace-pre-wrap">{{ $task->completion_notes }}</p>
                    </div>
                </div>
            @endif

            <!-- File Attachments -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-neutral-50 to-white border-b border-neutral-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                            <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-paperclip text-primary-600 text-sm"></i>
                            </div>
                            Attachments
                            @if(isset($taskFiles) && $taskFiles->count() > 0)
                                <span class="ml-2 px-2 py-0.5 bg-primary-100 text-primary-700 text-xs font-medium rounded-full">
                                    {{ $taskFiles->count() }}
                                </span>
                            @endif
                        </h3>
                        @if($task->status !== 'completed')
                            <button onclick="openUploadModal()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-all shadow-sm hover:shadow-md">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>
                                Upload
                            </button>
                        @endif
                    </div>
                </div>
                <div class="px-6 py-5">
                    @if(isset($taskFiles) && $taskFiles->count() > 0)
                        <div class="space-y-3">
                            @foreach($taskFiles as $file)
                                <div class="group flex items-center justify-between p-4 bg-neutral-50 hover:bg-neutral-100 rounded-xl transition-all border border-neutral-200 hover:border-primary-200 hover:shadow-sm">
                                    <div class="flex items-center space-x-4 flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm border border-neutral-200 group-hover:border-primary-200 transition-colors">
                                            <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-neutral-900 truncate">{{ $file->fileName }}</p>
                                            <p class="text-sm text-neutral-500">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($file->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('adiutor.tasks.download-file', $file->documentID) }}" class="flex-shrink-0 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-all shadow-sm">
                                            <i class="fas fa-download mr-2"></i>
                                            Download
                                        </a>
                                        @if($file->uploaded_by == Auth::id())
                                            <form action="{{ route('adiutor.tasks.delete-file', $file->documentID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex-shrink-0 inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-all shadow-sm">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-folder-open text-neutral-400 text-3xl"></i>
                            </div>
                            <p class="text-neutral-500 text-sm mb-4">No files uploaded yet</p>
                            @if($task->status !== 'completed')
                                <button onclick="openUploadModal()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-all">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i>
                                    Upload Your First File
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Budget Change Request -->
            @if($task->status !== 'completed')
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-hand-holding-usd text-green-600 text-sm"></i>
                            </div>
                            Budget Adjustment
                        </h3>
                    </div>
                    <div class="px-6 py-5">
                        @if(isset($pendingBudgetRequest))
                            <div class="bg-amber-50 rounded-xl p-5 border border-amber-200">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-clock text-amber-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-amber-900 mb-2">Pending Budget Request</p>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-neutral-600">Requested Amount:</span>
                                                <span class="font-semibold text-neutral-900">₱{{ number_format($pendingBudgetRequest->requested_budget, 2) }}</span>
                                            </div>
                                            <div class="pt-2 border-t border-amber-200">
                                                <p class="text-neutral-600 mb-1">Reason:</p>
                                                <p class="text-neutral-700">{{ $pendingBudgetRequest->reason }}</p>
                                            </div>
                                            <p class="text-xs text-neutral-500 pt-2">
                                                <i class="far fa-clock mr-1"></i>
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
                                        <p class="text-2xl font-bold text-neutral-900">₱{{ number_format($task->allocated_budget ?? 0, 2) }}</p>
                                    </div>
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-wallet text-green-600 text-xl"></i>
                                    </div>
                                </div>
                                <button onclick="openBudgetModal()" class="w-full inline-flex items-center justify-center px-4 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-all shadow-sm">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Request Budget Change
                                </button>
                            </div>
                        @endif
=======
            <!-- Progress Section -->
            @if($assignment->assignment_status === 'active' || $assignment->assignment_status === 'completed')
                <div class="glass-card p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                            <i class="fas fa-chart-line text-primary-600 mr-2"></i>
                            Progress
                        </h3>
                        @if($assignment->assignment_status === 'active')
                            <button data-action="update-progress" data-assignment-id="{{ $assignment->assignment_id }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Update Progress
                            </button>
                        @endif
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-neutral-700">Overall Progress</p>
                            <p class="text-2xl font-bold text-primary-600">{{ $assignment->progress_percentage }}%</p>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-4 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-4 rounded-full transition-all duration-300 shadow-sm" 
                                 style="width: {{ $assignment->progress_percentage }}%"></div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Assignment Notes -->
            @if($assignment->assignment_notes)
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-primary-600 mr-2"></i>
                        Assignment Notes
                    </h3>
                    <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                        <p class="text-neutral-700 whitespace-pre-wrap">{{ $assignment->assignment_notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Project Notes -->
            @if($assignment->notes)
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-file-alt text-primary-600 mr-2"></i>
                        Project Notes
                    </h3>
                    <div class="p-4 bg-neutral-50 rounded-lg">
                        <p class="text-neutral-700 whitespace-pre-wrap">{{ $assignment->notes }}</p>
>>>>>>> 7c71488 (Initial commit from Princess)
                    </div>
                </div>
            @endif
        </div>

<<<<<<< HEAD
        <!-- Right Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Task Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-neutral-50 to-white border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle text-indigo-600 text-sm"></i>
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
                                <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                                <span class="font-semibold text-neutral-900">{{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}</span>
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
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="font-semibold text-neutral-900">{{ \Carbon\Carbon::parse($task->completedAt)->format('M d, Y h:i A') }}</span>
                            </div>
                            <p class="text-xs text-neutral-500 mt-1 ml-6">{{ \Carbon\Carbon::parse($task->completedAt)->diffForHumans() }}</p>
                        </div>
                    @endif

                    <!-- Created Date -->
                    @if($task->created_at)
                        <div class="pt-4 border-t border-neutral-200">
                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Created On</p>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-clock text-neutral-400 mr-2"></i>
                                <span class="text-neutral-700">{{ \Carbon\Carbon::parse($task->created_at)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Assigned To -->
                    @if($task->assignee_name)
                        <div class="pt-4 border-t border-neutral-200">
                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Assigned To</p>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-user-check text-primary-500 mr-2"></i>
                                <span class="font-semibold text-neutral-900">{{ $task->assignee_name }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions - Priority Placement -->
            @if($task->status !== 'completed')
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-neutral-900">Complete Task</h3>
                        </div>
                        <p class="text-neutral-600 text-sm mb-4">
                            Ready to mark this task as complete? This action will notify the admin and client.
                        </p>
                        <form action="{{ route('adiutor.tasks.complete', $task->taskID) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all shadow-sm">
                                <i class="fas fa-check-double mr-2"></i>
                                Mark as Complete
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Client Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-neutral-50 to-white border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        Client
                    </h3>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Name</p>
                        <p class="text-base font-semibold text-neutral-900">{{ $task->client_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Email</p>
                        <a href="mailto:{{ $task->client_email }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium break-all group">
                            <i class="fas fa-envelope mr-2 text-primary-500 group-hover:text-primary-600"></i>
                            {{ $task->client_email }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Project Info -->
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
                <div class="px-6 py-4 bg-white/50 border-b border-purple-100">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-project-diagram text-purple-600 text-sm"></i>
                        </div>
                        Project
                    </h3>
                </div>
                <div class="px-6 py-5">
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-purple-100">
                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Project Name</p>
                        <p class="text-base font-semibold text-neutral-900 mb-4">{{ $task->project_title }}</p>
                        <a href="{{ route('adiutor.projects.show', $task->project_id) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-all shadow-sm">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            View Full Project
                        </a>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-neutral-50 to-white border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                        <div class="w-8 h-8 bg-neutral-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-compass text-neutral-600 text-sm"></i>
                        </div>
                        Navigate
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('adiutor.tasks.index') }}" class="flex items-center justify-between p-3 bg-neutral-50 hover:bg-primary-50 rounded-xl transition-all group border border-transparent hover:border-primary-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white group-hover:bg-primary-100 rounded-lg flex items-center justify-center mr-3 shadow-sm transition-colors">
                                <i class="fas fa-list text-neutral-600 group-hover:text-primary-600 text-sm transition-colors"></i>
                            </div>
                            <span class="font-medium text-neutral-700 group-hover:text-primary-700 transition-colors">All Tasks</span>
                        </div>
                        <i class="fas fa-arrow-right text-neutral-400 group-hover:text-primary-500 transition-colors"></i>
                    </a>

                    <a href="{{ route('adiutor.projects.show', $task->project_id) }}" class="flex items-center justify-between p-3 bg-neutral-50 hover:bg-purple-50 rounded-xl transition-all group border border-transparent hover:border-purple-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white group-hover:bg-purple-100 rounded-lg flex items-center justify-center mr-3 shadow-sm transition-colors">
                                <i class="fas fa-project-diagram text-neutral-600 group-hover:text-purple-600 text-sm transition-colors"></i>
                            </div>
                            <span class="font-medium text-neutral-700 group-hover:text-purple-700 transition-colors">Parent Project</span>
                        </div>
                        <i class="fas fa-arrow-right text-neutral-400 group-hover:text-purple-500 transition-colors"></i>
                    </a>

                    <a href="{{ route('adiutor.dashboard') }}" class="flex items-center justify-between p-3 bg-neutral-50 hover:bg-blue-50 rounded-xl transition-all group border border-transparent hover:border-blue-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white group-hover:bg-blue-100 rounded-lg flex items-center justify-center mr-3 shadow-sm transition-colors">
                                <i class="fas fa-home text-neutral-600 group-hover:text-blue-600 text-sm transition-colors"></i>
                            </div>
                            <span class="font-medium text-neutral-700 group-hover:text-blue-700 transition-colors">Dashboard</span>
                        </div>
                        <i class="fas fa-arrow-right text-neutral-400 group-hover:text-blue-500 transition-colors"></i>
                    </a>
=======
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Client Information -->
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-user text-primary-600 mr-2"></i>
                    Client Information
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Name</p>
                        <p class="text-base font-semibold text-neutral-900">{{ $assignment->client_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Email</p>
                        <a href="mailto:{{ $assignment->client_email }}" class="text-base text-primary-600 hover:text-primary-700 flex items-center">
                            <i class="fas fa-envelope text-sm mr-2"></i>
                            {{ $assignment->client_email }}
                        </a>
                    </div>
                    @if($assignment->client_phone)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Phone</p>
                            <a href="tel:{{ $assignment->client_phone }}" class="text-base text-primary-600 hover:text-primary-700 flex items-center">
                                <i class="fas fa-phone text-sm mr-2"></i>
                                {{ $assignment->client_phone }}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="mt-6 pt-6 border-t border-neutral-200">
                    <button class="w-full px-4 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-md">
                        <i class="fas fa-comment-alt mr-2"></i>
                        Contact Client
                    </button>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-bolt text-primary-600 mr-2"></i>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    @if($assignment->assignment_status === 'pending')
                        <button data-action="accept" data-assignment-id="{{ $assignment->assignment_id }}" class="w-full px-4 py-2.5 bg-success-600 text-white font-medium rounded-lg hover:bg-success-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            Accept Project
                        </button>
                        <button data-action="decline" data-assignment-id="{{ $assignment->assignment_id }}" class="w-full px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Decline Project
                        </button>
                    @elseif($assignment->assignment_status === 'active')
                        <a href="{{ route('adiutor.documents') }}" class="block w-full px-4 py-2.5 bg-neutral-600 text-white text-center font-medium rounded-lg hover:bg-neutral-700 transition-colors">
                            <i class="fas fa-file-alt mr-2"></i>
                            View Documents
                        </a>
                        <button class="w-full px-4 py-2.5 bg-warning-600 text-white font-medium rounded-lg hover:bg-warning-700 transition-colors">
                            <i class="fas fa-flag mr-2"></i>
                            Report Issue
                        </button>
                    @endif
                </div>
            </div>

            <!-- Project Stats -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-primary-600 mr-2"></i>
                    Stats
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-600">Days Since Assignment</span>
                        <span class="text-lg font-bold text-neutral-900">{{ \Carbon\Carbon::parse($assignment->assigned_at)->diffInDays(now()) }}</span>
                    </div>
                    @if($assignment->deadline)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600">Days Until Deadline</span>
                            <span class="text-lg font-bold {{ \Carbon\Carbon::parse($assignment->deadline)->isPast() ? 'text-red-600' : 'text-neutral-900' }}">
                                {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($assignment->deadline), false) }}
                            </span>
                        </div>
                    @endif
                    @if($assignment->assignment_status === 'active')
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600">Progress</span>
                            <span class="text-lg font-bold text-primary-600">{{ $assignment->progress_percentage }}%</span>
                        </div>
                    @endif
>>>>>>> 7c71488 (Initial commit from Princess)
                </div>
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD
<!-- Upload File Modal -->
<div id="uploadFileModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-neutral-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-neutral-900">Upload File</h3>
                <button type="button" onclick="closeUploadModal()" class="text-neutral-400 hover:text-neutral-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form action="{{ route('adiutor.tasks.upload-file', $task->taskID) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Select File</label>
                    <input type="file" name="file" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="text-xs text-neutral-500 mt-2">Max file size: 10MB. Accepted formats: PDF, DOC, DOCX, XLS, XLSX, PNG, JPG, ZIP</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Description (Optional)</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Brief description of this file..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="closeUploadModal()" class="flex-1 px-4 py-2 bg-neutral-200 text-neutral-700 font-medium rounded-lg hover:bg-neutral-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-upload mr-2"></i>
                        Upload
                    </button>
                </div>
            </form>
        </div>
</div>

<!-- Budget Change Request Modal -->
<div id="budgetChangeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full" onclick="event.stopPropagation()">
            <div class="p-6 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-neutral-900">Request Budget Change</h3>
                    <button type="button" onclick="closeBudgetModal()" class="text-neutral-400 hover:text-neutral-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <form action="{{ route('adiutor.tasks.request-budget-change', $task->taskID) }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                    <p class="text-sm text-blue-800">
                        <strong>Current Budget:</strong> ₱{{ number_format($task->allocated_budget ?? 0, 2) }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Requested Amount *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-neutral-500">₱</span>
                        <input type="number" name="requested_budget" step="0.01" min="0" required class="w-full pl-8 pr-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0.00">
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Reason for Change *</label>
                    <textarea name="reason" rows="4" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Explain why you need a budget adjustment..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="closeBudgetModal()" class="flex-1 px-4 py-2 bg-neutral-200 text-neutral-700 font-medium rounded-lg hover:bg-neutral-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
</div>

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
</script>
=======
<script src="{{ asset('js/adiutor/tasks.js') }}"></script>
>>>>>>> 7c71488 (Initial commit from Princess)
@endsection

@extends('adiutor.layouts.app')

@section('title', $task->taskTitle)

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Tasks', 'route' => 'adiutor.tasks.index', 'icon' => 'clipboard-list'],
        ['label' => Str::limit($task->taskTitle, 40), 'icon' => 'clipboard-check'],
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
                                $statusConfig = match($task->status) {
                                    'completed' => ['variant' => 'success', 'icon' => 'check-circle'],
                                    'in_progress' => ['variant' => 'primary', 'icon' => 'loader'],
                                    'pending' => ['variant' => 'warning', 'icon' => 'clock'],
                                    'pending_approval' => ['variant' => 'info', 'icon' => 'clock'],
                                    'cancelled' => ['variant' => 'neutral', 'icon' => 'x-circle'],
                                    default => ['variant' => 'neutral', 'icon' => 'circle-dashed']
                                };
                            @endphp
                            <x-ui.badge variant="{{ $statusConfig['variant'] }}">
                                <x-dynamic-component :component="'lucide-' . $statusConfig['icon']" class="w-3 h-3 mr-1" />
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </x-ui.badge>
                            
                            @php
                                $priorityConfig = match($task->priority) {
                                    'urgent' => ['variant' => 'error', 'icon' => 'alert-triangle'],
                                    'high' => ['variant' => 'warning', 'icon' => 'alert-triangle'],
                                    'medium' => ['variant' => 'primary', 'icon' => 'flag'],
                                    'low' => ['variant' => 'neutral', 'icon' => 'flag'],
                                    default => ['variant' => 'neutral', 'icon' => 'flag']
                                };
                            @endphp
                            <x-ui.badge variant="{{ $priorityConfig['variant'] }}">
                                <x-dynamic-component :component="'lucide-' . $priorityConfig['icon']" class="w-3 h-3 mr-1" />
                                {{ ucfirst($task->priority ?? 'Normal') }} Priority
                            </x-ui.badge>
                        </div>
                        
                        <!-- Related Links -->
                        <x-ui.related-links :task="$task" role="adiutor" class="mt-3" />
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
        <!-- Task Allocation Card -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between mb-3">
                <span class="text-neutral-500 text-sm font-medium">Task Allocation</span>
                <div class="p-2.5 bg-success-50 rounded-xl">
                    <x-lucide-wallet class="w-5 h-5 text-success-600" />
                </div>
            </div>
            <p class="text-2xl font-semibold text-success-700">₱{{ number_format($task->allocated_budget ?? 0, 2) }}</p>
            @if($task->actual_cost)
                <div class="mt-2">
                    <div class="flex items-center text-xs">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-success-100 text-success-700">
                            <x-lucide-check-circle class="w-3 h-3 mr-1" />
                            Earned: ₱{{ number_format($task->actual_cost, 2) }}
                        </span>
                    </div>
                </div>
            @elseif($task->status === 'completed')
                <p class="text-warning-600 text-xs mt-1 flex items-center gap-1">
                    <x-lucide-clock class="w-3 h-3" />
                    Pending approval to release earnings
                </p>
            @else
                <p class="text-success-600 text-xs mt-1">Potential earnings</p>
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

    <!-- Time Tracking / Hourly Rate Notice -->
    @php
        // Check if this is an hourly rate contract
        $isHourlyContract = $task->requires_time_tracking 
            || $task->hourly_rate 
            || ($assignment && $assignment->payment_type === 'hourly_rate')
            || ($assignment && $assignment->hourly_rate);
        
        // Determine the effective hourly rate (priority: task > assignment)
        $effectiveRate = $task->hourly_rate 
            ?? ($assignment->hourly_rate ?? null) 
            ?? ($assignment->agreed_rate ?? null);
        
        // Get task max hours status
        $maxHoursStatus = $task->getMaxHoursStatus();
    @endphp
    @if($isHourlyContract)
        <div class="bg-gradient-to-r from-info-50 to-primary-50 rounded-2xl p-6 border border-info-200 mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                        <x-lucide-clock class="w-6 h-6 text-info-600" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-sm text-info-700 font-semibold">Hourly Rate Contract</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-700">
                                <x-lucide-timer class="w-3 h-3 mr-1" />
                                Time Tracked
                            </span>
                        </div>
                        <p class="text-neutral-600 text-sm">
                            This task is compensated based on tracked hours. 
                            @if($effectiveRate)
                                Your rate is <span class="font-semibold text-info-700">₱{{ number_format($effectiveRate, 2) }}/hour</span>.
                            @endif
                        </p>
                        @if($task->total_hours_tracked)
                            <p class="text-xs text-neutral-500 mt-1">
                                Hours logged: <span class="font-medium">{{ number_format($task->total_hours_tracked, 2) }} hrs</span>
                                @if($effectiveRate)
                                    • Earned: <span class="font-medium text-success-600">₱{{ number_format($task->total_hours_tracked * $effectiveRate, 2) }}</span>
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('adiutor.time-tracking.index', ['task' => $task->taskID]) }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                    <x-lucide-play class="w-4 h-4" />
                    Track Time
                    <x-lucide-external-link class="w-3.5 h-3.5 ml-1 opacity-75" />
                </a>
            </div>
        </div>
    @endif

    <!-- Task Max Hours Limit Card -->
    @if($maxHoursStatus['has_limit'])
        @php
            $bgClass = $maxHoursStatus['status'] === 'reached' ? 'from-error-50 to-error-100 border-error-200' :
                      ($maxHoursStatus['status'] === 'approaching' ? 'from-warning-50 to-warning-100 border-warning-200' :
                      'from-success-50 to-success-100 border-success-200');
            $iconBg = $maxHoursStatus['status'] === 'reached' ? 'bg-error-100' :
                     ($maxHoursStatus['status'] === 'approaching' ? 'bg-warning-100' : 'bg-success-100');
            $iconColor = $maxHoursStatus['status'] === 'reached' ? 'text-error-600' :
                        ($maxHoursStatus['status'] === 'approaching' ? 'text-warning-600' : 'text-success-600');
            $textColor = $maxHoursStatus['status'] === 'reached' ? 'text-error-700' :
                        ($maxHoursStatus['status'] === 'approaching' ? 'text-warning-700' : 'text-success-700');
            $barColor = $maxHoursStatus['status'] === 'reached' ? 'bg-error-500' :
                       ($maxHoursStatus['status'] === 'approaching' ? 'bg-warning-500' : 'bg-success-500');
            $barBg = $maxHoursStatus['status'] === 'reached' ? 'bg-error-200' :
                    ($maxHoursStatus['status'] === 'approaching' ? 'bg-warning-200' : 'bg-success-200');
        @endphp
        <div class="bg-gradient-to-r {{ $bgClass }} rounded-2xl p-6 border mb-8">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 {{ $iconBg }} rounded-xl flex items-center justify-center shadow-sm">
                        @if($maxHoursStatus['status'] === 'reached')
                            <x-lucide-alert-circle class="w-6 h-6 {{ $iconColor }}" />
                        @elseif($maxHoursStatus['status'] === 'approaching')
                            <x-lucide-alert-triangle class="w-6 h-6 {{ $iconColor }}" />
                        @else
                            <x-lucide-clock class="w-6 h-6 {{ $iconColor }}" />
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-sm {{ $textColor }} font-semibold">
                                @if($maxHoursStatus['status'] === 'reached')
                                    Maximum Hours Reached
                                @elseif($maxHoursStatus['status'] === 'approaching')
                                    Approaching Maximum Hours
                                @else
                                    Task Hour Limit
                                @endif
                            </p>
                            <x-ui.badge :variant="$maxHoursStatus['badge_variant']">
                                {{ $maxHoursStatus['percentage'] }}% used
                            </x-ui.badge>
                        </div>
                        <p class="text-neutral-600 text-sm">
                            This task has a maximum of <span class="font-semibold">{{ $maxHoursStatus['max_hours'] }} hours</span> allocated.
                        </p>
                        <p class="text-xs text-neutral-500 mt-1">
                            <span class="font-medium">{{ $maxHoursStatus['used_hours'] }} hrs</span> used 
                            • <span class="font-medium">{{ $maxHoursStatus['remaining_hours'] }} hrs</span> remaining
                        </p>
                    </div>
                </div>
                @if($maxHoursStatus['status'] === 'reached' || $maxHoursStatus['status'] === 'approaching')
                <a href="{{ route('adiutor.hour-requests.create', ['task_id' => $task->taskID]) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 {{ $maxHoursStatus['status'] === 'reached' ? 'bg-error-600 hover:bg-error-700' : 'bg-warning-600 hover:bg-warning-700' }} text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
                    <x-lucide-plus-circle class="w-4 h-4" />
                    Request More Hours
                </a>
                @endif
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="h-2 {{ $barBg }} rounded-full overflow-hidden">
                    <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ $maxHoursStatus['percentage'] }}%"></div>
                </div>
            </div>
            
            @if($maxHoursStatus['status'] === 'reached')
            <div class="mt-4 p-3 bg-white/50 rounded-lg border border-error-200">
                <p class="text-sm text-error-700">
                    <strong>Note:</strong> You've reached the maximum hours for this task. Any additional time tracked will be marked as non-billable. 
                    Please request more hours if you need to continue working on this task.
                </p>
            </div>
            @endif
        </div>
    @endif

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
                            <p class="text-xs text-neutral-500 mb-0.5">Phase Allocation</p>
                            <p class="text-lg font-semibold text-success-700">₱{{ number_format($task->phase_budget, 2) }}</p>
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

            <!-- Subtasks Section -->
            @if(isset($subtasks) && $subtasks->count() > 0)
            <x-ui.card>
                <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                            <div class="w-8 h-8 bg-secondary-100 rounded-xl flex items-center justify-center mr-3">
                                <x-lucide-list-checks class="w-4 h-4 text-secondary-600" />
                            </div>
                            Subtasks
                        </h3>
                        <span id="subtask-completed-counter" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-700">
                            {{ $subtasks->where('is_completed', true)->count() }}/{{ $subtasks->count() }} completed
                        </span>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <div class="space-y-3">
                        @foreach($subtasks as $subtask)
                        <div class="group flex items-start gap-3 p-3 rounded-lg transition-all {{ $subtask->is_completed ? 'bg-success-50 border border-success-200' : 'bg-neutral-50 border border-neutral-200 hover:border-secondary-300' }}"
                             id="subtask-{{ $subtask->id }}">
                            <div class="flex-shrink-0 mt-0.5">
                                @if($task->status !== 'completed')
                                <button type="button" 
                                        onclick="toggleSubtask({{ $subtask->id }})"
                                        class="focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-1 rounded-full">
                                    <div id="subtask-icon-{{ $subtask->id }}" class="w-5 h-5 {{ $subtask->is_completed ? 'bg-success-500' : 'border-2 border-neutral-300 hover:border-secondary-500' }} rounded-full flex items-center justify-center transition-all">
                                        @if($subtask->is_completed)
                                            <x-lucide-check class="w-3 h-3 text-white" />
                                        @endif
                                    </div>
                                </button>
                                @else
                                    <div class="w-5 h-5 {{ $subtask->is_completed ? 'bg-success-500' : 'border-2 border-neutral-300' }} rounded-full flex items-center justify-center">
                                        @if($subtask->is_completed)
                                            <x-lucide-check class="w-3 h-3 text-white" />
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p id="subtask-title-{{ $subtask->id }}" class="text-sm font-medium transition-all {{ $subtask->is_completed ? 'text-neutral-500 line-through' : 'text-neutral-800' }}">
                                    {{ $subtask->title }}
                                </p>
                                @if($subtask->description)
                                    <p class="text-xs text-neutral-500 mt-1">{{ $subtask->description }}</p>
                                @endif
                                @if($subtask->is_completed && $subtask->completed_at)
                                    <p id="subtask-completed-{{ $subtask->id }}" class="text-xs text-success-600 mt-1 flex items-center gap-1">
                                        <x-lucide-check-circle class="w-3 h-3" />
                                        Completed {{ \Carbon\Carbon::parse($subtask->completed_at)->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Progress Bar -->
                    @php
                        $completedCount = $subtasks->where('is_completed', true)->count();
                        $totalCount = $subtasks->count();
                        $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                    @endphp
                    <div class="mt-4 pt-4 border-t border-neutral-100">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-neutral-500">Subtask Progress</span>
                            <span id="subtask-progress-text" class="font-medium text-neutral-700">{{ $progressPercent }}%</span>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-2">
                            <div id="subtask-progress-bar" class="bg-secondary-500 h-2 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
            @endif

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
            <!-- Deliverables Section (Files and Links) -->
            <x-ui.card>
                <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                            <div class="w-8 h-8 bg-primary-100 rounded-xl flex items-center justify-center mr-3">
                                <x-lucide-package-check class="w-4 h-4 text-primary-600" />
                            </div>
                            Deliverables
                            @if(isset($deliverables) && $deliverables->count() > 0)
                                <span class="ml-2 px-2 py-0.5 bg-primary-100 text-primary-700 text-xs font-medium rounded-full">
                                    {{ $deliverables->count() }}
                                </span>
                            @endif
                        </h3>
                        @if($task->status !== 'completed')
                            <div class="flex items-center gap-2">
                                <x-ui.button onclick="openAddLinkModal()" variant="secondary" size="sm">
                                    <x-lucide-link class="w-4 h-4 mr-2" />
                                    Add Link
                                </x-ui.button>
                                <x-ui.button onclick="openUploadModal()" variant="primary" size="sm">
                                    <x-lucide-upload-cloud class="w-4 h-4 mr-2" />
                                    Upload File
                                </x-ui.button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="px-6 py-5">
                    @if(isset($deliverables) && $deliverables->count() > 0)
                        <div class="space-y-3">
                            @foreach($deliverables as $deliverable)
                                <div class="group flex items-center justify-between p-4 bg-neutral-50 hover:bg-neutral-100 rounded-xl transition-all border border-neutral-200 hover:border-primary-200">
                                    <div class="flex items-center space-x-4 flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-neutral-200 group-hover:border-primary-200 transition-colors">
                                            @if($deliverable->deliverable_type === 'link')
                                                <x-lucide-link class="w-6 h-6 text-blue-500" />
                                            @else
                                                <x-lucide-file-text class="w-6 h-6 text-error-500" />
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="font-medium text-neutral-800 truncate">
                                                    {{ $deliverable->fileName ?: ($deliverable->description ?: 'Untitled') }}
                                                </p>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $deliverable->deliverable_type === 'link' ? 'bg-blue-100 text-blue-700' : 'bg-primary-100 text-primary-700' }}">
                                                    {{ ucfirst($deliverable->deliverable_type ?? 'file') }}
                                                </span>
                                                @if($deliverable->is_approved)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                                        <x-lucide-check class="w-3 h-3 mr-1" />
                                                        Approved
                                                    </span>
                                                @endif
                                            </div>
                                            @if($deliverable->description && $deliverable->fileName)
                                                <p class="text-sm text-neutral-500 truncate">{{ $deliverable->description }}</p>
                                            @endif
                                            <p class="text-xs text-neutral-400 flex items-center mt-1">
                                                <x-lucide-clock class="w-3 h-3 mr-1" />
                                                {{ \Carbon\Carbon::parse($deliverable->created_at)->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($deliverable->deliverable_type === 'link' && $deliverable->link_url)
                                            <a href="{{ $deliverable->link_url }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                                <x-lucide-external-link class="w-4 h-4 mr-1" />
                                                Open Link
                                            </a>
                                        @else
                                            <x-ui.button href="{{ route('adiutor.tasks.download-file', $deliverable->documentID) }}" variant="primary" size="sm">
                                                <x-lucide-download class="w-4 h-4 mr-1" />
                                                Download
                                            </x-ui.button>
                                        @endif
                                        @if($deliverable->uploaded_by == Auth::id() && $task->status !== 'completed')
                                            <form action="{{ route('adiutor.tasks.delete-file', $deliverable->documentID) }}" method="POST" onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete Deliverable', 'Are you sure you want to delete this deliverable?')">
                                                @csrf
                                                @method('DELETE')
                                                <x-ui.button type="submit" variant="danger" size="sm">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
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
                                <x-lucide-package-open class="w-10 h-10 text-neutral-400" />
                            </div>
                            <p class="text-neutral-600 font-medium mb-2">No deliverables yet</p>
                            <p class="text-neutral-500 text-sm mb-4">Upload files or add links to submit as deliverables for this task.</p>
                            @if($task->status !== 'completed')
                                <div class="flex items-center justify-center gap-3">
                                    <x-ui.button onclick="openAddLinkModal()" variant="secondary">
                                        <x-lucide-link class="w-4 h-4 mr-2" />
                                        Add Link
                                    </x-ui.button>
                                    <x-ui.button onclick="openUploadModal()" variant="primary">
                                        <x-lucide-upload-cloud class="w-4 h-4 mr-2" />
                                        Upload File
                                    </x-ui.button>
                                </div>
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
                                        <p class="text-sm text-neutral-600">Current Task Allocation</p>
                                        <p class="text-2xl font-semibold text-success-700">₱{{ number_format($task->allocated_budget ?? 0, 2) }}</p>
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
                    <x-ui.button type="button" onclick="completeTask({{ $task->taskID }})" variant="success" class="w-full justify-center">
                        <x-lucide-check-check class="w-4 h-4 mr-2" />
                        Mark as Complete
                    </x-ui.button>
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
                <h3 class="text-xl font-semibold text-neutral-800">Upload Deliverable File</h3>
                <button type="button" onclick="closeUploadModal()" class="text-neutral-400 hover:text-neutral-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
        </div>
        
        <form id="uploadFileForm" class="p-6">
            <input type="hidden" name="is_deliverable" value="1">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Select File</label>
                <input type="file" name="file" id="uploadFileInput" required class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <p class="text-xs text-neutral-500 mt-2">Max file size: 10MB. Accepted formats: PDF, DOC, DOCX, XLS, XLSX, PNG, JPG, ZIP</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Description (Optional)</label>
                <textarea name="description" id="uploadFileDescription" rows="3" class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Brief description of this deliverable..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <x-ui.button type="button" onclick="closeUploadModal()" variant="secondary" class="flex-1 justify-center">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="flex-1 justify-center" id="uploadFileBtn">
                    <x-lucide-upload class="w-4 h-4 mr-2" />
                    Upload Deliverable
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Add Link Modal -->
<div id="addLinkModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-neutral-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-neutral-800">Add Deliverable Link</h3>
                <button type="button" onclick="closeAddLinkModal()" class="text-neutral-400 hover:text-neutral-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
        </div>
        
        <form id="addLinkForm" class="p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Title *</label>
                <input type="text" name="title" id="linkTitle" required 
                       class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                       placeholder="e.g., Figma Design, GitHub Repo, Google Doc">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Link URL *</label>
                <input type="url" name="link_url" id="linkUrl" required 
                       class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                       placeholder="https://example.com/resource">
                <p class="text-xs text-neutral-500 mt-2">Figma, GitHub, Google Drive, Notion, etc.</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Description (Optional)</label>
                <textarea name="description" id="linkDescription" rows="2"
                          class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                          placeholder="Brief description..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <x-ui.button type="button" onclick="closeAddLinkModal()" variant="secondary" class="flex-1 justify-center">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="flex-1 justify-center" id="addLinkBtn">
                    <x-lucide-link class="w-4 h-4 mr-2" />
                    Add Link
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
// Complete Task Function
async function completeTask(taskId, confirmNoDeliverables = false) {
    // First confirmation - "Are you sure you want to mark this task as completed?"
    if (!confirmNoDeliverables) {
        const confirmed = await window.Alerts.confirm({
            title: 'Complete Task',
            message: 'Are you sure you want to mark this task as completed? This will notify the admin and client.',
            confirmText: 'Yes, Complete',
            cancelText: 'Cancel',
            confirmVariant: 'success'
        });
        
        if (!confirmed) return;
    }
    
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        if (confirmNoDeliverables) {
            formData.append('confirm_no_deliverables', '1');
        }
        
        const response = await fetch(`/adiutor/tasks/${taskId}/complete`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        // Check if confirmation is required for no deliverables
        if (data.requires_confirmation) {
            const confirmNoDelivs = await window.Alerts.confirm({
                title: 'Complete Without Deliverables?',
                message: data.message,
                confirmText: 'Yes, Complete Anyway',
                cancelText: 'Cancel',
                confirmVariant: 'warning'
            });
            
            if (confirmNoDelivs) {
                // Re-submit with confirmation
                await completeTask(taskId, true);
            }
            return;
        }
        
        if (data.success) {
            window.Alerts?.success('Success', 'Task marked as completed!');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Failed to complete task');
        }
    } catch (error) {
        console.error('Error completing task:', error);
        window.Alerts?.error('Error', error.message || 'Failed to complete task. Please try again.');
    }
}

// Toggle subtask completion
async function toggleSubtask(subtaskId) {
    try {
        const response = await fetch(`/adiutor/subtasks/${subtaskId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            const subtaskEl = document.getElementById(`subtask-${subtaskId}`);
            const iconEl = document.getElementById(`subtask-icon-${subtaskId}`);
            const titleEl = document.getElementById(`subtask-title-${subtaskId}`);
            const completedEl = document.getElementById(`subtask-completed-${subtaskId}`);
            
            if (data.is_completed) {
                subtaskEl.classList.remove('bg-neutral-50', 'border-neutral-200', 'hover:border-secondary-300');
                subtaskEl.classList.add('bg-success-50', 'border-success-200');
                iconEl.classList.remove('border-2', 'border-neutral-300', 'hover:border-secondary-500');
                iconEl.classList.add('bg-success-500');
                iconEl.innerHTML = '<svg class="w-3 h-3 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
                titleEl.classList.remove('text-neutral-800');
                titleEl.classList.add('text-neutral-500', 'line-through');
                
                if (!completedEl) {
                    const completedInfo = document.createElement('p');
                    completedInfo.id = `subtask-completed-${subtaskId}`;
                    completedInfo.className = 'text-xs text-success-600 mt-1 flex items-center gap-1';
                    completedInfo.innerHTML = '<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg> Completed just now';
                    titleEl.parentNode.appendChild(completedInfo);
                }
            } else {
                subtaskEl.classList.add('bg-neutral-50', 'border-neutral-200', 'hover:border-secondary-300');
                subtaskEl.classList.remove('bg-success-50', 'border-success-200');
                iconEl.classList.add('border-2', 'border-neutral-300', 'hover:border-secondary-500');
                iconEl.classList.remove('bg-success-500');
                iconEl.innerHTML = '';
                titleEl.classList.add('text-neutral-800');
                titleEl.classList.remove('text-neutral-500', 'line-through');
                
                if (completedEl) {
                    completedEl.remove();
                }
            }
            
            // Update progress bar and counters using task_stats
            const progressBar = document.getElementById('subtask-progress-bar');
            const progressText = document.getElementById('subtask-progress-text');
            const completedCounter = document.getElementById('subtask-completed-counter');
            
            if (data.task_stats) {
                const { completed, total, percentage } = data.task_stats;
                
                // Update progress bar width
                if (progressBar) {
                    progressBar.style.width = `${percentage}%`;
                }
                
                // Update progress percentage text
                if (progressText) {
                    progressText.textContent = `${percentage}%`;
                }
                
                // Update X/Y completed counter
                if (completedCounter) {
                    completedCounter.textContent = `${completed}/${total} completed`;
                }
            }
            
            // Show success toast notification
            window.toast?.success('Subtask updated successfully');
            
            // Check if all subtasks are completed and task is not already completed
            if (data.all_subtasks_completed && data.task_status !== 'completed') {
                // Show confirmation dialog
                showCompleteTaskConfirmation();
            }
        } else {
            window.toast?.error('Failed to update subtask');
        }
    } catch (error) {
        console.error('Error toggling subtask:', error);
        window.toast?.error('An error occurred while updating subtask');
    }
}

// Show confirmation dialog for completing the task
function showCompleteTaskConfirmation() {
    window.Alerts?.confirm(
        'All Subtasks Completed!',
        'All subtasks have been marked as completed. Would you like to mark the entire task as completed?',
        async () => {
            await markTaskAsCompleted();
        },
        () => {
            // User cancelled - do nothing, task remains in progress
            window.toast?.info('Task remains in progress. You can mark it as completed later.');
        },
        {
            confirmText: 'Yes, Complete Task',
            cancelText: 'No, Keep in Progress',
            confirmVariant: 'success'
        }
    );
}

// Mark the task as completed
async function markTaskAsCompleted() {
    try {
        const taskId = {{ $task->taskID }};
        const response = await fetch(`/adiutor/tasks/${taskId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                confirm_no_deliverables: true
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.toast?.success('Task marked as completed!');
            // Reload page to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else if (data.requires_confirmation) {
            // Task has no deliverables - show warning
            window.Alerts?.warning('No Deliverables', data.message);
        } else {
            window.toast?.error(data.message || 'Failed to complete task');
        }
    } catch (error) {
        console.error('Error completing task:', error);
        window.toast?.error('An error occurred while completing the task');
    }
}

function openUploadModal() {
    const modal = document.getElementById('uploadFileModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
}

function closeUploadModal() {
    const modal = document.getElementById('uploadFileModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
    // Reset form
    document.getElementById('uploadFileForm').reset();
}

function openAddLinkModal() {
    const modal = document.getElementById('addLinkModal');
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
}

function closeAddLinkModal() {
    const modal = document.getElementById('addLinkModal');
    modal.style.display = 'none';
    modal.classList.add('hidden');
    // Reset form
    document.getElementById('addLinkForm').reset();
}

// Upload File Form Handler (AJAX)
document.getElementById('uploadFileForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('uploadFileBtn');
    const originalBtnContent = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Uploading...';
    
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('file', document.getElementById('uploadFileInput').files[0]);
        formData.append('description', document.getElementById('uploadFileDescription').value);
        formData.append('is_deliverable', '1');
        
        const response = await fetch('{{ route("adiutor.tasks.upload-file", $task->taskID) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeUploadModal();
            window.Alerts?.success('Success', data.message);
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Failed to upload file');
        }
    } catch (error) {
        console.error('Error uploading file:', error);
        window.Alerts?.error('Error', error.message || 'Failed to upload file. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnContent;
    }
});

// Add Link Form Handler (AJAX)
document.getElementById('addLinkForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('addLinkBtn');
    const originalBtnContent = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Adding...';
    
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('title', document.getElementById('linkTitle').value);
        formData.append('link_url', document.getElementById('linkUrl').value);
        formData.append('description', document.getElementById('linkDescription').value);
        
        const response = await fetch('{{ route("adiutor.tasks.add-link-deliverable", $task->taskID) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeAddLinkModal();
            window.Alerts?.success('Success', data.message);
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Failed to add link');
        }
    } catch (error) {
        console.error('Error adding link:', error);
        window.Alerts?.error('Error', error.message || 'Failed to add link. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnContent;
    }
});

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
document.getElementById('addLinkModal')?.addEventListener('click', closeAddLinkModal);
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
        
        const progressValue = parseInt(progressInput.value) || 0;
        
        await submitProgressUpdate(progressValue, false, this, modal);
    });
}

async function submitProgressUpdate(progressValue, confirmNoDeliverables, form, modal) {
    const formData = new FormData();
    formData.append('progress_percentage', progressValue);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    if (confirmNoDeliverables) {
        formData.append('confirm_no_deliverables', '1');
    }
    
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Updating...';
    submitButton.disabled = true;
    
    try {
        const response = await fetch('{{ route("adiutor.tasks.update-progress", $task->taskID) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        // Check if confirmation is required for no deliverables
        if (data.requires_confirmation) {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            
            // Show confirmation modal
            const confirmed = await window.Alerts.confirm({
                title: 'Complete Task Without Deliverables?',
                message: data.message,
                confirmText: 'Yes, Complete Task',
                cancelText: 'Cancel',
                confirmVariant: 'warning'
            });
            
            if (confirmed) {
                // Re-submit with confirmation
                await submitProgressUpdate(progressValue, true, form, modal);
            }
            return;
        }
        
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
            window.Alerts?.success('Success', 'Progress updated successfully!');
            
            // Close modal
            modal.remove();
            
            // If progress is 100%, reload page to show completed status
            if (progressValue === 100) {
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        } else {
            throw new Error(data.message || data.error || 'Failed to update progress');
        }
    } catch (error) {
        console.error('Error updating progress:', error);
        
        // Show error message
        window.Alerts?.error('Error', 'Failed to update progress. Please try again.');
        
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    }
}
</script>

@endpush
@extends('admin.layouts.app')

@section('title', 'Project Details - ' . $project->title)
@section('page-title', 'Project Details')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Projects', 'url' => route('admin.projects.index'), 'icon' => 'folder-kanban'],
        ['label' => $project->title, 'icon' => 'file-text']
    ]" class="mb-6" />
        
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center mb-2">
                @php
                    $statusConfig = [
                        'active' => ['class' => 'bg-info-100 text-info-800', 'label' => 'Active', 'icon' => 'play-circle'],
                        'in_progress' => ['class' => 'bg-primary-100 text-primary-800', 'label' => 'In Progress', 'icon' => 'loader'],
                        'review' => ['class' => 'bg-warning-100 text-warning-800', 'label' => 'Review', 'icon' => 'eye'],
                        'completed' => ['class' => 'bg-success-100 text-success-800', 'label' => 'Completed', 'icon' => 'check-circle'],
                        'cancelled' => ['class' => 'bg-error-100 text-error-800', 'label' => 'Cancelled', 'icon' => 'x-circle'],
                        'on_hold' => ['class' => 'bg-neutral-100 text-neutral-800', 'label' => 'On Hold', 'icon' => 'pause-circle'],
                    ];
                    $config = $statusConfig[$project->status] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'label' => ucfirst($project->status), 'icon' => 'circle'];
                @endphp
                
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $config['class'] }} mr-3">
                    @php $statusIcon = 'lucide-' . $config['icon']; @endphp
                    <x-dynamic-component :component="$statusIcon" class="w-3.5 h-3.5 mr-1.5" />
                    {{ $config['label'] }}
                </span>
                
                @if($project->priority)
                    @php
                        $priorityConfig = [
                            'low' => ['class' => 'bg-info-100 text-info-800', 'icon' => 'arrow-down'],
                            'medium' => ['class' => 'bg-warning-100 text-warning-800', 'icon' => 'minus'],
                            'high' => ['class' => 'bg-orange-100 text-orange-800', 'icon' => 'arrow-up'],
                            'urgent' => ['class' => 'bg-error-100 text-error-800', 'icon' => 'alert-triangle'],
                        ];
                        $pConfig = $priorityConfig[$project->priority] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'circle'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $pConfig['class'] }}">
                        @php $priorityIcon = 'lucide-' . $pConfig['icon']; @endphp
                        <x-dynamic-component :component="$priorityIcon" class="w-3.5 h-3.5 mr-1.5" />
                        {{ ucfirst($project->priority) }} Priority
                    </span>
                @endif
            </div>
            
            <h1 class="text-2xl font-semibold text-neutral-800 mb-1">{{ $project->title }}</h1>
            
            <div class="text-neutral-500 flex items-center">
                <x-lucide-calendar class="w-4 h-4 mr-2" />
                <span>Created {{ $project->created_at->format('F d, Y') }}</span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <x-ui.button variant="secondary" href="{{ route('admin.projects.index') }}">
                <x-lucide-arrow-left class="w-4 h-4" />
                Back to Projects
            </x-ui.button>
            
            @if($project->status !== 'completed')
                <x-ui.button variant="success" onclick="showCompleteModal()">
                    <x-lucide-check-circle class="w-4 h-4" />
                    Mark as Completed
                </x-ui.button>
            @endif
            
            <x-ui.button variant="primary" href="{{ route('admin.projects.edit', $project->id) }}">
                <x-lucide-pencil class="w-4 h-4" />
                Edit Project
            </x-ui.button>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Project Details -->
        <div class="lg:col-span-2">
            <!-- Project Details Card -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <x-lucide-file-text class="w-5 h-5 text-primary-500" />
                        Project Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Description</h3>
                        <div class="prose max-w-none text-neutral-800">
                            {{ $project->description }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Start Date:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Deadline:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ $project->deadline ? $project->deadline->format('M d, Y') : 'Not set' }}
                                    </div>
                                </div>
                                @if($project->completion_date)
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Completed:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ $project->completion_date->format('M d, Y') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Project Information</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Project ID:</div>
                                    <div class="flex-1 text-neutral-800 font-medium">
                                        #{{ $project->id }}
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Status:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ ucwords(str_replace('_', ' ', $project->status)) }}
                                    </div>
                                </div>
                                @if($project->priority)
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Priority:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ ucfirst($project->priority) }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>

                <!-- Budget Overview Card -->
                <x-ui.card class="mb-6">
                    <div class="px-6 py-4 border-b border-neutral-100">
                        <h2 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                            <x-lucide-wallet class="w-5 h-5 text-primary-500" />
                            Budget Overview
                        </h2>
                        @if($project->serviceRequest && ($project->serviceRequest->coupon_discount_amount > 0 || $project->serviceRequest->loyalty_discount_amount > 0))
                            <div class="mt-2 text-sm text-neutral-600">
                                <p class="flex items-center gap-2">
                                    <span class="text-neutral-500">Original Budget:</span>
                                    <span class="line-through text-neutral-400">₱{{ number_format($project->serviceRequest->getOriginalBudget(), 2) }}</span>
                                </p>
                                @if($project->serviceRequest->coupon_discount_amount > 0)
                                    <p class="flex items-center gap-2 text-success-600">
                                        <x-lucide-ticket class="w-4 h-4" />
                                        <span>Coupon Discount:</span>
                                        <span class="font-semibold">-₱{{ number_format($project->serviceRequest->coupon_discount_amount, 2) }}</span>
                                    </p>
                                @endif
                                @if($project->serviceRequest->loyalty_discount_amount > 0)
                                    <p class="flex items-center gap-2 text-primary-600">
                                        <x-lucide-award class="w-4 h-4" />
                                        <span>Loyalty Discount:</span>
                                        <span class="font-semibold">-₱{{ number_format($project->serviceRequest->loyalty_discount_amount, 2) }}</span>
                                    </p>
                                @endif
                                <p class="flex items-center gap-2 mt-1">
                                    <span class="text-neutral-700 font-medium">Final Budget (Client Pays):</span>
                                    <span class="text-primary-600 font-bold">₱{{ number_format($project->budget, 2) }}</span>
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <!-- Main Budget Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-primary-50 rounded-xl">
                                <p class="text-sm text-primary-600 font-medium mb-1">Project Budget</p>
                                <p class="text-2xl font-bold text-primary-900">₱{{ number_format($budgetOverview['project_budget'], 2) }}</p>
                                @if($project->serviceRequest && ($project->serviceRequest->coupon_discount_amount > 0 || $project->serviceRequest->loyalty_discount_amount > 0))
                                    <p class="text-xs text-neutral-500 mt-1">(After discounts)</p>
                                @endif
                            </div>
                            <div class="text-center p-4 bg-warning-50 rounded-xl">
                                <p class="text-sm text-warning-600 font-medium mb-1">Task Allocated</p>
                                <p class="text-2xl font-bold text-warning-900">₱{{ number_format($budgetOverview['total_allocated'], 2) }}</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-xl">
                                <p class="text-sm text-purple-600 font-medium mb-1">Adiutor Earnings</p>
                                <p class="text-2xl font-bold text-purple-900">₱{{ number_format($budgetOverview['adiutor_earnings']['total_approved'] ?? 0, 2) }}</p>
                                <p class="text-xs text-neutral-500 mt-1">Approved payments</p>
                            </div>
                            <div class="text-center p-4 bg-success-50 rounded-xl">
                                <p class="text-sm text-success-600 font-medium mb-1">Remaining</p>
                                <p class="text-2xl font-bold {{ ($budgetOverview['adiutor_earnings']['remaining_after_earnings'] ?? 0) < 0 ? 'text-error-900' : 'text-success-900' }}">
                                    ₱{{ number_format($budgetOverview['adiutor_earnings']['remaining_after_earnings'] ?? $budgetOverview['remaining_budget'], 2) }}
                                </p>
                                <p class="text-xs text-neutral-500 mt-1">After earnings</p>
                            </div>
                        </div>

                        <!-- Adiutor Earnings Breakdown -->
                        @if(isset($budgetOverview['adiutor_earnings']))
                        <div class="border border-neutral-200 rounded-xl p-4 mb-6">
                            <h3 class="text-sm font-semibold text-neutral-700 mb-4 flex items-center">
                                <x-lucide-wallet class="w-4 h-4 text-purple-500 mr-2" />
                                Adiutor Earnings Breakdown
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Hourly Rate Earnings -->
                                <div class="bg-blue-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-blue-700 flex items-center">
                                            <x-lucide-clock class="w-4 h-4 mr-1" />
                                            Hourly Rate
                                        </span>
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                            {{ $budgetOverview['adiutor_earnings']['hourly']['entry_count'] }} entries
                                        </span>
                                    </div>
                                    <p class="text-lg font-bold text-blue-900">
                                        ₱{{ number_format($budgetOverview['adiutor_earnings']['hourly']['approved_amount'], 2) }}
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1">
                                        {{ $budgetOverview['adiutor_earnings']['hourly']['approved_hours'] }} hours approved
                                    </p>
                                    @if($budgetOverview['adiutor_earnings']['hourly']['pending_amount'] > 0)
                                        <p class="text-xs text-warning-600 mt-1 flex items-center">
                                            <x-lucide-hourglass class="w-3 h-3 mr-1" />
                                            ₱{{ number_format($budgetOverview['adiutor_earnings']['hourly']['pending_amount'], 2) }} pending
                                        </p>
                                    @endif
                                </div>

                                <!-- Fixed Rate Earnings -->
                                <div class="bg-purple-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-purple-700 flex items-center">
                                            <x-lucide-file-text class="w-4 h-4 mr-1" />
                                            Fixed Rate
                                        </span>
                                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full">
                                            {{ $budgetOverview['adiutor_earnings']['fixed_rate']['approved_count'] }} approved
                                        </span>
                                    </div>
                                    <p class="text-lg font-bold text-purple-900">
                                        ₱{{ number_format($budgetOverview['adiutor_earnings']['fixed_rate']['approved_amount'], 2) }}
                                    </p>
                                    @if($budgetOverview['adiutor_earnings']['fixed_rate']['pending_count'] > 0)
                                        <p class="text-xs text-warning-600 mt-1 flex items-center">
                                            <x-lucide-hourglass class="w-3 h-3 mr-1" />
                                            {{ $budgetOverview['adiutor_earnings']['fixed_rate']['pending_count'] }} pending 
                                            (₱{{ number_format($budgetOverview['adiutor_earnings']['fixed_rate']['pending_amount'], 2) }})
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Projected Budget Warning -->
                            @if($budgetOverview['adiutor_earnings']['total_pending'] > 0)
                                <div class="mt-4 bg-warning-50 border border-warning-200 rounded-xl p-3">
                                    <div class="flex items-start">
                                        <x-lucide-alert-triangle class="w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0" />
                                        <div class="text-sm">
                                            <p class="font-medium text-warning-800">Pending Approvals</p>
                                            <p class="text-warning-700">
                                                ₱{{ number_format($budgetOverview['adiutor_earnings']['total_pending'], 2) }} in pending payments.
                                                Projected remaining: 
                                                <span class="font-semibold {{ $budgetOverview['adiutor_earnings']['projected_remaining'] < 0 ? 'text-error-600' : '' }}">
                                                    ₱{{ number_format($budgetOverview['adiutor_earnings']['projected_remaining'], 2) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif

                        <!-- Budget Utilization Bar -->
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-neutral-600">Earnings vs Budget</span>
                                <span class="font-semibold {{ ($budgetOverview['adiutor_earnings']['earnings_percentage'] ?? 0) > 100 ? 'text-error-600' : 'text-neutral-900' }}">
                                    {{ $budgetOverview['adiutor_earnings']['earnings_percentage'] ?? $budgetOverview['budget_utilization_percentage'] }}%
                                </span>
                            </div>
                            <div class="w-full bg-neutral-200 rounded-full h-3">
                                <div class="h-3 rounded-full {{ ($budgetOverview['adiutor_earnings']['earnings_percentage'] ?? 0) > 100 ? 'bg-error-600' : 'bg-purple-600' }}" 
                                     style="width: {{ min($budgetOverview['adiutor_earnings']['earnings_percentage'] ?? $budgetOverview['budget_utilization_percentage'], 100) }}%">
                                </div>
                            </div>
                            <p class="text-xs text-neutral-500 mt-1">
                                Shows approved adiutor earnings as percentage of total project budget
                            </p>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Tasks Section -->
                <x-ui.card class="mb-6">
                    <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                            <x-lucide-list-checks class="w-5 h-5 text-primary-500" />
                            Project Tasks
                        </h2>
                        <x-ui.button variant="primary" size="sm" href="{{ route('admin.tasks.create', ['project_id' => $project->id]) }}">
                            <x-lucide-plus class="w-4 h-4" />
                            Add Task
                        </x-ui.button>
                    </div>
                    <div class="p-6">
                        @if($project->tasks->count() > 0)
                            <div class="space-y-3">
                                @foreach($project->tasks as $task)
                                <div class="border border-neutral-200 rounded-xl p-4 hover:bg-neutral-50 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="font-semibold text-neutral-900">{{ $task->title }}</h3>
                                                @php
                                                    $taskStatusConfig = [
                                                        'pending' => ['class' => 'bg-warning-100 text-warning-800', 'label' => 'Pending'],
                                                        'in_progress' => ['class' => 'bg-primary-100 text-primary-800', 'label' => 'In Progress'],
                                                        'completed' => ['class' => 'bg-success-100 text-success-800', 'label' => 'Completed'],
                                                        'on_hold' => ['class' => 'bg-neutral-100 text-neutral-800', 'label' => 'On Hold'],
                                                    ];
                                                    $taskConfig = $taskStatusConfig[$task->status] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'label' => 'Unknown'];
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $taskConfig['class'] }}">
                                                    {{ $taskConfig['label'] }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-neutral-600 mb-2">{{ Str::limit($task->description, 100) }}</p>
                                            <div class="flex items-center space-x-4 text-xs text-neutral-500">
                                                <span class="flex items-center">
                                                    <x-lucide-user class="w-3.5 h-3.5 mr-1" />
                                                    {{ $task->assignedUser->fullName ?? 'Unassigned' }}
                                                </span>
                                                <span class="flex items-center">
                                                    <x-lucide-banknote class="w-3.5 h-3.5 mr-1" />
                                                    ₱{{ number_format($task->allocated_budget ?? 0, 2) }}
                                                </span>
                                                <span class="flex items-center">
                                                    <x-lucide-calendar class="w-3.5 h-3.5 mr-1" />
                                                    {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No deadline' }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.tasks.show', $task->taskID) }}" 
                                           class="ml-4 text-primary-600 hover:text-primary-700 text-sm font-medium inline-flex items-center">
                                            View
                                            <x-lucide-arrow-right class="w-4 h-4 ml-1" />
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="bg-neutral-100 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                    <x-lucide-clipboard-list class="w-8 h-8 text-neutral-400" />
                                </div>
                                <h3 class="text-neutral-500 text-base">No tasks created</h3>
                                <p class="text-neutral-400 text-sm mt-1">There are no tasks associated with this project yet</p>
                                <x-ui.button variant="primary" href="{{ route('admin.tasks.create', ['project_id' => $project->id]) }}" class="mt-4">
                                    <x-lucide-plus class="w-4 h-4" />
                                    Create First Task
                                </x-ui.button>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            </div>

            <!-- Right Column: Client Info, Related Service Request, Team -->
            <div class="lg:col-span-1">
                <!-- Client Information Card -->
                <x-ui.card class="mb-6">
                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-neutral-100 flex items-center gap-2">
                        <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                            <x-lucide-user class="w-4 h-4 text-primary-500" />
                        </div>
                        <h2 class="text-sm font-semibold text-neutral-800">Client Information</h2>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-3">
                        <div class="p-3 bg-neutral-50 rounded-xl">
                            <!-- Client Profile -->
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0 shadow-sm">
                                    {{ substr($project->client->fullName, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-neutral-900 text-sm truncate">{{ $project->client->fullName }}</h3>
                                    <p class="text-xs text-neutral-500 truncate">{{ $project->client->email }}</p>
                                </div>
                            </div>
                            
                            <!-- Contact Details -->
                            @if($project->client->phone)
                            <div class="py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60 mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-neutral-100 rounded flex items-center justify-center flex-shrink-0">
                                        <x-lucide-phone class="w-3 h-3 text-neutral-500" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] text-neutral-400 uppercase tracking-wide">Phone</p>
                                        <p class="text-sm text-neutral-900 truncate">{{ $project->client->phone }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- View Profile Link -->
                            <a href="{{ route('admin.clients.show', $project->client->id) }}" 
                            class="flex items-center justify-center gap-1.5 w-full py-2 text-xs font-medium text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors">
                                <span>View Client Profile</span>
                                <x-lucide-arrow-right class="w-3 h-3" />
                            </a>
                        </div>
                    </div>
                </x-ui.card>


                <!-- Related Service Request -->
                @if($project->serviceRequest)
                <x-ui.card class="mb-6">
                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-neutral-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-secondary-50 rounded-lg flex items-center justify-center">
                                <x-lucide-file-text class="w-4 h-4 text-secondary-500" />
                            </div>
                            <h2 class="text-sm font-semibold text-neutral-800">Service Request</h2>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-primary-100 text-primary-700">
                            #{{ $project->serviceRequest->id }}
                        </span>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-3">
                        <div class="p-3 bg-neutral-50 rounded-xl">
                            <!-- Request Details -->
                            <div class="space-y-2 mb-3">
                                <!-- Service Type -->
                                <div class="py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-accent-100 rounded flex items-center justify-center flex-shrink-0">
                                            <x-lucide-concierge-bell class="w-3 h-3 text-accent-600" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] text-neutral-400 uppercase tracking-wide">Service Type</p>
                                            <p class="text-sm text-neutral-900 truncate">{{ $project->serviceRequest->service_type ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Submitted Date -->
                                <div class="py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 bg-neutral-100 rounded flex items-center justify-center flex-shrink-0">
                                            <x-lucide-calendar class="w-3 h-3 text-neutral-500" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] text-neutral-400 uppercase tracking-wide">Submitted</p>
                                            <p class="text-sm text-neutral-900">{{ $project->serviceRequest->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- View Details Link -->
                            <a href="{{ route('admin.requests.show', $project->serviceRequest->id) }}" 
                            class="flex items-center justify-center gap-1.5 w-full py-2 text-xs font-medium text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors">
                                <span>View Request Details</span>
                                <x-lucide-arrow-right class="w-3 h-3" />
                            </a>
                        </div>
                    </div>
                </x-ui.card>
                @endif

                <!-- Assigned Team -->
                <x-ui.card class="mb-6">
                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-neutral-100 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-primary-50 rounded-lg flex items-center justify-center">
                                <x-lucide-users class="w-4 h-4 text-primary-500" />
                            </div>
                            <h2 class="text-sm font-semibold text-neutral-800">Assigned Team</h2>
                            @if($project->adiutors->count() > 0)
                                <span class="bg-neutral-100 text-neutral-600 text-xs font-medium px-1.5 py-0.5 rounded-full">
                                    {{ $project->adiutors->count() }}
                                </span>
                            @endif
                        </div>
                        <button onclick="showAssignModal()"
                                type="button"
                                class="inline-flex items-center justify-center w-7 h-7 bg-primary-500 hover:bg-primary-600 text-white rounded-lg text-xs transition-colors"
                                title="Assign Adiutor">
                            <x-lucide-plus class="w-4 h-4" />
                        </button>
                    </div>
                    
                    <!-- Team List -->
                    <div class="p-3">
                        @if($project->adiutors->count() > 0)
                            <div class="space-y-2">
                                @foreach($project->adiutors as $adiutor)
                                <div class="group p-3 bg-neutral-50 hover:bg-neutral-100/80 rounded-xl transition-colors">
                                    <!-- Member Info Row -->
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-9 h-9 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0 shadow-sm">
                                            {{ substr($adiutor->fullName, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-neutral-900 text-sm truncate">{{ $adiutor->fullName }}</p>
                                            <p class="text-xs text-neutral-500 truncate">{{ $adiutor->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Payment Details -->
                                    <div class="space-y-2">
                                        @if($adiutor->pivot->payment_type === 'fixed_rate')
                                            <!-- Fixed Rate Info -->
                                            <div class="flex items-center justify-between py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-700">
                                                        <x-lucide-file-text class="w-3 h-3 mr-1" />
                                                        Fixed
                                                    </span>
                                                    <span class="text-sm font-semibold text-neutral-900">₱{{ number_format($adiutor->pivot->agreed_rate, 2) }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Approval Status -->
                                            <div class="flex items-center justify-between py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                                @if($adiutor->pivot->fixed_rate_paid)
                                                    <span class="inline-flex items-center text-xs font-medium text-success-700">
                                                        <x-lucide-check-circle class="w-3.5 h-3.5 mr-1.5 text-success-500" />
                                                        Payment Complete
                                                    </span>
                                                @elseif($adiutor->pivot->fixed_rate_approved)
                                                    <span class="inline-flex items-center text-xs font-medium text-success-700">
                                                        <x-lucide-check class="w-3.5 h-3.5 mr-1.5" />
                                                        Approved
                                                    </span>
                                                    <form action="{{ route('admin.projects.assignments.revoke-fixed-rate', [$project->id, $adiutor->pivot->id]) }}" 
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                onclick="return confirm('Revoke approval? This will prevent payout.');"
                                                                class="text-xs text-warning-600 hover:text-warning-700 font-medium">
                                                            Revoke
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-neutral-500">Pending Approval</span>
                                                    <form action="{{ route('admin.projects.assignments.approve-fixed-rate', [$project->id, $adiutor->pivot->id]) }}" 
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="inline-flex items-center px-2.5 py-1 bg-success-500 hover:bg-success-600 text-white rounded text-xs font-medium transition-colors">
                                                            <x-lucide-check class="w-3 h-3 mr-1" />
                                                            Approve
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @else
                                            <!-- Hourly Rate Info -->
                                            <div class="py-2 px-2.5 bg-white rounded-lg border border-neutral-200/60">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                                        <x-lucide-clock class="w-3 h-3 mr-1" />
                                                        Hourly
                                                    </span>
                                                    <span class="text-sm font-semibold text-neutral-900">₱{{ number_format($adiutor->pivot->hourly_rate ?? $adiutor->pivot->agreed_rate ?? $adiutor->hourlyRate ?? 0, 2) }}<span class="text-xs font-normal text-neutral-500">/hr</span></span>
                                                </div>
                                                
                                                <!-- Hours Progress -->
                                                @if($adiutor->pivot->max_hours)
                                                    @php
                                                        $maxHours = (float) $adiutor->pivot->max_hours;
                                                        $loggedHours = (float) ($adiutor->pivot->total_billable_hours ?? 0);
                                                        $remainingHours = max(0, $maxHours - $loggedHours);
                                                        $percentage = $maxHours > 0 ? min(100, round(($loggedHours / $maxHours) * 100)) : 0;
                                                        
                                                        $statusColor = 'success';
                                                        if ($percentage >= 100) {
                                                            $statusColor = 'error';
                                                        } elseif ($percentage >= 80) {
                                                            $statusColor = 'warning';
                                                        }
                                                    @endphp
                                                    <div class="pt-2 border-t border-neutral-100">
                                                        <div class="flex items-center justify-between text-xs mb-1.5">
                                                            <span class="text-neutral-600">Hours Used</span>
                                                            <span class="font-medium text-{{ $statusColor }}-600">
                                                                {{ number_format($loggedHours, 1) }} / {{ number_format($maxHours, 1) }}
                                                            </span>
                                                        </div>
                                                        <div class="w-full bg-neutral-200 rounded-full h-1.5 overflow-hidden">
                                                            <div class="h-full rounded-full bg-{{ $statusColor }}-500 transition-all duration-300" 
                                                                style="width: {{ $percentage }}%"></div>
                                                        </div>
                                                        <div class="flex items-center justify-between mt-1.5">
                                                            @if($remainingHours > 0)
                                                                <span class="text-[10px] text-neutral-500">{{ number_format($remainingHours, 1) }} hrs left</span>
                                                            @else
                                                                <span class="text-[10px] text-error-600 font-medium flex items-center">
                                                                    <x-lucide-ban class="w-3 h-3 mr-0.5" />
                                                                    Max reached
                                                                </span>
                                                            @endif
                                                            <span class="text-[10px] font-medium text-{{ $statusColor }}-600">{{ $percentage }}%</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="pt-2 border-t border-neutral-100">
                                                        <span class="inline-flex items-center text-[10px] text-neutral-400">
                                                            <x-lucide-infinity class="w-3 h-3 mr-1" />
                                                            Unlimited hours
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <div class="mt-3 pt-2 border-t border-neutral-200/60">
                                        <form action="{{ route('admin.projects.remove-adiutor', [$project->id, $adiutor->id]) }}" 
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to remove this adiutor from the project?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium text-neutral-500 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors">
                                                <x-lucide-user-minus class="w-3 h-3" />
                                                Remove from Project
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-8 px-4">
                                <div class="w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <x-lucide-user-plus class="w-6 h-6 text-neutral-400" />
                                </div>
                                <p class="text-sm font-medium text-neutral-600 mb-1">No team members</p>
                                <p class="text-xs text-neutral-400 mb-4">Assign adiutors to this project</p>
                                <button onclick="showAssignModal()"
                                        type="button"
                                        class="inline-flex items-center px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg text-xs font-medium transition-colors">
                                    <x-lucide-plus class="w-3.5 h-3.5 mr-1.5" />
                                    Assign Adiutor
                                </button>
                            </div>
                        @endif
                    </div>
                </x-ui.card>

    <!-- Complete Project Modal -->
    <div id="completeModal" class="modal-overlay fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div onclick="hideCompleteModal()" class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity z-40"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="modal-content inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
                
                <form action="{{ route('admin.projects.complete', $project->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-neutral-800">Complete Project</h5>
                        <button type="button" onclick="hideCompleteModal()" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                            <x-lucide-x class="w-5 h-5" />
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-success-100 flex items-center justify-center mr-3">
                                <x-lucide-check-circle class="w-6 h-6 text-success-600" />
                            </div>
                            <div>
                                <p class="text-sm text-neutral-600 mb-3">
                                    Are you sure you want to mark this project as completed? This will notify the client.
                                </p>
                            </div>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Completion Notes (Optional)</label>
                            <textarea name="completion_notes" 
                                      rows="3" 
                                      class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                      placeholder="Add any final notes about the project completion..."></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3">
                        <x-ui.button type="button" variant="secondary" onclick="hideCompleteModal()">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="success">
                            <x-lucide-check-circle class="w-4 h-4" />
                            Complete Project
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Adiutor Modal -->
    <div id="assignModal" class="modal-overlay fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div onclick="hideAssignModal()" class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity z-40"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="modal-content inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full relative z-50">
                
                <form action="{{ route('admin.projects.assign-adiutor', $project->id) }}" method="POST" id="assignAdiutorForm">
                    @csrf
                    <input type="hidden" name="adiutor_id" id="adiutor_id" required>
                    
                    <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-neutral-800">Assign Adiutor to Project</h5>
                        <button type="button" onclick="hideAssignModal()" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                            <x-lucide-x class="w-5 h-5" />
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-700 mb-4">Select Adiutor <span class="text-error-500">*</span></h3>
                            
                            <!-- Adiutors Table -->
                            <div class="overflow-x-auto border border-neutral-200 rounded-lg">
                                <table class="min-w-full divide-y divide-neutral-200">
                                    <thead class="bg-neutral-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Select</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Adiutor Name</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Calendar</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Skills</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Workload</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-neutral-200">
                                        @forelse($availableAdiutors as $adiutor)
                                        <tr class="hover:bg-neutral-50 cursor-pointer adiutor-row" 
                                            data-adiutor-id="{{ $adiutor['id'] }}"
                                            data-adiutor-name="{{ $adiutor['fullName'] }}"
                                            onclick="selectAdiutor({{ $adiutor['id'] }}, '{{ $adiutor['fullName'] }}', {{ $adiutor['rating'] ?? 0 }})">
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                <input type="radio" 
                                                       name="adiutor_radio" 
                                                       value="{{ $adiutor['id'] }}"
                                                       class="w-4 h-4 text-primary-600 focus:ring-primary-500 border-neutral-300">
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                                        {{ substr($adiutor['fullName'], 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-neutral-900">{{ $adiutor['fullName'] }}</div>
                                                        <div class="text-xs text-neutral-500 flex items-center gap-0.5">
                                                            @for($i = 0; $i < 5; $i++)
                                                                @if($i < floor($adiutor['rating']))
                                                                    <x-lucide-star class="w-3 h-3 text-yellow-400 fill-yellow-400" />
                                                                @elseif($i < $adiutor['rating'])
                                                                    <x-lucide-star-half class="w-3 h-3 text-yellow-400 fill-yellow-400" />
                                                                @else
                                                                    <x-lucide-star class="w-3 h-3 text-neutral-300" />
                                                                @endif
                                                            @endfor
                                                            <span class="ml-1">{{ number_format($adiutor['rating'], 1) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                @if($adiutor['calendar_connected'])
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                                        <x-lucide-check-circle class="w-3 h-3 mr-1" />
                                                        Connected
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                                        <x-lucide-x-circle class="w-3 h-3 mr-1" />
                                                        Not Connected
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    @if($adiutor['skills'] && $adiutor['skills']->count() > 0)
                                                        @foreach($adiutor['skills']->take(3) as $skill)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">
                                                                {{ $skill->name }}
                                                            </span>
                                                        @endforeach
                                                        @if($adiutor['skills']->count() > 3)
                                                            @php
                                                                $remainingSkills = $adiutor['skills']->slice(3)->pluck('name')->implode(', ');
                                                            @endphp
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-600 cursor-help relative group"
                                                                  title="{{ $remainingSkills }}">
                                                                +{{ $adiutor['skills']->count() - 3 }} more
                                                                <!-- Tooltip -->
                                                                <span class="invisible group-hover:visible absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-neutral-800 text-white text-xs rounded-lg shadow-lg whitespace-nowrap z-50 max-w-xs">
                                                                    {{ $remainingSkills }}
                                                                    <span class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1 border-4 border-transparent border-t-neutral-800"></span>
                                                                </span>
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-xs text-neutral-400 italic">No skills listed</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $projectCount = $adiutor['active_projects_count'];
                                                    if ($projectCount == 0) {
                                                        $workloadClass = 'bg-success-100 text-success-800';
                                                    } elseif ($projectCount <= 3) {
                                                        $workloadClass = 'bg-warning-100 text-warning-800';
                                                    } else {
                                                        $workloadClass = 'bg-error-100 text-error-800';
                                                    }
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $workloadClass }}">
                                                    <x-lucide-circle class="w-2 h-2 mr-1.5 fill-current" />
                                                    {{ $projectCount }} {{ Str::plural('project', $projectCount) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                <button type="button" 
                                                        onclick="event.stopPropagation(); viewAdiutorSchedule({{ $adiutor['id'] }}, '{{ addslashes($adiutor['fullName']) }}')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-primary-300 rounded-lg text-xs font-medium text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                                    <x-lucide-calendar class="w-3.5 h-3.5 mr-1.5" />
                                                    View Schedule
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-neutral-500">
                                                <x-lucide-users class="w-8 h-8 mx-auto mb-2" />
                                                <p>No adiutors available</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div id="selected_adiutor_info" class="mt-4 hidden">
                                <div class="bg-primary-50 border border-primary-200 rounded-xl p-4">
                                    <p class="text-sm text-primary-800 flex items-center">
                                        <x-lucide-check-circle class="w-4 h-4 mr-2" />
                                        <strong>Selected:</strong> <span id="selected_name" class="ml-1"></span>
                                        <span id="selected_rating" class="ml-2"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Type Selection (Phase 1) -->
                        <div class="bg-neutral-50 border border-neutral-200 rounded-xl p-4">
                            <h3 class="text-sm font-semibold text-neutral-800 mb-3 flex items-center">
                                <x-lucide-banknote class="w-4 h-4 mr-2 text-primary-600" />
                                Payment Method <span class="text-error-500 ml-1">*</span>
                            </h3>
                            <p class="text-xs text-neutral-500 mb-4">Choose how this adiutor will be paid for this project. Each adiutor can only use ONE payment method.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Fixed Rate Option -->
                                <label class="payment-type-option relative flex cursor-pointer rounded-lg border border-neutral-300 bg-white p-4 shadow-sm hover:border-primary-400 focus:outline-none transition-all"
                                       id="payment_type_fixed_label">
                                    <input type="radio" name="payment_type" value="fixed_rate" 
                                           class="sr-only" id="payment_type_fixed"
                                           onchange="togglePaymentType('fixed_rate')">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-neutral-900">
                                                Fixed Rate
                                            </span>
                                            <span class="mt-1 flex items-center text-xs text-neutral-500">
                                                One-time payment when work completes
                                            </span>
                                            <span class="mt-2 text-xs text-neutral-400">
                                                No time tracking required. Tasks are for organization only.
                                            </span>
                                        </span>
                                    </span>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent payment-type-border" aria-hidden="true"></span>
                                </label>
                                
                                <!-- Hourly Rate Option -->
                                <label class="payment-type-option relative flex cursor-pointer rounded-lg border border-neutral-300 bg-white p-4 shadow-sm hover:border-primary-400 focus:outline-none transition-all"
                                       id="payment_type_hourly_label">
                                    <input type="radio" name="payment_type" value="hourly_rate" 
                                           class="sr-only" id="payment_type_hourly" checked
                                           onchange="togglePaymentType('hourly_rate')">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-neutral-900">
                                                Hourly Rate
                                            </span>
                                            <span class="mt-1 flex items-center text-xs text-neutral-500">
                                                Multiple payments based on time logged
                                            </span>
                                            <span class="mt-2 text-xs text-neutral-400">
                                                Time tracking required. Billed per task.
                                            </span>
                                        </span>
                                    </span>
                                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-primary-500 payment-type-border" aria-hidden="true"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Fixed Rate Fields (shown when Fixed Rate is selected) -->
                        <div id="fixed_rate_container" class="hidden">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Agreed Fixed Rate (₱) <span class="text-error-500">*</span>
                            </label>
                            <input type="number" name="agreed_rate" id="agreed_rate_input" step="0.01" min="0"
                                   class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                   placeholder="e.g., 50000.00">
                            <p class="text-xs text-neutral-500 mt-1 flex items-center">
                                <x-lucide-info class="w-3 h-3 mr-1 flex-shrink-0" />
                                Total payment for this adiutor's complete work on this project. Paid when admin approves completion.
                            </p>
                        </div>
                        
                        <!-- Hourly Rate Fields (shown when Hourly Rate is selected) -->
                        <div id="hourly_rate_container">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Hourly Rate (₱)
                                </label>
                                <input type="number" name="hourly_rate" id="hourly_rate_input" step="0.01" min="0"
                                       class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                       placeholder="Leave empty to use adiutor's standard rate">
                                <p class="text-xs text-neutral-500 mt-1">
                                    <span id="standard_rate_display" class="font-medium text-primary-600"></span>
                                    Override the adiutor's standard rate for this project
                                </p>
                                <div id="hourly_rate_warning" class="hidden mt-2 p-2 bg-warning-50 border border-warning-200 rounded-xl">
                                    <p class="text-xs text-warning-700 flex items-start">
                                        <x-lucide-alert-triangle class="w-3.5 h-3.5 mr-2 mt-0.5 flex-shrink-0" />
                                        <span>Warning: The overridden hourly rate is below the adiutor's standard rate.</span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Max Hours Limit (Phase 3) -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-neutral-700 mb-2">
                                    Maximum Billable Hours <span class="text-neutral-400 text-xs">(Optional)</span>
                                </label>
                                <input type="number" name="max_hours" id="max_hours_input" step="0.5" min="0.5"
                                       class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                       placeholder="No limit">
                                <p class="text-xs text-neutral-500 mt-1">
                                    Set a cap on billable hours. Leave empty for unlimited.
                                </p>
                                <div id="max_hours_estimate" class="hidden mt-2 p-2 bg-primary-50 border border-primary-200 rounded-xl">
                                    <p class="text-xs text-primary-700 flex items-start">
                                        <x-lucide-calculator class="w-3.5 h-3.5 mr-2 mt-0.5 flex-shrink-0" />
                                        <span>Estimated max cost: <strong id="max_hours_cost">₱0.00</strong></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Expected Completion Date
                            </label>
                            <input type="date" name="expected_completion"
                                   class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea name="notes" rows="3"
                                      class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-colors"
                                      placeholder="Additional notes about this assignment..."></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3">
                        <x-ui.button type="button" variant="secondary" onclick="hideAssignModal()">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            <x-lucide-user-plus class="w-4 h-4" />
                            Assign Adiutor
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Schedule View Modal -->
<div id="scheduleModal" class="modal-overlay fixed inset-0 bg-neutral-900 bg-opacity-50 hidden flex items-center justify-center z-50" onclick="hideScheduleModal()">
    <div class="modal-content bg-white rounded-2xl shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white border-b border-neutral-100 px-6 py-4 flex items-center justify-between z-10">
            <div>
                <h2 class="text-xl font-bold text-neutral-900 flex items-center gap-2">
                    <x-lucide-calendar class="w-5 h-5 text-primary-600" />
                    Schedule: <span id="schedule_adiutor_name" class="text-primary-600"></span>
                </h2>
                <p class="text-sm text-neutral-500 mt-1">
                    Week: <span id="schedule_week_range"></span>
                </p>
            </div>
            <button onclick="hideScheduleModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <x-lucide-x class="w-6 h-6" />
            </button>
        </div>

        <div class="p-6">
            <!-- Week Navigation -->
            <div class="flex items-center justify-between mb-6">
                <button onclick="navigateWeek('prev')" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">
                    <x-lucide-chevron-left class="w-4 h-4 mr-2" />
                    Previous Week
                </button>
                <button onclick="navigateWeek('today')" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-colors">
                    <x-lucide-calendar class="w-4 h-4 mr-2" />
                    Today
                </button>
                <button onclick="navigateWeek('next')" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-colors">
                    Next Week
                    <x-lucide-chevron-right class="w-4 h-4 ml-2" />
                </button>
            </div>

            <!-- Calendar Grid -->
            <div class="overflow-x-auto border border-neutral-200 rounded-xl">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider w-20">Time</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_0_header">Mon</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_1_header">Tue</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_2_header">Wed</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_3_header">Thu</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider" id="day_4_header">Fri</th>
                        </tr>
                    </thead>
                    <tbody id="schedule_calendar_body" class="bg-white divide-y divide-neutral-200">
                        <!-- Time slots will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="mt-6 bg-neutral-50 border border-neutral-200 rounded-xl p-4">
                <div class="flex items-center justify-center gap-6 text-sm">
                    <div class="flex items-center">
                        <span class="w-4 h-4 bg-primary-100 border border-primary-300 rounded mr-2"></span>
                        <span class="text-neutral-600">CMS Task</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-4 h-4 bg-purple-100 border border-purple-300 rounded mr-2"></span>
                        <span class="text-neutral-600">Calendar Event</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky bottom-0 bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3">
            <x-ui.button variant="secondary" onclick="hideScheduleModal()">
                Close
            </x-ui.button>
        </div>
    </div>
</div>

<style>
/* Modal CSS */
.modal-overlay {
    transition: opacity 0.3s ease-in-out;
}

.modal-overlay.hidden {
    opacity: 0;
    visibility: hidden;
}

.modal-overlay:not(.hidden) {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    transform: translateY(0) scale(1);
    opacity: 1;
}

.modal-overlay.hidden .modal-content {
    transform: translateY(4px) scale(0.95);
    opacity: 0;
}
</style>

<script>
// Global variable for standard rate validation
let adiutorStandardRate = 0;

// Modal JavaScript Functions
function showCompleteModal() {
    const modal = document.getElementById('completeModal');
    modal.classList.remove('hidden');
    // Add smooth animation
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
}

function hideCompleteModal() {
    const modal = document.getElementById('completeModal');
    modal.classList.add('hidden');
}

function showAssignModal() {
    const modal = document.getElementById('assignModal');
    modal.classList.remove('hidden');
    
    // Auto-fill Expected Completion Date from project deadline
    @if($project->deadline)
        const expectedCompletionInput = document.querySelector('input[name="expected_completion"]');
        if (expectedCompletionInput) {
            expectedCompletionInput.value = '{{ $project->deadline->format('Y-m-d') }}';
        }
    @endif
    
    // Add smooth animation
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
}

function hideAssignModal() {
    const modal = document.getElementById('assignModal');
    modal.classList.add('hidden');
    // Reset form
    document.getElementById('assignAdiutorForm').reset();
    document.getElementById('adiutor_id').value = '';
    document.getElementById('selected_adiutor_info').classList.add('hidden');
    document.getElementById('hourly_rate_input').value = '';
    document.getElementById('standard_rate_display').textContent = '';
    
    // Uncheck all radio buttons
    document.querySelectorAll('input[name="adiutor_radio"]').forEach(radio => {
        radio.checked = false;
    });
    
    // Remove selected styling from rows
    document.querySelectorAll('.adiutor-row').forEach(row => {
        row.classList.remove('bg-primary-50', 'border-l-4', 'border-primary-500');
    });
    
    // Reset payment type to hourly (default)
    togglePaymentType('hourly_rate');
    document.getElementById('payment_type_hourly').checked = true;
}

// Toggle payment type fields
function togglePaymentType(type) {
    const fixedRateContainer = document.getElementById('fixed_rate_container');
    const hourlyRateContainer = document.getElementById('hourly_rate_container');
    const fixedLabel = document.getElementById('payment_type_fixed_label');
    const hourlyLabel = document.getElementById('payment_type_hourly_label');
    
    if (type === 'fixed_rate') {
        // Show fixed rate, hide hourly rate
        fixedRateContainer.classList.remove('hidden');
        hourlyRateContainer.classList.add('hidden');
        
        // Update border styling
        fixedLabel.querySelector('.payment-type-border').classList.add('border-primary-500');
        fixedLabel.querySelector('.payment-type-border').classList.remove('border-transparent');
        hourlyLabel.querySelector('.payment-type-border').classList.remove('border-primary-500');
        hourlyLabel.querySelector('.payment-type-border').classList.add('border-transparent');
        
        // Make agreed_rate required
        document.getElementById('agreed_rate_input').setAttribute('required', 'required');
        document.getElementById('hourly_rate_input').removeAttribute('required');
    } else {
        // Show hourly rate, hide fixed rate
        fixedRateContainer.classList.add('hidden');
        hourlyRateContainer.classList.remove('hidden');
        
        // Update border styling
        hourlyLabel.querySelector('.payment-type-border').classList.add('border-primary-500');
        hourlyLabel.querySelector('.payment-type-border').classList.remove('border-transparent');
        fixedLabel.querySelector('.payment-type-border').classList.remove('border-primary-500');
        fixedLabel.querySelector('.payment-type-border').classList.add('border-transparent');
        
        // Remove required from agreed_rate
        document.getElementById('agreed_rate_input').removeAttribute('required');
    }
    
    // Reset max hours
    document.getElementById('max_hours_input').value = '';
    updateMaxHoursEstimate();
}

// Update max hours cost estimate
function updateMaxHoursEstimate() {
    const maxHoursInput = document.getElementById('max_hours_input');
    const hourlyRateInput = document.getElementById('hourly_rate_input');
    const estimateDiv = document.getElementById('max_hours_estimate');
    const costSpan = document.getElementById('max_hours_cost');
    
    const maxHours = parseFloat(maxHoursInput.value) || 0;
    const hourlyRate = parseFloat(hourlyRateInput.value) || 0;
    
    if (maxHours > 0 && hourlyRate > 0) {
        const maxCost = maxHours * hourlyRate;
        costSpan.textContent = '₱' + maxCost.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        estimateDiv.classList.remove('hidden');
    } else {
        estimateDiv.classList.add('hidden');
    }
}

// Add event listeners for max hours calculation
document.getElementById('max_hours_input').addEventListener('input', updateMaxHoursEstimate);
document.getElementById('hourly_rate_input').addEventListener('input', updateMaxHoursEstimate);

// Select adiutor from table
function selectAdiutor(adiutorId, adiutorName, rating) {
    // Update hidden input
    document.getElementById('adiutor_id').value = adiutorId;
    
    // Update selected info display
    document.getElementById('selected_name').textContent = adiutorName;
    
    // Build rating stars
    let ratingHtml = '';
    for (let i = 0; i < 5; i++) {
        if (i < Math.floor(rating)) {
            ratingHtml += '<i class="fas fa-star text-yellow-400"></i>';
        } else if (i < rating) {
            ratingHtml += '<i class="fas fa-star-half-alt text-yellow-400"></i>';
        } else {
            ratingHtml += '<i class="far fa-star text-neutral-300"></i>';
        }
    }
    ratingHtml += ` <span class="ml-1">${rating.toFixed(1)}</span>`;
    document.getElementById('selected_rating').innerHTML = ratingHtml;
    
    // Show selected info
    document.getElementById('selected_adiutor_info').classList.remove('hidden');
    
    // Remove previous selection styling
    document.querySelectorAll('.adiutor-row').forEach(row => {
        row.classList.remove('bg-primary-50', 'border-l-4', 'border-primary-500');
    });
    
    // Add selection styling to current row
    const selectedRow = document.querySelector(`.adiutor-row[data-adiutor-id="${adiutorId}"]`);
    if (selectedRow) {
        selectedRow.classList.add('bg-primary-50', 'border-l-4', 'border-primary-500');
    }
    
    // Check the radio button
    const radioButton = document.querySelector(`input[name="adiutor_radio"][value="${adiutorId}"]`);
    if (radioButton) {
        radioButton.checked = true;
    }
    
    // Fetch adiutor's standard rate
    fetch(`/api/adiutor/${adiutorId}/rate`)
        .then(response => response.json())
        .then(data => {
            const standardRateDisplay = document.getElementById('standard_rate_display');
            const hourlyRateInput = document.getElementById('hourly_rate_input');
            
            if (data.rate && data.rate > 0) {
                adiutorStandardRate = parseFloat(data.rate);
                standardRateDisplay.textContent = `Standard rate: ₱${parseFloat(data.rate).toFixed(2)}/hour. `;
                
                // Auto-fill if field is empty
                if (!hourlyRateInput.value) {
                    hourlyRateInput.value = data.rate;
                }
            } else {
                adiutorStandardRate = 0;
                standardRateDisplay.textContent = 'No standard rate set. ';
            }
        })
        .catch(error => {
            console.error('Error fetching rate:', error);
            adiutorStandardRate = 0;
            document.getElementById('standard_rate_display').textContent = '';
        });
}

// Old dropdown logic removed - no longer needed

// Schedule View Modal
let currentAdiutorId = null;
let currentWeekStart = null;

// View adiutor's schedule
function viewAdiutorSchedule(adiutorId, adiutorName) {
    currentAdiutorId = adiutorId;
    currentWeekStart = getStartOfWeek(new Date());
    
    // Set adiutor name
    document.getElementById('schedule_adiutor_name').textContent = adiutorName;
    
    // Show modal
    const modal = document.getElementById('scheduleModal');
    modal.classList.remove('hidden');
    
    // Load schedule
    loadSchedule();
}

function hideScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    modal.classList.add('hidden');
    currentAdiutorId = null;
    currentWeekStart = null;
}

function navigateWeek(direction) {
    if (direction === 'today') {
        currentWeekStart = getStartOfWeek(new Date());
    } else if (direction === 'prev') {
        currentWeekStart.setDate(currentWeekStart.getDate() - 7);
    } else if (direction === 'next') {
        currentWeekStart.setDate(currentWeekStart.getDate() + 7);
    }
    
    loadSchedule();
}

function getStartOfWeek(date) {
    const d = new Date(date);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Adjust when day is Sunday
    return new Date(d.setDate(diff));
}

function formatDate(date) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return `${months[date.getMonth()]} ${date.getDate()}`;
}

function loadSchedule() {
    // Update week range display
    const weekEnd = new Date(currentWeekStart);
    weekEnd.setDate(weekEnd.getDate() + 4); // Friday
    document.getElementById('schedule_week_range').textContent = 
        `${formatDate(currentWeekStart)} - ${formatDate(weekEnd)}, ${currentWeekStart.getFullYear()}`;
    
    // Update day headers
    for (let i = 0; i < 5; i++) {
        const dayDate = new Date(currentWeekStart);
        dayDate.setDate(dayDate.getDate() + i);
        const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        document.getElementById(`day_${i}_header`).innerHTML = 
            `${dayNames[i]}<br><span class="text-neutral-400 font-normal">${dayDate.getMonth() + 1}/${dayDate.getDate()}</span>`;
    }
    
    // Fetch schedule data from server
    const startDate = currentWeekStart.toISOString().split('T')[0];
    fetch(`/api/schedule/adiutor/${currentAdiutorId}/timeline?start_date=${startDate}`)
        .then(response => response.json())
        .then(data => {
            renderSchedule(data);
        })
        .catch(error => {
            console.error('Error loading schedule:', error);
            // Render empty schedule as fallback
            renderSchedule({ slots: [], summary: { scheduled: 0, available: 0 } });
        });
}

function renderSchedule(data) {
    const tbody = document.getElementById('schedule_calendar_body');
    tbody.innerHTML = '';
    
    // Working hours: 8 AM to 6 PM
    const startHour = 8;
    const endHour = 18;
    
    for (let hour = startHour; hour < endHour; hour++) {
        const row = document.createElement('tr');
        
        // Time column
        const timeCell = document.createElement('td');
        timeCell.className = 'px-4 py-3 text-sm text-neutral-500 font-medium border-r border-neutral-200';
        timeCell.textContent = `${hour.toString().padStart(2, '0')}:00`;
        row.appendChild(timeCell);
        
        // Day columns (Mon-Fri)
        for (let day = 0; day < 5; day++) {
            const cell = document.createElement('td');
            cell.className = 'px-2 py-3 text-center text-xs border-r border-neutral-200 min-w-[120px]';
            
            // Find events for this time slot
            const dayDate = new Date(currentWeekStart);
            dayDate.setDate(dayDate.getDate() + day);
            const dateStr = dayDate.toISOString().split('T')[0];
            const timeStr = `${hour.toString().padStart(2, '0')}:00`;
            
            const events = (data.slots || []).filter(slot => 
                slot.date === dateStr && slot.hour === hour
            );
            
            if (events.length > 0) {
                events.forEach(event => {
                    const eventDiv = document.createElement('div');
                    if (event.type === 'task') {
                        eventDiv.className = 'bg-primary-100 border border-primary-300 text-primary-800 rounded px-2 py-1 mb-1 text-left';
                        eventDiv.innerHTML = `
                            <div class="font-medium">📋 ${event.title}</div>
                            <div class="text-xs">${event.duration}</div>
                        `;
                    } else if (event.type === 'calendar') {
                        eventDiv.className = 'bg-purple-100 border border-purple-300 text-purple-800 rounded px-2 py-1 mb-1 text-left';
                        eventDiv.innerHTML = `
                            <div class="font-medium">⚫ ${event.title}</div>
                            <div class="text-xs">${event.duration}</div>
                        `;
                    }
                    cell.appendChild(eventDiv);
                });
            } else {
                // Empty slot - check if it's lunch time
                if (hour === 12) {
                    cell.innerHTML = '<span class="text-neutral-400 italic">Lunch</span>';
                    cell.className += ' bg-neutral-50';
                }
            }
            
            row.appendChild(cell);
        }
        
        tbody.appendChild(row);
    }
}

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideCompleteModal();
        hideAssignModal();
        hideScheduleModal();
    }
});

// Prevent modal from closing when clicking inside the modal content
document.addEventListener('DOMContentLoaded', function() {
    const modalContents = document.querySelectorAll('.modal-content');
    modalContents.forEach(function(content) {
        content.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
    
    // Toggle between Hourly Rate and Agreed Rate based on time tracking checkbox
    const timeTrackingCheckbox = document.querySelector('input[name="requires_time_tracking"]');
    const hourlyRateDiv = document.getElementById('hourly_rate_container');
    const agreedRateDiv = document.getElementById('agreed_rate_container');
    
    if (timeTrackingCheckbox && hourlyRateDiv && agreedRateDiv) {
        function toggleRateFields() {
            if (timeTrackingCheckbox.checked) {
                // Show Hourly Rate, hide Agreed Rate
                hourlyRateDiv.style.display = 'block';
                agreedRateDiv.style.display = 'none';
            } else {
                // Hide Hourly Rate, show Agreed Rate
                hourlyRateDiv.style.display = 'none';
                agreedRateDiv.style.display = 'block';
            }
        }
        
        // Initial state
        toggleRateFields();
        
        // Listen for changes
        timeTrackingCheckbox.addEventListener('change', toggleRateFields);
    }
    
    // Validate hourly rate against standard rate
    const hourlyRateInput = document.getElementById('hourly_rate_input');
    const hourlyRateWarning = document.getElementById('hourly_rate_warning');
    
    if (hourlyRateInput && hourlyRateWarning) {
        hourlyRateInput.addEventListener('input', function() {
            const overriddenRate = parseFloat(this.value);
            
            // Only show warning if a value was entered and it's below standard rate
            if (this.value && overriddenRate && adiutorStandardRate && overriddenRate < adiutorStandardRate) {
                hourlyRateWarning.classList.remove('hidden');
            } else {
                hourlyRateWarning.classList.add('hidden');
            }
        });
    }
});
</script>

@endsection

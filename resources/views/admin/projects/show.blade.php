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
                        'active' => ['class' => 'bg-primary-100 text-primary-700', 'label' => 'Active', 'icon' => 'play-circle'],
                        'in_progress' => ['class' => 'bg-info-100 text-info-700', 'label' => 'In Progress', 'icon' => 'loader'],
                        'review' => ['class' => 'bg-purple-100 text-purple-700', 'label' => 'In Review', 'icon' => 'eye'],
                        'completed' => ['class' => 'bg-success-100 text-success-700', 'label' => 'Completed', 'icon' => 'check-circle'],
                        'cancelled' => ['class' => 'bg-error-100 text-error-700', 'label' => 'Cancelled', 'icon' => 'x-circle'],
                        'on_hold' => ['class' => 'bg-warning-100 text-warning-700', 'label' => 'On Hold', 'icon' => 'pause-circle'],
                    ];
                    $config = $statusConfig[$project->status] ?? ['class' => 'bg-neutral-100 text-neutral-700', 'label' => ucfirst($project->status), 'icon' => 'circle'];
                @endphp
                
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-sm font-medium {{ $config['class'] }} mr-3">
                    <x-dynamic-component :component="'lucide-' . $config['icon']" class="w-3.5 h-3.5" />
                    {{ $config['label'] }}
                </span>
                
                @if($project->priority)
                    @php
                        $priorityConfig = [
                            'low' => ['class' => 'bg-info-100 text-info-700', 'icon' => 'arrow-down'],
                            'medium' => ['class' => 'bg-warning-100 text-warning-700', 'icon' => 'minus'],
                            'high' => ['class' => 'bg-orange-100 text-orange-700', 'icon' => 'arrow-up'],
                            'urgent' => ['class' => 'bg-error-100 text-error-700', 'icon' => 'alert-triangle'],
                        ];
                        $pConfig = $priorityConfig[$project->priority] ?? ['class' => 'bg-neutral-100 text-neutral-700', 'icon' => 'circle'];
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-sm font-medium {{ $pConfig['class'] }}">
                        <x-dynamic-component :component="'lucide-' . $pConfig['icon']" class="w-3.5 h-3.5" />
                        {{ ucfirst($project->priority) }} Priority
                    </span>
                @endif
            </div>
            
            <h1 class="text-2xl font-semibold text-neutral-800 mb-1">{{ $project->title }}</h1>
            
            <div class="text-neutral-500 flex items-center mb-3">
                <x-lucide-calendar class="w-4 h-4 mr-2" />
                <span>Created {{ $project->created_at->format('F d, Y') }}</span>
            </div>
            
            <!-- Related Links -->
            <x-ui.related-links :project="$project" role="admin" />
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

                    {{-- Service Requirements from Request --}}
                    @if($project->serviceRequest && (!empty($project->serviceRequest->requested_features) || !empty($project->serviceRequest->requested_skills) || !empty($project->serviceRequest->template_features) || !empty($project->serviceRequest->template_skills)))
                        <div class="mb-6 p-4 bg-secondary-50 rounded-xl border border-secondary-200">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-medium text-secondary-700 uppercase tracking-wider flex items-center">
                                    <x-lucide-layers class="w-4 h-4 mr-2" />
                                    Service Requirements
                                </h3>
                                @if($project->serviceRequest->has_customizations)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                        <x-lucide-edit-3 class="w-3 h-3 mr-1" />
                                        Client Customized
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                        <x-lucide-copy class="w-3 h-3 mr-1" />
                                        Template Default
                                    </span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Features / What's Included --}}
                                @php
                                    $sr = $project->serviceRequest;
                                    $displayFeatures = $sr->effective_features ?? [];
                                @endphp
                                @if(!empty($displayFeatures))
                                    <div class="bg-white p-3 rounded-lg border border-secondary-100">
                                        <h4 class="text-xs font-semibold text-neutral-600 uppercase mb-2 flex items-center">
                                            <x-lucide-check-circle class="w-3 h-3 mr-1 text-success-500" />
                                            What's Included
                                        </h4>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($displayFeatures as $feature)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-50 text-success-700 border border-success-200">
                                                    {{ $feature }}
                                                </span>
                                            @endforeach
                                        </div>
                                        @if($sr->has_customizations && $sr->hasCustomizedFeatures())
                                            <div class="mt-2 pt-2 border-t border-neutral-100">
                                                <p class="text-xs text-neutral-400 mb-1">Original template:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($sr->template_features ?? [] as $feature)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-neutral-100 text-neutral-500 line-through">
                                                            {{ $feature }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                
                                {{-- Skills Required --}}
                                @php
                                    $displaySkills = $sr->effective_skills ?? [];
                                @endphp
                                @if(!empty($displaySkills))
                                    <div class="bg-white p-3 rounded-lg border border-secondary-100">
                                        <h4 class="text-xs font-semibold text-neutral-600 uppercase mb-2 flex items-center">
                                            <x-lucide-wrench class="w-3 h-3 mr-1 text-primary-500" />
                                            Skills Required
                                        </h4>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($displaySkills as $skill)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-50 text-primary-700 border border-primary-200">
                                                    {{ $skill }}
                                                </span>
                                            @endforeach
                                        </div>
                                        @if($sr->has_customizations && $sr->hasCustomizedSkills())
                                            <div class="mt-2 pt-2 border-t border-neutral-100">
                                                <p class="text-xs text-neutral-400 mb-1">Original template:</p>
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($sr->template_skills ?? [] as $skill)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-neutral-100 text-neutral-500 line-through">
                                                            {{ $skill }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Template Reference Info --}}
                            @if(!empty($sr->estimated_duration_days) || !empty($sr->template_base_price))
                                <div class="mt-4 pt-3 border-t border-secondary-200 flex flex-wrap gap-4 text-sm">
                                    @if(!empty($sr->estimated_duration_days))
                                        <div class="flex items-center text-neutral-600">
                                            <x-lucide-calendar-days class="w-4 h-4 mr-1.5 text-secondary-500" />
                                            <span>Template Est.: <strong>{{ $sr->estimated_duration_days }} days</strong></span>
                                        </div>
                                    @endif
                                    @if(!empty($sr->template_base_price))
                                        <div class="flex items-center text-neutral-600">
                                            <x-lucide-banknote class="w-4 h-4 mr-1.5 text-success-500" />
                                            <span>Template Base: <strong>₱{{ number_format($sr->template_base_price, 2) }}</strong></span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @elseif($project->serviceRequest && !$project->serviceRequest->template_service_id)
                        {{-- No template was used - show note --}}
                        <div class="mb-6 p-4 bg-neutral-50 rounded-xl border border-neutral-200">
                            <div class="flex items-center gap-2 text-neutral-500">
                                <x-lucide-info class="w-4 h-4" />
                                <span class="text-sm">This project was created from a custom request without a service template.</span>
                            </div>
                        </div>
                    @endif

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
                            @if($project->tasks->count() > 1)
                            <span class="text-xs font-normal text-neutral-400 ml-2">
                                <x-lucide-grip-vertical class="w-3 h-3 inline" /> Drag to reorder
                            </span>
                            @endif
                        </h2>
                        <div class="flex items-center gap-2">
                            <span id="reorderStatus" class="text-xs text-neutral-500 hidden"></span>
                            <x-ui.button variant="primary" size="sm" href="{{ route('admin.tasks.create', ['project_id' => $project->id]) }}">
                                <x-lucide-plus class="w-4 h-4" />
                                Add Task
                            </x-ui.button>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($project->tasks->count() > 0)
                            <div id="tasksList" class="space-y-3" data-project-id="{{ $project->id }}">
                                @foreach($project->orderedTasks as $task)
                                <div class="task-item border border-neutral-200 rounded-xl p-4 hover:bg-neutral-50 transition cursor-move group" 
                                     data-task-id="{{ $task->taskID }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-3 flex-1">
                                            <!-- Drag Handle -->
                                            <div class="drag-handle flex-shrink-0 mt-1 text-neutral-300 group-hover:text-neutral-500 transition cursor-grab active:cursor-grabbing">
                                                <x-lucide-grip-vertical class="w-5 h-5" />
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h3 class="font-semibold text-neutral-900">{{ $task->taskTitle }}</h3>
                                                    @php
                                                        $taskStatusConfig = [
                                                            'pending' => ['class' => 'bg-warning-100 text-warning-700', 'label' => 'Pending', 'icon' => 'clock'],
                                                            'in_progress' => ['class' => 'bg-info-100 text-info-700', 'label' => 'In Progress', 'icon' => 'loader'],
                                                            'completed' => ['class' => 'bg-success-100 text-success-700', 'label' => 'Completed', 'icon' => 'check-circle'],
                                                            'on_hold' => ['class' => 'bg-neutral-100 text-neutral-700', 'label' => 'On Hold', 'icon' => 'pause-circle'],
                                                            'cancelled' => ['class' => 'bg-error-100 text-error-700', 'label' => 'Cancelled', 'icon' => 'x-circle'],
                                                            'pending_approval' => ['class' => 'bg-purple-100 text-purple-700', 'label' => 'Pending Approval', 'icon' => 'eye'],
                                                        ];
                                                        $taskConfig = $taskStatusConfig[$task->status] ?? ['class' => 'bg-neutral-100 text-neutral-700', 'label' => 'Unknown', 'icon' => 'circle-dashed'];
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $taskConfig['class'] }}">
                                                        <x-dynamic-component :component="'lucide-' . $taskConfig['icon']" class="w-3 h-3" />
                                                        {{ $taskConfig['label'] }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-neutral-600 mb-2">{{ Str::limit($task->taskDescription, 100) }}</p>
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
                                                        {{ $task->deadline ? $task->deadline->format('M d, Y') : 'No deadline' }}
                                                    </span>
                                                </div>
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

                <!-- Deliverables Section - Grouped by Task -->
                <x-ui.card class="mb-6">
                    <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                            <x-lucide-package-check class="w-5 h-5 text-success-500" />
                            Deliverables
                            @if($totalDeliverables > 0)
                                <span id="total-deliverables-badge" class="ml-2 px-2 py-0.5 bg-primary-100 text-primary-700 text-xs font-medium rounded-full">
                                    {{ $totalDeliverables }}
                                </span>
                                @if($pendingDeliverables > 0)
                                    <span id="pending-deliverables-badge" class="px-2 py-0.5 bg-warning-100 text-warning-700 text-xs font-medium rounded-full">
                                        {{ $pendingDeliverables }} pending approval
                                    </span>
                                @endif
                            @endif
                        </h2>
                        <a href="{{ route('admin.deliverables.pending') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                            View All Pending
                            <x-lucide-arrow-right class="w-4 h-4" />
                        </a>
                    </div>
                    <div class="p-6">
                        @if($tasksWithDeliverables->count() > 0 || $projectLevelDocuments->count() > 0)
                            <div class="space-y-4">
                                {{-- Project-level documents --}}
                                @if($projectLevelDocuments->count() > 0)
                                    <div x-data="{ open: true }" class="border border-neutral-200 rounded-xl overflow-hidden">
                                        <button @click="open = !open" class="w-full flex items-center justify-between p-4 bg-neutral-50 hover:bg-neutral-100 transition-colors">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                                    <x-lucide-folder class="w-5 h-5 text-primary-600" />
                                                </div>
                                                <div class="text-left">
                                                    <h3 class="font-semibold text-neutral-800">Project Files</h3>
                                                    <p class="text-sm text-neutral-500">{{ $projectLevelDocuments->count() }} file(s)</p>
                                                </div>
                                            </div>
                                            <x-lucide-chevron-down class="w-5 h-5 text-neutral-400 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
                                        </button>
                                        <div x-show="open" x-collapse class="border-t border-neutral-200">
                                            <div class="p-4 space-y-2">
                                                @foreach($projectLevelDocuments as $document)
                                                    @include('admin.projects.partials.deliverable-item', ['document' => $document])
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Task-grouped deliverables --}}
                                @foreach($tasksWithDeliverables as $task)
                                    <div x-data="{ open: true }" class="border border-neutral-200 rounded-xl overflow-hidden">
                                        <button @click="open = !open" class="w-full flex items-center justify-between p-4 bg-neutral-50 hover:bg-neutral-100 transition-colors">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                                    <x-lucide-clipboard-list class="w-5 h-5 text-primary-600" />
                                                </div>
                                                <div class="text-left">
                                                    <h3 class="font-semibold text-neutral-800">{{ $task->taskTitle }}</h3>
                                                    <p class="text-sm text-neutral-500">
                                                        {{ $task->documents->count() }} deliverable(s)
                                                        @php
                                                            $taskPending = $task->documents->where('is_deliverable', true)->where('is_approved', false)->count();
                                                        @endphp
                                                        @if($taskPending > 0)
                                                            <span class="text-warning-600 task-pending-count" data-task-id="{{ $task->taskID }}">• {{ $taskPending }} pending</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @php
                                                    $statusConfig = match($task->status) {
                                                        'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-700', 'icon' => 'check-circle'],
                                                        'in_progress' => ['bg' => 'bg-info-100', 'text' => 'text-info-700', 'icon' => 'loader'],
                                                        'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-700', 'icon' => 'clock'],
                                                        default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-600', 'icon' => 'circle-dashed']
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                                    <x-dynamic-component :component="'lucide-' . $statusConfig['icon']" class="w-3 h-3" />
                                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                                <x-lucide-chevron-down class="w-5 h-5 text-neutral-400 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
                                            </div>
                                        </button>
                                        <div x-show="open" x-collapse class="border-t border-neutral-200">
                                            <div class="p-4 space-y-2">
                                                @foreach($task->documents as $document)
                                                    @include('admin.projects.partials.deliverable-item', ['document' => $document])
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="bg-neutral-100 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                    <x-lucide-package-open class="w-8 h-8 text-neutral-400" />
                                </div>
                                <h3 class="text-neutral-500 text-base">No deliverables yet</h3>
                                <p class="text-neutral-400 text-sm mt-1">Deliverables from tasks will appear here organized by task</p>
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
                                @if($project->client->profilePic)
                                    <img src="{{ $project->client->getProfilePictureUrl() }}" 
                                         alt="{{ $project->client->fullName }}" 
                                         class="w-10 h-10 rounded-full object-cover flex-shrink-0 shadow-sm">
                                @else
                                    <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0 shadow-sm">
                                        {{ substr($project->client->fullName, 0, 1) }}
                                    </div>
                                @endif
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
                                        @if($adiutor->profilePic)
                                            <img src="{{ $adiutor->getProfilePictureUrl() }}" 
                                                 alt="{{ $adiutor->fullName }}" 
                                                 class="w-9 h-9 rounded-full object-cover flex-shrink-0 shadow-sm">
                                        @else
                                            <div class="w-9 h-9 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0 shadow-sm">
                                                {{ substr($adiutor->fullName, 0, 1) }}
                                            </div>
                                        @endif
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
                                                        method="POST" class="inline"
                                                        onsubmit="return window.Alerts.confirmForm(event, 'Revoke Approval', 'Revoke approval? This will prevent payout.')">
                                                        @csrf
                                                        <button type="submit"
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
                                            
                                            <!-- Time Entry Approval Button for Hourly Rate -->
                                            <button type="button"
                                                    onclick="showTimeEntriesModal({{ $adiutor->id }}, {{ json_encode($adiutor->fullName) }})"
                                                    class="w-full mt-2 flex items-center justify-center gap-1.5 py-2 px-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-medium transition-colors border border-blue-200">
                                                <x-lucide-clock class="w-3.5 h-3.5" />
                                                View Time Entries
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <div class="mt-3 pt-2 border-t border-neutral-200/60">
                                        <form action="{{ route('admin.projects.remove-adiutor', [$project->id, $adiutor->id]) }}" 
                                            method="POST"
                                            onsubmit="return window.Alerts.confirmDeleteForm(event, 'Remove Adiutor', 'Are you sure you want to remove this adiutor from the project?')">
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
                        @php
                            $incompleteTasks = $project->tasks->whereNotIn('status', ['completed', 'cancelled']);
                            $completedTasks = $project->tasks->where('status', 'completed');
                            $hasIncompleteTasks = $incompleteTasks->count() > 0;
                        @endphp

                        @if($hasIncompleteTasks)
                        <!-- Warning: Incomplete Tasks -->
                        <div class="mb-4 p-4 bg-warning-50 border border-warning-200 rounded-xl">
                            <div class="flex items-start">
                                <x-lucide-alert-triangle class="w-5 h-5 text-warning-600 mt-0.5 mr-3 flex-shrink-0" />
                                <div>
                                    <h4 class="text-sm font-semibold text-warning-800 mb-1">Incomplete Tasks Detected</h4>
                                    <p class="text-xs text-warning-700 mb-2">
                                        {{ $incompleteTasks->count() }} task(s) are not yet completed:
                                    </p>
                                    <ul class="text-xs text-warning-700 list-disc list-inside space-y-0.5 max-h-24 overflow-y-auto">
                                        @foreach($incompleteTasks->take(5) as $task)
                                            <li>{{ $task->taskTitle }} <span class="text-warning-500">({{ ucfirst($task->status) }})</span></li>
                                        @endforeach
                                        @if($incompleteTasks->count() > 5)
                                            <li class="text-warning-500">...and {{ $incompleteTasks->count() - 5 }} more</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Success: All Tasks Complete -->
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-success-100 flex items-center justify-center mr-3">
                                <x-lucide-check-circle class="w-6 h-6 text-success-600" />
                            </div>
                            <div>
                                <p class="text-sm text-neutral-600 mb-1">
                                    All {{ $completedTasks->count() }} task(s) are completed. Ready to mark project as complete.
                                </p>
                                <p class="text-xs text-neutral-500">
                                    This will notify the client about the project completion.
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Task Summary -->
                        <div class="mb-4 p-3 bg-neutral-50 rounded-lg">
                            <div class="flex justify-between text-xs text-neutral-600">
                                <span>Completed: <strong class="text-success-600">{{ $completedTasks->count() }}</strong></span>
                                <span>Pending: <strong class="text-warning-600">{{ $incompleteTasks->count() }}</strong></span>
                                <span>Total: <strong>{{ $project->tasks->count() }}</strong></span>
                            </div>
                        </div>

                        @if($hasIncompleteTasks)
                        <!-- Force Complete Option -->
                        <div class="mb-4 p-3 bg-error-50 border border-error-100 rounded-lg">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" name="force_complete" value="1" 
                                       class="mt-0.5 w-4 h-4 text-error-600 border-error-300 rounded focus:ring-error-500"
                                       onchange="toggleCompleteButton(this)">
                                <div>
                                    <span class="text-sm font-medium text-error-800">Force Complete</span>
                                    <p class="text-xs text-error-600 mt-0.5">
                                        Complete this project even with incomplete tasks. Use with caution.
                                    </p>
                                </div>
                            </label>
                        </div>
                        @endif
                        
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
                        <x-ui.button type="submit" variant="success" id="completeProjectBtn" :disabled="$hasIncompleteTasks">
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
                                                    @if(isset($adiutor['profilePic']) && $adiutor['profilePic'])
                                                        <img src="{{ $adiutor['profilePic'] }}" 
                                                             alt="{{ $adiutor['fullName'] }}" 
                                                             class="h-10 w-10 rounded-full object-cover mr-3">
                                                    @else
                                                        <div class="flex-shrink-0 h-10 w-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                                            {{ substr($adiutor['fullName'], 0, 1) }}
                                                        </div>
                                                    @endif
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

<!-- Time Entries Modal for Hourly Rate Team Members -->
<div id="timeEntriesModal" class="modal-overlay fixed inset-0 z-50 overflow-y-auto hidden" onclick="hideTimeEntriesModal()">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity z-40"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="modal-content inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative z-50 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="sticky top-0 bg-white border-b border-neutral-100 px-6 py-4 flex items-center justify-between z-10">
            <div>
                <h2 class="text-xl font-bold text-neutral-900 flex items-center gap-2">
                    <x-lucide-clock class="w-5 h-5 text-blue-600" />
                    Time Entries: <span id="time_entries_adiutor_name" class="text-blue-600"></span>
                </h2>
                <p class="text-sm text-neutral-500 mt-1">
                    Project: {{ $project->project_title }}
                </p>
            </div>
            <button onclick="hideTimeEntriesModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <x-lucide-x class="w-6 h-6" />
            </button>
        </div>

        <!-- Stats Summary -->
        <div id="time_entries_stats" class="bg-neutral-50 px-6 py-4 border-b border-neutral-100">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg px-4 py-3 border border-neutral-200">
                    <div class="text-xs text-neutral-500">Total Entries</div>
                    <div class="text-lg font-bold text-neutral-900" id="stat_total_entries">-</div>
                </div>
                <div class="bg-white rounded-lg px-4 py-3 border border-neutral-200">
                    <div class="text-xs text-neutral-500">Total Hours</div>
                    <div class="text-lg font-bold text-neutral-900" id="stat_total_hours">-</div>
                </div>
                <div class="bg-white rounded-lg px-4 py-3 border border-warning-200">
                    <div class="text-xs text-warning-600">Pending</div>
                    <div class="text-lg font-bold text-warning-700" id="stat_pending">-</div>
                </div>
                <div class="bg-white rounded-lg px-4 py-3 border border-success-200">
                    <div class="text-xs text-success-600">Approved</div>
                    <div class="text-lg font-bold text-success-700" id="stat_approved">-</div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 overflow-y-auto max-h-[50vh]" id="time_entries_content">
            <!-- Loading State -->
            <div id="time_entries_loading" class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
            </div>
            
            <!-- Time Entries List -->
            <div id="time_entries_list" class="hidden space-y-3">
                <!-- Entries will be dynamically inserted here -->
            </div>
            
            <!-- Empty State -->
            <div id="time_entries_empty" class="hidden text-center py-12">
                <x-lucide-clock class="w-12 h-12 text-neutral-300 mx-auto mb-4" />
                <p class="text-neutral-600">No time entries found for this adiutor on this project.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="sticky bottom-0 bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3">
            <x-ui.button variant="secondary" onclick="hideTimeEntriesModal()">
                Close
            </x-ui.button>
        </div>
        </div>
    </div>
</div>

<!-- Time Entry Reject Modal -->
<div id="timeEntryRejectModal" class="modal-overlay fixed inset-0 z-[60] overflow-y-auto hidden" onclick="hideTimeEntryRejectModal()">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity z-40"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="modal-content inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full relative z-50" onclick="event.stopPropagation()">
        <div class="bg-error-50 border-b border-error-100 px-6 py-4 flex justify-between items-center rounded-t-2xl">
            <h5 class="text-lg font-semibold text-error-800">Reject Time Entry</h5>
            <button type="button" onclick="hideTimeEntryRejectModal()" class="text-error-500 hover:text-error-700">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>
        
        <form id="timeEntryRejectForm" onsubmit="return submitTimeEntryRejection(event)">
            <div class="p-6">
                <p class="text-sm text-neutral-600 mb-4">Please provide a reason for rejecting this time entry. The entry will be deleted.</p>
                <input type="hidden" id="reject_time_entry_id" name="time_entry_id">
                <div>
                    <label for="reject_time_entry_reason" class="block text-sm font-medium text-neutral-700 mb-2">
                        Rejection Reason <span class="text-error-500">*</span>
                    </label>
                    <textarea id="reject_time_entry_reason" name="rejection_reason" rows="3" required
                              class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-neutral-800 focus:border-error-500 focus:ring-2 focus:ring-error-500/20 transition-colors"
                              placeholder="Explain why this time entry is being rejected..."></textarea>
                </div>
            </div>
            <div class="bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                <x-ui.button type="button" variant="secondary" onclick="hideTimeEntryRejectModal()">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="danger" id="rejectTimeEntryBtn">
                    <x-lucide-x class="w-4 h-4" />
                    Reject Entry
                </x-ui.button>
            </div>
        </form>
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

// Approve Deliverable via AJAX
function approveDeliverable(documentId) {
    const btn = document.getElementById(`approve-btn-${documentId}`);
    const statusBadge = document.getElementById(`status-badge-${documentId}`);
    const deliverableItem = document.getElementById(`deliverable-item-${documentId}`);
    const taskId = deliverableItem ? deliverableItem.dataset.taskId : null;
    
    // Disable button and show loading state
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin w-3.5 h-3.5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Approving...
    `;
    
    fetch(`/admin/deliverables/${documentId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status badge to show approved
            statusBadge.className = 'inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-success-100 text-success-700';
            statusBadge.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 mr-0.5"><path d="M20 6 9 17l-5-5"/></svg>
                Approved
            `;
            
            // Mark the item as no longer pending
            if (deliverableItem) {
                deliverableItem.dataset.isPending = 'false';
            }
            
            // Remove the approve button
            btn.remove();
            
            // Update pending counts
            updatePendingCounts(taskId);
            
            // Show success message
            if (window.Alerts) {
                window.Alerts.success(data.message || 'Deliverable approved successfully!');
            }
        } else {
            // Re-enable button on error
            btn.disabled = false;
            btn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1"><path d="M20 6 9 17l-5-5"/></svg>
                Approve
            `;
            if (window.Alerts) {
                window.Alerts.error(data.message || 'Failed to approve deliverable.');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1"><path d="M20 6 9 17l-5-5"/></svg>
            Approve
        `;
        if (window.Alerts) {
            window.Alerts.error('An error occurred. Please try again.');
        }
    });
}

// Update pending count badges after approval
function updatePendingCounts(taskId) {
    // Update main pending deliverables badge
    const pendingBadge = document.getElementById('pending-deliverables-badge');
    if (pendingBadge) {
        const match = pendingBadge.textContent.match(/(\d+)/);
        if (match) {
            const count = parseInt(match[1]) - 1;
            if (count > 0) {
                pendingBadge.textContent = `${count} pending approval`;
            } else {
                pendingBadge.remove();
            }
        }
    }
    
    // Update task-specific pending count if taskId provided
    if (taskId) {
        const taskPendingSpan = document.querySelector(`.task-pending-count[data-task-id="${taskId}"]`);
        if (taskPendingSpan) {
            const match = taskPendingSpan.textContent.match(/(\d+)/);
            if (match) {
                const count = parseInt(match[1]) - 1;
                if (count > 0) {
                    taskPendingSpan.textContent = `• ${count} pending`;
                } else {
                    taskPendingSpan.remove();
                }
            }
        }
    }
}

// Toggle complete button when force_complete checkbox is changed
function toggleCompleteButton(checkbox) {
    const btn = document.getElementById('completeProjectBtn');
    if (checkbox.checked) {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

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

// ====== TIME ENTRIES MODAL FUNCTIONS ======
let currentTimeEntriesAdiutorId = null;

function showTimeEntriesModal(adiutorId, adiutorName) {
    currentTimeEntriesAdiutorId = adiutorId;
    const modal = document.getElementById('timeEntriesModal');
    
    // Set adiutor name in header
    document.getElementById('time_entries_adiutor_name').textContent = adiutorName;
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Reset states
    document.getElementById('time_entries_loading').classList.remove('hidden');
    document.getElementById('time_entries_list').classList.add('hidden');
    document.getElementById('time_entries_empty').classList.add('hidden');
    
    // Fetch time entries
    fetchTimeEntries(adiutorId);
}

function hideTimeEntriesModal() {
    const modal = document.getElementById('timeEntriesModal');
    modal.classList.add('hidden');
    currentTimeEntriesAdiutorId = null;
}

function fetchTimeEntries(adiutorId) {
    const projectId = {{ $project->id }};
    
    fetch(`/admin/projects/${projectId}/adiutors/${adiutorId}/time-entries`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('time_entries_loading').classList.add('hidden');
        
        if (data.success) {
            updateTimeEntriesStats(data.stats);
            
            if (data.time_entries.length > 0) {
                renderTimeEntries(data.time_entries);
                document.getElementById('time_entries_list').classList.remove('hidden');
            } else {
                document.getElementById('time_entries_empty').classList.remove('hidden');
            }
        } else {
            document.getElementById('time_entries_empty').classList.remove('hidden');
            if (window.Alerts) {
                window.Alerts.error(data.message || 'Failed to load time entries');
            }
        }
    })
    .catch(error => {
        console.error('Error fetching time entries:', error);
        document.getElementById('time_entries_loading').classList.add('hidden');
        document.getElementById('time_entries_empty').classList.remove('hidden');
        // Only show error alert if not a post-approval refresh
        if (window.Alerts && !document.getElementById('time_entries_list').innerHTML) {
            window.Alerts.error('Failed to load time entries: ' + error.message);
        }
    });
}

function updateTimeEntriesStats(stats) {
    document.getElementById('stat_total_entries').textContent = stats.total_entries;
    document.getElementById('stat_total_hours').textContent = stats.total_hours.toFixed(1) + ' hrs';
    document.getElementById('stat_pending').textContent = `${stats.pending_count} (${stats.pending_hours.toFixed(1)} hrs)`;
    document.getElementById('stat_approved').textContent = `${stats.approved_count} (₱${stats.approved_amount.toLocaleString('en-PH', {minimumFractionDigits: 2})})`;
}

function renderTimeEntries(entries) {
    const container = document.getElementById('time_entries_list');
    container.innerHTML = '';
    
    entries.forEach(entry => {
        const isPending = !entry.is_approved;
        const statusBadge = isPending 
            ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-warning-100 text-warning-700"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 mr-1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Pending</span>'
            : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-100 text-success-700"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 mr-1"><path d="M20 6 9 17l-5-5"/></svg>Approved</span>';
        
        const actionsHtml = isPending ? `
            <div class="flex items-center gap-2 mt-2 md:mt-0">
                <button type="button" onclick="approveTimeEntry(${entry.id}, this)" 
                        class="inline-flex items-center px-3 py-1.5 bg-success-500 hover:bg-success-600 text-white rounded-lg text-xs font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1"><path d="M20 6 9 17l-5-5"/></svg>
                    Approve
                </button>
                <button type="button" onclick="showTimeEntryRejectModal(${entry.id})" 
                        class="inline-flex items-center px-3 py-1.5 bg-error-50 hover:bg-error-100 text-error-700 rounded-lg text-xs font-medium transition-colors border border-error-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    Reject
                </button>
            </div>
        ` : `
            <div class="text-xs text-neutral-500 mt-2 md:mt-0">
                Approved by ${entry.approved_by || 'Admin'}<br>
                <span class="text-neutral-400">${entry.approved_at || ''}</span>
            </div>
        `;

        const entryHtml = `
            <div class="time-entry-item p-4 bg-neutral-50 rounded-xl border border-neutral-200" data-entry-id="${entry.id}">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            ${statusBadge}
                            <span class="text-xs text-neutral-500">${entry.entry_date}</span>
                        </div>
                        <p class="font-medium text-neutral-900 text-sm">${entry.task_title}</p>
                        ${entry.description ? `<p class="text-xs text-neutral-600 mt-1">${entry.description}</p>` : ''}
                        <div class="flex items-center gap-4 mt-2 text-xs text-neutral-600">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1 text-neutral-400"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                ${entry.hours} hours
                            </span>
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1 text-neutral-400"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                ₱${entry.hourly_rate.toLocaleString()}/hr
                            </span>
                            <span class="font-medium text-neutral-900">
                                ₱${entry.calculated_amount.toLocaleString('en-PH', {minimumFractionDigits: 2})}
                            </span>
                        </div>
                    </div>
                    ${actionsHtml}
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', entryHtml);
    });
}

function approveTimeEntry(entryId, btn) {
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-3.5 h-3.5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Approving...';
    
    fetch(`/admin/payouts/time-entries/${entryId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({})
    })
    .then(response => {
        // Check if it's a redirect (non-JSON response)
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        }
        // If not JSON, it was probably a redirect/success
        return { success: true, message: 'Time entry approved successfully!' };
    })
    .then(data => {
        if (data.success || data.message?.includes('approved')) {
            // Refresh the time entries
            fetchTimeEntries(currentTimeEntriesAdiutorId);
            if (window.Alerts) {
                window.Alerts.success(data.message || 'Time entry approved successfully!');
            }
        } else {
            btn.disabled = false;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1"><path d="M20 6 9 17l-5-5"/></svg> Approve';
            if (window.Alerts) {
                window.Alerts.error(data.message || 'Failed to approve time entry');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 mr-1"><path d="M20 6 9 17l-5-5"/></svg> Approve';
        if (window.Alerts) {
            window.Alerts.error('An error occurred. Please try again.');
        }
    });
}

function showTimeEntryRejectModal(entryId) {
    document.getElementById('reject_time_entry_id').value = entryId;
    document.getElementById('reject_time_entry_reason').value = '';
    document.getElementById('timeEntryRejectModal').classList.remove('hidden');
}

function hideTimeEntryRejectModal() {
    document.getElementById('timeEntryRejectModal').classList.add('hidden');
    document.getElementById('reject_time_entry_id').value = '';
    document.getElementById('reject_time_entry_reason').value = '';
}

function submitTimeEntryRejection(event) {
    event.preventDefault();
    
    const entryId = document.getElementById('reject_time_entry_id').value;
    const reason = document.getElementById('reject_time_entry_reason').value;
    const btn = document.getElementById('rejectTimeEntryBtn');
    
    if (!reason.trim()) {
        if (window.Alerts) {
            window.Alerts.error('Please provide a rejection reason');
        }
        return false;
    }
    
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Rejecting...';
    
    fetch(`/admin/payouts/time-entries/${entryId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ rejection_reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message?.includes('rejected')) {
            hideTimeEntryRejectModal();
            fetchTimeEntries(currentTimeEntriesAdiutorId);
            if (window.Alerts) {
                window.Alerts.success(data.message || 'Time entry rejected successfully!');
            }
        } else {
            btn.disabled = false;
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg> Reject Entry';
            if (window.Alerts) {
                window.Alerts.error(data.message || 'Failed to reject time entry');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg> Reject Entry';
        if (window.Alerts) {
            window.Alerts.error('An error occurred. Please try again.');
        }
    });
    
    return false;
}
// ====== END TIME ENTRIES MODAL FUNCTIONS ======

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
            ratingHtml += '<svg class="w-4 h-4 inline text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
        } else if (i < rating) {
            ratingHtml += '<svg class="w-4 h-4 inline text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="url(#half)"/><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs></svg>';
        } else {
            ratingHtml += '<svg class="w-4 h-4 inline text-neutral-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
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

// ============================================
// Task Reordering with Drag & Drop
// ============================================

// Load SortableJS from CDN
const sortableScript = document.createElement('script');
sortableScript.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js';
sortableScript.onload = initializeSortable;
document.head.appendChild(sortableScript);

let sortableInstance = null;
let isReorderMode = false;

function initializeSortable() {
    const taskList = document.getElementById('sortable-tasks');
    const toggleBtn = document.getElementById('toggle-reorder-mode');
    const saveBtn = document.getElementById('save-task-order');
    const cancelBtn = document.getElementById('cancel-reorder');
    const reorderControls = document.getElementById('reorder-controls');
    
    if (!taskList || !toggleBtn) return;
    
    // Initialize Sortable but keep it disabled initially
    sortableInstance = new Sortable(taskList, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'bg-blue-50',
        chosenClass: 'bg-blue-100',
        dragClass: 'shadow-lg',
        disabled: true,
        onEnd: function(evt) {
            // Visual feedback that order changed
            if (evt.oldIndex !== evt.newIndex) {
                saveBtn.classList.remove('bg-gray-400');
                saveBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            }
        }
    });
    
    // Toggle reorder mode
    toggleBtn.addEventListener('click', function() {
        isReorderMode = !isReorderMode;
        
        if (isReorderMode) {
            // Enable reorder mode
            sortableInstance.option('disabled', false);
            taskList.classList.add('reorder-mode');
            reorderControls.classList.remove('hidden');
            toggleBtn.innerHTML = '<svg class="w-4 h-4 inline mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg> Exit Reorder Mode';
            toggleBtn.classList.remove('bg-gray-600');
            toggleBtn.classList.add('bg-red-600', 'hover:bg-red-700');
            
            // Show drag handles
            document.querySelectorAll('.drag-handle').forEach(handle => {
                handle.classList.remove('opacity-0');
                handle.classList.add('opacity-100');
            });
        } else {
            exitReorderMode();
        }
    });
    
    // Cancel reordering
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            // Reload to reset order
            window.location.reload();
        });
    }
    
    // Save new order
    if (saveBtn) {
        saveBtn.addEventListener('click', saveTaskOrder);
    }
}

function exitReorderMode() {
    const taskList = document.getElementById('sortable-tasks');
    const toggleBtn = document.getElementById('toggle-reorder-mode');
    const reorderControls = document.getElementById('reorder-controls');
    
    isReorderMode = false;
    sortableInstance.option('disabled', true);
    taskList.classList.remove('reorder-mode');
    reorderControls.classList.add('hidden');
    toggleBtn.innerHTML = '<svg class="w-4 h-4 inline mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 16 4 4 4-4"/><path d="M7 20V4"/><path d="m21 8-4-4-4 4"/><path d="M17 4v16"/></svg> Reorder Tasks';
    toggleBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
    toggleBtn.classList.add('bg-gray-600');
    
    // Hide drag handles
    document.querySelectorAll('.drag-handle').forEach(handle => {
        handle.classList.remove('opacity-100');
        handle.classList.add('opacity-0');
    });
}

function saveTaskOrder() {
    const taskList = document.getElementById('sortable-tasks');
    const saveBtn = document.getElementById('save-task-order');
    const taskItems = taskList.querySelectorAll('[data-task-id]');
    
    // Collect task IDs in new order
    const taskIds = Array.from(taskItems).map(item => parseInt(item.dataset.taskId));
    
    // Disable save button and show loading
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-1 animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Saving...';
    
    // Send AJAX request
    fetch('{{ route("admin.tasks.reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            project_id: {{ $project->id }},
            task_ids: taskIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            window.toast.success('Task order saved successfully!');
            exitReorderMode();
        } else {
            window.toast.error(data.message || 'Failed to save task order');
        }
    })
    .catch(error => {
        console.error('Error saving task order:', error);
        window.toast.error('An error occurred while saving task order');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Order';
    });
}
</script>

<style>
/* Drag and Drop Styles */
#sortable-tasks.reorder-mode [data-task-id] {
    cursor: move;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

#sortable-tasks.reorder-mode [data-task-id]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.drag-handle {
    cursor: grab;
    transition: opacity 0.2s ease;
}

.drag-handle:active {
    cursor: grabbing;
}

.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    background-color: #EBF4FF !important;
}
</style>

@endsection

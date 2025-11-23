@extends('admin.layouts.app')

@section('title', 'Project Details - ' . $project->title)
@section('page-title', 'Project Details')

@section('content')
<div class="px-6 py-8">
        
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center mb-2">
                @php
                    $statusConfig = [
                        'active' => ['class' => 'bg-info-100 text-info-800', 'label' => 'Active'],
                        'in_progress' => ['class' => 'bg-primary-100 text-primary-800', 'label' => 'In Progress'],
                        'review' => ['class' => 'bg-warning-100 text-warning-800', 'label' => 'Review'],
                        'completed' => ['class' => 'bg-success-100 text-success-800', 'label' => 'Completed'],
                        'cancelled' => ['class' => 'bg-error-100 text-error-800', 'label' => 'Cancelled'],
                        'on_hold' => ['class' => 'bg-neutral-100 text-neutral-800', 'label' => 'On Hold'],
                    ];
                    $config = $statusConfig[$project->status] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'label' => ucfirst($project->status)];
                @endphp
                
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $config['class'] }} mr-3">
                    <span class="h-2 w-2 rounded-full {{ str_replace('bg-', 'bg-', str_replace('-100', '-500', $config['class'])) }} mr-1.5"></span>
                    {{ $config['label'] }}
                </span>
                
                @if($project->priority)
                    @php
                        $priorityConfig = [
                            'low' => ['class' => 'bg-info-100 text-info-800', 'icon' => 'fa-arrow-down'],
                            'medium' => ['class' => 'bg-warning-100 text-warning-800', 'icon' => 'fa-minus'],
                            'high' => ['class' => 'bg-orange-100 text-orange-800', 'icon' => 'fa-arrow-up'],
                            'urgent' => ['class' => 'bg-error-100 text-error-800', 'icon' => 'fa-exclamation'],
                        ];
                        $pConfig = $priorityConfig[$project->priority] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'fa-circle'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $pConfig['class'] }}">
                        <i class="fas {{ $pConfig['icon'] }} mr-1.5 text-xs"></i>
                        {{ ucfirst($project->priority) }} Priority
                    </span>
                @endif
            </div>
            
            <h1 class="text-2xl font-semibold text-primary-600 mb-1">{{ $project->title }}</h1>
            
            <div class="text-neutral-500 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>
                <span>Created {{ $project->created_at->format('F d, Y') }}</span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <a href="{{ route('admin.projects.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Projects
            </a>
            
            @if($project->status !== 'completed')
                <button onclick="showCompleteModal()"
                        class="inline-flex items-center px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-check-circle mr-2"></i>Mark as Completed
                </button>
            @endif
            
            <a href="{{ route('admin.projects.edit', $project->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Project
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Project Details -->
        <div class="lg:col-span-2">
            <!-- Project Details Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Project Details</h2>
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
            </div>

                <!-- Budget Overview Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-neutral-200">
                        <h2 class="text-lg font-semibold text-primary-500">Budget Overview</h2>
                        @if($project->serviceRequest && ($project->serviceRequest->coupon_discount_amount > 0 || $project->serviceRequest->loyalty_discount_amount > 0))
                            <div class="mt-2 text-sm text-neutral-600">
                                <p class="flex items-center gap-2">
                                    <span class="text-neutral-500">Original Budget:</span>
                                    <span class="line-through text-neutral-400">₱{{ number_format($project->serviceRequest->getOriginalBudget(), 2) }}</span>
                                </p>
                                @if($project->serviceRequest->coupon_discount_amount > 0)
                                    <p class="flex items-center gap-2 text-success-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        <span>Coupon Discount:</span>
                                        <span class="font-semibold">-₱{{ number_format($project->serviceRequest->coupon_discount_amount, 2) }}</span>
                                    </p>
                                @endif
                                @if($project->serviceRequest->loyalty_discount_amount > 0)
                                    <p class="flex items-center gap-2 text-primary-600">
                                        <i class="fas fa-medal"></i>
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
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-primary-50 rounded-lg">
                                <p class="text-sm text-primary-600 font-medium mb-1">Project Budget</p>
                                <p class="text-2xl font-bold text-primary-900">₱{{ number_format($budgetOverview['project_budget'], 2) }}</p>
                                @if($project->serviceRequest && ($project->serviceRequest->coupon_discount_amount > 0 || $project->serviceRequest->loyalty_discount_amount > 0))
                                    <p class="text-xs text-neutral-500 mt-1">(After discounts)</p>
                                @endif
                            </div>
                            <div class="text-center p-4 bg-warning-50 rounded-lg">
                                <p class="text-sm text-warning-600 font-medium mb-1">Allocated</p>
                                <p class="text-2xl font-bold text-warning-900">₱{{ number_format($budgetOverview['total_allocated'], 2) }}</p>
                            </div>
                            <div class="text-center p-4 bg-error-50 rounded-lg">
                                <p class="text-sm text-error-600 font-medium mb-1">Spent</p>
                                <p class="text-2xl font-bold text-error-900">₱{{ number_format($budgetOverview['total_spent'], 2) }}</p>
                            </div>
                            <div class="text-center p-4 bg-success-50 rounded-lg">
                                <p class="text-sm text-success-600 font-medium mb-1">Remaining</p>
                                <p class="text-2xl font-bold {{ $budgetOverview['is_over_budget'] ? 'text-error-900' : 'text-success-900' }}">
                                    ₱{{ number_format($budgetOverview['remaining_budget'], 2) }}
                                </p>
                            </div>
                        </div>

                        <!-- Budget Utilization Bar -->
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-neutral-600">Budget Utilization</span>
                                <span class="font-semibold {{ $budgetOverview['is_over_budget'] ? 'text-error-600' : 'text-neutral-900' }}">
                                    {{ $budgetOverview['budget_utilization_percentage'] }}%
                                </span>
                            </div>
                            <div class="w-full bg-neutral-200 rounded-full h-3">
                                <div class="h-3 rounded-full {{ $budgetOverview['is_over_budget'] ? 'bg-error-600' : 'bg-success-600' }}" 
                                     style="width: {{ min($budgetOverview['budget_utilization_percentage'], 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Section -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-primary-500">Project Tasks</h2>
                        <a href="{{ route('admin.tasks.create', ['project_id' => $project->id]) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Task
                        </a>
                    </div>
                    <div class="p-6">
                        @if($project->tasks->count() > 0)
                            <div class="space-y-3">
                                @foreach($project->tasks as $task)
                                <div class="border border-neutral-200 rounded-lg p-4 hover:bg-neutral-50 transition">
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
                                                <span>
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $task->assignedUser->fullName ?? 'Unassigned' }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-dollar-sign mr-1"></i>
                                                    ₱{{ number_format($task->allocated_budget ?? 0, 2) }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No deadline' }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.tasks.show', $task->taskID) }}" 
                                           class="ml-4 text-primary-600 hover:text-primary-700 text-sm font-medium">
                                            View →
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-tasks text-neutral-400 text-xl"></i>
                                </div>
                                <h3 class="text-neutral-500 text-base">No tasks created</h3>
                                <p class="text-neutral-400 text-sm mt-1">There are no tasks associated with this project yet</p>
                                <a href="{{ route('admin.tasks.create', ['project_id' => $project->id]) }}" 
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Create First Task
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Client Info, Related Service Request, Team -->
            <div class="lg:col-span-1">
                <!-- Client Information Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-neutral-200">
                        <h2 class="text-lg font-semibold text-primary-500">Client Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="bg-primary-100 h-12 w-12 rounded-full flex items-center justify-center text-primary-600 mr-4">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-neutral-900">{{ $project->client->fullName }}</h3>
                                <p class="text-sm text-neutral-500">{{ $project->client->email }}</p>
                            </div>
                        </div>
                        
                        @if($project->client->phone)
                        <div class="space-y-3 mb-6">
                            <div class="flex">
                                <div class="w-8 flex-shrink-0 text-neutral-400">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-neutral-500">Phone</div>
                                    <div class="text-neutral-900">{{ $project->client->phone }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="pt-4 border-t border-neutral-100">
                            <a href="{{ route('admin.clients.show', $project->client->id) }}" 
                               class="inline-flex items-center text-primary-600 hover:text-primary-700">
                                <span>View Client Profile</span>
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Related Service Request -->
                @if($project->serviceRequest)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-neutral-200">
                        <h2 class="text-lg font-semibold text-primary-500">Related Service Request</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <div class="flex">
                                <div class="w-32 text-neutral-500">Request ID:</div>
                                <div class="flex-1 text-neutral-900 font-medium">#{{ $project->serviceRequest->id }}</div>
                            </div>
                            <div class="flex">
                                <div class="w-32 text-neutral-500">Service Type:</div>
                                <div class="flex-1 text-neutral-900">{{ $project->serviceRequest->service_type ?? 'N/A' }}</div>
                            </div>
                            <div class="flex">
                                <div class="w-32 text-neutral-500">Submitted:</div>
                                <div class="flex-1 text-neutral-900">{{ $project->serviceRequest->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-neutral-100">
                            <a href="{{ route('admin.requests.show', $project->serviceRequest->id) }}" 
                               class="inline-flex items-center text-primary-600 hover:text-primary-700">
                                <span>View Request Details</span>
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Assigned Team -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-primary-500">Assigned Team</h2>
                        <button onclick="showAssignModal()"
                                type="button"
                                class="inline-flex items-center px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>Assign Adiutor
                        </button>
                    </div>
                    <div class="p-6">
                        @if($project->adiutors->count() > 0)
                            <div class="space-y-3">
                                @foreach($project->adiutors as $adiutor)
                                <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                            {{ substr($adiutor->fullName, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-neutral-900">{{ $adiutor->fullName }}</p>
                                            <p class="text-xs text-neutral-500">{{ $adiutor->email }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.projects.remove-adiutor', [$project->id, $adiutor->id]) }}" 
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to remove this adiutor from the project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-error-600 hover:text-error-700 text-sm font-medium transition-colors">
                                            <i class="fas fa-times mr-1"></i>Remove
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <div class="bg-neutral-50 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-users text-neutral-400"></i>
                                </div>
                                <p class="text-neutral-500 text-sm">No team members assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Project Modal -->
    <div id="completeModal" class="modal-overlay fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div onclick="hideCompleteModal()" class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity z-40"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="modal-content inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
                
                <form action="{{ route('admin.projects.complete', $project->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-neutral-800">Complete Project</h5>
                        <button type="button" onclick="hideCompleteModal()" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-success-100 flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-success-600 text-xl"></i>
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
                                      class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                      placeholder="Add any final notes about the project completion..."></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                        <button type="button"
                                onclick="hideCompleteModal()"
                                class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 mr-3">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>Complete Project
                        </button>
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

            <div class="modal-content inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full relative z-50">
                
                <form action="{{ route('admin.projects.assign-adiutor', $project->id) }}" method="POST" id="assignAdiutorForm">
                    @csrf
                    <input type="hidden" name="adiutor_id" id="adiutor_id" required>
                    
                    <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-neutral-800">Assign Adiutor to Project</h5>
                        <button type="button" onclick="hideAssignModal()" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                            <i class="fas fa-times"></i>
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
                                                        <div class="text-xs text-neutral-500">
                                                            @for($i = 0; $i < 5; $i++)
                                                                @if($i < floor($adiutor['rating']))
                                                                    <i class="fas fa-star text-yellow-400"></i>
                                                                @elseif($i < $adiutor['rating'])
                                                                    <i class="fas fa-star-half-alt text-yellow-400"></i>
                                                                @else
                                                                    <i class="far fa-star text-neutral-300"></i>
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
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Connected
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                                        <i class="fas fa-times-circle mr-1"></i>
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
                                                        $workloadIcon = 'fa-circle';
                                                    } elseif ($projectCount <= 3) {
                                                        $workloadClass = 'bg-warning-100 text-warning-800';
                                                        $workloadIcon = 'fa-circle';
                                                    } else {
                                                        $workloadClass = 'bg-error-100 text-error-800';
                                                        $workloadIcon = 'fa-circle';
                                                    }
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $workloadClass }}">
                                                    <i class="fas {{ $workloadIcon }} mr-1.5 text-xs"></i>
                                                    {{ $projectCount }} {{ Str::plural('project', $projectCount) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                                <button type="button" 
                                                        onclick="event.stopPropagation(); viewAdiutorSchedule({{ $adiutor['id'] }}, '{{ addslashes($adiutor['fullName']) }}')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-primary-300 rounded-md text-xs font-medium text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                                    <i class="far fa-calendar-alt mr-1.5"></i>
                                                    View Schedule
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-neutral-500">
                                                <i class="fas fa-users text-3xl mb-2"></i>
                                                <p>No adiutors available</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div id="selected_adiutor_info" class="mt-4 hidden">
                                <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                                    <p class="text-sm text-primary-800">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <strong>Selected:</strong> <span id="selected_name"></span>
                                        <span id="selected_rating" class="ml-2"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="requires_time_tracking" value="1"
                                       class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm font-medium text-neutral-700">Require time tracking for this project</span>
                            </label>
                            <p class="text-xs text-neutral-500 mt-1 ml-6">Adiutor must log time entries for hourly payment</p>
                        </div>
                        
                        <div id="hourly_rate_container">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Hourly Rate (₱)
                            </label>
                            <input type="number" name="hourly_rate" id="hourly_rate_input" step="0.01" min="0"
                                   class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                   placeholder="Leave empty to use adiutor's standard rate">
                            <p class="text-xs text-neutral-500 mt-1">
                                <span id="standard_rate_display" class="font-medium text-primary-600"></span>
                                Override the adiutor's standard rate for this project
                            </p>
                            <div id="hourly_rate_warning" class="hidden mt-2 p-2 bg-warning-50 border border-warning-300 rounded-lg">
                                <p class="text-xs text-warning-700 flex items-start">
                                    <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                                    <span>Warning: The overridden hourly rate is below the adiutor's standard rate.</span>
                                </p>
                            </div>
                        </div>
                        
                        <div id="agreed_rate_container">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Agreed Rate (₱)
                            </label>
                            <input type="number" name="agreed_rate" step="0.01" min="0"
                                   class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                   placeholder="e.g., 5000.00">
                            <p class="text-xs text-neutral-500 mt-1">Fixed project rate or budget cap (optional)</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Expected Completion Date
                            </label>
                            <input type="date" name="expected_completion"
                                   class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea name="notes" rows="3"
                                      class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                      placeholder="Additional notes about this assignment..."></textarea>
                        </div>
                    </div>
                    
                    <div class="bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end gap-3">
                        <button type="button" 
                                onclick="hideAssignModal()"
                                class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-user-plus mr-2"></i>Assign Adiutor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Schedule View Modal -->
<div id="scheduleModal" class="modal-overlay fixed inset-0 bg-neutral-900 bg-opacity-50 hidden flex items-center justify-center z-50" onclick="hideScheduleModal()">
    <div class="modal-content bg-white rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
        <div class="sticky top-0 bg-white border-b border-neutral-200 px-6 py-4 flex items-center justify-between z-10">
            <div>
                <h2 class="text-xl font-bold text-neutral-900">
                    <i class="far fa-calendar-alt mr-2 text-primary-600"></i>
                    Schedule: <span id="schedule_adiutor_name" class="text-primary-600"></span>
                </h2>
                <p class="text-sm text-neutral-500 mt-1">
                    Week: <span id="schedule_week_range"></span>
                </p>
            </div>
            <button onclick="hideScheduleModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-6">
            <!-- Week Navigation -->
            <div class="flex items-center justify-between mb-6">
                <button onclick="navigateWeek('prev')" class="px-4 py-2 bg-white border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors">
                    <i class="fas fa-chevron-left mr-2"></i>
                    Previous Week
                </button>
                <button onclick="navigateWeek('today')" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-calendar-day mr-2"></i>
                    Today
                </button>
                <button onclick="navigateWeek('next')" class="px-4 py-2 bg-white border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors">
                    Next Week
                    <i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>

            <!-- Calendar Grid -->
            <div class="overflow-x-auto border border-neutral-200 rounded-lg">
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
            <div class="mt-6 bg-neutral-50 border border-neutral-200 rounded-lg p-4">
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

        <div class="sticky bottom-0 bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end gap-3">
            <button onclick="hideScheduleModal()" class="px-4 py-2 bg-white border border-neutral-300 text-neutral-700 rounded-lg hover:bg-neutral-100 transition-colors">
                Close
            </button>
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
}

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

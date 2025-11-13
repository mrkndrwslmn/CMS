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
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-primary-50 rounded-lg">
                                <p class="text-sm text-primary-600 font-medium mb-1">Project Budget</p>
                                <p class="text-2xl font-bold text-primary-900">₱{{ number_format($budgetOverview['project_budget'], 2) }}</p>
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

            <div class="modal-content inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
                
                <form action="{{ route('admin.projects.assign-adiutor', $project->id) }}" method="POST">
                    @csrf
                    
                    <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4 flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-neutral-800">Assign Adiutor to Project</h5>
                        <button type="button" onclick="hideAssignModal()" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">
                                Select Adiutor <span class="text-error-500">*</span>
                            </label>
                            <select name="adiutor_id" id="adiutor_select" required
                                    class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <option value="">-- Select Adiutor --</option>
                                @foreach($availableAdiutors as $adiutor)
                                    <option value="{{ $adiutor->id }}">{{ $adiutor->fullName }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
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
                        </div>
                        
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="requires_time_tracking" value="1"
                                       class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm font-medium text-neutral-700">Require time tracking for this project</span>
                            </label>
                            <p class="text-xs text-neutral-500 mt-1 ml-6">Adiutor must log time entries for hourly payment</p>
                        </div>
                        
                        <div>
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
    // Add smooth animation
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
}

function hideAssignModal() {
    const modal = document.getElementById('assignModal');
    modal.classList.add('hidden');
    // Reset form
    document.getElementById('hourly_rate_input').value = '';
    document.getElementById('standard_rate_display').textContent = '';
}

// Auto-fill hourly rate when adiutor is selected
document.addEventListener('DOMContentLoaded', function() {
    const adiutorSelect = document.getElementById('adiutor_select');
    const hourlyRateInput = document.getElementById('hourly_rate_input');
    const standardRateDisplay = document.getElementById('standard_rate_display');
    
    if (adiutorSelect) {
        adiutorSelect.addEventListener('change', function() {
            const adiutorId = this.value;
            
            if (adiutorId) {
                // Fetch adiutor's standard rate
                fetch(`/api/adiutor/${adiutorId}/rate`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.rate && data.rate > 0) {
                            standardRateDisplay.textContent = `Standard Rate: ₱${parseFloat(data.rate).toFixed(2)}/hr - `;
                            
                            // Auto-fill if field is empty
                            if (!hourlyRateInput.value) {
                                hourlyRateInput.value = data.rate;
                            }
                        } else {
                            standardRateDisplay.textContent = 'No standard rate set - ';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching rate:', error);
                        standardRateDisplay.textContent = '';
                    });
            } else {
                standardRateDisplay.textContent = '';
                hourlyRateInput.value = '';
            }
        });
    }
});


// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideCompleteModal();
        hideAssignModal();
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
});
</script>

@endsection

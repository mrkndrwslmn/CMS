@extends('admin.layouts.app')

@section('title', 'Create New Task')
@section('page-title', 'Create New Task')
@include('admin.tasks.helpers')

@section('content')
<div class="px-6 py-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-neutral-900">
                Create New Task
            </h1>
            <p class="text-neutral-500 mt-2">Fill in the details below to create a new task for your project</p>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.tasks.index') }}" 
               class="inline-flex items-center px-5 py-2.5 bg-white border border-neutral-300 hover:border-neutral-400 text-neutral-700 rounded-lg transition-all shadow-sm hover:shadow">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Tasks
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-4 rounded-lg shadow-sm mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-success-500 text-xl mr-3"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-error-50 border-l-4 border-error-500 text-error-700 p-4 rounded-lg shadow-sm mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-error-500 text-xl mr-3"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <form action="{{ route('admin.tasks.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Project Selection Section -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-project-diagram text-primary-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900">Project & Assignment</h2>
                        <p class="text-sm text-neutral-500">Select the project and assign team members</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Project Selection -->
                    <div class="md:col-span-2">
                        <label for="project_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Related Project <span class="text-error-500">*</span>
                        </label>
                        @if(isset($preSelectedProject) && $preSelectedProject)
                            <!-- Project is pre-selected and locked -->
                            <input type="hidden" name="project_id" value="{{ $preSelectedProject->id }}">
                            <div class="w-full rounded-lg border-2 border-primary-200 bg-primary-50 px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-lock text-primary-500 mr-3"></i>
                                        <div>
                                            <p class="font-semibold text-neutral-900">{{ $preSelectedProject->title }}</p>
                                            @if($preSelectedProject->client)
                                                <p class="text-sm text-neutral-600">Client: {{ $preSelectedProject->client->fullName }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-primary-600 text-white">
                                        <i class="fas fa-check-circle mr-1"></i> Auto-selected
                                    </span>
                                </div>
                            </div>
                            <p class="text-xs text-neutral-500 mt-2">
                                <i class="fas fa-info-circle text-primary-500"></i> 
                                Project is automatically selected from context
                            </p>
                        @else
                            <!-- Normal project selection -->
                            <select name="project_id" id="project_id" required
                                    class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('project_id') border-error-500 @enderror">
                                <option value="">Select a project...</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->title }} @if($project->client)({{ $project->client->fullName }})@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="text-error-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                    <!-- Phase Selection (Dynamic) -->
                    <div id="phaseFieldContainer" class="md:col-span-2" style="display: none;">
                        <label for="phase_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Project Phase <span class="text-error-500">*</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-secondary-100 text-secondary-700 ml-2">
                                <i class="fas fa-layer-group mr-1"></i> Milestone Payment
                            </span>
                        </label>
                        <select name="phase_id" id="phase_id" 
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('phase_id') border-error-500 @enderror"
                                disabled>
                            <option value="">Loading phases...</option>
                        </select>
                        @error('phase_id')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2">
                            <i class="fas fa-info-circle text-secondary-500"></i> 
                            This task will be associated with the selected project phase for milestone-based delivery
                        </p>
                    </div>
                    
                    <!-- Assign To -->
                    <div class="md:col-span-2">
                        <label for="assignedTo" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Assign To Team Member
                        </label>
                        <select name="assignedTo" id="assignedTo" 
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('assignedTo') border-error-500 @enderror"
                                disabled>
                            <option value="">Select project first...</option>
                        </select>
                        @error('assignedTo')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2">
                            <i class="fas fa-users text-neutral-400"></i> 
                            Only team members assigned to this project
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Details Section -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-accent-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clipboard-list text-accent-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900">Task Details</h2>
                        <p class="text-sm text-neutral-500">Define the task requirements and description</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Task Title -->
                    <div>
                        <label for="taskTitle" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Task Title <span class="text-error-500">*</span>
                        </label>
                        <input type="text" name="taskTitle" id="taskTitle" 
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('taskTitle') border-error-500 @enderror"
                               value="{{ old('taskTitle') }}" 
                               placeholder="Enter a clear, descriptive task title..."
                               required>
                        @error('taskTitle')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Task Description -->
                    <div>
                        <label for="taskDescription" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Task Description <span class="text-error-500">*</span>
                        </label>
                        <textarea name="taskDescription" id="taskDescription" rows="6" required
                                  class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('taskDescription') border-error-500 @enderror"
                                  placeholder="Provide detailed description of what needs to be accomplished...">{{ old('taskDescription') }}</textarea>
                        @error('taskDescription')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Notes (Optional) -->
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Additional Notes
                            <span class="text-xs font-normal text-neutral-500">(Optional)</span>
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('notes') border-error-500 @enderror"
                                  placeholder="Any additional context, requirements, or comments...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Settings Section -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-warning-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-sliders-h text-warning-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900">Task Settings</h2>
                        <p class="text-sm text-neutral-500">Configure status, priority, and timeline</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Status <span class="text-error-500">*</span>
                        </label>
                        <select name="status" id="status" required
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('status') border-error-500 @enderror">
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>
                                📋 Pending
                            </option>
                            <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>
                                🚀 In Progress
                            </option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>
                                ✅ Completed
                            </option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>
                                ❌ Cancelled
                            </option>
                        </select>
                        @error('status')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Priority <span class="text-error-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('priority') border-error-500 @enderror">
                            <option value="low" {{ old('priority', 'medium') === 'low' ? 'selected' : '' }}>
                                🟢 Low Priority
                            </option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>
                                🟡 Medium Priority
                            </option>
                            <option value="high" {{ old('priority', 'medium') === 'high' ? 'selected' : '' }}>
                                🟠 High Priority
                            </option>
                            <option value="urgent" {{ old('priority', 'medium') === 'urgent' ? 'selected' : '' }}>
                                🔴 Urgent
                            </option>
                        </select>
                        @error('priority')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label for="deadline" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Deadline
                        </label>
                        <input type="date" name="deadline" id="deadline" 
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('deadline') border-error-500 @enderror"
                               value="{{ old('deadline') }}">
                        @error('deadline')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Payment Configuration Section -->
                    <div class="md:col-span-2">
                        <div class="border-2 border-primary-100 rounded-lg p-4 bg-primary-50/30">
                            <h3 class="text-md font-semibold text-neutral-900 mb-3 flex items-center">
                                <i class="fas fa-dollar-sign text-primary-600 mr-2"></i>
                                Payment Configuration
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Payment Type Selection -->
                                <div>
                                    <label class="block text-sm font-semibold text-neutral-700 mb-3">
                                        Payment Type <span class="text-error-500">*</span>
                                    </label>
                                    
                                    <div class="space-y-3">
                                        <!-- Hourly with Time Tracking -->
                                        <label class="flex items-start p-3 border-2 border-neutral-200 rounded-lg cursor-pointer hover:border-primary-300 transition-colors">
                                            <input type="radio" name="payment_type" value="hourly" 
                                                   class="mt-1 w-4 h-4 text-primary-600 focus:ring-primary-500" checked>
                                            <div class="ml-3 flex-1">
                                                <span class="block text-sm font-semibold text-neutral-900">Hourly with Time Tracking</span>
                                                <span class="block text-xs text-neutral-600 mt-0.5">Adiutor tracks time, paid per hour worked</span>
                                            </div>
                                        </label>
                                        
                                        <!-- Fixed Budget -->
                                        <label class="flex items-start p-3 border-2 border-neutral-200 rounded-lg cursor-pointer hover:border-primary-300 transition-colors">
                                            <input type="radio" name="payment_type" value="fixed" 
                                                   class="mt-1 w-4 h-4 text-primary-600 focus:ring-primary-500">
                                            <div class="ml-3 flex-1">
                                                <span class="block text-sm font-semibold text-neutral-900">Fixed Budget</span>
                                                <span class="block text-xs text-neutral-600 mt-0.5">Fixed amount, no time tracking needed</span>
                                            </div>
                                        </label>
                                        
                                        <!-- No Payment -->
                                        <label class="flex items-start p-3 border-2 border-neutral-200 rounded-lg cursor-pointer hover:border-primary-300 transition-colors">
                                            <input type="radio" name="payment_type" value="none" 
                                                   class="mt-1 w-4 h-4 text-primary-600 focus:ring-primary-500">
                                            <div class="ml-3 flex-1">
                                                <span class="block text-sm font-semibold text-neutral-900">No Payment</span>
                                                <span class="block text-xs text-neutral-600 mt-0.5">Internal/volunteer work</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Hourly Rate Fields (shown for hourly payment type) -->
                                <div id="hourly_fields" class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="hourly_rate" class="block text-sm font-semibold text-neutral-700 mb-2">
                                            Hourly Rate (Optional)
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 font-medium">₱</span>
                                            <input type="number" name="hourly_rate" id="hourly_rate" step="0.01" min="0"
                                                   class="w-full rounded-lg border-2 border-neutral-300 pl-8 pr-4 py-2.5 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200"
                                                   placeholder="Uses adiutor's standard rate if empty">
                                        </div>
                                        <p class="text-xs text-neutral-500 mt-1">Overrides adiutor's standard rate</p>
                                    </div>
                                    
                                    <div>
                                        <label for="budget_cap" class="block text-sm font-semibold text-neutral-700 mb-2">
                                            Budget Cap (Optional)
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 font-medium">₱</span>
                                            <input type="number" name="budget_cap" id="budget_cap" step="0.01" min="0"
                                                   class="w-full rounded-lg border-2 border-neutral-300 pl-8 pr-4 py-2.5 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200"
                                                   placeholder="Maximum budget">
                                        </div>
                                        <p class="text-xs text-neutral-500 mt-1">Maximum amount for this task</p>
                                    </div>
                                    
                                    <div class="col-span-2">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="requires_time_tracking" value="1" checked
                                                   class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                            <span class="text-sm font-medium text-neutral-700">Require time tracking</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Fixed Budget Fields (hidden by default) -->
                                <div id="fixed_fields" class="hidden">
                                    <label for="fixed_budget" class="block text-sm font-semibold text-neutral-700 mb-2">
                                        Fixed Budget Amount <span class="text-error-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 font-medium">₱</span>
                                        <input type="number" name="fixed_budget" id="fixed_budget" step="0.01" min="0"
                                               class="w-full rounded-lg border-2 border-neutral-300 pl-8 pr-4 py-2.5 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200"
                                               placeholder="5000.00">
                                    </div>
                                    <p class="text-xs text-neutral-500 mt-1">Fixed amount paid upon completion</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Allocated Budget -->
                    <div>
                        <label for="allocated_budget" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Allocated Budget
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 font-medium">₱</span>
                            <input type="number" name="allocated_budget" id="allocated_budget" step="0.01" min="0"
                                   class="w-full rounded-lg border-2 border-neutral-300 pl-8 pr-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('allocated_budget') border-error-500 @enderror"
                                   value="{{ old('allocated_budget') }}" placeholder="0.00">
                        </div>
                        @error('allocated_budget')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Completed Date -->
                    <div>
                        <label for="completedAt" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Completed Date
                        </label>
                        <input type="date" name="completedAt" id="completedAt" 
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('completedAt') border-error-500 @enderror"
                               value="{{ old('completedAt') }}">
                        @error('completedAt')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2">
                            <i class="fas fa-info-circle text-neutral-400"></i> 
                            Leave blank if task is not completed yet
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between pt-4">
            <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center px-6 py-3 bg-white border-2 border-neutral-300 hover:border-neutral-400 text-neutral-700 font-medium rounded-lg transition-all shadow-sm hover:shadow">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </button>
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-plus-circle mr-2"></i>
                Create Task
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const assignedToSelect = document.getElementById('assignedTo');
        const phaseSelect = document.getElementById('phase_id');
        const phaseFieldContainer = document.getElementById('phaseFieldContainer');
        
        // Check for both hidden input (pre-selected) and select dropdown
        const preSelectedProjectInput = document.querySelector('input[name="project_id"][type="hidden"]');
        const projectSelect = document.getElementById('project_id'); // This will be null if pre-selected
        
        const preSelectedProjectId = preSelectedProjectInput ? preSelectedProjectInput.value : null;
        
        // Function to load team members for a project
        function loadTeamMembers(projectId) {
            if (!projectId || !assignedToSelect) {
                return;
            }
            
            // Show loading state
            assignedToSelect.innerHTML = '<option value="">Loading team members...</option>';
            assignedToSelect.disabled = true;
            
            // Fetch team members for this project
            fetch(`/admin/projects/${projectId}/team-members`)
                .then(response => response.json())
                .then(data => {
                    assignedToSelect.innerHTML = '<option value="">-- Not Assigned --</option>';
                    
                    if (data.length === 0) {
                        assignedToSelect.innerHTML += '<option value="" disabled>⚠️ No team members assigned to this project</option>';
                    } else {
                        data.forEach(member => {
                            assignedToSelect.innerHTML += `<option value="${member.id}">${member.fullName}</option>`;
                        });
                    }
                    
                    assignedToSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching team members:', error);
                    assignedToSelect.innerHTML = '<option value="">-- Error loading team members --</option>';
                    assignedToSelect.disabled = true;
                });
        }
        
        // Function to load project phases
        function loadProjectPhases(projectId) {
            if (!projectId || !phaseSelect || !phaseFieldContainer) return;
            
            // Show loading state
            phaseSelect.innerHTML = '<option value="">Loading phases...</option>';
            phaseSelect.disabled = true;
            
            // Fetch phases for this project
            fetch(`/admin/projects/${projectId}/phases`)
                .then(response => response.json())
                .then(data => {
                    if (data.hasMilestones && data.phases.length > 0) {
                        // Show phase field
                        phaseFieldContainer.style.display = 'block';
                        
                        // Populate phases
                        phaseSelect.innerHTML = '<option value="">-- Select Phase --</option>';
                        
                        data.phases.forEach(phase => {
                            const statusBadge = phase.is_paid ? '✓ Paid' : 
                                               phase.status === 'in_progress' ? '⚡ Current' : 
                                               phase.status === 'completed' ? '✓ Completed' : 
                                               'Pending';
                            const option = document.createElement('option');
                            option.value = phase.id;
                            option.textContent = `${phase.phase_name} (${statusBadge})`;
                            
                            // Auto-select current phase
                            if (phase.id === data.currentPhaseId) {
                                option.selected = true;
                            }
                            
                            phaseSelect.appendChild(option);
                        });
                        
                        phaseSelect.disabled = false;
                        
                        // Make phase required
                        phaseSelect.setAttribute('required', 'required');
                    } else {
                        // Hide phase field - not a milestone payment project
                        phaseFieldContainer.style.display = 'none';
                        phaseSelect.innerHTML = '<option value="">-- Not Applicable --</option>';
                        phaseSelect.disabled = true;
                        phaseSelect.removeAttribute('required');
                        phaseSelect.value = ''; // Clear value so it won't be submitted
                    }
                })
                .catch(error => {
                    console.error('Error fetching phases:', error);
                    phaseFieldContainer.style.display = 'none';
                    phaseSelect.innerHTML = '<option value="">-- Error loading phases --</option>';
                    phaseSelect.disabled = true;
                    phaseSelect.removeAttribute('required');
                });
        }
        
        // If project is pre-selected via hidden input, load its team members and phases immediately
        if (preSelectedProjectId) {
            loadTeamMembers(preSelectedProjectId);
            loadProjectPhases(preSelectedProjectId);
        }
        
        // Dynamic team member and phase loading based on project selection
        if (projectSelect) {
            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                
                if (!projectId) {
                    // Reset team members
                    if (assignedToSelect) {
                        assignedToSelect.innerHTML = '<option value="">-- Select Project First --</option>';
                        assignedToSelect.disabled = true;
                    }
                    
                    // Reset and hide phases
                    if (phaseSelect && phaseFieldContainer) {
                        phaseFieldContainer.style.display = 'none';
                        phaseSelect.innerHTML = '<option value="">-- Select Project First --</option>';
                        phaseSelect.disabled = true;
                        phaseSelect.removeAttribute('required');
                    }
                    return;
                }
                
                // Load team members and phases
                loadTeamMembers(projectId);
                loadProjectPhases(projectId);
            });
            
            // Disable controls initially if no project is selected
            if (!projectSelect.value && !preSelectedProjectId) {
                if (assignedToSelect) assignedToSelect.disabled = true;
                if (phaseSelect) phaseSelect.disabled = true;
            }
        }
        
        // Auto-update status based on completed date
        const completedAtInput = document.getElementById('completedAt');
        const statusSelect = document.getElementById('status');
        
        if (completedAtInput && statusSelect) {
            completedAtInput.addEventListener('change', function() {
                if (this.value) {
                    // If completed date is set, change status to completed
                    statusSelect.value = 'completed';
                }
            });
            
            statusSelect.addEventListener('change', function() {
                if (this.value === 'completed' && !completedAtInput.value) {
                    // If status is completed but no completion date, set to today
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    completedAtInput.value = `${yyyy}-${mm}-${dd}`;
                } else if (this.value !== 'completed') {
                    // If status is not completed, clear completion date
                    completedAtInput.value = '';
                }
            });
        }
        
        // Payment type toggle functionality
        const paymentTypeRadios = document.querySelectorAll('input[name="payment_type"]');
        const hourlyFields = document.getElementById('hourly_fields');
        const fixedFields = document.getElementById('fixed_fields');
        
        function togglePaymentFields() {
            const selectedType = document.querySelector('input[name="payment_type"]:checked').value;
            
            if (selectedType === 'hourly') {
                hourlyFields.style.display = 'grid';
                fixedFields.style.display = 'none';
            } else if (selectedType === 'fixed') {
                hourlyFields.style.display = 'none';
                fixedFields.style.display = 'block';
            } else {
                hourlyFields.style.display = 'none';
                fixedFields.style.display = 'none';
            }
        }
        
        paymentTypeRadios.forEach(radio => {
            radio.addEventListener('change', togglePaymentFields);
        });
        
        // Initialize on page load
        togglePaymentFields();
    });
</script>
@endpush
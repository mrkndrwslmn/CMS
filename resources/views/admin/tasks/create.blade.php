@extends('admin.layouts.app')

@section('title', 'Create New Task')
@section('page-title', 'Create New Task')
@include('admin.tasks.helpers')

@section('content')
<div class="min-h-screen">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Tasks', 'url' => route('admin.tasks.index'), 'icon' => 'list-checks'],
        ['label' => 'Create Task', 'icon' => 'plus']
    ]" class="mb-4" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Create New Task" 
        subtitle="Fill in the details to create a new task"
        class="mb-6"
    >
        <x-slot name="actions">
            <x-ui.button href="{{ route('admin.tasks.index') }}" variant="secondary">
                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                Back
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Main Content -->
    <div class="max-w-8xl mx-auto">
        <form action="{{ route('admin.tasks.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <!-- Main Form Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Project & Assignment Card -->
                <x-ui.card>
                    <div class="px-6 py-4 border-b border-neutral-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                                <x-lucide-folder-kanban class="w-4 h-4 text-primary-600" />
                            </div>
                            <h2 class="text-lg font-semibold text-neutral-900">Project & Assignment</h2>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Project Selection -->
                        @if(isset($preSelectedProject) && $preSelectedProject)
                            <input type="hidden" name="project_id" value="{{ $preSelectedProject->id }}">
                            <div class="flex items-center justify-between p-4 bg-primary-50 border border-primary-200 rounded-lg">
                                <div class="flex items-center flex-1">
                                    <x-lucide-lock class="w-5 h-5 text-primary-500 mr-3" />
                                    <div>
                                        <p class="font-semibold text-neutral-900 text-sm">{{ $preSelectedProject->title }}</p>
                                        @if($preSelectedProject->client)
                                            <p class="text-xs text-neutral-600">{{ $preSelectedProject->client->fullName }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-xs font-bold px-2.5 py-1 bg-primary-600 text-white rounded-full">Auto-selected</span>
                            </div>
                        @else
                            <div>
                                <label for="project_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                                    Related Project <span class="text-error-500">*</span>
                                </label>
                                <select name="project_id" id="project_id" required
                                        class="w-full px-4 py-2.5 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('project_id') border-error-500 @enderror">
                                    <option value="">Select a project...</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->title }} @if($project->client)({{ $project->client->fullName }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <p class="text-error-500 text-xs mt-1.5 flex items-center">
                                        <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        @endif

                        <!-- Assign To -->
                        <div>
                            <label for="assignedTo" class="block text-sm font-semibold text-neutral-700 mb-2">
                                Assign To
                            </label>
                            <select name="assignedTo" id="assignedTo" 
                                    class="w-full px-4 py-2.5 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('assignedTo') border-error-500 @enderror"
                                    disabled>
                                <option value="">Select project first...</option>
                            </select>
                            @error('assignedTo')
                                <p class="text-error-500 text-xs mt-1.5 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phase Selection (Dynamic) -->
                        <div id="phaseFieldContainer" style="display: none;">
                            <label for="phase_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                                Project Phase <span class="inline-text-xs font-medium text-secondary-700 ml-2">Milestone</span>
                            </label>
                            <select name="phase_id" id="phase_id" 
                                    class="w-full px-4 py-2.5 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('phase_id') border-error-500 @enderror"
                                    disabled>
                                <option value="">Loading phases...</option>
                            </select>
                            @error('phase_id')
                                <p class="text-error-500 text-xs mt-1.5 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>

                <!-- Task Details Card -->
                <x-ui.card>
                    <div class="px-6 py-4 border-b border-neutral-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-accent-100 rounded-lg flex items-center justify-center mr-3">
                                <x-lucide-clipboard-list class="w-4 h-4 text-accent-600" />
                            </div>
                            <h2 class="text-lg font-semibold text-neutral-900">Task Details</h2>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <!-- Task Title -->
                        <div>
                            <label for="taskTitle" class="block text-sm font-semibold text-neutral-700 mb-2">
                                Task Title <span class="text-error-500">*</span>
                            </label>
                            <input type="text" name="taskTitle" id="taskTitle" 
                                   class="w-full px-4 py-2.5 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('taskTitle') border-error-500 @enderror"
                                   value="{{ old('taskTitle') }}" 
                                   placeholder="Clear and descriptive task name..."
                                   required>
                            @error('taskTitle')
                                <p class="text-error-500 text-xs mt-1.5 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Task Description -->
                        <div>
                            <label for="taskDescription" class="block text-sm font-semibold text-neutral-700 mb-2">
                                Description <span class="text-error-500">*</span>
                            </label>
                            <textarea name="taskDescription" id="taskDescription" rows="4" required
                                      class="w-full px-4 py-2.5 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all resize-none @error('taskDescription') border-error-500 @enderror"
                                      placeholder="What needs to be accomplished?">{{ old('taskDescription') }}</textarea>
                            @error('taskDescription')
                                <p class="text-error-500 text-xs mt-1.5 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-semibold text-neutral-700 mb-2">
                                Notes <span class="text-xs font-normal text-neutral-500">(Optional)</span>
                            </label>
                            <textarea name="notes" id="notes" rows="2"
                                      class="w-full px-4 py-2.5 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all resize-none @error('notes') border-error-500 @enderror"
                                      placeholder="Any additional context...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-error-500 text-xs mt-1.5 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Settings Sidebar -->
            <div class="lg:col-span-1">
                <x-ui.card class="sticky top-24">
                    <!-- Settings Header -->
                    <div class="px-6 py-4 border-b border-neutral-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-warning-100 rounded-lg flex items-center justify-center mr-3">
                                <x-lucide-sliders-horizontal class="w-4 h-4 text-warning-600" />
                            </div>
                            <h2 class="text-lg font-semibold text-neutral-900">Settings</h2>
                        </div>
                    </div>

                    <!-- Settings Content -->
                    <div class="p-6 space-y-4">
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-xs font-semibold text-neutral-700 uppercase tracking-wide mb-2">
                                Status
                            </label>
                            <select name="status" id="status" required
                                    class="w-full px-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('status') border-error-500 @enderror">
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="text-error-500 text-xs mt-1 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-xs font-semibold text-neutral-700 uppercase tracking-wide mb-2">
                                Priority
                            </label>
                            <select name="priority" id="priority" required
                                    class="w-full px-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('priority') border-error-500 @enderror">
                                <option value="low" {{ old('priority', 'medium') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', 'medium') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', 'medium') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <p class="text-error-500 text-xs mt-1 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-neutral-100 my-3"></div>

                        <!-- Starting Date -->
                        <div>
                            <label for="starting_date" class="block text-xs font-semibold text-neutral-700 uppercase tracking-wide mb-2">
                                Start Date
                            </label>
                            <input type="date" name="starting_date" id="starting_date" 
                                   class="w-full px-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('starting_date') border-error-500 @enderror"
                                   value="{{ old('starting_date') }}">
                            @error('starting_date')
                                <p class="text-error-500 text-xs mt-1 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div>
                            <label for="deadline" class="block text-xs font-semibold text-neutral-700 uppercase tracking-wide mb-2">
                                Deadline
                            </label>
                            <input type="date" name="deadline" id="deadline" 
                                   class="w-full px-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('deadline') border-error-500 @enderror"
                                   value="{{ old('deadline') }}">
                            @error('deadline')
                                <p class="text-error-500 text-xs mt-1 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Max Hours -->
                        <div>
                            <label for="max_hours" class="block text-xs font-semibold text-neutral-700 uppercase tracking-wide mb-2">
                                Max Hours
                            </label>
                            <div class="relative">
                                <input type="number" name="max_hours" id="max_hours" step="0.5" min="0"
                                       class="w-full px-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('max_hours') border-error-500 @enderror"
                                       value="{{ old('max_hours') }}" placeholder="0.0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 text-xs">hrs</span>
                            </div>
                            @error('max_hours')
                                <p class="text-error-500 text-xs mt-1 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Allocated Budget -->
                        <div>
                            <label for="allocated_budget" class="block text-xs font-semibold text-neutral-700 uppercase tracking-wide mb-2">
                                Budget
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-sm font-medium">₱</span>
                                <input type="number" name="allocated_budget" id="allocated_budget" step="0.01" min="0"
                                       class="w-full pl-7 pr-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('allocated_budget') border-error-500 @enderror"
                                       value="{{ old('allocated_budget') }}" placeholder="0.00">
                            </div>
                            @error('allocated_budget')
                                <p class="text-error-500 text-xs mt-1 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Completed Date -->
                        <div>
                            <label for="completedAt" class="block text-xs font-semibold text-neutral-700 uppercase tracking-wide mb-2">
                                Completed Date
                            </label>
                            <input type="date" name="completedAt" id="completedAt" 
                                   class="w-full px-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('completedAt') border-error-500 @enderror"
                                   value="{{ old('completedAt') }}">
                            @error('completedAt')
                                <p class="text-error-500 text-xs mt-1 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Settings Footer with Action Buttons -->
                    <div class="px-6 py-4 border-t border-neutral-100 bg-neutral-50 space-y-3 rounded-b-2xl">
                        <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                            <x-lucide-plus-circle class="w-4 h-4 mr-2" />
                            Create Task
                        </x-ui.button>
                        <x-ui.button type="button" variant="secondary" onclick="window.history.back()" class="w-full justify-center">
                            <x-lucide-x class="w-4 h-4 mr-2" />
                            Cancel
                        </x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const assignedToSelect = document.getElementById('assignedTo');
        const phaseSelect = document.getElementById('phase_id');
        const phaseFieldContainer = document.getElementById('phaseFieldContainer');
        
        const preSelectedProjectInput = document.querySelector('input[name="project_id"][type="hidden"]');
        const projectSelect = document.getElementById('project_id');
        
        const preSelectedProjectId = preSelectedProjectInput ? preSelectedProjectInput.value : null;
        
        function loadTeamMembers(projectId) {
            if (!projectId || !assignedToSelect) return;
            
            assignedToSelect.innerHTML = '<option value="">Loading...</option>';
            assignedToSelect.disabled = true;
            
            fetch(`/admin/projects/${projectId}/team-members`)
                .then(response => response.json())
                .then(data => {
                    assignedToSelect.innerHTML = '<option value="">-- Not Assigned --</option>';
                    
                    if (data.length === 0) {
                        assignedToSelect.innerHTML += '<option value="" disabled>⚠️ No team members</option>';
                    } else {
                        data.forEach(member => {
                            assignedToSelect.innerHTML += `<option value="${member.id}">${member.fullName}</option>`;
                        });
                    }
                    
                    assignedToSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching team members:', error);
                    assignedToSelect.innerHTML = '<option value="">-- Error loading --</option>';
                    assignedToSelect.disabled = true;
                });
        }
        
        function loadProjectPhases(projectId) {
            if (!projectId || !phaseSelect || !phaseFieldContainer) return;
            
            phaseSelect.innerHTML = '<option value="">Loading...</option>';
            phaseSelect.disabled = true;
            
            fetch(`/admin/projects/${projectId}/phases`)
                .then(response => response.json())
                .then(data => {
                    if (data.hasMilestones && data.phases.length > 0) {
                        phaseFieldContainer.style.display = 'block';
                        phaseSelect.innerHTML = '<option value="">-- Select Phase --</option>';
                        
                        data.phases.forEach(phase => {
                            const statusBadge = phase.is_paid ? '✓ Paid' : 
                                               phase.status === 'in_progress' ? '⚡ Current' : 
                                               phase.status === 'completed' ? '✓ Completed' : 
                                               'Pending';
                            const option = document.createElement('option');
                            option.value = phase.id;
                            option.textContent = `${phase.phase_name} (${statusBadge})`;
                            
                            if (phase.id === data.currentPhaseId) {
                                option.selected = true;
                            }
                            
                            phaseSelect.appendChild(option);
                        });
                        
                        phaseSelect.disabled = false;
                        phaseSelect.setAttribute('required', 'required');
                    } else {
                        phaseFieldContainer.style.display = 'none';
                        phaseSelect.innerHTML = '<option value="">-- Not Applicable --</option>';
                        phaseSelect.disabled = true;
                        phaseSelect.removeAttribute('required');
                        phaseSelect.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error fetching phases:', error);
                    phaseFieldContainer.style.display = 'none';
                    phaseSelect.innerHTML = '<option value="">-- Error --</option>';
                    phaseSelect.disabled = true;
                    phaseSelect.removeAttribute('required');
                });
        }
        
        if (preSelectedProjectId) {
            loadTeamMembers(preSelectedProjectId);
            loadProjectPhases(preSelectedProjectId);
        }
        
        if (projectSelect) {
            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                
                if (!projectId) {
                    if (assignedToSelect) {
                        assignedToSelect.innerHTML = '<option value="">-- Select Project First --</option>';
                        assignedToSelect.disabled = true;
                    }
                    
                    if (phaseSelect && phaseFieldContainer) {
                        phaseFieldContainer.style.display = 'none';
                        phaseSelect.innerHTML = '<option value="">-- Select Project First --</option>';
                        phaseSelect.disabled = true;
                        phaseSelect.removeAttribute('required');
                    }
                    return;
                }
                
                loadTeamMembers(projectId);
                loadProjectPhases(projectId);
            });
            
            if (!projectSelect.value && !preSelectedProjectId) {
                if (assignedToSelect) assignedToSelect.disabled = true;
                if (phaseSelect) phaseSelect.disabled = true;
            }
        }
        
        const completedAtInput = document.getElementById('completedAt');
        const statusSelect = document.getElementById('status');
        
        if (completedAtInput && statusSelect) {
            completedAtInput.addEventListener('change', function() {
                if (this.value) {
                    statusSelect.value = 'completed';
                }
            });
            
            statusSelect.addEventListener('change', function() {
                if (this.value === 'completed' && !completedAtInput.value) {
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    completedAtInput.value = `${yyyy}-${mm}-${dd}`;
                } else if (this.value !== 'completed') {
                    completedAtInput.value = '';
                }
            });
        }
    });
</script>
@endpush
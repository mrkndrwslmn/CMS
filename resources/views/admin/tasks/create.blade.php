@extends('admin.layouts.app')

@section('title', 'Create New Task')
@section('page-title', 'Create New Task')
@include('admin.tasks.helpers')

@section('content')
<div class="px-6 py-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-primary-600">
                Create New Task
            </h1>
            <p class="text-neutral-500 mt-1">Add a new task to the system</p>
        </div>
        
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.tasks.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Tasks
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-success-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-success-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-error-50 border-l-4 border-error-500 text-error-700 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-error-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-error-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-primary-500">Task Information</h2>
        </div>
        
        <form action="{{ route('admin.tasks.store') }}" method="POST" class="p-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="taskTitle" class="block text-sm font-medium text-neutral-700 mb-1">Task Title <span class="text-error-500">*</span></label>
                    <input type="text" name="taskTitle" id="taskTitle" 
                           class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('taskTitle') border-error-500 @enderror"
                           value="{{ old('taskTitle') }}" required>
                    @error('taskTitle')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="project_id" class="block text-sm font-medium text-neutral-700 mb-1">Related Project <span class="text-error-500">*</span></label>
                    <select name="project_id" id="project_id" required
                            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('project_id') border-error-500 @enderror">
                        <option value="">-- Select Project --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->title }} ({{ $project->client ? $project->client->fullName : 'No Client' }})
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <small class="text-neutral-500 mt-1 block">Tasks must be associated with a project</small>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Status <span class="text-error-500">*</span></label>
                    <select name="status" id="status" required
                            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('status') border-error-500 @enderror">
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="priority" class="block text-sm font-medium text-neutral-700 mb-1">Priority <span class="text-error-500">*</span></label>
                    <select name="priority" id="priority" required
                            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('priority') border-error-500 @enderror">
                        <option value="low" {{ old('priority', 'medium') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', 'medium') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', 'medium') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-medium text-neutral-700 mb-1">Deadline</label>
                    <input type="date" name="deadline" id="deadline" 
                           class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('deadline') border-error-500 @enderror"
                           value="{{ old('deadline') }}">
                    @error('deadline')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="allocated_budget" class="block text-sm font-medium text-neutral-700 mb-1">Allocated Budget</label>
                    <input type="number" name="allocated_budget" id="allocated_budget" step="0.01" min="0"
                           class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('allocated_budget') border-error-500 @enderror"
                           value="{{ old('allocated_budget') }}" placeholder="0.00">
                    @error('allocated_budget')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <small class="text-neutral-500 mt-1 block">Budget allocated for this task</small>
                </div>
                
                <div>
                    <label for="assignedTo" class="block text-sm font-medium text-neutral-700 mb-1">Assign To</label>
                    <select name="assignedTo" id="assignedTo" 
                            class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('assignedTo') border-error-500 @enderror"
                            disabled>
                        <option value="">-- Select Project First --</option>
                    </select>
                    @error('assignedTo')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <small class="text-neutral-500 mt-1 block">Only team members of the selected project can be assigned</small>
                </div>
                
                <div>
                    <label for="completedAt" class="block text-sm font-medium text-neutral-700 mb-1">Completed Date</label>
                    <input type="date" name="completedAt" id="completedAt" 
                           class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('completedAt') border-error-500 @enderror"
                           value="{{ old('completedAt') }}">
                    @error('completedAt')
                        <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <small class="text-neutral-500 mt-1 block">Leave blank if the task is not completed yet.</small>
                </div>
            </div>
            
            <!-- Task Description -->
            <div class="mb-6">
                <label for="taskDescription" class="block text-sm font-medium text-neutral-700 mb-1">Task Description <span class="text-error-500">*</span></label>
                <textarea name="taskDescription" id="taskDescription" rows="6" required
                          class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('taskDescription') border-error-500 @enderror">{{ old('taskDescription') }}</textarea>
                @error('taskDescription')
                    <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-neutral-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="4"
                          class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 @error('notes') border-error-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-error-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <small class="text-neutral-500 mt-1 block">Optional notes or comments about this task.</small>
            </div>
            
            <!-- Form Actions -->
            <div class="flex items-center justify-end pt-4 border-t border-neutral-200">
                <button type="button" onclick="window.history.back()"
                        class="mr-4 px-6 py-2.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-plus-circle mr-2"></i>Create Task
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectSelect = document.getElementById('project_id');
        const adiutorSelect = document.getElementById('assignedTo');
        
        // Dynamic team member loading based on project selection
        if (projectSelect && adiutorSelect) {
            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                
                if (!projectId) {
                    adiutorSelect.innerHTML = '<option value="">-- Select Project First --</option>';
                    adiutorSelect.disabled = true;
                    return;
                }
                
                // Show loading state
                adiutorSelect.innerHTML = '<option value="">Loading team members...</option>';
                adiutorSelect.disabled = true;
                
                // Fetch team members for this project
                fetch(`/admin/projects/${projectId}/team-members`)
                    .then(response => response.json())
                    .then(data => {
                        adiutorSelect.innerHTML = '<option value="">-- Not Assigned --</option>';
                        
                        if (data.length === 0) {
                            adiutorSelect.innerHTML += '<option value="" disabled>⚠️ No team members assigned to this project</option>';
                        } else {
                            data.forEach(member => {
                                adiutorSelect.innerHTML += `<option value="${member.id}">${member.fullName}</option>`;
                            });
                        }
                        
                        adiutorSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching team members:', error);
                        adiutorSelect.innerHTML = '<option value="">-- Error loading team members --</option>';
                        adiutorSelect.disabled = true;
                    });
            });
            
            // Disable adiutor select initially if no project is selected
            if (!projectSelect.value) {
                adiutorSelect.disabled = true;
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
    });
</script>
@endpush
@extends('admin.layouts.app')

@section('title', 'Edit Task')
@section('page-title', 'Edit Task')

@include('admin.tasks.helpers')

@section('content')
<div class="min-h-screen">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Tasks', 'url' => route('admin.tasks.index'), 'icon' => 'list-checks'],
        ['label' => 'Edit Task', 'icon' => 'pencil']
    ]" class="mb-4" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Edit Task" 
        subtitle="Update task information and details"
        class="mb-6"
    >
        <x-slot name="actions">
            <x-ui.button href="{{ route('admin.tasks.index') }}" variant="secondary">
                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                Back to Tasks
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Form Card -->
    <form action="{{ route('admin.tasks.update', $task->taskID) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')
        
        <!-- Project Selection Section -->
        <x-ui.card>
            <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                        <x-lucide-folder-kanban class="w-5 h-5 text-primary-600" />
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
                        <select name="project_id" id="project_id" required
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('project_id') border-error-500 @enderror">
                            <option value="">Select a project...</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                    @if($project->client)
                                        - {{ $project->client->fullName }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phase Selection (Dynamic) -->
                    <div id="phaseFieldContainer" class="md:col-span-2" style="display: none;">
                        <label for="phase_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Project Phase <span class="text-error-500">*</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-secondary-100 text-secondary-700 ml-2">
                                <x-lucide-layers class="w-3 h-3" /> Milestone Payment
                            </span>
                        </label>
                        <select name="phase_id" id="phase_id" 
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('phase_id') border-error-500 @enderror"
                                disabled>
                            <option value="">Loading phases...</option>
                        </select>
                        @error('phase_id')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2 flex items-center gap-1">
                            <x-lucide-info class="w-3 h-3 text-secondary-500" />
                            This task will be associated with the selected project phase for milestone-based delivery
                        </p>
                    </div>
                    
                    <!-- Assign To -->
                    <div class="md:col-span-2">
                        <label for="assignedTo" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Assign To Team Member
                        </label>
                        <select name="assignedTo" id="assignedTo" 
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('assignedTo') border-error-500 @enderror">
                            <option value="">-- Not Assigned --</option>
                            @foreach($adiutors as $adiutor)
                                <option value="{{ $adiutor->id }}" {{ old('assignedTo', $task->assignedTo) == $adiutor->id ? 'selected' : '' }}>
                                    {{ $adiutor->fullName }}
                                </option>
                            @endforeach
                        </select>
                        @error('assignedTo')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2 flex items-center gap-1">
                            <x-lucide-users class="w-3 h-3 text-neutral-400" />
                            Only team members assigned to this project
                        </p>
                    </div>
                </div>
            </div>
        </x-ui.card>
        <!-- Task Details Section -->
        <x-ui.card>
            <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-accent-100 rounded-lg flex items-center justify-center mr-3">
                        <x-lucide-clipboard-list class="w-5 h-5 text-accent-600" />
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
                               value="{{ old('taskTitle', $task->taskTitle) }}" 
                               placeholder="Enter a clear, descriptive task title..."
                               required>
                        @error('taskTitle')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
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
                                  placeholder="Provide detailed description of what needs to be accomplished...">{{ old('taskDescription', $task->taskDescription) }}</textarea>
                        @error('taskDescription')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
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
                                  placeholder="Any additional context, requirements, or comments...">{{ old('notes', $task->notes) }}</textarea>
                        @error('notes')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Completion Notes (if task is completed) -->
                    @if($task->status === 'completed')
                    <div>
                        <label for="completion_notes" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Completion Notes
                            <span class="text-xs font-normal text-neutral-500">(Optional)</span>
                        </label>
                        <textarea name="completion_notes" id="completion_notes" rows="3"
                                  class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('completion_notes') border-error-500 @enderror"
                                  placeholder="Notes about task completion (deliverables, results, etc.)...">{{ old('completion_notes', $task->completion_notes) }}</textarea>
                        @error('completion_notes')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    @endif
                </div>
            </div>
        </x-ui.card>

        <!-- Task Settings Section -->
        <x-ui.card>
            <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-warning-100 rounded-lg flex items-center justify-center mr-3">
                        <x-lucide-sliders-horizontal class="w-5 h-5 text-warning-600" />
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
                            <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>
                                In Progress
                            </option>
                            <option value="pending_approval" {{ old('status', $task->status) === 'pending_approval' ? 'selected' : '' }}>
                                Pending Approval
                            </option>
                            <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="cancelled" {{ old('status', $task->status) === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                        @error('status')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
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
                            <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>
                                Low Priority
                            </option>
                            <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>
                                Medium Priority
                            </option>
                            <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>
                                High Priority
                            </option>
                            <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>
                                Urgent
                            </option>
                        </select>
                        @error('priority')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
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
                               value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d') : '') }}">
                        @error('deadline')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Allocated Budget -->
                    <div>
                        <label for="allocated_budget" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Allocated Budget
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 font-medium">₱</span>
                            <input type="number" name="allocated_budget" id="allocated_budget" step="0.01" min="0"
                                   class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 pl-8 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('allocated_budget') border-error-500 @enderror"
                                   value="{{ old('allocated_budget', $task->allocated_budget) }}" placeholder="0.00">
                        </div>
                        @error('allocated_budget')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Actual Cost -->
                    <div>
                        <label for="actual_cost" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Actual Cost
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500 font-medium">₱</span>
                            <input type="number" name="actual_cost" id="actual_cost" step="0.01" min="0"
                                   class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 pl-8 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('actual_cost') border-error-500 @enderror"
                                   value="{{ old('actual_cost', $task->actual_cost) }}" placeholder="0.00">
                        </div>
                        @error('actual_cost')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Progress Percentage -->
                    <div>
                        <label for="progress_percentage" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Progress (%)
                        </label>
                        <input type="number" name="progress_percentage" id="progress_percentage" min="0" max="100"
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('progress_percentage') border-error-500 @enderror"
                               value="{{ old('progress_percentage', $task->progress_percentage ?? 0) }}" placeholder="0">
                        @error('progress_percentage')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Completed Date -->
                    <div class="md:col-span-2 lg:col-span-3">
                        <label for="completedAt" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Completed Date
                        </label>
                        <input type="date" name="completedAt" id="completedAt" 
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('completedAt') border-error-500 @enderror"
                               value="{{ old('completedAt', $task->completedAt ? $task->completedAt->format('Y-m-d') : '') }}">
                        @error('completedAt')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2 flex items-center gap-1">
                            <x-lucide-info class="w-3 h-3 text-neutral-400" />
                            Leave blank if task is not completed yet
                        </p>
                    </div>
                </div>
            </div>
        </x-ui.card>
            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-4">
                <x-ui.button type="button" variant="secondary" onclick="window.history.back()">
                    <x-lucide-x class="w-4 h-4 mr-2" />
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-save class="w-4 h-4 mr-2" />
                    Save Changes
                </x-ui.button>
            </div>
        </form>

    <!-- Associated Documents Section -->
    <x-ui.card class="mt-6">
        <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-neutral-800 flex items-center gap-2">
                    <x-lucide-folder-open class="w-5 h-5 text-primary-500" />
                    Associated Documents
                </h3>
                <x-ui.button href="{{ route('admin.documents.create', ['taskID' => $task->taskID]) }}" variant="primary" size="sm">
                    <x-lucide-plus class="w-4 h-4 mr-2" />
                    Add Document
                </x-ui.button>
            </div>
        </div>
        
        <div class="p-6">
            @if(count($task->documents) > 0)
                <div class="space-y-3">
                    @foreach($task->documents as $document)
                    <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-lg border border-neutral-200 hover:bg-neutral-100 transition-colors">
                        <div class="flex items-start space-x-3 flex-1 min-w-0">
                            <!-- File Icon -->
                            <div class="flex-shrink-0">
                                @php
                                    $extension = strtolower(pathinfo($document->fileName, PATHINFO_EXTENSION));
                                    $iconConfig = match($extension) {
                                        'pdf' => ['color' => 'text-error-600', 'bg' => 'bg-error-100', 'icon' => 'file-text'],
                                        'doc', 'docx' => ['color' => 'text-info-600', 'bg' => 'bg-info-100', 'icon' => 'file-text'],
                                        'xls', 'xlsx' => ['color' => 'text-success-600', 'bg' => 'bg-success-100', 'icon' => 'file-spreadsheet'],
                                        'ppt', 'pptx' => ['color' => 'text-warning-600', 'bg' => 'bg-warning-100', 'icon' => 'file-presentation'],
                                        'jpg', 'jpeg', 'png', 'gif', 'svg' => ['color' => 'text-secondary-600', 'bg' => 'bg-secondary-100', 'icon' => 'image'],
                                        'zip', 'rar', '7z' => ['color' => 'text-warning-600', 'bg' => 'bg-warning-100', 'icon' => 'archive'],
                                        default => ['color' => 'text-neutral-600', 'bg' => 'bg-neutral-100', 'icon' => 'file']
                                    };
                                @endphp
                                <div class="w-10 h-10 rounded-lg {{ $iconConfig['bg'] }} flex items-center justify-center">
                                    <x-dynamic-component :component="'lucide-' . $iconConfig['icon']" class="w-5 h-5 {{ $iconConfig['color'] }}" />
                                </div>
                            </div>

                            <!-- File Info -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-neutral-900 truncate">{{ $document->fileName }}</p>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-neutral-500 mt-1">
                                    @if($document->description)
                                        <span class="truncate max-w-xs">{{ $document->description }}</span>
                                        <span class="text-neutral-300">-</span>
                                    @endif
                                    @if($document->uploadedBy)
                                        <span>Uploaded by {{ $document->uploadedBy->fullName }}</span>
                                        <span class="text-neutral-300">-</span>
                                    @endif
                                    <span>{{ $document->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex-shrink-0 ml-4 flex items-center space-x-2">
                            <x-ui.button href="{{ route('admin.documents.download', $document->documentID) }}" variant="primary" size="sm">
                                <x-lucide-download class="w-4 h-4 mr-1" />
                                Download
                            </x-ui.button>
                            
                            <form action="{{ route('admin.documents.destroy', $document->documentID) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" variant="danger" size="sm"
                                        onclick="return window.Alerts.confirmDeleteForm(event, 'Delete Document', 'Are you sure you want to delete this document?')">
                                    <x-lucide-trash-2 class="w-4 h-4 mr-1" />
                                    Delete
                                </x-ui.button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                        <x-lucide-file class="w-8 h-8 text-neutral-400" />
                    </div>
                    <h3 class="text-neutral-500 font-medium">No documents attached</h3>
                    <p class="text-neutral-400 text-sm mt-1">Upload documents to associate with this task</p>
                </div>
            @endif
        </div>
    </x-ui.card>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const projectSelect = document.getElementById('project_id');
        const assignedToSelect = document.getElementById('assignedTo');
        const phaseSelect = document.getElementById('phase_id');
        const phaseFieldContainer = document.getElementById('phaseFieldContainer');
        const completedAtInput = document.getElementById('completedAt');
        const statusSelect = document.getElementById('status');
        
        // Current values for restoration
        const currentProjectId = '{{ $task->project_id }}';
        const currentPhaseId = '{{ $task->phase_id ?? '' }}';
        
        // Function to load team members for a project
        function loadTeamMembers(projectId) {
            if (!projectId || !assignedToSelect) {
                return;
            }
            
            const currentAssignedTo = assignedToSelect.value;
            
            // Fetch team members for this project
            fetch(`/admin/projects/${projectId}/team-members`)
                .then(response => response.json())
                .then(data => {
                    assignedToSelect.innerHTML = '<option value="">-- Not Assigned --</option>';
                    
                    if (data.length === 0) {
                        assignedToSelect.innerHTML += '<option value="" disabled>⚠️ No team members assigned to this project</option>';
                    } else {
                        data.forEach(member => {
                            const option = document.createElement('option');
                            option.value = member.id;
                            option.textContent = member.fullName;
                            if (member.id == currentAssignedTo) {
                                option.selected = true;
                            }
                            assignedToSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching team members:', error);
                    assignedToSelect.innerHTML = '<option value="">-- Error loading team members --</option>';
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
                                               phase.status === 'in_progress' ? '🔄 In Progress' :
                                               phase.status === 'completed' ? '✅ Completed' :
                                               'Pending';
                            const option = document.createElement('option');
                            option.value = phase.id;
                            option.textContent = `${phase.phase_name} (${statusBadge})`;
                            
                            // Restore current phase
                            if (phase.id == currentPhaseId) {
                                option.selected = true;
                            }
                            // Auto-select current phase if no phase was set
                            else if (!currentPhaseId && phase.id === data.currentPhaseId) {
                                option.selected = true;
                            }
                            
                            phaseSelect.appendChild(option);
                        });
                        
                        phaseSelect.disabled = false;
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
        
        // Load team members and phases for current project on page load
        if (currentProjectId) {
            loadTeamMembers(currentProjectId);
            loadProjectPhases(currentProjectId);
        }
        
        // Dynamic team member and phase loading based on project selection
        if (projectSelect) {
            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                
                if (!projectId) {
                    // Reset team members
                    if (assignedToSelect) {
                        assignedToSelect.innerHTML = '<option value="">-- Select Project First --</option>';
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
        }
        
        // Auto-update status based on completed date
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
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
                Back
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Main Content -->
    <div class="max-w-8xl mx-auto">
        <form action="{{ route('admin.tasks.update', $task->taskID) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PATCH')

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
                        <div>
                            <label for="project_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                                Related Project <span class="text-error-500">*</span>
                            </label>
                            <select name="project_id" id="project_id" required
                                    class="w-full px-4 py-2.5 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('project_id') border-error-500 @enderror">
                                <option value="">Select a project...</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
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

                        <!-- Assign To -->
                        <div>
                            <label for="assignedTo" class="block text-sm font-semibold text-neutral-700 mb-2">
                                Assign To
                            </label>
                            <select name="assignedTo" id="assignedTo" 
                                    class="w-full px-4 py-2.5 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('assignedTo') border-error-500 @enderror">
                                <option value="">Loading...</option>
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
                                   value="{{ old('taskTitle', $task->taskTitle) }}" 
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
                                      placeholder="What needs to be accomplished?">{{ old('taskDescription', $task->taskDescription) }}</textarea>
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
                                      placeholder="Any additional context...">{{ old('notes', $task->notes) }}</textarea>
                            @error('notes')
                                <p class="text-error-500 text-xs mt-1.5 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Completion Notes (if task is completed) -->
                        @if($task->status === 'completed' || old('status') === 'completed')
                        <div>
                            <label for="completion_notes" class="block text-sm font-semibold text-neutral-700 mb-2">
                                Completion Notes <span class="text-xs font-normal text-neutral-500">(Optional)</span>
                            </label>
                            <textarea name="completion_notes" id="completion_notes" rows="2"
                                      class="w-full px-4 py-2.5 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all resize-none @error('completion_notes') border-error-500 @enderror"
                                      placeholder="Summary of work completed...">{{ old('completion_notes', $task->completion_notes) }}</textarea>
                            @error('completion_notes')
                                <p class="text-error-500 text-xs mt-1.5 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        @endif
                    </div>
                </x-ui.card>

                <!-- Subtasks Card -->
                <x-ui.card x-data="subtasksManager()">
                    <div class="px-6 py-4 border-b border-neutral-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-secondary-100 rounded-lg flex items-center justify-center mr-3">
                                    <x-lucide-list-checks class="w-4 h-4 text-secondary-600" />
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-neutral-900">Subtasks</h2>
                                    <p class="text-xs text-neutral-500">Break down the task into smaller steps</p>
                                </div>
                            </div>
                            <span x-show="subtasks.length > 0" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-700" x-text="subtasks.length + ' subtask' + (subtasks.length !== 1 ? 's' : '')"></span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Subtasks List -->
                        <div id="subtasks-container" class="space-y-3">
                            <template x-for="(subtask, index) in subtasks" :key="subtask.id">
                                <div class="group relative bg-neutral-50 rounded-xl p-4 border border-neutral-200 hover:border-secondary-300 transition-all">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-6 h-6 bg-secondary-100 rounded-full flex items-center justify-center mt-1">
                                            <span class="text-xs font-semibold text-secondary-600" x-text="index + 1"></span>
                                        </div>
                                        <div class="flex-1 space-y-3">
                                            <!-- Subtask Title -->
                                            <div>
                                                <input type="text" 
                                                       :name="'subtasks[' + index + '][title]'"
                                                       x-model="subtask.title"
                                                       @keydown.enter.prevent="handleEnterKey(index)"
                                                       :data-subtask-index="index"
                                                       class="w-full px-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-secondary-500 focus:ring-2 focus:ring-secondary-200 transition-all subtask-title-input"
                                                       placeholder="Subtask title..."
                                                       required>
                                            </div>
                                            <!-- Subtask Description (Optional) -->
                                            <div>
                                                <textarea :name="'subtasks[' + index + '][description]'"
                                                          x-model="subtask.description"
                                                          rows="2"
                                                          class="w-full px-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-secondary-500 focus:ring-2 focus:ring-secondary-200 transition-all resize-none"
                                                          placeholder="Description (optional)..."></textarea>
                                            </div>
                                        </div>
                                        <!-- Remove Button -->
                                        <button type="button" 
                                                @click="removeSubtask(index)"
                                                class="flex-shrink-0 p-1.5 text-neutral-400 hover:text-error-500 hover:bg-error-50 rounded-lg transition-all opacity-40 group-hover:opacity-100">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Empty State -->
                        <div x-show="subtasks.length === 0" class="text-center py-8">
                            <div class="w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-lucide-list-plus class="w-6 h-6 text-neutral-400" />
                            </div>
                            <p class="text-sm text-neutral-500 mb-1">No subtasks yet</p>
                            <p class="text-xs text-neutral-400">Add subtasks to break down the work</p>
                        </div>

                        <!-- Add Subtask Button -->
                        <button type="button" 
                                @click="addSubtask()"
                                class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 border-2 border-dashed border-neutral-300 rounded-xl text-sm font-medium text-neutral-600 hover:border-secondary-400 hover:text-secondary-600 hover:bg-secondary-50 transition-all">
                            <x-lucide-plus class="w-4 h-4" />
                            Add Subtask
                        </button>
                        
                        @error('subtasks')
                            <p class="text-error-500 text-xs mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                            </p>
                        @enderror
                        @error('subtasks.*')
                            <p class="text-error-500 text-xs mt-2 flex items-center">
                                <x-lucide-alert-circle class="w-3 h-3 mr-1" /> Please fill in all subtask titles
                            </p>
                        @enderror
                    </div>
                </x-ui.card>

                <!-- Associated Documents Card -->
                <x-ui.card>
                    <div class="px-6 py-4 border-b border-neutral-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-info-100 rounded-lg flex items-center justify-center mr-3">
                                    <x-lucide-folder-open class="w-4 h-4 text-info-600" />
                                </div>
                                <h2 class="text-lg font-semibold text-neutral-900">Documents</h2>
                            </div>
                            <x-ui.button href="{{ route('admin.documents.create', ['taskID' => $task->taskID]) }}" variant="primary" size="sm">
                                <x-lucide-plus class="w-4 h-4 mr-1" />
                                Add
                            </x-ui.button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @if(count($task->documents) > 0)
                            <div class="space-y-3">
                                @foreach($task->documents as $document)
                                <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg border border-neutral-200 hover:border-info-300 transition-all">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        @php
                                            $extension = strtolower(pathinfo($document->fileName, PATHINFO_EXTENSION));
                                            $iconConfig = match($extension) {
                                                'pdf' => ['color' => 'text-error-600', 'bg' => 'bg-error-100', 'icon' => 'file-text'],
                                                'doc', 'docx' => ['color' => 'text-info-600', 'bg' => 'bg-info-100', 'icon' => 'file-text'],
                                                'xls', 'xlsx' => ['color' => 'text-success-600', 'bg' => 'bg-success-100', 'icon' => 'file-spreadsheet'],
                                                'jpg', 'jpeg', 'png', 'gif' => ['color' => 'text-secondary-600', 'bg' => 'bg-secondary-100', 'icon' => 'image'],
                                                'zip', 'rar' => ['color' => 'text-warning-600', 'bg' => 'bg-warning-100', 'icon' => 'archive'],
                                                default => ['color' => 'text-neutral-600', 'bg' => 'bg-neutral-100', 'icon' => 'file']
                                            };
                                        @endphp
                                        <div class="w-8 h-8 rounded-lg {{ $iconConfig['bg'] }} flex items-center justify-center flex-shrink-0">
                                            <x-dynamic-component :component="'lucide-' . $iconConfig['icon']" class="w-4 h-4 {{ $iconConfig['color'] }}" />
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-neutral-900 truncate">{{ $document->fileName }}</p>
                                            <p class="text-xs text-neutral-500">{{ $document->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                                        <x-ui.button href="{{ route('admin.documents.download', $document->documentID) }}" variant="ghost" size="sm">
                                            <x-lucide-download class="w-4 h-4" />
                                        </x-ui.button>
                                        <form action="{{ route('admin.documents.destroy', $document->documentID) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="ghost" size="sm"
                                                    onclick="return window.Alerts.confirmDeleteForm(event, 'Delete Document', 'This action cannot be undone.')">
                                                <x-lucide-trash-2 class="w-4 h-4" />
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <x-lucide-file class="w-6 h-6 text-neutral-400" />
                                </div>
                                <p class="text-sm text-neutral-500 mb-1">No documents</p>
                                <p class="text-xs text-neutral-400">Add documents to this task</p>
                            </div>
                        @endif
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
                                <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="pending_approval" {{ old('status', $task->status) === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                                <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $task->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
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
                                   value="{{ old('starting_date', $task->starting_date?->format('Y-m-d')) }}">
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
                                   value="{{ old('deadline', $task->deadline?->format('Y-m-d')) }}">
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
                                       value="{{ old('max_hours', $task->max_hours) }}" placeholder="0.0">
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
                                       value="{{ old('allocated_budget', $task->allocated_budget) }}" placeholder="0.00">
                            </div>
                            @error('allocated_budget')
                                <p class="text-error-500 text-xs mt-1 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Actual Cost -->
                        <div>
                            <label for="actual_cost" class="block text-xs font-semibold text-neutral-700 uppercase tracking-wide mb-2">
                                Actual Cost
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-sm font-medium">₱</span>
                                <input type="number" name="actual_cost" id="actual_cost" step="0.01" min="0"
                                       class="w-full pl-7 pr-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('actual_cost') border-error-500 @enderror"
                                       value="{{ old('actual_cost', $task->actual_cost) }}" placeholder="0.00">
                            </div>
                            @error('actual_cost')
                                <p class="text-error-500 text-xs mt-1 flex items-center">
                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" /> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Progress -->
                        <div>
                            <label for="progress_percentage" class="block text-xs font-semibold text-neutral-700 uppercase tracking-wide mb-2">
                                Progress
                            </label>
                            <div class="relative">
                                <input type="number" name="progress_percentage" id="progress_percentage" step="1" min="0" max="100"
                                       class="w-full px-3 py-2 text-sm border-2 border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('progress_percentage') border-error-500 @enderror"
                                       value="{{ old('progress_percentage', $task->progress_percentage ?? 0) }}" placeholder="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 text-xs">%</span>
                            </div>
                            @error('progress_percentage')
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
                                   value="{{ old('completedAt', $task->completedAt?->format('Y-m-d')) }}">
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
                            <x-lucide-save class="w-4 h-4 mr-2" />
                            Save Changes
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
        const currentAssignedTo = {{ $task->assignedTo ?? 'null' }};
        const currentPhaseId = {{ $task->phase_id ?? 'null' }};
        
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
                            const isSelected = currentAssignedTo && member.id === currentAssignedTo ? 'selected' : '';
                            assignedToSelect.innerHTML += `<option value="${member.id}" ${isSelected}>${member.fullName}</option>`;
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
                            
                            if (currentPhaseId && phase.id === currentPhaseId) {
                                option.selected = true;
                            } else if (!currentPhaseId && phase.id === data.currentPhaseId) {
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
    
    // Subtasks Manager Alpine Component
    function subtasksManager() {
        // Initialize with existing task subtasks or old values if validation failed
        const oldSubtasks = @json(old('subtasks', $task->subtasks ?? []));
        let initialSubtasks = [];
        let nextId = 1;
        
        if (oldSubtasks && Array.isArray(oldSubtasks)) {
            initialSubtasks = oldSubtasks.map((subtask, index) => ({
                id: nextId++,
                title: subtask.title || '',
                description: subtask.description || ''
            }));
        }
        
        return {
            subtasks: initialSubtasks,
            nextId: nextId,
            
            addSubtask() {
                this.subtasks.push({
                    id: this.nextId++,
                    title: '',
                    description: ''
                });
                
                // Focus the new subtask title input after DOM updates
                this.$nextTick(() => {
                    const newIndex = this.subtasks.length - 1;
                    const newInput = this.$root.querySelector(`.subtask-title-input[data-subtask-index="${newIndex}"]`);
                    if (newInput) {
                        newInput.focus();
                    }
                });
            },
            
            removeSubtask(index) {
                this.subtasks.splice(index, 1);
            },
            
            handleEnterKey(index) {
                // If this is the last subtask, add a new one
                if (index === this.subtasks.length - 1) {
                    this.addSubtask();
                } else {
                    // Otherwise, focus the next subtask's title input
                    this.$nextTick(() => {
                        const nextInput = this.$root.querySelector(`.subtask-title-input[data-subtask-index="${index + 1}"]`);
                        if (nextInput) {
                            nextInput.focus();
                        }
                    });
                }
            }
        }
    }
</script>
@endpush
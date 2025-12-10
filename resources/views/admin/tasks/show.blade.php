@extends('admin.layouts.app')

@section('title', 'Task Details')
@section('page-title', 'Task Details')

@include('admin.tasks.helpers')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Home', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Tasks', 'url' => route('admin.tasks.index'), 'icon' => 'list-checks'],
        ['label' => Str::limit(html_entity_decode($task['taskTitle']), 30), 'icon' => 'clipboard-list']
    ]" class="mb-6" />

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                @php
                    $statusConfig = [
                        'pending' => ['class' => 'bg-warning-100 text-warning-700', 'icon' => 'clock', 'text' => 'Pending'],
                        'in_progress' => ['class' => 'bg-info-100 text-info-700', 'icon' => 'loader', 'text' => 'In Progress'],
                        'pending_approval' => ['class' => 'bg-purple-100 text-purple-700', 'icon' => 'eye', 'text' => 'Pending Approval'],
                        'completed' => ['class' => 'bg-success-100 text-success-700', 'icon' => 'check-circle', 'text' => 'Completed'],
                        'cancelled' => ['class' => 'bg-error-100 text-error-700', 'icon' => 'x-circle', 'text' => 'Cancelled'],
                    ];
                    $sConfig = $statusConfig[$task['status']] ?? ['class' => 'bg-neutral-100 text-neutral-700', 'icon' => 'circle-dashed', 'text' => ucfirst(str_replace('_', ' ', $task['status']))];
                @endphp
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-sm font-medium {{ $sConfig['class'] }}">
                    <x-dynamic-component :component="'lucide-' . $sConfig['icon']" class="w-3.5 h-3.5" />
                    {{ $sConfig['text'] }}
                </span>
                
                @php
                    $priorityConfig = [
                        'low' => ['class' => 'bg-info-100 text-info-700', 'icon' => 'arrow-down'],
                        'medium' => ['class' => 'bg-warning-100 text-warning-700', 'icon' => 'minus'],
                        'high' => ['class' => 'bg-orange-100 text-orange-700', 'icon' => 'arrow-up'],
                        'urgent' => ['class' => 'bg-error-100 text-error-700', 'icon' => 'alert-triangle'],
                    ];
                    $pConfig = $priorityConfig[$task['priority']] ?? ['class' => 'bg-neutral-100 text-neutral-700', 'icon' => 'circle-dashed'];
                @endphp
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-sm font-medium {{ $pConfig['class'] }}">
                    <x-dynamic-component :component="'lucide-' . $pConfig['icon']" class="w-3.5 h-3.5" />
                    {{ ucfirst($task['priority']) }} Priority
                </span>
            </div>
            
            <x-ui.page-header 
                title="{{ html_entity_decode($task['taskTitle']) }}"
                subtitle="Created {{ date('F d, Y', strtotime($task['created_at'])) }}"
            />
            
            <!-- Related Links -->
            <x-ui.related-links :task="$task" role="admin" class="mt-3" />
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <x-ui.button href="{{ route('admin.tasks.index') }}" variant="secondary" class="inline-flex items-center gap-2">
                <x-lucide-arrow-left class="w-4 h-4" />
                Back to Tasks
            </x-ui.button>
            
            <x-ui.button href="{{ route('admin.tasks.edit', $task['taskID']) }}" variant="primary" class="inline-flex items-center gap-2">
                <x-lucide-pencil class="w-4 h-4" />
                Edit Task
            </x-ui.button>
            
            <x-ui.button type="button" id="statusUpdateBtn" variant="info" class="inline-flex items-center gap-2">
                <x-lucide-refresh-cw class="w-4 h-4" />
                Update Status
            </x-ui.button>
            
            <form action="{{ route('admin.tasks.destroy', $task['taskID']) }}" method="POST" class="inline" onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete Task', 'Are you sure you want to delete this task?')">
                @csrf
                @method('DELETE')
                <x-ui.button type="submit" variant="danger" class="inline-flex items-center gap-2">
                    <x-lucide-trash-2 class="w-4 h-4" />
                    Delete
                </x-ui.button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Task Details -->
        <div class="lg:col-span-2">
            <!-- Task Details Card -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-700 flex items-center gap-2">
                        <x-lucide-clipboard-list class="w-5 h-5 text-primary-500" />
                        Task Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Description</h3>
                        <div class="prose max-w-none text-neutral-800">
                            {!! nl2br(e($task['taskDescription'])) !!}
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Task Information</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Task ID:</div>
                                    <div class="flex-1 text-neutral-800 font-medium">
                                        {{ $task['taskID'] }}
                                    </div>
                                </div>
                                
                                @if(isset($task['form']) && $task['form'])
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Related Form:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <a href="{{ route('admin.forms.show', $task['formID']) }}" class="text-primary-600 hover:underline inline-flex items-center gap-1">
                                            <x-lucide-file-text class="w-4 h-4" />
                                            Form #{{ $task['formID'] }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Status:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sConfig['class'] }}">
                                            <x-dynamic-component :component="'lucide-' . $sConfig['icon']" class="w-3 h-3" />
                                            {{ $sConfig['text'] }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Priority:</div>
                                    <div class="flex-1">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pConfig['class'] }}">
                                            <x-dynamic-component :component="'lucide-' . $pConfig['icon']" class="w-3 h-3" />
                                            {{ ucfirst($task['priority']) }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($task->phase_id && $task->phase)
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Phase:</div>
                                    <div class="flex-1">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                            <x-lucide-layers class="w-3 h-3" />
                                            {{ $task->phase->phase_name }}
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Budget & Progress</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Allocated:</div>
                                    <div class="flex-1 text-neutral-800 font-semibold">
                                        {{ $task->allocated_budget ? '₱' . number_format($task->allocated_budget, 2) : 'Not set' }}
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Adiutor Earned:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ $task->actual_cost ? '₱' . number_format($task->actual_cost, 2) : '₱0.00' }}
                                        @if(!$task->actual_cost && $task->allocated_budget)
                                            <span class="text-xs text-neutral-400 ml-1">(Pending - approve deliverables to release)</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($task->allocated_budget)
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Remaining:</div>
                                    <div class="flex-1">
                                        @php
                                            $remaining = $task->allocated_budget - ($task->actual_cost ?? 0);
                                            $isOver = $remaining < 0;
                                        @endphp
                                        <span class="{{ $isOver ? 'text-error-600 font-semibold' : 'text-success-600' }}">
                                            {{ $isOver ? '-' : '' }}₱{{ number_format(abs($remaining), 2) }}
                                            @if($isOver)
                                                <span class="text-xs ml-1">(Over Budget)</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @endif
                                
                                @if($task->max_hours)
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Max Hours:</div>
                                    <div class="flex-1">
                                        <span class="text-neutral-800 font-semibold">{{ number_format($task->max_hours, 1) }} hrs</span>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Hours Tracked:</div>
                                    <div class="flex-1">
                                        @php
                                            $hoursTracked = $task->total_billable_hours ?? 0;
                                            $maxHoursUtilization = $task->max_hours > 0 ? ($hoursTracked / $task->max_hours) * 100 : 0;
                                            $hoursRemaining = max(0, $task->max_hours - $hoursTracked);
                                            $isOverHours = $hoursTracked > $task->max_hours;
                                        @endphp
                                        <span class="{{ $isOverHours ? 'text-error-600 font-semibold' : ($maxHoursUtilization >= 80 ? 'text-warning-600' : 'text-neutral-800') }}">
                                            {{ number_format($hoursTracked, 2) }} hrs
                                            @if($isOverHours)
                                                <span class="text-xs ml-1">(Over Limit)</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Hours Left:</div>
                                    <div class="flex-1">
                                        <span class="{{ $hoursRemaining <= 0 ? 'text-error-600 font-semibold' : ($maxHoursUtilization >= 80 ? 'text-warning-600' : 'text-success-600') }}">
                                            {{ number_format($hoursRemaining, 2) }} hrs
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="w-32 text-neutral-500">Utilization:</div>
                                    <div class="flex-1 flex items-center gap-2">
                                        <div class="flex-1 bg-neutral-200 rounded-full h-2">
                                            @php
                                                $barColor = $maxHoursUtilization >= 100 ? 'bg-error-500' : ($maxHoursUtilization >= 80 ? 'bg-warning-500' : 'bg-success-500');
                                            @endphp
                                            <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ min(100, $maxHoursUtilization) }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium {{ $maxHoursUtilization >= 100 ? 'text-error-600' : ($maxHoursUtilization >= 80 ? 'text-warning-600' : 'text-neutral-700') }}">
                                            {{ number_format($maxHoursUtilization, 0) }}%
                                        </span>
                                        @if($maxHoursUtilization >= 100)
                                        <x-ui.badge variant="error" size="sm">Max Reached</x-ui.badge>
                                        @elseif($maxHoursUtilization >= 80)
                                        <x-ui.badge variant="warning" size="sm">Approaching Limit</x-ui.badge>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Progress:</div>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <div class="flex-1 bg-neutral-200 rounded-full h-2 mr-3">
                                                <div class="bg-primary-500 h-2 rounded-full" style="width: {{ $task->progress_percentage ?? 0 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-neutral-700">{{ $task->progress_percentage ?? 0 }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Completion Notes (if completed) -->
                    @if($task->status === 'completed' && $task->completion_notes)
                    <div class="mt-6 pt-6 border-t border-neutral-100">
                        <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Completion Notes</h3>
                        <div class="bg-success-50 border border-success-200 rounded-lg p-4">
                            <p class="text-neutral-700 whitespace-pre-wrap">{{ $task->completion_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </x-ui.card>
            
            <!-- Subtasks Section -->
            <x-ui.card class="mb-6" x-data="subtasksManager()">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-neutral-700 flex items-center gap-2">
                            <x-lucide-list-checks class="w-5 h-5 text-secondary-500" />
                            Subtasks
                        </h2>
                        <span x-show="subtasks.length > 0" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-700">
                            <span x-text="completedCount + '/' + subtasks.length + ' completed'"></span>
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Empty State -->
                    <div x-show="subtasks.length === 0 && !isAdding" class="text-center py-6">
                        <div class="w-10 h-10 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <x-lucide-list-plus class="w-5 h-5 text-neutral-400" />
                        </div>
                        <p class="text-sm text-neutral-500">No subtasks yet</p>
                        <p class="text-xs text-neutral-400 mb-3">Click below to add subtasks</p>
                        <button type="button" @click="startAdding()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-secondary-500 text-white text-xs font-medium rounded-lg hover:bg-secondary-600 transition-colors">
                            <x-lucide-plus class="w-3.5 h-3.5" />
                            Add First Subtask
                        </button>
                    </div>
                    
                    <!-- Subtasks List -->
                    <div class="space-y-2">
                        <template x-for="(subtask, index) in subtasks" :key="subtask.id">
                            <div class="group flex items-start gap-3 p-3 rounded-lg transition-all"
                                 :class="subtask.is_completed ? 'bg-success-50 border border-success-200' : 'bg-neutral-50 border border-neutral-200'">
                                <div class="flex-shrink-0 mt-0.5">
                                    <button type="button" 
                                            @click="toggleSubtask(subtask)"
                                            class="focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2 rounded-full">
                                        <div x-show="subtask.is_completed" class="w-5 h-5 bg-success-500 rounded-full flex items-center justify-center hover:bg-success-600 transition-colors">
                                            <svg class="w-3 h-3 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        </div>
                                        <div x-show="!subtask.is_completed" class="w-5 h-5 border-2 border-neutral-300 rounded-full hover:border-secondary-400 transition-colors"></div>
                                    </button>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium" :class="subtask.is_completed ? 'text-neutral-500 line-through' : 'text-neutral-800'" x-text="subtask.title"></p>
                                    <p x-show="subtask.description" class="text-xs text-neutral-500 mt-1" x-text="subtask.description"></p>
                                    <p x-show="subtask.is_completed && subtask.completed_at" class="text-xs text-success-600 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        <span x-text="'Completed ' + formatTimeAgo(subtask.completed_at)"></span>
                                    </p>
                                </div>
                                <!-- Delete Button -->
                                <button type="button" 
                                        @click="deleteSubtask(subtask, index)"
                                        class="flex-shrink-0 p-1.5 text-neutral-400 hover:text-error-500 hover:bg-error-50 rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </template>
                        
                        <!-- New Subtask Input (Trello-style) -->
                        <div x-show="isAdding" class="flex items-start gap-3 p-3 rounded-lg bg-white shadow-sm border border-secondary-200">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="w-5 h-5 border-2 border-dashed border-secondary-300 rounded-full"></div>
                            </div>
                            <div class="flex-1">
                                <input type="text" 
                                       x-model="newTitle"
                                       x-ref="newInput"
                                       @keydown.enter.prevent="saveAndAddAnother()"
                                       @keydown.escape="cancelAdding()"
                                       class="w-full px-0 py-0 text-sm font-medium text-neutral-800 bg-transparent border-none shadow-none outline-none ring-0 focus:border-none focus:ring-0 focus:shadow-none focus:outline-none placeholder-neutral-400"
                                       style="box-shadow: none !important;"
                                       placeholder="Enter subtask title..."
                                       :disabled="isSaving">
                            </div>
                            <div class="flex-shrink-0 flex items-center gap-1">
                                <span x-show="isSaving" class="text-secondary-500">
                                    <x-lucide-loader-2 class="w-4 h-4 animate-spin" />
                                </span>
                                <button type="button" 
                                        @click="cancelAdding()"
                                        x-show="!isSaving"
                                        class="p-1 text-neutral-400 hover:text-neutral-600 rounded">
                                    <x-lucide-x class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Another Button -->
                    <button type="button" 
                            x-show="subtasks.length > 0 && !isAdding"
                            @click="startAdding()"
                            class="mt-3 w-full flex items-center justify-center gap-2 px-4 py-2 border-2 border-dashed border-neutral-300 rounded-lg text-sm font-medium text-neutral-500 hover:border-secondary-400 hover:text-secondary-600 hover:bg-secondary-50 transition-all">
                        <x-lucide-plus class="w-4 h-4" />
                        Add Subtask
                    </button>
                    
                    <!-- Keyboard hint -->
                    <p x-show="isAdding" class="mt-2 text-xs text-neutral-400 text-center">
                        Press <kbd class="px-1.5 py-0.5 bg-neutral-100 rounded text-neutral-600 font-mono">Enter</kbd> to save and add another, 
                        <kbd class="px-1.5 py-0.5 bg-neutral-100 rounded text-neutral-600 font-mono">Esc</kbd> to cancel
                    </p>
                    
                    <!-- Progress Bar -->
                    <div x-show="subtasks.length > 0" class="mt-4 pt-4 border-t border-neutral-100">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-neutral-500">Subtask Progress</span>
                            <span class="font-medium text-neutral-700" x-text="progressPercent + '%'"></span>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-2">
                            <div class="bg-secondary-500 h-2 rounded-full transition-all" :style="'width: ' + progressPercent + '%'"></div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
            
            <!-- Timeline Section -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-700 flex items-center gap-2">
                        <x-lucide-clock class="w-5 h-5 text-primary-500" />
                        Timeline
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-neutral-100 rounded-full flex items-center justify-center mr-4">
                                <x-lucide-plus class="w-5 h-5 text-neutral-600" />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-neutral-900">Task Created</p>
                                <p class="text-xs text-neutral-500">{{ $task->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($task->dateAssigned)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-info-100 rounded-full flex items-center justify-center mr-4">
                                <x-lucide-user-check class="w-5 h-5 text-info-600" />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-neutral-900">Task Assigned</p>
                                <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($task->dateAssigned)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($task->deadline)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-warning-100 rounded-full flex items-center justify-center mr-4">
                                <x-lucide-calendar class="w-5 h-5 text-warning-600" />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-neutral-900">Deadline</p>
                                <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($task->completedAt)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-success-100 rounded-full flex items-center justify-center mr-4">
                                <x-lucide-check-circle class="w-5 h-5 text-success-600" />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-neutral-900">Task Completed</p>
                                <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($task->completedAt)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </x-ui.card>

            <!-- Task Documents & Deliverables -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-neutral-700 flex items-center gap-2">
                        <x-lucide-package-check class="w-5 h-5 text-success-500" />
                        Documents & Deliverables
                        @if(isset($allDocuments) && $allDocuments->count() > 0)
                            <span class="ml-2 px-2 py-0.5 bg-primary-100 text-primary-700 text-xs font-medium rounded-full">
                                {{ $allDocuments->count() }}
                            </span>
                            @php
                                $pendingCount = $allDocuments->where('is_deliverable', true)->where('is_approved', false)->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="px-2 py-0.5 bg-warning-100 text-warning-700 text-xs font-medium rounded-full">
                                    {{ $pendingCount }} pending
                                </span>
                            @endif
                        @endif
                    </h2>
                    <div class="flex items-center gap-2">
                        <x-ui.button type="button" id="uploadDocumentBtn" size="sm" variant="primary">
                            <x-lucide-upload class="w-4 h-4 mr-1" />
                            Upload
                        </x-ui.button>
                        <a href="{{ route('admin.deliverables.pending') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
                            View All Pending
                            <x-lucide-arrow-right class="w-4 h-4" />
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if(isset($allDocuments) && $allDocuments->count() > 0)
                        <div class="space-y-3">
                            @foreach($allDocuments as $document)
                                <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-xl border border-neutral-200 hover:border-primary-200 transition-colors">
                                    <div class="flex items-center space-x-4 flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-neutral-200">
                                            @if($document->deliverable_type === 'link')
                                                <x-lucide-link class="w-6 h-6 text-blue-500" />
                                            @else
                                                @php
                                                    $extension = strtolower(pathinfo($document->fileName ?? '', PATHINFO_EXTENSION));
                                                    $iconConfig = match($extension) {
                                                        'pdf' => ['icon' => 'file-text', 'color' => 'text-error-500'],
                                                        'doc', 'docx' => ['icon' => 'file-text', 'color' => 'text-info-500'],
                                                        'xls', 'xlsx' => ['icon' => 'file-spreadsheet', 'color' => 'text-success-500'],
                                                        'jpg', 'jpeg', 'png', 'gif' => ['icon' => 'image', 'color' => 'text-secondary-500'],
                                                        'zip', 'rar' => ['icon' => 'archive', 'color' => 'text-warning-500'],
                                                        default => ['icon' => 'file', 'color' => 'text-neutral-500']
                                                    };
                                                @endphp
                                                <x-dynamic-component :component="'lucide-' . $iconConfig['icon']" class="w-6 h-6 {{ $iconConfig['color'] }}" />
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="font-medium text-neutral-800 truncate">
                                                    {{ $document->fileName ?: ($document->description ?: 'Untitled') }}
                                                </p>
                                                @if($document->is_deliverable)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $document->deliverable_type === 'link' ? 'bg-blue-100 text-blue-700' : 'bg-primary-100 text-primary-700' }}">
                                                        {{ ucfirst($document->deliverable_type ?? 'file') }}
                                                    </span>
                                                    @if($document->is_approved)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                                            <x-lucide-check class="w-3 h-3 mr-1" />
                                                            Approved
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                                            <x-lucide-clock class="w-3 h-3 mr-1" />
                                                            Pending
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                                        Document
                                                    </span>
                                                @endif
                                            </div>
                                            @if($document->description && $document->fileName)
                                                <p class="text-sm text-neutral-500 truncate mt-1">{{ $document->description }}</p>
                                            @endif
                                            <p class="text-xs text-neutral-400 flex items-center gap-2 mt-1">
                                                @if($document->uploader)
                                                    <span>By {{ $document->uploader->fullName }}</span>
                                                    <span>·</span>
                                                @endif
                                                <span>{{ $document->created_at->diffForHumans() }}</span>
                                                @if($document->fileSize)
                                                    <span>·</span>
                                                    <span>{{ number_format($document->fileSize / 1024, 1) }} KB</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 ml-4">
                                        @if($document->deliverable_type === 'link' && $document->link_url)
                                            <a href="{{ $document->link_url }}" target="_blank" 
                                               class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                                <x-lucide-external-link class="w-4 h-4 mr-1" />
                                                Open
                                            </a>
                                        @else
                                            <a href="{{ route('admin.documents.download', $document->documentID) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-primary-500 text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition-colors">
                                                <x-lucide-download class="w-4 h-4 mr-1" />
                                                Download
                                            </a>
                                        @endif
                                        @if($document->is_deliverable && !$document->is_approved)
                                            <form action="{{ route('admin.deliverables.approve', $document->documentID) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-success-500 text-white text-sm font-medium rounded-lg hover:bg-success-600 transition-colors">
                                                    <x-lucide-check class="w-4 h-4 mr-1" />
                                                    Approve
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                <x-lucide-package-open class="w-8 h-8 text-neutral-400" />
                            </div>
                            <h3 class="text-neutral-500 text-base">No documents or deliverables</h3>
                            <p class="text-neutral-400 text-sm mt-1">Upload documents or wait for adiutor submissions</p>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Task Notes -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-700 flex items-center gap-2">
                        <x-lucide-sticky-note class="w-5 h-5 text-primary-500" />
                        Notes
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <form action="{{ route('admin.tasks.update-notes', $task['taskID']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <textarea name="notes" rows="4" class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Add notes about this task...">{{ $task['notes'] ?? '' }}</textarea>
                            <div class="flex justify-end mt-2">
                                <x-ui.button type="submit" variant="primary">
                                    <x-lucide-save class="w-4 h-4 mr-2" />
                                    Save Notes
                                </x-ui.button>
                            </div>
                        </form>
                    </div>
                </div>
            </x-ui.card>
        </div>
        
        <!-- Right Column: Assigned User, Client Info -->
        <div class="lg:col-span-1">
            <!-- Assigned User Card -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-neutral-700 flex items-center gap-2">
                        <x-lucide-user-cog class="w-5 h-5 text-primary-500" />
                        Assigned Adiutor
                    </h2>
                    <x-ui.button type="button" id="assignUserBtn" size="sm" variant="primary">
                        <x-lucide-user-plus class="w-4 h-4 mr-1" />
                        Assign
                    </x-ui.button>
                </div>
                <div class="p-6">
                    @if(isset($task['assignedUser']) && $task['assignedUser'])
                        <div class="flex items-center mb-6">
                            <div class="bg-primary-100 h-12 w-12 rounded-full flex items-center justify-center text-primary-600 mr-4">
                                <x-lucide-user class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-neutral-900">{{ $task['assignedUser']['fullName'] }}</h3>
                                <div class="text-neutral-500 flex items-center gap-2">
                                    <x-lucide-mail class="w-4 h-4" />
                                    {{ $task['assignedUser']['email'] }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @if(isset($task['assignedUser']['phoneNumber']) && $task['assignedUser']['phoneNumber'])
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0 text-neutral-400">
                                        <x-lucide-phone class="w-4 h-4" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-neutral-500">Phone</div>
                                        <div class="text-neutral-900">{{ $task['assignedUser']['phoneNumber'] }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex">
                                <div class="w-8 flex-shrink-0 text-neutral-400">
                                    <x-lucide-calendar class="w-4 h-4" />
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-neutral-500">Assigned Date</div>
                                    <div class="text-neutral-900">{{ isset($task['dateAssigned']) && $task['dateAssigned'] ? date('M d, Y', strtotime($task['dateAssigned'])) : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-neutral-100">
                            <a href="{{ route('admin.users.show', $task['assignedUser']['id']) }}" 
                               class="inline-flex items-center text-primary-600 hover:text-primary-700">
                                <span>View User Profile</span>
                                <x-lucide-chevron-right class="w-4 h-4 ml-1" />
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="bg-neutral-50 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-4">
                                <x-lucide-user class="w-5 h-5 text-neutral-400" />
                            </div>
                            <h3 class="text-neutral-500 text-base">No assigned user</h3>
                            <p class="text-neutral-400 text-sm mt-1">This task is not assigned to any user yet</p>
                        </div>
                    @endif
                </div>
            </x-ui.card>
            
            <!-- Client Information -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-700 flex items-center gap-2">
                        <x-lucide-user class="w-5 h-5 text-primary-500" />
                        Client Information
                    </h2>
                </div>
                <div class="p-6">
                    @if(isset($task['client']) && $task['client'])
                        <div class="flex items-center mb-6">
                            <div class="bg-info-100 h-12 w-12 rounded-full flex items-center justify-center text-info-600 mr-4">
                                <x-lucide-user class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-neutral-900">{{ $task['client']['fullName'] }}</h3>
                                <div class="text-neutral-500 flex items-center gap-2">
                                    <x-lucide-mail class="w-4 h-4" />
                                    {{ $task['client']['email'] }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @if(isset($task['client']['phoneNumber']) && $task['client']['phoneNumber'])
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0 text-neutral-400">
                                        <x-lucide-phone class="w-4 h-4" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-neutral-500">Phone</div>
                                        <div class="text-neutral-900">{{ $task['client']['phoneNumber'] }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex">
                                <div class="w-8 flex-shrink-0 text-neutral-400">
                                    <x-lucide-calendar class="w-4 h-4" />
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-neutral-500">Client Since</div>
                                    <div class="text-neutral-900">{{ date('M d, Y', strtotime($task['client']['created_at'])) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-neutral-100">
                            <a href="{{ route('admin.clients.show', $task['client']['id']) }}" 
                               class="inline-flex items-center text-primary-600 hover:text-primary-700">
                                <span>View Client Profile</span>
                                <x-lucide-chevron-right class="w-4 h-4 ml-1" />
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="bg-neutral-50 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-4">
                                <x-lucide-user class="w-5 h-5 text-neutral-400" />
                            </div>
                            <h3 class="text-neutral-500 text-base">No client information</h3>
                            <p class="text-neutral-400 text-sm mt-1">This task is not associated with a client</p>
                        </div>
                    @endif
                </div>
            </x-ui.card>
        </div>
    </div>
</div>

<!-- Assign User Modal -->
<div class="modal hidden fixed inset-0 z-50 overflow-y-auto" id="assignUserModal" tabindex="-1" aria-hidden="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" data-dismiss="modal"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="modal-dialog max-w-md w-full relative pointer-events-auto">
            <div class="modal-content rounded-2xl shadow-lg border-0 bg-white">
            <form action="{{ route('admin.tasks.assign', $task['taskID']) }}" method="POST">
                @csrf
                <div class="modal-header bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl flex items-center justify-between">
                    <h5 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <x-lucide-user-plus class="w-5 h-5 text-primary-500" />
                        Assign User
                    </h5>
                    <button type="button" class="text-neutral-500 hover:text-neutral-700 focus:outline-none" data-dismiss="modal">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
                <div class="modal-body p-6">
                    <div class="mb-5">
                        <label for="assignedTo" class="block text-sm font-medium text-neutral-700 mb-1">Select User</label>
                        <select name="assignedTo" id="assignedTo" required
                                class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="">-- Select User --</option>
                            @foreach($adiutors as $adiutor)
                                <option value="{{ $adiutor->id }}" {{ $task['assignedTo'] == $adiutor->id ? 'selected' : '' }}>
                                    {{ $adiutor->fullName }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-neutral-500 mt-1 block">
                            Task will be automatically marked as "In Progress" when assigned.
                        </small>
                    </div>
                </div>
                <div class="modal-footer bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end rounded-b-2xl">
                    <x-ui.button type="button" variant="secondary" class="mr-2" data-dismiss="modal">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary">
                        <x-lucide-user-plus class="w-4 h-4 mr-1" />
                        Assign
                    </x-ui.button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal hidden fixed inset-0 z-50 overflow-y-auto" id="statusUpdateModal" tabindex="-1" aria-hidden="true" x-data="statusUpdateHandler()">
    <div class="fixed inset-0 bg-black/50 transition-opacity" data-dismiss="modal"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="modal-dialog max-w-md w-full relative pointer-events-auto">
            <div class="modal-content rounded-2xl shadow-lg border-0 bg-white">
            <form action="{{ route('admin.tasks.update-status', $task['taskID']) }}" method="POST" @submit.prevent="handleSubmit">
                @csrf
                @method('PATCH')
                <input type="hidden" name="confirm_no_deliverables" x-model="confirmNoDeliverables">
                <div class="modal-header bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl flex items-center justify-between">
                    <h5 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <x-lucide-refresh-cw class="w-5 h-5 text-primary-500" />
                        Update Status
                    </h5>
                    <button type="button" class="text-neutral-500 hover:text-neutral-700 focus:outline-none" data-dismiss="modal">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
                <div class="modal-body p-6">
                    <div class="mb-5">
                        <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Select Status</label>
                        <select name="status" id="status" required
                                class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="pending" {{ $task['status'] === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $task['status'] === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task['status'] === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $task['status'] === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <small class="text-neutral-500 mt-1 block">
                            Setting status to "Completed" will record the completion date.
                        </small>
                    </div>
                </div>
                <div class="modal-footer bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end rounded-b-2xl">
                    <x-ui.button type="button" variant="secondary" class="mr-2" data-dismiss="modal">
                        Cancel
                    </x-ui.button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            :disabled="isSubmitting">
                        <x-lucide-check class="w-4 h-4 mr-1" />
                        <span x-show="!isSubmitting">Update Status</span>
                        <span x-show="isSubmitting" x-cloak>Updating...</span>
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal hidden fixed inset-0 z-50 overflow-y-auto" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" data-dismiss="modal"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="modal-dialog max-w-md w-full relative pointer-events-auto">
            <div class="modal-content rounded-2xl shadow-lg border-0 bg-white">
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="taskID" value="{{ $task['taskID'] }}">
                <div class="modal-header bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl flex items-center justify-between">
                    <h5 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                        <x-lucide-upload class="w-5 h-5 text-primary-500" />
                        Upload Document
                    </h5>
                    <button type="button" class="text-neutral-500 hover:text-neutral-700 focus:outline-none" data-dismiss="modal">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
                <div class="modal-body p-6">
                    <div class="mb-5">
                        <label for="file" class="block text-sm font-medium text-neutral-700 mb-1">Select File</label>
                        <input type="file" name="file" id="file" required
                               class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <small class="text-neutral-500 mt-1 block">
                            Accepted file types: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max 10MB)
                        </small>
                    </div>
                    
                    <div class="mb-5">
                        <label for="description" class="block text-sm font-medium text-neutral-700 mb-1">Description (Optional)</label>
                        <textarea name="description" id="description" rows="2" 
                                  class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                  placeholder="Provide a brief description of the document..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end rounded-b-2xl">
                    <x-ui.button type="button" variant="secondary" class="mr-2" data-dismiss="modal">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary">
                        <x-lucide-upload class="w-4 h-4 mr-1" />
                        Upload
                    </x-ui.button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal Control Functions using Alpine.js
        // The modals should use x-data and x-show for visibility
        // This is a fallback for any Bootstrap-style modal elements
        
        const openModalBtns = {
            'assignUserBtn': 'assignUserModal',
            'statusUpdateBtn': 'statusUpdateModal',
            'uploadDocumentBtn': 'uploadDocumentModal'
        };
        
        // Set up open modal event listeners
        Object.entries(openModalBtns).forEach(([btnId, modalId]) => {
            const btn = document.getElementById(btnId);
            if (btn) {
                btn.addEventListener('click', function() {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                });
            }
        });
        
        // Set up close modal event listeners
        document.querySelectorAll('[data-dismiss="modal"]').forEach(closeBtn => {
            closeBtn.addEventListener('click', function() {
                const modal = closeBtn.closest('.modal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });
        
        // Close modal when clicking backdrop
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });
    });
    
    // Subtasks Manager Alpine Component
    function subtasksManager() {
        @php
            $subtasksData = $task->subtasks ? $task->subtasks->map(function($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'description' => $s->description,
                    'is_completed' => $s->is_completed,
                    'completed_at' => $s->completed_at?->toISOString(),
                ];
            })->values()->toArray() : [];
        @endphp
        return {
            subtasks: @json($subtasksData),
            isAdding: false,
            isSaving: false,
            newTitle: '',
            
            get completedCount() {
                return this.subtasks.filter(s => s.is_completed).length;
            },
            
            get progressPercent() {
                return this.subtasks.length > 0 ? Math.round((this.completedCount / this.subtasks.length) * 100) : 0;
            },
            
            startAdding() {
                this.isAdding = true;
                this.newTitle = '';
                this.$nextTick(() => {
                    this.$refs.newInput.focus();
                });
            },
            
            cancelAdding() {
                this.isAdding = false;
                this.newTitle = '';
            },
            
            async saveAndAddAnother() {
                if (!this.newTitle.trim() || this.isSaving) return;
                
                this.isSaving = true;
                const title = this.newTitle.trim();
                
                try {
                    const response = await fetch('{{ route("admin.tasks.subtasks.store", $task["taskID"]) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ title: title, description: null })
                    });
                    
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Add to local state
                        this.subtasks.push({
                            id: data.subtask.id,
                            title: data.subtask.title,
                            description: data.subtask.description,
                            is_completed: false,
                            completed_at: null
                        });
                        
                        // Clear input and keep focus for next entry
                        this.newTitle = '';
                        this.$nextTick(() => {
                            this.$refs.newInput.focus();
                        });
                    } else {
                        throw new Error(data.message || 'Failed to add subtask');
                    }
                } catch (error) {
                    console.error('Error adding subtask:', error);
                    window.toast.error('Failed to add subtask. Please try again.');
                } finally {
                    this.isSaving = false;
                }
            },
            
            async toggleSubtask(subtask) {
                const action = subtask.is_completed ? 'incomplete' : 'complete';
                const originalState = subtask.is_completed;
                
                // Optimistic update
                subtask.is_completed = !subtask.is_completed;
                if (subtask.is_completed) {
                    subtask.completed_at = new Date().toISOString();
                } else {
                    subtask.completed_at = null;
                }
                
                try {
                    const response = await fetch(`/admin/subtasks/${subtask.id}/${action}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (!data.success) {
                        // Revert on failure
                        subtask.is_completed = originalState;
                        subtask.completed_at = originalState ? subtask.completed_at : null;
                        throw new Error('Failed to update');
                    }
                } catch (error) {
                    console.error('Error toggling subtask:', error);
                    // Revert on error
                    subtask.is_completed = originalState;
                    window.toast.error('Failed to update subtask status.');
                }
            },
            
            async deleteSubtask(subtask, index) {
                // Use unified alert system for confirmation
                const confirmed = await window.Alerts.confirmDelete({
                    title: 'Delete Subtask?',
                    message: 'Are you sure you want to delete this subtask?'
                });
                
                if (!confirmed) return;
                
                // Optimistic removal
                const removed = this.subtasks.splice(index, 1)[0];
                
                try {
                    const response = await fetch(`/admin/subtasks/${subtask.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    if (!data.success) {
                        // Revert on failure
                        this.subtasks.splice(index, 0, removed);
                        throw new Error('Failed to delete');
                    }
                    
                    // Show success toast
                    window.toast.success('Subtask deleted successfully');
                } catch (error) {
                    console.error('Error deleting subtask:', error);
                    // Revert on error
                    this.subtasks.splice(index, 0, removed);
                    window.toast.error('Failed to delete subtask.');
                }
            },
            
            formatTimeAgo(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                const now = new Date();
                const seconds = Math.floor((now - date) / 1000);
                
                if (seconds < 60) return 'just now';
                if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
                if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
                if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
                return date.toLocaleDateString();
            }
        }
    }
    
    // Status Update Handler Alpine Component
    function statusUpdateHandler() {
        return {
            isSubmitting: false,
            confirmNoDeliverables: '0',
            
            async handleSubmit(event) {
                if (this.isSubmitting) return;
                
                this.isSubmitting = true;
                const form = event.target;
                const formData = new FormData(form);
                
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    // Check if confirmation is required
                    if (response.status === 422 && data.requires_confirmation) {
                        const confirmed = await window.Alerts.confirm({
                            title: 'No Deliverables Found',
                            message: data.message || 'This task has no deliverables. Do you want to proceed with marking it as completed?'
                        });
                        
                        if (confirmed) {
                            // Set confirmation flag and resubmit
                            this.confirmNoDeliverables = '1';
                            // Wait a tick for Alpine to update the hidden input
                            await this.$nextTick();
                            this.handleSubmit(event);
                        } else {
                            this.isSubmitting = false;
                        }
                        return;
                    }
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to update status');
                    }
                    
                    if (data.success) {
                        window.toast.success(data.message || 'Task status updated successfully');
                        // Close modal and reload page to show updated status
                        const modal = document.getElementById('statusUpdateModal');
                        if (modal) {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }
                        // Reload page after a brief delay
                        setTimeout(() => window.location.reload(), 500);
                    } else {
                        throw new Error(data.message || 'Failed to update status');
                    }
                } catch (error) {
                    console.error('Error updating status:', error);
                    window.toast.error(error.message || 'Failed to update task status.');
                } finally {
                    this.isSubmitting = false;
                    // Reset confirmation flag
                    this.confirmNoDeliverables = '0';
                }
            }
        }
    }
</script>
@endpush
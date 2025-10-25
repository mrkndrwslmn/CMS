@extends('admin.layouts.app')

@section('title', 'Task Details')
@section('page-title', 'Task Details')

@include('admin.tasks.helpers')

@section('content')
<div class="px-6 py-8">
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

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center mb-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ getTaskStatusBadgeClass($task['status']) }} mr-3">
                    @if($task['status'] === 'pending')
                        <span class="h-2 w-2 rounded-full bg-warning-500 mr-1.5"></span>Pending
                    @elseif($task['status'] === 'in_progress')
                        <span class="h-2 w-2 rounded-full bg-info-500 mr-1.5"></span>In Progress
                    @elseif($task['status'] === 'completed')
                        <span class="h-2 w-2 rounded-full bg-success-500 mr-1.5"></span>Completed
                    @elseif($task['status'] === 'cancelled')
                        <span class="h-2 w-2 rounded-full bg-error-500 mr-1.5"></span>Cancelled
                    @else
                        <span class="h-2 w-2 rounded-full bg-neutral-500 mr-1.5"></span>{{ ucfirst($task['status']) }}
                    @endif
                </span>
                
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ getTaskPriorityBadgeClass($task['priority']) }}">
                    @if($task['priority'] === 'low')
                        <i class="fas fa-arrow-down mr-1.5 text-xs"></i>
                    @elseif($task['priority'] === 'medium')
                        <i class="fas fa-minus mr-1.5 text-xs"></i>
                    @elseif($task['priority'] === 'high')
                        <i class="fas fa-arrow-up mr-1.5 text-xs"></i>
                    @endif
                    {{ ucfirst($task['priority']) }} Priority
                </span>
            </div>
            
            <h1 class="text-2xl font-semibold text-primary-600 mb-1">
                {{ $task['taskTitle'] }}
            </h1>
            
            <div class="text-neutral-500 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>
                <span>Created {{ date('F d, Y', strtotime($task['created_at'])) }}</span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <a href="{{ route('admin.tasks.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Tasks
            </a>
            
            <a href="{{ route('admin.tasks.edit', $task['taskID']) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Task
            </a>
            
            <button type="button" id="statusUpdateBtn"
                    class="inline-flex items-center px-4 py-2 bg-info-500 hover:bg-info-600 text-white rounded-lg transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>Update Status
            </button>
            
            <form action="{{ route('admin.tasks.destroy', $task['taskID']) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-error-500 hover:bg-error-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Task Details -->
        <div class="lg:col-span-2">
            <!-- Task Details Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Task Details</h2>
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
                                        <a href="{{ route('admin.forms.show', $task['formID']) }}" class="text-primary-600 hover:underline">
                                            Form #{{ $task['formID'] }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Status:</div>
                                    <div class="flex-1 text-neutral-800">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ getTaskStatusBadgeClass($task['status']) }}">
                                            {{ ucfirst($task['status']) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Priority:</div>
                                    <div class="flex-1">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ getTaskPriorityBadgeClass($task['priority']) }}">
                                            {{ ucfirst($task['priority']) }}
                                        </span>
                                    </div>
                                </div>
<<<<<<< HEAD
                                
                                @if($task->phase_id && $task->phase)
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Phase:</div>
                                    <div class="flex-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-700">
                                            <i class="fas fa-layer-group mr-1"></i>
                                            {{ $task->phase->phase_name }}
                                        </span>
                                    </div>
                                </div>
                                @endif
=======
>>>>>>> 7c71488 (Initial commit from Princess)
                            </div>
                        </div>
                        
                        <div>
<<<<<<< HEAD
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Budget & Progress</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Allocated:</div>
                                    <div class="flex-1 text-neutral-800 font-semibold">
                                        {{ $task->allocated_budget ? '₱' . number_format($task->allocated_budget, 2) : 'Not set' }}
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Actual Cost:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ $task->actual_cost ? '₱' . number_format($task->actual_cost, 2) : '₱0.00' }}
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
                    <div class="mt-6 pt-6 border-t border-neutral-200">
                        <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Completion Notes</h3>
                        <div class="bg-success-50 border border-success-200 rounded-lg p-4">
                            <p class="text-neutral-700 whitespace-pre-wrap">{{ $task->completion_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Timeline Section (NEW) -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-neutral-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-plus text-neutral-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-neutral-900">Task Created</p>
                                <p class="text-xs text-neutral-500">{{ $task->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($task->dateAssigned)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user-check text-blue-600"></i>
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
                                <i class="fas fa-calendar-alt text-warning-600"></i>
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
                                <i class="fas fa-check-circle text-success-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-neutral-900">Task Completed</p>
                                <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($task->completedAt)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
=======
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Created:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ $task->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Deadline:</div>
                                    <div class="flex-1 text-neutral-800">
                                        @if(isset($task['deadline']) && $task['deadline'])
                                            @php 
                                                $deadline = new DateTime($task['deadline']);
                                                $now = new DateTime();
                                                $isPast = $deadline < $now && $task['status'] !== 'completed';
                                                $isClose = !$isPast && $now->diff($deadline)->days <= 3;
                                            @endphp
                                            
                                            <span class="{{ $isPast ? 'text-error-600' : ($isClose ? 'text-warning-600' : 'text-neutral-600') }}">
                                                {{ date('M d, Y', strtotime($task['deadline'])) }}
                                                @if($isPast)
                                                    <span class="block text-xs mt-1">
                                                        <i class="fas fa-exclamation-circle"></i> 
                                                        Overdue
                                                    </span>
                                                @elseif($isClose)
                                                    <span class="block text-xs mt-1">
                                                        <i class="fas fa-clock"></i> 
                                                        Due soon
                                                    </span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-neutral-400">Not set</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Assigned:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ isset($task['dateAssigned']) && $task['dateAssigned'] ? date('M d, Y', strtotime($task['dateAssigned'])) : 'Not assigned' }}
                                    </div>
                                </div>
                                
                                @if(isset($task['completedAt']) && $task['completedAt'])
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Completed:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ date('M d, Y', strtotime($task['completedAt'])) }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
>>>>>>> 7c71488 (Initial commit from Princess)
                </div>
            </div>
            
            <!-- Task Documents -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-primary-500">Documents</h2>
                    <button type="button" id="uploadDocumentBtn" class="inline-flex items-center px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-colors">
                        <i class="fas fa-upload mr-1"></i>Upload
                    </button>
                </div>
                <div class="p-6">
                    @if(isset($task['documents']) && count($task['documents']) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($task['documents'] as $document)
                                <div class="bg-neutral-50 rounded-lg p-4 flex items-center">
                                    @php
                                        $extension = strtolower(pathinfo($document->fileName, PATHINFO_EXTENSION));
                                        
                                        $iconClass = match($extension) {
                                            'pdf' => 'fas fa-file-pdf text-error-600',
                                            'doc', 'docx' => 'fas fa-file-word text-blue-600',
                                            'xls', 'xlsx' => 'fas fa-file-excel text-success-600',
                                            'ppt', 'pptx' => 'fas fa-file-powerpoint text-orange-600',
                                            'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image text-purple-600',
                                            'zip', 'rar' => 'fas fa-file-archive text-warning-600',
                                            default => 'fas fa-file text-neutral-600'
                                        };
                                    @endphp
                                    
                                    <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center">
                                        <i class="{{ $iconClass }} text-xl"></i>
                                    </div>
                                    
                                    <div class="ml-4 flex-1 min-w-0">
                                        <div class="text-sm font-medium text-neutral-900 truncate">
                                            {{ $document->fileName }}
                                        </div>
                                        <div class="text-xs text-neutral-500">
                                            {{ $document->created_at->format('M d, Y') }}
                                            @if($document->fileSize)
                                                · {{ $document->fileSize }}
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('admin.documents.download', $document->documentID) }}" 
                                       class="ml-4 p-2 text-neutral-500 hover:text-primary-600 rounded-full hover:bg-primary-50">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-file-alt text-neutral-400 text-xl"></i>
                            </div>
                            <h3 class="text-neutral-500 text-base">No documents attached</h3>
                            <p class="text-neutral-400 text-sm mt-1">Upload documents to associate with this task</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Task Notes -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Notes</h2>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <form action="{{ route('admin.tasks.update-notes', $task['taskID']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <textarea name="notes" rows="4" class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Add notes about this task...">{{ $task['notes'] ?? '' }}</textarea>
                            <div class="flex justify-end mt-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                    <i class="fas fa-save mr-2"></i>Save Notes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Assigned User, Client Info -->
        <div class="lg:col-span-1">
            <!-- Assigned User Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-primary-500">Assigned Adiutor</h2>
                    <button type="button" id="assignUserBtn" class="inline-flex items-center px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-colors">
                        <i class="fas fa-user-plus mr-1"></i>Assign
                    </button>
                </div>
                <div class="p-6">
                    @if(isset($task['assignedUser']) && $task['assignedUser'])
                        <div class="flex items-center mb-6">
                            <div class="bg-primary-100 h-12 w-12 rounded-full flex items-center justify-center text-primary-600 mr-4">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-neutral-900">{{ $task['assignedUser']['fullName'] }}</h3>
                                <div class="text-neutral-500">
                                    <i class="fas fa-envelope mr-2"></i>{{ $task['assignedUser']['email'] }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @if(isset($task['assignedUser']['phoneNumber']) && $task['assignedUser']['phoneNumber'])
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0 text-neutral-400">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-neutral-500">Phone</div>
                                        <div class="text-neutral-900">{{ $task['assignedUser']['phoneNumber'] }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex">
                                <div class="w-8 flex-shrink-0 text-neutral-400">
                                    <i class="fas fa-calendar"></i>
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
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="bg-neutral-50 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-neutral-400"></i>
                            </div>
                            <h3 class="text-neutral-500 text-base">No assigned user</h3>
                            <p class="text-neutral-400 text-sm mt-1">This task is not assigned to any user yet</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Client Information -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Client Information</h2>
                </div>
                <div class="p-6">
                    @if(isset($task['client']) && $task['client'])
                        <div class="flex items-center mb-6">
                            <div class="bg-info-100 h-12 w-12 rounded-full flex items-center justify-center text-info-600 mr-4">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-neutral-900">{{ $task['client']['fullName'] }}</h3>
                                <div class="text-neutral-500">
                                    <i class="fas fa-envelope mr-2"></i>{{ $task['client']['email'] }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @if(isset($task['client']['phoneNumber']) && $task['client']['phoneNumber'])
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0 text-neutral-400">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-neutral-500">Phone</div>
                                        <div class="text-neutral-900">{{ $task['client']['phoneNumber'] }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex">
                                <div class="w-8 flex-shrink-0 text-neutral-400">
                                    <i class="fas fa-calendar"></i>
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
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="bg-neutral-50 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-neutral-400"></i>
                            </div>
                            <h3 class="text-neutral-500 text-base">No client information</h3>
                            <p class="text-neutral-400 text-sm mt-1">This task is not associated with a client</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign User Modal -->
<div class="modal fade" id="assignUserModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog max-w-md">
        <div class="modal-content rounded-lg shadow-lg border-0">
            <form action="{{ route('admin.tasks.assign', $task['taskID']) }}" method="POST">
                @csrf
                <div class="modal-header bg-neutral-50 border-b border-neutral-200 px-6 py-4">
                    <h5 class="text-lg font-semibold text-neutral-800">Assign User</h5>
                    <button type="button" class="text-neutral-500 hover:text-neutral-700 focus:outline-none" data-dismiss="modal">
                        <i class="fas fa-times"></i>
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
                <div class="modal-footer bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" class="mr-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog max-w-md">
        <div class="modal-content rounded-lg shadow-lg border-0">
            <form action="{{ route('admin.tasks.update-status', $task['taskID']) }}" method="POST">
                @csrf
                <div class="modal-header bg-neutral-50 border-b border-neutral-200 px-6 py-4">
                    <h5 class="text-lg font-semibold text-neutral-800">Update Status</h5>
                    <button type="button" class="text-neutral-500 hover:text-neutral-700 focus:outline-none" data-dismiss="modal">
                        <i class="fas fa-times"></i>
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
                <div class="modal-footer bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" class="mr-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog max-w-md">
        <div class="modal-content rounded-lg shadow-lg border-0">
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="taskID" value="{{ $task['taskID'] }}">
                <div class="modal-header bg-neutral-50 border-b border-neutral-200 px-6 py-4">
                    <h5 class="text-lg font-semibold text-neutral-800">Upload Document</h5>
                    <button type="button" class="text-neutral-500 hover:text-neutral-700 focus:outline-none" data-dismiss="modal">
                        <i class="fas fa-times"></i>
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
                <div class="modal-footer bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" class="mr-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal Control Functions
        const modals = ['assignUserModal', 'statusUpdateModal', 'uploadDocumentModal'];
        const openModalBtns = {
            'assignUserBtn': 'assignUserModal',
            'statusUpdateBtn': 'statusUpdateModal',
            'uploadDocumentBtn': 'uploadDocumentModal'
        };
        
        // Initialize Bootstrap Modals
        modals.forEach(modalId => {
            const modal = new bootstrap.Modal(document.getElementById(modalId));
            
            // Store modal instance in the DOM element
            document.getElementById(modalId)._bsModal = modal;
        });
        
        // Set up open modal event listeners
        Object.entries(openModalBtns).forEach(([btnId, modalId]) => {
            const btn = document.getElementById(btnId);
            if (btn) {
                btn.addEventListener('click', function() {
                    const modal = document.getElementById(modalId);
                    if (modal && modal._bsModal) {
                        modal._bsModal.show();
                    }
                });
            }
        });
        
        // Set up close modal event listeners
        document.querySelectorAll('[data-dismiss="modal"]').forEach(closeBtn => {
            closeBtn.addEventListener('click', function() {
                const modal = closeBtn.closest('.modal');
                if (modal && modal._bsModal) {
                    modal._bsModal.hide();
                }
            });
        });
    });
</script>
@endpush
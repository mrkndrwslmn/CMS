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
        ['label' => Str::limit($task['taskTitle'], 30), 'icon' => 'clipboard-list']
    ]" class="mb-6" />

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                @php
                    $statusConfig = [
                        'pending' => ['class' => 'bg-warning-100 text-warning-800', 'icon' => 'clock'],
                        'in_progress' => ['class' => 'bg-info-100 text-info-800', 'icon' => 'loader'],
                        'completed' => ['class' => 'bg-success-100 text-success-800', 'icon' => 'check-circle'],
                        'cancelled' => ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'circle-slash'],
                    ];
                    $sConfig = $statusConfig[$task['status']] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'circle'];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-sm font-medium {{ $sConfig['class'] }}">
                    <x-dynamic-component :component="'lucide-' . $sConfig['icon']" class="w-3.5 h-3.5" />
                    {{ ucfirst(str_replace('_', ' ', $task['status'])) }}
                </span>
                
                @php
                    $priorityConfig = [
                        'low' => ['class' => 'bg-success-100 text-success-800', 'icon' => 'arrow-down'],
                        'medium' => ['class' => 'bg-warning-100 text-warning-800', 'icon' => 'minus'],
                        'high' => ['class' => 'bg-error-100 text-error-800', 'icon' => 'arrow-up'],
                    ];
                    $pConfig = $priorityConfig[$task['priority']] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'circle'];
                @endphp
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-sm font-medium {{ $pConfig['class'] }}">
                    <x-dynamic-component :component="'lucide-' . $pConfig['icon']" class="w-3.5 h-3.5" />
                    {{ ucfirst($task['priority']) }} Priority
                </span>
            </div>
            
            <x-ui.page-header 
                title="{{ $task['taskTitle'] }}"
                subtitle="Created {{ date('F d, Y', strtotime($task['created_at'])) }}"
            />
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
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sConfig['class'] }}">
                                            <x-dynamic-component :component="'lucide-' . $sConfig['icon']" class="w-3.5 h-3.5" />
                                            {{ ucfirst(str_replace('_', ' ', $task['status'])) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Priority:</div>
                                    <div class="flex-1">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pConfig['class'] }}">
                                            <x-dynamic-component :component="'lucide-' . $pConfig['icon']" class="w-3.5 h-3.5" />
                                            {{ ucfirst($task['priority']) }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($task->phase_id && $task->phase)
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Phase:</div>
                                    <div class="flex-1">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-700">
                                            <x-lucide-layers class="w-3.5 h-3.5" />
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
                    <div class="mt-6 pt-6 border-t border-neutral-100">
                        <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Completion Notes</h3>
                        <div class="bg-success-50 border border-success-200 rounded-lg p-4">
                            <p class="text-neutral-700 whitespace-pre-wrap">{{ $task->completion_notes }}</p>
                        </div>
                    </div>
                    @endif
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
            
            <!-- Task Documents -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-neutral-700 flex items-center gap-2">
                        <x-lucide-folder-open class="w-5 h-5 text-primary-500" />
                        Documents
                    </h2>
                    <x-ui.button type="button" id="uploadDocumentBtn" size="sm" variant="primary">
                        <x-lucide-upload class="w-4 h-4 mr-1" />
                        Upload
                    </x-ui.button>
                </div>
                <div class="p-6">
                    @if(isset($task['documents']) && count($task['documents']) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($task['documents'] as $document)
                                <div class="bg-neutral-50 rounded-lg p-4 flex items-center">
                                    @php
                                        $extension = strtolower(pathinfo($document->fileName, PATHINFO_EXTENSION));
                                        
                                        $docConfig = match($extension) {
                                            'pdf' => ['icon' => 'file-text', 'color' => 'text-error-600 bg-error-100'],
                                            'doc', 'docx' => ['icon' => 'file-text', 'color' => 'text-info-600 bg-info-100'],
                                            'xls', 'xlsx' => ['icon' => 'file-spreadsheet', 'color' => 'text-success-600 bg-success-100'],
                                            'ppt', 'pptx' => ['icon' => 'file-presentation', 'color' => 'text-warning-600 bg-warning-100'],
                                            'jpg', 'jpeg', 'png', 'gif' => ['icon' => 'image', 'color' => 'text-secondary-600 bg-secondary-100'],
                                            'zip', 'rar' => ['icon' => 'archive', 'color' => 'text-warning-600 bg-warning-100'],
                                            default => ['icon' => 'file', 'color' => 'text-neutral-600 bg-neutral-100']
                                        };
                                    @endphp
                                    
                                    <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-lg {{ explode(' ', $docConfig['color'])[1] ?? 'bg-neutral-100' }}">
                                        <x-dynamic-component :component="'lucide-' . $docConfig['icon']" class="w-5 h-5 {{ explode(' ', $docConfig['color'])[0] ?? 'text-neutral-600' }}" />
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
                                        <x-lucide-download class="w-5 h-5" />
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                <x-lucide-file class="w-8 h-8 text-neutral-400" />
                            </div>
                            <h3 class="text-neutral-500 text-base">No documents attached</h3>
                            <p class="text-neutral-400 text-sm mt-1">Upload documents to associate with this task</p>
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
<div class="modal fade" id="assignUserModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog max-w-md">
        <div class="modal-content rounded-2xl shadow-lg border-0">
            <form action="{{ route('admin.tasks.assign', $task['taskID']) }}" method="POST">
                @csrf
                <div class="modal-header bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl">
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

<!-- Update Status Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog max-w-md">
        <div class="modal-content rounded-2xl shadow-lg border-0">
            <form action="{{ route('admin.tasks.update-status', $task['taskID']) }}" method="POST">
                @csrf
                <div class="modal-header bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl">
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
                    <x-ui.button type="submit" variant="primary">
                        <x-lucide-check class="w-4 h-4 mr-1" />
                        Update Status
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog max-w-md">
        <div class="modal-content rounded-2xl shadow-lg border-0">
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="taskID" value="{{ $task['taskID'] }}">
                <div class="modal-header bg-neutral-50 border-b border-neutral-100 px-6 py-4 rounded-t-2xl">
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
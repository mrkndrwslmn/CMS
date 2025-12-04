@extends('admin.layouts.app')

@section('title', 'Revision Request #' . $revision->id)
@section('page-title', 'Revision Request Details')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Home', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Inbox', 'url' => '#', 'icon' => 'inbox'],
        ['label' => 'Revisions', 'url' => route('admin.revisions.index'), 'icon' => 'rotate-ccw'],
        ['label' => 'Request #' . $revision->id]
    ]" class="mb-6" />
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                @php
                    $statusConfig = [
                        'pending' => ['class' => 'bg-warning-100 text-warning-800', 'label' => 'Pending Review', 'icon' => 'clock'],
                        'approved' => ['class' => 'bg-success-100 text-success-800', 'label' => 'Approved', 'icon' => 'check-circle'],
                        'rejected' => ['class' => 'bg-error-100 text-error-800', 'label' => 'Rejected', 'icon' => 'x-circle'],
                        'completed' => ['class' => 'bg-primary-100 text-primary-800', 'label' => 'Completed', 'icon' => 'check-check'],
                        'cancelled' => ['class' => 'bg-neutral-100 text-neutral-800', 'label' => 'Cancelled', 'icon' => 'circle-slash'],
                    ];
                    $config = $statusConfig[$revision->status] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'label' => ucfirst($revision->status), 'icon' => 'circle'];
                @endphp
                
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-sm font-medium {{ $config['class'] }}">
                    <x-dynamic-component :component="'lucide-' . $config['icon']" class="w-3.5 h-3.5" />
                    {{ $config['label'] }}
                </span>
                
                @if($revision->priority === 'urgent' || $revision->priority === 'high')
                    @php
                        $priorityConfig = [
                            'high' => ['class' => 'bg-orange-100 text-orange-800', 'icon' => 'arrow-up'],
                            'urgent' => ['class' => 'bg-error-100 text-error-800', 'icon' => 'alert-triangle'],
                        ];
                        $pConfig = $priorityConfig[$revision->priority] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'circle'];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-sm font-medium {{ $pConfig['class'] }}">
                        <x-dynamic-component :component="'lucide-' . $pConfig['icon']" class="w-3.5 h-3.5" />
                        {{ ucfirst($revision->priority) }} Priority
                    </span>
                @endif
            </div>
            
            <x-ui.page-header 
                title="Revision Request #{{ $revision->id }}"
                subtitle="Requested by {{ $revision->requestedBy->fullName ?? 'N/A' }} on {{ $revision->created_at->format('F d, Y') }}"
            />
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <x-ui.button href="{{ route('admin.revisions.index') }}" variant="secondary" class="inline-flex items-center gap-2">
                <x-lucide-arrow-left class="w-4 h-4" />
                Back to Revisions
            </x-ui.button>
            
            @if($revision->status === 'pending')
                <x-ui.button type="button" variant="success" onclick="document.getElementById('approveModal').classList.remove('hidden')" class="inline-flex items-center gap-2">
                    <x-lucide-check-circle class="w-4 h-4" />
                    Approve
                </x-ui.button>
                
                <x-ui.button type="button" variant="danger" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="inline-flex items-center gap-2">
                    <x-lucide-x-circle class="w-4 h-4" />
                    Reject
                </x-ui.button>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Revision Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Request Details Card -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                        <x-lucide-info class="w-5 h-5 text-primary-500" />
                        Request Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Source Type</h3>
                            <div class="flex items-center">
                                @if($revision->source_type === 'project')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-primary-100 text-primary-800">
                                        <x-lucide-folder-kanban class="w-4 h-4" />
                                        Project Revision
                                    </span>
                                @elseif($revision->source_type === 'task')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-info-100 text-info-800">
                                        <x-lucide-list-checks class="w-4 h-4" />
                                        Task Revision
                                    </span>
                                @elseif($revision->source_type === 'document')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-neutral-100 text-neutral-800">
                                        <x-lucide-file-text class="w-4 h-4" />
                                        Document Revision
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Priority Level</h3>
                            <div class="flex items-center">
                                @if($revision->priority === 'urgent')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-error-100 text-error-800">
                                        <x-lucide-alert-triangle class="w-4 h-4" />
                                        Urgent
                                    </span>
                                @elseif($revision->priority === 'high')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-orange-100 text-orange-800">
                                        <x-lucide-arrow-up class="w-4 h-4" />
                                        High
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium bg-neutral-100 text-neutral-700">
                                        <x-lucide-minus class="w-4 h-4" />
                                        Normal
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Requested Date</h3>
                            <div class="flex items-center gap-2 text-neutral-800">
                                <x-lucide-calendar-plus class="w-4 h-4 text-neutral-400" />
                                {{ $revision->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Due Date</h3>
                            <div class="flex items-center gap-2 text-neutral-800">
                                <x-lucide-calendar-check class="w-4 h-4 text-neutral-400" />
                                @if($revision->requested_due_date)
                                    {{ \Carbon\Carbon::parse($revision->requested_due_date)->format('M d, Y') }}
                                @else
                                    <span class="text-neutral-400">Not specified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <!-- Revision Reason Card -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                        <x-lucide-message-square class="w-5 h-5 text-primary-500" />
                        Revision Reason
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-neutral-800 bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                        {{ $revision->reason }}
                    </div>
                </div>
            </x-ui.card>

            <!-- Related Items Card -->
            @if($revision->project || $revision->task || $revision->document)
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                        <x-lucide-link class="w-5 h-5 text-primary-500" />
                        Related Items
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($revision->project)
                    <div class="flex items-start p-4 bg-primary-50 rounded-lg border border-primary-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-primary-500 rounded-xl flex items-center justify-center">
                                <x-lucide-folder-kanban class="w-5 h-5 text-white" />
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-1">Project</h3>
                            <a href="{{ route('admin.projects.show', $revision->project->id) }}" 
                               class="text-lg font-medium text-primary-700 hover:text-primary-900 inline-flex items-center gap-2">
                                {{ $revision->project->projectName }}
                                <x-lucide-external-link class="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($revision->task)
                    <div class="flex items-start p-4 bg-info-50 rounded-lg border border-info-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-info-500 rounded-xl flex items-center justify-center">
                                <x-lucide-list-checks class="w-5 h-5 text-white" />
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-1">Task</h3>
                            <a href="{{ route('admin.tasks.show', $revision->task->taskID) }}" 
                               class="text-lg font-medium text-info-700 hover:text-info-900 inline-flex items-center gap-2">
                                {{ $revision->task->taskTitle }}
                                <x-lucide-external-link class="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($revision->document)
                    <div class="flex items-start p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-neutral-500 rounded-xl flex items-center justify-center">
                                <x-lucide-file-text class="w-5 h-5 text-white" />
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-1">Document</h3>
                            <a href="{{ route('admin.documents.show', $revision->document->documentID) }}" 
                               class="text-lg font-medium text-neutral-700 hover:text-neutral-900 inline-flex items-center gap-2">
                                {{ $revision->document->fileName }}
                                <x-lucide-external-link class="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </x-ui.card>
            @endif

            <!-- Admin Review Card -->
            @if($revision->status !== 'pending' && $revision->status !== 'cancelled')
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                        <x-lucide-shield-check class="w-5 h-5 text-primary-500" />
                        Admin Review
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Reviewed By</h3>
                            <div class="flex items-center gap-2 text-neutral-800">
                                <x-lucide-user-check class="w-4 h-4 text-neutral-400" />
                                {{ $revision->reviewedBy->fullName ?? 'N/A' }}
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Reviewed On</h3>
                            <div class="flex items-center gap-2 text-neutral-800">
                                <x-lucide-clock class="w-4 h-4 text-neutral-400" />
                                @if($revision->reviewed_at)
                                    {{ \Carbon\Carbon::parse($revision->reviewed_at)->format('M d, Y h:i A') }}
                                @else
                                    <span class="text-neutral-400">N/A</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($revision->status === 'approved' && $revision->assignedAdiutor)
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Assigned To</h3>
                            <div class="flex items-center gap-2 text-neutral-800">
                                <x-lucide-user-cog class="w-4 h-4 text-neutral-400" />
                                {{ $revision->assignedAdiutor->fullName }}
                            </div>
                        </div>
                        @endif
                        
                        @if($revision->approved_due_date)
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Approved Due Date</h3>
                            <div class="flex items-center gap-2 text-neutral-800">
                                <x-lucide-calendar class="w-4 h-4 text-neutral-400" />
                                {{ \Carbon\Carbon::parse($revision->approved_due_date)->format('M d, Y') }}
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    @if($revision->admin_notes)
                    <div class="pt-6 border-t border-neutral-200">
                        <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-3">Admin Notes</h3>
                        <div class="prose max-w-none text-neutral-800 bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                            {{ $revision->admin_notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </x-ui.card>
            @endif

            <!-- Completion Details Card -->
            @if($revision->status === 'completed')
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100 bg-success-50">
                    <h2 class="text-lg font-semibold text-success-700 flex items-center gap-2">
                        <x-lucide-check-check class="w-5 h-5" />
                        Completion Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Completed By</h3>
                            <div class="flex items-center gap-2 text-neutral-800">
                                <x-lucide-user-check class="w-4 h-4 text-neutral-400" />
                                {{ $revision->completedBy->fullName ?? 'N/A' }}
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Completed On</h3>
                            <div class="flex items-center gap-2 text-neutral-800">
                                <x-lucide-clock class="w-4 h-4 text-neutral-400" />
                                @if($revision->completed_at)
                                    {{ \Carbon\Carbon::parse($revision->completed_at)->format('M d, Y h:i A') }}
                                @else
                                    <span class="text-neutral-400">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($revision->completion_notes)
                    <div class="pt-6 border-t border-neutral-200">
                        <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-3">Completion Notes</h3>
                        <div class="prose max-w-none text-neutral-800 bg-success-50 rounded-lg p-4 border border-success-200">
                            {{ $revision->completion_notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </x-ui.card>
            @endif

        </div>

        <!-- Right Column: Quick Actions & Info -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Client Information Card -->
            @if($revision->requestedBy)
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                        <x-lucide-user class="w-5 h-5 text-primary-500" />
                        Client Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 bg-primary-100 rounded-full mx-auto flex items-center justify-center mb-3">
                            <x-lucide-user class="w-10 h-10 text-primary-600" />
                        </div>
                        <h4 class="text-lg font-semibold text-neutral-900">{{ $revision->requestedBy->fullName }}</h4>
                        <p class="text-sm text-neutral-500">{{ $revision->requestedBy->email }}</p>
                    </div>
                    
                    <x-ui.button href="{{ route('admin.clients.show', $revision->requestedBy->id) }}" variant="primary" class="w-full inline-flex items-center justify-center gap-2">
                        <x-lucide-eye class="w-4 h-4" />
                        View Client Profile
                    </x-ui.button>
                </div>
            </x-ui.card>
            @endif

            <!-- Status Timeline Card -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                        <x-lucide-history class="w-5 h-5 text-primary-500" />
                        Status Timeline
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Created -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                                    <x-lucide-plus class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-neutral-900">Request Created</p>
                                <p class="text-xs text-neutral-500">{{ $revision->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        @if($revision->reviewed_at)
                        <!-- Reviewed -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 {{ $revision->status === 'approved' ? 'bg-success-500' : 'bg-error-500' }} rounded-full flex items-center justify-center">
                                    @if($revision->status === 'approved')
                                        <x-lucide-check class="w-4 h-4 text-white" />
                                    @else
                                        <x-lucide-x class="w-4 h-4 text-white" />
                                    @endif
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-neutral-900">{{ $revision->status === 'approved' ? 'Approved' : 'Rejected' }}</p>
                                <p class="text-xs text-neutral-500">{{ $revision->reviewed_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($revision->completed_at)
                        <!-- Completed -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                                    <x-lucide-check-check class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-neutral-900">Completed</p>
                                <p class="text-xs text-neutral-500">{{ $revision->completed_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </x-ui.card>

            <!-- Quick Actions Card -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                        <x-lucide-zap class="w-5 h-5 text-primary-500" />
                        Quick Actions
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($revision->project)
                    <x-ui.button href="{{ route('admin.projects.show', $revision->project->id) }}" variant="secondary" class="w-full inline-flex items-center justify-center gap-2">
                        <x-lucide-folder-kanban class="w-4 h-4" />
                        View Project
                    </x-ui.button>
                    @endif

                    @if($revision->task)
                    <x-ui.button href="{{ route('admin.tasks.show', $revision->task->taskID) }}" variant="secondary" class="w-full inline-flex items-center justify-center gap-2">
                        <x-lucide-list-checks class="w-4 h-4" />
                        View Task
                    </x-ui.button>
                    @endif

                    @if($revision->document)
                    <x-ui.button href="{{ route('admin.documents.show', $revision->document->documentID) }}" variant="secondary" class="w-full inline-flex items-center justify-center gap-2">
                        <x-lucide-file-text class="w-4 h-4" />
                        View Document
                    </x-ui.button>
                    @endif

                    @if($revision->requestedBy)
                    <x-ui.button href="{{ route('admin.clients.show', $revision->requestedBy->id) }}" variant="secondary" class="w-full inline-flex items-center justify-center gap-2">
                        <x-lucide-user class="w-4 h-4" />
                        View Client
                    </x-ui.button>
                    @endif
                </div>
            </x-ui.card>

        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl">
        <form action="{{ route('admin.revisions.approve', $revision->id) }}" method="POST">
            @csrf
            <div class="px-6 py-5 border-b border-neutral-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                        <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                        Approve Revision Request
                    </h3>
                    <button type="button" 
                            onclick="document.getElementById('approveModal').classList.add('hidden')"
                            class="text-neutral-400 hover:text-neutral-600">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
            </div>
            <div class="px-6 py-5 space-y-4">                
                <div>
                    <x-ui.select 
                        name="assigned_adiutor_id" 
                        id="assigned_adiutor_id" 
                        label="Assign Adiutor"
                        required
                    >
                        <option value="">Select Adiutor</option>
                        @foreach($adiutors as $adiutor)
                            <option value="{{ $adiutor->id }}" 
                                {{ ($revision->source_type === 'task' && $revision->task && $revision->task->assignedTo && $revision->task->assignedTo == $adiutor->id) ? 'selected' : '' }}>
                                {{ $adiutor->fullName }}
                                @if($revision->source_type === 'task' && $revision->task && $revision->task->assignedTo == $adiutor->id)
                                    (Currently Assigned)
                                @endif
                            </option>
                        @endforeach
                    </x-ui.select>
                    @if($revision->source_type === 'task' && $revision->task && $revision->task->assignedTo)
                        <p class="mt-1 text-xs text-info-600 flex items-center gap-1">
                            <x-lucide-info class="w-3 h-3" />
                            This task is currently assigned to {{ $revision->task->assignedUser ? $revision->task->assignedUser->fullName : 'an adiutor' }}
                        </p>
                    @endif
                </div>
                <div>
                    <x-ui.input 
                        type="date" 
                        name="approved_due_date" 
                        id="approved_due_date" 
                        label="Due Date"
                        :value="$revision->requested_due_date ? $revision->requested_due_date->format('Y-m-d') : ''"
                        min="{{ date('Y-m-d') }}"
                    />
                    @if($revision->requested_due_date)
                        <p class="mt-1 text-xs text-neutral-600 flex items-center gap-1">
                            <x-lucide-calendar class="w-3 h-3" />
                            Client requested: {{ $revision->requested_due_date->format('M d, Y') }}
                        </p>
                    @endif
                </div>
                <div>
                    <label for="admin_notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="admin_notes" id="admin_notes" rows="3" 
                              class="w-full border-neutral-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500" 
                              placeholder="Add any notes or instructions for the adiutor..."></textarea>
                </div>
                
                <!-- Task Selection for Project-based Revisions -->
                @if($revision->source_type === 'project' && $revision->project)
                    <div class="border-t pt-4">
                        <label class="text-sm font-semibold text-neutral-900 mb-3 flex items-center gap-2">
                            <x-lucide-list-checks class="w-4 h-4 text-primary-500" />
                            Select Tasks to Reopen (Optional)
                        </label>
                        <p class="text-xs text-neutral-600 mb-3">
                            Choose which completed tasks should be reopened for this revision. If none selected, only the project status will be changed.
                        </p>
                        
                        @php
                            $completedTasks = $revision->project->tasks()->where('status', 'completed')->get();
                        @endphp
                        
                        @if($completedTasks->count() > 0)
                            <div class="space-y-2 max-h-64 overflow-y-auto border rounded-lg p-3 bg-neutral-50">
                                @foreach($completedTasks as $task)
                                    <label class="flex items-start p-3 border border-neutral-200 rounded-lg hover:bg-white hover:border-primary-300 transition-all cursor-pointer">
                                        <input type="checkbox" 
                                               name="reopen_task_ids[]" 
                                               value="{{ $task->taskID }}"
                                               class="mt-1 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                        <div class="ml-3 flex-1">
                                            <span class="block font-medium text-neutral-900">{{ $task->taskTitle }}</span>
                                            <span class="block text-xs text-neutral-600 mt-1">
                                                Assigned to: {{ $task->assignee_name ?? 'Unassigned' }}
                                            </span>
                                            @if($task->completedAt)
                                                <span class="block text-xs text-neutral-500 mt-1">
                                                    Completed: {{ \Carbon\Carbon::parse($task->completedAt)->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            
                            <!-- Select All / None -->
                            <div class="flex gap-2 mt-2">
                                <button type="button" 
                                        onclick="selectAllTasks()"
                                        class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                                    Select All
                                </button>
                                <span class="text-xs text-neutral-400">|</span>
                                <button type="button" 
                                        onclick="deselectAllTasks()"
                                        class="text-xs text-neutral-600 hover:text-neutral-700 font-medium">
                                    Deselect All
                                </button>
                            </div>
                        @else
                            <p class="text-sm text-neutral-500 italic">No completed tasks to reopen.</p>
                        @endif
                    </div>
                    
                    <!-- Allow New Tasks -->
                    <div class="border-t pt-4">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" 
                                   name="allows_new_tasks" 
                                   value="1"
                                   class="mt-1 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                            <div class="ml-3">
                                <span class="block font-medium text-neutral-900">Allow creating new tasks for this revision</span>
                                <span class="block text-xs text-neutral-600 mt-1">
                                    If checked, admin/adiutor can create additional tasks as part of this revision scope.
                                </span>
                            </div>
                        </label>
                    </div>
                @endif
            </div>
            <div class="px-6 py-4 bg-neutral-50 rounded-b-2xl flex justify-end space-x-3">
                <x-ui.button type="button" variant="secondary" onclick="document.getElementById('approveModal').classList.add('hidden')">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="success" class="inline-flex items-center gap-2">
                    <x-lucide-check-circle class="w-4 h-4" />
                    Approve Request
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl">
        <form action="{{ route('admin.revisions.reject', $revision->id) }}" method="POST">
            @csrf
            <div class="px-6 py-5 border-b border-neutral-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                        <x-lucide-x-circle class="w-5 h-5 text-error-500" />
                        Reject Revision Request
                    </h3>
                    <button type="button" 
                            onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="text-neutral-400 hover:text-neutral-600">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
            </div>
            <div class="px-6 py-5">
                <div>
                    <label for="reject_notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Reason for Rejection <span class="text-error-500">*</span>
                    </label>
                    <textarea name="admin_notes" id="reject_notes" rows="4" 
                              class="w-full border-neutral-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500" 
                              required 
                              placeholder="Explain why this revision request is being rejected..."></textarea>
                    <p class="mt-2 text-xs text-neutral-500 flex items-center gap-1">
                        <x-lucide-info class="w-3 h-3" />
                        This will be visible to the client.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 bg-neutral-50 rounded-b-2xl flex justify-end space-x-3">
                <x-ui.button type="button" variant="secondary" onclick="document.getElementById('rejectModal').classList.add('hidden')">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="danger" class="inline-flex items-center gap-2">
                    <x-lucide-x-circle class="w-4 h-4" />
                    Reject Request
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<script>
function selectAllTasks() {
    document.querySelectorAll('input[name="reopen_task_ids[]"]').forEach(cb => cb.checked = true);
}

function deselectAllTasks() {
    document.querySelectorAll('input[name="reopen_task_ids[]"]').forEach(cb => cb.checked = false);
}
</script>
@endsection
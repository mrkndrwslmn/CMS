@extends('admin.layouts.app')

@section('title', 'Revision Request #' . $revision->id)
@section('page-title', 'Revision Request Details')

@section('content')
<div class="px-6 py-8">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center mb-2">
                @php
                    $statusConfig = [
                        'pending' => ['class' => 'bg-warning-100 text-warning-800', 'label' => 'Pending Review', 'icon' => 'fa-clock'],
                        'approved' => ['class' => 'bg-success-100 text-success-800', 'label' => 'Approved', 'icon' => 'fa-check-circle'],
                        'rejected' => ['class' => 'bg-error-100 text-error-800', 'label' => 'Rejected', 'icon' => 'fa-times-circle'],
                        'completed' => ['class' => 'bg-primary-100 text-primary-800', 'label' => 'Completed', 'icon' => 'fa-check-double'],
                        'cancelled' => ['class' => 'bg-neutral-100 text-neutral-800', 'label' => 'Cancelled', 'icon' => 'fa-ban'],
                    ];
                    $config = $statusConfig[$revision->status] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'label' => ucfirst($revision->status), 'icon' => 'fa-circle'];
                @endphp
                
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $config['class'] }} mr-3">
                    <i class="fas {{ $config['icon'] }} mr-1.5 text-xs"></i>
                    {{ $config['label'] }}
                </span>
                
                @if($revision->priority === 'urgent' || $revision->priority === 'high')
                    @php
                        $priorityConfig = [
                            'high' => ['class' => 'bg-orange-100 text-orange-800', 'icon' => 'fa-arrow-up'],
                            'urgent' => ['class' => 'bg-error-100 text-error-800', 'icon' => 'fa-exclamation-circle'],
                        ];
                        $pConfig = $priorityConfig[$revision->priority] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'fa-circle'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $pConfig['class'] }}">
                        <i class="fas {{ $pConfig['icon'] }} mr-1.5 text-xs"></i>
                        {{ ucfirst($revision->priority) }} Priority
                    </span>
                @endif
            </div>
            
            <h1 class="text-2xl font-semibold text-primary-600 mb-1">Revision Request #{{ $revision->id }}</h1>
            
            <div class="text-neutral-500 flex items-center">
                <i class="fas fa-user mr-2"></i>
                <span>Requested by {{ $revision->requestedBy->fullName ?? 'N/A' }} on {{ $revision->created_at->format('F d, Y') }}</span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <a href="{{ route('admin.revisions.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Revisions
            </a>
            
            @if($revision->status === 'pending')
                <button onclick="document.getElementById('approveModal').classList.remove('hidden')"
                        class="inline-flex items-center px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-check-circle mr-2"></i>Approve
                </button>
                
                <button onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                        class="inline-flex items-center px-4 py-2 bg-error-500 hover:bg-error-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-times-circle mr-2"></i>Reject
                </button>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Revision Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Request Details Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">
                        <i class="fas fa-info-circle mr-2"></i>Request Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Source Type</h3>
                            <div class="flex items-center">
                                @if($revision->source_type === 'project')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-project-diagram mr-2"></i>Project Revision
                                    </span>
                                @elseif($revision->source_type === 'task')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-tasks mr-2"></i>Task Revision
                                    </span>
                                @elseif($revision->source_type === 'document')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-file-alt mr-2"></i>Document Revision
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Priority Level</h3>
                            <div class="flex items-center">
                                @if($revision->priority === 'urgent')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-error-100 text-error-800">
                                        <i class="fas fa-exclamation-circle mr-2"></i>Urgent
                                    </span>
                                @elseif($revision->priority === 'high')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-arrow-up mr-2"></i>High
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-700">
                                        <i class="fas fa-minus mr-2"></i>Normal
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Requested Date</h3>
                            <div class="flex items-center text-neutral-800">
                                <i class="fas fa-calendar-plus text-neutral-400 mr-2"></i>
                                {{ $revision->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Due Date</h3>
                            <div class="flex items-center text-neutral-800">
                                <i class="fas fa-calendar-check text-neutral-400 mr-2"></i>
                                @if($revision->requested_due_date)
                                    {{ \Carbon\Carbon::parse($revision->requested_due_date)->format('M d, Y') }}
                                @else
                                    <span class="text-neutral-400">Not specified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revision Reason Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">
                        <i class="fas fa-comment-alt mr-2"></i>Revision Reason
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-neutral-800 bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                        {{ $revision->reason }}
                    </div>
                </div>
            </div>

            <!-- Related Items Card -->
            @if($revision->project || $revision->task || $revision->document)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">
                        <i class="fas fa-link mr-2"></i>Related Items
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($revision->project)
                    <div class="flex items-start p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-project-diagram text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-1">Project</h3>
                            <a href="{{ route('admin.projects.show', $revision->project->id) }}" 
                               class="text-lg font-medium text-blue-700 hover:text-blue-900 inline-flex items-center">
                                {{ $revision->project->projectName }}
                                <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($revision->task)
                    <div class="flex items-start p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tasks text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-1">Task</h3>
                            <a href="{{ route('admin.tasks.show', $revision->task->taskID) }}" 
                               class="text-lg font-medium text-purple-700 hover:text-purple-900 inline-flex items-center">
                                {{ $revision->task->taskTitle }}
                                <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($revision->document)
                    <div class="flex items-start p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-1">Document</h3>
                            <a href="{{ route('admin.documents.show', $revision->document->documentID) }}" 
                               class="text-lg font-medium text-gray-700 hover:text-gray-900 inline-flex items-center">
                                {{ $revision->document->fileName }}
                                <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Admin Review Card -->
            @if($revision->status !== 'pending' && $revision->status !== 'cancelled')
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">
                        <i class="fas fa-user-shield mr-2"></i>Admin Review
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Reviewed By</h3>
                            <div class="flex items-center text-neutral-800">
                                <i class="fas fa-user-check text-neutral-400 mr-2"></i>
                                {{ $revision->reviewedBy->fullName ?? 'N/A' }}
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Reviewed On</h3>
                            <div class="flex items-center text-neutral-800">
                                <i class="fas fa-clock text-neutral-400 mr-2"></i>
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
                            <div class="flex items-center text-neutral-800">
                                <i class="fas fa-user-tag text-neutral-400 mr-2"></i>
                                {{ $revision->assignedAdiutor->fullName }}
                            </div>
                        </div>
                        @endif
                        
                        @if($revision->approved_due_date)
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Approved Due Date</h3>
                            <div class="flex items-center text-neutral-800">
                                <i class="fas fa-calendar-alt text-neutral-400 mr-2"></i>
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
            </div>
            @endif

            <!-- Completion Details Card -->
            @if($revision->status === 'completed')
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200 bg-success-50">
                    <h2 class="text-lg font-semibold text-success-700">
                        <i class="fas fa-check-double mr-2"></i>Completion Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Completed By</h3>
                            <div class="flex items-center text-neutral-800">
                                <i class="fas fa-user-check text-neutral-400 mr-2"></i>
                                {{ $revision->completedBy->fullName ?? 'N/A' }}
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Completed On</h3>
                            <div class="flex items-center text-neutral-800">
                                <i class="fas fa-clock text-neutral-400 mr-2"></i>
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
            </div>
            @endif

        </div>

        <!-- Right Column: Quick Actions & Info -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Client Information Card -->
            @if($revision->requestedBy)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-primary-500">
                        <i class="fas fa-user mr-2"></i>Client Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 bg-primary-100 rounded-full mx-auto flex items-center justify-center mb-3">
                            <i class="fas fa-user text-3xl text-primary-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-neutral-900">{{ $revision->requestedBy->fullName }}</h4>
                        <p class="text-sm text-neutral-500">{{ $revision->requestedBy->email }}</p>
                    </div>
                    
                    <a href="{{ route('admin.clients.show', $revision->requestedBy->id) }}" 
                       class="block w-full text-center bg-primary-500 hover:bg-primary-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-eye mr-2"></i>View Client Profile
                    </a>
                </div>
            </div>
            @endif

            <!-- Status Timeline Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-primary-500">
                        <i class="fas fa-history mr-2"></i>Status Timeline
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Created -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-white text-xs"></i>
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
                                    <i class="fas {{ $revision->status === 'approved' ? 'fa-check' : 'fa-times' }} text-white text-xs"></i>
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
                                    <i class="fas fa-check-double text-white text-xs"></i>
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
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-lg font-semibold text-primary-500">
                        <i class="fas fa-bolt mr-2"></i>Quick Actions
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($revision->project)
                    <a href="{{ route('admin.projects.show', $revision->project->id) }}" 
                       class="block w-full text-center bg-neutral-100 hover:bg-neutral-200 text-neutral-700 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-project-diagram mr-2"></i>View Project
                    </a>
                    @endif

                    @if($revision->task)
                    <a href="{{ route('admin.tasks.show', $revision->task->taskID) }}" 
                       class="block w-full text-center bg-neutral-100 hover:bg-neutral-200 text-neutral-700 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-tasks mr-2"></i>View Task
                    </a>
                    @endif

                    @if($revision->document)
                    <a href="{{ route('admin.documents.show', $revision->document->documentID) }}" 
                       class="block w-full text-center bg-neutral-100 hover:bg-neutral-200 text-neutral-700 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>View Document
                    </a>
                    @endif

                    @if($revision->requestedBy)
                    <a href="{{ route('admin.clients.show', $revision->requestedBy->id) }}" 
                       class="block w-full text-center bg-neutral-100 hover:bg-neutral-200 text-neutral-700 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-user mr-2"></i>View Client
                    </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-lg w-full shadow-xl">
        <form action="{{ route('admin.revisions.approve', $revision->id) }}" method="POST">
            @csrf
            <div class="px-6 py-5 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-900">
                        <i class="fas fa-check-circle text-success-500 mr-2"></i>Approve Revision Request
                    </h3>
                    <button type="button" 
                            onclick="document.getElementById('approveModal').classList.add('hidden')"
                            class="text-neutral-400 hover:text-neutral-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="px-6 py-5 space-y-4">                
                <div>
                    <label for="assigned_adiutor_id" class="block text-sm font-medium text-neutral-700 mb-2">
                        Assign Adiutor <span class="text-error-500">*</span>
                    </label>
                    <select name="assigned_adiutor_id" id="assigned_adiutor_id" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500" 
                            required>
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
                    </select>
                    @if($revision->source_type === 'task' && $revision->task && $revision->task->assignedTo)
                        <p class="mt-1 text-xs text-blue-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            This task is currently assigned to {{ $revision->task->assignedUser ? $revision->task->assignedUser->fullName : 'an adiutor' }}
                        </p>
                    @endif
                </div>
                <div>
                    <label for="approved_due_date" class="block text-sm font-medium text-neutral-700 mb-2">
                        Due Date
                    </label>
                    <input type="date" name="approved_due_date" id="approved_due_date" 
                           value="{{ $revision->requested_due_date ? $revision->requested_due_date->format('Y-m-d') : '' }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500" 
                           min="{{ date('Y-m-d') }}">
                    @if($revision->requested_due_date)
                        <p class="mt-1 text-xs text-neutral-600">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Client requested: {{ $revision->requested_due_date->format('M d, Y') }}
                        </p>
                    @endif
                </div>
                <div>
                    <label for="admin_notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="admin_notes" id="admin_notes" rows="3" 
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500" 
                              placeholder="Add any notes or instructions for the adiutor..."></textarea>
                </div>
                
                <!-- Task Selection for Project-based Revisions -->
                @if($revision->source_type === 'project' && $revision->project)
                    <div class="border-t pt-4">
                        <label class="block text-sm font-semibold text-neutral-900 mb-3">
                            <i class="fas fa-tasks text-primary-500 mr-2"></i>
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
            <div class="px-6 py-4 bg-neutral-50 rounded-b-xl flex justify-end space-x-3">
                <button type="button" 
                        onclick="document.getElementById('approveModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-success-500 text-white rounded-lg hover:bg-success-600 transition-colors">
                    <i class="fas fa-check-circle mr-2"></i>Approve Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-lg w-full shadow-xl">
        <form action="{{ route('admin.revisions.reject', $revision->id) }}" method="POST">
            @csrf
            <div class="px-6 py-5 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-900">
                        <i class="fas fa-times-circle text-error-500 mr-2"></i>Reject Revision Request
                    </h3>
                    <button type="button" 
                            onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="text-neutral-400 hover:text-neutral-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="px-6 py-5">
                <div>
                    <label for="reject_notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Reason for Rejection <span class="text-error-500">*</span>
                    </label>
                    <textarea name="admin_notes" id="reject_notes" rows="4" 
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500" 
                              required 
                              placeholder="Explain why this revision request is being rejected..."></textarea>
                    <p class="mt-2 text-xs text-neutral-500">
                        <i class="fas fa-info-circle mr-1"></i>This will be visible to the client.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 bg-neutral-50 rounded-b-xl flex justify-end space-x-3">
                <button type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-error-500 text-white rounded-lg hover:bg-error-600 transition-colors">
                    <i class="fas fa-times-circle mr-2"></i>Reject Request
                </button>
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
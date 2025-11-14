@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.revisions.index') }}" class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 mb-3">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Revisions
                    </a>
                    <h1 class="text-2xl font-semibold text-neutral-900">Revision Request #{{ $revision->id }}</h1>
                    <p class="mt-1 text-sm text-neutral-500">
                        Requested by {{ $revision->requestedBy->fullName ?? 'N/A' }} on {{ $revision->created_at->format('M d, Y') }}
                    </p>
                </div>
                <div>
                    @if($revision->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-amber-50 text-amber-800 border border-amber-200">
                            Pending Review
                        </span>
                    @elseif($revision->status === 'approved')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-neutral-900 text-white">
                            Approved
                        </span>
                    @elseif($revision->status === 'rejected')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-neutral-100 text-neutral-800 border border-neutral-200">
                            Rejected
                        </span>
                    @elseif($revision->status === 'completed')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-neutral-900 text-white">
                            Completed
                        </span>
                    @elseif($revision->status === 'cancelled')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-neutral-100 text-neutral-800 border border-neutral-200">
                            Cancelled
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content - Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Details Card -->
                <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h2 class="text-base font-semibold text-neutral-900">Request Details</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Type</dt>
                                <dd>
                                    @if($revision->source_type === 'project')
                                        <span class="inline-flex items-center text-sm text-neutral-700">Project Revision</span>
                                    @elseif($revision->source_type === 'task')
                                        <span class="inline-flex items-center text-sm text-neutral-700">Task Revision</span>
                                    @elseif($revision->source_type === 'document')
                                        <span class="inline-flex items-center text-sm text-neutral-700">Document Revision</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Priority</dt>
                                <dd>
                                    @if($revision->priority === 'urgent')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-neutral-900 text-white">Urgent</span>
                                    @elseif($revision->priority === 'high')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-neutral-800 text-white">High</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-neutral-100 text-neutral-700">Normal</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Requested Date</dt>
                                <dd class="text-sm text-neutral-700">{{ $revision->created_at->format('M d, Y h:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Due Date</dt>
                                <dd class="text-sm text-neutral-700">
                                    @if($revision->requested_due_date)
                                        {{ \Carbon\Carbon::parse($revision->requested_due_date)->format('M d, Y') }}
                                    @else
                                        <span class="text-neutral-400">Not specified</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Revision Reason -->
                <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h2 class="text-base font-semibold text-neutral-900">Revision Reason</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-neutral-700 leading-relaxed">{{ $revision->reason }}</p>
                    </div>
                </div>

                <!-- Related Items -->
                @if($revision->project || $revision->task || $revision->document)
                <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h2 class="text-base font-semibold text-neutral-900">Related Items</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($revision->project)
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-2">Project</label>
                            <a href="{{ route('admin.projects.show', $revision->project->id) }}" 
                               class="inline-flex items-center text-sm font-medium text-neutral-900 hover:text-primary-600">
                                {{ $revision->project->projectName }}
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                        @endif

                        @if($revision->task)
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-2">Task</label>
                            <a href="{{ route('admin.tasks.show', $revision->task->taskID) }}" 
                               class="inline-flex items-center text-sm font-medium text-neutral-900 hover:text-primary-600">
                                {{ $revision->task->taskTitle }}
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                        @endif

                        @if($revision->document)
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-2">Document</label>
                            <a href="{{ route('admin.documents.show', $revision->document->documentID) }}" 
                               class="inline-flex items-center text-sm font-medium text-neutral-900 hover:text-primary-600">
                                {{ $revision->document->fileName }}
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Admin Review (if reviewed) -->
                @if($revision->status !== 'pending' && $revision->status !== 'cancelled')
                <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h2 class="text-base font-semibold text-neutral-900">Admin Review</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Reviewed By</dt>
                                <dd class="text-sm text-neutral-700">
                                    {{ $revision->reviewedBy->fullName ?? 'N/A' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Reviewed On</dt>
                                <dd class="text-sm text-neutral-700">
                                    @if($revision->reviewed_at)
                                        {{ \Carbon\Carbon::parse($revision->reviewed_at)->format('M d, Y h:i A') }}
                                    @else
                                        <span class="text-neutral-400">N/A</span>
                                    @endif
                                </dd>
                            </div>
                            @if($revision->status === 'approved' && $revision->assignedAdiutor)
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Assigned To</dt>
                                <dd class="text-sm text-neutral-700">{{ $revision->assignedAdiutor->fullName }}</dd>
                            </div>
                            @endif
                            @if($revision->approved_due_date)
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Approved Due Date</dt>
                                <dd class="text-sm text-neutral-700">{{ \Carbon\Carbon::parse($revision->approved_due_date)->format('M d, Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                        @if($revision->admin_notes)
                        <div class="mt-5 pt-5 border-t border-neutral-200">
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-2">Notes</label>
                            <p class="text-sm text-neutral-700 leading-relaxed">{{ $revision->admin_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Completion Details (if completed) -->
                @if($revision->status === 'completed')
                <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h2 class="text-base font-semibold text-neutral-900">Completion Details</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Completed By</dt>
                                <dd class="text-sm text-neutral-700">
                                    {{ $revision->completedBy->fullName ?? 'N/A' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Completed On</dt>
                                <dd class="text-sm text-neutral-700">
                                    @if($revision->completed_at)
                                        {{ \Carbon\Carbon::parse($revision->completed_at)->format('M d, Y h:i A') }}
                                    @else
                                        <span class="text-neutral-400">N/A</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                        @if($revision->completion_notes)
                        <div class="mt-5 pt-5 border-t border-neutral-200">
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-2">Completion Notes</label>
                            <p class="text-sm text-neutral-700 leading-relaxed">{{ $revision->completion_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Sidebar - Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden sticky top-6">
                    <div class="px-6 py-4 bg-neutral-50 border-b border-neutral-200">
                        <h3 class="text-base font-semibold text-neutral-900">Actions</h3>
                    </div>
                    <div class="p-6">
                        @if($revision->status === 'pending')
                            <!-- Approve Form -->
                            <form action="{{ route('admin.revisions.approve', $revision->id) }}" method="POST" class="space-y-4 mb-4">
                                @csrf
                                <div>
                                    <label for="assigned_adiutor_id" class="block text-xs font-medium text-neutral-700 uppercase tracking-wider mb-2">
                                        Assign Adiutor <span class="text-red-500">*</span>
                                    </label>
                                    <select name="assigned_adiutor_id" id="assigned_adiutor_id" 
                                            class="w-full rounded-lg border-neutral-300 text-sm focus:border-neutral-400 focus:ring-0" 
                                            required>
                                        <option value="">Select Adiutor</option>
                                        @foreach($adiutors as $adiutor)
                                            <option value="{{ $adiutor->id }}">{{ $adiutor->fullName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="approved_due_date" class="block text-xs font-medium text-neutral-700 uppercase tracking-wider mb-2">
                                        Due Date
                                    </label>
                                    <input type="date" name="approved_due_date" id="approved_due_date" 
                                           class="w-full rounded-lg border-neutral-300 text-sm focus:border-neutral-400 focus:ring-0" 
                                           min="{{ date('Y-m-d') }}">
                                </div>
                                <div>
                                    <label for="admin_notes" class="block text-xs font-medium text-neutral-700 uppercase tracking-wider mb-2">
                                        Notes
                                    </label>
                                    <textarea name="admin_notes" id="admin_notes" rows="3" 
                                              class="w-full rounded-lg border-neutral-300 text-sm focus:border-neutral-400 focus:ring-0" 
                                              placeholder="Optional notes..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-neutral-900 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-neutral-800 transition-colors">
                                    Approve Request
                                </button>
                            </form>

                            <!-- Reject Button -->
                            <button type="button" 
                                    onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                                    class="w-full bg-white text-neutral-700 px-4 py-2.5 rounded-lg text-sm font-medium border border-neutral-300 hover:bg-neutral-50 transition-colors">
                                Reject Request
                            </button>

                            <div class="mt-6 pt-6 border-t border-neutral-200">
                        @else
                            <div class="bg-neutral-50 rounded-lg p-4 text-sm text-neutral-600 mb-6">
                                This request has been <span class="font-medium">{{ $revision->status }}</span>.
                            </div>
                        @endif

                        <!-- Quick Links -->
                        <div class="space-y-2">
                            @if($revision->project)
                            <a href="{{ route('admin.projects.show', $revision->project->id) }}" 
                               class="block w-full text-center bg-white text-neutral-700 px-4 py-2.5 rounded-lg text-sm font-medium border border-neutral-300 hover:bg-neutral-50 transition-colors">
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    View Project
                                </span>
                            </a>
                            @endif

                            @if($revision->requestedBy)
                            <a href="{{ route('admin.clients.show', $revision->requestedBy->id) }}" 
                               class="block w-full text-center bg-white text-neutral-700 px-4 py-2.5 rounded-lg text-sm font-medium border border-neutral-300 hover:bg-neutral-50 transition-colors">
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    View Client
                                </span>
                            </a>
                            @endif
                        </div>
                        
                        @if($revision->status === 'pending')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full shadow-xl">
        <form action="{{ route('admin.revisions.reject', $revision->id) }}" method="POST">
            @csrf
            <div class="px-6 py-5 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-900">Reject Revision Request</h3>
                    <button type="button" 
                            onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-5">
                <div>
                    <label for="reject_notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea name="admin_notes" id="reject_notes" rows="4" 
                              class="w-full rounded-lg border-neutral-300 text-sm focus:border-neutral-400 focus:ring-0" 
                              required 
                              placeholder="Explain why this revision request is being rejected..."></textarea>
                    <p class="mt-1.5 text-xs text-neutral-500">This will be visible to the client.</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-neutral-50 rounded-b-xl flex justify-end space-x-3">
                <button type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium bg-neutral-900 text-white rounded-lg hover:bg-neutral-800 transition-colors">
                    Reject Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

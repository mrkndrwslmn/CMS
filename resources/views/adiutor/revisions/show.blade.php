@extends('adiutor.layouts.app')

@section('title', 'Revision Request Details')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Revisions', 'url' => route('adiutor.revisions.index')],
        ['label' => 'Revision #' . $revision->id],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Revision Request</h1>
            <p class="text-sm text-neutral-500 mt-1">Request ID: #{{ $revision->id }}</p>
        </div>
        <div class="flex items-center gap-2">
            @php
                $statusClass = match($revision->status) {
                    'pending' => 'bg-neutral-100 text-neutral-800',
                    'approved' => 'bg-warning-100 text-warning-800',
                    'completed' => 'bg-success-100 text-success-800',
                    'rejected' => 'bg-error-100 text-error-800',
                    default => 'bg-neutral-100 text-neutral-800'
                };
                $priorityClass = match($revision->priority ?? 'normal') {
                    'urgent' => 'bg-error-100 text-error-800',
                    'high' => 'bg-warning-100 text-warning-800',
                    'normal' => 'bg-primary-100 text-primary-800',
                    'low' => 'bg-neutral-100 text-neutral-800',
                    default => 'bg-neutral-100 text-neutral-800'
                };
            @endphp
            @if($revision->priority)
            <span class="px-3 py-1.5 text-sm font-medium rounded-full {{ $priorityClass }}">
                {{ ucfirst($revision->priority) }} Priority
            </span>
            @endif
            <span class="px-4 py-2 text-sm font-medium rounded-full {{ $statusClass }}">
                {{ ucfirst($revision->status) }}
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-2xl bg-success-50 border border-success-100 p-4">
        <div class="flex items-center gap-3">
            <div class="p-1.5 bg-success-100 rounded-lg">
                <x-lucide-check-circle-2 class="w-5 h-5 text-success-600" />
            </div>
            <p class="text-sm font-medium text-success-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 rounded-2xl bg-error-50 border border-error-100 p-4">
        <div class="flex items-center gap-3">
            <div class="p-1.5 bg-error-100 rounded-lg">
                <x-lucide-x-circle class="w-5 h-5 text-error-600" />
            </div>
            <p class="text-sm font-medium text-error-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Revision Details Card -->
            <x-ui.card>
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-neutral-800 mb-4">Revision Details</h2>
                    
                    <!-- Source Info -->
                    <div class="mb-6 p-4 bg-neutral-50 rounded-xl">
                        <div class="flex items-start gap-4">
                            <div class="p-2 bg-primary-100 rounded-lg">
                                @if($revision->source_type === 'document')
                                    <x-lucide-file-text class="w-5 h-5 text-primary-600" />
                                @elseif($revision->source_type === 'task')
                                    <x-lucide-clipboard-list class="w-5 h-5 text-primary-600" />
                                @else
                                    <x-lucide-folder class="w-5 h-5 text-primary-600" />
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-neutral-500 uppercase tracking-wide mb-1">
                                    {{ ucfirst($revision->source_type ?? 'Revision') }} Request
                                </p>
                                <h3 class="font-semibold text-neutral-800">
                                    @if($revision->document)
                                        {{ $revision->document->filename ?? 'Document' }}
                                    @elseif($revision->task)
                                        {{ $revision->task->taskTitle ?? 'Task' }}
                                    @else
                                        Revision #{{ $revision->id }}
                                    @endif
                                </h3>
                                @if($revision->project)
                                    <p class="text-sm text-neutral-500">
                                        Project: {{ $revision->project->title }}
                                    </p>
                                @elseif($revision->task && $revision->task->project)
                                    <p class="text-sm text-neutral-500">
                                        Project: {{ $revision->task->project->title }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Revision Reason -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Revision Reason</label>
                        <div class="p-4 bg-neutral-50 rounded-xl">
                            <p class="text-neutral-700 whitespace-pre-wrap">{{ $revision->reason ?? 'No reason provided.' }}</p>
                        </div>
                    </div>

                    <!-- Due Date -->
                    @if($revision->requested_due_date)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Requested Due Date</label>
                        <div class="flex items-center gap-2 p-3 {{ $revision->requested_due_date->isPast() ? 'bg-error-50' : 'bg-neutral-50' }} rounded-xl">
                            <x-lucide-calendar class="w-5 h-5 {{ $revision->requested_due_date->isPast() ? 'text-error-500' : 'text-neutral-500' }}" />
                            <span class="{{ $revision->requested_due_date->isPast() ? 'text-error-700 font-medium' : 'text-neutral-700' }}">
                                {{ $revision->requested_due_date->format('F d, Y') }}
                                @if($revision->requested_due_date->isPast())
                                    <span class="text-error-600">(Overdue)</span>
                                @elseif($revision->requested_due_date->isToday())
                                    <span class="text-warning-600">(Due Today)</span>
                                @else
                                    <span class="text-neutral-500">({{ $revision->requested_due_date->diffForHumans() }})</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($revision->admin_notes)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Admin Notes</label>
                        <div class="p-4 bg-primary-50 rounded-xl border border-primary-100">
                            <p class="text-neutral-700 whitespace-pre-wrap">{{ $revision->admin_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Document Section (if applicable) -->
            @if($revision->document)
            <x-ui.card>
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-neutral-800 mb-4">Original Document</h2>
                    <div class="p-4 bg-neutral-50 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-primary-100 rounded-lg">
                                    <x-lucide-file class="w-5 h-5 text-primary-600" />
                                </div>
                                <div>
                                    <p class="font-medium text-neutral-800">{{ $revision->document->filename }}</p>
                                    <p class="text-sm text-neutral-500">
                                        Uploaded {{ $revision->document->created_at->format('M d, Y') }}
                                        @if($revision->document->uploader)
                                            by {{ $revision->document->uploader->fullName }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('adiutor.documents.download', $revision->document->id) }}" 
                               class="px-3 py-1.5 text-sm font-medium text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors inline-flex items-center gap-1">
                                <x-lucide-download class="w-4 h-4" />
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            </x-ui.card>
            @endif

            <!-- Action Section for Approved Revisions -->
            @if($revision->status === 'approved')
            <x-ui.card>
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-neutral-800 mb-4">Complete Revision</h2>
                    <p class="text-sm text-neutral-600 mb-4">
                        Once you've completed the requested changes, mark this revision as complete. 
                        The client will be notified automatically.
                    </p>
                    
                    <form action="{{ route('adiutor.revisions.complete', $revision->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="completion_notes" class="block text-sm font-medium text-neutral-700 mb-2">
                                Completion Notes (Optional)
                            </label>
                            <textarea id="completion_notes" name="completion_notes" rows="3" 
                                      class="w-full px-4 py-2.5 border border-neutral-300 rounded-xl text-sm focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-colors"
                                      placeholder="Describe the changes you made..."></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full px-4 py-2.5 text-sm font-medium text-white bg-success-600 hover:bg-success-700 rounded-xl transition-colors inline-flex items-center justify-center gap-2">
                            <x-lucide-check-circle class="w-4 h-4" />
                            Mark as Completed
                        </button>
                    </form>
                </div>
            </x-ui.card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Request Info -->
            <x-ui.card>
                <div class="p-6">
                    <h3 class="text-sm font-semibold text-neutral-800 mb-4">Request Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-neutral-500 mb-1">Requested By</p>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-primary-600">
                                        {{ substr($revision->requestedBy->fullName ?? 'U', 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-800">
                                        {{ $revision->requestedBy->fullName ?? 'Unknown' }}
                                    </p>
                                    @if($revision->requestedBy && $revision->requestedBy->clientProfile)
                                    <p class="text-xs text-neutral-500">
                                        {{ $revision->requestedBy->clientProfile->company_name ?? '' }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-neutral-100 pt-4">
                            <p class="text-xs text-neutral-500 mb-1">Requested On</p>
                            <p class="text-sm text-neutral-800">{{ $revision->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>

                        @if($revision->reviewed_at)
                        <div class="border-t border-neutral-100 pt-4">
                            <p class="text-xs text-neutral-500 mb-1">Reviewed On</p>
                            <p class="text-sm text-neutral-800">{{ $revision->reviewed_at->format('M d, Y \a\t h:i A') }}</p>
                            @if($revision->reviewedBy)
                            <p class="text-xs text-neutral-500 mt-1">by {{ $revision->reviewedBy->fullName }}</p>
                            @endif
                        </div>
                        @endif

                        @if($revision->completed_at)
                        <div class="border-t border-neutral-100 pt-4">
                            <p class="text-xs text-neutral-500 mb-1">Completed On</p>
                            <p class="text-sm text-neutral-800">{{ $revision->completed_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                        @endif

                        @if($revision->revision_number)
                        <div class="border-t border-neutral-100 pt-4">
                            <p class="text-xs text-neutral-500 mb-1">Revision Number</p>
                            <p class="text-sm text-neutral-800">#{{ $revision->revision_number }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </x-ui.card>

            <!-- Quick Actions -->
            <x-ui.card>
                <div class="p-6">
                    <h3 class="text-sm font-semibold text-neutral-800 mb-4">Quick Actions</h3>
                    
                    <div class="space-y-2">
                        @if($revision->task)
                        <a href="{{ route('adiutor.tasks.show', $revision->task->taskID) }}" 
                           class="w-full px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 rounded-lg transition-colors inline-flex items-center gap-2">
                            <x-lucide-clipboard-list class="w-4 h-4 text-neutral-400" />
                            View Related Task
                        </a>
                        @endif
                        
                        @if($revision->project)
                        <a href="{{ route('adiutor.projects.show', $revision->project->id) }}" 
                           class="w-full px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 rounded-lg transition-colors inline-flex items-center gap-2">
                            <x-lucide-folder class="w-4 h-4 text-neutral-400" />
                            View Project
                        </a>
                        @endif
                        
                        <a href="{{ route('adiutor.revisions.index') }}" 
                           class="w-full px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-50 rounded-lg transition-colors inline-flex items-center gap-2">
                            <x-lucide-arrow-left class="w-4 h-4 text-neutral-400" />
                            Back to Revisions
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection

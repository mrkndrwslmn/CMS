@extends('client.layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-neutral-50 to-neutral-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('client.revisions.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Revision Requests
            </a>
        </div>

        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-neutral-900 mb-2">
                        Revision Request #{{ $revision->id }}
                    </h1>
                    <p class="text-neutral-600">
                        @if($revision->document)
                            For document: <span class="font-semibold">{{ $revision->document->fileName }}</span>
                        @elseif($revision->task)
                            For task: <span class="font-semibold">{{ $revision->task->taskTitle }}</span>
                        @elseif($revision->project)
                            For project: <span class="font-semibold">{{ $revision->project->title }}</span>
                        @endif
                    </p>
                </div>

                <!-- Status Badge -->
                @php
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-800', 'label' => 'Pending Review', 'icon' => '⏳'],
                        'approved' => ['bg' => 'bg-info-100', 'text' => 'text-info-800', 'label' => 'Approved', 'icon' => '✓'],
                        'rejected' => ['bg' => 'bg-error-100', 'text' => 'text-error-800', 'label' => 'Rejected', 'icon' => '✗'],
                        'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-800', 'label' => 'Completed', 'icon' => '✅'],
                        'cancelled' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'label' => 'Cancelled', 'icon' => '⊘'],
                    ];
                    $config = $statusConfig[$revision->status] ?? $statusConfig['pending'];
                @endphp
                <span class="px-4 py-2 rounded-lg text-sm font-bold {{ $config['bg'] }} {{ $config['text'] }}">
                    {{ $config['icon'] }} {{ $config['label'] }}
                </span>
            </div>

            <!-- Meta Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-neutral-200">
                <div>
                    <p class="text-xs text-neutral-500 mb-1">Requested Date</p>
                    <p class="font-semibold text-neutral-900">{{ $revision->created_at->format('M d, Y g:i A') }}</p>
                </div>
                
                @if($revision->requested_due_date)
                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Requested Due Date</p>
                        <p class="font-semibold text-neutral-900">{{ $revision->requested_due_date->format('M d, Y') }}</p>
                    </div>
                @endif

                @if($revision->priority)
                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Priority</p>
                        <p class="font-semibold text-neutral-900">{{ ucfirst($revision->priority) }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Revision Details -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-neutral-900 mb-4">Revision Details</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">Reason for Revision</label>
                    <div class="p-4 bg-neutral-50 rounded-lg">
                        <p class="text-neutral-900 whitespace-pre-wrap">{{ $revision->reason }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">Source Type</label>
                    <p class="text-neutral-900">{{ ucfirst($revision->source_type) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">Revision Number</label>
                    <p class="text-neutral-900">Revision #{{ $revision->revision_number }}</p>
                </div>
            </div>
        </div>

        <!-- Assignment Information -->
        @if($revision->assignedAdiutor)
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-neutral-900 mb-4">Assignment</h2>
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-accent-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($revision->assignedAdiutor->fullName, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-neutral-900">{{ $revision->assignedAdiutor->fullName }}</p>
                        <p class="text-sm text-neutral-600">Assigned Adiutor</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Admin Review -->
        @if($revision->reviewedBy)
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-neutral-900 mb-4">Admin Review</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1">Reviewed By</label>
                        <p class="text-neutral-900">{{ $revision->reviewedBy->fullName }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-1">Reviewed At</label>
                        <p class="text-neutral-900">{{ $revision->reviewed_at->format('M d, Y g:i A') }}</p>
                    </div>

                    @if($revision->admin_notes)
                        <div>
                            <label class="block text-sm font-semibold text-neutral-700 mb-2">Admin Notes</label>
                            <div class="p-4 bg-neutral-50 rounded-lg">
                                <p class="text-neutral-900 whitespace-pre-wrap">{{ $revision->admin_notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Completion Information -->
        @if($revision->status === 'completed' && $revision->completedBy)
            <div class="bg-success-50 border-2 border-success-200 rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-success-900 mb-4">✅ Completion Details</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-success-700 mb-1">Completed By</label>
                        <p class="text-success-900">{{ $revision->completedBy->fullName }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-success-700 mb-1">Completed At</label>
                        <p class="text-success-900">{{ $revision->completed_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex flex-wrap gap-3">
                @if($revision->project_id)
                    <a href="{{ route('client.projects.show', $revision->project_id) }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-accent-700 transition-all shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        View Project
                    </a>
                @endif

                @if($revision->status === 'pending')
                    <form action="{{ route('client.revisions.cancel', $revision->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to cancel this revision request?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-neutral-200 text-neutral-700 font-bold rounded-lg hover:bg-neutral-300 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel Request
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

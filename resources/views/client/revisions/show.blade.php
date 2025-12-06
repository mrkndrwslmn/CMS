@extends('client.layouts.app')

@section('content')
<div class="min-h-screen bg-neutral-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
            ['label' => 'Revision Requests', 'route' => 'client.revisions.index', 'icon' => 'git-pull-request'],
            ['label' => 'Request #' . $revision->id]
        ]" />

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('client.revisions.index') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                <x-lucide-arrow-left class="w-4 h-4 mr-1.5" />
                Back to Revision Requests
            </a>
        </div>

        <!-- Page Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800 mb-2">
                        Revision Request #{{ $revision->id }}
                    </h1>
                    <p class="text-sm text-neutral-600">
                        @if($revision->document)
                            For document: <span class="font-medium">{{ $revision->document->fileName }}</span>
                        @elseif($revision->task)
                            For task: <span class="font-medium">{{ $revision->task->taskTitle }}</span>
                        @elseif($revision->project)
                            For project: <span class="font-medium">{{ $revision->project->title }}</span>
                        @endif
                    </p>
                </div>

                <!-- Status Badge -->
                @php
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-800', 'label' => 'Pending Review', 'icon' => 'clock'],
                        'approved' => ['bg' => 'bg-info-100', 'text' => 'text-info-800', 'label' => 'Approved', 'icon' => 'check'],
                        'rejected' => ['bg' => 'bg-error-100', 'text' => 'text-error-800', 'label' => 'Rejected', 'icon' => 'x'],
                        'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-800', 'label' => 'Completed', 'icon' => 'check-circle'],
                        'cancelled' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'label' => 'Cancelled', 'icon' => 'ban'],
                    ];
                    $config = $statusConfig[$revision->status] ?? $statusConfig['pending'];
                @endphp
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                    <x-dynamic-component :component="'lucide-' . $config['icon']" class="w-4 h-4 mr-1.5" />
                    {{ $config['label'] }}
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
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-neutral-800 mb-4">Revision Details</h2>
            
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
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-neutral-800 mb-4">Assignment</h2>
                <div class="flex items-center space-x-4">
                    @if($revision->assignedAdiutor->profilePic)
                        <img src="{{ $revision->assignedAdiutor->getProfilePictureUrl() }}" 
                             alt="{{ $revision->assignedAdiutor->fullName }}" 
                             class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr($revision->assignedAdiutor->fullName, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-medium text-neutral-800">{{ $revision->assignedAdiutor->fullName }}</p>
                        <p class="text-sm text-neutral-500">Assigned Adiutor</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Admin Review -->
        @if($revision->reviewedBy)
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-neutral-800 mb-4">Admin Review</h2>
                
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
            <div class="bg-success-50 border border-success-200 rounded-2xl p-6 mb-6">
                <h2 class="text-lg font-semibold text-success-800 mb-4 flex items-center">
                    <x-lucide-check-circle class="w-5 h-5 mr-2" />
                    Completion Details
                </h2>
                
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
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex flex-wrap gap-3">
                @if($revision->project_id)
                    <a href="{{ route('client.projects.show', $revision->project_id) }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <x-lucide-folder class="w-4 h-4 mr-2" />
                        View Project
                    </a>
                @endif

                @if($revision->status === 'pending')
                    <form action="{{ route('client.revisions.cancel', $revision->id) }}" method="POST" 
                          onsubmit="return window.Alerts.confirmDeleteForm(event, 'Cancel Revision', 'Are you sure you want to cancel this revision request?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-neutral-100 text-neutral-700 text-sm font-medium rounded-lg hover:bg-neutral-200 transition-colors">
                            <x-lucide-x class="w-4 h-4 mr-2" />
                            Cancel Request
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('client.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-neutral-50 to-neutral-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
                        My Revision Requests
                    </h1>
                    <p class="mt-2 text-neutral-600">Track and manage your revision requests</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-neutral-500">Total Requests</p>
                    <p class="text-3xl font-bold text-primary-600">{{ $revisions->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Revisions List -->
        @if($revisions->count() > 0)
            <div class="space-y-4">
                @foreach($revisions as $revision)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <!-- Revision Info -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <!-- Status Badge -->
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-800', 'label' => 'Pending Review'],
                                                'approved' => ['bg' => 'bg-info-100', 'text' => 'text-info-800', 'label' => 'Approved'],
                                                'rejected' => ['bg' => 'bg-error-100', 'text' => 'text-error-800', 'label' => 'Rejected'],
                                                'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-800', 'label' => 'Completed'],
                                                'cancelled' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'label' => 'Cancelled'],
                                            ];
                                            $config = $statusConfig[$revision->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $config['label'] }}
                                        </span>

                                        <!-- Priority Badge -->
                                        @if($revision->priority && $revision->priority !== 'normal')
                                            @php
                                                $priorityConfig = [
                                                    'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'icon' => '⚡'],
                                                    'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => '🔥'],
                                                ];
                                                $pConfig = $priorityConfig[$revision->priority] ?? null;
                                            @endphp
                                            @if($pConfig)
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $pConfig['bg'] }} {{ $pConfig['text'] }}">
                                                    {{ $pConfig['icon'] }} {{ ucfirst($revision->priority) }}
                                                </span>
                                            @endif
                                        @endif

                                        <!-- Source Type Badge -->
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                                            {{ ucfirst($revision->source_type) }}
                                        </span>
                                    </div>

                                    <!-- Revision Details -->
                                    <h3 class="text-lg font-bold text-neutral-900 mb-2">
                                        @if($revision->document)
                                            {{ $revision->document->fileName }}
                                        @elseif($revision->task)
                                            {{ $revision->task->taskTitle }}
                                        @elseif($revision->project)
                                            {{ $revision->project->title }}
                                        @else
                                            Revision Request #{{ $revision->id }}
                                        @endif
                                    </h3>

                                    <p class="text-neutral-600 text-sm mb-3 line-clamp-2">
                                        {{ Str::limit($revision->reason, 150) }}
                                    </p>

                                    <!-- Meta Information -->
                                    <div class="flex items-center space-x-4 text-xs text-neutral-500">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Requested: {{ $revision->created_at->format('M d, Y') }}
                                        </div>
                                        
                                        @if($revision->requested_due_date)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Due: {{ $revision->requested_due_date->format('M d, Y') }}
                                            </div>
                                        @endif

                                        @if($revision->assignedAdiutor)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                Assigned to: {{ $revision->assignedAdiutor->fullName }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Admin Notes (if rejected) -->
                                    @if($revision->status === 'rejected' && $revision->admin_notes)
                                        <div class="mt-3 p-3 bg-error-50 border-l-4 border-error-500 rounded">
                                            <p class="text-sm font-semibold text-error-900 mb-1">Rejection Reason:</p>
                                            <p class="text-sm text-error-800">{{ $revision->admin_notes }}</p>
                                        </div>
                                    @endif

                                    <!-- Completion Info -->
                                    @if($revision->status === 'completed' && $revision->completed_at)
                                        <div class="mt-3 p-3 bg-success-50 border-l-4 border-success-500 rounded">
                                            <p class="text-sm font-semibold text-success-900">
                                                ✅ Completed on {{ $revision->completed_at->format('M d, Y \a\t g:i A') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="ml-6 flex flex-col space-y-2">
                                    <a href="{{ route('client.revisions.show', $revision->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-semibold rounded-lg hover:from-primary-600 hover:to-accent-700 transition-all shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Details
                                    </a>

                                    @if($revision->status === 'pending')
                                        <form action="{{ route('client.revisions.cancel', $revision->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this revision request?');">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-neutral-200 text-neutral-700 font-semibold rounded-lg hover:bg-neutral-300 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $revisions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-2xl font-bold text-neutral-900 mb-2">No Revision Requests Yet</h3>
                <p class="text-neutral-600 mb-6">You haven't submitted any revision requests.</p>
                <a href="{{ route('client.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-accent-700 transition-all shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Go to Dashboard
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

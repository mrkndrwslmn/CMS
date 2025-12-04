@extends('client.layouts.app')

@section('content')
<div class="min-h-screen bg-neutral-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
            ['label' => 'Revision Requests', 'icon' => 'git-pull-request']
        ]" />

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800">
                        My Revision Requests
                    </h1>
                    <p class="mt-1 text-sm text-neutral-500">Track and manage your revision requests</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-neutral-500">Total Requests</p>
                    <p class="text-2xl font-semibold text-neutral-800">{{ $revisions->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Revisions List -->
        @if($revisions->count() > 0)
            <div class="space-y-4">
                @foreach($revisions as $revision)
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
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
                                            @if($revision->priority === 'high')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <x-lucide-zap class="w-3 h-3 mr-1" />
                                                    High
                                                </span>
                                            @elseif($revision->priority === 'urgent')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <x-lucide-flame class="w-3 h-3 mr-1" />
                                                    Urgent
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
                                    <div class="flex items-center flex-wrap gap-4 text-xs text-neutral-500">
                                        <div class="flex items-center">
                                            <x-lucide-calendar class="w-4 h-4 mr-1" />
                                            Requested: {{ $revision->created_at->format('M d, Y') }}
                                        </div>
                                        
                                        @if($revision->requested_due_date)
                                            <div class="flex items-center">
                                                <x-lucide-clock class="w-4 h-4 mr-1" />
                                                Due: {{ $revision->requested_due_date->format('M d, Y') }}
                                            </div>
                                        @endif

                                        @if($revision->assignedAdiutor)
                                            <div class="flex items-center">
                                                <x-lucide-user class="w-4 h-4 mr-1" />
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
                                            <p class="text-sm font-medium text-success-900 flex items-center">
                                                <x-lucide-check-circle class="w-4 h-4 mr-2" />
                                                Completed on {{ $revision->completed_at->format('M d, Y \a\t g:i A') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="ml-6 flex flex-col space-y-2">
                                    <a href="{{ route('client.revisions.show', $revision->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                        <x-lucide-eye class="w-4 h-4 mr-2" />
                                        View Details
                                    </a>

                                    @if($revision->status === 'pending')
                                        <form action="{{ route('client.revisions.cancel', $revision->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this revision request?');">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-neutral-100 text-neutral-700 text-sm font-medium rounded-lg hover:bg-neutral-200 transition-colors">
                                                <x-lucide-x class="w-4 h-4 mr-2" />
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
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-12 text-center">
                <x-lucide-file-text class="w-16 h-16 mx-auto text-neutral-300 mb-4" />
                <h3 class="text-lg font-semibold text-neutral-800 mb-2">No Revision Requests Yet</h3>
                <p class="text-sm text-neutral-500 mb-6">You haven't submitted any revision requests.</p>
                <a href="{{ route('client.dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <x-lucide-home class="w-4 h-4 mr-2" />
                    Go to Dashboard
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

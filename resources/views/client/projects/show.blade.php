@extends('client.layout')

@section('title', 'Project Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('client.tasks') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Projects
        </a>
    </div>

    <!-- Project Header Card -->
    <div class="glass-card p-8 mb-6 border-l-4 {{ match($project->status) {
        'active' => 'border-primary-500',
        'in_progress' => 'border-accent-500',
        'review' => 'border-warning-500',
        'completed' => 'border-success-600',
        'cancelled' => 'border-error-500',
        default => 'border-neutral-500'
    } }}">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
                <!-- Project ID Badge -->
                <div class="inline-flex items-center space-x-3 mb-3">
                    <span class="text-xs font-mono font-bold text-neutral-500 bg-neutral-100 px-3 py-1.5 rounded-lg">
                        PROJ-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    @php
                        $statusConfig = match($project->status) {
                            'active' => ['bg' => 'bg-primary-500', 'text' => 'text-white', 'label' => 'Active'],
                            'in_progress' => ['bg' => 'bg-accent-500', 'text' => 'text-white', 'label' => 'In Progress'],
                            'review' => ['bg' => 'bg-warning-500', 'text' => 'text-white', 'label' => 'Under Review'],
                            'completed' => ['bg' => 'bg-success-600', 'text' => 'text-white', 'label' => 'Completed'],
                            'cancelled' => ['bg' => 'bg-error-500', 'text' => 'text-white', 'label' => 'Cancelled'],
                            default => ['bg' => 'bg-neutral-500', 'text' => 'text-white', 'label' => 'Unknown']
                        };
                    @endphp
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} shadow-md">
                        {{ $statusConfig['label'] }}
                    </span>
                    @if($project->priority)
                        @php
                            $priorityConfig = match($project->priority) {
                                'urgent' => ['bg' => 'bg-error-100', 'text' => 'text-error-800', 'border' => 'border-error-300'],
                                'high' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-800', 'border' => 'border-warning-300'],
                                'medium' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-800', 'border' => 'border-primary-300'],
                                'low' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'border' => 'border-neutral-300'],
                                default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'border' => 'border-neutral-300']
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $priorityConfig['bg'] }} {{ $priorityConfig['text'] }} border {{ $priorityConfig['border'] }}">
                            {{ ucfirst($project->priority) }} Priority
                        </span>
                    @endif
                </div>

                <!-- Project Title -->
                <h1 class="heading-serif text-3xl text-neutral-900 mb-3 leading-tight">{{ $project->title }}</h1>
                
                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-600">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Created {{ \Carbon\Carbon::parse($project->created_at)->format('M j, Y') }}</span>
                    </div>
                    @if($project->service_type)
                        <span class="text-neutral-300">•</span>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                            </svg>
                            <span>{{ ucfirst(str_replace('_', ' ', $project->service_type)) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Message Admin Button for Active Projects --}}
                @if(in_array($project->status, ['active', 'in_progress', 'review']))
                    <a href="{{ route('client.messages.show', $project->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-accent-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Message Admin
                    </a>
                @endif
                
                @if($feedback)
                    <a href="{{ route('client.feedback') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-success-500 text-success-600 font-semibold rounded-lg hover:bg-success-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Feedback Submitted
                    </a>
                @elseif($project->status === 'completed')
                    <a href="{{ route('client.feedback.create', $project->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-accent-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Leave Feedback
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Description -->
            <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Project Description
                    </h3>
                    <div class="prose prose-neutral prose-sm max-w-none">
                        <p class="text-neutral-700 leading-relaxed whitespace-pre-line">{{ $project->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Tasks -->
            @if($tasks && count($tasks) > 0)
                <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Project Tasks
                            <span class="ml-2 bg-neutral-100 text-neutral-700 px-2 py-1 rounded-full text-xs font-bold">{{ count($tasks) }}</span>
                        </h3>
                        <div class="space-y-3">
                            @foreach($tasks as $task)
                                <div class="border border-neutral-200 rounded-lg p-4 hover:border-neutral-300 hover:shadow-sm transition-all">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-neutral-900 mb-1">{{ $task->taskTitle }}</h4>
                                            <p class="text-sm text-neutral-600">{{ $task->taskDescription }}</p>
                                        </div>
                                        @php
                                            $taskStatusConfig = match($task->status) {
                                                'pending' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700'],
                                                'in_progress' => ['bg' => 'bg-accent-100', 'text' => 'text-accent-700'],
                                                'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-700'],
                                                'cancelled' => ['bg' => 'bg-error-100', 'text' => 'text-error-700'],
                                                default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700']
                                            };
                                        @endphp
                                        <span class="ml-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $taskStatusConfig['bg'] }} {{ $taskStatusConfig['text'] }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 text-xs text-neutral-500 mt-3">
                                        @if($task->assigned_to_name)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $task->assigned_to_name }}
                                            </div>
                                        @endif
                                        @if($task->deadline)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($task->deadline)->format('M j, Y') }}
                                            </div>
                                        @endif
                                        @if($task->progress_percentage > 0)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                {{ $task->progress_percentage }}% Complete
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Team Members -->
            @if($assignments && count($assignments) > 0)
                <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Team Members
                            <span class="ml-2 bg-neutral-100 text-neutral-700 px-2 py-1 rounded-full text-xs font-bold">{{ count($assignments) }}</span>
                        </h3>
                        <div class="space-y-3">
                            @foreach($assignments as $assignment)
                                <div class="border border-neutral-200 rounded-lg p-4 hover:border-neutral-300 hover:shadow-sm transition-all">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-neutral-900">{{ $assignment->adiutor_name }}</h4>
                                            @if($assignment->title)
                                                <p class="text-sm text-neutral-600">{{ $assignment->title }}</p>
                                            @endif
                                            @if($assignment->bio)
                                                <p class="text-sm text-neutral-500 mt-1">{{ Str::limit($assignment->bio, 100) }}</p>
                                            @endif
                                            <div class="flex items-center gap-3 mt-2 text-xs text-neutral-500">
                                                @php
                                                    $assignmentStatusConfig = match($assignment->status) {
                                                        'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-700'],
                                                        'accepted' => ['bg' => 'bg-success-100', 'text' => 'text-success-700'],
                                                        'in_progress' => ['bg' => 'bg-accent-100', 'text' => 'text-accent-700'],
                                                        'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-700'],
                                                        'declined' => ['bg' => 'bg-error-100', 'text' => 'text-error-700'],
                                                        default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700']
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $assignmentStatusConfig['bg'] }} {{ $assignmentStatusConfig['text'] }}">
                                                    {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                                </span>
                                                @if($assignment->agreed_rate)
                                                    <span>Rate: ₱{{ number_format($assignment->agreed_rate, 2) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Details Card -->
            <div class="glass-card overflow-hidden">
                <div class="bg-gradient-to-r from-neutral-700 to-neutral-800 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Project Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($project->budget)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Budget</dt>
                            <dd class="text-lg font-bold text-primary-600">₱{{ number_format($project->budget, 2) }}</dd>
                            @if($project->budget_type)
                                <dd class="text-xs text-neutral-500 mt-1">{{ ucfirst($project->budget_type) }}</dd>
                            @endif
                        </div>
                    @endif

                    @if($project->deadline)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Deadline</dt>
                            <dd class="text-sm font-bold {{ \Carbon\Carbon::parse($project->deadline)->isPast() ? 'text-error-600' : 'text-neutral-900' }}">
                                {{ \Carbon\Carbon::parse($project->deadline)->format('F j, Y') }}
                            </dd>
                            @if(\Carbon\Carbon::parse($project->deadline)->isPast())
                                <dd class="text-xs text-error-600 font-medium mt-1">Past Due</dd>
                            @else
                                <dd class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}</dd>
                            @endif
                        </div>
                    @endif

                    @if($project->started_at)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Started</dt>
                            <dd class="text-sm font-bold text-neutral-900">{{ \Carbon\Carbon::parse($project->started_at)->format('M j, Y') }}</dd>
                        </div>
                    @endif

                    @if($project->completed_at)
                        <div>
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Completed</dt>
                            <dd class="text-sm font-bold text-success-600">{{ \Carbon\Carbon::parse($project->completed_at)->format('M j, Y') }}</dd>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

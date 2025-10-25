@extends('adiutor.layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $assignment->title }}</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('adiutor.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('adiutor.tasks') }}" class="hover:text-accent-500 transition-colors">Projects</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">{{ Str::limit($assignment->title, 30) }}</span>
                </nav>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($assignment->assignment_status === 'active') bg-green-100 text-green-800
                    @elseif($assignment->assignment_status === 'pending') bg-amber-100 text-amber-800
                    @elseif($assignment->assignment_status === 'completed') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800 @endif">
                    <i class="fas fa-circle text-[6px] mr-2"></i>
                    {{ ucfirst($assignment->assignment_status) }}
                </span>
                <a href="{{ route('adiutor.tasks') }}" class="px-5 py-2.5 bg-neutral-600 text-white font-medium rounded-lg hover:bg-neutral-700 transition-colors shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Projects
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Project Overview -->
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                    Project Overview
                </h3>
                @if($assignment->description)
                    <p class="text-neutral-700 leading-relaxed whitespace-pre-wrap">{{ $assignment->description }}</p>
                @else
                    <p class="text-neutral-500 italic">No description provided</p>
                @endif
            </div>

            <!-- Project Details -->
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-clipboard-list text-primary-600 mr-2"></i>
                    Project Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Budget</p>
                        <p class="text-lg font-semibold text-neutral-900">
                            @if($assignment->agreed_rate)
                                ${{ number_format($assignment->agreed_rate, 2) }}
                            @else
                                ${{ number_format($assignment->budget, 2) }}
                            @endif
                            <span class="text-sm font-normal text-neutral-600">/ {{ ucfirst($assignment->budget_type) }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Priority</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($assignment->priority === 'urgent') bg-red-100 text-red-800
                            @elseif($assignment->priority === 'high') bg-orange-100 text-orange-800
                            @elseif($assignment->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($assignment->priority ?? 'Normal') }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Start Date</p>
                        <p class="text-base text-neutral-900">
                            @if($assignment->start_date)
                                {{ \Carbon\Carbon::parse($assignment->start_date)->format('M d, Y') }}
                            @else
                                <span class="text-neutral-400">Not started</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Deadline</p>
                        <p class="text-base text-neutral-900">
                            @if($assignment->deadline)
                                {{ \Carbon\Carbon::parse($assignment->deadline)->format('M d, Y') }}
                                <span class="text-sm text-neutral-500">({{ \Carbon\Carbon::parse($assignment->deadline)->diffForHumans() }})</span>
                            @else
                                <span class="text-neutral-400">No deadline set</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Assigned Date</p>
                        <p class="text-base text-neutral-900">{{ \Carbon\Carbon::parse($assignment->assigned_at)->format('M d, Y') }}</p>
                    </div>
                    @if($assignment->expected_completion)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Expected Completion</p>
                            <p class="text-base text-neutral-900">{{ \Carbon\Carbon::parse($assignment->expected_completion)->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress Section -->
            @if($assignment->assignment_status === 'active' || $assignment->assignment_status === 'completed')
                <div class="glass-card p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                            <i class="fas fa-chart-line text-primary-600 mr-2"></i>
                            Progress
                        </h3>
                        @if($assignment->assignment_status === 'active')
                            <button data-action="update-progress" data-assignment-id="{{ $assignment->assignment_id }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Update Progress
                            </button>
                        @endif
                    </div>
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-neutral-700">Overall Progress</p>
                            <p class="text-2xl font-bold text-primary-600">{{ $assignment->progress_percentage }}%</p>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-4 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-4 rounded-full transition-all duration-300 shadow-sm" 
                                 style="width: {{ $assignment->progress_percentage }}%"></div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Assignment Notes -->
            @if($assignment->assignment_notes)
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-primary-600 mr-2"></i>
                        Assignment Notes
                    </h3>
                    <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                        <p class="text-neutral-700 whitespace-pre-wrap">{{ $assignment->assignment_notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Project Notes -->
            @if($assignment->notes)
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-file-alt text-primary-600 mr-2"></i>
                        Project Notes
                    </h3>
                    <div class="p-4 bg-neutral-50 rounded-lg">
                        <p class="text-neutral-700 whitespace-pre-wrap">{{ $assignment->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Client Information -->
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-user text-primary-600 mr-2"></i>
                    Client Information
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Name</p>
                        <p class="text-base font-semibold text-neutral-900">{{ $assignment->client_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Email</p>
                        <a href="mailto:{{ $assignment->client_email }}" class="text-base text-primary-600 hover:text-primary-700 flex items-center">
                            <i class="fas fa-envelope text-sm mr-2"></i>
                            {{ $assignment->client_email }}
                        </a>
                    </div>
                    @if($assignment->client_phone)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Phone</p>
                            <a href="tel:{{ $assignment->client_phone }}" class="text-base text-primary-600 hover:text-primary-700 flex items-center">
                                <i class="fas fa-phone text-sm mr-2"></i>
                                {{ $assignment->client_phone }}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="mt-6 pt-6 border-t border-neutral-200">
                    <button class="w-full px-4 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-md">
                        <i class="fas fa-comment-alt mr-2"></i>
                        Contact Client
                    </button>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-bolt text-primary-600 mr-2"></i>
                    Quick Actions
                </h3>
                <div class="space-y-3">
                    @if($assignment->assignment_status === 'pending')
                        <button data-action="accept" data-assignment-id="{{ $assignment->assignment_id }}" class="w-full px-4 py-2.5 bg-success-600 text-white font-medium rounded-lg hover:bg-success-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            Accept Project
                        </button>
                        <button data-action="decline" data-assignment-id="{{ $assignment->assignment_id }}" class="w-full px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Decline Project
                        </button>
                    @elseif($assignment->assignment_status === 'active')
                        <a href="{{ route('adiutor.documents') }}" class="block w-full px-4 py-2.5 bg-neutral-600 text-white text-center font-medium rounded-lg hover:bg-neutral-700 transition-colors">
                            <i class="fas fa-file-alt mr-2"></i>
                            View Documents
                        </a>
                        <button class="w-full px-4 py-2.5 bg-warning-600 text-white font-medium rounded-lg hover:bg-warning-700 transition-colors">
                            <i class="fas fa-flag mr-2"></i>
                            Report Issue
                        </button>
                    @endif
                </div>
            </div>

            <!-- Project Stats -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-primary-600 mr-2"></i>
                    Stats
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-600">Days Since Assignment</span>
                        <span class="text-lg font-bold text-neutral-900">{{ \Carbon\Carbon::parse($assignment->assigned_at)->diffInDays(now()) }}</span>
                    </div>
                    @if($assignment->deadline)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600">Days Until Deadline</span>
                            <span class="text-lg font-bold {{ \Carbon\Carbon::parse($assignment->deadline)->isPast() ? 'text-red-600' : 'text-neutral-900' }}">
                                {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($assignment->deadline), false) }}
                            </span>
                        </div>
                    @endif
                    @if($assignment->assignment_status === 'active')
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600">Progress</span>
                            <span class="text-lg font-bold text-primary-600">{{ $assignment->progress_percentage }}%</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/adiutor/tasks.js') }}"></script>
@endsection

@extends('client.layout')

@section('title', 'Client Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900">Welcome back, {{ auth()->user()->firstName }}!</h1>
        <p class="text-neutral-600 mt-2">Here's an overview of your projects and service requests.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Projects -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Active Projects</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $stats['activeProjects'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-warning-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Pending Requests</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $stats['pendingRequests'] }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-success-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Completed Projects</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $stats['completedProjects'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Investment -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Total Investment</p>
                    <p class="text-2xl font-semibold text-neutral-900">₱{{ number_format($stats['totalSpent'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Projects -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-neutral-900">Recent Projects</h2>
                    <a href="{{ route('client.tasks') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        View All
                    </a>
                </div>

                @if($recentProjects->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentProjects as $project)
                            <div class="border border-neutral-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-neutral-900 mb-1">{{ $project->title }}</h3>
                                        <p class="text-sm text-neutral-600">{{ Str::limit($project->description, 100) }}</p>
                                    </div>
                                    @php
                                        $statusConfig = match($project->status) {
                                            'completed' => ['class' => 'bg-success-100 text-success-700', 'label' => 'Completed'],
                                            'in_progress' => ['class' => 'bg-primary-100 text-primary-700', 'label' => 'In Progress'],
                                            default => ['class' => 'bg-neutral-100 text-neutral-700', 'label' => ucfirst(str_replace('_', ' ', $project->status))]
                                        };
                                    @endphp
                                    <span class="ml-4 px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-4 text-xs text-neutral-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $project->created_at->format('M j, Y') }}</span>
                                    </div>
                                    @if($project->assignedAdiutor)
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span>{{ $project->assignedAdiutor->user->fullName }}</span>
                                        </div>
                                    @endif
                                    @if($project->progress_percentage)
                                        <div class="flex items-center gap-2 ml-auto">
                                            <div class="w-20 bg-neutral-200 rounded-full h-1.5">
                                                <div class="bg-primary-500 h-1.5 rounded-full transition-all" style="width: {{ $project->progress_percentage }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-neutral-700">{{ $project->progress_percentage }}%</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-neutral-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="text-neutral-600 mb-4">No projects yet</p>
                        <a href="{{ route('client.requests.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Your First Request
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h2 class="text-xl font-semibold text-neutral-900 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('client.requests.create') }}" 
                       class="flex items-center p-3 rounded-lg border border-neutral-200 hover:bg-neutral-50 transition-colors group">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-neutral-900">New Service Request</p>
                            <p class="text-sm text-neutral-600">Start a new project</p>
                        </div>
                    </a>

                    <a href="{{ route('client.tasks') }}" 
                       class="flex items-center p-3 rounded-lg border border-neutral-200 hover:bg-neutral-50 transition-colors group">
                        <div class="w-10 h-10 bg-accent-100 rounded-lg flex items-center justify-center group-hover:bg-accent-200 transition-colors">
                            <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-neutral-900">View Projects</p>
                            <p class="text-sm text-neutral-600">Track progress</p>
                        </div>
                    </a>

                    <a href="{{ route('client.feedback') }}" 
                       class="flex items-center p-3 rounded-lg border border-neutral-200 hover:bg-neutral-50 transition-colors group">
                        <div class="w-10 h-10 bg-warning-100 rounded-lg flex items-center justify-center group-hover:bg-warning-200 transition-colors">
                            <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-neutral-900">Leave Feedback</p>
                            <p class="text-sm text-neutral-600">Rate your experience</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h2 class="text-xl font-semibold text-neutral-900 mb-4">Recent Messages</h2>
                @if($recentMessages->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentMessages as $message)
                            <div class="border-l-4 border-primary-500 pl-4 py-2 bg-neutral-50 rounded-r">
                                <p class="text-sm text-neutral-900 font-medium mb-1">{{ Str::limit($message->message, 60) }}</p>
                                <p class="text-xs text-neutral-500">
                                    From {{ $message->sender->fullName }} • {{ $message->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="w-10 h-10 text-neutral-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-neutral-600 text-sm">No recent messages</p>
                    </div>
                @endif
            </div>

            <!-- Upcoming Deadlines -->
            @if($upcomingDeadlines->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h2 class="text-xl font-semibold text-neutral-900 mb-4">Upcoming Deadlines</h2>
                    <div class="space-y-3">
                        @foreach($upcomingDeadlines as $project)
                            <div class="flex items-center justify-between p-3 bg-warning-50 rounded-lg border border-warning-100">
                                <div>
                                    <p class="font-medium text-neutral-900">{{ $project->title }}</p>
                                    <p class="text-sm text-neutral-600">Due {{ $project->deadline->format('M j, Y') }}</p>
                                </div>
                                <span class="text-xs bg-warning-200 text-warning-800 px-2 py-1 rounded-full font-medium">
                                    {{ $project->deadline->diffForHumans() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
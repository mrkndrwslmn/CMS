@extends('adiutor.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900">Welcome back, {{ auth()->user()->fullName }}!</h1>
        <p class="text-neutral-600 mt-2">Here's what's happening with your projects today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Projects -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Active Projects</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['active_projects'] }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-success-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Completed</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['completed_projects'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Assignments -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-warning-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Pending</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['pending_assignments'] }}</p>
                </div>
            </div>
        </div>

        <!-- This Month Earnings -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">This Month</p>
                    <p class="text-2xl font-bold text-neutral-900">${{ number_format($thisMonthEarnings, 2) }}</p>
                    <p class="text-xs text-neutral-500">Total: ${{ number_format($stats['total_earnings'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Items Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Urgent Tasks -->
        <div class="glass-card p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Urgent Tasks
                </h3>
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $urgentTasks->count() }}</span>
            </div>
            @if($urgentTasks->count() > 0)
                <div class="space-y-3">
                    @foreach($urgentTasks->take(3) as $task)
                        <div class="p-3 bg-red-50 rounded-lg border border-red-200">
                            <h4 class="font-medium text-red-900 text-sm">{{ $task->title }}</h4>
                            <p class="text-xs text-red-700 mt-1">Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M j, Y') }}</p>
                            <p class="text-xs text-red-600">{{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}</p>
                        </div>
                    @endforeach
                    @if($urgentTasks->count() > 3)
                        <p class="text-xs text-red-600 text-center">+{{ $urgentTasks->count() - 3 }} more urgent tasks</p>
                    @endif
                </div>
            @else
                <p class="text-sm text-neutral-500">No urgent tasks</p>
            @endif
        </div>

        <!-- Budget Requests -->
        <div class="glass-card p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Budget Requests
                </h3>
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $pendingBudgetRequests->count() }}</span>
            </div>
            @if($pendingBudgetRequests->count() > 0)
                <div class="space-y-3">
                    @foreach($pendingBudgetRequests->take(2) as $request)
                        <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <h4 class="font-medium text-yellow-900 text-sm">{{ $request->project_title }}</h4>
                            <p class="text-xs text-yellow-700 mt-1">${{ number_format($request->requested_amount, 2) }} - {{ ucfirst($request->type) }}</p>
                            <p class="text-xs text-yellow-600">{{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}</p>
                        </div>
                    @endforeach
                    @if($pendingBudgetRequests->count() > 2)
                        <p class="text-xs text-yellow-600 text-center">+{{ $pendingBudgetRequests->count() - 2 }} more requests</p>
                    @endif
                </div>
            @else
                <p class="text-sm text-neutral-500">No pending requests</p>
            @endif
        </div>

        <!-- Performance Summary -->
        <div class="glass-card p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-neutral-900 flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Performance
                </h3>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-neutral-600">Completion Rate</span>
                    <span class="text-sm font-semibold text-green-700">{{ number_format($completionRate, 1) }}%</span>
                </div>
                <div class="w-full bg-neutral-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $completionRate }}%"></div>
                </div>
                <div class="pt-2 space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-neutral-500">Recent Revisions</span>
                        <span class="text-neutral-700">{{ $recentRevisions->count() }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-neutral-500">Active Projects</span>
                        <span class="text-neutral-700">{{ $stats['active_projects'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Projects -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-neutral-900">Recent Projects</h2>
                <a href="{{ route('adiutor.projects.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    View all →
                </a>
            </div>
            
            @if($recentProjects->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProjects as $project)
                        <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-lg hover:bg-neutral-100 transition-colors">
                            <div class="flex-1">
                                <h3 class="font-medium text-neutral-900">{{ $project->title }}</h3>
                                <p class="text-sm text-neutral-600">Client: {{ $project->client_name }}</p>
                                @if($project->project_deadline)
                                    <p class="text-xs text-neutral-500 mt-1">
                                        Deadline: {{ \Carbon\Carbon::parse($project->project_deadline)->format('M j, Y') }}
                                        @if(\Carbon\Carbon::parse($project->project_deadline)->isPast())
                                            <span class="text-red-600 font-medium">(Overdue)</span>
                                        @elseif(\Carbon\Carbon::parse($project->project_deadline)->diffInDays() <= 3)
                                            <span class="text-yellow-600 font-medium">(Due Soon)</span>
                                        @endif
                                    </p>
                                @endif
                                <div class="flex items-center mt-2">
                                    <div class="w-20 bg-neutral-200 rounded-full h-2 mr-3">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-xs text-neutral-600">{{ $project->progress_percentage }}%</span>
                                    @if($project->agreed_rate)
                                        <span class="text-xs text-neutral-500 ml-3">${{ number_format($project->agreed_rate, 2) }}/hr</span>
                                    @endif
                                </div>
                            </div>
                            <div class="shrink-0 ml-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($project->assignment_status === 'active') bg-green-100 text-green-800
                                    @elseif($project->assignment_status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($project->assignment_status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-neutral-500 mt-2">No projects assigned yet</p>
                </div>
            @endif
        </div>

        <!-- Recent Notifications -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-neutral-900">Recent Notifications</h2>
                <button class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    Mark all read
                </button>
            </div>
            
            @if($notifications->count() > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        @php
                            $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                            $title = $data['title'] ?? 'Notification';
                            $message = $data['message'] ?? 'You have a new notification';
                        @endphp
                        <div class="flex items-start space-x-3 p-3 bg-neutral-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h5v14z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900">{{ $title }}</p>
                                <p class="text-sm text-neutral-600">{{ $message }}</p>
                                <p class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h5v14z"/>
                    </svg>
                    <p class="text-neutral-500 mt-2">No new notifications</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <div class="glass-card p-6">
            <h2 class="text-lg font-semibold text-neutral-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('adiutor.profile.edit') }}" 
                   class="flex items-center justify-center p-4 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors group">
                    <svg class="w-6 h-6 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-primary-700 font-medium group-hover:text-primary-800">Update Profile</span>
                </a>
                
                <a href="{{ route('adiutor.projects.index') }}" 
                   class="flex items-center justify-center p-4 bg-accent-50 rounded-lg hover:bg-accent-100 transition-colors group">
                    <svg class="w-6 h-6 text-accent-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="text-accent-700 font-medium group-hover:text-accent-800">View Projects</span>
                </a>
                
                <a href="{{ route('adiutor.tasks.index') }}" 
                   class="flex items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors group">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="text-blue-700 font-medium group-hover:text-blue-800">View Tasks</span>
                </a>
                
                <a href="{{ route('adiutor.clients') }}" 
                   class="flex items-center justify-center p-4 bg-success-50 rounded-lg hover:bg-success-100 transition-colors group">
                    <svg class="w-6 h-6 text-success-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-success-700 font-medium group-hover:text-success-800">Manage Clients</span>
                </a>
                
                <a href="{{ route('adiutor.documents') }}" 
                   class="flex items-center justify-center p-4 bg-warning-50 rounded-lg hover:bg-warning-100 transition-colors group">
                    <svg class="w-6 h-6 text-warning-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-warning-700 font-medium group-hover:text-warning-800">Documents</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
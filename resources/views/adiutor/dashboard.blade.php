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

        <!-- Total Earnings -->
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
                    <p class="text-sm font-medium text-neutral-600">Total Earnings</p>
                    <p class="text-2xl font-bold text-neutral-900">${{ number_format($stats['total_earnings'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Projects -->
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-neutral-900">Recent Projects</h2>
<<<<<<< HEAD
                <a href="{{ route('adiutor.tasks.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
=======
                <a href="{{ route('adiutor.tasks') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
>>>>>>> 7c71488 (Initial commit from Princess)
                    View all →
                </a>
            </div>
            
            @if($recentProjects->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProjects as $project)
                        <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-medium text-neutral-900">{{ $project->title }}</h3>
                                <p class="text-sm text-neutral-600">Client: {{ $project->client_name }}</p>
                                <div class="flex items-center mt-2">
                                    <div class="w-20 bg-neutral-200 rounded-full h-2 mr-3">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-xs text-neutral-600">{{ $project->progress_percentage }}%</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($project->assignment_status === 'active') bg-success-100 text-success-800
                                    @elseif($project->assignment_status === 'pending') bg-warning-100 text-warning-800
                                    @else bg-neutral-100 text-neutral-800 @endif">
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
                        <div class="flex items-start space-x-3 p-3 bg-neutral-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h5v14z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900">{{ $notification->title }}</p>
                                <p class="text-sm text-neutral-600">{{ $notification->message }}</p>
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
                
<<<<<<< HEAD
                <a href="{{ route('adiutor.tasks.index') }}" 
=======
                <a href="{{ route('adiutor.tasks') }}" 
>>>>>>> 7c71488 (Initial commit from Princess)
                   class="flex items-center justify-center p-4 bg-accent-50 rounded-lg hover:bg-accent-100 transition-colors group">
                    <svg class="w-6 h-6 text-accent-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="text-accent-700 font-medium group-hover:text-accent-800">View Projects</span>
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
@extends('adiutor.layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('adiutor.dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('adiutor.projects.index') }}" class="hover:text-primary-600 transition-colors">Projects</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 font-medium">{{ $project->title }}</span>
    </nav>

    <!-- Project Header -->
    <div class="bg-white rounded-xl border border-gray-200 p-8 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($project->assignment_status === 'active') bg-green-100 text-green-800
                        @elseif($project->assignment_status === 'pending') bg-amber-100 text-amber-800
                        @elseif($project->assignment_status === 'completed') bg-primary-100 text-primary-800
                        @else bg-gray-100 text-gray-800 @endif">
                        <span class="w-2 h-2 rounded-full mr-2
                            @if($project->assignment_status === 'active') bg-green-600
                            @elseif($project->assignment_status === 'pending') bg-amber-600
                            @elseif($project->assignment_status === 'completed') bg-primary-600
                            @else bg-gray-600 @endif">
                        </span>
                        {{ ucfirst($project->assignment_status) }}
                    </span>
                </div>
                <p class="text-gray-600">{{ $project->service_type }}</p>
            </div>
            
            @if($project->assignment_status === 'active')
                <button onclick="updateProgress()" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Update Progress
                </button>
            @endif
        </div>

        <!-- Project Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600 mb-1 flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Budget
                </p>
                <p class="text-2xl font-bold text-gray-900">
                    ${{ number_format($project->agreed_rate ?? $project->budget, 2) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">{{ ucfirst($project->budget_type) }}</p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600 mb-1 flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Start Date
                </p>
                <p class="text-2xl font-bold text-gray-900">
                    @if($project->start_date)
                        {{ \Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}
                    @else
                        <span class="text-gray-400 text-lg">Not started</span>
                    @endif
                </p>
                @if($project->start_date)
                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($project->start_date)->diffForHumans() }}</p>
                @endif
            </div>

            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600 mb-1 flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Deadline
                </p>
                <p class="text-2xl font-bold text-gray-900">
                    @if($project->deadline)
                        {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                    @else
                        <span class="text-gray-400 text-lg">Not set</span>
                    @endif
                </p>
                @if($project->deadline)
                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}</p>
                @endif
            </div>

            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600 mb-1 flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Progress
                </p>
                <p class="text-2xl font-bold text-primary-600">{{ $project->progress_percentage }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $project->progress_percentage }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Description -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Project Description
                </h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $project->description ?? $project->request_description ?? 'No description provided.' }}</p>
            </div>

            <!-- Milestones (if milestone payment) -->
            @if($project->payment_type === 'milestone' && count($milestones) > 0)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        Project Milestones
                    </h2>
                    <div class="space-y-4">
                        @foreach($milestones as $milestone)
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                        <span class="text-primary-600 font-bold">{{ $milestone->phase_order }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $milestone->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $milestone->description }}</p>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span class="text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                            ${{ number_format($milestone->amount, 2) }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($milestone->due_date)->format('M d, Y') }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($milestone->status === 'completed') bg-green-100 text-green-800
                                            @elseif($milestone->status === 'in_progress') bg-primary-100 text-primary-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tasks -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        My Tasks ({{ count($tasks) }})
                    </h2>
                    <div class="flex items-center space-x-3">
                        @if($project->assignment_status === 'active')
                            <button onclick="document.getElementById('createTaskModal').classList.remove('hidden')" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create Task
                            </button>
                        @endif
                        <a href="{{ route('adiutor.tasks.index') }}?project={{ $project->id }}" class="text-sm text-primary-600 hover:text-primary-700">
                            View All Tasks
                            <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                @if(count($tasks) > 0)
                    <div class="space-y-3">
                        @foreach($tasks->take(5) as $task)
                            @if(isset($task->id))
                                <a href="{{ route('adiutor.tasks.show', $task->id) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            @else
                                <div class="block p-4 bg-gray-50 rounded-lg">
                            @endif
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $task->title ?? 'Untitled Task' }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description ?? '', 100) }}</p>
                                        <div class="flex items-center space-x-3 mt-2">
                                            @if(isset($task->deadline))
                                                <span class="text-xs text-gray-500">
                                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    Due: {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}
                                                </span>
                                            @endif
                                            @if($task->priority ?? null)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    @if($task->priority === 'urgent') bg-red-100 text-red-800
                                                    @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        @if(($task->status ?? 'pending') === 'completed') bg-green-100 text-green-800
                                        @elseif(($task->status ?? 'pending') === 'in_progress') bg-primary-100 text-primary-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $task->status ?? 'pending')) }}
                                    </span>
                                </div>
                            @if(isset($task->id))
                                </a>
                            @else
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <p class="text-gray-500">No tasks assigned yet</p>
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            @if(count($activities) > 0)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Recent Activity
                    </h2>
                    <div class="space-y-3">
                        @foreach($activities->take(5) as $activity)
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-4 h-4 text-primary-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-700">{{ $activity->content }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column (1/3) -->
        <div class="space-y-6">
            <!-- Client Information -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Client
                </h2>
                <div class="flex items-center space-x-4 mb-4">
                    <img src="{{ $project->client_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->client_name) }}" 
                         alt="{{ $project->client_name }}" 
                         class="w-16 h-16 rounded-full">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $project->client_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $project->client_email }}</p>
                    </div>
                </div>
                @if($project->client_phone)
                    <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>{{ $project->client_phone }}</span>
                    </div>
                @endif
                <button onclick="contactClient()" class="w-full mt-4 px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact Client
                </button>
            </div>

            <!-- Team Members -->
            @if(count($teamMembers) > 1)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        Team Members
                    </h2>
                    <div class="space-y-3">
                        @foreach($teamMembers as $member)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $member->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->fullName) }}" 
                                     alt="{{ $member->fullName }}" 
                                     class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $member->fullName }}</p>
                                    <p class="text-xs text-gray-500">{{ $member->role ?? 'Team Member' }}</p>
                                </div>
                                @if(isset($member->id) && $member->id == $user->id)
                                    <span class="text-xs bg-primary-100 text-primary-800 px-2 py-1 rounded">You</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Assignment Notes -->
            @if($project->assignment_notes)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Assignment Notes
                    </h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $project->assignment_notes }}</p>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Quick Actions
                </h2>
                <div class="space-y-2">
                    <a href="{{ route('adiutor.documents') }}" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-center font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        View Documents
                    </a>
                    <a href="{{ route('adiutor.tasks.index') }}?project={{ $project->id }}" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-center font-medium text-gray-700">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        View All Tasks
                    </a>
                    @if($project->assignment_status === 'active')
                        <button onclick="requestBudgetChange()" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-center font-medium text-gray-700">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            Request Budget Change
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div id="createTaskModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl border border-gray-200 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Create New Task</h3>
                    <button onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-amber-600 mt-2">
                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    This task will be sent to admin for approval before it becomes active.
                </p>
            </div>
            
            <form action="{{ route('adiutor.projects.create-task', $project->id) }}" method="POST" class="p-6">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Task Title *</label>
                    <input type="text" name="task_title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Enter task title...">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Task Description *</label>
                    <textarea name="task_description" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Describe the task in detail..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select name="priority" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                        <input type="date" name="deadline" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Requested Budget (Optional)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500">₱</span>
                        <input type="number" name="allocated_budget" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0.00">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Leave blank if no specific budget is needed</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Add any additional notes or requirements..."></textarea>
                </div>
                
                <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded mb-6">
                    <p class="text-sm text-amber-800">
                        <strong>Note:</strong> This task will be created with "Pending Approval" status. An admin will review and approve it before it becomes an active task.
                    </p>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Submit for Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateProgress() {
    // Implement progress update modal
    alert('Progress update functionality coming soon!');
}

function contactClient() {
    // Implement contact client functionality
    window.location.href = 'mailto:{{ $project->client_email }}';
}

function requestBudgetChange() {
    // Implement budget change request
    alert('Budget change request functionality coming soon!');
}

// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('createTaskModal');
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });
});
</script>
@endsection

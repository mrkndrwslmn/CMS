@extends('adiutor.layouts.app')

<<<<<<< HEAD
@section('title', 'My Tasks')
=======
@section('title', 'My Projects')
>>>>>>> 7c71488 (Initial commit from Princess)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
<<<<<<< HEAD
                <h1 class="text-3xl font-bold text-gray-900">My Tasks</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('adiutor.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">Tasks</span>
                </nav>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Tasks</p>
                <p class="text-3xl font-bold text-accent-500">{{ $tasks->count() }}</p>
=======
                <h1 class="text-3xl font-bold text-gray-900">My Projects</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('adiutor.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">Projects</span>
                </nav>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Active Projects</p>
                <p class="text-3xl font-bold text-accent-500">{{ $projects->where('assignment_status', 'active')->count() }}</p>
>>>>>>> 7c71488 (Initial commit from Princess)
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
<<<<<<< HEAD
        <!-- Pending Tasks -->
=======
        <!-- Total Projects -->
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
                    <p class="text-sm font-medium text-neutral-600">Total Projects</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $projects->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
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
                    <p class="text-sm font-medium text-neutral-600">Active</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $projects->where('assignment_status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Projects -->
>>>>>>> 7c71488 (Initial commit from Princess)
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
<<<<<<< HEAD
                    <p class="text-2xl font-bold text-neutral-900">{{ $tasks->where('status', 'pending')->count() }}</p>
=======
                    <p class="text-2xl font-bold text-neutral-900">{{ $projects->where('assignment_status', 'pending')->count() }}</p>
>>>>>>> 7c71488 (Initial commit from Princess)
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- In Progress Tasks -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">In Progress</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $tasks->where('status', 'in_progress')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-success-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
=======
        <!-- Completed Projects -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
>>>>>>> 7c71488 (Initial commit from Princess)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Completed</p>
<<<<<<< HEAD
                    <p class="text-2xl font-bold text-neutral-900">{{ $tasks->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Urgent Tasks -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-error-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-error-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Urgent</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $tasks->where('priority', 'urgent')->count() }}</p>
=======
                    <p class="text-2xl font-bold text-neutral-900">{{ $projects->where('assignment_status', 'completed')->count() }}</p>
>>>>>>> 7c71488 (Initial commit from Princess)
                </div>
            </div>
        </div>
    </div>

<<<<<<< HEAD
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" action="{{ route('adiutor.tasks.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Project Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                <select name="project" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                            {{ $project->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500">
                    <option value="all">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500">
                    <option value="all">All Priorities</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-accent-600 text-white font-medium rounded-lg hover:bg-accent-700 transition-colors shadow-lg shadow-accent-500/30">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    @if($tasks->count() > 0)
        <!-- Tasks Grid -->
        <div class="grid gap-6">
            @foreach($tasks as $task)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                    <div class="p-6">
                        <!-- Task Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $task->taskTitle }}</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($task->status === 'completed') bg-green-100 text-green-800
                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        <i class="fas fa-circle text-[6px] mr-2"></i>
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                    @if($task->priority)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                            @if($task->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                            @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 flex items-center space-x-3">
                                    <span>
                                        <i class="fas fa-project-diagram text-accent-500 mr-1"></i>
                                        {{ $task->project_title }}
                                    </span>
                                    <span>
                                        <i class="fas fa-user text-accent-500 mr-1"></i>
                                        {{ $task->client_name }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Task Description -->
                        @if($task->taskDescription)
                            <p class="text-gray-700 mb-4">{{ Str::limit($task->taskDescription, 200) }}</p>
                        @endif

                        <!-- Task Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-xs font-medium text-gray-500 mb-1 flex items-center">
                                    <i class="fas fa-calendar-alt text-accent-500 mr-1"></i>
                                    Deadline
                                </p>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($task->deadline)
                                        {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}
                                    @else
                                        <span class="text-gray-400 text-sm">Not set</span>
                                    @endif
                                </p>
                                @if($task->deadline)
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 mb-1 flex items-center">
=======
    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-lg mb-6">
        <div class="border-b border-gray-200 px-6">
            <nav class="-mb-px flex space-x-8">
                <button class="filter-tab active border-accent-500 text-accent-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="all">
                    All Projects
                </button>
                <button class="filter-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="active">
                    Active
                </button>
                <button class="filter-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="pending">
                    Pending
                </button>
                <button class="filter-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="completed">
                    Completed
                </button>
            </nav>
        </div>
    </div>

    @if($projects->count() > 0)
        <!-- Projects Grid -->
        <div class="grid gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden project-item hover:shadow-xl transition-shadow duration-200" data-status="{{ $project->assignment_status }}">
                    <div class="p-6">
                        <!-- Project Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $project->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1 flex items-center">
                                    <i class="fas fa-user text-accent-500 mr-2"></i>
                                    Client: {{ $project->client_name }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($project->assignment_status === 'active') bg-green-100 text-green-800
                                    @elseif($project->assignment_status === 'pending') bg-amber-100 text-amber-800
                                    @elseif($project->assignment_status === 'completed') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <i class="fas fa-circle text-[6px] mr-2"></i>
                                    {{ ucfirst($project->assignment_status) }}
                                </span>
                                @if($project->priority)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($project->priority === 'urgent') bg-red-100 text-red-800
                                        @elseif($project->priority === 'high') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ ucfirst($project->priority) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Project Description -->
                        @if($project->description)
                            <p class="text-gray-700 mb-4">{{ Str::limit($project->description, 200) }}</p>
                        @endif

                        <!-- Project Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-xs font-medium text-gray-500 mb-1 flex items-center">
>>>>>>> 7c71488 (Initial commit from Princess)
                                    <i class="fas fa-dollar-sign text-accent-500 mr-1"></i>
                                    Budget
                                </p>
                                <p class="text-lg font-semibold text-gray-900">
<<<<<<< HEAD
                                    @if($task->allocated_budget)
                                        ₱{{ number_format($task->allocated_budget, 2) }}
                                    @else
                                        <span class="text-gray-400 text-sm">Not set</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 mb-1 flex items-center">
                                    <i class="fas fa-clock text-accent-500 mr-1"></i>
                                    Assigned
                                </p>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($task->dateAssigned)->format('M d, Y') }}
                                </p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($task->dateAssigned)->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('adiutor.tasks.show', $task->taskID) }}" class="px-4 py-2 bg-accent-600 text-white text-sm font-medium rounded-lg hover:bg-accent-700 transition-colors shadow-md hover:shadow-lg">
                                    <i class="fas fa-eye mr-1"></i>
                                    View Details
                                </a>
                                @if($task->status !== 'completed')
                                    <form method="POST" action="{{ route('adiutor.tasks.complete', $task->taskID) }}" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Mark this task as completed?')" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-md hover:shadow-lg">
                                            <i class="fas fa-check mr-1"></i>
                                            Mark Complete
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            <a href="{{ route('adiutor.projects.show', $task->project_id) }}" class="flex items-center space-x-2 text-sm text-gray-600 hover:text-accent-600 transition-colors px-3 py-2 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-project-diagram"></i>
                                <span>View Project</span>
                            </a>
=======
                                    @if($project->agreed_rate)
                                        ${{ number_format($project->agreed_rate, 2) }}
                                    @else
                                        ${{ number_format($project->budget, 2) }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ ucfirst($project->budget_type) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 mb-1 flex items-center">
                                    <i class="fas fa-calendar-alt text-accent-500 mr-1"></i>
                                    Deadline
                                </p>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($project->deadline)
                                        {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                                    @else
                                        <span class="text-gray-400">Not set</span>
                                    @endif
                                </p>
                                @if($project->deadline)
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 mb-1 flex items-center">
                                    <i class="fas fa-play-circle text-accent-500 mr-1"></i>
                                    Start Date
                                </p>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($project->start_date)
                                        {{ \Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}
                                    @else
                                        <span class="text-gray-400">Not started</span>
                                    @endif
                                </p>
                                @if($project->start_date)
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($project->start_date)->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @if($project->assignment_status === 'active')
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-chart-line text-accent-500 mr-2"></i>
                                        Progress
                                    </p>
                                    <p class="text-sm font-semibold text-accent-600">{{ $project->progress_percentage }}%</p>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-accent-500 to-accent-600 h-3 rounded-full transition-all duration-300 shadow-sm" 
                                         style="width: {{ $project->progress_percentage }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Project Notes -->
                        @if($project->notes)
                            <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                                <p class="text-sm font-medium text-blue-900 mb-1 flex items-center">
                                    <i class="fas fa-sticky-note mr-2"></i>
                                    Notes
                                </p>
                                <p class="text-sm text-blue-800">{{ $project->notes }}</p>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-3">
                                @if($project->assignment_status === 'pending')
                                    <button data-action="accept" data-assignment-id="{{ $project->assignment_id }}" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-md hover:shadow-lg">
                                        <i class="fas fa-check mr-1"></i>
                                        Accept Project
                                    </button>
                                    <button data-action="decline" data-assignment-id="{{ $project->assignment_id }}" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-md hover:shadow-lg">
                                        <i class="fas fa-times mr-1"></i>
                                        Decline
                                    </button>
                                @elseif($project->assignment_status === 'active')
                                    <button data-action="update-progress" data-assignment-id="{{ $project->assignment_id }}" class="px-4 py-2 bg-accent-600 text-white text-sm font-medium rounded-lg hover:bg-accent-700 transition-colors shadow-md hover:shadow-lg">
                                        <i class="fas fa-edit mr-1"></i>
                                        Update Progress
                                    </button>
                                    <a href="{{ route('adiutor.documents') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        Documents
                                    </a>
                                @elseif($project->assignment_status === 'completed')
                                    <button data-action="view-details" data-assignment-id="{{ $project->assignment_id }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Details
                                    </button>
                                @endif
                            </div>
                            
                            <button class="flex items-center space-x-2 text-sm text-gray-600 hover:text-accent-600 transition-colors px-3 py-2 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-comment-alt"></i>
                                <span>Contact Client</span>
                            </button>
>>>>>>> 7c71488 (Initial commit from Princess)
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-lg p-12">
            <div class="text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
<<<<<<< HEAD
                    <i class="fas fa-tasks text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No tasks found</h3>
                <p class="text-gray-600 mb-6">
                    @if(request('project') || request('status') || request('priority'))
                        No tasks match your current filters. Try adjusting your search criteria.
                    @else
                        You don't have any tasks assigned yet.
                    @endif
                </p>
                @if(request('project') || request('status') || request('priority'))
                    <a href="{{ route('adiutor.tasks.index') }}" class="inline-flex items-center px-6 py-3 bg-accent-600 text-white font-medium rounded-lg hover:bg-accent-700 transition-colors shadow-lg shadow-accent-500/30">
                        <i class="fas fa-redo mr-2"></i>
                        Clear Filters
                    </a>
                @else
                    <a href="{{ route('adiutor.projects.index') }}" class="inline-flex items-center px-6 py-3 bg-accent-600 text-white font-medium rounded-lg hover:bg-accent-700 transition-colors shadow-lg shadow-accent-500/30">
                        <i class="fas fa-project-diagram mr-2"></i>
                        View Projects
                    </a>
                @endif
=======
                    <i class="fas fa-briefcase text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No projects assigned</h3>
                <p class="text-gray-600 mb-6">You'll see your assigned projects here once they're available.</p>
                <a href="{{ route('adiutor.profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-accent-600 text-white font-medium rounded-lg hover:bg-accent-700 transition-colors shadow-lg shadow-accent-500/30">
                    <i class="fas fa-user-edit mr-2"></i>
                    Complete Your Profile
                </a>
>>>>>>> 7c71488 (Initial commit from Princess)
            </div>
        </div>
    @endif
</div>
@endsection
<<<<<<< HEAD
=======

@section('scripts')
<script src="{{ asset('js/adiutor/tasks.js') }}"></script>
@endsection
>>>>>>> 7c71488 (Initial commit from Princess)

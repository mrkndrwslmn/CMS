@extends('client.layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Welcome back, {{ explode(' ', auth()->user()->fullName)[0] }}!</h1>
        <p class="text-sm text-neutral-500 mt-1">Here's an overview of your projects and service requests.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Projects -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Active Projects</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['activeProjects'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-folder-kanban class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending Requests</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['pendingRequests'] }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Completed Projects</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['completedProjects'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>

        <!-- Total Investment -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Investment</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($stats['totalSpent'], 2) }}</p>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl">
                    <x-lucide-wallet class="w-5 h-5 text-neutral-400" />
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Projects -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium text-neutral-700">Recent Projects</h2>
                    <a href="{{ route('client.tasks') }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                        View All
                        <x-lucide-arrow-right class="w-4 h-4" />
                    </a>
                </div>

                @if($recentProjects->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentProjects as $project)
                            <a href="{{ route('client.projects.show', $project->id) }}" class="block group">
                                <div class="border border-neutral-200 rounded-xl p-5 hover:border-primary-300 hover:shadow-md transition-all">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-medium text-neutral-700 mb-1 group-hover:text-primary-600 transition-colors">{{ $project->title }}</h3>
                                            <p class="text-sm text-neutral-500 line-clamp-2">{{ Str::limit($project->description, 100) }}</p>
                                        </div>
                                        @php
                                            $statusConfig = match($project->status) {
                                                'completed' => ['class' => 'bg-success-100 text-success-700', 'label' => 'Completed'],
                                                'in_progress' => ['class' => 'bg-primary-100 text-primary-700', 'label' => 'In Progress'],
                                                default => ['class' => 'bg-neutral-100 text-neutral-600', 'label' => ucfirst(str_replace('_', ' ', $project->status))]
                                            };
                                        @endphp
                                        <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
                                            {{ $statusConfig['label'] }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-4 text-xs text-neutral-400 pt-3 border-t border-neutral-100">
                                        <div class="flex items-center gap-1.5">
                                            <x-lucide-calendar class="w-4 h-4" />
                                            <span>{{ $project->created_at->format('M j, Y') }}</span>
                                        </div>
                                        @if($project->assignedAdiutor)
                                            <div class="flex items-center gap-1.5">
                                                <x-lucide-user class="w-4 h-4" />
                                                <span>{{ $project->assignedAdiutor->user->fullName }}</span>
                                            </div>
                                        @endif
                                        @if($project->progress_percentage)
                                            <div class="flex items-center gap-2 ml-auto">
                                                <div class="w-24 bg-neutral-100 rounded-full h-1.5">
                                                    <div class="bg-primary-500 h-1.5 rounded-full transition-all" style="width: {{ $project->progress_percentage }}%"></div>
                                                </div>
                                                <span class="text-xs font-medium text-neutral-600">{{ $project->progress_percentage }}%</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-14 h-14 bg-neutral-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <x-lucide-folder-kanban class="w-7 h-7 text-neutral-400" />
                        </div>
                        <p class="text-sm text-neutral-500 mb-4">No projects yet</p>
                        <a href="{{ route('client.requests.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                            <x-lucide-plus class="w-4 h-4" />
                            Create Your First Request
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <h2 class="text-lg font-medium text-neutral-700 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('client.requests.create') }}" 
                       class="flex items-center p-4 rounded-xl bg-neutral-50 hover:bg-primary-50 border border-neutral-100 hover:border-primary-200 transition-all group">
                        <div class="w-10 h-10 bg-white border border-neutral-200 rounded-lg flex items-center justify-center group-hover:border-primary-300 transition-colors">
                            <x-lucide-plus class="w-5 h-5 text-primary-600" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-neutral-700">New Service Request</p>
                            <p class="text-xs text-neutral-400">Start a new project</p>
                        </div>
                    </a>

                    <a href="{{ route('client.projects.index') }}" 
                       class="flex items-center p-4 rounded-xl bg-neutral-50 hover:bg-primary-50 border border-neutral-100 hover:border-primary-200 transition-all group">
                        <div class="w-10 h-10 bg-white border border-neutral-200 rounded-lg flex items-center justify-center group-hover:border-primary-300 transition-colors">
                            <x-lucide-folder-kanban class="w-5 h-5 text-neutral-500" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-neutral-700">View Projects</p>
                            <p class="text-xs text-neutral-400">Track progress</p>
                        </div>
                    </a>

                    <a href="{{ route('client.documents') }}" 
                       class="flex items-center p-4 rounded-xl bg-neutral-50 hover:bg-primary-50 border border-neutral-100 hover:border-primary-200 transition-all group">
                        <div class="w-10 h-10 bg-white border border-neutral-200 rounded-lg flex items-center justify-center group-hover:border-primary-300 transition-colors">
                            <x-lucide-files class="w-5 h-5 text-neutral-500" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-neutral-700">My Documents</p>
                            <p class="text-xs text-neutral-400">View & download files</p>
                        </div>
                    </a>

                    <a href="{{ route('client.feedback') }}" 
                       class="flex items-center p-4 rounded-xl bg-neutral-50 hover:bg-primary-50 border border-neutral-100 hover:border-primary-200 transition-all group">
                        <div class="w-10 h-10 bg-white border border-neutral-200 rounded-lg flex items-center justify-center group-hover:border-primary-300 transition-colors">
                            <x-lucide-star class="w-5 h-5 text-warning-500" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-neutral-700">Leave Feedback</p>
                            <p class="text-xs text-neutral-400">Rate your experience</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <h2 class="text-lg font-medium text-neutral-700 mb-4">Recent Messages</h2>
                @if($recentMessages->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentMessages as $message)
                            <div class="border-l-2 border-primary-500 pl-4 py-3 bg-primary-50 rounded-r-lg">
                                <p class="text-sm text-neutral-700 mb-1.5 line-clamp-2">{{ Str::limit($message->message, 60) }}</p>
                                <p class="text-xs text-neutral-400">
                                    <span class="font-medium text-neutral-500">{{ $message->sender->fullName }}</span> · {{ $message->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-neutral-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <x-lucide-message-square class="w-6 h-6 text-neutral-400" />
                        </div>
                        <p class="text-sm text-neutral-500">No recent messages</p>
                    </div>
                @endif
            </div>

            <!-- Upcoming Deadlines -->
            @if($upcomingDeadlines->count() > 0)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <h2 class="text-lg font-medium text-neutral-700 mb-4">Upcoming Deadlines</h2>
                    <div class="space-y-3">
                        @foreach($upcomingDeadlines as $project)
                            <div class="flex items-start gap-3 p-4 bg-warning-50 rounded-xl border border-warning-100">
                                <div class="flex-shrink-0 p-2 bg-warning-100 rounded-lg">
                                    <x-lucide-alert-circle class="w-4 h-4 text-warning-500" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-700 mb-1">{{ $project->title }}</p>
                                    <p class="text-xs text-neutral-500">Due {{ $project->deadline->format('M j, Y') }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
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

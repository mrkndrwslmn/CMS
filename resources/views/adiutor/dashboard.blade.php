@extends('adiutor.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'icon' => 'home'],
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Welcome back, {{ auth()->user()->fullName }}!</h1>
        <p class="text-neutral-500 mt-2">Here's what's happening with your projects today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Projects -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Active Projects</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['active_projects'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-folder-kanban class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Completed Projects -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Completed</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['completed_projects'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Pending Assignments -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['pending_assignments'] }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- This Month Earnings -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">This Month</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($thisMonthEarnings, 2) }}</p>
                    <p class="text-sm text-neutral-400 mt-1">Total: ₱{{ number_format($stats['total_earnings'], 2) }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-banknote class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Action Items Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Urgent Tasks -->
        <x-ui.card class="p-6 border-l-4 border-error-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                    <x-lucide-alert-triangle class="w-5 h-5 text-error-500 mr-2" />
                    Urgent Tasks
                </h3>
                <x-ui.badge variant="error">{{ $urgentTasks->count() }}</x-ui.badge>
            </div>
            @if($urgentTasks->count() > 0)
                <div class="space-y-3">
                    @foreach($urgentTasks->take(3) as $task)
                        <div class="p-3 bg-error-50 rounded-xl border border-error-100">
                            <h4 class="font-medium text-error-900 text-sm">{{ $task->taskTitle }}</h4>
                            <p class="text-xs text-error-700 mt-1">Due: {{ \Carbon\Carbon::parse($task->deadline)->format('M j, Y') }}</p>
                            <p class="text-xs text-error-600">{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</p>
                        </div>
                    @endforeach
                    @if($urgentTasks->count() > 3)
                        <p class="text-xs text-error-600 text-center">+{{ $urgentTasks->count() - 3 }} more urgent tasks</p>
                    @endif
                </div>
            @else
                <p class="text-sm text-neutral-500">No urgent tasks</p>
            @endif
        </x-ui.card>

        <!-- Budget Requests -->
        <x-ui.card class="p-6 border-l-4 border-warning-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                    <x-lucide-banknote class="w-5 h-5 text-warning-500 mr-2" />
                    Budget Requests
                </h3>
                <x-ui.badge variant="warning">{{ $pendingBudgetRequests->count() }}</x-ui.badge>
            </div>
            @if($pendingBudgetRequests->count() > 0)
                <div class="space-y-3">
                    @foreach($pendingBudgetRequests->take(2) as $request)
                        <div class="p-3 bg-warning-50 rounded-xl border border-warning-100">
                            <h4 class="font-medium text-warning-900 text-sm">{{ $request->project_title }}</h4>
                            <p class="text-xs text-warning-700 mt-1">₱{{ number_format($request->requested_amount, 2) }} - {{ ucfirst($request->type) }}</p>
                            <p class="text-xs text-warning-600">{{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}</p>
                        </div>
                    @endforeach
                    @if($pendingBudgetRequests->count() > 2)
                        <p class="text-xs text-warning-600 text-center">+{{ $pendingBudgetRequests->count() - 2 }} more requests</p>
                    @endif
                </div>
            @else
                <p class="text-sm text-neutral-500">No pending requests</p>
            @endif
        </x-ui.card>

        <!-- Performance Summary -->
        <x-ui.card class="p-6 border-l-4 border-success-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-neutral-800 flex items-center">
                    <x-lucide-bar-chart-2 class="w-5 h-5 text-success-500 mr-2" />
                    Performance
                </h3>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-neutral-600">Completion Rate</span>
                    <span class="text-sm font-semibold text-success-700">{{ number_format($completionRate, 1) }}%</span>
                </div>
                <div class="w-full bg-neutral-200 rounded-full h-2">
                    <div class="bg-success-500 h-2 rounded-full" style="width: {{ $completionRate }}%"></div>
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
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Projects -->
        <x-ui.card class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-neutral-800">Recent Projects</h2>
                <a href="{{ route('adiutor.projects.index') }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                    View all
                    <x-lucide-arrow-right class="w-3 h-3" />
                </a>
            </div>
            
            @if($recentProjects->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProjects as $project)
                        <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-xl hover:bg-neutral-100 transition-colors">
                            <div class="flex-1">
                                <h3 class="font-medium text-neutral-800">{{ $project->title }}</h3>
                                <p class="text-sm text-neutral-500">Client: {{ $project->client_name }}</p>
                                @if($project->project_deadline)
                                    <p class="text-xs text-neutral-500 mt-1">
                                        Deadline: {{ \Carbon\Carbon::parse($project->project_deadline)->format('M j, Y') }}
                                        @if(\Carbon\Carbon::parse($project->project_deadline)->isPast())
                                            <span class="text-error-600 font-medium">(Overdue)</span>
                                        @elseif(\Carbon\Carbon::parse($project->project_deadline)->diffInDays() <= 3)
                                            <span class="text-warning-600 font-medium">(Due Soon)</span>
                                        @endif
                                    </p>
                                @endif
                                <div class="flex items-center mt-2">
                                    <div class="w-20 bg-neutral-200 rounded-full h-2 mr-3">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-xs text-neutral-600">{{ $project->progress_percentage }}%</span>
                                    @if($project->agreed_rate)
                                        <span class="text-xs text-neutral-500 ml-3">₱{{ number_format($project->agreed_rate, 2) }}/hr</span>
                                    @endif
                                </div>
                            </div>
                            <div class="shrink-0 ml-4">
                                @if($project->assignment_status === 'active')
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @elseif($project->assignment_status === 'pending')
                                    <x-ui.badge variant="warning">Pending</x-ui.badge>
                                @else
                                    <x-ui.badge variant="neutral">{{ ucfirst($project->assignment_status) }}</x-ui.badge>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <x-lucide-folder-kanban class="mx-auto h-12 w-12 text-neutral-400" />
                    <p class="text-neutral-500 mt-2">No projects assigned yet</p>
                </div>
            @endif
        </x-ui.card>

        <!-- Recent Notifications -->
        <x-ui.card class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-neutral-800">Recent Notifications</h2>
                <button class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
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
                        <div class="flex items-start space-x-3 p-3 bg-neutral-50 rounded-xl">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                                    <x-lucide-bell class="w-4 h-4 text-primary-500" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-800">{{ $title }}</p>
                                <p class="text-sm text-neutral-500">{{ $message }}</p>
                                <p class="text-xs text-neutral-400 mt-1">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <x-lucide-bell-off class="mx-auto h-12 w-12 text-neutral-400" />
                    <p class="text-neutral-500 mt-2">No new notifications</p>
                </div>
            @endif
        </x-ui.card>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <x-ui.card class="p-6">
            <h2 class="text-lg font-semibold text-neutral-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="{{ route('adiutor.profile.edit') }}" 
                   class="flex items-center justify-center p-4 bg-neutral-50 rounded-xl hover:bg-neutral-100 transition-colors group">
                    <x-lucide-user class="w-5 h-5 text-neutral-500 mr-2 group-hover:text-primary-600" />
                    <span class="text-neutral-700 font-medium group-hover:text-primary-600">Update Profile</span>
                </a>
                
                <a href="{{ route('adiutor.projects.index') }}" 
                   class="flex items-center justify-center p-4 bg-neutral-50 rounded-xl hover:bg-neutral-100 transition-colors group">
                    <x-lucide-folder-kanban class="w-5 h-5 text-neutral-500 mr-2 group-hover:text-primary-600" />
                    <span class="text-neutral-700 font-medium group-hover:text-primary-600">View Projects</span>
                </a>
                
                <a href="{{ route('adiutor.tasks.index') }}" 
                   class="flex items-center justify-center p-4 bg-neutral-50 rounded-xl hover:bg-neutral-100 transition-colors group">
                    <x-lucide-list-checks class="w-5 h-5 text-neutral-500 mr-2 group-hover:text-primary-600" />
                    <span class="text-neutral-700 font-medium group-hover:text-primary-600">View Tasks</span>
                </a>
                
                <a href="{{ route('adiutor.clients') }}" 
                   class="flex items-center justify-center p-4 bg-neutral-50 rounded-xl hover:bg-neutral-100 transition-colors group">
                    <x-lucide-users class="w-5 h-5 text-neutral-500 mr-2 group-hover:text-primary-600" />
                    <span class="text-neutral-700 font-medium group-hover:text-primary-600">Manage Clients</span>
                </a>
                
                <a href="{{ route('adiutor.documents') }}" 
                   class="flex items-center justify-center p-4 bg-neutral-50 rounded-xl hover:bg-neutral-100 transition-colors group">
                    <x-lucide-file-text class="w-5 h-5 text-neutral-500 mr-2 group-hover:text-primary-600" />
                    <span class="text-neutral-700 font-medium group-hover:text-primary-600">Documents</span>
                </a>
            </div>
        </x-ui.card>
    </div>
</div>
@endsection
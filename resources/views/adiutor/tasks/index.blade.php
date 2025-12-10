@extends('adiutor.layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Tasks', 'icon' => 'clipboard-list'],
    ]" />

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">My Tasks</h1>
                <p class="text-neutral-500 mt-2">Manage and track your assigned tasks</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-neutral-500">Total Tasks</p>
                <p class="text-2xl font-semibold text-primary-600">{{ $tasks->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pending Tasks -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $tasks->where('status', 'pending')->count() }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- In Progress Tasks -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">In Progress</p>
                    <p class="text-2xl font-semibold text-primary-600 mt-1">{{ $tasks->where('status', 'in_progress')->count() }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-zap class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Completed Tasks -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Completed</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $tasks->where('status', 'completed')->count() }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Urgent Tasks -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Urgent</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $tasks->where('priority', 'urgent')->count() }}</p>
                </div>
                <div class="p-3 bg-error-50 rounded-xl">
                    <x-lucide-alert-triangle class="w-5 h-5 text-error-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Filters -->
    <x-ui.card class="p-6 mb-8">
        <form method="GET" action="{{ route('adiutor.tasks.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Project Filter -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Project</label>
                <select name="project" class="w-full rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-sm text-neutral-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
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
                <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-sm text-neutral-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <option value="all">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Priority</label>
                <select name="priority" class="w-full rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-sm text-neutral-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <option value="all">All Priorities</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <x-ui.button type="submit" variant="primary" class="w-full">
                    <x-lucide-filter class="w-4 h-4 mr-2" />
                    Apply Filters
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    @if($tasks->count() > 0)
        <!-- Tasks Grid -->
        <div class="grid gap-6">
            @foreach($tasks as $task)
                <x-ui.card class="overflow-hidden hover:shadow-md hover:border-primary-200 transition-all duration-200 cursor-pointer" 
                     onclick="window.location.href='{{ route('adiutor.tasks.show', $task->taskID) }}'">
                    <div class="p-6">
                        <!-- Task Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-semibold text-neutral-800 hover:text-primary-600 transition-colors">{{ $task->taskTitle }}</h3>
                                    @php
                                        $statusConfig = match($task->status) {
                                            'completed' => ['variant' => 'success', 'icon' => 'check-circle'],
                                            'in_progress' => ['variant' => 'primary', 'icon' => 'loader'],
                                            'pending' => ['variant' => 'warning', 'icon' => 'clock'],
                                            'pending_approval' => ['variant' => 'info', 'icon' => 'clock'],
                                            'cancelled' => ['variant' => 'neutral', 'icon' => 'x-circle'],
                                            default => ['variant' => 'neutral', 'icon' => 'circle-dashed']
                                        };
                                    @endphp
                                    <x-ui.badge variant="{{ $statusConfig['variant'] }}">
                                        <x-dynamic-component :component="'lucide-' . $statusConfig['icon']" class="w-3 h-3 mr-1" />
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </x-ui.badge>
                                    @if($task->priority)
                                        @php
                                            $priorityConfig = match($task->priority) {
                                                'urgent' => ['variant' => 'error', 'icon' => 'alert-triangle'],
                                                'high' => ['variant' => 'warning', 'icon' => 'alert-triangle'],
                                                'medium' => ['variant' => 'primary', 'icon' => 'flag'],
                                                'low' => ['variant' => 'neutral', 'icon' => 'flag'],
                                                default => ['variant' => 'neutral', 'icon' => 'flag']
                                            };
                                        @endphp
                                        <x-ui.badge variant="{{ $priorityConfig['variant'] }}">
                                            <x-dynamic-component :component="'lucide-' . $priorityConfig['icon']" class="w-3 h-3 mr-1" />
                                            {{ ucfirst($task->priority) }}
                                        </x-ui.badge>
                                    @endif
                                </div>
                                <p class="text-sm text-neutral-500 flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <x-lucide-folder-kanban class="w-4 h-4 text-primary-500 mr-1" />
                                        {{ $task->project_title }}
                                    </span>
                                    <span class="flex items-center">
                                        <x-lucide-user class="w-4 h-4 text-primary-500 mr-1" />
                                        {{ $task->client_name }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Task Description -->
                        @if($task->taskDescription)
                            <p class="text-neutral-600 mb-4 line-clamp-3">{{ Str::limit($task->taskDescription, 200) }}</p>
                        @endif

                        <!-- Task Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-neutral-50 rounded-xl">
                            <div>
                                <p class="text-xs font-medium text-neutral-500 mb-1 flex items-center">
                                    <x-lucide-calendar class="w-4 h-4 text-primary-500 mr-1" />
                                    Deadline
                                </p>
                                <p class="text-lg font-semibold text-neutral-800">
                                    @if($task->deadline)
                                        {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}
                                    @else
                                        <span class="text-neutral-400 text-sm">Not set</span>
                                    @endif
                                </p>
                                @if($task->deadline)
                                    <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-medium text-neutral-500 mb-1 flex items-center">
                                    <x-lucide-wallet class="w-4 h-4 text-success-500 mr-1" />
                                    Task Allocation
                                </p>
                                <p class="text-lg font-semibold text-success-700">
                                    @if($task->allocated_budget)
                                        ₱{{ number_format($task->allocated_budget, 2) }}
                                    @else
                                        <span class="text-neutral-400 text-sm">Not set</span>
                                    @endif
                                </p>
                                <p class="text-xs text-success-600">Potential earnings</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-neutral-500 mb-1 flex items-center">
                                    <x-lucide-clock class="w-4 h-4 text-primary-500 mr-1" />
                                    Assigned
                                </p>
                                <p class="text-lg font-semibold text-neutral-800">
                                    {{ \Carbon\Carbon::parse($task->dateAssigned)->format('M d, Y') }}
                                </p>
                                <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($task->dateAssigned)->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Progress Section -->
                        @if($task->progress_percentage !== null)
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-neutral-700 flex items-center">
                                        <x-lucide-bar-chart-3 class="w-4 h-4 text-primary-500 mr-2" />
                                        Progress
                                    </span>
                                    <p class="text-sm font-semibold text-primary-600">{{ $task->progress_percentage }}%</p>
                                </div>
                                <div class="w-full bg-neutral-200 rounded-full h-2">
                                    <div class="bg-primary-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $task->progress_percentage }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-neutral-200">
                            <div class="flex items-center space-x-3">
                                <x-ui.button onclick="event.stopPropagation(); window.location.href='{{ route('adiutor.tasks.show', $task->taskID) }}'" 
                                        variant="primary" size="sm">
                                    <x-lucide-eye class="w-4 h-4 mr-1" />
                                    View Details
                                </x-ui.button>
                                @if($task->status !== 'completed')
                                    <x-ui.button type="button" onclick="event.stopPropagation(); completeTask({{ $task->taskID }})" 
                                            variant="success" size="sm">
                                        <x-lucide-check class="w-4 h-4 mr-1" />
                                        Mark Complete
                                    </x-ui.button>
                                @endif
                            </div>
                            
                            <button onclick="event.stopPropagation(); window.location.href='{{ route('adiutor.projects.show', $task->project_id) }}'" 
                                    class="flex items-center space-x-2 text-sm text-neutral-600 hover:text-primary-600 transition-colors px-3 py-2 rounded-xl hover:bg-neutral-50">
                                <x-lucide-folder-kanban class="w-4 h-4" />
                                <span>View Project</span>
                            </button>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <x-ui.card class="p-12">
            <div class="text-center">
                <div class="mx-auto w-24 h-24 bg-neutral-100 rounded-full flex items-center justify-center mb-4">
                    <x-lucide-clipboard-list class="w-12 h-12 text-neutral-400" />
                </div>
                <h3 class="text-xl font-semibold text-neutral-800 mb-2">No tasks found</h3>
                <p class="text-neutral-500 mb-6">
                    @if(request('project') || request('status') || request('priority'))
                        No tasks match your current filters. Try adjusting your search criteria.
                    @else
                        You don't have any tasks assigned yet.
                    @endif
                </p>
                @if(request('project') || request('status') || request('priority'))
                    <x-ui.button href="{{ route('adiutor.tasks.index') }}" variant="primary">
                        <x-lucide-rotate-ccw class="w-4 h-4 mr-2" />
                        Clear Filters
                    </x-ui.button>
                @else
                    <x-ui.button href="{{ route('adiutor.projects.index') }}" variant="primary">
                        <x-lucide-folder-kanban class="w-4 h-4 mr-2" />
                        View Projects
                    </x-ui.button>
                @endif
            </div>
        </x-ui.card>
    @endif
</div>
@endsection

@push('scripts')
<script>
async function completeTask(taskId, confirmNoDeliverables = false) {
    // First confirmation - "Are you sure you want to mark this task as completed?"
    if (!confirmNoDeliverables) {
        const confirmed = await window.Alerts.confirm({
            title: 'Complete Task',
            message: 'Mark this task as completed?',
            confirmText: 'Yes, Complete',
            cancelText: 'Cancel',
            confirmVariant: 'success'
        });
        
        if (!confirmed) return;
    }
    
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        if (confirmNoDeliverables) {
            formData.append('confirm_no_deliverables', '1');
        }
        
        const response = await fetch(`/adiutor/tasks/${taskId}/complete`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        // Check if confirmation is required for no deliverables
        if (data.requires_confirmation) {
            const confirmNoDelivs = await window.Alerts.confirm({
                title: 'Complete Without Deliverables?',
                message: data.message,
                confirmText: 'Yes, Complete Anyway',
                cancelText: 'Cancel',
                confirmVariant: 'warning'
            });
            
            if (confirmNoDelivs) {
                // Re-submit with confirmation
                await completeTask(taskId, true);
            }
            return;
        }
        
        if (data.success) {
            window.Alerts?.success('Success', 'Task marked as completed!');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Failed to complete task');
        }
    } catch (error) {
        console.error('Error completing task:', error);
        window.Alerts?.error('Error', error.message || 'Failed to complete task. Please try again.');
    }
}
</script>
@endpush
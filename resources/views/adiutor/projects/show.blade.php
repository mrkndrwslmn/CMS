@extends('adiutor.layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Projects', 'route' => 'adiutor.projects.index', 'icon' => 'folder-kanban'],
        ['label' => $project->title, 'icon' => 'file-text'],
    ]" />

    <!-- Project Header -->
    <x-ui.card class="p-8 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-2xl font-semibold text-neutral-800">{{ $project->title }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($project->assignment_status === 'active') bg-success-100 text-success-700
                        @elseif($project->assignment_status === 'pending') bg-warning-100 text-warning-700
                        @elseif($project->assignment_status === 'completed') bg-primary-100 text-primary-700
                        @else bg-neutral-100 text-neutral-600 @endif">
                        <span class="w-2 h-2 rounded-full mr-2
                            @if($project->assignment_status === 'active') bg-success-500
                            @elseif($project->assignment_status === 'pending') bg-warning-500
                            @elseif($project->assignment_status === 'completed') bg-primary-500
                            @else bg-neutral-500 @endif">
                        </span>
                        {{ ucfirst($project->assignment_status) }}
                    </span>
                </div>
                <p class="text-neutral-500">{{ $project->service_type }}</p>
            </div>
            
            @if($project->assignment_status === 'active')
                <button onclick="updateProgress()" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <x-lucide-pencil class="w-5 h-5" />
                    Update Progress
                </button>
            @endif
        </div>

        <!-- Project Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-4 bg-neutral-50 rounded-xl">
                <p class="text-sm font-medium text-neutral-500 mb-1 flex items-center">
                    <x-lucide-banknote class="w-4 h-4 text-primary-500 mr-2" />
                    Budget
                </p>
                <p class="text-2xl font-semibold text-neutral-800">
                    ${{ number_format($project->agreed_rate ?? $project->budget, 2) }}
                </p>
                <p class="text-xs text-neutral-500 mt-1">{{ ucfirst($project->budget_type) }}</p>
            </div>

            <div class="p-4 bg-neutral-50 rounded-xl">
                <p class="text-sm font-medium text-neutral-500 mb-1 flex items-center">
                    <x-lucide-calendar class="w-4 h-4 text-primary-500 mr-2" />
                    Start Date
                </p>
                <p class="text-2xl font-semibold text-neutral-800">
                    @if($project->start_date)
                        {{ \Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}
                    @else
                        <span class="text-neutral-400 text-lg">Not started</span>
                    @endif
                </p>
                @if($project->start_date)
                    <p class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($project->start_date)->diffForHumans() }}</p>
                @endif
            </div>

            <div class="p-4 bg-neutral-50 rounded-xl">
                <p class="text-sm font-medium text-neutral-500 mb-1 flex items-center">
                    <x-lucide-calendar-clock class="w-4 h-4 text-primary-500 mr-2" />
                    Deadline
                </p>
                <p class="text-2xl font-semibold text-neutral-800">
                    @if($project->deadline)
                        {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                    @else
                        <span class="text-neutral-400 text-lg">Not set</span>
                    @endif
                </p>
                @if($project->deadline)
                    <p class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}</p>
                @endif
            </div>

            <div class="p-4 bg-neutral-50 rounded-xl">
                <p class="text-sm font-medium text-neutral-500 mb-1 flex items-center">
                    <x-lucide-bar-chart-3 class="w-4 h-4 text-primary-500 mr-2" />
                    Progress
                </p>
                <p class="text-2xl font-semibold text-primary-600">{{ $project->progress_percentage }}%</p>
                <div class="w-full bg-neutral-200 rounded-full h-2 mt-2">
                    <div class="bg-primary-500 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $project->progress_percentage }}%"></div>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Description -->
            <x-ui.card class="p-6">
                <h2 class="text-lg font-medium text-neutral-800 mb-4 flex items-center">
                    <x-lucide-info class="w-5 h-5 text-primary-500 mr-2" />
                    Project Description
                </h2>
                <p class="text-neutral-600 whitespace-pre-line">{{ $project->description ?? $project->request_description ?? 'No description provided.' }}</p>
            </x-ui.card>

            <!-- Milestones (if milestone payment) -->
            @if($project->payment_type === 'milestone' && count($milestones) > 0)
                <x-ui.card class="p-6">
                    <h2 class="text-lg font-medium text-neutral-800 mb-4 flex items-center">
                        <x-lucide-milestone class="w-5 h-5 text-primary-500 mr-2" />
                        Project Milestones
                    </h2>
                    <div class="space-y-4">
                        @foreach($milestones as $milestone)
                            <div class="flex items-start space-x-4 p-4 bg-neutral-50 rounded-xl">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                        <span class="text-primary-600 font-semibold">{{ $milestone->phase_order }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium text-neutral-800">{{ $milestone->title }}</h3>
                                    <p class="text-sm text-neutral-500 mt-1">{{ $milestone->description }}</p>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span class="text-sm text-neutral-500 flex items-center">
                                            <x-lucide-banknote class="w-4 h-4 mr-1" />
                                            ${{ number_format($milestone->amount, 2) }}
                                        </span>
                                        <span class="text-sm text-neutral-500 flex items-center">
                                            <x-lucide-calendar class="w-4 h-4 mr-1" />
                                            {{ \Carbon\Carbon::parse($milestone->due_date)->format('M d, Y') }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($milestone->status === 'completed') bg-success-100 text-success-700
                                            @elseif($milestone->status === 'in_progress') bg-primary-100 text-primary-700
                                            @else bg-neutral-100 text-neutral-600 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            <!-- Tasks -->
            <x-ui.card class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-neutral-800 flex items-center">
                        <x-lucide-clipboard-list class="w-5 h-5 text-primary-500 mr-2" />
                        My Tasks ({{ count($tasks) }})
                    </h2>
                    <div class="flex items-center space-x-3">
                        @if($project->assignment_status === 'active')
                            <button onclick="document.getElementById('createTaskModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <x-lucide-plus class="w-4 h-4" />
                                Create Task
                            </button>
                        @endif
                        <a href="{{ route('adiutor.tasks.index') }}?project={{ $project->id }}" class="text-sm text-primary-600 hover:text-primary-700 flex items-center">
                            View All Tasks
                            <x-lucide-chevron-right class="w-4 h-4 ml-1" />
                        </a>
                    </div>
                </div>

                @if(count($tasks) > 0)
                    <div class="space-y-3">
                        @foreach($tasks->take(5) as $task)
                            @if(isset($task->id))
                                <a href="{{ route('adiutor.tasks.show', $task->id) }}" class="block p-4 bg-neutral-50 rounded-xl hover:bg-neutral-100 transition-colors">
                            @else
                                <div class="block p-4 bg-neutral-50 rounded-xl">
                            @endif
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-neutral-800">{{ $task->title ?? 'Untitled Task' }}</h4>
                                        <p class="text-sm text-neutral-500 mt-1">{{ Str::limit($task->description ?? '', 100) }}</p>
                                        <div class="flex items-center space-x-3 mt-2">
                                            @if(isset($task->deadline))
                                                <span class="text-xs text-neutral-500 flex items-center">
                                                    <x-lucide-calendar class="w-4 h-4 mr-1" />
                                                    Due: {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}
                                                </span>
                                            @endif
                                            @if($task->priority ?? null)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    @if($task->priority === 'urgent') bg-error-100 text-error-700
                                                    @elseif($task->priority === 'high') bg-warning-100 text-warning-700
                                                    @else bg-neutral-100 text-neutral-600 @endif">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        @if(($task->status ?? 'pending') === 'completed') bg-success-100 text-success-700
                                        @elseif(($task->status ?? 'pending') === 'in_progress') bg-primary-100 text-primary-700
                                        @else bg-neutral-100 text-neutral-600 @endif">
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
                        <x-lucide-clipboard-list class="w-12 h-12 text-neutral-300 mx-auto mb-3" />
                        <p class="text-neutral-500">No tasks assigned yet</p>
                    </div>
                @endif
            </x-ui.card>

            <!-- Recent Activity -->
            @if(count($activities) > 0)
                <x-ui.card class="p-6">
                    <h2 class="text-lg font-medium text-neutral-800 mb-4 flex items-center">
                        <x-lucide-activity class="w-5 h-5 text-primary-500 mr-2" />
                        Recent Activity
                    </h2>
                    <div class="space-y-3">
                        @foreach($activities->take(5) as $activity)
                            <div class="flex items-start space-x-3 p-3 bg-neutral-50 rounded-xl">
                                <x-lucide-message-square class="w-4 h-4 text-primary-500 mt-1" />
                                <div>
                                    <p class="text-sm text-neutral-700">{{ $activity->content }}</p>
                                    <p class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif
        </div>

        <!-- Right Column (1/3) -->
        <div class="space-y-6">
            <!-- Client Information -->
            <x-ui.card class="p-6">
                <h2 class="text-lg font-medium text-neutral-800 mb-4 flex items-center">
                    <x-lucide-user class="w-5 h-5 text-primary-500 mr-2" />
                    Client
                </h2>
                <div class="flex items-center space-x-4 mb-4">
                    <img src="{{ $project->client_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->client_name) }}" 
                         alt="{{ $project->client_name }}" 
                         class="w-16 h-16 rounded-full">
                    <div>
                        <h3 class="font-medium text-neutral-800">{{ $project->client_name }}</h3>
                        <p class="text-sm text-neutral-500">{{ $project->client_email }}</p>
                    </div>
                </div>
                @if($project->client_phone)
                    <div class="flex items-center space-x-2 text-sm text-neutral-500 mb-2">
                        <x-lucide-phone class="w-4 h-4 text-primary-500" />
                        <span>{{ $project->client_phone }}</span>
                    </div>
                @endif
                <button onclick="contactClient()" class="w-full mt-4 inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <x-lucide-mail class="w-4 h-4" />
                    Contact Client
                </button>
            </x-ui.card>

            <!-- Team Members -->
            @if(count($teamMembers) > 1)
                <x-ui.card class="p-6">
                    <h2 class="text-lg font-medium text-neutral-800 mb-4 flex items-center">
                        <x-lucide-users class="w-5 h-5 text-primary-500 mr-2" />
                        Team Members
                    </h2>
                    <div class="space-y-3">
                        @foreach($teamMembers as $member)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $member->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->fullName) }}" 
                                     alt="{{ $member->fullName }}" 
                                     class="w-10 h-10 rounded-full">
                                <div class="flex-1">
                                    <p class="font-medium text-neutral-800">{{ $member->fullName }}</p>
                                    <p class="text-xs text-neutral-500">{{ $member->role ?? 'Team Member' }}</p>
                                </div>
                                @if(isset($member->id) && $member->id == $user->id)
                                    <span class="text-xs bg-primary-100 text-primary-700 px-2 py-1 rounded-full">You</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            <!-- Assignment Notes -->
            @if($project->assignment_notes)
                <x-ui.card class="p-6">
                    <h2 class="text-lg font-medium text-neutral-800 mb-4 flex items-center">
                        <x-lucide-sticky-note class="w-5 h-5 text-primary-500 mr-2" />
                        Assignment Notes
                    </h2>
                    <p class="text-neutral-600 whitespace-pre-line">{{ $project->assignment_notes }}</p>
                </x-ui.card>
            @endif

            <!-- Quick Actions -->
            <x-ui.card class="p-6">
                <h2 class="text-lg font-medium text-neutral-800 mb-4 flex items-center">
                    <x-lucide-zap class="w-5 h-5 text-primary-500 mr-2" />
                    Quick Actions
                </h2>
                <div class="space-y-2">
                    <a href="{{ route('adiutor.documents') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-neutral-50 hover:bg-neutral-100 rounded-xl transition-colors font-medium text-neutral-700">
                        <x-lucide-file-text class="w-4 h-4" />
                        View Documents
                    </a>
                    <a href="{{ route('adiutor.tasks.index') }}?project={{ $project->id }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-neutral-50 hover:bg-neutral-100 rounded-xl transition-colors font-medium text-neutral-700">
                        <x-lucide-clipboard-list class="w-4 h-4" />
                        View All Tasks
                    </a>
                    @if($project->assignment_status === 'active')
                        <button onclick="requestBudgetChange()" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-neutral-50 hover:bg-neutral-100 rounded-xl transition-colors font-medium text-neutral-700">
                            <x-lucide-banknote class="w-4 h-4" />
                            Request Budget Change
                        </button>
                    @endif
                </div>
            </x-ui.card>
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div id="createTaskModal" class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-neutral-100 sticky top-0 bg-white rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-neutral-800">Create New Task</h3>
                    <button onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                        <x-lucide-x class="w-6 h-6" />
                    </button>
                </div>
                <p class="text-sm text-warning-600 mt-2 flex items-center">
                    <x-lucide-info class="w-4 h-4 mr-1" />
                    This task will be sent to admin for approval before it becomes active.
                </p>
            </div>
            
            <form action="{{ route('adiutor.projects.create-task', $project->id) }}" method="POST" class="p-6">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Task Title *</label>
                    <input type="text" name="task_title" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="Enter task title...">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Task Description *</label>
                    <textarea name="task_description" rows="4" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="Describe the task in detail..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Priority *</label>
                        <select name="priority" required class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Deadline</label>
                        <input type="date" name="deadline" class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Requested Budget (Optional)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-neutral-500">₱</span>
                        <input type="number" name="allocated_budget" step="0.01" min="0" class="w-full pl-8 pr-4 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="0.00">
                    </div>
                    <p class="text-xs text-neutral-500 mt-1">Leave blank if no specific budget is needed</p>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-2.5 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" placeholder="Add any additional notes or requirements..."></textarea>
                </div>
                
                <div class="p-4 bg-warning-50 border-l-4 border-warning-500 rounded-xl mb-6">
                    <p class="text-sm text-warning-700">
                        <strong>Note:</strong> This task will be created with "Pending Approval" status. An admin will review and approve it before it becomes an active task.
                    </p>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="flex-1 px-4 py-2.5 bg-neutral-100 text-neutral-700 font-medium rounded-lg hover:bg-neutral-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <x-lucide-send class="w-4 h-4" />
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

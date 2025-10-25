@extends('adiutor.layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('adiutor.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="{{ route('adiutor.projects.index') }}" class="hover:text-accent-500 transition-colors">Projects</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-900">{{ $project->title }}</span>
    </nav>

    <!-- Project Header -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($project->assignment_status === 'active') bg-green-100 text-green-800
                        @elseif($project->assignment_status === 'pending') bg-amber-100 text-amber-800
                        @elseif($project->assignment_status === 'completed') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        <i class="fas fa-circle text-[6px] mr-2"></i>
                        {{ ucfirst($project->assignment_status) }}
                    </span>
                </div>
                <p class="text-gray-600">{{ $project->service_type }}</p>
            </div>
            
            @if($project->assignment_status === 'active')
                <button onclick="updateProgress()" class="px-6 py-3 bg-accent-600 text-white font-medium rounded-lg hover:bg-accent-700 transition-colors shadow-lg shadow-accent-500/30">
                    <i class="fas fa-edit mr-2"></i>
                    Update Progress
                </button>
            @endif
        </div>

        <!-- Project Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600 mb-1 flex items-center">
                    <i class="fas fa-dollar-sign text-accent-500 mr-2"></i>
                    Budget
                </p>
                <p class="text-2xl font-bold text-gray-900">
                    ${{ number_format($project->agreed_rate ?? $project->budget, 2) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">{{ ucfirst($project->budget_type) }}</p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600 mb-1 flex items-center">
                    <i class="fas fa-calendar-start text-accent-500 mr-2"></i>
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
                    <i class="fas fa-calendar-check text-accent-500 mr-2"></i>
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
                    <i class="fas fa-chart-line text-accent-500 mr-2"></i>
                    Progress
                </p>
                <p class="text-2xl font-bold text-accent-600">{{ $project->progress_percentage }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-gradient-to-r from-accent-500 to-accent-600 h-2 rounded-full transition-all duration-300" 
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
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-accent-500 mr-2"></i>
                    Project Description
                </h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $project->description ?? $project->request_description ?? 'No description provided.' }}</p>
            </div>

            <!-- Milestones (if milestone payment) -->
            @if($project->payment_type === 'milestone' && count($milestones) > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-flag-checkered text-accent-500 mr-2"></i>
                        Project Milestones
                    </h2>
                    <div class="space-y-4">
                        @foreach($milestones as $milestone)
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-accent-100 rounded-full flex items-center justify-center">
                                        <span class="text-accent-600 font-bold">{{ $milestone->milestone_number }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $milestone->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $milestone->description }}</p>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span class="text-sm text-gray-500">
                                            <i class="fas fa-dollar-sign mr-1"></i>
                                            ${{ number_format($milestone->amount, 2) }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($milestone->due_date)->format('M d, Y') }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($milestone->status === 'completed') bg-green-100 text-green-800
                                            @elseif($milestone->status === 'in_progress') bg-blue-100 text-blue-800
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
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-tasks text-accent-500 mr-2"></i>
                        My Tasks ({{ count($tasks) }})
                    </h2>
                    <div class="flex items-center space-x-3">
                        @if($project->assignment_status === 'active')
                            <button onclick="document.getElementById('createTaskModal').classList.remove('hidden')" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Create Task
                            </button>
                        @endif
                        <a href="{{ route('adiutor.tasks.index') }}?project={{ $project->id }}" class="text-sm text-accent-600 hover:text-accent-700">
                            View All Tasks <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                @if(count($tasks) > 0)
                    <div class="space-y-3">
                        @foreach($tasks->take(5) as $task)
                            <a href="{{ route('adiutor.tasks.show', $task->id) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $task->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description, 100) }}</p>
                                        <div class="flex items-center space-x-3 mt-2">
                                            <span class="text-xs text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                                Due: {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}
                                            </span>
                                            @if($task->priority)
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
                                        @if($task->status === 'completed') bg-green-100 text-green-800
                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No tasks assigned yet</p>
                    </div>
                @endif
            </div>

            <!-- Recent Activity -->
            @if(count($activities) > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-history text-accent-500 mr-2"></i>
                        Recent Activity
                    </h2>
                    <div class="space-y-3">
                        @foreach($activities->take(5) as $activity)
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-sticky-note text-accent-500 mt-1"></i>
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
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user text-accent-500 mr-2"></i>
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
                        <i class="fas fa-phone text-accent-500"></i>
                        <span>{{ $project->client_phone }}</span>
                    </div>
                @endif
                <button onclick="contactClient()" class="w-full mt-4 px-4 py-2 bg-accent-600 text-white font-medium rounded-lg hover:bg-accent-700 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    Contact Client
                </button>
            </div>

            <!-- Team Members -->
            @if(count($teamMembers) > 1)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-users text-accent-500 mr-2"></i>
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
                                @if($member->id === $user->id)
                                    <span class="text-xs bg-accent-100 text-accent-800 px-2 py-1 rounded">You</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Assignment Notes -->
            @if($project->assignment_notes)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note text-accent-500 mr-2"></i>
                        Assignment Notes
                    </h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $project->assignment_notes }}</p>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt text-accent-500 mr-2"></i>
                    Quick Actions
                </h2>
                <div class="space-y-2">
                    <a href="{{ route('adiutor.documents') }}" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-center font-medium text-gray-700">
                        <i class="fas fa-file-alt mr-2"></i>
                        View Documents
                    </a>
                    <a href="{{ route('adiutor.tasks.index') }}?project={{ $project->id }}" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-center font-medium text-gray-700">
                        <i class="fas fa-tasks mr-2"></i>
                        View All Tasks
                    </a>
                    @if($project->assignment_status === 'active')
                        <button onclick="requestBudgetChange()" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors text-center font-medium text-gray-700">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            Request Budget Change
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div id="createTaskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-neutral-200 sticky top-0 bg-white">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-neutral-900">Create New Task</h3>
                <button onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="text-neutral-400 hover:text-neutral-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-sm text-amber-600 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                This task will be sent to admin for approval before it becomes active.
            </p>
        </div>
        
        <form action="{{ route('adiutor.projects.create-task', $project->id) }}" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Task Title *</label>
                <input type="text" name="task_title" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Enter task title...">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Task Description *</label>
                <textarea name="task_description" rows="4" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Describe the task in detail..."></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Priority *</label>
                    <select name="priority" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Deadline</label>
                    <input type="date" name="deadline" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Requested Budget (Optional)</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-neutral-500">₱</span>
                    <input type="number" name="allocated_budget" step="0.01" min="0" class="w-full pl-8 pr-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0.00">
                </div>
                <p class="text-xs text-neutral-500 mt-1">Leave blank if no specific budget is needed</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Notes (Optional)</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Add any additional notes or requirements..."></textarea>
            </div>
            
            <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded mb-6">
                <p class="text-sm text-amber-800">
                    <strong>Note:</strong> This task will be created with "Pending Approval" status. An admin will review and approve it before it becomes an active task.
                </p>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-neutral-200 text-neutral-700 font-medium rounded-lg hover:bg-neutral-300 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit for Approval
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
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
</script>
@endpush

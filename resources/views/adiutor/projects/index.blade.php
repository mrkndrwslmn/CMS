@extends('adiutor.layouts.app')

@section('title', 'Projects')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Projects', 'icon' => 'folder-kanban'],
    ]" />

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">My Projects</h1>
                <p class="text-neutral-500 mt-1">Manage and track your assigned projects</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-neutral-500">Active Projects</p>
                <p class="text-2xl font-semibold text-primary-600">{{ $projects->where('assignment_status', 'active')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Projects</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $projects->count() }}</p>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl">
                    <x-lucide-folder-kanban class="w-5 h-5 text-neutral-400" />
                </div>
            </div>
        </x-ui.card>

        <!-- Active Projects -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Active</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $projects->where('assignment_status', 'active')->count() }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Pending Projects -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $projects->where('assignment_status', 'pending')->count() }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Completed Projects -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Completed</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $projects->where('assignment_status', 'completed')->count() }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-check class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Filter Tabs -->
    <x-ui.card class="mb-6">
        <div class="border-b border-neutral-100 px-6">
            <nav class="-mb-px flex space-x-8">
                <button class="filter-tab active border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="all">
                    All Projects
                </button>
                <button class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="active">
                    Active
                </button>
                <button class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="pending">
                    Pending
                </button>
                <button class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="completed">
                    Completed
                </button>
            </nav>
        </div>
    </x-ui.card>

    @if($projects->count() > 0)
        <!-- Projects Grid -->
        <div class="grid gap-6">
            @foreach($projects as $project)
                <x-ui.card class="overflow-hidden project-item hover:shadow-md hover:border-primary-200 transition-all duration-200 cursor-pointer" 
                     data-status="{{ $project->assignment_status }}"
                     onclick="window.location.href='{{ route('adiutor.projects.show', $project->id) }}'">
                    <div class="p-6">
                        <!-- Project Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-neutral-800 mb-1">{{ $project->title }}</h3>
                                <p class="text-sm text-neutral-500 flex items-center">
                                    <x-lucide-user class="w-4 h-4 text-primary-500 mr-2" />
                                    Client: {{ $project->client_name }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $project->assignment_status === 'active' ? 'bg-success-100 text-success-700' : '' }}
                                    {{ $project->assignment_status === 'pending' ? 'bg-warning-100 text-warning-700' : '' }}
                                    {{ $project->assignment_status === 'completed' ? 'bg-primary-100 text-primary-700' : '' }}
                                    {{ !in_array($project->assignment_status, ['active', 'pending', 'completed']) ? 'bg-neutral-100 text-neutral-600' : '' }}">
                                    <span class="w-2 h-2 rounded-full mr-2
                                        {{ $project->assignment_status === 'active' ? 'bg-success-500' : '' }}
                                        {{ $project->assignment_status === 'pending' ? 'bg-warning-500' : '' }}
                                        {{ $project->assignment_status === 'completed' ? 'bg-primary-500' : '' }}
                                        {{ !in_array($project->assignment_status, ['active', 'pending', 'completed']) ? 'bg-neutral-500' : '' }}">
                                    </span>
                                    {{ ucfirst($project->assignment_status) }}
                                </span>
                                @if($project->priority)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $project->priority === 'urgent' ? 'bg-error-100 text-error-700' : '' }}
                                        {{ $project->priority === 'high' ? 'bg-warning-100 text-warning-700' : '' }}
                                        {{ !in_array($project->priority, ['urgent', 'high']) ? 'bg-neutral-100 text-neutral-600' : '' }}">
                                        <x-lucide-alert-triangle class="w-3 h-3 mr-1" />
                                        {{ ucfirst($project->priority) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Project Description -->
                        @if($project->description)
                            <p class="text-neutral-600 mb-4 line-clamp-2">{{ Str::limit($project->description, 150) }}</p>
                        @endif

                        <!-- Project Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-neutral-50 rounded-xl">
                            <div>
                                <p class="text-xs font-medium text-neutral-500 mb-1 flex items-center">
                                    <x-lucide-banknote class="w-4 h-4 text-primary-500 mr-1" />
                                    Budget
                                </p>
                                <p class="text-lg font-semibold text-neutral-800">
                                    @if($project->agreed_rate)
                                        ₱{{ number_format($project->agreed_rate, 2) }}
                                    @else
                                        ₱{{ number_format($project->budget, 2) }}
                                    @endif
                                </p>
                                <p class="text-xs text-neutral-500">{{ ucfirst($project->budget_type) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-neutral-500 mb-1 flex items-center">
                                    <x-lucide-calendar class="w-4 h-4 text-primary-500 mr-1" />
                                    Deadline
                                </p>
                                <p class="text-lg font-semibold text-neutral-800">
                                    @if($project->deadline)
                                        {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                                    @else
                                        <span class="text-neutral-400">Not set</span>
                                    @endif
                                </p>
                                @if($project->deadline)
                                    <p class="text-xs text-neutral-500">
                                        {{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-medium text-neutral-500 mb-1 flex items-center">
                                    <x-lucide-play-circle class="w-4 h-4 text-primary-500 mr-1" />
                                    Status
                                </p>
                                <p class="text-lg font-semibold text-neutral-800">
                                    @if($project->start_date)
                                        Started
                                    @else
                                        <span class="text-neutral-400">Not started</span>
                                    @endif
                                </p>
                                @if($project->start_date)
                                    <p class="text-xs text-neutral-500">
                                        {{ \Carbon\Carbon::parse($project->start_date)->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @if($project->assignment_status === 'active')
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-neutral-700 flex items-center">
                                        <x-lucide-bar-chart-3 class="w-4 h-4 text-primary-500 mr-2" />
                                        Progress
                                    </p>
                                    <p class="text-sm font-semibold text-primary-600">{{ $project->progress_percentage }}%</p>
                                </div>
                                <div class="w-full bg-neutral-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-primary-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $project->progress_percentage }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Project Notes -->
                        @if($project->notes)
                            <div class="mb-4 p-4 bg-primary-50 border-l-4 border-primary-500 rounded-xl">
                                <p class="text-sm font-medium text-primary-800 mb-1 flex items-center">
                                    <x-lucide-message-square class="w-4 h-4 mr-2" />
                                    Notes
                                </p>
                                <p class="text-sm text-primary-700">{{ $project->notes }}</p>
                            </div>
                        @endif

                        <!-- View Details Button -->
                        <div class="flex items-center justify-between pt-4 border-t border-neutral-100">
                            <div class="flex items-center text-sm text-neutral-500">
                                <x-lucide-clock class="w-4 h-4 mr-2" />
                                <span>Created {{ \Carbon\Carbon::parse($project->created_at)->diffForHumans() }}</span>
                            </div>
                            
                            <div class="flex items-center text-primary-600 font-medium">
                                <span class="text-sm">View Details</span>
                                <x-lucide-chevron-right class="w-4 h-4 ml-2" />
                            </div>
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
                    <x-lucide-folder-kanban class="w-12 h-12 text-neutral-400" />
                </div>
                <h3 class="text-lg font-medium text-neutral-800 mb-2">No projects assigned</h3>
                <p class="text-neutral-500 mb-6">You'll see your assigned projects here once they're available.</p>
                <a href="{{ route('adiutor.profile.edit') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <x-lucide-user class="w-5 h-5" />
                    Complete Your Profile
                </a>
            </div>
        </x-ui.card>
    @endif
</div>

<script>
// Filter functionality
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        const filter = this.dataset.filter;
        
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(t => {
            t.classList.remove('active', 'border-primary-500', 'text-primary-600');
            t.classList.add('border-transparent', 'text-neutral-500');
        });
        
        this.classList.add('active', 'border-primary-500', 'text-primary-600');
        this.classList.remove('border-transparent', 'text-neutral-500');
        
        // Filter projects
        document.querySelectorAll('.project-item').forEach(project => {
            const status = project.dataset.status;
            
            if (filter === 'all' || status === filter) {
                project.style.display = 'block';
            } else {
                project.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
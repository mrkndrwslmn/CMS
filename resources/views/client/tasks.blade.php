@extends('client.layout')

@section('title', 'My Projects')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-neutral-900">My Projects</h1>
            <p class="text-neutral-600 mt-2">Track your active and completed projects</p>
        </div>
        <a href="{{ route('client.requests.create') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Request
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8">
        <div class="border-b border-neutral-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button data-filter="all" class="filter-tab active border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    All Projects ({{ collect($projects)->count() }})
                </button>
                <button data-filter="pending" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Pending ({{ collect($projects)->where('status', 'pending')->count() }})
                </button>
                <button data-filter="in_progress" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    In Progress ({{ collect($projects)->where('status', 'in_progress')->count() }})
                </button>
                <button data-filter="completed" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Completed ({{ collect($projects)->where('status', 'completed')->count() }})
                </button>
            </nav>
        </div>
    </div>

    @if(collect($projects)->count() > 0)
        <!-- Projects Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($projects as $project)
                <div class="project-card card hover:shadow-lg transition-shadow" data-status="{{ $project->status }}">
                    <!-- Project Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-neutral-900 mb-2">{{ $project->title ?? $project->project_name ?? 'Untitled Project' }}</h3>
                            <p class="text-neutral-600 text-sm">{{ Str::limit($project->description ?? 'No description available.', 120) }}</p>
                        </div>
                        <span class="badge badge-{{ $project->status === 'completed' ? 'success' : ($project->status === 'in_progress' ? 'warning' : 'neutral') }} ml-4">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>

                    <!-- Project Details -->
                    <div class="space-y-3 mb-6">
                        <!-- Budget -->
                        @if($project->budget)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <span class="text-neutral-600">Budget: <span class="font-medium">${{ number_format($project->budget, 2) }}</span></span>
                            </div>
                        @endif

                        <!-- Deadline -->
                        @if($project->deadline)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-neutral-600">
                                    Deadline: 
                                    <span class="font-medium {{ $project->deadline->isPast() ? 'text-error-600' : '' }}">
                                        {{ $project->deadline->format('M j, Y') }}
                                    </span>
                                </span>
                            </div>
                        @endif

                        <!-- Assigned Adiutor -->
                        @if($project->assignedAdiutor)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-neutral-600">
                                    Adiutor: 
                                    <span class="font-medium text-primary-600">{{ $project->assignedAdiutor->user->fullName }}</span>
                                </span>
                            </div>
                        @endif

                        <!-- Assignment Status -->
                        @if($project->assignment_status)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <span class="text-neutral-600">
                                    Assignment: 
                                    <span class="font-medium text-primary-600">{{ ucfirst(str_replace('_', ' ', $project->assignment_status)) }}</span>
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    @if($project->status === 'in_progress')
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-neutral-700">Progress</span>
                                <span class="text-sm text-neutral-500">{{ $project->progress ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-neutral-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $project->progress ?? 0 }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Project Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-neutral-200">
                        <div class="flex items-center space-x-4 text-sm text-neutral-500">
                            <span>{{ $project->created_at->format('M j, Y') }}</span>
                            @if($project->start_date)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Started {{ $project->start_date->format('M j') }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="#" 
                               class="btn-secondary btn-sm">
                                View Details
                            </a>
                            @if($project->status === 'completed')
                                <a href="{{ route('client.feedback') }}" 
                                   class="btn-primary btn-sm">
                                    Leave Feedback
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($projects instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-neutral-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">No projects yet</h3>
            <p class="text-neutral-600 mb-6">Get started by creating your first service request</p>
            <a href="{{ route('client.requests.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Service Request
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const projectCards = document.querySelectorAll('.project-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active tab
            filterTabs.forEach(t => {
                t.classList.remove('active', 'border-primary-500', 'text-primary-600');
                t.classList.add('border-transparent', 'text-neutral-500');
            });
            this.classList.add('active', 'border-primary-500', 'text-primary-600');
            this.classList.remove('border-transparent', 'text-neutral-500');
            
            // Filter projects
            projectCards.forEach(card => {
                if (filter === 'all' || card.dataset.status === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection
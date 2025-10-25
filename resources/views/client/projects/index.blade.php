@extends('client.layout')

@section('title', 'My Projects')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
        <div>
            <h1 class="heading-serif text-4xl text-neutral-900">My Projects</h1>
            <p class="text-neutral-600 mt-2 text-lg">Track your active and completed projects</p>
        </div>
        <a href="{{ route('client.requests.create') }}" class="btn-primary inline-flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Request
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8">
        <div class="glass-card p-2">
            <nav class="flex flex-wrap gap-2" aria-label="Tabs">
                <button data-filter="all" class="filter-tab active flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 bg-gradient-to-br from-primary-500 to-accent-600 text-white shadow-md hover:shadow-lg">
                    All Projects <span class="ml-1 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ collect($projects)->count() }}</span>
                </button>
                <button data-filter="pending" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-warning-50 hover:text-warning-700">
                    Pending <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ collect($projects)->where('status', 'pending')->count() }}</span>
                </button>
                <button data-filter="in_progress" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-accent-50 hover:text-accent-700">
                    In Progress <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ collect($projects)->where('status', 'in_progress')->count() }}</span>
                </button>
                <button data-filter="completed" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-success-50 hover:text-success-700">
                    Completed <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ collect($projects)->where('status', 'completed')->count() }}</span>
                </button>
            </nav>
        </div>
    </div>

    @if(collect($projects)->count() > 0)
        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <div class="glass-card p-6 hover:scale-[1.02] hover:-translate-y-2 hover:shadow-xl transition-all duration-300 ease-out" data-status="{{ $project->status }}">
                    <!-- Project Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1 pr-4">
                            <h3 class="text-xl font-semibold text-neutral-900 mb-2">{{ $project->title ?? $project->project_name ?? 'Untitled Project' }}</h3>
                            <p class="text-neutral-600 text-sm leading-relaxed">{{ Str::limit($project->description ?? 'No description available.', 120) }}</p>
                        </div>
                        @php
                            $statusStyles = match($project->status) {
                                'completed' => 'bg-success-100 text-success-800 border border-success-300',
                                'in_progress' => 'bg-accent-100 text-accent-800 border border-accent-300',
                                'active' => 'bg-primary-100 text-primary-800 border border-primary-300',
                                'review' => 'bg-warning-100 text-warning-800 border border-warning-300',
                                default => 'bg-neutral-100 text-neutral-800 border border-neutral-300'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $statusStyles }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>

                    <!-- Project Details -->
                    <div class="grid grid-cols-1 gap-3 mb-6">
                        <!-- Budget -->
                        @if($project->budget)
                            <div class="flex items-center space-x-3 p-3 bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-primary-600 font-medium">Project Budget</p>
                                    <p class="text-sm font-bold text-primary-900">₱{{ number_format($project->budget, 2) }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Deadline -->
                        @if($project->deadline)
                            <div class="flex items-center space-x-3 p-3 bg-gradient-to-br from-accent-50 to-accent-100 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-accent-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-accent-600 font-medium">Project Deadline</p>
                                    <p class="text-sm font-bold {{ $project->deadline->isPast() ? 'text-error-600' : 'text-accent-900' }}">
                                        {{ $project->deadline->format('M j, Y') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Assigned Adiutor -->
                        @if($project->assignedAdiutor)
                            <div class="flex items-center space-x-3 p-3 bg-gradient-to-br from-secondary-50 to-secondary-100 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-secondary-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-secondary-600 font-medium">Assigned Adiutor</p>
                                    <p class="text-sm font-bold text-secondary-900">{{ $project->assignedAdiutor->user->fullName }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Assignment Status -->
                        @if($project->assignment_status)
                            <div class="flex items-center space-x-3 p-3 bg-gradient-to-br from-tertiary-50 to-tertiary-100 rounded-lg">
                                <div class="flex-shrink-0 w-10 h-10 bg-tertiary-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-tertiary-600 font-medium">Assignment Status</p>
                                    <p class="text-sm font-bold text-tertiary-900">{{ ucfirst(str_replace('_', ' ', $project->assignment_status)) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    @if($project->status === 'in_progress')
                        <div class="mb-6 p-4 bg-gradient-to-br from-accent-50 to-primary-50 rounded-lg border border-accent-200">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-semibold text-neutral-900">Project Progress</span>
                                <span class="text-lg font-bold text-accent-600">{{ $project->progress ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-white rounded-full h-3 shadow-inner">
                                <div class="bg-gradient-to-r from-primary-500 to-accent-600 h-3 rounded-full shadow-sm transition-all duration-500" style="width: {{ $project->progress ?? 0 }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Project Actions -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-neutral-200 gap-4">
                        <div class="flex flex-col space-y-1 text-sm text-neutral-500">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Created {{ $project->created_at->format('M j, Y') }}</span>
                            </div>
                            @if($project->start_date)
                                <div class="flex items-center space-x-2 text-xs text-neutral-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span>Started {{ $project->start_date->format('M j, Y') }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <a href="{{ route('client.projects.show', $project->id) }}" 
                               class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border-2 border-primary-500 text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-all duration-300 shadow-sm hover:shadow-md text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Details
                            </a>
                            @if($project->status === 'completed')
                                <a href="{{ route('client.feedback') }}" 
                                   class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-success-500 to-success-600 text-white font-semibold rounded-lg hover:from-success-600 hover:to-success-700 transition-all duration-300 shadow-md hover:shadow-lg text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    Feedback
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
        <div class="glass-card p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-accent-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-neutral-900 mb-3">No projects yet</h3>
                <p class="text-neutral-600 mb-8 text-lg leading-relaxed">Get started by creating your first service request and we'll transform it into an amazing project</p>
                <a href="{{ route('client.requests.create') }}" class="btn-primary inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Service Request
                </a>
            </div>
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
                t.classList.remove('active', 'bg-gradient-to-br', 'from-primary-500', 'to-accent-600', 'text-white', 'shadow-md');
                t.classList.add('text-neutral-600');
            });
            this.classList.add('active', 'bg-gradient-to-br', 'from-primary-500', 'to-accent-600', 'text-white', 'shadow-md');
            this.classList.remove('text-neutral-600');
            
            // Update badge styling on active tab
            const badge = this.querySelector('span');
            if (badge) {
                badge.classList.remove('bg-neutral-100');
                badge.classList.add('bg-white/20');
            }
            
            // Reset badges on inactive tabs
            filterTabs.forEach(t => {
                if (!t.classList.contains('active')) {
                    const badge = t.querySelector('span');
                    if (badge) {
                        badge.classList.remove('bg-white/20');
                        badge.classList.add('bg-neutral-100');
                    }
                }
            });
            
            // Filter projects with animation
            projectCards.forEach(card => {
                if (filter === 'all' || card.dataset.status === filter) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Initialize card styles
    projectCards.forEach(card => {
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });
});
</script>
@endsection
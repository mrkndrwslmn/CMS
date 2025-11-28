@extends('client.layouts.app')

@section('title', 'My Projects')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-primary-700">My Projects</h1>
            <p class="text-neutral-600 mt-2">Track your active and completed projects</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8">
        <div class="bg-white rounded-xl border border-neutral-200 p-1.5">
            <nav class="flex flex-wrap gap-1" aria-label="Tabs">
                <button data-filter="all" class="filter-tab active flex-1 min-w-fit px-5 py-3 rounded-lg font-semibold text-sm transition-all duration-200 bg-primary-600 text-white">
                    All Projects <span class="ml-1.5 px-2 py-0.5 bg-white/20 rounded-full text-xs font-semibold">{{ collect($projects)->count() }}</span>
                </button>
                <button data-filter="pending" class="filter-tab flex-1 min-w-fit px-5 py-3 rounded-lg font-semibold text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    Pending <span class="ml-1.5 px-2 py-0.5 bg-neutral-100 rounded-full text-xs font-semibold">{{ collect($projects)->where('status', 'pending')->count() }}</span>
                </button>
                <button data-filter="in_progress" class="filter-tab flex-1 min-w-fit px-5 py-3 rounded-lg font-semibold text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    In Progress <span class="ml-1.5 px-2 py-0.5 bg-neutral-100 rounded-full text-xs font-semibold">{{ collect($projects)->where('status', 'in_progress')->count() }}</span>
                </button>
                <button data-filter="completed" class="filter-tab flex-1 min-w-fit px-5 py-3 rounded-lg font-semibold text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    Completed <span class="ml-1.5 px-2 py-0.5 bg-neutral-100 rounded-full text-xs font-semibold">{{ collect($projects)->where('status', 'completed')->count() }}</span>
                </button>
            </nav>
        </div>
    </div>

    @if(collect($projects)->count() > 0)
        <!-- Projects Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($projects as $project)
            <div class="bg-white rounded-xl border border-neutral-200 p-6 hover:border-primary-300 hover:shadow-lg transition-all duration-300" data-status="{{ $project->status }}">
                    <!-- Project Header -->
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex-1 pr-4 min-w-0">
                            <h3 class="text-lg font-bold text-primary-700 mb-2 line-clamp-2">{{ $project->title ?? $project->project_name ?? 'Untitled Project' }}</h3>
                            <p class="text-neutral-600 text-sm leading-relaxed line-clamp-3">{{ Str::limit($project->description ?? 'No description available.', 120) }}</p>
                        </div>
                        @php
                            $statusStyles = match($project->status) {
                                'completed' => 'bg-success-50 text-success-700 border-success-200',
                                'in_progress' => 'bg-primary-50 text-primary-700 border-primary-200',
                                'active' => 'bg-primary-50 text-primary-700 border-primary-200',
                                'review' => 'bg-warning-50 text-warning-700 border-warning-200',
                                default => 'bg-neutral-50 text-neutral-700 border-neutral-200'
                            };
                        @endphp
                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap border {{ $statusStyles }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>

                    <!-- Project Details -->
                    <div class="space-y-3 mb-6">
                        <!-- Budget -->
                        @if($project->budget)
                            <div class="flex items-center gap-3 p-3 bg-primary-50 rounded-lg border border-primary-100">
                                <div class="flex-shrink-0 w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-primary-600 font-semibold mb-0.5">Project Budget</p>
                                    @if($project->hasDiscounts)
                                        <p class="text-xs text-neutral-400 line-through">₱{{ number_format($project->originalBudget, 2) }}</p>
                                        <p class="text-sm font-bold text-primary-700">₱{{ number_format($project->budget, 2) }}</p>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @if($project->coupon_discount_amount > 0)
                                                <span class="text-xs text-success-600 font-medium">
                                                    <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                                    </svg>
                                                    -₱{{ number_format($project->coupon_discount_amount, 2) }}
                                                </span>
                                            @endif
                                            @if($project->loyalty_discount_amount > 0)
                                                <span class="text-xs text-success-600 font-medium">
                                                    <i class="fas fa-medal"></i> -₱{{ number_format($project->loyalty_discount_amount, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-sm font-bold text-primary-700">₱{{ number_format($project->budget, 2) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Deadline -->
                        @if($project->deadline)
                            <div class="flex items-center gap-3 p-3 bg-accent-50 rounded-lg border border-accent-100">
                                <div class="flex-shrink-0 w-10 h-10 bg-accent-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-accent-600 font-semibold mb-0.5">Project Deadline</p>
                                    <p class="text-sm font-bold {{ $project->deadline->isPast() ? 'text-error-600' : 'text-primary-700' }}">
                                        {{ $project->deadline->format('M j, Y') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    @if($project->status === 'in_progress')
                        <div class="mb-6 p-4 bg-primary-50 rounded-lg border border-primary-200">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-semibold text-primary-700">Project Progress</span>
                                <span class="text-lg font-bold text-primary-700">{{ $project->progress ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-white rounded-full h-2.5 border border-primary-100">
                                <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $project->progress ?? 0 }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Project Actions -->
                    <div class="flex flex-col gap-4 pt-5 border-t border-neutral-200">
                        <div class="flex items-center gap-3 text-sm text-neutral-500">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Created {{ $project->created_at->format('M j, Y') }}</span>
                            @if($project->start_date)
                                <span class="mx-2 text-neutral-300">•</span>
                                <span class="text-xs">Started {{ $project->start_date->format('M j, Y') }}</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <a href="{{ route('client.projects.show', $project->id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-white border-2 border-primary-600 text-primary-700 font-semibold rounded-lg hover:bg-primary-50 transition-all duration-200 text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                            @if($project->status === 'completed')
                                <a href="{{ route('client.feedback') }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-all duration-200 text-sm">
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
        <div class="bg-white rounded-xl border border-neutral-200 p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-primary-700 mb-3">No projects yet</h3>
                <p class="text-neutral-600 mb-8 leading-relaxed">Get started by creating your first service request and we'll transform it into an amazing project</p>
                <a href="{{ route('client.requests.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-all duration-200">
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
                t.classList.remove('active', 'bg-primary-600', 'text-white');
                t.classList.add('text-neutral-600');
                
                const badge = t.querySelector('span');
                if (badge) {
                    badge.classList.remove('bg-white/20');
                    badge.classList.add('bg-neutral-100');
                }
            });
            
            this.classList.add('active', 'bg-primary-600', 'text-white');
            this.classList.remove('text-neutral-600');
            
            const activeBadge = this.querySelector('span');
            if (activeBadge) {
                activeBadge.classList.remove('bg-neutral-100');
                activeBadge.classList.add('bg-white/20');
            }
            
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

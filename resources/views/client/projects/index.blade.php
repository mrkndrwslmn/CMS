@extends('client.layouts.app')

@section('title', 'My Projects')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'My Projects', 'icon' => 'folder-kanban'],
    ]" />

    <!-- Header -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="p-4 bg-neutral-50 rounded-xl">
                <x-lucide-folder-kanban class="w-8 h-8 text-neutral-400" />
            </div>
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">My Projects</h1>
                <p class="text-neutral-500 mt-1">Track your active and completed projects</p>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-1.5">
            <nav class="flex flex-wrap gap-1" aria-label="Tabs">
                <button data-filter="all" class="filter-tab active flex-1 min-w-fit px-5 py-2.5 rounded-xl font-medium text-sm transition-colors bg-primary-600 text-white">
                    All Projects <span class="ml-1.5 px-2 py-0.5 bg-white/20 rounded-full text-xs font-medium">{{ collect($projects)->count() }}</span>
                </button>
                <button data-filter="pending" class="filter-tab flex-1 min-w-fit px-5 py-2.5 rounded-xl font-medium text-sm transition-colors text-neutral-600 hover:bg-neutral-50">
                    Pending <span class="ml-1.5 px-2 py-0.5 bg-neutral-100 rounded-full text-xs font-medium">{{ collect($projects)->where('status', 'pending')->count() }}</span>
                </button>
                <button data-filter="in_progress" class="filter-tab flex-1 min-w-fit px-5 py-2.5 rounded-xl font-medium text-sm transition-colors text-neutral-600 hover:bg-neutral-50">
                    In Progress <span class="ml-1.5 px-2 py-0.5 bg-neutral-100 rounded-full text-xs font-medium">{{ collect($projects)->where('status', 'in_progress')->count() }}</span>
                </button>
                <button data-filter="completed" class="filter-tab flex-1 min-w-fit px-5 py-2.5 rounded-xl font-medium text-sm transition-colors text-neutral-600 hover:bg-neutral-50">
                    Completed <span class="ml-1.5 px-2 py-0.5 bg-neutral-100 rounded-full text-xs font-medium">{{ collect($projects)->where('status', 'completed')->count() }}</span>
                </button>
            </nav>
        </div>
    </div>

    @if(collect($projects)->count() > 0)
        <!-- Projects Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($projects as $project)
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow flex flex-col h-full" data-status="{{ $project->status }}">
                    <!-- Project Header -->
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex-1 pr-4 min-w-0">
                            <h3 class="text-base font-medium text-neutral-800 mb-2 line-clamp-2 min-h-[2.5rem]">{{ $project->title ?? $project->project_name ?? 'Untitled Project' }}</h3>
                            <p class="text-neutral-500 text-sm leading-relaxed line-clamp-2 min-h-[2.5rem]">{{ Str::limit($project->description ?? 'No description available.', 80) }}</p>
                        </div>
                        @php
                            $statusStyles = match($project->status) {
                                'completed' => 'bg-success-50 text-success-700',
                                'in_progress' => 'bg-primary-50 text-primary-700',
                                'active' => 'bg-primary-50 text-primary-700',
                                'review' => 'bg-warning-50 text-warning-700',
                                default => 'bg-neutral-50 text-neutral-700'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $statusStyles }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>

                    <!-- Project Details - Fixed Height Section -->
                    <div class="flex-1 space-y-3 mb-6">
                        <!-- Budget -->
                        <div class="flex items-center gap-3 p-3 bg-neutral-50 rounded-xl border border-neutral-100">
                            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-xl flex items-center justify-center border border-neutral-100">
                                <x-lucide-wallet class="w-5 h-5 text-neutral-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-neutral-500 font-medium mb-0.5">Project Budget</p>
                                @if($project->budget)
                                    @if($project->hasDiscounts)
                                        <p class="text-xs text-neutral-400 line-through">₱{{ number_format($project->originalBudget, 2) }}</p>
                                        <p class="text-sm font-semibold text-neutral-800">₱{{ number_format($project->budget, 2) }}</p>
                                    @else
                                        <p class="text-sm font-semibold text-neutral-800">₱{{ number_format($project->budget, 2) }}</p>
                                    @endif
                                @else
                                    <p class="text-sm text-neutral-400 italic">Not set</p>
                                @endif
                            </div>
                        </div>

                        <!-- Deadline -->
                        <div class="flex items-center gap-3 p-3 bg-neutral-50 rounded-xl border border-neutral-100">
                            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-xl flex items-center justify-center border border-neutral-100">
                                <x-lucide-calendar class="w-5 h-5 text-neutral-400" />
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-neutral-500 font-medium mb-0.5">Project Deadline</p>
                                @if($project->deadline)
                                    <p class="text-sm font-semibold {{ $project->deadline->isPast() ? 'text-error-600' : 'text-neutral-800' }}">
                                        {{ $project->deadline->format('M j, Y') }}
                                    </p>
                                @else
                                    <p class="text-sm text-neutral-400 italic">Not set</p>
                                @endif
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-100">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs text-neutral-500 font-medium">Progress</span>
                                <span class="text-sm font-semibold text-neutral-800">{{ $project->progress ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-neutral-200 rounded-full h-1.5">
                                <div class="bg-primary-600 h-1.5 rounded-full transition-all" style="width: {{ $project->progress ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Actions - Always at Bottom -->
                    <div class="flex flex-col gap-3 pt-4 border-t border-neutral-100 mt-auto">
                        <div class="flex items-center gap-2 text-xs text-neutral-400">
                            <x-lucide-clock class="w-3.5 h-3.5 flex-shrink-0" />
                            <span>Created {{ $project->created_at->format('M j, Y') }}</span>
                            @if($project->start_date)
                                <span class="text-neutral-300">•</span>
                                <span>Started {{ $project->start_date->format('M j, Y') }}</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <a href="{{ route('client.projects.show', $project->id) }}" 
                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-neutral-100 text-neutral-700 font-medium rounded-xl hover:bg-neutral-50 transition-colors text-sm">
                                <x-lucide-eye class="w-4 h-4" />
                                View Details
                            </a>
                            @if($project->status === 'completed')
                                <a href="{{ route('client.feedback') }}" 
                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors text-sm">
                                    <x-lucide-star class="w-4 h-4" />
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
                <x-ui.pagination :paginator="$projects" />
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-16 h-16 bg-neutral-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <x-lucide-folder-kanban class="w-8 h-8 text-neutral-400" />
                </div>
                <h3 class="text-lg font-semibold text-neutral-800 mb-2">No projects yet</h3>
                <p class="text-neutral-500 mb-6">Get started by creating your first service request and we'll transform it into an amazing project</p>
                <a href="{{ route('client.requests.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
                    <x-lucide-plus class="w-5 h-5" />
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

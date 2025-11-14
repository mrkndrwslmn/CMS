@extends('client.layouts.app')

@section('title', 'Service Requests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
        <div>
            <h1 class="heading-serif text-4xl text-neutral-900">Service Requests</h1>
            <p class="text-neutral-600 mt-2 text-lg">Manage your service requests and track their progress</p>
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
                    All <span class="ml-1 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $requests->count() }}</span>
                </button>
                <button data-filter="pending" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-warning-50 hover:text-warning-700">
                    Pending <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'pending')->count() }}</span>
                </button>
                <button data-filter="approved" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-success-50 hover:text-success-700">
                    Approved <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'approved')->count() }}</span>
                </button>
                <button data-filter="pending_payment" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-secondary-50 hover:text-secondary-700">
                    Pending Payment <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'pending_payment')->count() }}</span>
                </button>
                <button data-filter="paid" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-primary-50 hover:text-primary-700">
                    Paid <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'paid')->count() }}</span>
                </button>
                <button data-filter="in_progress" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-accent-50 hover:text-accent-700">
                    In Progress <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'in_progress')->count() }}</span>
                </button>
                <button data-filter="completed" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-success-50 hover:text-success-700">
                    Completed <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'completed')->count() }}</span>
                </button>
                <button data-filter="rejected" class="filter-tab flex-1 min-w-fit px-4 py-3 rounded-lg font-medium text-sm transition-all duration-300 text-neutral-600 hover:bg-error-50 hover:text-error-700">
                    Rejected <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'rejected')->count() }}</span>
                </button>
            </nav>
        </div>
    </div>

    @if($requests->count() > 0)
        <!-- Requests Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($requests as $request)
                <div class="request-card glass-card overflow-hidden hover:scale-[1.02] hover:-translate-y-2 hover:shadow-xl transition-all duration-300 ease-out" data-status="{{ $request->status }}">
                    <!-- Card Header with Status -->
                    <div class="relative p-6 pb-4">
                        <!-- Status Badge - Top Right -->
                        <div class="absolute top-4 right-4">
                            @php
                                $statusConfig = match($request->status) {
                                    'pending' => ['bg' => 'bg-warning-500', 'text' => 'text-white', 'label' => 'Pending Review'],
                                    'approved' => ['bg' => 'bg-success-500', 'text' => 'text-white', 'label' => 'Approved'],
                                    'rejected' => ['bg' => 'bg-error-500', 'text' => 'text-white', 'label' => 'Rejected'],
                                    'pending_payment' => ['bg' => 'bg-secondary-500', 'text' => 'text-white', 'label' => 'Awaiting Payment'],
                                    'paid' => ['bg' => 'bg-primary-500', 'text' => 'text-white', 'label' => 'Paid'],
                                    'in_progress' => ['bg' => 'bg-accent-500', 'text' => 'text-white', 'label' => 'In Progress'],
                                    'completed' => ['bg' => 'bg-success-600', 'text' => 'text-white', 'label' => 'Completed'],
                                    default => ['bg' => 'bg-neutral-500', 'text' => 'text-white', 'label' => 'Unknown']
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} shadow-md">
                                {{ $statusConfig['label'] }}
                            </span>
                        </div>

                        <!-- Request ID -->
                        <div class="inline-flex items-center space-x-2 mb-3">
                            <span class="text-xs font-mono text-neutral-500 bg-neutral-100 px-2 py-1 rounded">REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</span>
                            <!-- Service Type Badge -->
                            <span class="text-xs font-medium text-primary-700 bg-primary-50 px-2 py-1 rounded">
                                {{ ucfirst(str_replace('_', ' ', $request->service_type)) }}
                            </span>
                        </div>

                        <!-- Project Name -->
                        <h3 class="text-xl font-bold text-neutral-900 mb-2 pr-24 leading-tight">
                            {{ $request->project_name }}
                        </h3>

                        <!-- Description -->
                        <p class="text-sm text-neutral-600 leading-relaxed line-clamp-2">
                            {{ $request->request_description }}
                        </p>
                    </div>

                    <!-- Key Information Grid -->
                    <div class="px-6 py-4 bg-gradient-to-br from-neutral-50 to-neutral-100/50 border-y border-neutral-200">
                        <div class="grid grid-cols-3 gap-3">
                            <!-- Budget -->
                            @if($request->estimated_budget)
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 mb-1">
                                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-neutral-500 font-medium">Budget</p>
                                    <p class="text-sm font-bold text-neutral-900">₱{{ number_format($request->estimated_budget, 0) }}</p>
                                </div>
                            @endif

                            <!-- Deadline -->
                            @if($request->deadline)
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $request->deadline->isPast() ? 'bg-error-100' : 'bg-accent-100' }} mb-1">
                                        <svg class="w-4 h-4 {{ $request->deadline->isPast() ? 'text-error-600' : 'text-accent-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-neutral-500 font-medium">Deadline</p>
                                    <p class="text-xs font-bold {{ $request->deadline->isPast() ? 'text-error-600' : 'text-neutral-900' }}">
                                        {{ $request->deadline->format('M j, Y') }}
                                    </p>
                                </div>
                            @endif

                            <!-- Priority -->
                            <div class="text-center">
                                @php
                                    $priorityConfig = match($request->priority) {
                                        'high' => ['bg' => 'bg-error-100', 'text' => 'text-error-600', 'icon' => 'M5 10l7-7m0 0l7 7m-7-7v18'],
                                        'medium' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-600', 'icon' => 'M5 12h14'],
                                        'low' => ['bg' => 'bg-success-100', 'text' => 'text-success-600', 'icon' => 'M19 14l-7 7m0 0l-7-7m7 7V3'],
                                        default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-600', 'icon' => 'M5 12h14']
                                    };
                                @endphp
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $priorityConfig['bg'] }} mb-1">
                                    <svg class="w-4 h-4 {{ $priorityConfig['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $priorityConfig['icon'] }}"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-neutral-500 font-medium">Priority</p>
                                <p class="text-sm font-bold {{ $priorityConfig['text'] }}">{{ ucfirst($request->priority) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="px-6 py-3 bg-white">
                        <div class="flex items-center justify-between text-xs text-neutral-500">
                            <div class="flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $request->created_at->diffForHumans() }}</span>
                            </div>
                            @if($request->updated_at != $request->created_at)
                                <span class="text-neutral-400">Updated {{ $request->updated_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-4 bg-neutral-50 border-t border-neutral-200">
                        <div class="flex gap-2">
                            <a href="{{ route('client.requests.show', $request->id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-white border-2 border-primary-500 text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-all duration-200 text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                            
                            @if($request->status === 'pending')
                                <a href="{{ route('client.requests.edit', $request->id) }}" 
                                   class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold rounded-lg hover:from-accent-600 hover:to-accent-700 transition-all duration-200 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('client.requests.destroy', $request->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border-2 border-error-500 text-error-600 font-semibold rounded-lg hover:bg-error-50 transition-all duration-200 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-8">
                {{ $requests->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="glass-card p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-accent-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-neutral-900 mb-3">No service requests yet</h3>
                <p class="text-neutral-600 mb-8 text-lg leading-relaxed">Create your first service request to get started with our platform and bring your ideas to life</p>
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
    const requestCards = document.querySelectorAll('.request-card');

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
            
            // Filter requests with animation
            requestCards.forEach(card => {
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
    requestCards.forEach(card => {
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });
});
</script>
@endsection
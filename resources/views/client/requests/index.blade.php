@extends('client.layouts.app')

@section('title', 'Service Requests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Service Requests', 'icon' => 'file-text']
    ]" />

    <!-- Header -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-neutral-50 rounded-xl">
                    <x-lucide-file-text class="w-8 h-8 text-neutral-400" />
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800">Service Requests</h1>
                    <p class="text-neutral-500 mt-1">Manage your service requests and track their progress</p>
                </div>
            </div>
            <a href="{{ route('client.requests.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white font-medium rounded-xl shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                <x-lucide-plus class="w-4 h-4" />
                New Request
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-2">
            <nav class="flex flex-wrap gap-2" aria-label="Tabs">
                <button data-filter="all" class="filter-tab active flex-1 min-w-fit px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 bg-primary-600 text-white shadow-sm">
                    All <span class="ml-1 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $requests->count() }}</span>
                </button>
                <button data-filter="pending" class="filter-tab flex-1 min-w-fit px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    Pending <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'pending')->count() }}</span>
                </button>
                <button data-filter="approved" class="filter-tab flex-1 min-w-fit px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    Approved <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'approved')->count() }}</span>
                </button>
                <button data-filter="pending_payment" class="filter-tab flex-1 min-w-fit px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    Pending Payment <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'pending_payment')->count() }}</span>
                </button>
                <button data-filter="paid" class="filter-tab flex-1 min-w-fit px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    Paid <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'paid')->count() }}</span>
                </button>
                <button data-filter="in_progress" class="filter-tab flex-1 min-w-fit px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    In Progress <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'in_progress')->count() }}</span>
                </button>
                <button data-filter="completed" class="filter-tab flex-1 min-w-fit px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    Completed <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'completed')->count() }}</span>
                </button>
                <button data-filter="rejected" class="filter-tab flex-1 min-w-fit px-4 py-2.5 rounded-lg font-medium text-sm transition-all duration-200 text-neutral-600 hover:bg-neutral-50">
                    Rejected <span class="ml-1 px-2 py-0.5 bg-neutral-100 rounded-full text-xs">{{ $requests->where('status', 'rejected')->count() }}</span>
                </button>
            </nav>
        </div>
    </div>

    @if($requests->count() > 0)
        <!-- Requests Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($requests as $request)
                <div class="request-card bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col h-full" data-status="{{ $request->status }}">
                    <!-- Card Header with Status -->
                    <div class="relative p-6 pb-4">
                        <!-- Status Badge - Top Right -->
                        <div class="absolute top-4 right-4">
                            @php
                                $statusConfig = match($request->status) {
                                    'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-700', 'label' => 'Pending Review'],
                                    'approved' => ['bg' => 'bg-success-100', 'text' => 'text-success-700', 'label' => 'Approved'],
                                    'rejected' => ['bg' => 'bg-error-100', 'text' => 'text-error-700', 'label' => 'Rejected'],
                                    'pending_payment' => ['bg' => 'bg-secondary-100', 'text' => 'text-secondary-700', 'label' => 'Awaiting Payment'],
                                    'paid' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-700', 'label' => 'Paid'],
                                    'in_progress' => ['bg' => 'bg-accent-100', 'text' => 'text-accent-700', 'label' => 'In Progress'],
                                    'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-700', 'label' => 'Completed'],
                                    default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700', 'label' => 'Unknown']
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
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
                        <h3 class="text-lg font-semibold text-neutral-800 mb-2 pr-24 leading-snug min-h-[3.25rem] line-clamp-2 overflow-hidden">
                            {{ $request->project_name }}
                        </h3>

                        <!-- Description -->
                        <p class="text-sm text-neutral-600 leading-relaxed line-clamp-2 min-h-[2.5rem]">
                            {{ $request->request_description ?? 'No description provided.' }}
                        </p>
                    </div>

                    <!-- Key Information Grid - Always Show All 3 -->
                    <div class="flex-1 px-6 py-4 border-t border-neutral-100">
                        <div class="grid grid-cols-3 gap-3">
                            <!-- Budget -->
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-neutral-50 mb-1">
                                    <x-lucide-circle-dollar-sign class="w-4 h-4 text-neutral-400" />
                                </div>
                                <p class="text-xs text-neutral-500 font-medium">Budget</p>
                                @if($request->approved_budget)
                                    {{-- Show approved budget with discount info if applicable --}}
                                    @if($request->total_discount_amount > 0)
                                        <p class="text-xs text-neutral-400 line-through">₱{{ number_format($request->getOriginalBudget(), 0) }}</p>
                                    @endif
                                    <p class="text-sm font-semibold text-success-600">₱{{ number_format($request->approved_budget, 0) }}</p>
                                @elseif($request->estimated_budget)
                                    <p class="text-sm font-semibold text-neutral-800">₱{{ number_format($request->estimated_budget, 0) }}</p>
                                @else
                                    <p class="text-sm text-neutral-400 italic">Not set</p>
                                @endif
                            </div>

                            <!-- Deadline -->
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $request->deadline && $request->deadline->isPast() ? 'bg-error-50' : 'bg-neutral-50' }} mb-1">
                                    <x-lucide-calendar class="w-4 h-4 {{ $request->deadline && $request->deadline->isPast() ? 'text-error-500' : 'text-neutral-400' }}" />
                                </div>
                                <p class="text-xs text-neutral-500 font-medium">Deadline</p>
                                @if($request->deadline)
                                    <p class="text-sm font-semibold {{ $request->deadline->isPast() ? 'text-error-600' : 'text-neutral-800' }}">
                                        {{ $request->deadline->format('M j, Y') }}
                                    </p>
                                @else
                                    <p class="text-sm text-neutral-400 italic">Not set</p>
                                @endif
                            </div>

                            <!-- Priority -->
                            <div class="text-center">
                                @php
                                    $priorityConfig = match($request->priority ?? 'normal') {
                                        'high' => ['bg' => 'bg-error-50', 'text' => 'text-error-500', 'icon' => 'arrow-up'],
                                        'medium' => ['bg' => 'bg-warning-50', 'text' => 'text-warning-500', 'icon' => 'minus'],
                                        'low' => ['bg' => 'bg-success-50', 'text' => 'text-success-500', 'icon' => 'arrow-down'],
                                        default => ['bg' => 'bg-neutral-50', 'text' => 'text-neutral-400', 'icon' => 'minus']
                                    };
                                @endphp
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $priorityConfig['bg'] }} mb-1">
                                    @if($priorityConfig['icon'] === 'arrow-up')
                                        <x-lucide-arrow-up class="w-4 h-4 {{ $priorityConfig['text'] }}" />
                                    @elseif($priorityConfig['icon'] === 'arrow-down')
                                        <x-lucide-arrow-down class="w-4 h-4 {{ $priorityConfig['text'] }}" />
                                    @else
                                        <x-lucide-minus class="w-4 h-4 {{ $priorityConfig['text'] }}" />
                                    @endif
                                </div>
                                <p class="text-xs text-neutral-500 font-medium">Priority</p>
                                <p class="text-sm font-semibold {{ $priorityConfig['text'] }}">{{ ucfirst($request->priority ?? 'Normal') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps & Actions - Always at Bottom -->
                    <div class="mt-auto">
                        <div class="px-6 py-3 border-t border-neutral-100">
                            <div class="flex items-center justify-between text-xs text-neutral-400">
                                <div class="flex items-center gap-1">
                                    <x-lucide-clock class="w-3 h-3" />
                                    <span>{{ $request->created_at->diffForHumans() }}</span>
                                </div>
                                @if($request->updated_at != $request->created_at)
                                    <span>Updated {{ $request->updated_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-4 border-t border-neutral-100">
                            <div class="flex gap-2">
                                <a href="{{ route('client.requests.show', $request->id) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-white border border-neutral-100 text-neutral-700 font-medium rounded-xl hover:bg-neutral-50 hover:shadow-sm transition-all text-sm">
                                    <x-lucide-eye class="w-4 h-4 mr-1.5" />
                                    View
                                </a>
                                
                                @if($request->status === 'pending')
                                    <a href="{{ route('client.requests.edit', $request->id) }}" 
                                       class="inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all text-sm">
                                        <x-lucide-pencil class="w-4 h-4 mr-1.5" />
                                        Edit
                                    </a>
                                    <form action="{{ route('client.requests.destroy', $request->id) }}" 
                                          method="POST" 
                                          onsubmit="return window.Alerts.confirmDeleteForm(event, 'Cancel Request?', 'Are you sure you want to cancel this request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-error-200 text-error-600 font-medium rounded-xl hover:bg-error-50 transition-all duration-200 text-sm">
                                            <x-lucide-x class="w-4 h-4" />
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-8">
                <x-ui.pagination :paginator="$requests" />
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-16 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-16 h-16 bg-neutral-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <x-lucide-file-text class="w-8 h-8 text-neutral-400" />
                </div>
                <h3 class="text-lg font-semibold text-neutral-800 mb-2">No service requests yet</h3>
                <p class="text-neutral-500 mb-6">Create your first service request to get started with our platform and bring your ideas to life</p>
                <a href="{{ route('client.requests.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-medium rounded-xl shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
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
    const requestCards = document.querySelectorAll('.request-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active tab
            filterTabs.forEach(t => {
                t.classList.remove('active', 'bg-primary-600', 'text-white', 'shadow-sm');
                t.classList.add('text-neutral-600');
            });
            this.classList.add('active', 'bg-primary-600', 'text-white', 'shadow-sm');
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
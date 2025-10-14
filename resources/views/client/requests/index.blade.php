@extends('client.layout')

@section('title', 'Service Requests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-neutral-900">Service Requests</h1>
            <p class="text-neutral-600 mt-2">Manage your service requests and track their progress</p>
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
                    All Requests ({{ $requests->count() }})
                </button>
                <button data-filter="pending" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Pending ({{ $requests->where('status', 'pending')->count() }})
                </button>
                <button data-filter="approved" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Approved ({{ $requests->where('status', 'approved')->count() }})
                </button>
                <button data-filter="pending_payment" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Pending Payment ({{ $requests->where('status', 'pending_payment')->count() }})
                </button>
                <button data-filter="paid" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Paid ({{ $requests->where('status', 'paid')->count() }})
                </button>
                <button data-filter="in_progress" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    In Progress ({{ $requests->where('status', 'in_progress')->count() }})
                </button>
                <button data-filter="completed" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Completed ({{ $requests->where('status', 'completed')->count() }})
                </button>
                <button data-filter="rejected" class="filter-tab border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    Rejected ({{ $requests->where('status', 'rejected')->count() }})
                </button>
            </nav>
        </div>
    </div>

    @if($requests->count() > 0)
        <!-- Requests Grid -->
        <div class="grid grid-cols-1 gap-6">
            @foreach($requests as $request)
                <div class="request-card card hover:shadow-lg transition-shadow" data-status="{{ $request->status }}">
                    <div class="flex items-start justify-between">
                        <!-- Request Content -->
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-neutral-900">{{ $request->project_name }}</h3>
                                <div class="flex items-center space-x-3">
                                    @php
                                        $badgeColor = match($request->status) {
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'error',
                                            'pending_payment' => 'info',
                                            'paid' => 'primary',
                                            'in_progress' => 'warning',
                                            'completed' => 'success',
                                            default => 'neutral'
                                        };
                                        $statusLabel = match($request->status) {
                                            'pending' => 'Pending Review',
                                            'approved' => 'Approved',
                                            'rejected' => 'Rejected',
                                            'pending_payment' => 'Pending Payment',
                                            'paid' => 'Payment Confirmed',
                                            'in_progress' => 'In Progress',
                                            'completed' => 'Completed',
                                            default => ucfirst(str_replace('_', ' ', $request->status))
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $badgeColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                    <span class="text-sm text-neutral-500">
                                        Request #{{ $request->id }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-neutral-600 mb-4">{{ Str::limit($request->request_description, 200) }}</p>

                            <!-- Request Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- Budget -->
                                @if($request->estimated_budget)
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        <span class="text-neutral-600">Budget: <span class="font-medium">${{ number_format($request->estimated_budget, 2) }}</span></span>
                                    </div>
                                @endif

                                <!-- Deadline -->
                                @if($request->deadline)
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-neutral-600">
                                            Deadline: 
                                            <span class="font-medium {{ $request->deadline->isPast() ? 'text-error-600' : '' }}">
                                                {{ $request->deadline->format('M j, Y') }}
                                            </span>
                                        </span>
                                    </div>
                                @endif

                                <!-- Priority -->
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                    </svg>
                                    <span class="text-neutral-600">
                                        Priority: 
                                        <span class="font-medium {{ $request->priority === 'high' ? 'text-error-600' : ($request->priority === 'medium' ? 'text-warning-600' : 'text-success-600') }}">
                                            {{ ucfirst($request->priority) }}
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <!-- Service Type -->
                            <div class="flex flex-wrap items-center gap-4 mb-4">
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-neutral-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                                    </svg>
                                    <span class="font-medium text-primary-600">{{ ucfirst(str_replace('_', ' ', $request->service_type)) }}</span>
                                </div>
                            </div>

                            <!-- Timestamps -->
                            <div class="flex items-center justify-between text-sm text-neutral-500 pt-4 border-t border-neutral-200">
                                <span>Created {{ $request->created_at->format('M j, Y \a\t g:i A') }}</span>
                                @if($request->updated_at != $request->created_at)
                                    <span>Updated {{ $request->updated_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="ml-6 flex flex-col space-y-2">
                            <a href="{{ route('client.requests.show', $request->id) }}" 
                               class="btn-secondary btn-sm">
                                View Details
                            </a>
                            @if($request->status === 'pending')
                                <a href="{{ route('client.requests.edit', $request->id) }}" 
                                   class="btn-primary btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('client.requests.destroy', $request->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to cancel this request?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-error btn-sm w-full">
                                        Cancel
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
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-neutral-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">No service requests yet</h3>
            <p class="text-neutral-600 mb-6">Create your first service request to get started with our platform</p>
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
    const requestCards = document.querySelectorAll('.request-card');

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
            
            // Filter requests
            requestCards.forEach(card => {
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
@extends('layouts.admin')

@section('title', 'All Referrals - Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="heading-serif text-3xl text-primary-700 mb-2">All Referrals</h1>
            <p class="text-neutral-600">View and manage all referral records</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.referrals.index') }}" class="btn-secondary">
                <i class="fas fa-chart-line mr-2"></i>Analytics Dashboard
            </a>
            <button onclick="exportFiltered()" class="btn-primary">
                <i class="fas fa-download mr-2"></i>Export Filtered
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card p-6 mb-6">
        <form method="GET" action="{{ route('admin.referrals.list') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-semibold text-neutral-700 mb-2">Search</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Name, email, or code..."
                        class="w-full px-4 py-2 rounded-lg border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-neutral-700 mb-2">Status</label>
                    <select 
                        id="status" 
                        name="status"
                        class="w-full px-4 py-2 rounded-lg border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rewarded" {{ request('status') === 'rewarded' ? 'selected' : '' }}>Rewarded</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-semibold text-neutral-700 mb-2">From Date</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        class="w-full px-4 py-2 rounded-lg border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-semibold text-neutral-700 mb-2">To Date</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        class="w-full px-4 py-2 rounded-lg border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex justify-between items-center pt-2">
                <div class="text-sm text-neutral-600">
                    Showing {{ $referrals->firstItem() ?? 0 }} to {{ $referrals->lastItem() ?? 0 }} of {{ $referrals->total() }} results
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.referrals.list') }}" class="btn-secondary btn-sm">
                        <i class="fas fa-redo mr-2"></i>Clear Filters
                    </a>
                    <button type="submit" class="btn-primary btn-sm">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Referrals Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">
                            <a href="{{ route('admin.referrals.list', array_merge(request()->all(), ['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-primary-600">
                                ID
                                @if(request('sort') === 'id')
                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Referrer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Referred User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">
                            <a href="{{ route('admin.referrals.list', array_merge(request()->all(), ['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-primary-600">
                                Status
                                @if(request('sort') === 'status')
                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Rewards</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">
                            <a href="{{ route('admin.referrals.list', array_merge(request()->all(), ['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-primary-600">
                                Date
                                @if(request('sort') === 'created_at' || !request('sort'))
                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse($referrals as $referral)
                    <tr class="hover:bg-primary-50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="text-sm font-mono text-neutral-800">#{{ $referral->id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-neutral-800">{{ $referral->referrer->fullName }}</p>
                                <p class="text-xs text-neutral-600">{{ $referral->referrer->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-neutral-800">{{ $referral->referred->fullName }}</p>
                                <p class="text-xs text-neutral-600">{{ $referral->referred->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <code class="px-2 py-1 bg-primary-100 text-primary-700 rounded text-sm font-mono">{{ $referral->referral_code }}</code>
                        </td>
                        <td class="px-4 py-3">
                            @if($referral->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                            @elseif($referral->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <i class="fas fa-check mr-1"></i>Completed
                            </span>
                            @elseif($referral->status === 'rewarded')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-gift mr-1"></i>Rewarded
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($referral->status === 'pending')
                            <div class="text-sm">
                                <p class="text-neutral-600">Pending: {{ number_format($referral->pending_points) }} pts</p>
                            </div>
                            @elseif($referral->status === 'rewarded')
                            <div class="text-sm">
                                <p class="font-semibold text-green-700">{{ number_format($referral->earned_points) }} pts</p>
                                @if($referral->referrerCoupon)
                                <p class="text-xs text-neutral-600">+ {{ $referral->referrerCoupon->discount_value }}% coupon</p>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-neutral-500">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm">
                                <p class="text-neutral-800">{{ $referral->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-neutral-600">{{ $referral->created_at->format('h:i A') }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.referrals.show', $referral->id) }}" 
                                   class="text-primary-600 hover:text-primary-700"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($referral->status === 'pending')
                                <form method="POST" action="{{ route('admin.referrals.process', $referral->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-700"
                                            title="Process Manually"
                                            onclick="return confirm('Process this referral manually? This will award rewards to the referrer.')">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <i class="fas fa-inbox text-neutral-300 text-5xl mb-4"></i>
                            <p class="text-neutral-600 text-lg">No referrals found</p>
                            <p class="text-neutral-500 text-sm mt-2">Try adjusting your filters</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($referrals->hasPages())
        <div class="px-4 py-4 border-t border-neutral-200">
            {{ $referrals->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function exportFiltered() {
    const params = new URLSearchParams(window.location.search);
    params.append('format', 'csv');
    window.location.href = '{{ route("admin.referrals.export") }}?' + params.toString();
}
</script>
@endpush
@endsection

@extends('admin.layouts.app')

@section('title', 'All Referrals - Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Home', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Referrals', 'url' => route('admin.referrals.index'), 'icon' => 'users'],
        ['label' => 'All Referrals', 'icon' => 'list']
    ]" class="mb-4" />

    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <x-ui.page-header
            title="All Referrals"
            subtitle="View and manage all referral records"
        />
        <div class="flex gap-3">
            <x-ui.button 
                href="{{ route('admin.referrals.index') }}" 
                variant="secondary"
                class="inline-flex items-center gap-2"
            >
                <x-lucide-bar-chart-3 class="w-4 h-4" />
                Analytics Dashboard
            </x-ui.button>
            <x-ui.button 
                variant="primary"
                onclick="exportFiltered()"
                class="inline-flex items-center gap-2"
            >
                <x-lucide-download class="w-4 h-4" />
                Export Filtered
            </x-ui.button>
        </div>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.referrals.list') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-neutral-700 mb-2">Search</label>
                        <x-ui.input 
                            type="text" 
                            id="search" 
                            name="search" 
                            :value="request('search')"
                            placeholder="Name, email, or code..."
                        />
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                        <x-ui.select id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rewarded" {{ request('status') === 'rewarded' ? 'selected' : '' }}>Rewarded</option>
                        </x-ui.select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-neutral-700 mb-2">From Date</label>
                        <x-ui.input 
                            type="date" 
                            id="date_from" 
                            name="date_from" 
                            :value="request('date_from')"
                        />
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-neutral-700 mb-2">To Date</label>
                        <x-ui.input 
                            type="date" 
                            id="date_to" 
                            name="date_to" 
                            :value="request('date_to')"
                        />
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="flex justify-between items-center pt-2">
                    <div class="text-sm text-neutral-600">
                        Showing {{ $referrals->firstItem() ?? 0 }} to {{ $referrals->lastItem() ?? 0 }} of {{ $referrals->total() }} results
                    </div>
                    <div class="flex gap-3">
                        <x-ui.button 
                            href="{{ route('admin.referrals.list') }}" 
                            variant="secondary"
                            size="sm"
                            class="inline-flex items-center gap-2"
                        >
                            <x-lucide-refresh-cw class="w-4 h-4" />
                            Clear Filters
                        </x-ui.button>
                        <x-ui.button 
                            type="submit" 
                            variant="primary"
                            size="sm"
                            class="inline-flex items-center gap-2"
                        >
                            <x-lucide-search class="w-4 h-4" />
                            Apply Filters
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Referrals Table -->
    <x-ui.card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <a href="{{ route('admin.referrals.list', array_merge(request()->all(), ['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-primary-600">
                                ID
                                @if(request('sort') === 'id')
                                    @if(request('direction') === 'asc')
                                        <x-lucide-arrow-up class="w-3 h-3" />
                                    @else
                                        <x-lucide-arrow-down class="w-3 h-3" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Referrer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Referred User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <a href="{{ route('admin.referrals.list', array_merge(request()->all(), ['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-primary-600">
                                Status
                                @if(request('sort') === 'status')
                                    @if(request('direction') === 'asc')
                                        <x-lucide-arrow-up class="w-3 h-3" />
                                    @else
                                        <x-lucide-arrow-down class="w-3 h-3" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Rewards</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <a href="{{ route('admin.referrals.list', array_merge(request()->all(), ['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-primary-600">
                                Date
                                @if(request('sort') === 'created_at' || !request('sort'))
                                    @if(request('direction') === 'asc')
                                        <x-lucide-arrow-up class="w-3 h-3" />
                                    @else
                                        <x-lucide-arrow-down class="w-3 h-3" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse($referrals as $referral)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="text-sm font-mono text-neutral-800">#{{ $referral->id }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-neutral-800">{{ $referral->referrer->fullName }}</p>
                                <p class="text-xs text-neutral-500">{{ $referral->referrer->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-neutral-800">{{ $referral->referred->fullName }}</p>
                                <p class="text-xs text-neutral-500">{{ $referral->referred->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <code class="px-2 py-1 bg-primary-100 text-primary-700 rounded text-sm font-mono">{{ $referral->referral_code }}</code>
                        </td>
                        <td class="px-4 py-3">
                            @if($referral->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                <x-lucide-clock class="w-3 h-3 mr-1" />
                                Pending
                            </span>
                            @elseif($referral->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-700">
                                <x-lucide-check class="w-3 h-3 mr-1" />
                                Completed
                            </span>
                            @elseif($referral->status === 'rewarded')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                <x-lucide-gift class="w-3 h-3 mr-1" />
                                Rewarded
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
                                <p class="font-medium text-success-700">{{ number_format($referral->earned_points) }} pts</p>
                                @if($referral->referrerCoupon)
                                <p class="text-xs text-neutral-500">+ {{ $referral->referrerCoupon->discount_value }}% coupon</p>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-neutral-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm">
                                <p class="text-neutral-800">{{ $referral->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-neutral-500">{{ $referral->created_at->format('h:i A') }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.referrals.show', $referral->id) }}" 
                                   class="text-primary-600 hover:text-primary-700"
                                   title="View Details">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                @if($referral->status === 'pending')
                                <form method="POST" action="{{ route('admin.referrals.process', $referral->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-success-600 hover:text-success-700"
                                            title="Process Manually"
                                            onclick="return window.Alerts.confirmForm(event, 'Process Referral', 'Process this referral manually? This will award rewards to the referrer.')">
                                        <x-lucide-check-circle class="w-4 h-4" />
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-lucide-inbox class="w-8 h-8 text-neutral-400" />
                            </div>
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
            <x-ui.pagination :paginator="$referrals" />
        </div>
        @endif
    </x-ui.card>
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

@extends('layouts.admin')

@section('title', 'Manage Referral Codes - Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="heading-serif text-3xl text-primary-700 mb-2">Referral Codes Management</h1>
            <p class="text-neutral-600">View and manage all referral codes</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.referrals.index') }}" class="btn-secondary">
                <i class="fas fa-chart-line mr-2"></i>Analytics Dashboard
            </a>
            <button onclick="exportCodes()" class="btn-primary">
                <i class="fas fa-download mr-2"></i>Export Codes
            </button>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-code text-3xl text-primary-600"></i>
                <span class="text-xs font-semibold text-neutral-500 uppercase">Total Codes</span>
            </div>
            <h3 class="text-2xl font-bold text-primary-700">{{ number_format($stats['total_codes']) }}</h3>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-check-circle text-3xl text-green-600"></i>
                <span class="text-xs font-semibold text-neutral-500 uppercase">Active</span>
            </div>
            <h3 class="text-2xl font-bold text-green-700">{{ number_format($stats['active_codes']) }}</h3>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-users text-3xl text-blue-600"></i>
                <span class="text-xs font-semibold text-neutral-500 uppercase">Total Uses</span>
            </div>
            <h3 class="text-2xl font-bold text-blue-700">{{ number_format($stats['total_uses']) }}</h3>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-percentage text-3xl text-purple-600"></i>
                <span class="text-xs font-semibold text-neutral-500 uppercase">Avg Conversion</span>
            </div>
            <h3 class="text-2xl font-bold text-purple-700">{{ number_format($stats['avg_conversion'], 1) }}%</h3>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card p-6 mb-6">
        <form method="GET" action="{{ route('admin.referrals.codes') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-semibold text-neutral-700 mb-2">Search</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Code or user name..."
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
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort" class="block text-sm font-semibold text-neutral-700 mb-2">Sort By</label>
                    <select 
                        id="sort" 
                        name="sort"
                        class="w-full px-4 py-2 rounded-lg border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                        <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="total_referrals" {{ request('sort') === 'total_referrals' ? 'selected' : '' }}>Total Referrals</option>
                        <option value="successful_referrals" {{ request('sort') === 'successful_referrals' ? 'selected' : '' }}>Successful Referrals</option>
                        <option value="lifetime_earnings_points" {{ request('sort') === 'lifetime_earnings_points' ? 'selected' : '' }}>Total Earnings</option>
                    </select>
                </div>

                <!-- Min Referrals -->
                <div>
                    <label for="min_referrals" class="block text-sm font-semibold text-neutral-700 mb-2">Min Referrals</label>
                    <input 
                        type="number" 
                        id="min_referrals" 
                        name="min_referrals" 
                        value="{{ request('min_referrals') }}"
                        placeholder="0"
                        min="0"
                        class="w-full px-4 py-2 rounded-lg border border-neutral-300 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    >
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex justify-between items-center pt-2">
                <div class="text-sm text-neutral-600">
                    Showing {{ $codes->firstItem() ?? 0 }} to {{ $codes->lastItem() ?? 0 }} of {{ $codes->total() }} codes
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.referrals.codes') }}" class="btn-secondary btn-sm">
                        <i class="fas fa-redo mr-2"></i>Clear Filters
                    </a>
                    <button type="submit" class="btn-primary btn-sm">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Codes Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Owner</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Total Referrals</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Pending</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Successful</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Conversion</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Total Earnings</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Last Used</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse($codes as $code)
                    <tr class="hover:bg-primary-50 transition-colors">
                        <td class="px-4 py-3">
                            <code class="px-3 py-1.5 bg-gradient-to-r from-primary-100 to-primary-200 text-primary-800 rounded-lg text-sm font-mono font-bold">
                                {{ $code->code }}
                            </code>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-neutral-800">{{ $code->user->fullName }}</p>
                                <p class="text-xs text-neutral-600">{{ $code->user->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($code->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-lg font-bold text-primary-700">{{ $code->total_referrals }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-lg font-bold text-yellow-700">{{ $code->pending_referrals }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-lg font-bold text-green-700">{{ $code->successful_referrals }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $conversionRate = $code->total_referrals > 0 
                                    ? ($code->successful_referrals / $code->total_referrals * 100) 
                                    : 0;
                            @endphp
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-16 bg-neutral-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" 
                                         style="width: {{ $conversionRate }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-neutral-700">{{ number_format($conversionRate, 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-sm">
                                <p class="font-bold text-purple-700">{{ number_format($code->lifetime_earnings_points) }}</p>
                                <p class="text-xs text-neutral-600">points</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($code->last_used_at)
                            <div class="text-sm">
                                <p class="text-neutral-800">{{ $code->last_used_at->format('M d, Y') }}</p>
                                <p class="text-xs text-neutral-600">{{ $code->last_used_at->diffForHumans() }}</p>
                            </div>
                            @else
                            <span class="text-sm text-neutral-500">Never</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.show', $code->user->id) }}" 
                                   class="text-primary-600 hover:text-primary-700"
                                   title="View User">
                                    <i class="fas fa-user"></i>
                                </a>
                                <form method="POST" 
                                      action="{{ route('admin.referrals.codes.toggle', $code->id) }}" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to {{ $code->is_active ? 'deactivate' : 'activate' }} this code?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="{{ $code->is_active ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700' }}"
                                            title="{{ $code->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $code->is_active ? 'ban' : 'check-circle' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center">
                            <i class="fas fa-code text-neutral-300 text-5xl mb-4"></i>
                            <p class="text-neutral-600 text-lg">No referral codes found</p>
                            <p class="text-neutral-500 text-sm mt-2">Codes are generated automatically when users access their referral dashboard</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($codes->hasPages())
        <div class="px-4 py-4 border-t border-neutral-200">
            {{ $codes->links() }}
        </div>
        @endif
    </div>

    <!-- Performance Insights -->
    @if($codes->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Top Performing Codes -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4">Top Performing Codes</h3>
            <div class="space-y-3">
                @foreach($codes->sortByDesc('successful_referrals')->take(5) as $topCode)
                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-transparent rounded-lg">
                    <div class="flex items-center gap-3">
                        <code class="px-2 py-1 bg-primary-100 text-primary-700 rounded text-sm font-mono">{{ $topCode->code }}</code>
                        <span class="text-sm text-neutral-700">{{ $topCode->user->fullName }}</span>
                    </div>
                    <span class="text-lg font-bold text-green-700">{{ $topCode->successful_referrals }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recently Used Codes -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4">Recently Used Codes</h3>
            <div class="space-y-3">
                @foreach($codes->whereNotNull('last_used_at')->sortByDesc('last_used_at')->take(5) as $recentCode)
                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-transparent rounded-lg">
                    <div class="flex items-center gap-3">
                        <code class="px-2 py-1 bg-primary-100 text-primary-700 rounded text-sm font-mono">{{ $recentCode->code }}</code>
                        <span class="text-sm text-neutral-700">{{ $recentCode->user->fullName }}</span>
                    </div>
                    <span class="text-sm text-neutral-600">{{ $recentCode->last_used_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function exportCodes() {
    const params = new URLSearchParams(window.location.search);
    params.append('format', 'csv');
    params.append('type', 'codes');
    window.location.href = '{{ route("admin.referrals.export") }}?' + params.toString();
}
</script>
@endpush
@endsection

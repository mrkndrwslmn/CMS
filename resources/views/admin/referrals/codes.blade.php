                        @extends('admin.layouts.app')

@section('title', 'Manage Referral Codes - Admin Dashboard')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Referrals', 'route' => 'admin.referrals.index', 'icon' => 'gift'],
        ['label' => 'Codes', 'icon' => 'qr-code'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800 mb-1">Referral Codes Management</h1>
            <p class="text-neutral-500">View and manage all referral codes</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <x-ui.button variant="secondary" href="{{ route('admin.referrals.index') }}">
                <x-lucide-bar-chart-2 class="w-4 h-4" />
                Analytics Dashboard
            </x-ui.button>
            <x-ui.button variant="primary" onclick="exportCodes()">
                <x-lucide-download class="w-4 h-4" />
                Export Codes
            </x-ui.button>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Total Codes</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ number_format($stats['total_codes']) }}</h3>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-qr-code class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Active</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ number_format($stats['active_codes']) }}</h3>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Total Uses</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ number_format($stats['total_uses']) }}</h3>
                </div>
                <div class="p-3 bg-info-50 rounded-xl">
                    <x-lucide-users class="w-5 h-5 text-info-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Avg Conversion</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ number_format($stats['avg_conversion'], 1) }}%</h3>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl">
                    <x-lucide-percent class="w-5 h-5 text-purple-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Filters -->
    <x-ui.card class="p-6 mb-6">
        <form method="GET" action="{{ route('admin.referrals.codes') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-neutral-700 mb-2">Search</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Code or user name..."
                        class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                    <select 
                        id="status" 
                        name="status"
                        class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors"
                    >
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-neutral-700 mb-2">Sort By</label>
                    <select 
                        id="sort" 
                        name="sort"
                        class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors"
                    >
                        <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="total_referrals" {{ request('sort') === 'total_referrals' ? 'selected' : '' }}>Total Referrals</option>
                        <option value="successful_referrals" {{ request('sort') === 'successful_referrals' ? 'selected' : '' }}>Successful Referrals</option>
                        <option value="lifetime_earnings_points" {{ request('sort') === 'lifetime_earnings_points' ? 'selected' : '' }}>Total Earnings</option>
                    </select>
                </div>

                <!-- Min Referrals -->
                <div>
                    <label for="min_referrals" class="block text-sm font-medium text-neutral-700 mb-2">Min Referrals</label>
                    <input 
                        type="number" 
                        id="min_referrals" 
                        name="min_referrals" 
                        value="{{ request('min_referrals') }}"
                        placeholder="0"
                        min="0"
                        class="w-full px-4 py-2.5 rounded-xl border border-neutral-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors"
                    >
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-2">
                <div class="text-sm text-neutral-500">
                    Showing {{ $codes->firstItem() ?? 0 }} to {{ $codes->lastItem() ?? 0 }} of {{ $codes->total() }} codes
                </div>
                <div class="flex gap-3">
                    <x-ui.button variant="secondary" href="{{ route('admin.referrals.codes') }}" size="sm">
                        <x-lucide-rotate-ccw class="w-4 h-4" />
                        Clear Filters
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary" size="sm">
                        <x-lucide-search class="w-4 h-4" />
                        Apply Filters
                    </x-ui.button>
                </div>
            </div>
        </form>
    </x-ui.card>

    <!-- Codes Table -->
    <x-ui.card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Owner</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Referrals</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Pending</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Successful</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Conversion</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Earnings</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Last Used</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse($codes as $code)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3">
                            <code class="px-3 py-1.5 text-primary-800 rounded-lg text-sm font-mono font-semibold">
                                {{ $code->code }}
                            </code>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-neutral-800">{{ $code->user->fullName }}</p>
                                <p class="text-xs text-neutral-500">{{ $code->user->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($code->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                <x-lucide-check-circle class="w-3 h-3 mr-1" />
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-700">
                                <x-lucide-x-circle class="w-3 h-3 mr-1" />
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-lg font-semibold text-primary-700">{{ $code->total_referrals }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-lg font-semibold text-warning-700">{{ $code->pending_referrals }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-lg font-semibold text-success-700">{{ $code->successful_referrals }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $conversionRate = $code->total_referrals > 0 
                                    ? ($code->successful_referrals / $code->total_referrals * 100) 
                                    : 0;
                            @endphp
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-16 bg-neutral-200 rounded-full h-2">
                                    <div class="bg-success-500 h-2 rounded-full" 
                                         style="width: {{ $conversionRate }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">{{ number_format($conversionRate, 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-sm">
                                <p class="font-semibold text-purple-700">{{ number_format($code->lifetime_earnings_points) }}</p>
                                <p class="text-xs text-neutral-500">points</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($code->last_used_at)
                            <div class="text-sm">
                                <p class="text-neutral-800">{{ $code->last_used_at->format('M d, Y') }}</p>
                                <p class="text-xs text-neutral-500">{{ $code->last_used_at->diffForHumans() }}</p>
                            </div>
                            @else
                            <span class="text-sm text-neutral-400">Never</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.show', $code->user->id) }}" 
                                   class="text-primary-600 hover:text-primary-700"
                                   title="View User">
                                    <x-lucide-user class="w-4 h-4" />
                                </a>
                                <form method="POST" 
                                      action="{{ route('admin.referrals.codes.toggle', $code->id) }}" 
                                      class="inline"
                                      onsubmit="return window.Alerts.confirmForm(event, '{{ $code->is_active ? 'Deactivate' : 'Activate' }} Code', 'Are you sure you want to {{ $code->is_active ? 'deactivate' : 'activate' }} this code?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="{{ $code->is_active ? 'text-error-600 hover:text-error-700' : 'text-success-600 hover:text-success-700' }}"
                                            title="{{ $code->is_active ? 'Deactivate' : 'Activate' }}">
                                        @if($code->is_active)
                                            <x-lucide-ban class="w-4 h-4" />
                                        @else
                                            <x-lucide-check-circle class="w-4 h-4" />
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center">
                            <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-lucide-qr-code class="w-8 h-8 text-neutral-400" />
                            </div>
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
            <x-ui.pagination :paginator="$codes" />
        </div>
        @endif
    </x-ui.card>

    <!-- Performance Insights -->
    @if($codes->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Top Performing Codes -->
        <x-ui.card>
            <div class="p-6">
                <div class="flex items-center gap-2 mb-4">
                    <x-lucide-trophy class="w-5 h-5 text-success-600" />
                    <h3 class="text-lg font-semibold text-neutral-800">Top Performing Codes</h3>
                </div>
                <div class="space-y-3">
                    @foreach($codes->sortByDesc('successful_referrals')->take(5) as $topCode)
                    <div class="flex items-center justify-between p-3 bg-success-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <code class="px-2 py-1 text-primary-700 rounded text-sm font-mono">{{ $topCode->code }}</code>
                            <span class="text-sm text-neutral-700">{{ $topCode->user->fullName }}</span>
                        </div>
                        <span class="text-lg font-semibold text-success-700">{{ $topCode->successful_referrals }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </x-ui.card>

        <!-- Recently Used Codes -->
        <x-ui.card>
            <div class="p-6">
                <div class="flex items-center gap-2 mb-4">
                    <x-lucide-clock class="w-5 h-5 text-info-600" />
                    <h3 class="text-lg font-semibold text-neutral-800">Recently Used Codes</h3>
                </div>
                <div class="space-y-3">
                    @foreach($codes->whereNotNull('last_used_at')->sortByDesc('last_used_at')->take(5) as $recentCode)
                    <div class="flex items-center justify-between p-3 bg-info-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <code class="px-2 py-1 text-primary-700 rounded text-sm font-mono">{{ $recentCode->code }}</code>
                            <span class="text-sm text-neutral-700">{{ $recentCode->user->fullName }}</span>
                        </div>
                        <span class="text-sm text-neutral-600">{{ $recentCode->last_used_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </x-ui.card>
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

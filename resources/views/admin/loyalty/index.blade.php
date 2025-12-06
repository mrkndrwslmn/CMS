@extends('admin.layouts.app')

@section('title', 'Loyalty Program')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Loyalty Program', 'icon' => 'award'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Loyalty Program" 
            description="Manage customer loyalty points and tiers"
        />
        <div class="flex flex-wrap gap-3">
            <x-ui.button href="{{ route('admin.loyalty.leaderboard') }}" variant="secondary">
                <x-lucide-trophy class="w-4 h-4" />
                Leaderboard
            </x-ui.button>
            <x-ui.button href="{{ route('admin.loyalty.settings') }}" variant="secondary">
                <x-lucide-settings class="w-4 h-4" />
                Settings
            </x-ui.button>
            <x-ui.button href="{{ route('admin.loyalty.export') }}" variant="primary">
                <x-lucide-download class="w-4 h-4" />
                Export Report
            </x-ui.button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Members</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_members']) }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-users class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Points in Circulation</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_points_circulation']) }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-coins class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Avg Points/User</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['average_points_per_user'], 0) }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-trending-up class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">This Month</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['transactions_this_month']) }}</p>
                    <p class="text-xs text-neutral-400 mt-1">Transactions</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-history class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Tier Distribution -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4">Tier Distribution</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach(['bronze', 'silver', 'gold', 'platinum'] as $tier)
                <div class="text-center p-4 rounded-xl border-2 border-{{ $tier === 'bronze' ? 'warning' : ($tier === 'silver' ? 'neutral' : ($tier === 'gold' ? 'warning' : 'info')) }}-200 bg-{{ $tier === 'bronze' ? 'warning' : ($tier === 'silver' ? 'neutral' : ($tier === 'gold' ? 'warning' : 'info')) }}-50">
                    <x-lucide-award class="w-8 h-8 mx-auto mb-2 text-{{ $tier === 'bronze' ? 'warning' : ($tier === 'silver' ? 'neutral' : ($tier === 'gold' ? 'warning' : 'info')) }}-600" />
                    <p class="text-sm font-semibold text-neutral-700 uppercase">{{ $tier }}</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['tier_distribution'][$tier] ?? 0 }}</p>
                    <p class="text-xs text-neutral-500 mt-1">Members</p>
                </div>
                @endforeach
            </div>
        </div>
    </x-ui.card>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.loyalty.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <x-ui.input 
                        label="Search User"
                        name="search" 
                        :value="request('search')" 
                        placeholder="Name or email..."
                    />
                </div>
                <div>
                    <x-ui.select label="Tier" name="tier">
                        <option value="">All Tiers</option>
                        <option value="bronze" {{ request('tier') === 'bronze' ? 'selected' : '' }}>Bronze</option>
                        <option value="silver" {{ request('tier') === 'silver' ? 'selected' : '' }}>Silver</option>
                        <option value="gold" {{ request('tier') === 'gold' ? 'selected' : '' }}>Gold</option>
                        <option value="platinum" {{ request('tier') === 'platinum' ? 'selected' : '' }}>Platinum</option>
                    </x-ui.select>
                </div>
                <div>
                    <x-ui.select label="Sort By" name="sort_by">
                        <option value="lifetime_earned" {{ request('sort_by') === 'lifetime_earned' ? 'selected' : '' }}>Lifetime Earned</option>
                        <option value="available_points" {{ request('sort_by') === 'available_points' ? 'selected' : '' }}>Available Points</option>
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Join Date</option>
                    </x-ui.select>
                </div>
                <div class="flex items-end gap-2">
                    <x-ui.button type="submit" variant="primary" class="flex-1">
                        <x-lucide-search class="w-4 h-4" />
                        Filter
                    </x-ui.button>
                    <x-ui.button href="{{ route('admin.loyalty.index') }}" variant="secondary">
                        <x-lucide-refresh-cw class="w-4 h-4" />
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Members Table -->
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Tier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Available Points</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Lifetime Earned</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Lifetime Redeemed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Member Since</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($loyaltyPoints as $lp)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    @if($lp->user->profilePic)
                                        <img src="{{ $lp->user->getProfilePictureUrl() }}" 
                                             alt="{{ $lp->user->fullName }}" 
                                             class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                            <span class="text-primary-600 font-semibold text-sm">
                                                {{ substr($lp->user->fullName ?? 'U', 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-900">
                                        {{ $lp->user->fullName }}
                                    </div>
                                    <div class="text-sm text-neutral-500">{{ $lp->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex items-center gap-1 text-xs leading-5 font-semibold rounded-full
                                {{ $lp->tier === 'platinum' ? 'bg-info-100 text-info-800' : '' }}
                                {{ $lp->tier === 'gold' ? 'bg-warning-100 text-warning-800' : '' }}
                                {{ $lp->tier === 'silver' ? 'bg-neutral-200 text-neutral-700' : '' }}
                                {{ $lp->tier === 'bronze' ? 'bg-orange-100 text-orange-800' : '' }}">
                                <x-lucide-award class="w-3 h-3" />
                                {{ ucfirst($lp->tier) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-success-600">{{ number_format($lp->available_points) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                            {{ number_format($lp->lifetime_earned) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                            {{ number_format($lp->lifetime_redeemed) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $lp->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-ui.button href="{{ route('admin.loyalty.show', $lp->user) }}" size="sm" variant="primary">
                                View Details
                            </x-ui.button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <x-lucide-users class="w-16 h-16 text-neutral-300 mb-4" />
                                <p class="text-neutral-500 text-lg">No loyalty members found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($loyaltyPoints->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">
            <x-ui.pagination :paginator="$loyaltyPoints" />
        </div>
        @endif
    </x-ui.card>
</div>
@endsection

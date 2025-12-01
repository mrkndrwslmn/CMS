@extends('admin.layouts.app')

@section('title', 'Loyalty Program')

@section('content')
<div class="max-w-8xl mx-auto container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-neutral-800 mb-2">Loyalty Program</h1>
            <p class="text-neutral-600">Manage customer loyalty points and tiers</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.loyalty.leaderboard') }}" class="btn btn-secondary">
                <i class="fas fa-trophy mr-2"></i>Leaderboard
            </a>
            <a href="{{ route('admin.loyalty.settings') }}" class="btn btn-secondary">
                <i class="fas fa-cog mr-2"></i>Settings
            </a>
            <a href="{{ route('admin.loyalty.export') }}" class="btn btn-primary">
                <i class="fas fa-download mr-2"></i>Export Report
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card p-6 bg-gradient-to-br from-primary-50 to-primary-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-primary-600 mb-1">Total Members</p>
                    <p class="text-3xl font-bold text-primary-700">{{ number_format($stats['total_members']) }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 bg-gradient-to-br from-success-50 to-success-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-success-600 mb-1">Points in Circulation</p>
                    <p class="text-3xl font-bold text-success-700">{{ number_format($stats['total_points_circulation']) }}</p>
                </div>
                <div class="w-12 h-12 bg-success-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-coins text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 bg-gradient-to-br from-warning-50 to-warning-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-warning-600 mb-1">Avg Points/User</p>
                    <p class="text-3xl font-bold text-warning-700">{{ number_format($stats['average_points_per_user'], 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-warning-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 bg-gradient-to-br from-info-50 to-info-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-info-600 mb-1">This Month</p>
                    <p class="text-3xl font-bold text-info-700">{{ number_format($stats['transactions_this_month']) }}</p>
                    <p class="text-xs text-info-600 mt-1">Transactions</p>
                </div>
                <div class="w-12 h-12 bg-info-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-history text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tier Distribution -->
    <div class="glass-card p-6 mb-6">
        <h3 class="text-lg font-bold text-neutral-800 mb-4">Tier Distribution</h3>
        <div class="grid grid-cols-4 gap-4">
            @foreach(['bronze', 'silver', 'gold', 'platinum'] as $tier)
            <div class="text-center p-4 rounded-lg border-2 border-{{ $tier === 'bronze' ? 'warning' : ($tier === 'silver' ? 'neutral' : ($tier === 'gold' ? 'warning' : 'info')) }}-200 bg-{{ $tier === 'bronze' ? 'warning' : ($tier === 'silver' ? 'neutral' : ($tier === 'gold' ? 'warning' : 'info')) }}-50">
                <i class="fas fa-medal text-3xl text-{{ $tier === 'bronze' ? 'warning' : ($tier === 'silver' ? 'neutral' : ($tier === 'gold' ? 'warning' : 'info')) }}-600 mb-2"></i>
                <p class="text-sm font-semibold text-neutral-700 uppercase">{{ $tier }}</p>
                <p class="text-2xl font-bold text-neutral-900">{{ $stats['tier_distribution'][$tier] ?? 0 }}</p>
                <p class="text-xs text-neutral-500 mt-1">Members</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card p-6 mb-6">
        <form method="GET" action="{{ route('admin.loyalty.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Search User</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Name or email..." class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Tier</label>
                <select name="tier" class="form-select w-full">
                    <option value="">All Tiers</option>
                    <option value="bronze" {{ request('tier') === 'bronze' ? 'selected' : '' }}>Bronze</option>
                    <option value="silver" {{ request('tier') === 'silver' ? 'selected' : '' }}>Silver</option>
                    <option value="gold" {{ request('tier') === 'gold' ? 'selected' : '' }}>Gold</option>
                    <option value="platinum" {{ request('tier') === 'platinum' ? 'selected' : '' }}>Platinum</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Sort By</label>
                <select name="sort_by" class="form-select w-full">
                    <option value="lifetime_earned" {{ request('sort_by') === 'lifetime_earned' ? 'selected' : '' }}>Lifetime Earned</option>
                    <option value="available_points" {{ request('sort_by') === 'available_points' ? 'selected' : '' }}>Available Points</option>
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Join Date</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.loyalty.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Members Table -->
    <div class="glass-card overflow-hidden">
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
                                    <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-primary-600 font-semibold text-sm">
                                            {{ substr($lp->user->first_name, 0, 1) }}{{ substr($lp->user->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-900">
                                        {{ $lp->user->first_name }} {{ $lp->user->last_name }}
                                    </div>
                                    <div class="text-sm text-neutral-500">{{ $lp->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $lp->tier === 'platinum' ? 'bg-info-100 text-info-800' : '' }}
                                {{ $lp->tier === 'gold' ? 'bg-warning-100 text-warning-800' : '' }}
                                {{ $lp->tier === 'silver' ? 'bg-neutral-200 text-neutral-700' : '' }}
                                {{ $lp->tier === 'bronze' ? 'bg-orange-100 text-orange-800' : '' }}">
                                <i class="fas fa-medal mr-1"></i>{{ ucfirst($lp->tier) }}
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
                            <a href="{{ route('admin.loyalty.show', $lp->user) }}" class="btn btn-sm btn-primary">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-users text-neutral-300 text-6xl mb-4"></i>
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
            {{ $loyaltyPoints->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

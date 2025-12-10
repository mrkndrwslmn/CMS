@extends('client.layouts.app')

@section('title', 'Loyalty Rewards')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Loyalty Rewards', 'icon' => 'award'],
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Loyalty Rewards</h1>
        <p class="text-sm text-neutral-500 mt-1">Track your points, tier status, and rewards</p>
    </div>

    <!-- Points Overview Card -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Available Points -->
            <x-loyalty.points-display 
                :points="$loyaltyPoint->available_points" 
                label="Available Points"
                size="lg"
                :showCurrency="true"
            />

            <!-- Current Tier -->
            <div class="text-center border-x border-neutral-100">
                <p class="text-sm font-medium text-neutral-500 mb-3">Current Tier</p>
                <x-loyalty.tier-badge :tier="$loyaltyPoint->tier" size="xl" />
                <p class="text-sm text-neutral-400 mt-3">{{ $stats['earning_rate'] }} earning rate</p>
            </div>

            <!-- Lifetime Stats -->
            <x-loyalty.points-display 
                :points="$loyaltyPoint->lifetime_earned" 
                label="Lifetime Earned"
                type="earned"
                size="lg"
            >
                {{ number_format($loyaltyPoint->lifetime_redeemed) }} redeemed
            </x-loyalty.points-display>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tier Progress -->
            @if($stats['next_tier'])
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <h3 class="text-lg font-medium text-neutral-700 mb-4 flex items-center gap-2">
                    <x-lucide-trending-up class="w-5 h-5 text-success-500" />
                    Progress to {{ ucfirst($stats['next_tier']) }}
                </h3>
                
                <x-loyalty.tier-progress 
                    :current="$loyaltyPoint->lifetime_earned"
                    :target="$stats['points_to_next_tier'] + $loyaltyPoint->lifetime_earned"
                    :currentTier="$loyaltyPoint->tier"
                    :nextTier="$stats['next_tier']"
                    class="mb-4"
                />

                <div class="flex items-start gap-2 p-3 bg-primary-50 rounded-xl border border-primary-100">
                    <x-lucide-info class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" />
                    <p class="text-sm text-primary-700">
                        Earn <span class="font-medium">{{ number_format($stats['points_to_next_tier']) }}</span> more points to unlock {{ ucfirst($stats['next_tier']) }} tier!
                    </p>
                </div>
            </div>
            @endif

            <!-- Current Benefits -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <h3 class="text-lg font-medium text-neutral-700 mb-4 flex items-center gap-2">
                    <x-lucide-gift class="w-5 h-5 text-primary-500" />
                    Your {{ ucfirst($loyaltyPoint->tier) }} Benefits
                </h3>

                <div class="space-y-3">
                    @foreach($currentTierBenefits['benefits'] as $benefit)
                    <div class="flex items-start gap-3 p-3 bg-success-50 rounded-xl border border-success-100">
                        <x-lucide-check-circle class="w-4 h-4 text-success-500 mt-0.5 flex-shrink-0" />
                        <span class="text-sm text-neutral-700">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Next Tier Benefits -->
            @if($nextTierBenefits)
            <div class="bg-white rounded-2xl border border-primary-200 shadow-sm p-6">
                <h3 class="text-lg font-medium text-neutral-700 mb-4 flex items-center gap-2">
                    <x-lucide-star class="w-5 h-5 text-warning-500" />
                    Unlock {{ ucfirst($stats['next_tier']) }} Benefits
                </h3>

                <div class="space-y-3">
                    @foreach($nextTierBenefits['benefits'] as $benefit)
                    <div class="flex items-start gap-3 p-3 bg-neutral-50 rounded-xl border border-neutral-100">
                        <x-lucide-lock class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                        <span class="text-sm text-neutral-500">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Transactions -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-2">
                        <x-lucide-clock class="w-5 h-5 text-neutral-400" />
                        Recent Activity
                    </h3>
                    <a href="{{ route('client.loyalty.transactions') }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                        View All
                        <x-lucide-arrow-right class="w-3 h-3" />
                    </a>
                </div>

                @if($recentTransactions->count() > 0)
                <div class="space-y-3">
                    @foreach($recentTransactions as $transaction)
                        <x-loyalty.transaction-row :transaction="$transaction" compact />
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-14 h-14 bg-neutral-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <x-lucide-clock class="w-7 h-7 text-neutral-400" />
                    </div>
                    <p class="text-sm text-neutral-500">No transactions yet</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Points Expiring Soon -->
            <x-loyalty.expiring-points 
                :transactions="$expiringPoints" 
                :totalExpiring="$stats['expiring_soon']" 
            />

            <!-- All Tiers -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <h3 class="text-lg font-medium text-neutral-700 mb-4 flex items-center gap-2">
                    <x-lucide-layers class="w-5 h-5 text-neutral-400" />
                    Tier System
                </h3>

                <div class="space-y-3">
                    @foreach($allTiers as $tierName => $tierData)
                    <div class="p-4 rounded-xl border 
                        {{ $loyaltyPoint->tier === $tierName ? 'border-primary-300 bg-primary-50' : 'border-neutral-100 bg-white' }}">
                        <div class="flex items-center justify-between mb-2">
                            <x-loyalty.tier-badge :tier="$tierName" size="sm" />
                            @if($loyaltyPoint->tier === $tierName)
                            <span class="px-2 py-0.5 bg-primary-600 text-white text-xs rounded-full font-medium">Current</span>
                            @endif
                        </div>
                        <p class="text-xs text-neutral-500 mb-1">
                            {{ $tierData['points'] === 0 ? 'Starting tier' : number_format($tierData['points']) . '+ points' }}
                        </p>
                        <p class="text-xs text-primary-600 font-medium">{{ $tierData['discount'] }}% automatic discount</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- How to Earn -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <h3 class="text-lg font-medium text-neutral-700 mb-4 flex items-center gap-2">
                    <x-lucide-lightbulb class="w-5 h-5 text-warning-500" />
                    How to Earn Points
                </h3>

                <div class="space-y-3">
                    <div class="flex items-start gap-3 p-3 bg-success-50 rounded-xl border border-success-100">
                        <x-lucide-coins class="w-4 h-4 text-success-500 mt-0.5 flex-shrink-0" />
                        <div>
                            <p class="font-medium text-neutral-700 text-sm">Complete Payments</p>
                            <p class="text-neutral-500 text-xs mt-0.5">Earn {{ $stats['earning_rate'] }} on every ₱100 spent</p>
                        </div>
                    </div>
                    <!-- TODO: MILESTONE POINTS IMPLEMENTATION
                    <div class="flex items-start gap-3 p-3 bg-primary-50 rounded-xl border border-primary-100">
                        <x-lucide-list-checks class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" />
                        <div>
                            <p class="font-medium text-neutral-700 text-sm">Project Milestones</p>
                            <p class="text-neutral-500 text-xs mt-0.5">Earn 200 points per milestone completed</p>
                        </div>
                    </div> -->
                    <div class="flex items-start gap-3 p-3 bg-success-50 rounded-xl border border-success-100">
                        <x-lucide-check-circle class="w-4 h-4 text-success-500 mt-0.5 flex-shrink-0" />
                        <div>
                            <p class="font-medium text-neutral-700 text-sm">Project Completion</p>
                            <p class="text-neutral-500 text-xs mt-0.5">Earn 500 bonus points</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-warning-50 rounded-xl border border-warning-100">
                        <x-lucide-star class="w-4 h-4 text-warning-500 mt-0.5 flex-shrink-0" />
                        <div>
                            <p class="font-medium text-neutral-700 text-sm">Submit Feedback</p>
                            <p class="text-neutral-500 text-xs mt-0.5">Earn 100 points for reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

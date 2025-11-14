@extends('client.layouts.app')

@section('title', 'Loyalty Rewards')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-800 mb-2">Loyalty Rewards</h1>
        <p class="text-neutral-600">Track your points, tier status, and rewards</p>
    </div>

    <!-- Points Overview Card -->
    <div class="glass-card p-8 mb-8 bg-gradient-to-br from-primary-50 via-white to-success-50 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500 opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-success-500 opacity-5 rounded-full -ml-24 -mb-24"></div>
        
        <div class="relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Available Points -->
                <div class="text-center">
                    <p class="text-sm text-neutral-600 mb-2">Available Points</p>
                    <p class="text-5xl font-bold text-primary-600 mb-2">{{ number_format($loyaltyPoint->available_points) }}</p>
                    <p class="text-sm text-neutral-500">≈ ₱{{ number_format($loyaltyPoint->available_points) }} discount</p>
                </div>

                <!-- Current Tier -->
                <div class="text-center border-x border-neutral-200">
                    <p class="text-sm text-neutral-600 mb-2">Current Tier</p>
                    <div class="inline-flex items-center justify-center px-6 py-3 rounded-full text-2xl font-bold
                        {{ $loyaltyPoint->tier === 'platinum' ? 'bg-info-100 text-info-700' : '' }}
                        {{ $loyaltyPoint->tier === 'gold' ? 'bg-warning-100 text-warning-700' : '' }}
                        {{ $loyaltyPoint->tier === 'silver' ? 'bg-neutral-200 text-neutral-700' : '' }}
                        {{ $loyaltyPoint->tier === 'bronze' ? 'bg-orange-100 text-orange-700' : '' }}">
                        <i class="fas fa-medal mr-2"></i>{{ ucfirst($loyaltyPoint->tier) }}
                    </div>
                    <p class="text-sm text-neutral-500 mt-2">{{ $stats['earning_rate'] }} earning rate</p>
                </div>

                <!-- Lifetime Stats -->
                <div class="text-center">
                    <p class="text-sm text-neutral-600 mb-2">Lifetime Earned</p>
                    <p class="text-5xl font-bold text-success-600 mb-2">{{ number_format($loyaltyPoint->lifetime_earned) }}</p>
                    <p class="text-sm text-neutral-500">{{ number_format($loyaltyPoint->lifetime_redeemed) }} redeemed</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Tier Progress -->
            @if($stats['next_tier'])
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-4">
                    <i class="fas fa-arrow-up text-success-600 mr-2"></i>Progress to {{ ucfirst($stats['next_tier']) }}
                </h3>
                
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-neutral-600 mb-2">
                        <span>{{ number_format($loyaltyPoint->lifetime_earned) }} points</span>
                        <span>{{ number_format($stats['points_to_next_tier'] + $loyaltyPoint->lifetime_earned) }} points needed</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-primary-500 to-success-500 h-3 rounded-full transition-all" 
                             style="width: {{ min(100, ($loyaltyPoint->lifetime_earned / ($stats['points_to_next_tier'] + $loyaltyPoint->lifetime_earned)) * 100) }}%">
                        </div>
                    </div>
                </div>

                <p class="text-sm text-neutral-600">
                    <i class="fas fa-info-circle text-info-500 mr-2"></i>
                    Earn <span class="font-bold text-primary-600">{{ number_format($stats['points_to_next_tier']) }}</span> more points to unlock {{ ucfirst($stats['next_tier']) }} tier!
                </p>
            </div>
            @endif

            <!-- Current Benefits -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-4">
                    <i class="fas fa-gift text-primary-600 mr-2"></i>Your {{ ucfirst($loyaltyPoint->tier) }} Benefits
                </h3>

                <div class="space-y-3">
                    @foreach($currentTierBenefits['benefits'] as $benefit)
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-success-500 mt-1 mr-3"></i>
                        <span class="text-neutral-700">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Next Tier Benefits -->
            @if($nextTierBenefits)
            <div class="glass-card p-6 border-2 border-primary-200">
                <h3 class="text-lg font-bold text-neutral-800 mb-4">
                    <i class="fas fa-star text-warning-500 mr-2"></i>Unlock {{ ucfirst($stats['next_tier']) }} Benefits
                </h3>

                <div class="space-y-3">
                    @foreach($nextTierBenefits['benefits'] as $benefit)
                    <div class="flex items-start">
                        <i class="fas fa-lock text-neutral-400 mt-1 mr-3"></i>
                        <span class="text-neutral-600">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Transactions -->
            <div class="glass-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-neutral-800">
                        <i class="fas fa-history text-info-600 mr-2"></i>Recent Activity
                    </h3>
                    <a href="{{ route('client.loyalty.transactions') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($recentTransactions->count() > 0)
                <div class="space-y-4">
                    @foreach($recentTransactions as $transaction)
                    <div class="flex items-center justify-between py-3 border-b border-neutral-100 last:border-0">
                        <div class="flex items-center flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4
                                {{ $transaction->transaction_type === 'earned' ? 'bg-success-100' : '' }}
                                {{ $transaction->transaction_type === 'redeemed' ? 'bg-warning-100' : '' }}
                                {{ $transaction->transaction_type === 'expired' ? 'bg-error-100' : '' }}
                                {{ $transaction->transaction_type === 'adjusted' ? 'bg-info-100' : '' }}">
                                <i class="fas fa-{{ $transaction->transaction_type === 'earned' ? 'plus' : ($transaction->transaction_type === 'redeemed' ? 'minus' : 'clock') }}
                                    {{ $transaction->transaction_type === 'earned' ? 'text-success-600' : '' }}
                                    {{ $transaction->transaction_type === 'redeemed' ? 'text-warning-600' : '' }}
                                    {{ $transaction->transaction_type === 'expired' ? 'text-error-600' : '' }}
                                    {{ $transaction->transaction_type === 'adjusted' ? 'text-info-600' : '' }}">
                                </i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-neutral-800">{{ $transaction->description }}</p>
                                <p class="text-xs text-neutral-500">{{ $transaction->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold
                                {{ $transaction->points > 0 ? 'text-success-600' : 'text-error-600' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-neutral-500 py-8">No transactions yet</p>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Points Expiring Soon -->
            @if($expiringPoints->count() > 0)
            <div class="glass-card p-6 border-2 border-warning-200 bg-warning-50">
                <h3 class="text-lg font-bold text-warning-800 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Points Expiring Soon
                </h3>

                <div class="space-y-3">
                    @foreach($expiringPoints->take(3) as $expiring)
                    <div class="bg-white rounded-lg p-3 border border-warning-200">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-lg font-bold text-warning-700">{{ number_format($expiring->points) }}</span>
                            <span class="text-xs text-warning-600">{{ $expiring->expires_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-xs text-neutral-600">{{ $expiring->description }}</p>
                    </div>
                    @endforeach
                </div>

                <p class="text-xs text-warning-700 mt-4">
                    <i class="fas fa-info-circle mr-1"></i>
                    Total {{ number_format($stats['expiring_soon']) }} points expiring in 30 days
                </p>
            </div>
            @endif

            <!-- All Tiers -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-4">
                    <i class="fas fa-layer-group text-primary-600 mr-2"></i>Tier System
                </h3>

                <div class="space-y-3">
                    @foreach($allTiers as $tierName => $tierData)
                    <div class="p-4 rounded-lg border-2 
                        {{ $loyaltyPoint->tier === $tierName ? 'border-primary-400 bg-primary-50' : 'border-neutral-200' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <i class="fas fa-medal text-2xl mr-3
                                    {{ $tierName === 'platinum' ? 'text-info-600' : '' }}
                                    {{ $tierName === 'gold' ? 'text-warning-500' : '' }}
                                    {{ $tierName === 'silver' ? 'text-neutral-400' : '' }}
                                    {{ $tierName === 'bronze' ? 'text-orange-600' : '' }}">
                                </i>
                                <span class="font-bold text-neutral-800 uppercase">{{ $tierName }}</span>
                            </div>
                            @if($loyaltyPoint->tier === $tierName)
                            <span class="px-2 py-1 bg-primary-600 text-white text-xs rounded-full">Current</span>
                            @endif
                        </div>
                        <p class="text-xs text-neutral-600">
                            {{ $tierData['points'] === 0 ? 'Starting tier' : number_format($tierData['points']) . '+ points' }}
                        </p>
                        <p class="text-xs text-primary-600 mt-1">{{ $tierData['discount'] }}% automatic discount</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- How to Earn -->
            <div class="glass-card p-6 bg-gradient-to-br from-success-50 to-white">
                <h3 class="text-lg font-bold text-neutral-800 mb-4">
                    <i class="fas fa-lightbulb text-warning-500 mr-2"></i>How to Earn Points
                </h3>

                <div class="space-y-3 text-sm">
                    <div class="flex items-start">
                        <i class="fas fa-coins text-success-500 mt-1 mr-3"></i>
                        <div>
                            <p class="font-semibold text-neutral-800">Complete Payments</p>
                            <p class="text-neutral-600 text-xs">Earn {{ $stats['earning_rate'] }} on every ₱100 spent</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-tasks text-primary-500 mt-1 mr-3"></i>
                        <div>
                            <p class="font-semibold text-neutral-800">Project Milestones</p>
                            <p class="text-neutral-600 text-xs">Earn 200 points per milestone completed</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-success-500 mt-1 mr-3"></i>
                        <div>
                            <p class="font-semibold text-neutral-800">Project Completion</p>
                            <p class="text-neutral-600 text-xs">Earn 500 bonus points</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-star text-warning-500 mt-1 mr-3"></i>
                        <div>
                            <p class="font-semibold text-neutral-800">Submit Feedback</p>
                            <p class="text-neutral-600 text-xs">Earn 100 points for reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

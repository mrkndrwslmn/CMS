@extends('client.layouts.app')

@section('title', 'Loyalty Rewards')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-primary-700">Loyalty Rewards</h1>
        <p class="text-neutral-600 mt-2">Track your points, tier status, and rewards</p>
    </div>

    <!-- Points Overview Card -->
    <div class="bg-white rounded-xl border border-neutral-200 p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Available Points -->
            <div class="text-center">
                <p class="text-sm font-semibold text-neutral-600 mb-3">Available Points</p>
                <p class="text-5xl font-bold text-primary-700 mb-2">{{ number_format($loyaltyPoint->available_points) }}</p>
                <p class="text-sm text-neutral-500">≈ ₱{{ number_format($loyaltyPoint->available_points) }} discount</p>
            </div>

            <!-- Current Tier -->
            <div class="text-center border-x border-neutral-200">
                <p class="text-sm font-semibold text-neutral-600 mb-3">Current Tier</p>
                <div class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-2xl font-bold border-2
                    {{ $loyaltyPoint->tier === 'platinum' ? 'bg-info-50 text-info-700 border-info-200' : '' }}
                    {{ $loyaltyPoint->tier === 'gold' ? 'bg-warning-50 text-warning-700 border-warning-200' : '' }}
                    {{ $loyaltyPoint->tier === 'silver' ? 'bg-neutral-100 text-neutral-700 border-neutral-300' : '' }}
                    {{ $loyaltyPoint->tier === 'bronze' ? 'bg-orange-50 text-orange-700 border-orange-200' : '' }}">
                    <i class="fas fa-medal mr-2"></i>{{ ucfirst($loyaltyPoint->tier) }}
                </div>
                <p class="text-sm text-neutral-500 mt-3">{{ $stats['earning_rate'] }} earning rate</p>
            </div>

            <!-- Lifetime Stats -->
            <div class="text-center">
                <p class="text-sm font-semibold text-neutral-600 mb-3">Lifetime Earned</p>
                <p class="text-5xl font-bold text-success-600 mb-2">{{ number_format($loyaltyPoint->lifetime_earned) }}</p>
                <p class="text-sm text-neutral-500">{{ number_format($loyaltyPoint->lifetime_redeemed) }} redeemed</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tier Progress -->
            @if($stats['next_tier'])
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center">
                    <i class="fas fa-arrow-up text-success-600 mr-2"></i>
                    Progress to {{ ucfirst($stats['next_tier']) }}
                </h3>
                
                <div class="mb-4">
                    <div class="flex justify-between text-sm font-medium text-neutral-600 mb-2">
                        <span>{{ number_format($loyaltyPoint->lifetime_earned) }} points</span>
                        <span>{{ number_format($stats['points_to_next_tier'] + $loyaltyPoint->lifetime_earned) }} needed</span>
                    </div>
                    <div class="w-full bg-neutral-100 rounded-full h-3 border border-neutral-200">
                        <div class="bg-primary-600 h-3 rounded-full transition-all" 
                             style="width: {{ min(100, ($loyaltyPoint->lifetime_earned / ($stats['points_to_next_tier'] + $loyaltyPoint->lifetime_earned)) * 100) }}%">
                        </div>
                    </div>
                </div>

                <div class="flex items-start gap-2 p-3 bg-primary-50 rounded-lg border border-primary-100">
                    <i class="fas fa-info-circle text-primary-600 mt-0.5"></i>
                    <p class="text-sm text-primary-700">
                        Earn <span class="font-bold">{{ number_format($stats['points_to_next_tier']) }}</span> more points to unlock {{ ucfirst($stats['next_tier']) }} tier!
                    </p>
                </div>
            </div>
            @endif

            <!-- Current Benefits -->
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center">
                    <i class="fas fa-gift text-primary-600 mr-2"></i>
                    Your {{ ucfirst($loyaltyPoint->tier) }} Benefits
                </h3>

                <div class="space-y-3">
                    @foreach($currentTierBenefits['benefits'] as $benefit)
                    <div class="flex items-start gap-3 p-3 bg-success-50 rounded-lg border border-success-100">
                        <i class="fas fa-check-circle text-success-600 mt-0.5 flex-shrink-0"></i>
                        <span class="text-sm text-primary-700 font-medium">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Next Tier Benefits -->
            @if($nextTierBenefits)
            <div class="bg-white rounded-xl border-2 border-primary-300 p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center">
                    <i class="fas fa-star text-warning-500 mr-2"></i>
                    Unlock {{ ucfirst($stats['next_tier']) }} Benefits
                </h3>

                <div class="space-y-3">
                    @foreach($nextTierBenefits['benefits'] as $benefit)
                    <div class="flex items-start gap-3 p-3 bg-neutral-50 rounded-lg border border-neutral-200">
                        <i class="fas fa-lock text-neutral-400 mt-0.5 flex-shrink-0"></i>
                        <span class="text-sm text-neutral-600 font-medium">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Transactions -->
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-primary-700 flex items-center">
                        <i class="fas fa-history text-primary-600 mr-2"></i>
                        Recent Activity
                    </h3>
                    <a href="{{ route('client.loyalty.transactions') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold transition-colors">
                        View All →
                    </a>
                </div>

                @if($recentTransactions->count() > 0)
                <div class="space-y-3">
                    @foreach($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-lg border border-neutral-200 hover:border-primary-300 transition-all">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                                {{ $transaction->transaction_type === 'earned' ? 'bg-success-100 border border-success-200' : '' }}
                                {{ $transaction->transaction_type === 'redeemed' ? 'bg-warning-100 border border-warning-200' : '' }}
                                {{ $transaction->transaction_type === 'expired' ? 'bg-error-100 border border-error-200' : '' }}
                                {{ $transaction->transaction_type === 'adjusted' ? 'bg-info-100 border border-info-200' : '' }}">
                                <i class="fas fa-{{ $transaction->transaction_type === 'earned' ? 'plus' : ($transaction->transaction_type === 'redeemed' ? 'minus' : 'clock') }}
                                    {{ $transaction->transaction_type === 'earned' ? 'text-success-600' : '' }}
                                    {{ $transaction->transaction_type === 'redeemed' ? 'text-warning-600' : '' }}
                                    {{ $transaction->transaction_type === 'expired' ? 'text-error-600' : '' }}
                                    {{ $transaction->transaction_type === 'adjusted' ? 'text-info-600' : '' }}">
                                </i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-primary-700 truncate">{{ $transaction->description }}</p>
                                <p class="text-xs text-neutral-500">{{ $transaction->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-lg font-bold
                                {{ $transaction->points > 0 ? 'text-success-600' : 'text-error-600' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-neutral-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-2xl text-neutral-400"></i>
                    </div>
                    <p class="text-neutral-500 font-medium">No transactions yet</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Points Expiring Soon -->
            @if($expiringPoints->count() > 0)
            <div class="bg-white rounded-xl border-2 border-warning-300 p-6">
                <h3 class="text-lg font-bold text-warning-700 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Points Expiring Soon
                </h3>

                <div class="space-y-3">
                    @foreach($expiringPoints->take(3) as $expiring)
                    <div class="bg-warning-50 rounded-lg p-3 border border-warning-200">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-bold text-warning-700">{{ number_format($expiring->points) }}</span>
                            <span class="text-xs font-semibold text-warning-600 bg-warning-100 px-2 py-1 rounded">{{ $expiring->expires_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-xs text-neutral-600">{{ $expiring->description }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="flex items-start gap-2 mt-4 p-3 bg-warning-50 rounded-lg border border-warning-100">
                    <i class="fas fa-info-circle text-warning-600 mt-0.5"></i>
                    <p class="text-xs text-warning-700 font-medium">
                        Total {{ number_format($stats['expiring_soon']) }} points expiring in 30 days
                    </p>
                </div>
            </div>
            @endif

            <!-- All Tiers -->
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center">
                    <i class="fas fa-layer-group text-primary-600 mr-2"></i>
                    Tier System
                </h3>

                <div class="space-y-3">
                    @foreach($allTiers as $tierName => $tierData)
                    <div class="p-4 rounded-lg border-2 
                        {{ $loyaltyPoint->tier === $tierName ? 'border-primary-400 bg-primary-50' : 'border-neutral-200 bg-white' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-medal text-xl
                                    {{ $tierName === 'platinum' ? 'text-info-600' : '' }}
                                    {{ $tierName === 'gold' ? 'text-warning-500' : '' }}
                                    {{ $tierName === 'silver' ? 'text-neutral-400' : '' }}
                                    {{ $tierName === 'bronze' ? 'text-orange-600' : '' }}">
                                </i>
                                <span class="font-bold text-primary-700 uppercase text-sm">{{ $tierName }}</span>
                            </div>
                            @if($loyaltyPoint->tier === $tierName)
                            <span class="px-2.5 py-1 bg-primary-600 text-white text-xs rounded-lg font-bold">Current</span>
                            @endif
                        </div>
                        <p class="text-xs text-neutral-600 mb-1">
                            {{ $tierData['points'] === 0 ? 'Starting tier' : number_format($tierData['points']) . '+ points' }}
                        </p>
                        <p class="text-xs text-primary-600 font-semibold">{{ $tierData['discount'] }}% automatic discount</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- How to Earn -->
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center">
                    <i class="fas fa-lightbulb text-warning-500 mr-2"></i>
                    How to Earn Points
                </h3>

                <div class="space-y-3">
                    <div class="flex items-start gap-3 p-3 bg-success-50 rounded-lg border border-success-100">
                        <i class="fas fa-coins text-success-600 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-semibold text-primary-700 text-sm">Complete Payments</p>
                            <p class="text-neutral-600 text-xs mt-0.5">Earn {{ $stats['earning_rate'] }} on every ₱100 spent</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-primary-50 rounded-lg border border-primary-100">
                        <i class="fas fa-tasks text-primary-600 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-semibold text-primary-700 text-sm">Project Milestones</p>
                            <p class="text-neutral-600 text-xs mt-0.5">Earn 200 points per milestone completed</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-success-50 rounded-lg border border-success-100">
                        <i class="fas fa-check-circle text-success-600 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-semibold text-primary-700 text-sm">Project Completion</p>
                            <p class="text-neutral-600 text-xs mt-0.5">Earn 500 bonus points</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-warning-50 rounded-lg border border-warning-100">
                        <i class="fas fa-star text-warning-600 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="font-semibold text-primary-700 text-sm">Submit Feedback</p>
                            <p class="text-neutral-600 text-xs mt-0.5">Earn 100 points for reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

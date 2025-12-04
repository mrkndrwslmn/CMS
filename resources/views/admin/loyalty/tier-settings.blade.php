@extends('admin.layouts.app')

@section('title', 'Tier Settings')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Loyalty Program', 'route' => 'admin.loyalty.index', 'icon' => 'award'],
        ['label' => 'Tier Settings', 'icon' => 'settings'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Tier Settings" 
            description="View and manage loyalty tier configurations"
        />
        <x-ui.button href="{{ route('admin.loyalty.index') }}" variant="secondary">
            <x-lucide-arrow-left class="w-4 h-4" />
            Back to Loyalty
        </x-ui.button>
    </div>

    <!-- Info Alert -->
    <div class="bg-info-50 border border-info-200 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <x-lucide-info class="w-5 h-5 text-info-500 mt-0.5 flex-shrink-0" />
            <div>
                <p class="text-sm font-medium text-info-800">Configuration File Based</p>
                <p class="text-sm text-info-700 mt-1">
                    Tier settings are configured in <code class="bg-info-100 px-1.5 py-0.5 rounded text-xs">config/loyalty.php</code>. 
                    After making changes, run <code class="bg-info-100 px-1.5 py-0.5 rounded text-xs">php artisan config:cache</code> to apply them.
                </p>
            </div>
        </div>
    </div>

    <!-- Tier Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        @foreach(['bronze', 'silver', 'gold', 'platinum'] as $tierName)
        @php
            $tierData = $tiers[$tierName] ?? ['points' => 0, 'discount' => 0];
            $benefits = $tierBenefits[$tierName] ?? ['earning_rate' => '1%', 'discount' => '0%', 'benefits' => []];
        @endphp
        <div class="bg-white rounded-2xl border-2 shadow-sm overflow-hidden
            {{ $tierName === 'platinum' ? 'border-info-300' : '' }}
            {{ $tierName === 'gold' ? 'border-warning-300' : '' }}
            {{ $tierName === 'silver' ? 'border-neutral-300' : '' }}
            {{ $tierName === 'bronze' ? 'border-orange-300' : '' }}">
            
            <!-- Tier Header -->
            <div class="p-6 text-center
                {{ $tierName === 'platinum' ? 'bg-gradient-to-br from-info-50 to-info-100' : '' }}
                {{ $tierName === 'gold' ? 'bg-gradient-to-br from-warning-50 to-warning-100' : '' }}
                {{ $tierName === 'silver' ? 'bg-gradient-to-br from-neutral-50 to-neutral-100' : '' }}
                {{ $tierName === 'bronze' ? 'bg-gradient-to-br from-orange-50 to-orange-100' : '' }}">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4
                    {{ $tierName === 'platinum' ? 'bg-info-200 text-info-700' : '' }}
                    {{ $tierName === 'gold' ? 'bg-warning-200 text-warning-700' : '' }}
                    {{ $tierName === 'silver' ? 'bg-neutral-200 text-neutral-700' : '' }}
                    {{ $tierName === 'bronze' ? 'bg-orange-200 text-orange-700' : '' }}">
                    <x-lucide-award class="w-8 h-8" />
                </div>
                <h3 class="text-xl font-bold text-neutral-800 uppercase">{{ $tierName }}</h3>
                <p class="text-sm text-neutral-600 mt-1">
                    {{ $tierData['points'] === 0 ? 'Starting Tier' : number_format($tierData['points']) . '+ points' }}
                </p>
            </div>

            <!-- Tier Details -->
            <div class="p-6 space-y-4">
                <!-- Key Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-neutral-50 rounded-xl">
                        <p class="text-xs text-neutral-500 mb-1">Earning Rate</p>
                        <p class="text-lg font-bold text-success-600">{{ $benefits['earning_rate'] }}</p>
                    </div>
                    <div class="text-center p-3 bg-neutral-50 rounded-xl">
                        <p class="text-xs text-neutral-500 mb-1">Auto Discount</p>
                        <p class="text-lg font-bold text-primary-600">{{ $benefits['discount'] }}</p>
                    </div>
                </div>

                <!-- Benefits List -->
                <div>
                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">Benefits</p>
                    <ul class="space-y-2">
                        @foreach($benefits['benefits'] ?? [] as $benefit)
                        <li class="flex items-start gap-2 text-sm">
                            <x-lucide-check class="w-4 h-4 text-success-500 mt-0.5 flex-shrink-0" />
                            <span class="text-neutral-700">{{ $benefit }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Configuration Reference -->
    <x-ui.card>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                <x-lucide-code class="w-5 h-5 text-neutral-500" />
                Configuration Reference
            </h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Points Configuration -->
                <div>
                    <h4 class="text-sm font-semibold text-neutral-700 mb-3">Points Settings</h4>
                    <div class="bg-neutral-50 rounded-xl p-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Conversion Rate</span>
                            <span class="text-sm font-semibold text-neutral-800">1 point = ₱{{ config('loyalty.points.conversion_rate', 1) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Minimum Redemption</span>
                            <span class="text-sm font-semibold text-neutral-800">{{ number_format(config('loyalty.points.minimum_redemption', 100)) }} points</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Max Redemption %</span>
                            <span class="text-sm font-semibold text-neutral-800">{{ config('loyalty.points.maximum_redemption_percentage', 50) }}%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Points Expiry</span>
                            <span class="text-sm font-semibold text-neutral-800">{{ config('loyalty.points.expiry_months', 12) }} months</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Expiry Warning</span>
                            <span class="text-sm font-semibold text-neutral-800">{{ config('loyalty.points.expiry_warning_days', 30) }} days before</span>
                        </div>
                    </div>
                </div>

                <!-- Bonus Points -->
                <div>
                    <h4 class="text-sm font-semibold text-neutral-700 mb-3">Bonus Points</h4>
                    <div class="bg-neutral-50 rounded-xl p-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">First Project</span>
                            <span class="text-sm font-semibold text-success-600">+{{ number_format(config('loyalty.bonuses.first_project', 500)) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Milestone Completion</span>
                            <span class="text-sm font-semibold text-success-600">+{{ number_format(config('loyalty.bonuses.milestone_completion', 200)) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Project Completion</span>
                            <span class="text-sm font-semibold text-success-600">+{{ number_format(config('loyalty.bonuses.project_completion', 500)) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Referral</span>
                            <span class="text-sm font-semibold text-success-600">+{{ number_format(config('loyalty.bonuses.referral', 1000)) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Feedback Submission</span>
                            <span class="text-sm font-semibold text-success-600">+{{ number_format(config('loyalty.bonuses.feedback_submission', 100)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.card>
</div>
@endsection

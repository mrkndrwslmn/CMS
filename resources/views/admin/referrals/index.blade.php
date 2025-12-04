@extends('admin.layouts.app')

@section('title', 'Referral Analytics - Admin Dashboard')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Referrals', 'icon' => 'gift'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800 mb-1">Referral System Analytics</h1>
            <p class="text-neutral-500">Monitor referral performance and manage referral codes</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <x-ui.button variant="secondary" href="{{ route('admin.referrals.list') }}">
                <x-lucide-list class="w-4 h-4" />
                View All Referrals
            </x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('admin.referrals.codes') }}">
                <x-lucide-qr-code class="w-4 h-4" />
                Manage Codes
            </x-ui.button>
            <x-ui.button variant="primary" onclick="exportData()">
                <x-lucide-download class="w-4 h-4" />
                Export Report
            </x-ui.button>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Referrals -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Total Referrals</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ number_format($stats['total_referrals']) }}</h3>
                    <p class="text-sm text-neutral-500 mt-1">All time</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-users class="w-5 h-5 text-primary-500" />
                </div>
            </div>
            @if($stats['referral_growth'] != 0)
            <div class="mt-3 flex items-center text-sm {{ $stats['referral_growth'] > 0 ? 'text-success-600' : 'text-error-600' }}">
                @if($stats['referral_growth'] > 0)
                    <x-lucide-trending-up class="w-4 h-4 mr-1" />
                @else
                    <x-lucide-trending-down class="w-4 h-4 mr-1" />
                @endif
                {{ abs($stats['referral_growth']) }}% from last month
            </div>
            @endif
        </x-ui.card>

        <!-- Successful Referrals -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Completed</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ number_format($stats['successful_referrals']) }}</h3>
                    <p class="text-sm text-neutral-500 mt-1">{{ number_format($stats['conversion_rate'], 1) }}% conversion</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Pending Referrals -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Pending</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ number_format($stats['pending_referrals']) }}</h3>
                    <p class="text-sm text-neutral-500 mt-1">Awaiting payment</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Total Rewards -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500 mb-1">Total Rewards</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ number_format($stats['total_rewards']) }}</h3>
                    <p class="text-sm text-neutral-500 mt-1">Points distributed</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl">
                    <x-lucide-gift class="w-5 h-5 text-purple-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Referral Trend Chart -->
        <x-ui.card class="p-6">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                <x-lucide-trending-up class="w-5 h-5 text-primary-500" />
                Referral Trends (Last 6 Months)
            </h3>
            <canvas id="referralTrendChart" height="250"></canvas>
        </x-ui.card>

        <!-- Status Distribution -->
        <x-ui.card class="p-6">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                <x-lucide-pie-chart class="w-5 h-5 text-primary-500" />
                Referral Status Distribution
            </h3>
            <canvas id="statusDistributionChart" height="250"></canvas>
        </x-ui.card>
    </div>

    <!-- Conversion Funnel & Top Referrers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Conversion Funnel -->
        <x-ui.card class="p-6">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                <x-lucide-filter class="w-5 h-5 text-primary-500" />
                Conversion Funnel
            </h3>
            <div class="space-y-4">
                <!-- Signups -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-neutral-700">Total Signups</span>
                        <span class="text-sm font-semibold text-primary-700">{{ number_format($stats['total_referrals']) }}</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3">
                        <div class="bg-primary-500 h-3 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Completed -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-neutral-700">First Payment Made</span>
                        <span class="text-sm font-semibold text-success-700">{{ number_format($stats['successful_referrals']) }}</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3">
                        <div class="bg-success-500 h-3 rounded-full" 
                             style="width: {{ $stats['total_referrals'] > 0 ? ($stats['successful_referrals'] / $stats['total_referrals'] * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-neutral-500 mt-1">{{ number_format($stats['conversion_rate'], 1) }}% conversion rate</p>
                </div>

                <!-- Rewarded -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-neutral-700">Rewards Distributed</span>
                        <span class="text-sm font-semibold text-purple-700">{{ number_format($stats['successful_referrals']) }}</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3">
                        <div class="bg-purple-500 h-3 rounded-full" 
                             style="width: {{ $stats['total_referrals'] > 0 ? ($stats['successful_referrals'] / $stats['total_referrals'] * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-neutral-500 mt-1">{{ number_format($stats['total_rewards']) }} total points</p>
                </div>

                <!-- Drop-off Rate -->
                <div class="mt-4 p-3 bg-warning-50 border border-warning-200 rounded-xl">
                    <div class="flex items-center gap-2">
                        <x-lucide-alert-triangle class="w-5 h-5 text-warning-600 flex-shrink-0" />
                        <div>
                            <p class="text-sm font-semibold text-warning-800">Drop-off Rate</p>
                            <p class="text-xs text-warning-700">{{ number_format(100 - $stats['conversion_rate'], 1) }}% of referred users haven't made their first payment</p>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.card>

        <!-- Top Referrers Leaderboard -->
        <x-ui.card class="p-6">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                <x-lucide-trophy class="w-5 h-5 text-primary-500" />
                Top Referrers
                <span class="text-sm font-normal text-neutral-500">(Last 30 Days)</span>
            </h3>
            <div class="space-y-3">
                @forelse($topReferrers as $index => $referrer)
                <div class="flex items-center justify-between p-3 bg-neutral-50 hover:bg-neutral-100 rounded-xl transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            @if($index === 0)
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <x-lucide-crown class="w-5 h-5 text-yellow-600" />
                            </div>
                            @elseif($index === 1)
                            <div class="w-10 h-10 bg-neutral-200 rounded-full flex items-center justify-center">
                                <x-lucide-medal class="w-5 h-5 text-neutral-600" />
                            </div>
                            @elseif($index === 2)
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                <x-lucide-medal class="w-5 h-5 text-orange-600" />
                            </div>
                            @else
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-semibold text-sm">
                                {{ $index + 1 }}
                            </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-neutral-800">{{ $referrer->fullName }}</p>
                            <p class="text-xs text-neutral-500">{{ $referrer->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold text-primary-700">{{ $referrer->successful_referrals }}</p>
                        <p class="text-xs text-neutral-500">referrals</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <x-lucide-users class="w-6 h-6 text-neutral-400" />
                    </div>
                    <p class="text-neutral-500">No referrers yet</p>
                </div>
                @endforelse
            </div>
        </x-ui.card>
    </div>

    <!-- Recent Activity -->
    <x-ui.card>
        <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                <x-lucide-activity class="w-5 h-5 text-primary-500" />
                Recent Referral Activity
            </h3>
            <a href="{{ route('admin.referrals.list') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium inline-flex items-center gap-1">
                View All
                <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Referrer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Referred User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Rewards</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($recentReferrals as $referral)
                    <tr class="hover:bg-neutral-50 transition-colors">
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
                            @if($referral->status === 'rewarded')
                            <div class="text-sm">
                                <p class="font-medium text-success-700">{{ number_format($referral->earned_points) }} pts</p>
                                @if($referral->referrerCoupon)
                                <p class="text-xs text-neutral-500">+ {{ $referral->referrerCoupon->discount_value }}% coupon</p>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-neutral-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm">
                                <p class="text-neutral-800">{{ $referral->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-neutral-500">{{ $referral->created_at->diffForHumans() }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.referrals.show', $referral->id) }}" class="text-primary-600 hover:text-primary-700">
                                <x-lucide-eye class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center">
                            <div class="w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-lucide-inbox class="w-6 h-6 text-neutral-400" />
                            </div>
                            <p class="text-neutral-500">No referral activity yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
// Referral Trend Chart
const trendCtx = document.getElementById('referralTrendChart').getContext('2d');
new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyStats->pluck('month')) !!},
        datasets: [
            {
                label: 'Total Referrals',
                data: {!! json_encode($monthlyStats->pluck('total')) !!},
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Completed',
                data: {!! json_encode($monthlyStats->pluck('completed')) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Completed', 'Rewarded'],
        datasets: [{
            data: [
                {{ $stats['pending_referrals'] }},
                {{ $stats['successful_referrals'] - $stats['pending_referrals'] }},
                {{ $stats['successful_referrals'] }}
            ],
            backgroundColor: [
                'rgb(234, 179, 8)',
                'rgb(59, 130, 246)',
                'rgb(34, 197, 94)'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Export functionality
function exportData() {
    window.location.href = '{{ route("admin.referrals.export") }}?format=csv';
}
</script>
@endpush
@endsection

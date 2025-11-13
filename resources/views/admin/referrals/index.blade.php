@extends('layouts.admin')

@section('title', 'Referral Analytics - Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="heading-serif text-3xl text-primary-700 mb-2">Referral System Analytics</h1>
            <p class="text-neutral-600">Monitor referral performance and manage referral codes</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.referrals.list') }}" class="btn-secondary">
                <i class="fas fa-list mr-2"></i>View All Referrals
            </a>
            <a href="{{ route('admin.referrals.codes') }}" class="btn-secondary">
                <i class="fas fa-code mr-2"></i>Manage Codes
            </a>
            <button onclick="exportData()" class="btn-primary">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Referrals -->
        <div class="glass-card p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-neutral-500 uppercase tracking-wide">Total Referrals</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-primary-700">{{ number_format($stats['total_referrals']) }}</h3>
                    <p class="text-sm text-neutral-600 mt-1">All time</p>
                </div>
                @if($stats['referral_growth'] != 0)
                <span class="text-sm font-semibold {{ $stats['referral_growth'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas fa-{{ $stats['referral_growth'] > 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                    {{ abs($stats['referral_growth']) }}%
                </span>
                @endif
            </div>
        </div>

        <!-- Successful Referrals -->
        <div class="glass-card p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-neutral-500 uppercase tracking-wide">Completed</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-green-700">{{ number_format($stats['successful_referrals']) }}</h3>
                    <p class="text-sm text-neutral-600 mt-1">{{ number_format($stats['conversion_rate'], 1) }}% conversion</p>
                </div>
            </div>
        </div>

        <!-- Pending Referrals -->
        <div class="glass-card p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-neutral-500 uppercase tracking-wide">Pending</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-yellow-700">{{ number_format($stats['pending_referrals']) }}</h3>
                    <p class="text-sm text-neutral-600 mt-1">Awaiting payment</p>
                </div>
            </div>
        </div>

        <!-- Total Rewards -->
        <div class="glass-card p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-gift text-white text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-neutral-500 uppercase tracking-wide">Total Rewards</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-purple-700">{{ number_format($stats['total_rewards']) }}</h3>
                    <p class="text-sm text-neutral-600 mt-1">Points distributed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Referral Trend Chart -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4">Referral Trends (Last 6 Months)</h3>
            <canvas id="referralTrendChart" height="250"></canvas>
        </div>

        <!-- Status Distribution -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4">Referral Status Distribution</h3>
            <canvas id="statusDistributionChart" height="250"></canvas>
        </div>
    </div>

    <!-- Conversion Funnel & Top Referrers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Conversion Funnel -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4">Conversion Funnel</h3>
            <div class="space-y-4">
                <!-- Signups -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-neutral-700">Total Signups</span>
                        <span class="text-sm font-bold text-primary-700">{{ number_format($stats['total_referrals']) }}</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-3 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Completed -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-neutral-700">First Payment Made</span>
                        <span class="text-sm font-bold text-green-700">{{ number_format($stats['successful_referrals']) }}</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full" 
                             style="width: {{ $stats['total_referrals'] > 0 ? ($stats['successful_referrals'] / $stats['total_referrals'] * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-neutral-600 mt-1">{{ number_format($stats['conversion_rate'], 1) }}% conversion rate</p>
                </div>

                <!-- Rewarded -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-neutral-700">Rewards Distributed</span>
                        <span class="text-sm font-bold text-purple-700">{{ number_format($stats['successful_referrals']) }}</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-3 rounded-full" 
                             style="width: {{ $stats['total_referrals'] > 0 ? ($stats['successful_referrals'] / $stats['total_referrals'] * 100) : 0 }}%"></div>
                    </div>
                    <p class="text-xs text-neutral-600 mt-1">{{ number_format($stats['total_rewards']) }} total points</p>
                </div>

                <!-- Drop-off Rate -->
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                        <div>
                            <p class="text-sm font-semibold text-yellow-800">Drop-off Rate</p>
                            <p class="text-xs text-yellow-700">{{ number_format(100 - $stats['conversion_rate'], 1) }}% of referred users haven't made their first payment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Referrers Leaderboard -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-primary-700 mb-4">
                Top Referrers
                <span class="text-sm font-normal text-neutral-600">(Last 30 Days)</span>
            </h3>
            <div class="space-y-4">
                @forelse($topReferrers as $index => $referrer)
                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-primary-50 to-transparent rounded-lg hover-lift">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            @if($index === 0)
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-crown text-white text-lg"></i>
                            </div>
                            @elseif($index === 1)
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-300 to-gray-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-medal text-white text-lg"></i>
                            </div>
                            @elseif($index === 2)
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-medal text-white text-lg"></i>
                            </div>
                            @else
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $index + 1 }}
                            </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-neutral-800">{{ $referrer->fullName }}</p>
                            <p class="text-xs text-neutral-600">{{ $referrer->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-primary-700">{{ $referrer->successful_referrals }}</p>
                        <p class="text-xs text-neutral-600">referrals</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-users text-neutral-300 text-4xl mb-3"></i>
                    <p class="text-neutral-600">No referrers yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="glass-card p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-primary-700">Recent Referral Activity</h3>
            <a href="{{ route('admin.referrals.list') }}" class="text-sm text-primary-600 hover:text-primary-700 font-semibold">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Referrer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Referred User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Rewards</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($recentReferrals as $referral)
                    <tr class="hover:bg-primary-50 transition-colors">
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-neutral-800">{{ $referral->referrer->fullName }}</p>
                                <p class="text-xs text-neutral-600">{{ $referral->referrer->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-neutral-800">{{ $referral->referred->fullName }}</p>
                                <p class="text-xs text-neutral-600">{{ $referral->referred->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <code class="px-2 py-1 bg-primary-100 text-primary-700 rounded text-sm font-mono">{{ $referral->referral_code }}</code>
                        </td>
                        <td class="px-4 py-3">
                            @if($referral->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                            @elseif($referral->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <i class="fas fa-check mr-1"></i>Completed
                            </span>
                            @elseif($referral->status === 'rewarded')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-gift mr-1"></i>Rewarded
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($referral->status === 'rewarded')
                            <div class="text-sm">
                                <p class="font-semibold text-green-700">{{ number_format($referral->earned_points) }} pts</p>
                                @if($referral->referrerCoupon)
                                <p class="text-xs text-neutral-600">+ {{ $referral->referrerCoupon->discount_value }}% coupon</p>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-neutral-500">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm">
                                <p class="text-neutral-800">{{ $referral->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-neutral-600">{{ $referral->created_at->diffForHumans() }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.referrals.show', $referral->id) }}" class="text-primary-600 hover:text-primary-700">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center">
                            <i class="fas fa-inbox text-neutral-300 text-4xl mb-3"></i>
                            <p class="text-neutral-600">No referral activity yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
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

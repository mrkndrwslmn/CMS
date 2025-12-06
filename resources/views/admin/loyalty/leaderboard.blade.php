@extends('admin.layouts.app')

@section('title', 'Loyalty Leaderboard')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Loyalty Program', 'route' => 'admin.loyalty.index', 'icon' => 'award'],
        ['label' => 'Leaderboard', 'icon' => 'trophy'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Loyalty Leaderboard" 
            description="Top 50 members by lifetime points earned"
        />
        <div class="flex gap-3">
            <x-ui.button href="{{ route('admin.loyalty.index') }}" variant="secondary">
                <x-lucide-arrow-left class="w-4 h-4" />
                Back to Loyalty
            </x-ui.button>
        </div>
    </div>

    <!-- Period Filter -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <form action="{{ route('admin.loyalty.leaderboard') }}" method="GET" class="flex flex-wrap gap-4 items-center">
                <span class="text-sm font-medium text-neutral-600">Filter by period:</span>
                <div class="flex gap-2">
                    <x-ui.button 
                        type="submit" 
                        name="period" 
                        value="all_time"
                        :variant="$period === 'all_time' ? 'primary' : 'secondary'"
                    >
                        All Time
                    </x-ui.button>
                    <x-ui.button 
                        type="submit" 
                        name="period" 
                        value="this_year"
                        :variant="$period === 'this_year' ? 'primary' : 'secondary'"
                    >
                        This Year
                    </x-ui.button>
                    <x-ui.button 
                        type="submit" 
                        name="period" 
                        value="this_month"
                        :variant="$period === 'this_month' ? 'primary' : 'secondary'"
                    >
                        This Month
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Leaderboard -->
    <x-ui.card>
        <div class="p-6">
            @if($leaderboard->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider w-16">Rank</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Member</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Tier</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Available Points</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Lifetime Earned</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($leaderboard as $index => $loyalty)
                        <tr class="hover:bg-neutral-50 transition-colors {{ $index < 3 ? 'bg-warning-50' : '' }}">
                            <td class="px-4 py-3 text-center">
                                @if($index === 0)
                                <div class="inline-flex items-center justify-center w-8 h-8 bg-warning-400 text-white rounded-full font-bold">
                                    <x-lucide-crown class="w-4 h-4" />
                                </div>
                                @elseif($index === 1)
                                <div class="inline-flex items-center justify-center w-8 h-8 bg-neutral-400 text-white rounded-full font-bold">2</div>
                                @elseif($index === 2)
                                <div class="inline-flex items-center justify-center w-8 h-8 bg-orange-400 text-white rounded-full font-bold">3</div>
                                @else
                                <span class="text-lg font-semibold text-neutral-600">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($loyalty->user)
                                <div class="flex items-center gap-3">
                                    @if($loyalty->user->profilePic)
                                        <img src="{{ $loyalty->user->getProfilePictureUrl() }}" 
                                             alt="{{ $loyalty->user->fullName }}" 
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                                            <span class="text-sm font-bold text-primary-700">{{ strtoupper(substr($loyalty->user->fullName ?? 'U', 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-neutral-800">{{ $loyalty->user->fullName }}</p>
                                        <p class="text-xs text-neutral-500">{{ $loyalty->user->email }}</p>
                                    </div>
                                </div>
                                @else
                                <span class="text-sm text-neutral-400">User not found</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $loyalty->tier === 'platinum' ? 'bg-info-100 text-info-700' : '' }}
                                    {{ $loyalty->tier === 'gold' ? 'bg-warning-100 text-warning-700' : '' }}
                                    {{ $loyalty->tier === 'silver' ? 'bg-neutral-200 text-neutral-700' : '' }}
                                    {{ $loyalty->tier === 'bronze' ? 'bg-orange-100 text-orange-700' : '' }}">
                                    <x-lucide-award class="w-4 h-4" />
                                    {{ ucfirst($loyalty->tier) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-lg font-semibold text-primary-600">{{ number_format($loyalty->available_points) }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-lg font-bold text-success-600">{{ number_format($loyalty->lifetime_earned) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($loyalty->user)
                                <x-ui.button href="{{ route('admin.loyalty.show', $loyalty->user) }}" variant="secondary" size="sm">
                                    <x-lucide-eye class="w-4 h-4" />
                                    View
                                </x-ui.button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <x-lucide-trophy class="w-12 h-12 mx-auto text-neutral-300 mb-4" />
                <p class="text-neutral-500">No members found for this period</p>
                <p class="text-sm text-neutral-400 mt-1">Members will appear once they earn loyalty points</p>
            </div>
            @endif
        </div>
    </x-ui.card>
</div>
@endsection

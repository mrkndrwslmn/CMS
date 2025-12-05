@extends('admin.layouts.app')

@section('title', 'Adiutor Leaderboard')

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Earnings Analytics', 'route' => 'admin.earnings-analytics.index', 'icon' => 'bar-chart-2'],
            ['label' => 'Leaderboard', 'icon' => 'trophy'],
        ]" />

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800">
                        Adiutor Leaderboard
                    </h1>
                    <p class="text-sm text-neutral-500 mt-1">Top performing adiutors by earnings and hours</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Period Filter -->
                    <form method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="sort" value="{{ $sortBy }}">
                        <select name="period" class="px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" onchange="this.form.submit()">
                            <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ request('period', '30') == '30' ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                            <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                        </select>
                    </form>
                    
                    <!-- Export -->
                    <a href="{{ route('admin.earnings-analytics.export', ['type' => 'adiutor_earnings', 'period' => request('period', 30)]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <x-lucide-download class="w-4 h-4" /> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Earnings</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($totals['total_earnings'], 2) }}</p>
                    </div>
                    <div class="p-3 bg-success-50 rounded-xl">
                        <x-lucide-banknote class="w-5 h-5 text-success-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Hours</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($totals['total_hours'], 1) }} hrs</p>
                    </div>
                    <div class="p-3 bg-primary-50 rounded-xl">
                        <x-lucide-clock class="w-5 h-5 text-primary-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Adiutors</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $totals['total_adiutors'] }}</p>
                    </div>
                    <div class="p-3 bg-neutral-50 rounded-xl">
                        <x-lucide-users class="w-5 h-5 text-neutral-400" />
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Active This Period</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $totals['active_adiutors'] }}</p>
                    </div>
                    <div class="p-3 bg-neutral-50 rounded-xl">
                        <x-lucide-user-check class="w-5 h-5 text-neutral-400" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaderboard Table -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
            <div class="p-5 border-b border-neutral-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h5 class="text-lg font-medium text-neutral-700">Rankings</h5>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-neutral-500">Sort by:</span>
                        <form method="GET" class="flex items-center gap-2">
                            <input type="hidden" name="period" value="{{ request('period', 30) }}">
                            <select name="sort" class="px-4 py-2 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" onchange="this.form.submit()">
                                <option value="earnings" {{ $sortBy == 'earnings' ? 'selected' : '' }}>Total Earnings</option>
                                <option value="hours" {{ $sortBy == 'hours' ? 'selected' : '' }}>Hours Worked</option>
                                <option value="assignments" {{ $sortBy == 'assignments' ? 'selected' : '' }}>Total Assignments</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-center w-16">Rank</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-left">Adiutor</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Hourly Earnings</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Fixed Earnings</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Total Earnings</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Hours</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Assignments</th>
                            <th class="text-xs font-medium text-neutral-500 uppercase tracking-wider py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($adiutors as $index => $adiutor)
                            @php
                                $rank = ($adiutors->currentPage() - 1) * $adiutors->perPage() + $index + 1;
                            @endphp
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="py-4 px-5 text-center">
                                    @if($rank <= 3)
                                        <div class="w-8 h-8 mx-auto flex items-center justify-center rounded-full 
                                            {{ $rank === 1 ? 'bg-warning-100 text-warning-600' : ($rank === 2 ? 'bg-neutral-100 text-neutral-600' : 'bg-orange-100 text-orange-600') }}">
                                            @if($rank === 1)
                                                <x-lucide-trophy class="w-4 h-4" />
                                            @elseif($rank === 2)
                                                <x-lucide-medal class="w-4 h-4" />
                                            @else
                                                <x-lucide-award class="w-4 h-4" />
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-neutral-500 font-medium">{{ $rank }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    <div class="flex items-center">
                                        @if($adiutor->profilePic)
                                            <img src="{{ asset('storage/' . $adiutor->profilePic) }}" 
                                                 class="h-10 w-10 rounded-full object-cover mr-3" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                                <span class="text-primary-600 font-semibold">{{ substr($adiutor->fullName, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-neutral-700">{{ $adiutor->fullName }}</div>
                                            <div class="text-xs text-neutral-500">{{ $adiutor->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5 text-right text-primary-600 font-medium">
                                    ₱{{ number_format($adiutor->hourly_earnings, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-neutral-600 font-medium">
                                    ₱{{ number_format($adiutor->fixed_earnings, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right font-bold text-success-600">
                                    ₱{{ number_format($adiutor->hourly_earnings + $adiutor->fixed_earnings, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-neutral-600">
                                    {{ number_format($adiutor->total_hours, 1) }} hrs
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                        {{ $adiutor->total_assignments }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <a href="{{ route('admin.payouts.adiutor-earnings', $adiutor->id) }}" 
                                       class="text-neutral-400 hover:text-primary-600 transition-colors" title="View Details">
                                        <x-lucide-external-link class="w-4 h-4" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-neutral-500">
                                    <x-lucide-users class="w-12 h-12 mx-auto mb-3 text-neutral-300" />
                                    <p class="text-sm">No adiutor data found for this period</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($adiutors->hasPages())
                <div class="p-5 border-t border-neutral-100">
                    <x-ui.pagination :paginator="$adiutors->withQueryString()" />
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Adiutor Leaderboard')

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 text-sm text-neutral-500">
                            <li><a href="{{ route('admin.earnings-analytics.index') }}" class="hover:text-accent-500">Earnings Analytics</a></li>
                            <li><i class="fas fa-chevron-right mx-2 text-xs"></i></li>
                            <li class="text-primary-500 font-medium">Leaderboard</li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-semibold text-primary-500">
                        <i class="fas fa-trophy text-warning-500 mr-2"></i>
                        Adiutor Leaderboard
                    </h1>
                    <p class="text-neutral-500 mt-1">Top performing adiutors by earnings and hours</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Period Filter -->
                    <form method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="sort" value="{{ $sortBy }}">
                        <select name="period" class="form-select rounded-lg border-neutral-200 text-sm" onchange="this.form.submit()">
                            <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 days</option>
                            <option value="30" {{ request('period', '30') == '30' ? 'selected' : '' }}>Last 30 days</option>
                            <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                            <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Last year</option>
                        </select>
                    </form>
                    
                    <!-- Export -->
                    <a href="{{ route('admin.earnings-analytics.export', ['type' => 'adiutor_earnings', 'period' => request('period', 30)]) }}" 
                       class="glass-button-accent rounded-lg px-4 py-2 flex items-center text-sm">
                        <i class="fas fa-download mr-2"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-success-500">
                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Total Earnings</div>
                <div class="text-2xl font-bold text-primary-500">₱{{ number_format($totals['total_earnings'], 2) }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-accent-500">
                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Total Hours</div>
                <div class="text-2xl font-bold text-primary-500">{{ number_format($totals['total_hours'], 1) }} hrs</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-secondary-500">
                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Total Adiutors</div>
                <div class="text-2xl font-bold text-primary-500">{{ $totals['total_adiutors'] }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-tertiary-500">
                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Active This Period</div>
                <div class="text-2xl font-bold text-primary-500">{{ $totals['active_adiutors'] }}</div>
            </div>
        </div>

        <!-- Leaderboard Table -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-5 border-b border-neutral-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h5 class="text-lg font-bold text-primary-500">Rankings</h5>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-neutral-500">Sort by:</span>
                        <form method="GET" class="flex items-center gap-2">
                            <input type="hidden" name="period" value="{{ request('period', 30) }}">
                            <select name="sort" class="form-select rounded-lg border-neutral-200 text-sm" onchange="this.form.submit()">
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
                    <thead>
                        <tr class="border-b border-neutral-100">
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-center w-16">Rank</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-left">Adiutor</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Hourly Earnings</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Fixed Earnings</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Total Earnings</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Hours</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Assignments</th>
                            <th class="text-neutral-500 font-semibold py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adiutors as $index => $adiutor)
                            @php
                                $rank = ($adiutors->currentPage() - 1) * $adiutors->perPage() + $index + 1;
                            @endphp
                            <tr class="border-b border-neutral-50 hover:bg-neutral-50">
                                <td class="py-4 px-5 text-center">
                                    @if($rank <= 3)
                                        <div class="w-8 h-8 mx-auto flex items-center justify-center rounded-full 
                                            {{ $rank === 1 ? 'bg-yellow-100 text-yellow-600' : ($rank === 2 ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-600') }}
                                            font-bold">
                                            @if($rank === 1)
                                                <i class="fas fa-trophy"></i>
                                            @elseif($rank === 2)
                                                <i class="fas fa-medal"></i>
                                            @else
                                                <i class="fas fa-award"></i>
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
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-accent-400 to-secondary-500 flex items-center justify-center mr-3">
                                                <span class="text-white font-bold">{{ substr($adiutor->fullName, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-primary-500">{{ $adiutor->fullName }}</div>
                                            <div class="text-xs text-neutral-500">{{ $adiutor->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5 text-right text-accent-500 font-medium">
                                    ₱{{ number_format($adiutor->hourly_earnings, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-secondary-500 font-medium">
                                    ₱{{ number_format($adiutor->fixed_earnings, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right font-bold text-success-500">
                                    ₱{{ number_format($adiutor->hourly_earnings + $adiutor->fixed_earnings, 2) }}
                                </td>
                                <td class="py-4 px-5 text-right text-neutral-600">
                                    {{ number_format($adiutor->total_hours, 1) }} hrs
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <span class="px-2.5 py-1 bg-neutral-100 text-neutral-600 rounded-full text-xs font-medium">
                                        {{ $adiutor->total_assignments }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <a href="{{ route('admin.payouts.adiutor-earnings', $adiutor->id) }}" 
                                       class="text-accent-500 hover:text-accent-600" title="View Details">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-neutral-500">
                                    <i class="fas fa-users text-5xl mb-4 opacity-30"></i>
                                    <p>No adiutor data found for this period</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($adiutors->hasPages())
                <div class="p-5 border-t border-neutral-100">
                    {{ $adiutors->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

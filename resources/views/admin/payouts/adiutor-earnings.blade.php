@extends('admin.layouts.app')

@section('title', 'Adiutor Earnings - ' . $adiutor->fullName)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.payouts.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Payouts
            </a>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ $adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($adiutor->fullName) }}" 
                     alt="{{ $adiutor->fullName }}" 
                     class="w-16 h-16 rounded-full border-2 border-gray-200">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $adiutor->fullName }}</h1>
                    <p class="text-sm text-gray-500">{{ $adiutor->email }}</p>
                    @if($adiutor->adiutorProfile)
                        <p class="text-sm text-gray-500 mt-1">
                            Standard Rate: ₱{{ number_format($adiutor->adiutorProfile->standard_hourly_rate ?? 0, 2) }}/hr
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Earnings -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Earnings</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalEarnings, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Paid Earnings -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Paid Out</p>
                    <p class="text-2xl font-bold text-green-600">₱{{ number_format($paidEarnings, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved Unpaid -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Ready for Payout</p>
                    <p class="text-2xl font-bold text-blue-600">₱{{ number_format($approvedUnpaid, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Pending Approval</p>
                    <p class="text-2xl font-bold text-yellow-600">₱{{ number_format($pendingApproval, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content - Time Entries -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Time Entries Table -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Time Entries</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $timeEntries->total() }} total entries</p>
                    </div>
                </div>
                
                @if($timeEntries->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Time Entries</h4>
                        <p class="text-gray-500">This adiutor hasn't recorded any time entries yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Task / Project</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($timeEntries as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $entry->start_time->format('M d, Y') }}
                                        <div class="text-xs text-gray-500">
                                            {{ $entry->start_time->format('g:i A') }} - {{ $entry->end_time ? $entry->end_time->format('g:i A') : 'Ongoing' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $entry->task?->taskTitle ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $entry->task?->project?->title ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($entry->duration_minutes / 60, 2) }}h
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱{{ number_format($entry->calculated_amount ?? 0, 2) }}
                                        <div class="text-xs text-gray-500">@ ₱{{ number_format($entry->hourly_rate ?? 0, 2) }}/hr</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($entry->is_paid)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Paid
                                            </span>
                                        @elseif($entry->is_approved)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($timeEntries->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            {{ $timeEntries->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Sidebar - Payouts -->
        <div class="space-y-6">
            <!-- Payout History -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Payout History</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $payouts->count() }} payouts</p>
                </div>
                
                @if($payouts->isEmpty())
                    <div class="p-8 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">No payouts yet</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($payouts->take(10) as $payout)
                        <a href="{{ route('admin.payouts.show', $payout->id) }}" 
                           class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-900">{{ $payout->payout_number }}</span>
                                @if($payout->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                @elseif($payout->status === 'processing')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Processing</span>
                                @elseif($payout->status === 'cancelled')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-primary-600">₱{{ number_format($payout->amount, 2) }}</span>
                                <span class="text-xs text-gray-500">{{ $payout->created_at->format('M d, Y') }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    
                    @if($payouts->count() > 10)
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-center">
                            <span class="text-sm text-gray-500">Showing latest 10 of {{ $payouts->count() }} payouts</span>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Adiutor Profile Info -->
            @if($adiutor->adiutorProfile)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Preferred Method</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ ucwords(str_replace('_', ' ', $adiutor->adiutorProfile->preferred_payout_method ?? 'Not specified')) }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Standard Hourly Rate</p>
                        <p class="text-sm font-medium text-gray-900">
                            ₱{{ number_format($adiutor->adiutorProfile->standard_hourly_rate ?? 0, 2) }}/hr
                        </p>
                    </div>

                    @if($adiutor->adiutorProfile->payout_details)
                        @php
                            $payoutDetails = is_string($adiutor->adiutorProfile->payout_details) 
                                ? json_decode($adiutor->adiutorProfile->payout_details, true) 
                                : $adiutor->adiutorProfile->payout_details;
                        @endphp
                        @if(is_array($payoutDetails))
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Account Details</p>
                                @foreach($payoutDetails as $key => $value)
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-500">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                        <span class="text-gray-900 font-medium">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-6 text-white">
                <h4 class="text-sm font-medium text-primary-100 mb-4">Quick Stats</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-primary-100">Total Time Logged</span>
                        <span class="font-semibold">{{ number_format($timeEntries->sum('duration_minutes') / 60, 1) }}h</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-primary-100">Avg. Hourly Earned</span>
                        @php
                            $totalHours = $timeEntries->sum('duration_minutes') / 60;
                            $avgHourly = $totalHours > 0 ? $totalEarnings / $totalHours : 0;
                        @endphp
                        <span class="font-semibold">₱{{ number_format($avgHourly, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-primary-100">Projects Worked</span>
                        <span class="font-semibold">{{ $timeEntries->pluck('task.project_id')->unique()->filter()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-primary-100">Payout Rate</span>
                        @php
                            $payoutRate = $totalEarnings > 0 ? ($paidEarnings / $totalEarnings) * 100 : 0;
                        @endphp
                        <span class="font-semibold">{{ number_format($payoutRate, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

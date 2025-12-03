@extends('admin.layouts.app')

@section('title', 'Adiutor Earnings - ' . $adiutor->fullName)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Payouts', 'url' => route('admin.payouts.index'), 'icon' => 'wallet'],
        ['label' => $adiutor->fullName, 'icon' => 'user']
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ $adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($adiutor->fullName) }}" 
                     alt="{{ $adiutor->fullName }}" 
                     class="w-16 h-16 rounded-full border-2 border-neutral-200">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800">{{ $adiutor->fullName }}</h1>
                    <p class="text-sm text-neutral-500">{{ $adiutor->email }}</p>
                    @if($adiutor->adiutorProfile)
                        <p class="text-sm text-neutral-500 mt-1">
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
        <x-ui.card class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Total Earnings</p>
                    <p class="text-2xl font-bold text-neutral-800">₱{{ number_format($totalEarnings, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                    <x-lucide-banknote class="w-6 h-6 text-primary-600" />
                </div>
            </div>
        </x-ui.card>

        <!-- Paid Earnings -->
        <x-ui.card class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Paid Out</p>
                    <p class="text-2xl font-bold text-success-600">₱{{ number_format($paidEarnings, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                    <x-lucide-check-circle class="w-6 h-6 text-success-600" />
                </div>
            </div>
        </x-ui.card>

        <!-- Approved Unpaid -->
        <x-ui.card class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Ready for Payout</p>
                    <p class="text-2xl font-bold text-primary-600">₱{{ number_format($approvedUnpaid, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                    <x-lucide-wallet class="w-6 h-6 text-primary-600" />
                </div>
            </div>
        </x-ui.card>

        <!-- Pending Approval -->
        <x-ui.card class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Pending Approval</p>
                    <p class="text-2xl font-bold text-warning-600">₱{{ number_format($pendingApproval, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                    <x-lucide-clock class="w-6 h-6 text-warning-600" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content - Time Entries -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Time Entries Table -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-800">Time Entries</h3>
                        <p class="text-sm text-neutral-500 mt-1">{{ $timeEntries->total() }} total entries</p>
                    </div>
                </div>
                
                @if($timeEntries->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-neutral-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <x-lucide-clock class="w-8 h-8 text-neutral-400" />
                        </div>
                        <h4 class="text-lg font-medium text-neutral-800 mb-2">No Time Entries</h4>
                        <p class="text-neutral-500">This adiutor hasn't recorded any time entries yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-neutral-50 border-b border-neutral-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Task / Project</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-neutral-100">
                                @foreach($timeEntries as $entry)
                                <tr class="hover:bg-neutral-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800">
                                        {{ $entry->start_time->format('M d, Y') }}
                                        <div class="text-xs text-neutral-500">
                                            {{ $entry->start_time->format('g:i A') }} - {{ $entry->end_time ? $entry->end_time->format('g:i A') : 'Ongoing' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-neutral-800">{{ $entry->task?->taskTitle ?? 'N/A' }}</div>
                                        <div class="text-xs text-neutral-500">{{ $entry->task?->project?->title ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800">
                                        {{ number_format($entry->duration_minutes / 60, 2) }}h
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-800">
                                        ₱{{ number_format($entry->calculated_amount ?? 0, 2) }}
                                        <div class="text-xs text-neutral-500">@ ₱{{ number_format($entry->hourly_rate ?? 0, 2) }}/hr</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($entry->is_paid)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-success-50 text-success-700">
                                                <x-lucide-check-circle class="w-3 h-3" />
                                                Paid
                                            </span>
                                        @elseif($entry->is_approved)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-primary-50 text-primary-700">
                                                <x-lucide-check class="w-3 h-3" />
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-warning-50 text-warning-700">
                                                <x-lucide-clock class="w-3 h-3" />
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
                        <div class="px-6 py-4 border-t border-neutral-100">
                            {{ $timeEntries->links() }}
                        </div>
                    @endif
                @endif
            </x-ui.card>
        </div>

        <!-- Sidebar - Payouts -->
        <div class="space-y-6">
            <!-- Payout History -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-lg font-semibold text-neutral-800">Payout History</h3>
                    <p class="text-sm text-neutral-500 mt-1">{{ $payouts->count() }} payouts</p>
                </div>
                
                @if($payouts->isEmpty())
                    <div class="p-8 text-center">
                        <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <x-lucide-wallet class="w-6 h-6 text-neutral-400" />
                        </div>
                        <p class="text-sm text-neutral-500">No payouts yet</p>
                    </div>
                @else
                    <div class="divide-y divide-neutral-100">
                        @foreach($payouts->take(10) as $payout)
                        <a href="{{ route('admin.payouts.show', $payout->id) }}" 
                           class="block px-6 py-4 hover:bg-neutral-50 transition-colors">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-neutral-800">{{ $payout->payout_number }}</span>
                                @if($payout->status === 'completed')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-medium bg-success-50 text-success-700">Paid</span>
                                @elseif($payout->status === 'processing')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-medium bg-primary-50 text-primary-700">Processing</span>
                                @elseif($payout->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-medium bg-error-50 text-error-700">Cancelled</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-medium bg-warning-50 text-warning-700">Pending</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-primary-600">₱{{ number_format($payout->amount, 2) }}</span>
                                <span class="text-xs text-neutral-500">{{ $payout->created_at->format('M d, Y') }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    
                    @if($payouts->count() > 10)
                        <div class="px-6 py-3 border-t border-neutral-100 text-center">
                            <span class="text-sm text-neutral-500">Showing latest 10 of {{ $payouts->count() }} payouts</span>
                        </div>
                    @endif
                @endif
            </x-ui.card>

            <!-- Adiutor Profile Info -->
            @if($adiutor->adiutorProfile)
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-lg font-semibold text-neutral-800">Payment Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Preferred Method</p>
                        <p class="text-sm font-medium text-neutral-800">
                            {{ ucwords(str_replace('_', ' ', $adiutor->adiutorProfile->preferred_payout_method ?? 'Not specified')) }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Standard Hourly Rate</p>
                        <p class="text-sm font-medium text-neutral-800">
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
                            <div class="pt-4 border-t border-neutral-100">
                                <p class="text-xs text-neutral-500 uppercase tracking-wider mb-2">Account Details</p>
                                @foreach($payoutDetails as $key => $value)
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-neutral-500">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                        <span class="text-neutral-800 font-medium">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </x-ui.card>
            @endif

            <!-- Quick Stats -->
            <x-ui.card class="bg-primary-600 border-0">
                <div class="p-6 text-white">
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
            </x-ui.card>
        </div>
    </div>
</div>
@endsection

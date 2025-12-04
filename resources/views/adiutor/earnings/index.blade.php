@extends('adiutor.layouts.app')

@section('title', 'My Earnings')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Earnings', 'icon' => 'wallet'],
    ]" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">My Earnings</h1>
                <p class="text-sm text-neutral-500 mt-1">Track your earnings and work history</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <x-ui.button variant="secondary" href="{{ route('adiutor.earnings.wallet') }}">
                    <x-lucide-wallet class="w-4 h-4" />
                    My Wallet
                </x-ui.button>
                <x-ui.button variant="secondary" href="{{ route('adiutor.earnings.payouts') }}">
                    <x-lucide-receipt class="w-4 h-4" />
                    Payout History
                </x-ui.button>
                <x-ui.button variant="secondary" href="{{ route('adiutor.profile.earnings') }}">
                    <x-lucide-settings class="w-4 h-4" />
                    Settings
                </x-ui.button>
                @if($approvedEarnings >= ($adiutor->adiutorProfile->minimum_payout_amount ?? 500))
                <x-ui.button variant="primary" href="{{ route('adiutor.earnings.request-form') }}">
                    <x-lucide-banknote class="w-4 h-4" />
                    Request Payout
                </x-ui.button>
                @endif
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Earnings -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Earnings</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($totalEarnings, 2) }}</p>
                    <p class="text-sm text-neutral-400 mt-2">{{ number_format($totalHours, 1) }} hours</p>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl">
                    <x-lucide-coins class="w-5 h-5 text-neutral-400" />
                </div>
            </div>
        </div>

        <!-- Approved (Available) -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Approved</p>
                    <p class="text-2xl font-semibold text-success-600 mt-1">₱{{ number_format($approvedEarnings, 2) }}</p>
                    <p class="text-sm text-neutral-400 mt-2">Available for payout</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>

        <!-- Paid -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Paid</p>
                    <p class="text-2xl font-semibold text-primary-600 mt-1">₱{{ number_format($paidEarnings, 2) }}</p>
                    <p class="text-sm text-neutral-400 mt-2">Already received</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-banknote class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-warning-600 mt-1">₱{{ number_format($pendingApproval, 2) }}</p>
                    <p class="text-sm text-neutral-400 mt-2">Awaiting approval</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings by Project -->
    @if($earningsByProject->isNotEmpty())
    <x-ui.card class="mb-6">
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-neutral-800">Earnings by Project</h2>
        </div>
        <div class="space-y-3">
            @foreach($earningsByProject as $earning)
            <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-xl hover:bg-neutral-100 transition-colors">
                <div class="flex-1">
                    <h3 class="font-medium text-neutral-800">{{ $earning->project->title ?? 'Unknown Project' }}</h3>
                    <p class="text-sm text-neutral-500 mt-1">{{ number_format($earning->total_minutes / 60, 1) }} hours tracked</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-neutral-800">₱{{ number_format($earning->total_earnings, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </x-ui.card>
    @endif

    <!-- Filters and Earnings -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <h2 class="text-lg font-semibold text-neutral-800">Earnings</h2>
                
                <!-- Filters -->
                <form method="GET" action="{{ route('adiutor.earnings.index') }}" class="flex flex-wrap items-center gap-3">
                    <select name="period" onchange="this.form.submit()" 
                            class="text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="all" {{ $periodFilter == 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="month" {{ $periodFilter == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="week" {{ $periodFilter == 'week' ? 'selected' : '' }}>This Week</option>
                    </select>
                    
                    <select name="status" onchange="this.form.submit()"
                            class="text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="approved" {{ $statusFilter == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $statusFilter == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Earnings Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Project / Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type of Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse($timeEntries as $entry)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                            {{ $entry->start_time->format('M d, Y') }}
                            <span class="block text-xs text-neutral-400">{{ $entry->start_time->format('g:i A') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-800">{{ $entry->task->taskTitle ?? 'N/A' }}</div>
                            <div class="text-xs text-neutral-500">{{ $entry->task->project->title ?? 'No Project' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                            @php
                                // If there are tracked hours/duration, it's Time Tracking
                                // Otherwise it's Agreed Rate (fixed payment)
                                $paymentType = ($entry->duration_minutes && $entry->duration_minutes > 0) ? 'Time Tracking' : 'Agreed Rate';
                            @endphp
                            {{ $paymentType }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                            ₱{{ number_format($entry->hourly_rate ?? 0, 2) }}/hr
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                            {{ number_format($entry->duration_minutes / 60, 2) }}h
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-neutral-800">
                            ₱{{ number_format($entry->calculated_amount ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($entry->is_paid)
                                <x-ui.badge variant="primary" size="sm">
                                    <x-lucide-check-circle class="w-3 h-3" />
                                    Paid
                                </x-ui.badge>
                            @elseif($entry->is_approved)
                                <x-ui.badge variant="success" size="sm">
                                    <x-lucide-check-circle class="w-3 h-3" />
                                    Approved
                                </x-ui.badge>
                            @else
                                <x-ui.badge variant="warning" size="sm">
                                    <x-lucide-clock class="w-3 h-3" />
                                    Pending
                                </x-ui.badge>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-neutral-100 rounded-2xl flex items-center justify-center mb-4">
                                    <x-lucide-clock class="w-8 h-8 text-neutral-400" />
                                </div>
                                <p class="text-neutral-700 font-medium mb-1">No earnings found</p>
                                <p class="text-sm text-neutral-500">Start tracking your time to see earnings here</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($timeEntries->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100">
            {{ $timeEntries->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

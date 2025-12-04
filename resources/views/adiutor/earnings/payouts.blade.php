@extends('adiutor.layouts.app')

@section('title', 'Payout History')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Earnings', 'route' => 'adiutor.earnings.index', 'icon' => 'wallet'],
        ['label' => 'Payout History', 'icon' => 'receipt'],
    ]" />

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-800">Payout History</h1>
        <p class="text-sm text-neutral-500 mt-1">View all your payout requests and their status</p>
    </div>

    <!-- Payouts List -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        @if($payouts->isEmpty())
            <!-- Empty State -->
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-neutral-100 rounded-2xl flex items-center justify-center mb-4">
                        <x-lucide-banknote class="w-8 h-8 text-neutral-400" />
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-800 mb-2">No Payout Requests Yet</h3>
                    <p class="text-neutral-500 mb-6">You haven't submitted any payout requests.</p>
                    <x-ui.button variant="primary" href="{{ route('adiutor.earnings.request-form') }}">
                        <x-lucide-plus class="w-4 h-4" />
                        Request Payout
                    </x-ui.button>
                </div>
            </div>
        @else
            <!-- Payouts Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Payout #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Requested</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-100">
                        @foreach($payouts as $payout)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-neutral-800">{{ $payout->payout_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-700">
                                    {{ \Carbon\Carbon::parse($payout->period_start)->format('M d') }} - 
                                    {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-neutral-800">₱{{ number_format($payout->amount, 2) }}</div>
                                <div class="text-xs text-neutral-500">{{ $payout->items->count() }} items</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-700">
                                    {{ ucwords(str_replace('_', ' ', $payout->payout_method ?? 'Not specified')) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payout->status === 'paid')
                                    <x-ui.badge variant="success" size="sm">
                                        <x-lucide-check-circle class="w-3 h-3" />
                                        Paid
                                    </x-ui.badge>
                                @elseif($payout->status === 'approved')
                                    <x-ui.badge variant="info" size="sm">
                                        <x-lucide-check-circle class="w-3 h-3" />
                                        Approved
                                    </x-ui.badge>
                                @elseif($payout->status === 'rejected')
                                    <x-ui.badge variant="error" size="sm">
                                        <x-lucide-x-circle class="w-3 h-3" />
                                        Rejected
                                    </x-ui.badge>
                                @else
                                    <x-ui.badge variant="warning" size="sm">
                                        <x-lucide-clock class="w-3 h-3" />
                                        Pending
                                    </x-ui.badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-700">{{ $payout->requested_at->format('M d, Y') }}</div>
                                <div class="text-xs text-neutral-500">{{ $payout->requested_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('adiutor.earnings.payout.show', $payout->id) }}" 
                                   class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 font-medium transition-colors">
                                    View Details
                                    <x-lucide-chevron-right class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payouts->hasPages())
            <div class="px-6 py-4 border-t border-neutral-100">
                {{ $payouts->links() }}
            </div>
            @endif
        @endif
    </div>
</div>
@endsection

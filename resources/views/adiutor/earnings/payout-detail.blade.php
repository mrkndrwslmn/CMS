@extends('adiutor.layouts.app')

@section('title', 'Payout Details - ' . $payout->payout_number)

@section('content')
<div class="max-w-8xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Earnings', 'route' => 'adiutor.earnings.index', 'icon' => 'wallet'],
        ['label' => 'Payout History', 'route' => 'adiutor.earnings.payouts', 'icon' => 'receipt'],
        ['label' => $payout->payout_number, 'icon' => 'file-text'],
    ]" />

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Payout {{ $payout->payout_number }}</h1>
                <p class="text-sm text-neutral-500 mt-1">Requested on {{ $payout->requested_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
            <div>
                @if($payout->status === 'paid')
                    <x-ui.badge variant="success">
                        <x-lucide-check-circle class="w-4 h-4" />
                        Paid
                    </x-ui.badge>
                @elseif($payout->status === 'processing')
                    <x-ui.badge variant="info">
                        <x-lucide-loader class="w-4 h-4" />
                        Processing
                    </x-ui.badge>
                @elseif($payout->status === 'rejected')
                    <x-ui.badge variant="error">
                        <x-lucide-x-circle class="w-4 h-4" />
                        Rejected
                    </x-ui.badge>
                @elseif($payout->status === 'cancelled')
                    <x-ui.badge variant="neutral">
                        <x-lucide-circle-dashed class="w-4 h-4" />
                        Cancelled
                    </x-ui.badge>
                @elseif($payout->status === 'failed')
                    <x-ui.badge variant="error">
                        <x-lucide-x-circle class="w-4 h-4" />
                        Failed
                    </x-ui.badge>
                @else
                    <x-ui.badge variant="warning">
                        <x-lucide-clock class="w-4 h-4" />
                        Pending
                    </x-ui.badge>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payout Items -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h2 class="text-lg font-semibold text-neutral-800">Earnings Included</h2>
                    <p class="text-sm text-neutral-500 mt-1">{{ $payout->items->count() }} items</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-neutral-50 border-b border-neutral-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-100">
                            @foreach($payout->items as $item)
                            <tr class="hover:bg-neutral-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($item->item_type)
                                        @case('time_entry')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-primary-50 text-primary-700">
                                                <x-lucide-clock class="w-3 h-3" />
                                                Hourly
                                            </span>
                                            @break
                                        @case('fixed_task')
                                        @case('fixed_rate')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-success-50 text-success-700">
                                                <x-lucide-briefcase class="w-3 h-3" />
                                                Fixed Rate
                                            </span>
                                            @break
                                        @case('milestone')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-warning-50 text-warning-700">
                                                <x-lucide-flag class="w-3 h-3" />
                                                Milestone
                                            </span>
                                            @break
                                        @case('referral')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-accent-50 text-accent-700">
                                                <x-lucide-users class="w-3 h-3" />
                                                Referral Credits
                                            </span>
                                            @break
                                        @case('bonus')
                                            @if(str_contains($item->description, 'Referral'))
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-accent-50 text-accent-700">
                                                <x-lucide-users class="w-3 h-3" />
                                                Referral Credits
                                            </span>
                                            @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-accent-50 text-accent-700">
                                                <x-lucide-gift class="w-3 h-3" />
                                                Bonus
                                            </span>
                                            @endif
                                            @break
                                        @default
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-neutral-100 text-neutral-700">
                                                <x-lucide-circle class="w-3 h-3" />
                                                Other
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-neutral-800">{{ $item->description }}</div>
                                    @if($item->project)
                                        <div class="text-xs text-neutral-500">{{ $item->project->title ?? '' }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                    @if($item->item_type === 'time_entry' && $item->hours)
                                        {{ number_format($item->hours, 2) }}h @ ₱{{ number_format($item->rate ?? 0, 2) }}/hr
                                    @elseif($item->timeEntry)
                                        {{ $item->timeEntry->start_time->format('M d, Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-neutral-800">
                                    ₱{{ number_format($item->amount, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-neutral-50 border-t-2 border-neutral-200">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-sm font-semibold text-neutral-800">Total</td>
                                <td class="px-6 py-4 text-lg font-bold text-primary-600">
                                    ₱{{ number_format($payout->amount, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Admin Notes -->
            @if($payout->admin_notes)
            <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-primary-100 rounded-lg">
                        <x-lucide-info class="w-5 h-5 text-primary-600" />
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-primary-800 mb-2">Admin Notes</h3>
                        <p class="text-sm text-primary-700">{{ $payout->admin_notes }}</p>
                        @if($payout->processedBy)
                            <p class="text-xs text-primary-600 mt-2">
                                - {{ $payout->processedBy->fullName }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes for Failed/Cancelled/Rejected Status -->
            @if(in_array($payout->status, ['failed', 'cancelled', 'rejected']) && $payout->notes)
            <div class="bg-error-50 border border-error-100 rounded-2xl p-6">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-error-100 rounded-lg">
                        <x-lucide-x-circle class="w-5 h-5 text-error-600" />
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-error-800 mb-2">{{ ucfirst($payout->status) }} Reason</h3>
                        <p class="text-sm text-error-700">{{ $payout->notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payout Summary -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-neutral-800">Summary</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Amount</p>
                        <p class="text-2xl font-bold text-neutral-800">₱{{ number_format($payout->amount, 2) }}</p>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Period</p>
                        <p class="text-sm text-neutral-800">
                            {{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} -<br>
                            {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}
                        </p>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Items Breakdown</p>
                        <div class="space-y-1 text-sm">
                            @php
                                $itemsByType = $payout->items->groupBy('item_type');
                            @endphp
                            @foreach($itemsByType as $type => $items)
                                <div class="flex justify-between text-neutral-700">
                                    <span>{{ ucwords(str_replace('_', ' ', $type)) }}</span>
                                    <span class="font-medium">₱{{ number_format($items->sum('amount'), 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Payment Method</p>
                        <p class="text-sm text-neutral-800">{{ ucwords(str_replace('_', ' ', $payout->payout_method ?? 'Not specified')) }}</p>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Currency</p>
                        <p class="text-sm text-neutral-800">{{ $payout->currency ?? 'PHP' }}</p>
                    </div>
                </div>
            </x-ui.card>

            <!-- Timeline -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-neutral-800">Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Requested -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                <x-lucide-clock class="w-4 h-4 text-primary-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-800">Requested</p>
                                <p class="text-xs text-neutral-500">{{ $payout->requested_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>

                        @if($payout->processed_at && in_array($payout->status, ['processing', 'paid', 'failed', 'cancelled', 'rejected']))
                        <!-- Processed -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full {{ in_array($payout->status, ['failed', 'cancelled', 'rejected']) ? 'bg-error-100' : 'bg-success-100' }} flex items-center justify-center">
                                @if(in_array($payout->status, ['failed', 'cancelled', 'rejected']))
                                    <x-lucide-x-circle class="w-4 h-4 text-error-600" />
                                @else
                                    <x-lucide-check-circle class="w-4 h-4 text-success-600" />
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-800">{{ ucfirst($payout->status) }}</p>
                                <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($payout->processed_at)->format('M d, Y g:i A') }}</p>
                                @if($payout->processedBy)
                                    <p class="text-xs text-neutral-500">by {{ $payout->processedBy->fullName }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($payout->completed_at)
                        <!-- Paid/Completed -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-success-100 flex items-center justify-center">
                                <x-lucide-banknote class="w-4 h-4 text-success-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-800">Paid</p>
                                <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($payout->completed_at)->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </x-ui.card>

            <!-- Payment Details -->
            @if($payout->payout_details)
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-neutral-800">Payment Details</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2 text-sm">
                        @foreach($payout->payout_details ?? [] as $key => $value)
                            <div>
                                <span class="text-neutral-500">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                <span class="text-neutral-800 font-medium ml-2">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-ui.card>
            @endif

            <!-- Your Notes -->
            @if($payout->adiutor_notes)
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-neutral-800">Your Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-neutral-700">{{ $payout->adiutor_notes }}</p>
                </div>
            </x-ui.card>
            @endif
        </div>
    </div>
</div>
@endsection

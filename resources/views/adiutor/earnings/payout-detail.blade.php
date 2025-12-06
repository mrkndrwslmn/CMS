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
                @elseif($payout->status === 'approved')
                    <x-ui.badge variant="info">
                        <x-lucide-check-circle class="w-4 h-4" />
                        Approved
                    </x-ui.badge>
                @elseif($payout->status === 'rejected')
                    <x-ui.badge variant="error">
                        <x-lucide-x-circle class="w-4 h-4" />
                        Rejected
                    </x-ui.badge>
                @else
                    <x-ui.badge variant="warning">
                        <x-lucide-clock class="w-4 h-4" />
                        Pending Review
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
                    <h2 class="text-lg font-semibold text-neutral-800">Time Entries Included</h2>
                    <p class="text-sm text-neutral-500 mt-1">{{ $payout->items->count() }} entries</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-neutral-50 border-b border-neutral-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Task / Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-100">
                            @foreach($payout->items as $item)
                            <tr class="hover:bg-neutral-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                                    @if($item->timeEntry)
                                        {{ $item->timeEntry->start_time->format('M d, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-neutral-800">{{ $item->description }}</div>
                                    @if($item->task)
                                        <div class="text-xs text-neutral-500">{{ $item->task->project->title ?? 'N/A' }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                                    {{ number_format($item->hours ?? 0, 2) }}h
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                                    ₱{{ number_format($item->rate ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-neutral-800">
                                    ₱{{ number_format($item->amount, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-neutral-50 border-t-2 border-neutral-200">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-sm font-semibold text-neutral-800">Total</td>
                                <td class="px-6 py-4 text-sm font-semibold text-neutral-800">
                                    {{ number_format($payout->items->sum('hours'), 2) }}h
                                </td>
                                <td class="px-6 py-4"></td>
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

            <!-- Rejection Reason -->
            @if($payout->status === 'rejected' && $payout->rejection_reason)
            <div class="bg-error-50 border border-error-100 rounded-2xl p-6">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-error-100 rounded-lg">
                        <x-lucide-x-circle class="w-5 h-5 text-error-600" />
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-error-800 mb-2">Rejection Reason</h3>
                        <p class="text-sm text-error-700">{{ $payout->rejection_reason }}</p>
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
                        <p class="text-xs text-neutral-500 uppercase tracking-wider mb-1">Total Hours</p>
                        <p class="text-sm text-neutral-800">{{ number_format($payout->items->sum('hours'), 2) }} hours</p>
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

                        @if($payout->reviewed_at)
                        <!-- Reviewed -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full {{ $payout->status === 'rejected' ? 'bg-error-100' : 'bg-success-100' }} flex items-center justify-center">
                                @if($payout->status === 'rejected')
                                    <x-lucide-x-circle class="w-4 h-4 text-error-600" />
                                @else
                                    <x-lucide-check-circle-2 class="w-4 h-4 text-success-600" />
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-800">{{ $payout->status === 'rejected' ? 'Rejected' : 'Approved' }}</p>
                                <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($payout->reviewed_at)->format('M d, Y g:i A') }}</p>
                                @if($payout->processedBy)
                                    <p class="text-xs text-neutral-500">by {{ $payout->processedBy->fullName }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($payout->paid_at)
                        <!-- Paid -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-success-100 flex items-center justify-center">
                                <x-lucide-banknote class="w-4 h-4 text-success-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-800">Paid</p>
                                <p class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($payout->paid_at)->format('M d, Y g:i A') }}</p>
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
                        @foreach(json_decode($payout->payout_details, true) ?? [] as $key => $value)
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

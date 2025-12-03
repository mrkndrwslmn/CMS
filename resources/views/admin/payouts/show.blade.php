@extends('admin.layouts.app')

@section('title', 'Payout Details - ' . $payout->payout_number)

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Payouts', 'route' => 'admin.payouts.index', 'icon' => 'wallet'],
        ['label' => $payout->payout_number, 'icon' => 'file-text'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <x-ui.page-header 
                title="Payout {{ $payout->payout_number }}" 
                :description="'Requested on ' . $payout->requested_at->format('F d, Y \a\t g:i A')"
            />
        </div>
        <div class="flex-shrink-0">
            @if($payout->status === 'paid')
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium bg-success-100 text-success-700">
                    <x-lucide-check-circle class="w-4 h-4" />
                    Paid
                </span>
            @elseif($payout->status === 'approved')
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium bg-primary-100 text-primary-700">
                    <x-lucide-check-circle class="w-4 h-4" />
                    Approved
                </span>
            @elseif($payout->status === 'rejected')
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium bg-error-100 text-error-700">
                    <x-lucide-x-circle class="w-4 h-4" />
                    Rejected
                </span>
            @else
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium bg-warning-100 text-warning-700">
                    <x-lucide-clock class="w-4 h-4" />
                    Pending Review
                </span>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-success-50 border border-success-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <x-lucide-check-circle class="w-5 h-5 text-success-500 flex-shrink-0" />
                <p class="text-sm text-success-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-error-50 border border-error-200 rounded-xl p-4">
            <div class="flex gap-3">
                <x-lucide-x-circle class="w-5 h-5 text-error-500 flex-shrink-0" />
                <ul class="text-sm text-error-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Action Buttons -->
            @if($payout->status === 'pending')
            <x-ui.card class="p-6">
                <h3 class="text-base font-medium text-neutral-700 mb-4">Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <x-ui.button type="button" variant="primary" onclick="showApproveModal()">
                        <x-lucide-check-circle class="w-4 h-4" />
                        Approve & Mark as Paid
                    </x-ui.button>
                    <x-ui.button type="button" variant="danger" onclick="showRejectModal()">
                        <x-lucide-x-circle class="w-4 h-4" />
                        Reject Request
                    </x-ui.button>
                </div>
            </x-ui.card>
            @endif

            <!-- Adiutor Information -->
            <x-ui.card class="overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                    <h3 class="text-base font-medium text-neutral-700">Adiutor Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <img src="{{ $payout->adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($payout->adiutor->fullName) }}" 
                             alt="{{ $payout->adiutor->fullName }}" 
                             class="w-16 h-16 rounded-full border-2 border-neutral-100">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-neutral-800">{{ $payout->adiutor->fullName }}</h4>
                            <p class="text-sm text-neutral-500">{{ $payout->adiutor->email }}</p>
                            @if($payout->adiutor->adiutorProfile)
                                <div class="mt-3 text-sm text-neutral-600 space-y-1">
                                    <p><span class="font-medium">Standard Rate:</span> ₱{{ number_format($payout->adiutor->adiutorProfile->standard_hourly_rate ?? 0, 2) }}/hr</p>
                                    <p><span class="font-medium">Preferred Method:</span> {{ ucwords(str_replace('_', ' ', $payout->adiutor->adiutorProfile->preferred_payout_method ?? 'Not set')) }}</p>
                                </div>
                            @endif
                            <a href="{{ route('admin.payouts.adiutor-earnings', $payout->adiutor_id) }}" 
                               class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-700 text-sm font-medium mt-3 transition-colors">
                                View All Earnings
                                <x-lucide-arrow-right class="w-4 h-4" />
                            </a>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <!-- Payout Items -->
            <x-ui.card class="overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                    <h3 class="text-base font-medium text-neutral-700">Time Entries Included</h3>
                    <p class="text-sm text-neutral-400 mt-1">{{ $payout->items->count() }} entries</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-neutral-50 border-b border-neutral-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Task / Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            @foreach($payout->items as $item)
                            <tr class="hover:bg-neutral-50 transition-colors">
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
                                        <div class="text-xs text-neutral-400">{{ $item->task->project->title ?? 'N/A' }}</div>
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
            </x-ui.card>

            <!-- Adiutor Notes -->
            @if($payout->adiutor_notes)
            <div class="bg-primary-50 border border-primary-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <x-lucide-info class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" />
                    <div>
                        <h4 class="text-sm font-medium text-primary-900 mb-2">Adiutor's Notes</h4>
                        <p class="text-sm text-primary-800">{{ $payout->adiutor_notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Summary -->
            <x-ui.card class="overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                    <h3 class="text-base font-medium text-neutral-700">Summary</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-neutral-400 uppercase tracking-wider mb-1">Amount</p>
                        <p class="text-2xl font-semibold text-neutral-800">₱{{ number_format($payout->amount, 2) }}</p>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400 uppercase tracking-wider mb-1">Period</p>
                        <p class="text-sm text-neutral-700">
                            {{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} -<br>
                            {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}
                        </p>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400 uppercase tracking-wider mb-1">Total Hours</p>
                        <p class="text-sm text-neutral-700">{{ number_format($payout->items->sum('hours'), 2) }} hours</p>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400 uppercase tracking-wider mb-1">Payment Method</p>
                        <p class="text-sm text-neutral-700">{{ ucwords(str_replace('_', ' ', $payout->payout_method ?? 'Not specified')) }}</p>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400 uppercase tracking-wider mb-1">Currency</p>
                        <p class="text-sm text-neutral-700">{{ $payout->currency ?? 'PHP' }}</p>
                    </div>

                    @if($payout->reference_number)
                    <div class="pt-4 border-t border-neutral-100">
                        <p class="text-xs text-neutral-400 uppercase tracking-wider mb-1">Reference Number</p>
                        <p class="text-sm text-neutral-700 font-mono">{{ $payout->reference_number }}</p>
                    </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Timeline -->
            <x-ui.card class="overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                    <h3 class="text-base font-medium text-neutral-700">Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Requested -->
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                <x-lucide-clock class="w-4 h-4 text-primary-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-800">Requested</p>
                                <p class="text-xs text-neutral-400">{{ $payout->requested_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>

                        @if($payout->reviewed_at)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $payout->status === 'rejected' ? 'bg-error-100' : 'bg-success-100' }} flex items-center justify-center">
                                @if($payout->status === 'rejected')
                                    <x-lucide-x class="w-4 h-4 text-error-600" />
                                @else
                                    <x-lucide-check class="w-4 h-4 text-success-600" />
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-800">{{ $payout->status === 'rejected' ? 'Rejected' : 'Approved' }}</p>
                                <p class="text-xs text-neutral-400">{{ \Carbon\Carbon::parse($payout->reviewed_at)->format('M d, Y g:i A') }}</p>
                                @if($payout->processedBy)
                                    <p class="text-xs text-neutral-400">by {{ $payout->processedBy->fullName }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($payout->paid_at)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-success-100 flex items-center justify-center">
                                <x-lucide-banknote class="w-4 h-4 text-success-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-800">Paid</p>
                                <p class="text-xs text-neutral-400">{{ \Carbon\Carbon::parse($payout->paid_at)->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </x-ui.card>

            <!-- Payment Details -->
            @if($payout->payout_details)
            <x-ui.card class="overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                    <h3 class="text-base font-medium text-neutral-700">Payment Details</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2 text-sm">
                        @foreach(json_decode($payout->payout_details, true) ?? [] as $key => $value)
                            <div>
                                <span class="text-neutral-500">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                <span class="text-neutral-700 font-medium ml-2">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-ui.card>
            @endif

            <!-- Admin Notes -->
            @if($payout->admin_notes)
            <x-ui.card class="overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50">
                    <h3 class="text-base font-medium text-neutral-700">Admin Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-neutral-600">{{ $payout->admin_notes }}</p>
                    @if($payout->processedBy)
                        <p class="text-xs text-neutral-400 mt-2">- {{ $payout->processedBy->fullName }}</p>
                    @endif
                </div>
            </x-ui.card>
            @endif

            <!-- Rejection Reason -->
            @if($payout->status === 'rejected' && $payout->rejection_reason)
            <div class="bg-error-50 border border-error-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <x-lucide-x-circle class="w-5 h-5 text-error-500 flex-shrink-0 mt-0.5" />
                    <div>
                        <h4 class="text-sm font-medium text-error-900 mb-2">Rejection Reason</h4>
                        <p class="text-sm text-error-800">{{ $payout->rejection_reason }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-lg">
        <form action="{{ route('admin.payouts.complete', $payout->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-6 py-4 border-b border-neutral-100 flex items-center gap-3">
                <div class="p-2 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
                <h3 class="text-lg font-semibold text-neutral-800">Approve & Process Payout</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-neutral-700 mb-1.5">
                        Reference Number <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="reference_number" name="reference_number" required
                           placeholder="e.g., TXN-2024-001234"
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>

                <div>
                    <label for="proof_of_payment" class="block text-sm font-medium text-neutral-700 mb-1.5">
                        Proof of Payment (Optional)
                    </label>
                    <input type="file" id="proof_of_payment" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                    <p class="text-xs text-neutral-400 mt-1">Accepted: JPG, PNG, PDF (Max 5MB)</p>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-neutral-700 mb-1.5">
                        Admin Notes (Optional)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              placeholder="Add any notes about this payout..."
                              class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors"></textarea>
                </div>

                <div class="bg-success-50 border border-success-200 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-sm text-success-800">
                        <x-lucide-info class="w-4 h-4 text-success-500 flex-shrink-0" />
                        <span>This will mark all time entries as paid and notify the adiutor.</span>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-neutral-100 flex justify-end gap-3">
                <x-ui.button type="button" variant="ghost" onclick="hideApproveModal()">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-check-circle class="w-4 h-4" />
                    Approve & Mark as Paid
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-lg">
        <form action="{{ route('admin.payouts.cancel', $payout->id) }}" method="POST">
            @csrf
            <div class="px-6 py-4 border-b border-neutral-100 flex items-center gap-3">
                <div class="p-2 bg-error-50 rounded-xl">
                    <x-lucide-x-circle class="w-5 h-5 text-error-500" />
                </div>
                <h3 class="text-lg font-semibold text-neutral-800">Reject Payout Request</h3>
            </div>
            <div class="p-6">
                <div>
                    <label for="reason" class="block text-sm font-medium text-neutral-700 mb-1.5">
                        Reason for Rejection <span class="text-error-500">*</span>
                    </label>
                    <textarea id="reason" name="reason" rows="4" required
                              placeholder="Explain why this payout request is being rejected..."
                              class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors"></textarea>
                </div>

                <div class="bg-error-50 border border-error-200 rounded-xl p-4 mt-4">
                    <div class="flex items-center gap-2 text-sm text-error-800">
                        <x-lucide-alert-triangle class="w-4 h-4 text-error-500 flex-shrink-0" />
                        <span>This will cancel the payout request and notify the adiutor.</span>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-neutral-100 flex justify-end gap-3">
                <x-ui.button type="button" variant="ghost" onclick="hideRejectModal()">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="danger">
                    <x-lucide-x-circle class="w-4 h-4" />
                    Reject Request
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<script>
function showApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function hideApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modals on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideApproveModal();
        hideRejectModal();
    }
});

// Close modals on backdrop click
document.getElementById('approveModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        hideApproveModal();
    }
});

document.getElementById('rejectModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        hideRejectModal();
    }
});
</script>
@endsection

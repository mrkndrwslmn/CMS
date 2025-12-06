@extends('admin.layouts.app')

@section('title', 'Withdrawal Details - Admin Dashboard')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Referrals', 'route' => 'admin.referrals.index', 'icon' => 'gift'],
        ['label' => 'Pending Withdrawals', 'route' => 'admin.referrals.withdrawals.pending', 'icon' => 'clock'],
        ['label' => $withdrawal->withdrawal_number, 'icon' => 'file-text'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800 mb-1">Withdrawal {{ $withdrawal->withdrawal_number }}</h1>
            <p class="text-neutral-500">Review withdrawal request details</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <x-ui.button variant="secondary" href="{{ route('admin.referrals.withdrawals.pending') }}">
                <x-lucide-arrow-left class="w-4 h-4" />
                Back to Pending
            </x-ui.button>
            @if(in_array($withdrawal->status, ['pending', 'processing']))
            <x-ui.button variant="success" onclick="completeWithdrawal()">
                <x-lucide-check-circle class="w-4 h-4" />
                Complete
            </x-ui.button>
            <x-ui.button variant="danger" onclick="rejectWithdrawal()">
                <x-lucide-x-circle class="w-4 h-4" />
                Reject
            </x-ui.button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Withdrawal Info -->
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <x-lucide-credit-card class="w-5 h-5 text-primary-500" />
                    Withdrawal Details
                </h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-neutral-500">Withdrawal Number</dt>
                        <dd class="font-medium text-neutral-800">
                            <code class="px-2 py-1 bg-neutral-100 rounded">{{ $withdrawal->withdrawal_number }}</code>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-neutral-500">Status</dt>
                        <dd>
                            @switch($withdrawal->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                        <x-lucide-clock class="w-3 h-3 mr-1" />
                                        Pending
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-700">
                                        <x-lucide-loader class="w-3 h-3 mr-1" />
                                        Processing
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                        <x-lucide-check-circle class="w-3 h-3 mr-1" />
                                        Completed
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-700">
                                        <x-lucide-x-circle class="w-3 h-3 mr-1" />
                                        Rejected
                                    </span>
                                    @break
                            @endswitch
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-neutral-500">Amount</dt>
                        <dd class="text-xl font-semibold text-neutral-800">₱{{ number_format($withdrawal->amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-neutral-500">Withdrawal Method</dt>
                        <dd class="font-medium text-neutral-800 capitalize">{{ str_replace('_', ' ', $withdrawal->withdrawal_method) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-neutral-500">Requested At</dt>
                        <dd class="text-neutral-800">{{ $withdrawal->requested_at?->format('M d, Y h:i A') ?? $withdrawal->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @if($withdrawal->completed_at)
                    <div>
                        <dt class="text-sm text-neutral-500">Completed At</dt>
                        <dd class="text-neutral-800">{{ $withdrawal->completed_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @endif
                </dl>
            </x-ui.card>

            <!-- Payment Details -->
            @if($withdrawal->withdrawal_details)
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <x-lucide-wallet class="w-5 h-5 text-primary-500" />
                    Payment Details
                </h3>
                <dl class="space-y-3">
                    @foreach($withdrawal->withdrawal_details as $key => $value)
                    <div class="flex justify-between py-2 border-b border-neutral-100 last:border-0">
                        <dt class="text-sm text-neutral-500 capitalize">{{ str_replace('_', ' ', $key) }}</dt>
                        <dd class="font-medium text-neutral-800">{{ $value }}</dd>
                    </div>
                    @endforeach
                </dl>
            </x-ui.card>
            @endif

            <!-- User Notes -->
            @if($withdrawal->user_notes)
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <x-lucide-message-square class="w-5 h-5 text-primary-500" />
                    User Notes
                </h3>
                <p class="text-neutral-700">{{ $withdrawal->user_notes }}</p>
            </x-ui.card>
            @endif

            <!-- Admin Notes / Rejection -->
            @if($withdrawal->admin_notes || $withdrawal->rejection_reason)
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <x-lucide-clipboard class="w-5 h-5 text-primary-500" />
                    Admin Notes
                </h3>
                @if($withdrawal->rejection_reason)
                <div class="p-4 bg-error-50 border border-error-200 rounded-xl mb-4">
                    <p class="text-sm font-medium text-error-800 mb-1">Rejection Reason:</p>
                    <p class="text-error-700">{{ $withdrawal->rejection_reason }}</p>
                </div>
                @endif
                @if($withdrawal->admin_notes)
                <p class="text-neutral-700">{{ $withdrawal->admin_notes }}</p>
                @endif
            </x-ui.card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <x-lucide-user class="w-5 h-5 text-primary-500" />
                    Requester
                </h3>
                <div class="text-center">
                    @if($withdrawal->user->profilePic)
                        <img src="{{ $withdrawal->user->getProfilePictureUrl() }}" 
                             alt="{{ $withdrawal->user->fullName }}" 
                             class="w-16 h-16 rounded-full object-cover mx-auto mb-3">
                    @else
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl font-semibold text-primary-700">{{ strtoupper(substr($withdrawal->user->fullName, 0, 1)) }}</span>
                        </div>
                    @endif
                    <p class="font-medium text-neutral-800">{{ $withdrawal->user->fullName }}</p>
                    <p class="text-sm text-neutral-500">{{ $withdrawal->user->email }}</p>
                </div>
            </x-ui.card>

            <!-- Processing Info -->
            @if($withdrawal->processedBy)
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <x-lucide-user-check class="w-5 h-5 text-primary-500" />
                    Processed By
                </h3>
                <div>
                    <p class="font-medium text-neutral-800">{{ $withdrawal->processedBy->fullName }}</p>
                    <p class="text-sm text-neutral-500">{{ $withdrawal->processed_at?->format('M d, Y h:i A') }}</p>
                </div>
            </x-ui.card>
            @endif

            <!-- Reference Info -->
            @if($withdrawal->reference_number || $withdrawal->proof_of_payment)
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                    <x-lucide-hash class="w-5 h-5 text-primary-500" />
                    Transaction Info
                </h3>
                @if($withdrawal->reference_number)
                <div class="mb-3">
                    <p class="text-sm text-neutral-500">Reference Number</p>
                    <code class="text-sm bg-neutral-100 px-2 py-1 rounded">{{ $withdrawal->reference_number }}</code>
                </div>
                @endif
                @if($withdrawal->proof_of_payment)
                <div>
                    <p class="text-sm text-neutral-500 mb-2">Proof of Payment</p>
                    <a href="{{ Storage::url($withdrawal->proof_of_payment) }}" target="_blank" 
                       class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700">
                        <x-lucide-file-image class="w-4 h-4" />
                        View Proof
                    </a>
                </div>
                @endif
            </x-ui.card>
            @endif
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-neutral-900/50" onclick="closeCompleteModal()"></div>
        <div class="relative z-10 w-full max-w-md p-6 mx-auto bg-white rounded-2xl shadow-xl">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4">Complete Withdrawal</h3>
            <form id="completeForm" onsubmit="submitComplete(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Reference Number (Optional)</label>
                        <input type="text" id="reference_number" name="reference_number" 
                               class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Enter transaction reference">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Admin Notes (Optional)</label>
                        <textarea id="complete_admin_notes" name="admin_notes" rows="2"
                                  class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="Add any notes..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeCompleteModal()" class="px-4 py-2 text-neutral-700 bg-neutral-100 hover:bg-neutral-200 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-white bg-success-600 hover:bg-success-700 rounded-xl transition-colors">
                        Complete Withdrawal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-neutral-900/50" onclick="closeRejectModal()"></div>
        <div class="relative z-10 w-full max-w-md p-6 mx-auto bg-white rounded-2xl shadow-xl">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4">Reject Withdrawal</h3>
            <form id="rejectForm" onsubmit="submitReject(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Rejection Reason <span class="text-error-500">*</span></label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                                  class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                  placeholder="Explain why this withdrawal is being rejected..."></textarea>
                    </div>
                    <div class="p-3 bg-warning-50 border border-warning-200 rounded-xl">
                        <div class="flex items-start gap-2">
                            <x-lucide-alert-triangle class="w-5 h-5 text-warning-600 flex-shrink-0 mt-0.5" />
                            <p class="text-sm text-warning-800">Credits will be refunded to the user's balance upon rejection.</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-neutral-700 bg-neutral-100 hover:bg-neutral-200 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-white bg-error-600 hover:bg-error-700 rounded-xl transition-colors">
                        Reject Withdrawal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const withdrawalId = {{ $withdrawal->id }};

function completeWithdrawal() {
    document.getElementById('completeModal').classList.remove('hidden');
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    document.getElementById('completeForm').reset();
}

function rejectWithdrawal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

async function submitComplete(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('reference_number', document.getElementById('reference_number').value);
    formData.append('admin_notes', document.getElementById('complete_admin_notes').value);
    
    try {
        const response = await fetch(`/admin/referrals/withdrawals/${withdrawalId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.reload();
        } else {
            window.toast.error(data.message || 'Failed to complete withdrawal');
        }
    } catch (error) {
        window.toast.error('An error occurred. Please try again.');
    }
}

async function submitReject(e) {
    e.preventDefault();
    const rejectionReason = document.getElementById('rejection_reason').value;
    
    if (!rejectionReason.trim()) {
        window.toast.warning('Please provide a rejection reason');
        return;
    }
    
    try {
        const response = await fetch(`/admin/referrals/withdrawals/${withdrawalId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                rejection_reason: rejectionReason
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = '{{ route("admin.referrals.withdrawals.pending") }}';
        } else {
            window.toast.error(data.message || 'Failed to reject withdrawal');
        }
    } catch (error) {
        window.toast.error('An error occurred. Please try again.');
    }
}
</script>
@endpush
@endsection

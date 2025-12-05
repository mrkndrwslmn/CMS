@extends('admin.layouts.app')

@section('title', 'Pending Withdrawals - Admin Dashboard')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Referrals', 'route' => 'admin.referrals.index', 'icon' => 'gift'],
        ['label' => 'Pending Withdrawals', 'icon' => 'clock'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800 mb-1">Pending Withdrawals</h1>
            <p class="text-neutral-500">Review and process referral credit withdrawal requests</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <x-ui.button variant="secondary" href="{{ route('admin.referrals.index') }}">
                <x-lucide-arrow-left class="w-4 h-4" />
                Back to Referrals
            </x-ui.button>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Withdrawal #</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Method</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Requested</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($withdrawals as $withdrawal)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3">
                            <code class="px-2 py-1 bg-neutral-100 text-neutral-700 rounded text-sm font-mono">{{ $withdrawal->withdrawal_number }}</code>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-neutral-800">{{ $withdrawal->user->fullName }}</p>
                                <p class="text-xs text-neutral-500">{{ $withdrawal->user->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-neutral-800">₱{{ number_format($withdrawal->amount, 2) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700 capitalize">
                                {{ str_replace('_', ' ', $withdrawal->withdrawal_method) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm">
                                <p class="text-neutral-800">{{ $withdrawal->requested_at?->format('M d, Y') ?? $withdrawal->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-neutral-500">{{ $withdrawal->requested_at?->diffForHumans() ?? $withdrawal->created_at->diffForHumans() }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                <x-lucide-clock class="w-3 h-3 mr-1" />
                                Pending
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.referrals.withdrawals.show', $withdrawal) }}" 
                                   class="p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors"
                                   title="View Details">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                <button type="button" 
                                        onclick="completeWithdrawal({{ $withdrawal->id }})"
                                        class="p-2 text-success-600 hover:text-success-700 hover:bg-success-50 rounded-lg transition-colors"
                                        title="Complete">
                                    <x-lucide-check-circle class="w-4 h-4" />
                                </button>
                                <button type="button" 
                                        onclick="rejectWithdrawal({{ $withdrawal->id }})"
                                        class="p-2 text-error-600 hover:text-error-700 hover:bg-error-50 rounded-lg transition-colors"
                                        title="Reject">
                                    <x-lucide-x-circle class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="w-16 h-16 bg-success-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-lucide-check-circle class="w-8 h-8 text-success-500" />
                            </div>
                            <p class="text-neutral-600 font-medium mb-1">No pending withdrawals</p>
                            <p class="text-neutral-500 text-sm">All withdrawal requests have been processed.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($withdrawals->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </x-ui.card>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-neutral-900/50" onclick="closeCompleteModal()"></div>
        <div class="relative z-10 w-full max-w-md p-6 mx-auto bg-white rounded-2xl shadow-xl">
            <h3 class="text-lg font-semibold text-neutral-800 mb-4">Complete Withdrawal</h3>
            <form id="completeForm" onsubmit="submitComplete(event)">
                <input type="hidden" id="complete_withdrawal_id" value="">
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
                <input type="hidden" id="reject_withdrawal_id" value="">
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
function completeWithdrawal(id) {
    document.getElementById('complete_withdrawal_id').value = id;
    document.getElementById('completeModal').classList.remove('hidden');
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    document.getElementById('completeForm').reset();
}

function rejectWithdrawal(id) {
    document.getElementById('reject_withdrawal_id').value = id;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

async function submitComplete(e) {
    e.preventDefault();
    const id = document.getElementById('complete_withdrawal_id').value;
    const formData = new FormData();
    formData.append('reference_number', document.getElementById('reference_number').value);
    formData.append('admin_notes', document.getElementById('complete_admin_notes').value);
    
    try {
        const response = await fetch(`/admin/referrals/withdrawals/${id}/complete`, {
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
    const id = document.getElementById('reject_withdrawal_id').value;
    const rejectionReason = document.getElementById('rejection_reason').value;
    
    if (!rejectionReason.trim()) {
        window.toast.warning('Please provide a rejection reason');
        return;
    }
    
    try {
        const response = await fetch(`/admin/referrals/withdrawals/${id}/reject`, {
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
            window.location.reload();
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

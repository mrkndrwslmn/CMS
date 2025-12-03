@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Payments', 'route' => 'admin.payments.index', 'icon' => 'credit-card'],
        ['label' => 'Payment #' . $payment->id, 'icon' => 'file-text'],
    ]" class="mb-6" />

    <!-- Header -->
    <x-ui.page-header 
        title="Payment Details" 
        description="View and manage payment information"
        class="mb-6"
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Payment Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Status Card -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-800">Payment #{{ $payment->id }}</h2>
                        <p class="text-sm text-neutral-500 mt-1">{{ $payment->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($payment->status == 'confirmed') bg-success-100 text-success-700
                        @elseif($payment->status == 'pending') bg-warning-100 text-warning-700
                        @elseif($payment->status == 'failed') bg-error-100 text-error-700
                        @elseif($payment->status == 'refunded') bg-primary-100 text-primary-700
                        @else bg-neutral-100 text-neutral-600
                        @endif">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-neutral-500">Amount</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500">Payment Method</p>
                        <p class="text-lg font-semibold text-neutral-800 mt-1 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</p>
                    </div>
                </div>
            </x-ui.card>

            <!-- Transaction Details -->
            <x-ui.card class="p-6">
                <h3 class="text-base font-medium text-neutral-700 mb-4">Transaction Details</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-neutral-500">Payment Reference</dt>
                        <dd class="text-sm font-medium text-neutral-700">{{ $payment->payment_reference }}</dd>
                    </div>
                    @if($payment->transaction_id)
                    <div class="flex justify-between">
                        <dt class="text-sm text-neutral-500">Transaction ID</dt>
                        <dd class="text-sm font-medium text-neutral-700">{{ $payment->transaction_id }}</dd>
                    </div>
                    @endif
                    @if($payment->gateway_fee)
                    <div class="flex justify-between">
                        <dt class="text-sm text-neutral-500">Gateway Fee</dt>
                        <dd class="text-sm font-medium text-neutral-700">₱{{ number_format($payment->gateway_fee, 2) }}</dd>
                    </div>
                    @endif
                    @if($payment->confirmed_at)
                    <div class="flex justify-between">
                        <dt class="text-sm text-neutral-500">Confirmed At</dt>
                        <dd class="text-sm font-medium text-neutral-700">{{ $payment->confirmed_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @endif
                    @if($payment->confirmedBy)
                    <div class="flex justify-between">
                        <dt class="text-sm text-neutral-500">Confirmed By</dt>
                        <dd class="text-sm font-medium text-neutral-700">{{ $payment->confirmedBy->fullName }}</dd>
                    </div>
                    @endif
                </dl>
            </x-ui.card>

            <!-- Project Information -->
            <x-ui.card class="p-6">
                <h3 class="text-base font-medium text-neutral-700 mb-4">Project Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-neutral-500">Project Name</dt>
                        <dd class="text-sm font-medium text-neutral-700 mt-1">{{ $payment->serviceRequest->project_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-neutral-500">Client</dt>
                        <dd class="text-sm font-medium text-neutral-700 mt-1">{{ $payment->serviceRequest->client->fullName }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-neutral-500">Service Request ID</dt>
                        <dd class="text-sm font-medium text-neutral-700 mt-1">#{{ $payment->service_request_id }}</dd>
                    </div>
                </dl>
                <div class="mt-4">
                    <a href="{{ route('admin.requests.show', $payment->service_request_id) }}" 
                       class="inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                        View Service Request
                        <x-lucide-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </x-ui.card>

            <!-- Notes -->
            @if($payment->notes)
            <x-ui.card class="p-6">
                <h3 class="text-base font-medium text-neutral-700 mb-4">Notes</h3>
                <p class="text-sm text-neutral-600 whitespace-pre-wrap">{{ $payment->notes }}</p>
            </x-ui.card>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <x-ui.card class="p-6">
                <h3 class="text-base font-medium text-neutral-700 mb-4">Actions</h3>
                <div class="space-y-3">
                    @if($payment->status === 'pending')
                    <form method="POST" action="{{ route('admin.payments.update-status', $payment->id) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                            <x-lucide-check-circle class="w-4 h-4" />
                            Confirm Payment
                        </x-ui.button>
                    </form>
                    @endif

                    @if($payment->status === 'confirmed')
                    <x-ui.button 
                        type="button"
                        variant="warning"
                        class="w-full justify-center"
                        onclick="document.getElementById('refundModal').classList.remove('hidden')">
                        <x-lucide-rotate-ccw class="w-4 h-4" />
                        Issue Refund
                    </x-ui.button>
                    @endif

                    <x-ui.button 
                        type="button"
                        variant="secondary"
                        class="w-full justify-center"
                        onclick="document.getElementById('notesModal').classList.remove('hidden')">
                        <x-lucide-file-plus class="w-4 h-4" />
                        Add Note
                    </x-ui.button>
                </div>
            </x-ui.card>

            <!-- Payment Gateway Details -->
            @if($payment->payment_details)
            <x-ui.card class="p-6">
                <h3 class="text-base font-medium text-neutral-700 mb-4">Gateway Details</h3>
                <pre class="text-xs bg-neutral-50 p-3 rounded-lg overflow-x-auto text-neutral-600">{{ json_encode($payment->payment_details, JSON_PRETTY_PRINT) }}</pre>
            </x-ui.card>
            @endif
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="hidden fixed inset-0 bg-neutral-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto max-w-md p-6 bg-white rounded-2xl shadow-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-warning-50 rounded-xl">
                <x-lucide-rotate-ccw class="w-5 h-5 text-warning-500" />
            </div>
            <h3 class="text-lg font-semibold text-neutral-800">Issue Refund</h3>
        </div>
        <form method="POST" action="{{ route('admin.payments.update-status', $payment->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="refunded">
            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Refund Reason</label>
                <textarea name="notes" rows="3" required
                          class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors"
                          placeholder="Enter reason for refund..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <x-ui.button type="button" variant="ghost" onclick="document.getElementById('refundModal').classList.add('hidden')">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="warning">
                    <x-lucide-rotate-ccw class="w-4 h-4" />
                    Issue Refund
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Notes Modal -->
<div id="notesModal" class="hidden fixed inset-0 bg-neutral-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto max-w-md p-6 bg-white rounded-2xl shadow-lg">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-primary-50 rounded-xl">
                <x-lucide-file-plus class="w-5 h-5 text-primary-500" />
            </div>
            <h3 class="text-lg font-semibold text-neutral-800">Add Note</h3>
        </div>
        <form method="POST" action="{{ route('admin.payments.update-status', $payment->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $payment->status }}">
            <div class="mb-4">
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Note</label>
                <textarea name="notes" rows="3" required
                          class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors"
                          placeholder="Enter your note..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <x-ui.button type="button" variant="ghost" onclick="document.getElementById('notesModal').classList.add('hidden')">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-plus class="w-4 h-4" />
                    Add Note
                </x-ui.button>
            </div>
        </form>
    </div>
</div>
@endsection

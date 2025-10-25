@extends('admin.layouts.app')

@section('title', 'Payment Details')
@section('page-title', 'Payment Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.payments.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Payments
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Payment Details</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Payment Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Payment #{{ $payment->id }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $payment->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full
                        @if($payment->status == 'confirmed') bg-green-100 text-green-800
                        @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($payment->status == 'failed') bg-red-100 text-red-800
                        @elseif($payment->status == 'refunded') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Amount</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment Method</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</p>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Payment Reference</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $payment->payment_reference }}</dd>
                    </div>
                    @if($payment->transaction_id)
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Transaction ID</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $payment->transaction_id }}</dd>
                    </div>
                    @endif
                    @if($payment->gateway_fee)
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Gateway Fee</dt>
                        <dd class="text-sm font-medium text-gray-900">₱{{ number_format($payment->gateway_fee, 2) }}</dd>
                    </div>
                    @endif
                    @if($payment->confirmed_at)
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Confirmed At</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $payment->confirmed_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    @endif
                    @if($payment->confirmedBy)
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Confirmed By</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $payment->confirmedBy->fullName }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Project Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500">Project Name</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-1">{{ $payment->serviceRequest->project_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Client</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-1">{{ $payment->serviceRequest->client->fullName }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Service Request ID</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-1">#{{ $payment->service_request_id }}</dd>
                    </div>
                </dl>
                <div class="mt-4">
                    <a href="{{ route('admin.requests.show', $payment->service_request_id) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Service Request →
                    </a>
                </div>
            </div>

            <!-- Notes -->
            @if($payment->notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $payment->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    @if($payment->status === 'pending')
                    <form method="POST" action="{{ route('admin.payments.update-status', $payment->id) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Confirm Payment
                        </button>
                    </form>
                    @endif

                    @if($payment->status === 'confirmed')
                    <button onclick="document.getElementById('refundModal').classList.remove('hidden')"
                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Issue Refund
                    </button>
                    @endif

                    <button onclick="document.getElementById('notesModal').classList.remove('hidden')"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Add Note
                    </button>
                </div>
            </div>

            <!-- Payment Gateway Details -->
            @if($payment->payment_details)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gateway Details</h3>
                <pre class="text-xs bg-gray-50 p-3 rounded overflow-x-auto">{{ json_encode($payment->payment_details, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Issue Refund</h3>
        <form method="POST" action="{{ route('admin.payments.update-status', $payment->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="refunded">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Refund Reason</label>
                <textarea name="notes" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Enter reason for refund..."></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('refundModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                    Issue Refund
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Notes Modal -->
<div id="notesModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Note</h3>
        <form method="POST" action="{{ route('admin.payments.update-status', $payment->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $payment->status }}">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                <textarea name="notes" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Enter your note..."></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('notesModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Add Note
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

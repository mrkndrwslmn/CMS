@extends('client.layout')

@section('title', 'Service Request Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('client.requests') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Service Requests
        </a>
    </div>

    <!-- Request Header -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-neutral-900 mb-2">{{ $request->project_name }}</h1>
                <p class="text-neutral-600 mb-4">Request #{{ $request->id }}</p>
                
                <div class="flex items-center space-x-4">
                    @php
                        $badgeColor = match($request->status) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'error',
                            'pending_payment' => 'info',
                            'paid' => 'primary',
                            'in_progress' => 'warning',
                            'completed' => 'success',
                            default => 'neutral'
                        };
                        $statusLabel = match($request->status) {
                            'pending' => 'Pending Review',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            'pending_payment' => 'Pending Payment',
                            'paid' => 'Payment Confirmed',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                            default => ucfirst(str_replace('_', ' ', $request->status))
                        };
                    @endphp
                    <span class="badge badge-{{ $badgeColor }}">{{ $statusLabel }}</span>
                    <span class="text-sm text-neutral-500">
                        Created {{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y \a\t g:i A') }}
                    </span>
                </div>
            </div>

            @if($request->status === 'pending')
                <div class="flex space-x-2">
                    <a href="{{ route('client.requests.edit', $request->id) }}" class="btn-secondary">
                        Edit Request
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Description -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h2 class="text-lg font-semibold text-neutral-900 mb-4">Project Description</h2>
                <div class="prose prose-neutral max-w-none">
                    <p class="whitespace-pre-line">{{ $request->request_description }}</p>
                </div>
            </div>

            <!-- Expectations -->
            @if($request->expectations)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">Expectations</h2>
                    <div class="prose prose-neutral max-w-none">
                        <p class="whitespace-pre-line">{{ $request->expectations }}</p>
                    </div>
                </div>
            @endif

            <!-- Additional Notes -->
            @if($request->additional_notes)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">Additional Notes</h2>
                    <div class="prose prose-neutral max-w-none">
                        <p class="whitespace-pre-line">{{ $request->additional_notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Attachments -->
            @if($attachments && count($attachments) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">Attachments</h2>
                    <div class="space-y-3">
                        @foreach($attachments as $attachment)
                            <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-neutral-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-900">{{ $attachment->original_filename }}</p>
                                        <p class="text-xs text-neutral-500">{{ number_format($attachment->file_size / 1024, 1) }} KB</p>
                                    </div>
                                </div>
                                <a href="{{ route('client.requests.attachment.download', [$request->id, $attachment->id]) }}" 
                                   class="btn-secondary btn-sm">
                                    Download
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Status Updates / Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h2 class="text-lg font-semibold text-neutral-900 mb-4">Status Timeline</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-neutral-900">Request Submitted</p>
                            <p class="text-sm text-neutral-500">{{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($request->reviewed_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-{{ $request->status === 'rejected' ? 'error' : 'success' }}-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($request->status === 'rejected')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        @endif
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-neutral-900">
                                    {{ $request->status === 'rejected' ? 'Request Rejected' : 'Request Reviewed' }}
                                </p>
                                <p class="text-sm text-neutral-500">{{ \Carbon\Carbon::parse($request->reviewed_at)->format('M j, Y \a\t g:i A') }}</p>
                                @if($request->status === 'rejected' && $request->rejection_reason)
                                    <p class="text-sm text-error-600 mt-1">{{ $request->rejection_reason }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($request->payment_confirmed_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-success-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-neutral-900">Payment Confirmed</p>
                                <p class="text-sm text-neutral-500">{{ \Carbon\Carbon::parse($request->payment_confirmed_at)->format('M j, Y \a\t g:i A') }}</p>
                                @if($request->payment_reference)
                                    <p class="text-sm text-neutral-600 mt-1">Reference: {{ $request->payment_reference }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Request Details -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Request Details</h3>
                <div class="space-y-4">
                    <!-- Service Type -->
                    <div>
                        <dt class="text-sm font-medium text-neutral-700">Service Type</dt>
                        <dd class="text-sm text-neutral-900 mt-1">{{ ucfirst(str_replace('_', ' ', $request->service_type)) }}</dd>
                    </div>

                    <!-- Contact Method -->
                    <div>
                        <dt class="text-sm font-medium text-neutral-700">Contact Method</dt>
                        <dd class="text-sm text-neutral-900 mt-1">{{ ucfirst($request->contact_method) }}</dd>
                    </div>

                    <!-- Contact Details -->
                    <div>
                        <dt class="text-sm font-medium text-neutral-700">Contact Details</dt>
                        <dd class="text-sm text-neutral-900 mt-1">{{ $request->contact_details }}</dd>
                    </div>

                    <!-- Priority -->
                    <div>
                        <dt class="text-sm font-medium text-neutral-700">Priority</dt>
                        <dd class="text-sm text-neutral-900 mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $request->priority === 'high' ? 'bg-error-100 text-error-800' : 
                                   ($request->priority === 'medium' ? 'bg-warning-100 text-warning-800' : 'bg-success-100 text-success-800') }}">
                                {{ ucfirst($request->priority) }}
                            </span>
                        </dd>
                    </div>

                    <!-- Deadline -->
                    @if($request->deadline)
                        <div>
                            <dt class="text-sm font-medium text-neutral-700">Deadline</dt>
                            <dd class="text-sm text-neutral-900 mt-1">
                                {{ \Carbon\Carbon::parse($request->deadline)->format('M j, Y') }}
                                @if(\Carbon\Carbon::parse($request->deadline)->isPast())
                                    <span class="text-error-600 text-xs">(Past due)</span>
                                @endif
                            </dd>
                        </div>
                    @endif

                    <!-- Budget -->
                    @if($request->estimated_budget)
                        <div>
                            <dt class="text-sm font-medium text-neutral-700">Estimated Budget</dt>
                            <dd class="text-sm text-neutral-900 mt-1">${{ number_format($request->estimated_budget, 2) }}</dd>
                        </div>
                    @endif

                    @if($request->approved_budget)
                        <div>
                            <dt class="text-sm font-medium text-neutral-700">Approved Budget</dt>
                            <dd class="text-sm text-neutral-900 mt-1">${{ number_format($request->approved_budget, 2) }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @if($request->status === 'pending_payment' || $request->status === 'paid')
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Payment Information</h3>
                    <div class="space-y-4">
                        @if($request->payment_method)
                            <div>
                                <dt class="text-sm font-medium text-neutral-700">Payment Method</dt>
                                <dd class="text-sm text-neutral-900 mt-1">{{ ucfirst($request->payment_method) }}</dd>
                            </div>
                        @endif

                        @if($request->payment_due_date)
                            <div>
                                <dt class="text-sm font-medium text-neutral-700">Payment Due</dt>
                                <dd class="text-sm text-neutral-900 mt-1">
                                    {{ \Carbon\Carbon::parse($request->payment_due_date)->format('M j, Y') }}
                                </dd>
                            </div>
                        @endif

                        @if($request->payment_instructions)
                            <div>
                                <dt class="text-sm font-medium text-neutral-700">Payment Instructions</dt>
                                <dd class="text-sm text-neutral-900 mt-1 whitespace-pre-line">{{ $request->payment_instructions }}</dd>
                            </div>
                        @endif

                        @if($request->status === 'pending_payment')
                            <div class="pt-4 border-t">
                                <a href="{{ route('client.requests.show-payment', $request->id) }}" class="btn-primary w-full">
                                    Make Payment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Admin Notes -->
            @if($request->admin_notes)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Admin Notes</h3>
                    <div class="prose prose-neutral prose-sm max-w-none">
                        <p class="whitespace-pre-line">{{ $request->admin_notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
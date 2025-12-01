@extends('client.layouts.app')

@section('title', 'Service Request Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-success-50 border-l-4 border-success-500 p-4 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-success-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-semibold text-success-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="mb-6 bg-error-50 border-l-4 border-error-500 p-4 rounded-r-lg shadow-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-error-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    @if(session('error'))
                        <p class="text-sm font-semibold text-error-800">{{ session('error') }}</p>
                    @endif
                    @if($errors->has('error'))
                        <p class="text-sm font-semibold text-error-800">{{ $errors->first('error') }}</p>
                    @endif
                    @foreach($errors->all() as $error)
                        @if(!$errors->has('error') || $error !== $errors->first('error'))
                            <p class="text-sm text-error-700 mt-1">{{ $error }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('client.requests') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Service Requests
        </a>
    </div>

    <!-- Request Header Card -->
    <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
                <!-- Request ID Badge -->
                <div class="inline-flex items-center space-x-3 mb-3">
                    <span class="text-xs font-mono font-bold text-neutral-500 bg-neutral-100 px-3 py-1.5 rounded-lg">
                        REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    @php
                        $statusConfig = match($request->status) {
                            'pending' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700', 'label' => 'Pending Review'],
                            'approved' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-700', 'label' => 'Approved'],
                            'rejected' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700', 'label' => 'Rejected'],
                            'pending_payment' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-700', 'label' => 'Awaiting Payment'],
                            'paid' => ['bg' => 'bg-primary-500', 'text' => 'text-white', 'label' => 'Payment Confirmed'],
                            'in_progress' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-700', 'label' => 'In Progress'],
                            'completed' => ['bg' => 'bg-primary-500', 'text' => 'text-white', 'label' => 'Completed'],
                            default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700', 'label' => 'Unknown']
                        };
                    @endphp
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                        {{ $statusConfig['label'] }}
                    </span>
                </div>

                <!-- Project Title -->
                <h1 class="text-2xl font-semibold text-neutral-900 mb-3">{{ $request->project_name }}</h1>
                
                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-600">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Created {{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y') }}</span>
                    </div>
                    <span class="text-neutral-300">•</span>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                        </svg>
                        <span>{{ ucfirst(str_replace('_', ' ', $request->service_type)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                @if($project)
                    <!-- View Project Button (shown when project exists) -->
                    <a href="{{ route('client.projects.show', $project->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-2.5 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        View Project
                    </a>
                @endif
                
                @if($request->status === 'pending')
                    <a href="{{ route('client.requests.edit', $request->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-2.5 bg-white border border-neutral-300 text-neutral-700 font-medium rounded-lg hover:bg-neutral-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Request
                    </a>
                @elseif($request->status === 'approved' || $request->status === 'pending_payment')
                    <a href="{{ route('client.maya.checkout', $request->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-2.5 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Pay with Maya
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Key Metrics Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Priority -->
        <div class="bg-white rounded-lg shadow-sm p-5 text-center">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-neutral-100 mb-3">
                <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
            </div>
            <p class="text-xs text-neutral-500 font-medium mb-1">Priority</p>
            <p class="text-base font-semibold text-neutral-900">{{ ucfirst($request->priority) }}</p>
        </div>

        <!-- Budget / Payment Status -->
        @if($request->approved_budget ?? $request->estimated_budget)
            <div class="bg-white rounded-lg shadow-sm p-5 text-center">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-50 mb-3">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                @if($request->approved_budget && $request->payment_type)
                    @php
                        $totalPaid = $request->getTotalPaid();
                        $remainingBalance = $request->getRemainingPaymentBalance();
                        $currentDue = $request->getCurrentPaymentAmountDue();
                    @endphp
                    @if($totalPaid > 0)
                        <!-- Show total paid -->
                        <p class="text-xs text-neutral-500 font-medium mb-1">Total Paid</p>
                        <p class="text-base font-semibold text-primary-600">₱{{ number_format($totalPaid, 0) }}</p>
                        @if($remainingBalance > 0)
                            <p class="text-xs text-neutral-600 font-medium mt-1">₱{{ number_format($remainingBalance, 0) }} remaining</p>
                        @else
                            <p class="text-xs text-primary-600 font-medium mt-1">✓ Fully Paid</p>
                        @endif
                    @elseif($currentDue > 0)
                        <!-- Show current amount due if nothing paid yet -->
                        <p class="text-xs text-neutral-500 font-medium mb-1">Amount Due</p>
                        <p class="text-base font-semibold text-neutral-900">₱{{ number_format($currentDue, 0) }}</p>
                        <p class="text-xs text-neutral-500 mt-1">{{ $request->getCurrentPaymentDescription() }}</p>
                    @else
                        <p class="text-xs text-neutral-500 font-medium mb-1">Approved Budget</p>
                        <p class="text-base font-semibold text-primary-600">₱{{ number_format($request->approved_budget, 0) }}</p>
                    @endif
                @else
                    <p class="text-xs text-neutral-500 font-medium mb-1">{{ $request->approved_budget ? 'Approved' : 'Estimated' }} Budget</p>
                    <p class="text-base font-semibold text-primary-600">₱{{ number_format($request->approved_budget ?? $request->estimated_budget, 0) }}</p>
                @endif
            </div>
        @endif

        <!-- Deadline -->
        @if($request->deadline)
            <div class="bg-white rounded-lg shadow-sm p-5 text-center">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-neutral-100 mb-3">
                    <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-xs text-neutral-500 font-medium mb-1">Deadline</p>
                <p class="text-sm font-semibold text-neutral-900">
                    {{ \Carbon\Carbon::parse($request->deadline)->format('M j, Y') }}
                </p>
                <p class="text-xs text-neutral-500 mt-1">
                    @if(\Carbon\Carbon::parse($request->deadline)->isPast())
                        Past Due
                    @else
                        {{ \Carbon\Carbon::parse($request->deadline)->diffForHumans() }}
                    @endif
                </p>
            </div>
        @endif

        <!-- Applied Coupon -->
        @if($request->applied_coupon_id && $request->appliedCoupon)
            <div class="bg-white rounded-lg shadow-sm p-5 text-center border border-primary-200">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-50 mb-3">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <p class="text-xs text-neutral-500 font-medium mb-1">Coupon Applied</p>
                <p class="text-sm font-semibold text-neutral-900 font-mono">{{ $request->appliedCoupon->code }}</p>
                @if($request->coupon_discount_amount > 0)
                    <p class="text-xs text-primary-600 font-semibold mt-1">-₱{{ number_format($request->coupon_discount_amount, 2) }}</p>
                @endif
                <p class="text-xs text-neutral-600 mt-1">{{ $request->appliedCoupon->getDiscountLabel() }}</p>
                @if(!$request->appliedCoupon->isValid())
                    <p class="text-xs text-neutral-600 mt-1">
                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Expired
                    </p>
                @endif
            </div>
        @endif

        <!-- Loyalty Discount -->
        @if($request->loyalty_discount_amount > 0)
            <div class="bg-white rounded-lg shadow-sm p-5 text-center border border-primary-200">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-50 mb-3">
                    <i class="fas fa-medal text-primary-500"></i>
                </div>
                <p class="text-xs text-neutral-500 font-medium mb-1">Loyalty Discount</p>
                <p class="text-sm font-semibold text-primary-600">-₱{{ number_format($request->loyalty_discount_amount, 2) }}</p>
                @if($request->loyalty_points_used > 0)
                    <p class="text-xs text-neutral-600 mt-1">{{ number_format($request->loyalty_points_used) }} points used</p>
                @endif
            </div>
        @endif

        <!-- Contact Method -->
        <div class="bg-white rounded-lg shadow-sm p-5 text-center">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-neutral-100 mb-3">
                <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-xs text-neutral-500 font-medium mb-1">Contact</p>
            <p class="text-sm font-semibold text-neutral-900">{{ ucfirst($request->contact_method) }}</p>
            <p class="text-xs text-neutral-500 mt-1 truncate">{{ $request->contact_details }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Description -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-neutral-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Project Description
                    </h3>
                    <div class="prose prose-neutral prose-sm max-w-none">
                        <p class="text-neutral-700 leading-relaxed whitespace-pre-line">{{ $request->request_description }}</p>
                    </div>
                </div>
            </div>

            <!-- Expectations -->
            @if($request->expectations)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-neutral-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Project Expectations
                        </h3>
                        <div class="prose prose-neutral prose-sm max-w-none">
                            <p class="text-neutral-700 leading-relaxed whitespace-pre-line">{{ $request->expectations }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Additional Notes -->
            @if($request->additional_notes)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-neutral-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Additional Notes
                        </h3>
                        <div class="prose prose-neutral prose-sm max-w-none">
                            <p class="text-neutral-700 leading-relaxed whitespace-pre-line">{{ $request->additional_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Attachments -->
            @if($attachments && count($attachments) > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-neutral-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Attachments
                            <span class="ml-2 bg-neutral-100 text-neutral-700 px-2 py-1 rounded-full text-xs font-bold">{{ count($attachments) }}</span>
                        </h3>
                        <div class="space-y-3">
                            @foreach($attachments as $attachment)
                                <div class="flex items-center justify-between p-4 border border-neutral-200 rounded-lg hover:border-neutral-300 hover:shadow-sm transition-all">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-10 h-10 bg-neutral-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-neutral-900 truncate">{{ $attachment->original_filename }}</p>
                                            <p class="text-xs text-neutral-500">{{ number_format($attachment->file_size / 1024, 1) }} KB</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('client.requests.attachment.download', [$request->id, $attachment->id]) }}" 
                                       class="ml-4 inline-flex items-center px-4 py-2 border border-neutral-300 text-neutral-700 font-medium rounded-lg hover:bg-neutral-50 hover:text-neutral-900 transition-all text-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status Timeline -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-neutral-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Request Timeline
                    </h3>
                    <div class="relative space-y-6">
                        <!-- Timeline Line -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-neutral-200"></div>

                        <!-- Request Submitted -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-neutral-700 rounded-full flex items-center justify-center shadow-md ring-4 ring-white">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 border border-neutral-200 rounded-lg p-4 flex-1 bg-white">
                                <p class="text-sm font-bold text-neutral-900">Request Submitted</p>
                                <p class="text-xs text-neutral-600 mt-1">{{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($request->reviewed_at)
                            <!-- Request Reviewed -->
                            <div class="relative flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 {{ $request->status === 'rejected' ? 'bg-error-600' : 'bg-neutral-700' }} rounded-full flex items-center justify-center shadow-md ring-4 ring-white">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($request->status === 'rejected')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            @endif
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 border border-neutral-200 rounded-lg p-4 flex-1 bg-white">
                                    <p class="text-sm font-bold text-neutral-900">
                                        {{ $request->status === 'rejected' ? 'Request Rejected' : 'Request Reviewed & Approved' }}
                                    </p>
                                    <p class="text-xs text-neutral-600 mt-1">{{ \Carbon\Carbon::parse($request->reviewed_at)->format('M j, Y \a\t g:i A') }}</p>
                                    @if($request->status === 'rejected' && $request->rejection_reason)
                                        <div class="mt-2 p-3 bg-error-50 rounded border-l-4 border-error-500">
                                            <p class="text-xs font-medium text-error-700">Reason: {{ $request->rejection_reason }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($request->payment_confirmed_at)
                            <!-- Payment Confirmed -->
                            <div class="relative flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-neutral-700 rounded-full flex items-center justify-center shadow-md ring-4 ring-white">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 border border-neutral-200 rounded-lg p-4 flex-1 bg-white">
                                    <p class="text-sm font-bold text-neutral-900">Payment Confirmed</p>
                                    <p class="text-xs text-neutral-600 mt-1">{{ \Carbon\Carbon::parse($request->payment_confirmed_at)->format('M j, Y \a\t g:i A') }}</p>
                                    @if($request->payment_reference)
                                        <p class="text-xs text-neutral-700 mt-2 font-mono bg-neutral-50 px-2 py-1 rounded inline-block border border-neutral-200">Ref: {{ $request->payment_reference }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Status Card (if project exists) -->
            @if($project)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-primary-50 rounded-full mb-4">
                            <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-2">Project Active</h3>
                        <p class="text-sm text-neutral-600 mb-4">Your request has been converted to an active project.</p>
                        <a href="{{ route('client.projects.show', $project->id) }}" 
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            View Project Details
                        </a>
                        <div class="mt-4 pt-4 border-t border-neutral-200">
                            <p class="text-xs text-neutral-600">
                                Status: <span class="font-semibold text-neutral-900">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Action Card (if applicable) -->
            @if($request->approved_budget && $request->payment_type)
                @php
                    $totalPaid = $request->getTotalPaid();
                    $totalBudget = $request->approved_budget;
                    $remainingBalance = $request->getRemainingPaymentBalance();
                    $currentPaymentDue = $request->getCurrentPaymentAmountDue();
                    $paymentDescription = $request->getCurrentPaymentDescription();
                    $paymentProgress = $request->getPaymentProgress();
                @endphp
                
                <!-- Payment Summary Card (Always shown when payment system is active) -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-primary-50 rounded-full mb-4">
                            <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($remainingBalance > 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                            {{ $remainingBalance > 0 ? 'Payment Status' : 'Fully Paid!' }}
                        </h3>
                        
                        <!-- Payment Progress Bar -->
                        <div class="bg-neutral-50 rounded-lg p-4 mb-4 border border-neutral-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-medium text-neutral-600">Progress</span>
                                <span class="text-xs font-semibold text-neutral-900">{{ number_format($paymentProgress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-neutral-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-primary-500 h-2 rounded-full transition-all duration-500" style="width: {{ $paymentProgress }}%"></div>
                            </div>
                            <div class="flex justify-between items-center mt-2 text-xs text-neutral-600">
                                <span>₱{{ number_format($totalPaid, 0) }} paid</span>
                                <span>₱{{ number_format($totalBudget, 0) }} total</span>
                            </div>
                        </div>

                        <!-- Payment Breakdown -->
                        <div class="bg-neutral-50 rounded-lg p-4 mb-4 space-y-3 border border-neutral-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-neutral-600">Original Budget:</span>
                                <span class="text-sm font-semibold text-neutral-900">₱{{ number_format($totalBudget, 2) }}</span>
                            </div>
                            
                            @if($request->coupon_discount_amount > 0 || $request->loyalty_discount_amount > 0)
                                @if($request->coupon_discount_amount > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-neutral-600">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        Coupon Discount:
                                    </span>
                                    <span class="text-sm font-semibold text-primary-600">-₱{{ number_format($request->coupon_discount_amount, 2) }}</span>
                                </div>
                                @endif
                                
                                @if($request->loyalty_discount_amount > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-neutral-600">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                        Loyalty Discount:
                                    </span>
                                    <span class="text-sm font-semibold text-primary-600">-₱{{ number_format($request->loyalty_discount_amount, 2) }}</span>
                                </div>
                                @endif
                                
                                <div class="h-px bg-neutral-200"></div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-neutral-900">Final Budget:</span>
                                    <span class="text-base font-semibold text-neutral-900">₱{{ number_format($totalBudget, 2) }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-neutral-600">Amount Paid:</span>
                                <span class="text-sm font-semibold text-primary-600">₱{{ number_format($totalPaid, 2) }}</span>
                            </div>
                            <div class="h-px bg-neutral-200"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-semibold text-neutral-900">Remaining Balance:</span>
                                <span class="text-base font-semibold {{ $remainingBalance > 0 ? 'text-neutral-900' : 'text-primary-600' }}">
                                    ₱{{ number_format($remainingBalance, 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Payment Type Info -->
                        <div class="bg-neutral-50 rounded-lg p-3 mb-4 border border-neutral-200">
                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Payment Type</p>
                            <p class="text-sm font-semibold text-neutral-900">{{ $request->getPaymentTypeLabel() }}</p>
                            
                            @if($request->isMilestonePayment() && $request->project)
                                @php
                                    $totalMilestones = $request->project->milestones()->count();
                                    $paidMilestones = $request->project->milestones()->where('is_paid', true)->count();
                                @endphp
                                <p class="text-xs text-neutral-600 mt-1">{{ $paidMilestones }} of {{ $totalMilestones }} phases paid</p>
                            @elseif($request->isDownpayment())
                                @if(!$request->downpayment_paid)
                                    <p class="text-xs text-neutral-600 mt-1">{{ number_format($request->downpayment_percentage, 0) }}% downpayment required</p>
                                @elseif(!$request->remaining_balance_paid)
                                    <p class="text-xs text-neutral-600 mt-1">Downpayment received • Final payment pending</p>
                                @else
                                    <p class="text-xs text-neutral-600 mt-1">All payments completed</p>
                                @endif
                            @endif
                        </div>

                        <!-- Coupon Section -->
                        @if(!$request->coupon_auto_applied)
                            @if($request->applied_coupon_id && $request->appliedCoupon)
                                <!-- Applied Coupon Display -->
                                <div class="bg-primary-50 rounded-lg p-4 mb-4 border border-primary-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Coupon Applied</p>
                                        @if(!$request->appliedCoupon->isValid())
                                            <span class="text-xs text-error-600 font-medium">Expired</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm font-semibold text-neutral-900 font-mono">{{ $request->appliedCoupon->code }}</p>
                                            <p class="text-xs text-neutral-600 mt-0.5">{{ $request->appliedCoupon->getDiscountLabel() }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-base font-semibold text-primary-600">-₱{{ number_format($request->coupon_discount_amount, 2) }}</p>
                                        </div>
                                    </div>
                                    @if($remainingBalance > 0)
                                        <form action="{{ route('client.coupons.remove', $request) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Remove this coupon?')" 
                                                    class="w-full px-3 py-1.5 bg-white border border-neutral-300 text-neutral-700 text-xs font-medium rounded hover:bg-neutral-50 transition-colors">
                                                Remove Coupon
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <!-- Coupon Input Form -->
                                <div class="bg-neutral-50 rounded-lg p-4 mb-4 border border-neutral-200">
                                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-3">Have a Coupon?</p>
                                    <form action="{{ route('client.coupons.apply', $request) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <input type="text" 
                                                   name="coupon_code" 
                                                   id="coupon_code"
                                                   placeholder="Enter code" 
                                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-all font-mono uppercase text-sm"
                                                   value="{{ old('coupon_code') }}"
                                                   required>
                                            @error('coupon_code')
                                                <p class="text-xs text-error-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit" 
                                                class="w-full px-4 py-2 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors text-sm">
                                            Apply Coupon
                                        </button>
                                        <p class="text-xs text-neutral-500">
                                            <a href="{{ route('client.coupons.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">View available coupons</a>
                                        </p>
                                    </form>
                                </div>
                            @endif
                        @endif

                        <!-- Pay Now Button (only if there's a balance due) -->
                        @if($currentPaymentDue > 0 && ($request->status === 'pending_payment' || $request->status === 'approved' || $request->status === 'in_progress'))
                            <div class="bg-neutral-50 rounded-lg p-4 mb-4 border border-neutral-200">
                                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Next Payment</p>
                                <p class="text-sm text-neutral-600">{{ $paymentDescription }}</p>
                                <p class="text-xl font-semibold text-neutral-900 mt-2">₱{{ number_format($currentPaymentDue, 2) }}</p>
                            </div>
                            
                            <a href="{{ route('client.maya.checkout', $request->id) }}" 
                               class="inline-flex items-center justify-center px-5 py-2.5 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors w-full">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Pay Now
                            </a>
                            
                            @if($request->payment_due_date)
                                <p class="text-xs text-neutral-600 mt-3 text-center">
                                    Due: {{ \Carbon\Carbon::parse($request->payment_due_date)->format('M j, Y') }}
                                </p>
                            @endif
                        @else
                            <div class="bg-primary-50 rounded-lg p-4 text-center border border-primary-200">
                                <svg class="w-10 h-10 text-primary-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-semibold text-neutral-900">All Payments Complete!</p>
                                <p class="text-xs text-neutral-600 mt-1">Thank you for your payment</p>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($request->status === 'pending_payment' || $request->status === 'approved')
                <!-- Fallback for requests without payment type set -->
                <div class="bg-white rounded-lg shadow-sm border-l-4 border-primary-500 overflow-hidden">
                    <div class="p-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-primary-50 rounded-full mb-4">
                            <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-2">Payment Required</h3>
                        <p class="text-2xl font-semibold text-neutral-900 mb-4">₱{{ number_format($request->approved_budget, 2) }}</p>
                        
                        <!-- Coupon Section -->
                        @if(!$request->coupon_auto_applied)
                            @if($request->applied_coupon_id && $request->appliedCoupon)
                                <!-- Applied Coupon Display -->
                                <div class="bg-primary-50 rounded-lg p-4 mb-4 border border-primary-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Coupon Applied</p>
                                        @if(!$request->appliedCoupon->isValid())
                                            <span class="text-xs text-error-600 font-medium">Expired</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm font-semibold text-neutral-900 font-mono">{{ $request->appliedCoupon->code }}</p>
                                            <p class="text-xs text-neutral-600 mt-0.5">{{ $request->appliedCoupon->getDiscountLabel() }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-base font-semibold text-primary-600">-₱{{ number_format($request->coupon_discount_amount, 2) }}</p>
                                        </div>
                                    </div>
                                    @php
                                        $totalPaidFallback = $request->getTotalPaid();
                                        $approvedBudgetFallback = $request->approved_budget ?? 0;
                                    @endphp
                                    @if($approvedBudgetFallback > 0 && $totalPaidFallback < $approvedBudgetFallback)
                                        <form action="{{ route('client.coupons.remove', $request) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Remove this coupon?')" 
                                                    class="w-full px-3 py-1.5 bg-white border border-neutral-300 text-neutral-700 text-xs font-medium rounded hover:bg-neutral-50 transition-colors">
                                                Remove Coupon
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <!-- Coupon Input Form -->
                                <div class="bg-neutral-50 rounded-lg p-4 mb-4 border border-neutral-200">
                                    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-3">Have a Coupon?</p>
                                    <form action="{{ route('client.coupons.apply', $request) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <input type="text" 
                                                   name="coupon_code" 
                                                   id="coupon_code"
                                                   placeholder="Enter code" 
                                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-all font-mono uppercase text-sm"
                                                   value="{{ old('coupon_code') }}"
                                                   required>
                                            @error('coupon_code')
                                                <p class="text-xs text-error-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit" 
                                                class="w-full px-4 py-2 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors text-sm">
                                            Apply Coupon
                                        </button>
                                        <p class="text-xs text-neutral-500">
                                            <a href="{{ route('client.coupons.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">View available coupons</a>
                                        </p>
                                    </form>
                                </div>
                            @endif
                        @endif
                        
                        <a href="{{ route('client.maya.checkout', $request->id) }}" 
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Pay Now
                        </a>
                    </div>
                </div>
            @endif

            <!-- Request Details Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-neutral-50 border-b border-neutral-200 p-4">
                    <h3 class="text-base font-semibold text-neutral-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Request Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="pb-4 border-b border-neutral-200">
                        <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Service Type</dt>
                        <dd class="text-sm font-bold text-neutral-900 flex items-center">
                            <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                            </svg>
                            {{ ucfirst(str_replace('_', ' ', $request->service_type)) }}
                        </dd>
                    </div>

                    <div class="pb-4 border-b border-neutral-200">
                        <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Contact Method</dt>
                        <dd class="text-sm font-bold text-neutral-900">{{ ucfirst($request->contact_method) }}</dd>
                        <dd class="text-sm text-neutral-600 mt-1">{{ $request->contact_details }}</dd>
                    </div>

                    <div class="pb-4 border-b border-neutral-200">
                        <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Priority Level</dt>
                        <dd class="text-sm mt-1">
                            @php
                                $priorityConfig = match($request->priority) {
                                    'high' => ['bg' => 'bg-error-100', 'text' => 'text-error-800', 'border' => 'border-error-300'],
                                    'medium' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-800', 'border' => 'border-warning-300'],
                                    'low' => ['bg' => 'bg-success-100', 'text' => 'text-success-800', 'border' => 'border-success-300'],
                                    default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'border' => 'border-neutral-300']
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $priorityConfig['bg'] }} {{ $priorityConfig['text'] }} border {{ $priorityConfig['border'] }}">
                                {{ ucfirst($request->priority) }} Priority
                            </span>
                        </dd>
                    </div>

                    @if($request->deadline)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Project Deadline</dt>
                            <dd class="text-sm font-bold {{ \Carbon\Carbon::parse($request->deadline)->isPast() ? 'text-error-600' : 'text-neutral-900' }}">
                                {{ \Carbon\Carbon::parse($request->deadline)->format('F j, Y') }}
                            </dd>
                            @if(\Carbon\Carbon::parse($request->deadline)->isPast())
                                <dd class="text-xs text-error-600 font-medium mt-1 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Past Due
                                </dd>
                            @else
                                <dd class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($request->deadline)->diffForHumans() }}</dd>
                            @endif
                        </div>
                    @endif

                    @if($request->estimated_budget)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Estimated Budget</dt>
                            <dd class="text-lg font-bold text-primary-600">₱{{ number_format($request->estimated_budget, 2) }}</dd>
                        </div>
                    @endif

                    @if($request->approved_budget)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Approved Budget</dt>
                            
                            @if($request->coupon_discount_amount > 0 || $request->loyalty_discount_amount > 0)
                                <!-- Show original budget with strikethrough -->
                                <dd class="text-sm text-neutral-400 line-through">₱{{ number_format($request->approved_budget + ($request->coupon_discount_amount ?? 0) + ($request->loyalty_discount_amount ?? 0), 2) }}</dd>
                                
                                <!-- Show discounts -->
                                @if($request->coupon_discount_amount > 0)
                                    <dd class="text-xs text-success-600 flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        Coupon: -₱{{ number_format($request->coupon_discount_amount, 2) }}
                                    </dd>
                                @endif
                                
                                @if($request->loyalty_discount_amount > 0)
                                    <dd class="text-xs text-warning-600 flex items-center mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                        Loyalty: -₱{{ number_format($request->loyalty_discount_amount, 2) }}
                                    </dd>
                                @endif
                                
                                <!-- Final budget -->
                                <dd class="text-lg font-bold text-success-600 mt-2">₱{{ number_format($request->approved_budget, 2) }}</dd>
                            @else
                                <dd class="text-lg font-bold text-success-600">₱{{ number_format($request->approved_budget, 2) }}</dd>
                            @endif
                            
                            @if($request->payment_type)
                                @php
                                    $totalPaid = $request->getTotalPaid();
                                    $remainingBalance = $request->getRemainingPaymentBalance();
                                @endphp
                                @if($totalPaid > 0)
                                    <dd class="text-xs text-neutral-600 mt-2">
                                        <span class="font-medium">Paid:</span> 
                                        <span class="text-success-600 font-bold">₱{{ number_format($totalPaid, 2) }}</span>
                                    </dd>
                                    @if($remainingBalance > 0)
                                        <dd class="text-xs text-neutral-600 mt-1">
                                            <span class="font-medium">Balance:</span> 
                                            <span class="text-error-600 font-bold">₱{{ number_format($remainingBalance, 2) }}</span>
                                        </dd>
                                    @endif
                                @endif
                            @endif
                        </div>
                    @else
                        @if($request->estimated_budget)
                            <div>
                                <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Estimated Budget</dt>
                                <dd class="text-lg font-bold text-primary-600">₱{{ number_format($request->estimated_budget, 2) }}</dd>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Payment Instructions (if applicable) -->
            @if($request->payment_instructions && ($request->status === 'pending_payment' || $request->status === 'approved'))
                <div class="glass-card overflow-hidden border-2 border-warning-300">
                    <div class="bg-gradient-to-r from-warning-500 to-warning-600 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Payment Instructions
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-sm max-w-none">
                            <p class="text-sm text-neutral-700 leading-relaxed whitespace-pre-line">{{ $request->payment_instructions }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Admin Notes -->
            @if($request->admin_notes)
                <div class="glass-card overflow-hidden">
                    <div class="bg-gradient-to-r from-accent-500 to-accent-600 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Notes from Admin
                        </h3>
                    </div>
                    <div class="p-6 bg-gradient-to-br from-accent-50 to-white">
                        <div class="prose prose-sm max-w-none">
                            <p class="text-sm text-neutral-700 leading-relaxed whitespace-pre-line">{{ $request->admin_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
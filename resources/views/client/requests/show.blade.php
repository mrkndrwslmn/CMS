@extends('client.layout')

@section('title', 'Service Request Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
    <div class="glass-card p-8 mb-6 border-l-4 {{ match($request->status) {
        'pending' => 'border-warning-500',
        'approved' => 'border-success-500',
        'rejected' => 'border-error-500',
        'pending_payment' => 'border-secondary-500',
        'paid' => 'border-primary-500',
        'in_progress' => 'border-accent-500',
        'completed' => 'border-success-600',
        default => 'border-neutral-500'
    } }}">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
                <!-- Request ID Badge -->
                <div class="inline-flex items-center space-x-3 mb-3">
                    <span class="text-xs font-mono font-bold text-neutral-500 bg-neutral-100 px-3 py-1.5 rounded-lg">
                        REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    @php
                        $statusConfig = match($request->status) {
                            'pending' => ['bg' => 'bg-warning-500', 'text' => 'text-white', 'label' => 'Pending Review', 'pulse' => true],
                            'approved' => ['bg' => 'bg-success-500', 'text' => 'text-white', 'label' => 'Approved', 'pulse' => false],
                            'rejected' => ['bg' => 'bg-error-500', 'text' => 'text-white', 'label' => 'Rejected', 'pulse' => false],
                            'pending_payment' => ['bg' => 'bg-secondary-500', 'text' => 'text-white', 'label' => 'Awaiting Payment', 'pulse' => true],
                            'paid' => ['bg' => 'bg-primary-500', 'text' => 'text-white', 'label' => 'Payment Confirmed', 'pulse' => false],
                            'in_progress' => ['bg' => 'bg-accent-500', 'text' => 'text-white', 'label' => 'In Progress', 'pulse' => true],
                            'completed' => ['bg' => 'bg-success-600', 'text' => 'text-white', 'label' => 'Completed', 'pulse' => false],
                            default => ['bg' => 'bg-neutral-500', 'text' => 'text-white', 'label' => 'Unknown', 'pulse' => false]
                        };
                    @endphp
                    <span class="relative inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} shadow-md">
                        @if($statusConfig['pulse'])
                            <span class="absolute inset-0 rounded-full {{ $statusConfig['bg'] }} animate-ping opacity-75"></span>
                        @endif
                        <span class="relative">{{ $statusConfig['label'] }}</span>
                    </span>
                </div>

                <!-- Project Title -->
                <h1 class="heading-serif text-3xl text-neutral-900 mb-3 leading-tight">{{ $request->project_name }}</h1>
                
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
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-accent-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        View Project
                    </a>
                @endif
                
                @if($request->status === 'pending')
                    <a href="{{ route('client.requests.edit', $request->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-primary-500 text-primary-600 font-semibold rounded-lg hover:bg-primary-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Request
                    </a>
                @elseif($request->status === 'approved' || $request->status === 'pending_payment')
                    <a href="{{ route('client.maya.checkout', $request->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-accent-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
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
        <div class="glass-card p-5 text-center">
            @php
                $priorityConfig = match($request->priority) {
                    'high' => ['bg' => 'bg-error-100', 'text' => 'text-error-600', 'icon' => 'text-error-500'],
                    'medium' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-600', 'icon' => 'text-warning-500'],
                    'low' => ['bg' => 'bg-success-100', 'text' => 'text-success-600', 'icon' => 'text-success-500'],
                    default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-600', 'icon' => 'text-neutral-500']
                };
            @endphp
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full {{ $priorityConfig['bg'] }} mb-2">
                <svg class="w-6 h-6 {{ $priorityConfig['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
            </div>
            <p class="text-xs text-neutral-500 font-medium mb-1">Priority</p>
            <p class="text-lg font-bold {{ $priorityConfig['text'] }}">{{ ucfirst($request->priority) }}</p>
        </div>

        <!-- Budget / Payment Status -->
        @if($request->approved_budget ?? $request->estimated_budget)
            <div class="glass-card p-5 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary-100 mb-2">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <p class="text-lg font-bold text-success-600">₱{{ number_format($totalPaid, 0) }}</p>
                        @if($remainingBalance > 0)
                            <p class="text-xs text-error-600 font-medium mt-1">₱{{ number_format($remainingBalance, 0) }} remaining</p>
                        @else
                            <p class="text-xs text-success-600 font-medium mt-1">✓ Fully Paid</p>
                        @endif
                    @elseif($currentDue > 0)
                        <!-- Show current amount due if nothing paid yet -->
                        <p class="text-xs text-neutral-500 font-medium mb-1">Amount Due</p>
                        <p class="text-lg font-bold text-error-600">₱{{ number_format($currentDue, 0) }}</p>
                        <p class="text-xs text-neutral-500 mt-1">{{ $request->getCurrentPaymentDescription() }}</p>
                    @else
                        <p class="text-xs text-neutral-500 font-medium mb-1">Approved Budget</p>
                        <p class="text-lg font-bold text-primary-600">₱{{ number_format($request->approved_budget, 0) }}</p>
                    @endif
                @else
                    <p class="text-xs text-neutral-500 font-medium mb-1">{{ $request->approved_budget ? 'Approved' : 'Estimated' }} Budget</p>
                    <p class="text-lg font-bold text-primary-600">₱{{ number_format($request->approved_budget ?? $request->estimated_budget, 0) }}</p>
                @endif
            </div>
        @endif

        <!-- Deadline -->
        @if($request->deadline)
            <div class="glass-card p-5 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full {{ \Carbon\Carbon::parse($request->deadline)->isPast() ? 'bg-error-100' : 'bg-accent-100' }} mb-2">
                    <svg class="w-6 h-6 {{ \Carbon\Carbon::parse($request->deadline)->isPast() ? 'text-error-500' : 'text-accent-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-xs text-neutral-500 font-medium mb-1">Deadline</p>
                <p class="text-sm font-bold {{ \Carbon\Carbon::parse($request->deadline)->isPast() ? 'text-error-600' : 'text-accent-600' }}">
                    {{ \Carbon\Carbon::parse($request->deadline)->format('M j, Y') }}
                </p>
                @if(\Carbon\Carbon::parse($request->deadline)->isPast())
                    <p class="text-xs text-error-500 font-medium mt-1">Past Due</p>
                @else
                    <p class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($request->deadline)->diffForHumans() }}</p>
                @endif
            </div>
        @endif

        <!-- Contact Method -->
        <div class="glass-card p-5 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-secondary-100 mb-2">
                <svg class="w-6 h-6 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-xs text-neutral-500 font-medium mb-1">Contact</p>
            <p class="text-sm font-bold text-secondary-600">{{ ucfirst($request->contact_method) }}</p>
            <p class="text-xs text-neutral-500 mt-1 truncate">{{ $request->contact_details }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Description -->
            <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
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
                <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
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
                <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
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
                <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
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
            <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-neutral-900 mb-6 flex items-center">
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
                <div class="glass-card overflow-hidden border-2 border-success-300 shadow-lg">
                    <div class="bg-gradient-to-br from-success-500 to-success-600 p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Project Active</h3>
                        <p class="text-white/90 text-sm mb-4">Your request has been converted to an active project. View progress and details.</p>
                        <a href="{{ route('client.projects.show', $project->id) }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-white text-success-600 font-bold rounded-lg hover:bg-success-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            View Project Details
                        </a>
                        <div class="mt-4 pt-4 border-t border-white/20">
                            <p class="text-xs text-white/80">
                                Status: <span class="font-bold">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
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
                <div class="glass-card overflow-hidden border-2 {{ $remainingBalance > 0 ? 'border-secondary-300' : 'border-success-300' }} shadow-lg">
                    <div class="bg-gradient-to-br {{ $remainingBalance > 0 ? 'from-secondary-500 to-primary-600' : 'from-success-500 to-success-600' }} p-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4 shadow-lg">
                            <svg class="w-8 h-8 {{ $remainingBalance > 0 ? 'text-secondary-600' : 'text-success-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($remainingBalance > 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-4 text-center">
                            {{ $remainingBalance > 0 ? 'Payment Status' : 'Fully Paid!' }}
                        </h3>
                        
                        <!-- Payment Progress Bar -->
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-semibold text-white/90">Progress</span>
                                <span class="text-xs font-bold text-white">{{ number_format($paymentProgress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-white/30 rounded-full h-3 overflow-hidden">
                                <div class="bg-white h-3 rounded-full transition-all duration-500" style="width: {{ $paymentProgress }}%"></div>
                            </div>
                            <div class="flex justify-between items-center mt-2 text-xs text-white/80">
                                <span>₱{{ number_format($totalPaid, 0) }} paid</span>
                                <span>₱{{ number_format($totalBudget, 0) }} total</span>
                            </div>
                        </div>

                        <!-- Payment Breakdown -->
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 mb-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-white/90">Total Budget:</span>
                                <span class="text-sm font-bold text-white">₱{{ number_format($totalBudget, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-white/90">Amount Paid:</span>
                                <span class="text-sm font-bold text-success-200">₱{{ number_format($totalPaid, 2) }}</span>
                            </div>
                            <div class="h-px bg-white/30"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-white/90">Remaining Balance:</span>
                                <span class="text-lg font-bold {{ $remainingBalance > 0 ? 'text-warning-200' : 'text-success-200' }}">
                                    ₱{{ number_format($remainingBalance, 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Payment Type Info -->
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 mb-4 text-left">
                            <p class="text-xs font-semibold text-white/90 uppercase tracking-wide mb-2">Payment Type</p>
                            <p class="text-sm font-bold text-white">{{ $request->getPaymentTypeLabel() }}</p>
                            
                            @if($request->isMilestonePayment() && $request->project)
                                @php
                                    $totalMilestones = $request->project->milestones()->count();
                                    $paidMilestones = $request->project->milestones()->where('is_paid', true)->count();
                                @endphp
                                <p class="text-xs text-white/80 mt-1">{{ $paidMilestones }} of {{ $totalMilestones }} phases paid</p>
                            @elseif($request->isDownpayment())
                                @if(!$request->downpayment_paid)
                                    <p class="text-xs text-white/80 mt-1">{{ number_format($request->downpayment_percentage, 0) }}% downpayment required</p>
                                @elseif(!$request->remaining_balance_paid)
                                    <p class="text-xs text-white/80 mt-1">Downpayment received • Final payment pending</p>
                                @else
                                    <p class="text-xs text-white/80 mt-1">All payments completed</p>
                                @endif
                            @endif
                        </div>

                        <!-- Pay Now Button (only if there's a balance due) -->
                        @if($currentPaymentDue > 0 && ($request->status === 'pending_payment' || $request->status === 'approved' || $request->status === 'in_progress'))
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 mb-4">
                                <p class="text-xs font-semibold text-white/90 uppercase tracking-wide mb-1">Next Payment</p>
                                <p class="text-sm text-white/80">{{ $paymentDescription }}</p>
                                <p class="text-2xl font-bold text-white mt-2">₱{{ number_format($currentPaymentDue, 2) }}</p>
                            </div>
                            
                            <a href="{{ route('client.maya.checkout', $request->id) }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-white text-secondary-600 font-bold rounded-lg hover:bg-secondary-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 w-full">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Pay Now
                            </a>
                            
                            @if($request->payment_due_date)
                                <p class="text-xs text-white/80 mt-3 text-center">
                                    Due: {{ \Carbon\Carbon::parse($request->payment_due_date)->format('M j, Y') }}
                                </p>
                            @endif
                        @else
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">
                                <svg class="w-12 h-12 text-white mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-bold text-white">All Payments Complete!</p>
                                <p class="text-xs text-white/80 mt-1">Thank you for your payment</p>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($request->status === 'pending_payment' || $request->status === 'approved')
                <!-- Fallback for requests without payment type set -->
                <div class="glass-card overflow-hidden border-2 border-secondary-300 shadow-lg">
                    <div class="bg-gradient-to-br from-secondary-500 to-primary-600 p-6 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Payment Required</h3>
                        <p class="text-3xl font-bold text-white mb-4">₱{{ number_format($request->approved_budget, 2) }}</p>
                        
                        <a href="{{ route('client.maya.checkout', $request->id) }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-white text-secondary-600 font-bold rounded-lg hover:bg-secondary-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Pay Now
                        </a>
                    </div>
                </div>
            @endif

            <!-- Request Details Card -->
            <div class="glass-card overflow-hidden">
                <div class="bg-gradient-to-r from-neutral-700 to-neutral-800 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <dd class="text-lg font-bold text-success-600">₱{{ number_format($request->approved_budget, 2) }}</dd>
                            
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
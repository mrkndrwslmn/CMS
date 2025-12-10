@extends('client.layouts.app')

@section('title', $request->project_name . ' - Service Request Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Service Requests', 'route' => 'client.requests', 'icon' => 'file-text'],
        ['label' => $request->project_name, 'icon' => 'file-check']
    ]" />

    <!-- Request Header Card -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
                <!-- Request ID Badge -->
                <div class="inline-flex items-center gap-3 mb-3">
                    <span class="text-xs font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-lg">
                        REQ-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    @php
                        $statusConfig = match($request->status) {
                            'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-700', 'label' => 'Pending Review'],
                            'approved' => ['bg' => 'bg-success-100', 'text' => 'text-success-700', 'label' => 'Approved'],
                            'rejected' => ['bg' => 'bg-error-100', 'text' => 'text-error-700', 'label' => 'Rejected'],
                            'pending_payment' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-700', 'label' => 'Awaiting Payment'],
                            'paid' => ['bg' => 'bg-success-100', 'text' => 'text-success-700', 'label' => 'Payment Confirmed'],
                            'in_progress' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-700', 'label' => 'In Progress'],
                            'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-700', 'label' => 'Completed'],
                            default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700', 'label' => 'Unknown']
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                        {{ $statusConfig['label'] }}
                    </span>
                </div>

                <!-- Project Title -->
                <h1 class="text-2xl font-semibold text-neutral-800 mb-2">{{ $request->project_name }}</h1>
                
                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-500 mb-3">
                    <div class="flex items-center gap-1.5">
                        <x-lucide-clock class="w-4 h-4 text-neutral-400" />
                        <span>Created {{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y') }}</span>
                    </div>
                    <span class="text-neutral-300">•</span>
                    <div class="flex items-center gap-1.5">
                        <x-lucide-briefcase class="w-4 h-4 text-neutral-400" />
                        <span>{{ ucfirst(str_replace('_', ' ', $request->service_type)) }}</span>
                    </div>
                </div>
                
                <!-- Related Links -->
                <x-ui.related-links :serviceRequest="$request" role="client" />
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                @if($project)
                    <!-- View Project Button (shown when project exists) -->
                    <a href="{{ route('client.projects.show', $project->id) }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <x-lucide-folder-open class="w-4 h-4" />
                        View Project
                    </a>
                @endif
                
                @if($request->status === 'pending')
                    <a href="{{ route('client.requests.edit', $request->id) }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-neutral-200 text-neutral-700 text-sm font-medium rounded-lg shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                        <x-lucide-pencil class="w-4 h-4" />
                        Edit Request
                    </a>
                @elseif($request->status === 'approved' || $request->status === 'pending_payment')
                    <a href="{{ route('client.maya.checkout', $request->id) }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                        <x-lucide-credit-card class="w-4 h-4" />
                        Pay with Maya
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Key Metrics Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
        <!-- Priority -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Priority</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ ucfirst($request->priority) }}</p>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl">
                    <x-lucide-arrow-up class="w-5 h-5 text-neutral-400" />
                </div>
            </div>
        </div>

        <!-- Budget / Payment Status -->
        @if($request->approved_budget ?? $request->estimated_budget)
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        @if($request->approved_budget && $request->payment_type)
                            @php
                                $totalPaid = $request->getTotalPaid();
                                $remainingBalance = $request->getRemainingPaymentBalance();
                                $currentDue = $request->getCurrentPaymentAmountDue();
                            @endphp
                            @if($totalPaid > 0)
                                <!-- Show total paid -->
                                <p class="text-sm font-medium text-neutral-500">Total Paid</p>
                                <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($totalPaid, 0) }}</p>
                                @if($remainingBalance > 0)
                                    <p class="text-sm text-neutral-500 mt-1">₱{{ number_format($remainingBalance, 0) }} remaining</p>
                                @else
                                    <p class="text-sm text-success-600 mt-1">Fully Paid</p>
                                @endif
                            @elseif($currentDue > 0)
                                <!-- Show current amount due if nothing paid yet -->
                                <p class="text-sm font-medium text-neutral-500">Amount Due</p>
                                <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($currentDue, 0) }}</p>
                                <p class="text-sm text-neutral-500 mt-1">{{ $request->getCurrentPaymentDescription() }}</p>
                            @else
                                <p class="text-sm font-medium text-neutral-500">Approved Budget</p>
                                @if($request->tier_discount_amount > 0 || $request->coupon_discount_amount > 0 || $request->loyalty_discount_amount > 0)
                                    {{-- Show original with strikethrough --}}
                                    <p class="text-lg text-neutral-400 line-through mt-1">₱{{ number_format($request->getOriginalBudget(), 0) }}</p>
                                    {{-- Show discounts applied --}}
                                    <div class="flex flex-wrap gap-2 mt-1 mb-2">
                                        @if($request->tier_discount_amount > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-info-50 text-info-700 rounded-full text-xs">
                                                <x-lucide-award class="w-3 h-3" />
                                                -₱{{ number_format($request->tier_discount_amount, 0) }}
                                            </span>
                                        @endif
                                        @if($request->coupon_discount_amount > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-success-50 text-success-700 rounded-full text-xs">
                                                <x-lucide-ticket class="w-3 h-3" />
                                                -₱{{ number_format($request->coupon_discount_amount, 0) }}
                                            </span>
                                        @endif
                                        @if($request->loyalty_discount_amount > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-warning-50 text-warning-700 rounded-full text-xs">
                                                <x-lucide-star class="w-3 h-3" />
                                                -₱{{ number_format($request->loyalty_discount_amount, 0) }}
                                            </span>
                                        @endif
                                    </div>
                                    {{-- Show final approved budget --}}
                                    <p class="text-2xl font-semibold text-success-600">₱{{ number_format($request->approved_budget, 0) }}</p>
                                @else
                                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($request->approved_budget, 0) }}</p>
                                @endif
                            @endif
                        @else
                            <p class="text-sm font-medium text-neutral-500">{{ $request->approved_budget ? 'Approved' : 'Estimated' }} Budget</p>
                            @if($request->approved_budget && ($request->tier_discount_amount > 0 || $request->coupon_discount_amount > 0 || $request->loyalty_discount_amount > 0))
                                {{-- Show discount info for approved budgets with discounts --}}
                                <p class="text-lg text-neutral-400 line-through mt-1">₱{{ number_format($request->getOriginalBudget(), 0) }}</p>
                                <div class="flex flex-wrap gap-2 mt-1 mb-2">
                                    @if($request->tier_discount_amount > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-info-50 text-info-700 rounded-full text-xs">
                                            <x-lucide-award class="w-3 h-3" />
                                            -₱{{ number_format($request->tier_discount_amount, 0) }}
                                        </span>
                                    @endif
                                    @if($request->coupon_discount_amount > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-success-50 text-success-700 rounded-full text-xs">
                                            <x-lucide-ticket class="w-3 h-3" />
                                            -₱{{ number_format($request->coupon_discount_amount, 0) }}
                                        </span>
                                    @endif
                                    @if($request->loyalty_discount_amount > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-warning-50 text-warning-700 rounded-full text-xs">
                                            <x-lucide-star class="w-3 h-3" />
                                            -₱{{ number_format($request->loyalty_discount_amount, 0) }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-2xl font-semibold text-success-600">₱{{ number_format($request->approved_budget, 0) }}</p>
                            @else
                                <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($request->approved_budget ?? $request->estimated_budget, 0) }}</p>
                            @endif
                        @endif
                    </div>
                    <div class="p-3 bg-neutral-50 rounded-xl">
                        <x-lucide-circle-dollar-sign class="w-5 h-5 text-neutral-400" />
                    </div>
                </div>
            </div>
        @endif

        <!-- Deadline -->
        @if($request->deadline)
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Deadline</p>
                        <p class="text-lg font-semibold text-neutral-800 mt-1">
                            {{ \Carbon\Carbon::parse($request->deadline)->format('M j, Y') }}
                        </p>
                        <p class="text-sm {{ \Carbon\Carbon::parse($request->deadline)->isPast() ? 'text-error-600' : 'text-neutral-500' }} mt-1">
                            @if(\Carbon\Carbon::parse($request->deadline)->isPast())
                                Past Due
                            @else
                                {{ \Carbon\Carbon::parse($request->deadline)->diffForHumans() }}
                            @endif
                        </p>
                    </div>
                    <div class="p-3 {{ \Carbon\Carbon::parse($request->deadline)->isPast() ? 'bg-error-50' : 'bg-neutral-50' }} rounded-xl">
                        <x-lucide-calendar class="w-5 h-5 {{ \Carbon\Carbon::parse($request->deadline)->isPast() ? 'text-error-500' : 'text-neutral-400' }}" />
                    </div>
                </div>
            </div>
        @endif

        <!-- Applied Coupon -->
        @if($request->applied_coupon_id && $request->appliedCoupon)
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Coupon Applied</p>
                        <p class="text-lg font-semibold text-neutral-800 font-mono mt-1">{{ $request->appliedCoupon->code }}</p>
                        @if($request->coupon_discount_amount > 0)
                            <p class="text-sm text-success-600 mt-1">-₱{{ number_format($request->coupon_discount_amount, 2) }}</p>
                        @endif
                        {{-- Don't show expired warning for applied coupons - discount already locked in --}}
                    </div>
                    <div class="p-3 bg-success-50 rounded-xl">
                        <x-lucide-ticket class="w-5 h-5 text-success-500" />
                    </div>
                </div>
            </div>
        @endif

        <!-- Loyalty Discount -->
        @if($request->loyalty_discount_amount > 0)
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Loyalty Discount</p>
                        <p class="text-lg font-semibold text-success-600 mt-1">-₱{{ number_format($request->loyalty_discount_amount, 2) }}</p>
                        @if($request->loyalty_points_used > 0)
                            <p class="text-sm text-neutral-500 mt-1">{{ number_format($request->loyalty_points_used) }} points used</p>
                        @endif
                    </div>
                    <div class="p-3 bg-warning-50 rounded-xl">
                        <x-lucide-award class="w-5 h-5 text-warning-500" />
                    </div>
                </div>
            </div>
        @endif

        <!-- Contact Method -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Contact</p>
                    <p class="text-lg font-semibold text-neutral-800 mt-1">{{ ucfirst($request->contact_method) }}</p>
                    <p class="text-sm text-neutral-500 mt-1 truncate">{{ $request->contact_details }}</p>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl">
                    <x-lucide-mail class="w-5 h-5 text-neutral-400" />
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Description -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <x-lucide-file-text class="w-5 h-5 text-neutral-400" />
                        <h3 class="text-lg font-medium text-neutral-700">Project Description</h3>
                    </div>
                    <div class="prose prose-neutral prose-sm max-w-none">
                        <p class="text-sm text-neutral-600 leading-relaxed whitespace-pre-line">{{ $request->request_description }}</p>
                    </div>
                </div>
            </div>

            <!-- Expectations -->
            @if($request->expectations)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <x-lucide-clipboard-check class="w-5 h-5 text-neutral-400" />
                            <h3 class="text-lg font-medium text-neutral-700">Project Expectations</h3>
                        </div>
                        <div class="prose prose-neutral prose-sm max-w-none">
                            <p class="text-sm text-neutral-600 leading-relaxed whitespace-pre-line">{{ $request->expectations }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Additional Notes -->
            @if($request->additional_notes)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <x-lucide-message-square class="w-5 h-5 text-neutral-400" />
                            <h3 class="text-lg font-medium text-neutral-700">Additional Notes</h3>
                        </div>
                        <div class="prose prose-neutral prose-sm max-w-none">
                            <p class="text-sm text-neutral-600 leading-relaxed whitespace-pre-line">{{ $request->additional_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Attachments -->
            @if($attachments && count($attachments) > 0)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <x-lucide-paperclip class="w-5 h-5 text-neutral-400" />
                            <h3 class="text-lg font-medium text-neutral-700">Attachments</h3>
                            <span class="bg-neutral-100 text-neutral-600 px-2 py-0.5 rounded-full text-xs font-medium">{{ count($attachments) }}</span>
                        </div>
                        <div class="space-y-3">
                            @foreach($attachments as $attachment)
                                <div class="flex items-center justify-between p-4 border border-neutral-100 rounded-xl hover:border-neutral-200 hover:shadow-sm transition-all">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="flex-shrink-0 w-10 h-10 bg-neutral-50 rounded-xl flex items-center justify-center mr-3">
                                            <x-lucide-file class="w-5 h-5 text-neutral-400" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-neutral-800 truncate">{{ $attachment->original_filename }}</p>
                                            <p class="text-xs text-neutral-500">{{ number_format($attachment->file_size / 1024, 1) }} KB</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('client.requests.attachment.download', [$request->id, $attachment->id]) }}" 
                                       class="ml-4 inline-flex items-center gap-1.5 px-4 py-2 border border-neutral-200 text-neutral-600 font-medium rounded-xl hover:bg-neutral-50 hover:text-neutral-800 transition-all text-sm">
                                        <x-lucide-download class="w-4 h-4" />
                                        Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status Timeline -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <x-lucide-clock class="w-5 h-5 text-neutral-400" />
                        <h3 class="text-lg font-medium text-neutral-700">Request Timeline</h3>
                    </div>
                    <div class="relative space-y-6">
                        <!-- Timeline Line -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-neutral-100"></div>

                        <!-- Request Submitted -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-neutral-700 rounded-full flex items-center justify-center shadow-sm ring-4 ring-white">
                                    <x-lucide-plus class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4 border border-neutral-100 rounded-xl p-4 flex-1 bg-white">
                                <p class="text-sm font-medium text-neutral-800">Request Submitted</p>
                                <p class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($request->reviewed_at)
                            <!-- Request Reviewed -->
                            <div class="relative flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 {{ $request->status === 'rejected' ? 'bg-error-600' : 'bg-neutral-700' }} rounded-full flex items-center justify-center shadow-sm ring-4 ring-white">
                                        @if($request->status === 'rejected')
                                            <x-lucide-x class="w-4 h-4 text-white" />
                                        @else
                                            <x-lucide-check class="w-4 h-4 text-white" />
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4 border border-neutral-100 rounded-xl p-4 flex-1 bg-white">
                                    <p class="text-sm font-medium text-neutral-800">
                                        {{ $request->status === 'rejected' ? 'Request Rejected' : 'Request Reviewed & Approved' }}
                                    </p>
                                    <p class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($request->reviewed_at)->format('M j, Y \a\t g:i A') }}</p>
                                    @if($request->status === 'rejected' && $request->rejection_reason)
                                        <div class="mt-2 p-3 bg-error-50 rounded-lg border-l-4 border-error-500">
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
                                    <div class="w-8 h-8 bg-neutral-700 rounded-full flex items-center justify-center shadow-sm ring-4 ring-white">
                                        <x-lucide-circle-dollar-sign class="w-4 h-4 text-white" />
                                    </div>
                                </div>
                                <div class="ml-4 border border-neutral-100 rounded-xl p-4 flex-1 bg-white">
                                    <p class="text-sm font-medium text-neutral-800">Payment Confirmed</p>
                                    <p class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($request->payment_confirmed_at)->format('M j, Y \a\t g:i A') }}</p>
                                    @if($request->payment_reference)
                                        <p class="text-xs text-neutral-600 mt-2 font-mono bg-neutral-50 px-2 py-1 rounded-lg inline-block border border-neutral-100">Ref: {{ $request->payment_reference }}</p>
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
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-success-50 rounded-xl">
                                <x-lucide-check-circle class="w-5 h-5 text-success-600" />
                            </div>
                            <h3 class="text-lg font-medium text-neutral-700">Project Active</h3>
                        </div>
                        <p class="text-sm text-neutral-600 mb-4">Your request has been converted to an active project.</p>
                        <a href="{{ route('client.projects.show', $project->id) }}" 
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors w-full">
                            <x-lucide-folder-open class="w-4 h-4" />
                            View Project Details
                        </a>
                        <div class="mt-4 pt-4 border-t border-neutral-100">
                            <p class="text-xs text-neutral-500">
                                Status: <span class="font-medium text-neutral-800">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
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
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 {{ $remainingBalance > 0 ? 'bg-neutral-50' : 'bg-success-50' }} rounded-xl">
                                @if($remainingBalance > 0)
                                    <x-lucide-credit-card class="w-5 h-5 text-neutral-400" />
                                @else
                                    <x-lucide-check-circle class="w-5 h-5 text-success-600" />
                                @endif
                            </div>
                            <h3 class="text-lg font-medium text-neutral-700">
                                {{ $remainingBalance > 0 ? 'Payment Status' : 'Fully Paid!' }}
                            </h3>
                        </div>
                        
                        <!-- Payment Progress Bar -->
                        <div class="bg-neutral-50 rounded-xl p-4 mb-4 border border-neutral-100">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-medium text-neutral-500">Progress</span>
                                <span class="text-xs font-semibold text-neutral-800">{{ number_format($paymentProgress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-neutral-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-500" style="width: {{ $paymentProgress }}%"></div>
                            </div>
                            <div class="flex justify-between items-center mt-2 text-xs text-neutral-500">
                                <span>₱{{ number_format($totalPaid, 0) }} paid</span>
                                <span>₱{{ number_format($totalBudget, 0) }} total</span>
                            </div>
                        </div>

                        <!-- Payment Breakdown -->
                        <div class="bg-neutral-50 rounded-xl p-4 mb-4 space-y-3 border border-neutral-100">
                            @if($request->tier_discount_amount > 0 || $request->coupon_discount_amount > 0 || $request->loyalty_discount_amount > 0)
                                {{-- Show original budget before any discounts --}}
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-neutral-600">Original Budget:</span>
                                    <span class="text-sm font-medium text-neutral-800">₱{{ number_format($request->getOriginalBudget(), 2) }}</span>
                                </div>
                                
                                {{-- Tier Discount --}}
                                @if($request->tier_discount_amount > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-neutral-600">
                                        <x-lucide-award class="w-4 h-4 inline mr-1" />
                                        {{ ucfirst($request->tier_at_approval ?? 'Tier') }} Discount ({{ $request->tier_discount_percentage }}%):
                                    </span>
                                    <span class="text-sm font-medium text-info-600">-₱{{ number_format($request->tier_discount_amount, 2) }}</span>
                                </div>
                                @endif
                                
                                {{-- Coupon Discount --}}
                                @if($request->coupon_discount_amount > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-neutral-600">
                                        <x-lucide-ticket class="w-4 h-4 inline mr-1" />
                                        Coupon Discount:
                                    </span>
                                    <span class="text-sm font-medium text-success-600">-₱{{ number_format($request->coupon_discount_amount, 2) }}</span>
                                </div>
                                @endif
                                
                                {{-- Loyalty Points Discount --}}
                                @if($request->loyalty_discount_amount > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-neutral-600">
                                        <x-lucide-star class="w-4 h-4 inline mr-1" />
                                        Loyalty Points:
                                    </span>
                                    <span class="text-sm font-medium text-warning-600">-₱{{ number_format($request->loyalty_discount_amount, 2) }}</span>
                                </div>
                                @endif
                                
                                <div class="h-px bg-neutral-200"></div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-neutral-800">Approved Budget:</span>
                                    <span class="text-base font-bold text-success-600">₱{{ number_format($totalBudget, 2) }}</span>
                                </div>
                                <div class="h-px bg-neutral-100"></div>
                            @else
                                {{-- No discounts applied --}}
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-neutral-600">Approved Budget:</span>
                                    <span class="text-sm font-medium text-neutral-800">₱{{ number_format($totalBudget, 2) }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-neutral-600">Amount Paid:</span>
                                <span class="text-sm font-medium text-primary-600">₱{{ number_format($totalPaid, 2) }}</span>
                            </div>
                            <div class="h-px bg-neutral-100"></div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-neutral-800">Remaining Balance:</span>
                                <span class="text-base font-semibold {{ $remainingBalance > 0 ? 'text-neutral-800' : 'text-primary-600' }}">
                                    ₱{{ number_format($remainingBalance, 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Payment Type Info -->
                        <div class="bg-neutral-50 rounded-xl p-4 mb-4 border border-neutral-100">
                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-2">Payment Type</p>
                            <p class="text-sm font-medium text-neutral-800">{{ $request->getPaymentTypeLabel() }}</p>
                            
                            @if($request->isMilestonePayment() && $request->project)
                                @php
                                    $totalMilestones = $request->project->milestones()->count();
                                    $paidMilestones = $request->project->milestones()->where('is_paid', true)->count();
                                @endphp
                                <p class="text-xs text-neutral-500 mt-1">{{ $paidMilestones }} of {{ $totalMilestones }} phases paid</p>
                            @elseif($request->isDownpayment())
                                @if(!$request->downpayment_paid)
                                    <p class="text-xs text-neutral-500 mt-1">{{ number_format($request->downpayment_percentage, 0) }}% downpayment required</p>
                                @elseif(!$request->remaining_balance_paid)
                                    <p class="text-xs text-neutral-500 mt-1">Downpayment received • Final payment pending</p>
                                @else
                                    <p class="text-xs text-neutral-500 mt-1">All payments completed</p>
                                @endif
                            @endif
                        </div>

                        <!-- Coupon Section -->
                        @if(!$request->coupon_auto_applied)
                            @if($request->applied_coupon_id && $request->appliedCoupon)
                                <!-- Applied Coupon Display -->
                                <div class="bg-neutral-50 rounded-xl p-4 mb-4 border border-neutral-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Coupon Applied</p>
                                        {{-- Don't show expired warning - discount already locked in --}}
                                    </div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm font-medium text-neutral-800 font-mono">{{ $request->appliedCoupon->code }}</p>
                                            <p class="text-xs text-neutral-500 mt-0.5">{{ $request->appliedCoupon->getDiscountLabel() }}</p>
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
                                                    onclick="return window.Alerts.confirmForm(event, 'Remove Coupon', 'Remove this coupon?')" 
                                                    class="w-full px-3 py-1.5 bg-white border border-neutral-200 text-neutral-600 text-xs font-medium rounded-lg hover:bg-neutral-50 transition-colors">
                                                Remove Coupon
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <!-- Coupon Input Form (AJAX) -->
                                <x-coupon-apply-form :serviceRequest="$request" />
                            @endif
                        @endif

                        <!-- Loyalty Points Redemption -->
                        <x-loyalty-points-apply-form :serviceRequest="$request" />

                        <!-- Pay Now Button (only if there's a balance due) -->
                        @if($currentPaymentDue > 0 && ($request->status === 'pending_payment' || $request->status === 'approved' || $request->status === 'in_progress'))
                            <div class="bg-neutral-50 rounded-xl p-4 mb-4 border border-neutral-100">
                                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Next Payment</p>
                                <p class="text-sm text-neutral-600">{{ $paymentDescription }}</p>
                                <p class="text-xl font-semibold text-neutral-800 mt-2">₱{{ number_format($currentPaymentDue, 2) }}</p>
                            </div>
                            
                            <a href="{{ route('client.maya.checkout', $request->id) }}" 
                               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors w-full">
                                <x-lucide-wallet class="w-4 h-4" />
                                Pay Now
                            </a>
                            
                            @if($request->payment_due_date)
                                <p class="text-xs text-neutral-500 mt-3 text-center">
                                    Due: {{ \Carbon\Carbon::parse($request->payment_due_date)->format('M j, Y') }}
                                </p>
                            @endif
                        @else
                            <div class="bg-success-50 rounded-xl p-4 text-center border border-success-100">
                                <x-lucide-check-circle class="w-10 h-10 text-success-600 mx-auto mb-2" />
                                <p class="text-sm font-medium text-neutral-800">All Payments Complete!</p>
                                <p class="text-xs text-neutral-500 mt-1">Thank you for your payment</p>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($request->status === 'pending_payment' || $request->status === 'approved')
                <!-- Fallback for requests without payment type set -->
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-neutral-50 rounded-xl">
                                <x-lucide-credit-card class="w-5 h-5 text-neutral-400" />
                            </div>
                            <h3 class="text-lg font-medium text-neutral-700">Payment Required</h3>
                        </div>
                        <p class="text-2xl font-semibold text-neutral-800 mb-4">₱{{ number_format($request->approved_budget, 2) }}</p>
                        
                        <!-- Coupon Section -->
                        @if(!$request->coupon_auto_applied)
                            @if($request->applied_coupon_id && $request->appliedCoupon)
                                <!-- Applied Coupon Display -->
                                <div class="bg-neutral-50 rounded-xl p-4 mb-4 border border-neutral-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Coupon Applied</p>
                                        {{-- Don't show expired warning - discount already locked in --}}
                                    </div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <p class="text-sm font-medium text-neutral-800 font-mono">{{ $request->appliedCoupon->code }}</p>
                                            <p class="text-xs text-neutral-500 mt-0.5">{{ $request->appliedCoupon->getDiscountLabel() }}</p>
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
                                                    onclick="return window.Alerts.confirmForm(event, 'Remove Coupon', 'Remove this coupon?')" 
                                                    class="w-full px-3 py-1.5 bg-white border border-neutral-200 text-neutral-600 text-xs font-medium rounded-lg hover:bg-neutral-50 transition-colors">
                                                Remove Coupon
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <!-- Coupon Input Form (AJAX) -->
                                <x-coupon-apply-form :serviceRequest="$request" />
                            @endif
                        @endif
                        
                        <!-- Loyalty Points Redemption -->
                        <x-loyalty-points-apply-form :serviceRequest="$request" />
                        
                        <a href="{{ route('client.maya.checkout', $request->id) }}" 
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors w-full">
                            <x-lucide-wallet class="w-4 h-4" />
                            Pay Now
                        </a>
                    </div>
                </div>
            @endif

            <!-- Request Details Card -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-info class="w-5 h-5 text-neutral-400" />
                        <h3 class="text-lg font-medium text-neutral-700">Request Information</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="pb-4 border-b border-neutral-100">
                        <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Service Type</dt>
                        <dd class="text-sm font-medium text-neutral-800 flex items-center">
                            <x-lucide-briefcase class="w-4 h-4 text-primary-600 mr-2" />
                            </svg>
                            {{ ucfirst(str_replace('_', ' ', $request->service_type)) }}
                        </dd>
                    </div>

                    <div class="pb-4 border-b border-neutral-100">
                        <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Contact Method</dt>
                        <dd class="text-sm font-medium text-neutral-800">{{ ucfirst($request->contact_method) }}</dd>
                        <dd class="text-sm text-neutral-500 mt-1">{{ $request->contact_details }}</dd>
                    </div>

                    <div class="pb-4 border-b border-neutral-100">
                        <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Priority Level</dt>
                        <dd class="text-sm mt-1">
                            @php
                                $priorityConfig = match($request->priority) {
                                    'high' => ['bg' => 'bg-error-50', 'text' => 'text-error-700', 'border' => 'border-error-200'],
                                    'medium' => ['bg' => 'bg-warning-50', 'text' => 'text-warning-700', 'border' => 'border-warning-200'],
                                    'low' => ['bg' => 'bg-success-50', 'text' => 'text-success-700', 'border' => 'border-success-200'],
                                    default => ['bg' => 'bg-neutral-50', 'text' => 'text-neutral-700', 'border' => 'border-neutral-200']
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $priorityConfig['bg'] }} {{ $priorityConfig['text'] }} border {{ $priorityConfig['border'] }}">
                                {{ ucfirst($request->priority) }} Priority
                            </span>
                        </dd>
                    </div>

                    @if($request->deadline)
                        <div class="pb-4 border-b border-neutral-100">
                            <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Project Deadline</dt>
                            <dd class="text-sm font-medium {{ \Carbon\Carbon::parse($request->deadline)->isPast() ? 'text-error-600' : 'text-neutral-800' }}">
                                {{ \Carbon\Carbon::parse($request->deadline)->format('F j, Y') }}
                            </dd>
                            @if(\Carbon\Carbon::parse($request->deadline)->isPast())
                                <dd class="text-xs text-error-600 font-medium mt-1 flex items-center">
                                    <x-lucide-alert-triangle class="w-3 h-3 mr-1" />
                                    Past Due
                                </dd>
                            @else
                                <dd class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($request->deadline)->diffForHumans() }}</dd>
                            @endif
                        </div>
                    @endif

                    @if($request->estimated_budget)
                        <div class="pb-4 border-b border-neutral-100">
                            <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Estimated Budget</dt>
                            <dd class="text-lg font-semibold text-primary-600">₱{{ number_format($request->estimated_budget, 2) }}</dd>
                        </div>
                    @endif

                    @if($request->approved_budget)
                        <div class="pb-4 border-b border-neutral-100">
                            <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Approved Budget</dt>
                            
                            @if($request->coupon_discount_amount > 0 || $request->loyalty_discount_amount > 0 || $request->tier_discount_amount > 0)
                                <!-- Show original budget with strikethrough -->
                                <dd class="text-sm text-neutral-400 line-through">₱{{ number_format($request->getOriginalBudget(), 2) }}</dd>
                                
                                <!-- Show discounts -->
                                @if($request->tier_discount_amount > 0)
                                    <dd class="text-xs text-info-600 flex items-center mt-1">
                                        <x-lucide-award class="w-3 h-3 mr-1" />
                                        {{ ucfirst($request->tier_at_approval ?? 'Tier') }} ({{ $request->tier_discount_percentage }}%): -₱{{ number_format($request->tier_discount_amount, 2) }}
                                    </dd>
                                @endif
                                
                                @if($request->coupon_discount_amount > 0)
                                    <dd class="text-xs text-success-600 flex items-center mt-1">
                                        <x-lucide-ticket class="w-3 h-3 mr-1" />
                                        Coupon: -₱{{ number_format($request->coupon_discount_amount, 2) }}
                                    </dd>
                                @endif
                                
                                @if($request->loyalty_discount_amount > 0)
                                    <dd class="text-xs text-warning-600 flex items-center mt-1">
                                        <x-lucide-star class="w-3 h-3 mr-1" />
                                        Loyalty Points: -₱{{ number_format($request->loyalty_discount_amount, 2) }}
                                    </dd>
                                @endif
                                
                                <!-- Final budget -->
                                <dd class="text-lg font-semibold text-success-600 mt-2">₱{{ number_format($request->approved_budget, 2) }}</dd>
                            @else
                                <dd class="text-lg font-semibold text-success-600">₱{{ number_format($request->approved_budget, 2) }}</dd>
                            @endif
                            
                            @if($request->payment_type)
                                @php
                                    $totalPaid = $request->getTotalPaid();
                                    $remainingBalance = $request->getRemainingPaymentBalance();
                                @endphp
                                @if($totalPaid > 0)
                                    <dd class="text-xs text-neutral-500 mt-2">
                                        <span class="font-medium">Paid:</span> 
                                        <span class="text-success-600 font-medium">₱{{ number_format($totalPaid, 2) }}</span>
                                    </dd>
                                    @if($remainingBalance > 0)
                                        <dd class="text-xs text-neutral-500 mt-1">
                                            <span class="font-medium">Balance:</span> 
                                            <span class="text-error-600 font-medium">₱{{ number_format($remainingBalance, 2) }}</span>
                                        </dd>
                                    @endif
                                @endif
                            @endif
                        </div>
                    @else
                        @if($request->estimated_budget)
                            <div>
                                <dt class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Estimated Budget</dt>
                                <dd class="text-lg font-semibold text-primary-600">₱{{ number_format($request->estimated_budget, 2) }}</dd>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Payment Instructions (if applicable) -->
            @if($request->payment_instructions && ($request->status === 'pending_payment' || $request->status === 'approved'))
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-neutral-100">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-warning-50 rounded-lg">
                                <x-lucide-info class="w-4 h-4 text-warning-600" />
                            </div>
                            <h3 class="text-lg font-medium text-neutral-700">Payment Instructions</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-sm max-w-none">
                            <p class="text-sm text-neutral-600 leading-relaxed whitespace-pre-line">{{ $request->payment_instructions }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Admin Notes -->
            @if($request->admin_notes)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-neutral-100">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-primary-50 rounded-lg">
                                <x-lucide-file-text class="w-4 h-4 text-primary-600" />
                            </div>
                            <h3 class="text-lg font-medium text-neutral-700">Notes from Admin</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-sm max-w-none">
                            <p class="text-sm text-neutral-600 leading-relaxed whitespace-pre-line">{{ $request->admin_notes }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
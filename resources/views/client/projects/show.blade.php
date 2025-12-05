@extends('client.layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Projects', 'route' => 'client.tasks', 'icon' => 'folder-kanban'],
        ['label' => Str::limit($project->title, 30), 'icon' => 'file-text'],
    ]" />

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-success-50 border border-success-200 p-4 rounded-xl">
            <div class="flex items-center">
                <x-lucide-check-circle class="w-5 h-5 text-success-500 mr-3 flex-shrink-0" />
                <p class="text-success-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-error-50 border border-error-200 p-4 rounded-xl">
            <div class="flex items-center">
                <x-lucide-alert-circle class="w-5 h-5 text-error-500 mr-3 flex-shrink-0" />
                <p class="text-error-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Project Header Card -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
                <!-- Project ID Badge -->
                <div class="inline-flex items-center space-x-3 mb-3">
                    <span class="text-xs font-mono font-medium text-neutral-500 bg-neutral-100 px-3 py-1.5 rounded-lg">
                        PROJ-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    @php
                        $statusConfig = match($project->status) {
                            'active' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-700', 'label' => 'Active'],
                            'in_progress' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-700', 'label' => 'In Progress'],
                            'review' => ['bg' => 'bg-warning-50', 'text' => 'text-warning-700', 'label' => 'Under Review'],
                            'completed' => ['bg' => 'bg-success-50', 'text' => 'text-success-700', 'label' => 'Completed'],
                            'cancelled' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-600', 'label' => 'Cancelled'],
                            default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-600', 'label' => 'Unknown']
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                        {{ $statusConfig['label'] }}
                    </span>
                    @if($project->priority)
                        @php
                            $priorityConfig = match($project->priority) {
                                'urgent' => ['bg' => 'bg-error-50', 'text' => 'text-error-700'],
                                'high' => ['bg' => 'bg-error-50', 'text' => 'text-error-700'],
                                'medium' => ['bg' => 'bg-warning-50', 'text' => 'text-warning-700'],
                                'low' => ['bg' => 'bg-success-50', 'text' => 'text-success-700'],
                                default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-600']
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $priorityConfig['bg'] }} {{ $priorityConfig['text'] }}">
                            {{ ucfirst($project->priority) }} Priority
                        </span>
                    @endif
                </div>

                <!-- Project Title -->
                <h1 class="text-2xl font-semibold text-neutral-800 mb-3">{{ $project->title }}</h1>
                
                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-500">
                    <div class="flex items-center gap-2">
                        <x-lucide-clock class="w-4 h-4 text-neutral-400" />
                        <span>Created {{ \Carbon\Carbon::parse($project->created_at)->format('M j, Y') }}</span>
                    </div>
                    @if($project->service_type)
                        <span class="text-neutral-300">•</span>
                        <div class="flex items-center gap-2">
                            <x-lucide-briefcase class="w-4 h-4 text-neutral-400" />
                            <span>{{ ucfirst(str_replace('_', ' ', $project->service_type)) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Message Admin Button for Active Projects --}}
                @if(in_array($project->status, ['active', 'in_progress', 'review']))
                    <a href="{{ route('client.messages.show', $project->id) }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <x-lucide-message-square class="w-5 h-5" />
                        Message Admin
                    </a>
                @endif
                
                {{-- Request Revision Button for Completed/Review Projects --}}
                @if(in_array($project->status, ['completed', 'review']))
                    <button onclick="openRevisionModal()" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-neutral-200 text-neutral-700 font-medium rounded-lg hover:bg-neutral-50 transition-colors">
                        <x-lucide-refresh-cw class="w-5 h-5" />
                        Request Revision
                    </button>
                @endif
                
                @if($feedback)
                    <a href="{{ route('client.feedback') }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-primary-200 text-primary-600 font-medium rounded-lg hover:bg-primary-50 transition-colors">
                        <x-lucide-check-circle class="w-5 h-5" />
                        Feedback Submitted
                    </a>
                @elseif($project->status === 'completed')
                    <a href="{{ route('client.feedback.create', $project->id) }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <x-lucide-star class="w-5 h-5" />
                        Leave Feedback
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Description -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                <h3 class="text-base font-medium text-neutral-800 mb-4 flex items-center">
                    <x-lucide-file-text class="w-5 h-5 mr-2 text-primary-500" />
                    Project Description
                </h3>
                <div class="text-neutral-600 leading-relaxed whitespace-pre-line">{{ $project->description }}</div>
            </div>

            {{-- Service Requirements Section --}}
            @if($project->serviceRequest && (!empty($project->serviceRequest->requested_features) || !empty($project->serviceRequest->requested_skills) || !empty($project->serviceRequest->template_features) || !empty($project->serviceRequest->template_skills)))
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-medium text-neutral-800 flex items-center">
                            <x-lucide-layers class="w-5 h-5 mr-2 text-secondary-500" />
                            Service Requirements
                        </h3>
                        @if($project->serviceRequest->has_customizations)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-primary-50 text-primary-700">
                                <x-lucide-edit-3 class="w-3 h-3 mr-1" />
                                You Customized This
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                <x-lucide-copy class="w-3 h-3 mr-1" />
                                Standard Template
                            </span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Features / What's Included --}}
                        @php
                            $sr = $project->serviceRequest;
                            $displayFeatures = $sr->effective_features ?? [];
                        @endphp
                        @if(!empty($displayFeatures))
                            <div class="bg-success-50/50 p-4 rounded-xl border border-success-100">
                                <h4 class="text-sm font-semibold text-neutral-700 mb-3 flex items-center">
                                    <x-lucide-check-circle class="w-4 h-4 mr-2 text-success-500" />
                                    What's Included
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($displayFeatures as $feature)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white text-success-700 border border-success-200 shadow-sm">
                                            {{ $feature }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        {{-- Skills Required --}}
                        @php
                            $displaySkills = $sr->effective_skills ?? [];
                        @endphp
                        @if(!empty($displaySkills))
                            <div class="bg-primary-50/50 p-4 rounded-xl border border-primary-100">
                                <h4 class="text-sm font-semibold text-neutral-700 mb-3 flex items-center">
                                    <x-lucide-wrench class="w-4 h-4 mr-2 text-primary-500" />
                                    Skills Being Applied
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($displaySkills as $skill)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white text-primary-700 border border-primary-200 shadow-sm">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Tasks -->
            @if($tasks && count($tasks) > 0)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <h3 class="text-base font-medium text-neutral-800 mb-4 flex items-center">
                        <x-lucide-clipboard-list class="w-5 h-5 mr-2 text-primary-500" />
                        Project Tasks
                        <span class="ml-2 bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full text-xs font-medium">{{ count($tasks) }}</span>
                    </h3>
                    <div class="space-y-4">
                        @foreach($tasks as $task)
                            <div class="border border-neutral-100 rounded-xl p-4 hover:border-primary-200 hover:bg-neutral-50/50 transition-colors">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-neutral-800 mb-1">{{ $task->taskTitle }}</h4>
                                        <p class="text-sm text-neutral-500">{{ $task->taskDescription }}</p>
                                    </div>
                                    @php
                                        $taskStatusConfig = match($task->status) {
                                            'pending' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700'],
                                            'in_progress' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-700'],
                                            'completed' => ['bg' => 'bg-success-50', 'text' => 'text-success-700'],
                                            'cancelled' => ['bg' => 'bg-error-50', 'text' => 'text-error-700'],
                                            default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700']
                                        };
                                    @endphp
                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $taskStatusConfig['bg'] }} {{ $taskStatusConfig['text'] }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </div>
                                
                                {{-- Task Revision Button for Completed Tasks --}}
                                @if($task->status === 'completed')
                                    <div class="mt-3 pt-3 border-t border-neutral-100">
                                        <button 
                                            type="button"
                                            onclick="openTaskRevisionModal({{ $task->taskID }}, '{{ addslashes($task->taskTitle) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-warning-700 bg-warning-50 border border-warning-200 rounded-lg hover:bg-warning-100 transition-colors">
                                            <x-lucide-refresh-cw class="w-3.5 h-3.5" />
                                            Request Revision for This Task
                                        </button>
                                    </div>
                                @endif
                                
                                <div class="flex items-center gap-4 text-xs text-neutral-500 mt-3">
                                    @if($task->assigned_to_name)
                                        <div class="flex items-center gap-1">
                                            <x-lucide-user class="w-3.5 h-3.5 text-neutral-400" />
                                            {{ $task->assigned_to_name }}
                                        </div>
                                    @endif
                                    @if($task->deadline)
                                        <div class="flex items-center gap-1">
                                            <x-lucide-calendar class="w-3.5 h-3.5 text-neutral-400" />
                                            {{ \Carbon\Carbon::parse($task->deadline)->format('M j, Y') }}
                                        </div>
                                    @endif
                                    @if($task->progress_percentage > 0)
                                        <div class="flex items-center gap-1">
                                            <x-lucide-zap class="w-3.5 h-3.5 text-neutral-400" />
                                            {{ $task->progress_percentage }}% Complete
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Team Members -->
            @if($assignments && count($assignments) > 0)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <h3 class="text-base font-medium text-neutral-800 mb-4 flex items-center">
                        <x-lucide-users class="w-5 h-5 mr-2 text-primary-500" />
                        Team Members
                        <span class="ml-2 bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full text-xs font-medium">{{ count($assignments) }}</span>
                    </h3>
                    <div class="space-y-4">
                        @foreach($assignments as $assignment)
                            <div class="border border-neutral-100 rounded-xl p-4 hover:border-primary-200 hover:bg-neutral-50/50 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="shrink-0 w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                        <x-lucide-user class="w-6 h-6 text-primary-600" />
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-neutral-800">{{ $assignment->adiutor_name }}</h4>
                                        @if($assignment->title)
                                            <p class="text-sm text-neutral-600">{{ $assignment->title }}</p>
                                        @endif
                                        @if($assignment->bio)
                                            <p class="text-sm text-neutral-500 mt-1">{{ Str::limit($assignment->bio, 100) }}</p>
                                        @endif
                                        <div class="flex items-center gap-3 mt-2 text-xs text-neutral-500">
                                            @php
                                                $assignmentStatusConfig = match($assignment->status) {
                                                    'pending' => ['bg' => 'bg-warning-50', 'text' => 'text-warning-700'],
                                                    'accepted' => ['bg' => 'bg-success-50', 'text' => 'text-success-700'],
                                                    'in_progress' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-700'],
                                                    'completed' => ['bg' => 'bg-success-50', 'text' => 'text-success-700'],
                                                    'declined' => ['bg' => 'bg-error-50', 'text' => 'text-error-700'],
                                                    default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700']
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $assignmentStatusConfig['bg'] }} {{ $assignmentStatusConfig['text'] }}">
                                                {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                            </span>
                                            @if($assignment->agreed_rate)
                                                <span>Rate: ₱{{ number_format($assignment->agreed_rate, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Status Card (if service request has payments) -->
            @if($serviceRequest && $serviceRequest->approved_budget && $serviceRequest->payment_type)
                @php
                    $totalPaid = $serviceRequest->getTotalPaid();
                    $totalBudget = $serviceRequest->approved_budget;
                    $remainingBalance = $serviceRequest->getRemainingPaymentBalance();
                    $currentPaymentDue = $serviceRequest->getCurrentPaymentAmountDue();
                    $paymentDescription = $serviceRequest->getCurrentPaymentDescription();
                    $paymentProgress = $serviceRequest->getPaymentProgress();
                @endphp
                
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                    <div class="bg-primary-600 p-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-white rounded-full mb-4">
                            @if($remainingBalance > 0)
                                <x-lucide-credit-card class="w-6 h-6 text-warning-600" />
                            @else
                                <x-lucide-check-circle class="w-6 h-6 text-success-600" />
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-semibold text-white mb-4">
                            {{ $remainingBalance > 0 ? 'Payment Status' : 'Fully Paid!' }}
                        </h3>
                        
                        <!-- Payment Progress Bar -->
                        <div class="bg-primary-100 rounded-xl p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-medium text-primary-800">Progress</span>
                                <span class="text-xs font-semibold text-primary-900">{{ number_format($paymentProgress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-primary-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-primary-800 h-2 rounded-full transition-all duration-500" style="width: {{ $paymentProgress }}%"></div>
                            </div>
                            <div class="flex justify-between items-center mt-2 text-xs text-primary-700">
                                <span>₱{{ number_format($totalPaid, 0) }} paid</span>
                                <span>₱{{ number_format($totalBudget, 0) }} total</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Breakdown -->
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Total Budget:</span>
                            <span class="text-sm font-medium text-neutral-800">₱{{ number_format($totalBudget, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-600">Amount Paid:</span>
                            <span class="text-sm font-medium text-success-600">₱{{ number_format($totalPaid, 2) }}</span>
                        </div>
                        <div class="h-px bg-neutral-200"></div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-neutral-700">Remaining Balance:</span>
                            <span class="text-lg font-semibold {{ $remainingBalance > 0 ? 'text-warning-600' : 'text-success-600' }}">
                                ₱{{ number_format($remainingBalance, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Milestone Payment Info -->
                    @if($serviceRequest->isMilestonePayment() && $serviceRequest->project)
                        @php
                            $totalMilestones = $serviceRequest->project->milestones()->count();
                            $paidMilestones = $serviceRequest->project->milestones()->where('is_paid', true)->count();
                        @endphp
                        <div class="bg-neutral-50 rounded-xl p-4 mb-4">
                            <p class="text-xs font-medium text-neutral-600 uppercase tracking-wide mb-2">Milestone Progress</p>
                            <p class="text-sm font-medium text-neutral-800">{{ $paidMilestones }} of {{ $totalMilestones }} phases paid</p>
                            
                            @if($currentPaymentDue > 0)
                                <div class="mt-3 pt-3 border-t border-neutral-200">
                                    <p class="text-xs text-neutral-600">Next Payment:</p>
                                    <p class="text-sm font-medium text-neutral-800">{{ $paymentDescription }}</p>
                                    <p class="text-lg font-semibold text-neutral-800 mt-1">₱{{ number_format($currentPaymentDue, 2) }}</p>
                                </div>
                            @endif
                        </div>
                    @elseif($serviceRequest->isDownpayment())
                        <div class="bg-neutral-50 rounded-xl p-4 mb-4">
                            <p class="text-xs font-medium text-neutral-600 uppercase tracking-wide mb-2">Payment Type</p>
                            @if(!$serviceRequest->downpayment_paid)
                                <p class="text-sm text-neutral-600">Downpayment Required</p>
                                <p class="text-sm font-medium text-neutral-800">{{ number_format($serviceRequest->downpayment_percentage, 0) }}% - ₱{{ number_format($currentPaymentDue, 2) }}</p>
                            @elseif(!$serviceRequest->remaining_balance_paid)
                                <p class="text-sm text-neutral-600">Downpayment Received</p>
                                <p class="text-sm font-medium text-neutral-800 mt-2">Final Payment Due:</p>
                                <p class="text-lg font-semibold text-neutral-800 mt-1">₱{{ number_format($currentPaymentDue, 2) }}</p>
                            @else
                                <p class="text-sm font-medium text-success-600">All payments completed</p>
                            @endif
                        </div>
                    @endif

                    <!-- Pay Now Button -->
                    @if($currentPaymentDue > 0 && in_array($serviceRequest->status, ['pending_payment', 'approved', 'in_progress']))
                        <a href="{{ route('client.maya.checkout', $serviceRequest->id) }}" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors w-full">
                            <x-lucide-wallet class="w-5 h-5" />
                            Pay Now
                        </a>
                    @else
                        <div class="bg-primary-50 rounded-xl p-4 text-center border border-primary-200">
                            <x-lucide-check-circle class="w-10 h-10 text-primary-500 mx-auto mb-2" />
                            <p class="text-sm font-medium text-primary-800">All Payments Complete!</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Project Details Card -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                <div class="bg-neutral-50 border-b border-neutral-100 p-4">
                    <h3 class="text-base font-medium text-neutral-800 flex items-center">
                        <x-lucide-info class="w-5 h-5 mr-2 text-neutral-600" />
                        Project Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($project->budget)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Project Budget</dt>
                            
                            @if($serviceRequest && ($serviceRequest->coupon_discount_amount > 0 || $serviceRequest->loyalty_discount_amount > 0))
                                <!-- Show breakdown if discounts applied -->
                                <dd class="text-sm text-neutral-500 line-through">₱{{ number_format($serviceRequest->getOriginalBudget(), 2) }}</dd>
                                
                                @if($serviceRequest->coupon_discount_amount > 0)
                                    <dd class="text-xs text-success-600 mt-1 flex items-center">
                                        <x-lucide-ticket class="w-3 h-3 mr-1" />
                                        Coupon: -₱{{ number_format($serviceRequest->coupon_discount_amount, 2) }}
                                    </dd>
                                @endif
                                
                                @if($serviceRequest->loyalty_discount_amount > 0)
                                    <dd class="text-xs text-primary-600 mt-1 flex items-center">
                                        <x-lucide-medal class="w-3 h-3 mr-1" />
                                        Loyalty: -₱{{ number_format($serviceRequest->loyalty_discount_amount, 2) }}
                                    </dd>
                                @endif
                                
                                <dd class="text-lg font-bold text-primary-600 mt-2">₱{{ number_format($project->budget, 2) }}</dd>
                                <dd class="text-xs text-neutral-500 mt-1">Final amount (after discounts)</dd>
                            @else
                                <!-- No discounts -->
                                <dd class="text-lg font-bold text-primary-600">₱{{ number_format($project->budget, 2) }}</dd>
                            @endif
                            
                            @if($project->budget_type)
                                <dd class="text-xs text-neutral-500 mt-1">{{ ucfirst($project->budget_type) }}</dd>
                            @endif
                        </div>
                    @endif

                    @if($project->deadline)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Deadline</dt>
                            <dd class="text-sm font-semibold {{ \Carbon\Carbon::parse($project->deadline)->isPast() ? 'text-error-600' : 'text-neutral-900' }}">
                                {{ \Carbon\Carbon::parse($project->deadline)->format('F j, Y') }}
                            </dd>
                            @if(\Carbon\Carbon::parse($project->deadline)->isPast())
                                <dd class="text-xs text-error-600 font-medium mt-1 flex items-center">
                                    <x-lucide-alert-triangle class="w-3 h-3 mr-1" />
                                    Past Due
                                </dd>
                            @else
                                <dd class="text-xs text-neutral-500 mt-1">{{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}</dd>
                            @endif
                        </div>
                    @endif

                    @if($project->started_at)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Started</dt>
                            <dd class="text-sm font-bold text-neutral-900">{{ \Carbon\Carbon::parse($project->started_at)->format('M j, Y') }}</dd>
                        </div>
                    @endif

                    @if($project->completed_at)
                        <div>
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Completed</dt>
                            <dd class="text-sm font-bold text-success-600">{{ \Carbon\Carbon::parse($project->completed_at)->format('M j, Y') }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Project Attachments Card -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                <div class="bg-neutral-50 border-b border-neutral-100 p-4">
                    <h3 class="text-base font-medium text-neutral-800 flex items-center">
                        <x-lucide-paperclip class="w-5 h-5 mr-2 text-neutral-600" />
                        Project Attachments
                    </h3>
                </div>
                <div class="p-6">
                    @if($documents && count($documents) > 0)
                        <div class="space-y-3">
                            @foreach($documents as $document)
                                <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-xl border border-neutral-100 hover:bg-neutral-100/50 transition-colors {{ $document->is_locked ? 'opacity-60' : '' }}">
                                    <div class="flex items-start space-x-3 flex-1 min-w-0">
                                        <!-- File Icon -->
                                        <div class="shrink-0">
                                            @if($document->is_locked)
                                                <x-lucide-lock class="w-6 h-6 text-warning-500" />
                                            @else
                                                @php
                                                    $extension = strtolower(pathinfo($document->fileName, PATHINFO_EXTENSION));
                                                    $iconConfig = match($extension) {
                                                        'pdf' => ['color' => 'text-error-600', 'bg' => 'bg-error-100'],
                                                        'doc', 'docx' => ['color' => 'text-primary-600', 'bg' => 'bg-primary-100'],
                                                        'xls', 'xlsx' => ['color' => 'text-success-600', 'bg' => 'bg-success-100'],
                                                        'jpg', 'jpeg', 'png', 'gif', 'svg' => ['color' => 'text-purple-600', 'bg' => 'bg-purple-100'],
                                                        'zip', 'rar', '7z' => ['color' => 'text-warning-600', 'bg' => 'bg-warning-100'],
                                                        default => ['color' => 'text-neutral-600', 'bg' => 'bg-neutral-100']
                                                    };
                                                @endphp
                                                <div class="w-10 h-10 rounded-lg {{ $iconConfig['bg'] }} flex items-center justify-center">
                                                    <x-lucide-file class="w-5 h-5 {{ $iconConfig['color'] }}" />
                                                </div>
                                            @endif
                                        </div>

                                        <!-- File Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <p class="text-sm font-medium text-neutral-800 truncate">{{ $document->fileName }}</p>
                                                @if($document->is_locked)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-warning-100 text-warning-700">
                                                        <x-lucide-lock class="w-3 h-3" />
                                                        Locked
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex flex-wrap items-center gap-2 text-xs text-neutral-500">
                                                @if($document->fileSize)
                                                    <span>{{ number_format($document->fileSize / 1024, 2) }} KB</span>
                                                @endif
                                                
                                                @if($document->task_name)
                                                    <span class="text-neutral-300">-</span>
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-primary-50 text-primary-700 font-medium">
                                                        <x-lucide-clipboard-list class="w-3 h-3" />
                                                        {{ $document->task_name }}
                                                    </span>
                                                @endif
                                                
                                                @if($document->uploaded_by_name)
                                                    <span class="text-neutral-300">-</span>
                                                    <span>Uploaded by {{ $document->uploaded_by_name }}</span>
                                                @endif
                                                
                                                @if($document->created_at)
                                                    <span class="text-neutral-300">-</span>
                                                    <span>{{ \Carbon\Carbon::parse($document->created_at)->format('M j, Y') }}</span>
                                                @endif
                                            </div>

                                            @if($document->is_locked)
                                                <p class="text-xs text-warning-700 mt-2 font-medium flex items-center">
                                                    <x-lucide-alert-triangle class="w-3 h-3 mr-1" />
                                                    Complete milestone payment to unlock this document
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Download Button -->
                                    <div class="shrink-0 ml-4">
                                        @if($document->is_locked)
                                            <button disabled class="inline-flex items-center gap-2 px-4 py-2 bg-neutral-200 text-neutral-400 font-medium rounded-lg cursor-not-allowed">
                                                <x-lucide-lock class="w-4 h-4" />
                                                Locked
                                            </button>
                                        @else
                                            <a href="{{ route('client.projects.documents.download', ['projectId' => $project->id, 'documentId' => $document->documentID]) }}" 
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                                                <x-lucide-download class="w-4 h-4" />
                                                Download
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <x-lucide-file class="w-16 h-16 text-neutral-300 mx-auto mb-4" />
                            <p class="text-neutral-500 font-medium">No attachments available yet</p>
                            <p class="text-neutral-400 text-sm mt-1">Documents will appear here once uploaded by your team</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revision Request Modal -->
<div id="revisionModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-warning-50 border-b border-warning-100 p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white rounded-full p-2 shadow-sm">
                        <x-lucide-refresh-cw class="w-6 h-6 text-warning-600" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-warning-800">Request Project Revision</h2>
                        <p class="text-warning-700 text-sm">Submit a revision request for this project</p>
                    </div>
                </div>
                <button onclick="closeRevisionModal()" class="text-warning-600 hover:text-warning-800 transition-colors">
                    <x-lucide-x class="w-6 h-6" />
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('client.revisions.project.store', $project->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Validation Errors -->
            @if($errors->any())
                <div class="bg-error-50 border-l-4 border-error-500 p-4 rounded-lg">
                    <div class="flex">
                        <x-lucide-alert-circle class="w-5 h-5 text-error-500 mr-3" />
                        <div>
                            <h3 class="text-sm font-medium text-error-800">Please fix the following errors:</h3>
                            <ul class="mt-2 text-sm text-error-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Project Info -->
            <div class="bg-neutral-50 border border-neutral-100 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <x-lucide-info class="w-5 h-5 text-primary-600 mt-0.5 shrink-0" />
                    <div>
                        <p class="text-sm font-medium text-neutral-800">{{ $project->title }}</p>
                        <p class="text-xs text-neutral-600 mt-1">This revision request will be reviewed by an administrator before being assigned to the team.</p>
                    </div>
                </div>
            </div>

            <!-- Revision Type Selection -->
            <div>
                <label class="block text-sm font-medium text-neutral-800 mb-3">Revision Scope</label>
                <div class="space-y-3">
                    <label class="flex items-start p-4 border border-neutral-200 rounded-xl cursor-pointer hover:border-warning-400 hover:bg-warning-50/50 transition-colors">
                        <input type="radio" name="revision_scope" value="project" checked class="mt-1 text-warning-600 focus:ring-warning-500" onchange="toggleTaskSelection(false)">
                        <div class="ml-3">
                            <span class="block font-medium text-neutral-800">Entire Project</span>
                            <span class="block text-sm text-neutral-600">Request revisions for the entire project deliverables</span>
                        </div>
                    </label>
                    
                    @if($tasks && count($tasks) > 0)
                        <label class="flex items-start p-4 border border-neutral-200 rounded-xl cursor-pointer hover:border-warning-400 hover:bg-warning-50/50 transition-colors">
                            <input type="radio" name="revision_scope" value="task" class="mt-1 text-warning-600 focus:ring-warning-500" onchange="toggleTaskSelection(true)">
                            <div class="ml-3">
                                <span class="block font-medium text-neutral-800">Specific Task(s)</span>
                                <span class="block text-sm text-neutral-600">Request revisions for specific tasks only</span>
                            </div>
                        </label>
                    @endif
                </div>
            </div>

            <!-- Task Selection (conditional) -->
            @if($tasks && count($tasks) > 0)
                <div id="taskSelectionSection" class="hidden space-y-3">
                    <label class="block text-sm font-medium text-neutral-800">Select Task(s)</label>
                    <div class="max-h-48 overflow-y-auto space-y-2 border border-neutral-200 rounded-xl p-3">
                        @foreach($tasks as $task)
                            <label class="flex items-start p-3 hover:bg-neutral-50 rounded-lg cursor-pointer">
                                <input type="checkbox" name="task_ids[]" value="{{ $task->taskID }}" class="mt-1 text-warning-600 focus:ring-warning-500">
                                <div class="ml-3 flex-1">
                                    <span class="block font-medium text-sm text-neutral-800">{{ $task->taskTitle }}</span>
                                    @if($task->taskDescription)
                                        <span class="block text-xs text-neutral-600 mt-1">{{ Str::limit($task->taskDescription, 60) }}</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reason for Revision -->
            <div>
                <label for="revision_reason" class="block text-sm font-medium text-neutral-800 mb-2">
                    Revision Details <span class="text-error-600">*</span>
                </label>
                <textarea 
                    id="revision_reason" 
                    name="reason" 
                    rows="5" 
                    required 
                    class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-warning-500 resize-none"
                    placeholder="Please describe in detail what needs to be revised and why...&#10;&#10;Examples:&#10;- The design doesn't match the approved mockups&#10;- Features are missing or not working as expected&#10;- Quality issues that need to be addressed"></textarea>
                <p class="text-xs text-neutral-500 mt-1">Minimum 20 characters. Be specific to help the team understand your concerns.</p>
            </div>

            <!-- Requested Due Date -->
            <div>
                <label for="revision_due_date" class="block text-sm font-medium text-neutral-800 mb-2">
                    Requested Completion Date (Optional)
                </label>
                <input 
                    type="date" 
                    id="revision_due_date" 
                    name="requested_due_date" 
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-warning-500">
                <p class="text-xs text-neutral-500 mt-1">When would you like the revision to be completed?</p>
            </div>

            <!-- Priority Level (Optional) -->
            <div>
                <label class="block text-sm font-medium text-neutral-800 mb-2">Priority Level</label>
                <select name="priority" class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-warning-500">
                    <option value="normal">Normal - No rush</option>
                    <option value="high">High - Needs attention soon</option>
                    <option value="urgent">Urgent - Critical issues</option>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-neutral-200">
                <button 
                    type="button" 
                    onclick="closeRevisionModal()"
                    class="px-6 py-3 bg-neutral-200 text-neutral-700 font-medium rounded-lg hover:bg-neutral-300 transition-colors">
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-warning-600 text-white font-medium rounded-lg hover:bg-warning-700 transition-colors">
                    <x-lucide-check-circle class="w-5 h-5" />
                    Submit Revision Request
                </button>
            </div>
        </form>
    </div>
    </div>
</div>

<!-- Task Revision Modal -->
<div id="taskRevisionModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4" onclick="if(event.target.parentElement.id === 'taskRevisionModal') closeTaskRevisionModal()">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="bg-warning-50 border-b border-warning-100 p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white p-2 rounded-lg shadow-sm">
                        <x-lucide-refresh-cw class="w-6 h-6 text-warning-600" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-warning-800">Request Task Revision</h2>
                        <p class="text-warning-700 text-sm" id="taskRevisionTitle"></p>
                    </div>
                </div>
                <button onclick="closeTaskRevisionModal()" class="text-warning-600 hover:text-warning-800 transition-colors">
                    <x-lucide-x class="w-6 h-6" />
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="taskRevisionForm" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Task Info -->
            <div class="bg-primary-50 border border-primary-100 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <x-lucide-info class="w-5 h-5 text-primary-600 mt-0.5 shrink-0" />
                    <div>
                        <p class="text-sm font-medium text-primary-800" id="taskRevisionInfoTitle"></p>
                        <p class="text-xs text-primary-700 mt-1">This revision will be automatically assigned to the adiutor who completed this task for review and corrections.</p>
                    </div>
                </div>
            </div>

            <!-- Reason for Revision -->
            <div>
                <label for="task_revision_reason" class="block text-sm font-medium text-neutral-800 mb-2">
                    What needs to be revised? <span class="text-error-600">*</span>
                </label>
                <textarea 
                    id="task_revision_reason" 
                    name="reason" 
                    rows="5" 
                    required 
                    minlength="20"
                    class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-warning-500 resize-none"
                    placeholder="Please describe specifically what needs to be revised in this task...&#10;&#10;Be clear and detailed so the adiutor can understand your concerns."></textarea>
                <p class="text-xs text-neutral-500 mt-1">Minimum 20 characters required.</p>
            </div>

            <!-- Requested Due Date -->
            <div>
                <label for="task_revision_due_date" class="block text-sm font-medium text-neutral-800 mb-2">
                    Requested Completion Date (Optional)
                </label>
                <input 
                    type="date" 
                    id="task_revision_due_date" 
                    name="requested_due_date" 
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-warning-500">
            </div>

            <!-- Priority Level -->
            <div>
                <label class="block text-sm font-medium text-neutral-800 mb-2">Priority Level</label>
                <select name="priority" class="w-full px-4 py-3 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-warning-500">
                    <option value="normal">Normal - No rush</option>
                    <option value="high">High - Needs attention soon</option>
                    <option value="urgent">Urgent - Critical issues</option>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-neutral-200">
                <button 
                    type="button" 
                    onclick="closeTaskRevisionModal()"
                    class="px-6 py-3 bg-neutral-200 text-neutral-700 font-medium rounded-lg hover:bg-neutral-300 transition-colors">
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-warning-600 text-white font-medium rounded-lg hover:bg-warning-700 transition-colors">
                    <x-lucide-check-circle class="w-5 h-5" />
                    Submit Task Revision
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
function openRevisionModal() {
    const modal = document.getElementById('revisionModal');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}

function closeRevisionModal() {
    const modal = document.getElementById('revisionModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

function toggleTaskSelection(show) {
    const section = document.getElementById('taskSelectionSection');
    if (show) {
        section.classList.remove('hidden');
    } else {
        section.classList.add('hidden');
        // Uncheck all task checkboxes
        document.querySelectorAll('input[name="task_ids[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    }
}

function openTaskRevisionModal(taskId, taskTitle) {
    console.log('Opening task revision modal for task:', taskId, taskTitle);
    
    const modal = document.getElementById('taskRevisionModal');
    
    if (!modal) {
        console.error('Modal element not found!');
        alert('ERROR: Modal element not found in DOM!');
        return;
    }
    
    console.log('Modal found:', modal);
    console.log('Modal parent:', modal.parentElement);
    console.log('Modal innerHTML length:', modal.innerHTML.length);
    
    const form = document.getElementById('taskRevisionForm');
    const titleElement = document.getElementById('taskRevisionTitle');
    const infoTitleElement = document.getElementById('taskRevisionInfoTitle');
    
    // Set form action to task-specific revision endpoint
    if (form) {
        form.action = `/client/revisions/tasks/${taskId}`;
        console.log('Form action set to:', form.action);
    } else {
        console.error('Form not found!');
    }
    
    // Set task title in modal
    if (titleElement) {
        titleElement.textContent = taskTitle;
        console.log('Title element updated');
    }
    if (infoTitleElement) {
        infoTitleElement.textContent = taskTitle;
        console.log('Info title element updated');
    }
    
    // Show modal
    console.log('Before show - display:', modal.style.display, 'class:', modal.className);
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    modal.style.visibility = 'visible';
    modal.style.opacity = '1';
    console.log('After show - display:', modal.style.display, 'class:', modal.className);
    
    // Force reflow
    modal.offsetHeight;
    
    console.log('Final computed style:', window.getComputedStyle(modal).display);
    console.log('Final visibility:', window.getComputedStyle(modal).visibility);
    console.log('Final opacity:', window.getComputedStyle(modal).opacity);
    console.log('Final z-index:', window.getComputedStyle(modal).zIndex);
}

function closeTaskRevisionModal() {
    const modal = document.getElementById('taskRevisionModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
    
    // Reset form
    const form = document.getElementById('taskRevisionForm');
    if (form) {
        form.reset();
    }
}

// Reopen modal if there are validation errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openRevisionModal();
    });
@endif
</script>

@endsection

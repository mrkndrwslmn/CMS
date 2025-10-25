@extends('client.layout')

@section('title', 'Project Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('client.tasks') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Projects
        </a>
    </div>

<<<<<<< HEAD
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-success-50 border-l-4 border-success-500 p-4 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-success-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-success-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-error-50 border-l-4 border-error-500 p-4 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-error-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-error-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

=======
>>>>>>> 7c71488 (Initial commit from Princess)
    <!-- Project Header Card -->
    <div class="glass-card p-8 mb-6 border-l-4 {{ match($project->status) {
        'active' => 'border-primary-500',
        'in_progress' => 'border-accent-500',
        'review' => 'border-warning-500',
        'completed' => 'border-success-600',
        'cancelled' => 'border-error-500',
        default => 'border-neutral-500'
    } }}">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
                <!-- Project ID Badge -->
                <div class="inline-flex items-center space-x-3 mb-3">
                    <span class="text-xs font-mono font-bold text-neutral-500 bg-neutral-100 px-3 py-1.5 rounded-lg">
                        PROJ-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                    @php
                        $statusConfig = match($project->status) {
                            'active' => ['bg' => 'bg-primary-500', 'text' => 'text-white', 'label' => 'Active'],
                            'in_progress' => ['bg' => 'bg-accent-500', 'text' => 'text-white', 'label' => 'In Progress'],
                            'review' => ['bg' => 'bg-warning-500', 'text' => 'text-white', 'label' => 'Under Review'],
                            'completed' => ['bg' => 'bg-success-600', 'text' => 'text-white', 'label' => 'Completed'],
                            'cancelled' => ['bg' => 'bg-error-500', 'text' => 'text-white', 'label' => 'Cancelled'],
                            default => ['bg' => 'bg-neutral-500', 'text' => 'text-white', 'label' => 'Unknown']
                        };
                    @endphp
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} shadow-md">
                        {{ $statusConfig['label'] }}
                    </span>
                    @if($project->priority)
                        @php
                            $priorityConfig = match($project->priority) {
                                'urgent' => ['bg' => 'bg-error-100', 'text' => 'text-error-800', 'border' => 'border-error-300'],
                                'high' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-800', 'border' => 'border-warning-300'],
                                'medium' => ['bg' => 'bg-primary-100', 'text' => 'text-primary-800', 'border' => 'border-primary-300'],
                                'low' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'border' => 'border-neutral-300'],
                                default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-800', 'border' => 'border-neutral-300']
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $priorityConfig['bg'] }} {{ $priorityConfig['text'] }} border {{ $priorityConfig['border'] }}">
                            {{ ucfirst($project->priority) }} Priority
                        </span>
                    @endif
                </div>

                <!-- Project Title -->
                <h1 class="heading-serif text-3xl text-neutral-900 mb-3 leading-tight">{{ $project->title }}</h1>
                
                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-neutral-600">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Created {{ \Carbon\Carbon::parse($project->created_at)->format('M j, Y') }}</span>
                    </div>
                    @if($project->service_type)
                        <span class="text-neutral-300">•</span>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/>
                            </svg>
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
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-accent-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Message Admin
                    </a>
                @endif
                
<<<<<<< HEAD
                {{-- Request Revision Button for Completed/Review Projects --}}
                @if(in_array($project->status, ['completed', 'review']))
                    <button onclick="openRevisionModal()" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-warning-500 to-warning-600 text-white font-bold rounded-lg hover:from-warning-600 hover:to-warning-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Request Revision
                    </button>
                @endif
                
=======
>>>>>>> 7c71488 (Initial commit from Princess)
                @if($feedback)
                    <a href="{{ route('client.feedback') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-success-500 text-success-600 font-semibold rounded-lg hover:bg-success-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Feedback Submitted
                    </a>
                @elseif($project->status === 'completed')
                    <a href="{{ route('client.feedback.create', $project->id) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-600 text-white font-bold rounded-lg hover:from-primary-600 hover:to-accent-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
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
            <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Project Description
                    </h3>
                    <div class="prose prose-neutral prose-sm max-w-none">
                        <p class="text-neutral-700 leading-relaxed whitespace-pre-line">{{ $project->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Tasks -->
            @if($tasks && count($tasks) > 0)
                <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Project Tasks
                            <span class="ml-2 bg-neutral-100 text-neutral-700 px-2 py-1 rounded-full text-xs font-bold">{{ count($tasks) }}</span>
                        </h3>
                        <div class="space-y-3">
                            @foreach($tasks as $task)
                                <div class="border border-neutral-200 rounded-lg p-4 hover:border-neutral-300 hover:shadow-sm transition-all">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-neutral-900 mb-1">{{ $task->taskTitle }}</h4>
                                            <p class="text-sm text-neutral-600">{{ $task->taskDescription }}</p>
                                        </div>
                                        @php
                                            $taskStatusConfig = match($task->status) {
                                                'pending' => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700'],
                                                'in_progress' => ['bg' => 'bg-accent-100', 'text' => 'text-accent-700'],
                                                'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-700'],
                                                'cancelled' => ['bg' => 'bg-error-100', 'text' => 'text-error-700'],
                                                default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700']
                                            };
                                        @endphp
                                        <span class="ml-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $taskStatusConfig['bg'] }} {{ $taskStatusConfig['text'] }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 text-xs text-neutral-500 mt-3">
                                        @if($task->assigned_to_name)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $task->assigned_to_name }}
                                            </div>
                                        @endif
                                        @if($task->deadline)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($task->deadline)->format('M j, Y') }}
                                            </div>
                                        @endif
                                        @if($task->progress_percentage > 0)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                {{ $task->progress_percentage }}% Complete
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Team Members -->
            @if($assignments && count($assignments) > 0)
                <div class="glass-card overflow-hidden border-l-4 border-neutral-300">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Team Members
                            <span class="ml-2 bg-neutral-100 text-neutral-700 px-2 py-1 rounded-full text-xs font-bold">{{ count($assignments) }}</span>
                        </h3>
                        <div class="space-y-3">
                            @foreach($assignments as $assignment)
                                <div class="border border-neutral-200 rounded-lg p-4 hover:border-neutral-300 hover:shadow-sm transition-all">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-neutral-900">{{ $assignment->adiutor_name }}</h4>
                                            @if($assignment->title)
                                                <p class="text-sm text-neutral-600">{{ $assignment->title }}</p>
                                            @endif
                                            @if($assignment->bio)
                                                <p class="text-sm text-neutral-500 mt-1">{{ Str::limit($assignment->bio, 100) }}</p>
                                            @endif
                                            <div class="flex items-center gap-3 mt-2 text-xs text-neutral-500">
                                                @php
                                                    $assignmentStatusConfig = match($assignment->status) {
                                                        'pending' => ['bg' => 'bg-warning-100', 'text' => 'text-warning-700'],
                                                        'accepted' => ['bg' => 'bg-success-100', 'text' => 'text-success-700'],
                                                        'in_progress' => ['bg' => 'bg-accent-100', 'text' => 'text-accent-700'],
                                                        'completed' => ['bg' => 'bg-success-100', 'text' => 'text-success-700'],
                                                        'declined' => ['bg' => 'bg-error-100', 'text' => 'text-error-700'],
                                                        default => ['bg' => 'bg-neutral-100', 'text' => 'text-neutral-700']
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $assignmentStatusConfig['bg'] }} {{ $assignmentStatusConfig['text'] }}">
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
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
<<<<<<< HEAD
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

                        <!-- Milestone Payment Info -->
                        @if($serviceRequest->isMilestonePayment() && $serviceRequest->project)
                            @php
                                $totalMilestones = $serviceRequest->project->milestones()->count();
                                $paidMilestones = $serviceRequest->project->milestones()->where('is_paid', true)->count();
                            @endphp
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 mb-4">
                                <p class="text-xs font-semibold text-white/90 uppercase tracking-wide mb-2">Milestone Progress</p>
                                <p class="text-sm font-bold text-white">{{ $paidMilestones }} of {{ $totalMilestones }} phases paid</p>
                                
                                @if($currentPaymentDue > 0)
                                    <div class="mt-3 pt-3 border-t border-white/30">
                                        <p class="text-xs text-white/80">Next Payment:</p>
                                        <p class="text-sm font-bold text-white">{{ $paymentDescription }}</p>
                                        <p class="text-xl font-bold text-white mt-1">₱{{ number_format($currentPaymentDue, 2) }}</p>
                                    </div>
                                @endif
                            </div>
                        @elseif($serviceRequest->isDownpayment())
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 mb-4">
                                <p class="text-xs font-semibold text-white/90 uppercase tracking-wide mb-2">Payment Type</p>
                                @if(!$serviceRequest->downpayment_paid)
                                    <p class="text-sm text-white/80">Downpayment Required</p>
                                    <p class="text-sm font-bold text-white">{{ number_format($serviceRequest->downpayment_percentage, 0) }}% • ₱{{ number_format($currentPaymentDue, 2) }}</p>
                                @elseif(!$serviceRequest->remaining_balance_paid)
                                    <p class="text-sm text-white/80">Downpayment Received ✓</p>
                                    <p class="text-sm font-bold text-white mt-2">Final Payment Due:</p>
                                    <p class="text-xl font-bold text-white mt-1">₱{{ number_format($currentPaymentDue, 2) }}</p>
                                @else
                                    <p class="text-sm font-bold text-white">All payments completed ✓</p>
                                @endif
                            </div>
                        @endif

                        <!-- Pay Now Button -->
                        @if($currentPaymentDue > 0 && in_array($serviceRequest->status, ['pending_payment', 'approved', 'in_progress']))
                            <a href="{{ route('client.maya.checkout', $serviceRequest->id) }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-white text-secondary-600 font-bold rounded-lg hover:bg-secondary-50 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 w-full">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Pay Now
                            </a>
                        @else
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">
                                <svg class="w-12 h-12 text-white mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-bold text-white">All Payments Complete!</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

=======
>>>>>>> 7c71488 (Initial commit from Princess)
            <!-- Project Details Card -->
            <div class="glass-card overflow-hidden">
                <div class="bg-gradient-to-r from-neutral-700 to-neutral-800 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Project Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($project->budget)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Budget</dt>
                            <dd class="text-lg font-bold text-primary-600">₱{{ number_format($project->budget, 2) }}</dd>
                            @if($project->budget_type)
                                <dd class="text-xs text-neutral-500 mt-1">{{ ucfirst($project->budget_type) }}</dd>
                            @endif
                        </div>
                    @endif

                    @if($project->deadline)
                        <div class="pb-4 border-b border-neutral-200">
                            <dt class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-1">Deadline</dt>
                            <dd class="text-sm font-bold {{ \Carbon\Carbon::parse($project->deadline)->isPast() ? 'text-error-600' : 'text-neutral-900' }}">
                                {{ \Carbon\Carbon::parse($project->deadline)->format('F j, Y') }}
                            </dd>
                            @if(\Carbon\Carbon::parse($project->deadline)->isPast())
                                <dd class="text-xs text-error-600 font-medium mt-1">Past Due</dd>
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
<<<<<<< HEAD

            <!-- Project Attachments Card -->
            <div class="glass-card overflow-hidden">
                <div class="bg-gradient-to-r from-neutral-700 to-neutral-800 p-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        Project Attachments
                    </h3>
                </div>
                <div class="p-6">
                    @if($documents && count($documents) > 0)
                        <div class="space-y-3">
                            @foreach($documents as $document)
                                <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-lg border border-neutral-200 hover:bg-neutral-100 transition-colors {{ $document->is_locked ? 'opacity-60' : '' }}">
                                    <div class="flex items-start space-x-3 flex-1 min-w-0">
                                        <!-- File Icon -->
                                        <div class="flex-shrink-0">
                                            @if($document->is_locked)
                                                <svg class="w-6 h-6 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            @else
                                                @php
                                                    $extension = strtolower(pathinfo($document->fileName, PATHINFO_EXTENSION));
                                                    $iconConfig = match($extension) {
                                                        'pdf' => ['color' => 'text-red-600', 'bg' => 'bg-red-100'],
                                                        'doc', 'docx' => ['color' => 'text-blue-600', 'bg' => 'bg-blue-100'],
                                                        'xls', 'xlsx' => ['color' => 'text-green-600', 'bg' => 'bg-green-100'],
                                                        'jpg', 'jpeg', 'png', 'gif', 'svg' => ['color' => 'text-purple-600', 'bg' => 'bg-purple-100'],
                                                        'zip', 'rar', '7z' => ['color' => 'text-yellow-600', 'bg' => 'bg-yellow-100'],
                                                        default => ['color' => 'text-neutral-600', 'bg' => 'bg-neutral-100']
                                                    };
                                                @endphp
                                                <div class="w-10 h-10 rounded-lg {{ $iconConfig['bg'] }} flex items-center justify-center">
                                                    <svg class="w-5 h-5 {{ $iconConfig['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- File Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <p class="text-sm font-semibold text-neutral-900 truncate">{{ $document->fileName }}</p>
                                                @if($document->is_locked)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-100 text-warning-800 border border-warning-300">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                        Locked
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex flex-wrap items-center gap-2 text-xs text-neutral-500">
                                                @if($document->fileSize)
                                                    <span>{{ number_format($document->fileSize / 1024, 2) }} KB</span>
                                                @endif
                                                
                                                @if($document->task_name)
                                                    <span class="text-neutral-300">•</span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-primary-100 text-primary-700 font-medium">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                        </svg>
                                                        {{ $document->task_name }}
                                                    </span>
                                                @endif
                                                
                                                @if($document->uploaded_by_name)
                                                    <span class="text-neutral-300">•</span>
                                                    <span>Uploaded by {{ $document->uploaded_by_name }}</span>
                                                @endif
                                                
                                                @if($document->created_at)
                                                    <span class="text-neutral-300">•</span>
                                                    <span>{{ \Carbon\Carbon::parse($document->created_at)->format('M j, Y') }}</span>
                                                @endif
                                            </div>

                                            @if($document->is_locked)
                                                <p class="text-xs text-warning-700 mt-2 font-medium">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    Complete milestone payment to unlock this document
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Download Button -->
                                    <div class="flex-shrink-0 ml-4">
                                        @if($document->is_locked)
                                            <button disabled class="inline-flex items-center px-4 py-2 bg-neutral-200 text-neutral-400 font-medium rounded-lg cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Locked
                                            </button>
                                        @else
                                            <a href="{{ route('client.projects.documents.download', ['projectId' => $project->id, 'documentId' => $document->documentID]) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-sm hover:shadow-md">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                Download
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-neutral-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
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
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-warning-500 to-warning-600 p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 rounded-full p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Request Project Revision</h2>
                        <p class="text-white/80 text-sm">Submit a revision request for this project</p>
                    </div>
                </div>
                <button onclick="closeRevisionModal()" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('client.revisions.project.store', $project->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Validation Errors -->
            @if($errors->any())
                <div class="bg-error-50 border-l-4 border-error-500 p-4 rounded">
                    <div class="flex">
                        <svg class="w-5 h-5 text-error-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-error-800">Please fix the following errors:</h3>
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
            <div class="bg-neutral-50 border border-neutral-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-primary-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-neutral-900">{{ $project->title }}</p>
                        <p class="text-xs text-neutral-600 mt-1">This revision request will be reviewed by an administrator before being assigned to the team.</p>
                    </div>
                </div>
            </div>

            <!-- Revision Type Selection -->
            <div>
                <label class="block text-sm font-semibold text-neutral-900 mb-3">Revision Scope</label>
                <div class="space-y-3">
                    <label class="flex items-start p-4 border-2 border-neutral-200 rounded-lg cursor-pointer hover:border-warning-500 hover:bg-warning-50 transition-all">
                        <input type="radio" name="revision_scope" value="project" checked class="mt-1 text-warning-600 focus:ring-warning-500" onchange="toggleTaskSelection(false)">
                        <div class="ml-3">
                            <span class="block font-medium text-neutral-900">Entire Project</span>
                            <span class="block text-sm text-neutral-600">Request revisions for the entire project deliverables</span>
                        </div>
                    </label>
                    
                    @if($tasks && count($tasks) > 0)
                        <label class="flex items-start p-4 border-2 border-neutral-200 rounded-lg cursor-pointer hover:border-warning-500 hover:bg-warning-50 transition-all">
                            <input type="radio" name="revision_scope" value="task" class="mt-1 text-warning-600 focus:ring-warning-500" onchange="toggleTaskSelection(true)">
                            <div class="ml-3">
                                <span class="block font-medium text-neutral-900">Specific Task(s)</span>
                                <span class="block text-sm text-neutral-600">Request revisions for specific tasks only</span>
                            </div>
                        </label>
                    @endif
                </div>
            </div>

            <!-- Task Selection (conditional) -->
            @if($tasks && count($tasks) > 0)
                <div id="taskSelectionSection" class="hidden space-y-3">
                    <label class="block text-sm font-semibold text-neutral-900">Select Task(s)</label>
                    <div class="max-h-48 overflow-y-auto space-y-2 border border-neutral-200 rounded-lg p-3">
                        @foreach($tasks as $task)
                            <label class="flex items-start p-3 hover:bg-neutral-50 rounded-lg cursor-pointer">
                                <input type="checkbox" name="task_ids[]" value="{{ $task->taskID }}" class="mt-1 text-warning-600 focus:ring-warning-500">
                                <div class="ml-3 flex-1">
                                    <span class="block font-medium text-sm text-neutral-900">{{ $task->taskTitle }}</span>
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
                <label for="revision_reason" class="block text-sm font-semibold text-neutral-900 mb-2">
                    Revision Details <span class="text-error-600">*</span>
                </label>
                <textarea 
                    id="revision_reason" 
                    name="reason" 
                    rows="5" 
                    required 
                    class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-warning-500 resize-none"
                    placeholder="Please describe in detail what needs to be revised and why...&#10;&#10;Examples:&#10;- The design doesn't match the approved mockups&#10;- Features are missing or not working as expected&#10;- Quality issues that need to be addressed"></textarea>
                <p class="text-xs text-neutral-500 mt-1">Minimum 20 characters. Be specific to help the team understand your concerns.</p>
            </div>

            <!-- Requested Due Date -->
            <div>
                <label for="revision_due_date" class="block text-sm font-semibold text-neutral-900 mb-2">
                    Requested Completion Date (Optional)
                </label>
                <input 
                    type="date" 
                    id="revision_due_date" 
                    name="requested_due_date" 
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-warning-500">
                <p class="text-xs text-neutral-500 mt-1">When would you like the revision to be completed?</p>
            </div>

            <!-- Priority Level (Optional) -->
            <div>
                <label class="block text-sm font-semibold text-neutral-900 mb-2">Priority Level</label>
                <select name="priority" class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-warning-500 focus:border-warning-500">
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
                    class="px-6 py-3 bg-neutral-200 text-neutral-700 font-semibold rounded-lg hover:bg-neutral-300 transition-colors">
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-warning-500 to-warning-600 text-white font-bold rounded-lg hover:from-warning-600 hover:to-warning-700 transition-all shadow-lg hover:shadow-xl">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Submit Revision Request
                    </span>
                </button>
            </div>
        </form>
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

// Reopen modal if there are validation errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openRevisionModal();
    });
@endif
</script>

=======
        </div>
    </div>
</div>
>>>>>>> 7c71488 (Initial commit from Princess)
@endsection

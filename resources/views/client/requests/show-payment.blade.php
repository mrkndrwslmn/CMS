@extends('client.layout')

@section('title', 'Request Details - Payment Required')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">{{ $request->project_name }}</h1>
                <p class="text-neutral-600 mt-2">Request #{{ $request->id }} • {{ $request->getStatusLabel() }}</p>
            </div>
            <span class="badge badge-{{ $request->getStatusColor() }} text-lg px-4 py-2">
                {{ $request->getStatusLabel() }}
            </span>
        </div>
    </div>

    @if($request->isPendingPayment() || $request->isApproved())
        <!-- Payment Information Card -->
        <div class="card mb-8 border-l-4 border-primary-500">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-2">Payment Required</h3>
                    <p class="text-neutral-600 mb-4">Your project has been approved! Please complete the payment to begin work on your project.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-neutral-900 mb-2">Payment Details</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Amount:</span>
                                    <span class="font-semibold text-xl text-primary-600">${{ number_format($request->approved_budget, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Payment Method:</span>
                                    <span class="font-medium">{{ $request->payment_method }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Due Date:</span>
                                    <span class="font-medium {{ $request->payment_due_date->isPast() ? 'text-error-600' : 'text-neutral-900' }}">
                                        {{ $request->payment_due_date->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if($request->payment_instructions)
                            <div>
                                <h4 class="font-medium text-neutral-900 mb-2">Payment Instructions</h4>
                                <div class="bg-neutral-50 rounded-lg p-4">
                                    <p class="text-sm text-neutral-700 whitespace-pre-line">{{ $request->payment_instructions }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    @if($request->payment_due_date->isPast())
                        <div class="mt-4 p-3 bg-error-50 border border-error-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-error-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-error-800">
                                    <span class="font-medium">Payment Overdue:</span> This payment is past due. Please contact us to discuss payment arrangements.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($request->isPaid())
        <!-- Payment Confirmed Card -->
        <div class="card mb-8 border-l-4 border-success-500">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-success-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0 5 5 0 01-10 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-success-900 mb-2">Payment Confirmed</h3>
                    <p class="text-success-700 mb-4">Thank you! Your payment has been confirmed and your project is now in progress.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Amount Paid:</span>
                                    <span class="font-semibold text-lg text-success-600">${{ number_format($request->approved_budget, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Payment Reference:</span>
                                    <span class="font-medium">{{ $request->payment_reference }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Confirmed Date:</span>
                                    <span class="font-medium">{{ $request->payment_confirmed_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Project Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="card mb-8">
                <h2 class="text-xl font-semibold text-neutral-900 mb-4">Project Description</h2>
                <div class="prose max-w-none">
                    <p class="text-neutral-700">{{ $request->request_description }}</p>
                </div>
            </div>

            @if($request->expectations)
                <div class="card mb-8">
                    <h2 class="text-xl font-semibold text-neutral-900 mb-4">Expectations</h2>
                    <div class="prose max-w-none">
                        <p class="text-neutral-700">{{ $request->expectations }}</p>
                    </div>
                </div>
            @endif

            @if($request->tasks->count() > 0)
                <!-- Tasks Progress -->
                <div class="card">
                    <h2 class="text-xl font-semibold text-neutral-900 mb-4">Project Tasks</h2>
                    <div class="space-y-4">
                        @foreach($request->tasks as $task)
                            <div class="border border-neutral-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-medium text-neutral-900">{{ $task->taskTitle }}</h3>
                                    <span class="badge badge-{{ $task->getStatusBadgeClass() }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-neutral-600 mb-3">{{ $task->taskDescription }}</p>
                                
                                @if($task->progress_percentage > 0)
                                    <div class="mb-2">
                                        <div class="flex items-center justify-between text-sm text-neutral-600 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $task->progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-neutral-200 rounded-full h-2">
                                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $task->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($task->allocated_budget)
                                    <div class="text-sm text-neutral-600">
                                        <span>Budget: ${{ number_format($task->allocated_budget, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Summary -->
            <div class="card">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Project Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Service Type:</span>
                        <span class="font-medium">{{ $request->service_type }}</span>
                    </div>
                    @if($request->estimated_budget)
                        <div class="flex justify-between">
                            <span class="text-neutral-600">Initial Budget:</span>
                            <span>${{ number_format($request->estimated_budget, 2) }}</span>
                        </div>
                    @endif
                    @if($request->approved_budget)
                        <div class="flex justify-between">
                            <span class="text-neutral-600">Approved Budget:</span>
                            <span class="font-medium text-primary-600">${{ number_format($request->approved_budget, 2) }}</span>
                        </div>
                    @endif
                    @if($request->deadline)
                        <div class="flex justify-between">
                            <span class="text-neutral-600">Deadline:</span>
                            <span class="font-medium">{{ $request->deadline->format('M d, Y') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Submitted:</span>
                        <span>{{ $request->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Budget Breakdown -->
            @if($request->isPaid() && $request->tasks->count() > 0)
                <div class="card">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Budget Breakdown</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-neutral-600">Total Budget:</span>
                            <span class="font-medium">${{ number_format($request->approved_budget, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600">Allocated:</span>
                            <span>${{ number_format($request->getTotalAllocatedBudget(), 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="text-neutral-600">Remaining:</span>
                            <span class="font-semibold {{ $request->getRemainingBudget() < 0 ? 'text-error-600' : 'text-success-600' }}">
                                ${{ number_format($request->getRemainingBudget(), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Contact Information -->
            <div class="card">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Contact Information</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-neutral-600">Preferred Method:</span>
                        <span class="font-medium block">{{ ucfirst($request->contact_method) }}</span>
                    </div>
                    <div>
                        <span class="text-neutral-600">Details:</span>
                        <span class="font-medium block">{{ $request->contact_details }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
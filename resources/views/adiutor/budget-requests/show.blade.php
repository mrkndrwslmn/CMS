@extends('adiutor.layouts.app')

@section('title', 'Budget Change Request Details')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Budget Requests', 'route' => 'adiutor.budget-requests.index', 'icon' => 'wallet'],
        ['label' => 'Request #' . $budgetRequest->id, 'icon' => 'file-text'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Budget Change Request</h1>
            <p class="text-sm text-neutral-500 mt-1">Request ID: #{{ $budgetRequest->id }}</p>
        </div>
        @php
            $statusConfig = match($budgetRequest->status) {
                'pending' => ['bg' => 'bg-warning-50', 'text' => 'text-warning-700', 'icon' => 'clock'],
                'approved' => ['bg' => 'bg-success-50', 'text' => 'text-success-700', 'icon' => 'check-circle'],
                'rejected' => ['bg' => 'bg-error-50', 'text' => 'text-error-700', 'icon' => 'x-circle'],
                default => ['bg' => 'bg-neutral-50', 'text' => 'text-neutral-600', 'icon' => 'circle-dashed']
            };
        @endphp
        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-sm font-medium rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
            <x-dynamic-component :component="'lucide-' . $statusConfig['icon']" class="w-3 h-3" />
            {{ ucfirst($budgetRequest->status) }}
        </span>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-2xl bg-success-50 border border-success-100 p-4">
        <div class="flex items-center gap-3">
            <div class="p-1.5 bg-success-100 rounded-lg">
                <x-lucide-check-circle-2 class="w-5 h-5 text-success-600" />
            </div>
            <p class="text-sm font-medium text-success-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Request Details Card -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-neutral-800 mb-4">Request Details</h2>
            
            <!-- Task Info -->
            <div class="mb-6 p-4 bg-neutral-50 rounded-xl">
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-primary-100 rounded-lg">
                        <x-lucide-clipboard-list class="w-5 h-5 text-primary-600" />
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-neutral-800">{{ $budgetRequest->task->taskTitle ?? 'Task' }}</h3>
                        <p class="text-sm text-neutral-500">
                            Project: {{ $budgetRequest->task->project->title ?? 'N/A' }}
                        </p>
                        <a href="{{ route('adiutor.tasks.show', $budgetRequest->task_id) }}" 
                           class="text-sm text-primary-600 hover:text-primary-700 inline-flex items-center gap-1 mt-1">
                            View Task
                            <x-lucide-external-link class="w-3 h-3" />
                        </a>
                    </div>
                </div>
            </div>

            <!-- Budget Comparison -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-neutral-100 rounded-xl text-center">
                    <p class="text-sm text-neutral-500 mb-1">Current Budget</p>
                    <p class="text-xl font-semibold text-neutral-800">₱{{ number_format($budgetRequest->current_budget, 2) }}</p>
                </div>
                <div class="flex items-center justify-center">
                    <x-lucide-arrow-right class="w-6 h-6 text-neutral-400" />
                </div>
                <div class="p-4 {{ $budgetRequest->requested_budget > $budgetRequest->current_budget ? 'bg-success-100' : 'bg-error-100' }} rounded-xl text-center">
                    <p class="text-sm {{ $budgetRequest->requested_budget > $budgetRequest->current_budget ? 'text-success-600' : 'text-error-600' }} mb-1">Requested Budget</p>
                    <p class="text-xl font-semibold {{ $budgetRequest->requested_budget > $budgetRequest->current_budget ? 'text-success-800' : 'text-error-800' }}">
                        ₱{{ number_format($budgetRequest->requested_budget, 2) }}
                    </p>
                </div>
            </div>

            <!-- Budget Difference -->
            @php
                $difference = $budgetRequest->requested_budget - $budgetRequest->current_budget;
                $percentChange = $budgetRequest->current_budget > 0 
                    ? (($difference / $budgetRequest->current_budget) * 100) 
                    : 100;
            @endphp
            <div class="flex items-center justify-center gap-4 mb-6 p-3 {{ $difference > 0 ? 'bg-success-50' : 'bg-error-50' }} rounded-xl">
                <span class="text-sm {{ $difference > 0 ? 'text-success-600' : 'text-error-600' }}">
                    {{ $difference > 0 ? '+' : '' }}₱{{ number_format($difference, 2) }}
                </span>
                <span class="text-neutral-400">|</span>
                <span class="text-sm {{ $difference > 0 ? 'text-success-600' : 'text-error-600' }}">
                    {{ $difference > 0 ? '+' : '' }}{{ number_format($percentChange, 1) }}%
                </span>
            </div>

            <!-- Reason -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-neutral-700 mb-2">Reason for Request</h3>
                <div class="p-4 bg-neutral-50 rounded-xl">
                    <p class="text-neutral-700 whitespace-pre-wrap">{{ $budgetRequest->reason }}</p>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-neutral-500">Submitted:</span>
                    <span class="text-neutral-700 ml-1">{{ $budgetRequest->created_at->format('M d, Y \a\t h:i A') }}</span>
                </div>
                @if($budgetRequest->reviewed_at)
                <div>
                    <span class="text-neutral-500">Reviewed:</span>
                    <span class="text-neutral-700 ml-1">{{ $budgetRequest->reviewed_at->format('M d, Y \a\t h:i A') }}</span>
                </div>
                @endif
            </div>
        </div>
    </x-ui.card>

    <!-- Review Details (if reviewed) -->
    @if($budgetRequest->status !== 'pending')
    <x-ui.card class="mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                @if($budgetRequest->isApproved())
                <x-lucide-check-circle class="w-5 h-5 text-success-600" />
                Approved
                @else
                <x-lucide-x-circle class="w-5 h-5 text-error-600" />
                Rejected
                @endif
            </h2>

            @if($budgetRequest->reviewer)
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-neutral-200 rounded-full flex items-center justify-center">
                    <x-lucide-user class="w-5 h-5 text-neutral-500" />
                </div>
                <div>
                    <p class="font-medium text-neutral-800">{{ $budgetRequest->reviewer->fullName }}</p>
                    <p class="text-sm text-neutral-500">Reviewed on {{ $budgetRequest->reviewed_at->format('M d, Y') }}</p>
                </div>
            </div>
            @endif

            @if($budgetRequest->review_notes)
            <div class="p-4 {{ $budgetRequest->isApproved() ? 'bg-success-50' : 'bg-error-50' }} rounded-xl">
                <h3 class="text-sm font-medium {{ $budgetRequest->isApproved() ? 'text-success-700' : 'text-error-700' }} mb-1">Review Notes</h3>
                <p class="{{ $budgetRequest->isApproved() ? 'text-success-800' : 'text-error-800' }}">{{ $budgetRequest->review_notes }}</p>
            </div>
            @endif

            @if($budgetRequest->isApproved())
            <div class="mt-4 p-4 bg-success-100 rounded-xl">
                <p class="text-success-800 font-medium">
                    <x-lucide-check class="w-4 h-4 inline mr-1" />
                    Task budget has been updated to ₱{{ number_format($budgetRequest->requested_budget, 2) }}
                </p>
            </div>
            @endif
        </div>
    </x-ui.card>
    @endif

    <!-- Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('adiutor.budget-requests.index') }}" 
           class="inline-flex items-center gap-2 text-sm text-neutral-600 hover:text-neutral-800">
            <x-lucide-arrow-left class="w-4 h-4" />
            Back to Requests
        </a>

        @if($budgetRequest->isPending())
        <form action="{{ route('adiutor.budget-requests.cancel', $budgetRequest->id) }}" method="POST"
              onsubmit="return window.Alerts.confirmDeleteForm(event, 'Cancel Request', 'Are you sure you want to cancel this request?')">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-error-600 rounded-lg shadow-sm hover:bg-error-700 hover:shadow-md transition-all">
                <x-lucide-trash-2 class="w-4 h-4" />
                Cancel Request
            </button>
        </form>
        @endif
    </div>
</div>
@endsection

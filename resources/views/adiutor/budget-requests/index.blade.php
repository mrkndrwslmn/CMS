@extends('adiutor.layouts.app')

@section('title', 'Budget Change Requests')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Budget Requests'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Budget Change Requests</h1>
            <p class="text-sm text-neutral-500 mt-1">Request budget adjustments for your assigned tasks</p>
        </div>
        <a href="{{ route('adiutor.budget-requests.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors">
            <x-lucide-plus class="w-4 h-4" />
            New Request
        </a>
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

    @if(session('error'))
    <div class="mb-6 rounded-2xl bg-error-50 border border-error-100 p-4">
        <div class="flex items-center gap-3">
            <div class="p-1.5 bg-error-100 rounded-lg">
                <x-lucide-x-circle class="w-5 h-5 text-error-600" />
            </div>
            <p class="text-sm font-medium text-error-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="mb-6 rounded-2xl bg-primary-50 border border-primary-100 p-4">
        <div class="flex items-center gap-3">
            <div class="p-1.5 bg-primary-100 rounded-lg">
                <x-lucide-info class="w-5 h-5 text-primary-600" />
            </div>
            <p class="text-sm font-medium text-primary-800">{{ session('info') }}</p>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-warning-50 border border-warning-100 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-warning-100 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-600" />
                </div>
                <div>
                    <p class="text-2xl font-semibold text-warning-800">{{ $stats['pending'] }}</p>
                    <p class="text-sm text-warning-600">Pending</p>
                </div>
            </div>
        </div>
        <div class="bg-success-50 border border-success-100 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-success-100 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-600" />
                </div>
                <div>
                    <p class="text-2xl font-semibold text-success-800">{{ $stats['approved'] }}</p>
                    <p class="text-sm text-success-600">Approved</p>
                </div>
            </div>
        </div>
        <div class="bg-error-50 border border-error-100 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-error-100 rounded-xl">
                    <x-lucide-x-circle class="w-5 h-5 text-error-600" />
                </div>
                <div>
                    <p class="text-2xl font-semibold text-error-800">{{ $stats['rejected'] }}</p>
                    <p class="text-sm text-error-600">Rejected</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    <x-ui.card class="overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-neutral-800">Your Requests</h2>
            
            <!-- Filter -->
            <form method="GET" action="{{ route('adiutor.budget-requests.index') }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" 
                        class="text-sm border-neutral-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>

        @if($budgetRequests->isEmpty())
        <div class="p-12 text-center">
            <div class="flex justify-center mb-4">
                <div class="p-4 bg-neutral-100 rounded-full">
                    <x-lucide-wallet class="w-8 h-8 text-neutral-400" />
                </div>
            </div>
            <h3 class="text-lg font-semibold text-neutral-800 mb-1">No budget requests yet</h3>
            <p class="text-sm text-neutral-500 mb-4">When you need a budget adjustment for a task, you can submit a request here.</p>
            <a href="{{ route('adiutor.budget-requests.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Create Request
            </a>
        </div>
        @else
        <div class="divide-y divide-neutral-100">
            @foreach($budgetRequests as $request)
            <div class="px-6 py-4 hover:bg-neutral-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-neutral-800">{{ $request->task->taskTitle ?? 'Task' }}</h3>
                            @php
                                $statusClass = match($request->status) {
                                    'pending' => 'bg-warning-100 text-warning-800',
                                    'approved' => 'bg-success-100 text-success-800',
                                    'rejected' => 'bg-error-100 text-error-800',
                                    default => 'bg-neutral-100 text-neutral-800'
                                };
                            @endphp
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-neutral-500 mb-1">
                            Project: {{ $request->task->project->title ?? 'N/A' }}
                        </p>
                        <div class="flex items-center gap-4 text-sm text-neutral-500">
                            <span>Current: ₱{{ number_format($request->current_budget, 2) }}</span>
                            <x-lucide-arrow-right class="w-4 h-4" />
                            <span class="font-medium {{ $request->requested_budget > $request->current_budget ? 'text-success-600' : 'text-error-600' }}">
                                Requested: ₱{{ number_format($request->requested_budget, 2) }}
                            </span>
                            <span class="text-neutral-300">|</span>
                            <span>{{ $request->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($request->isApproved())
                        <p class="text-sm text-success-600 mt-1 flex items-center gap-1">
                            <x-lucide-check class="w-4 h-4" />
                            Budget updated to ₱{{ number_format($request->requested_budget, 2) }}
                        </p>
                        @endif
                        @if($request->isRejected() && $request->review_notes)
                        <p class="text-sm text-error-600 mt-1">
                            Reason: {{ $request->review_notes }}
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('adiutor.budget-requests.show', $request->id) }}" 
                           class="px-3 py-1.5 text-sm text-neutral-600 hover:text-neutral-800 hover:bg-neutral-100 rounded-lg transition-colors">
                            View
                        </a>
                        @if($request->isPending())
                        <form action="{{ route('adiutor.budget-requests.cancel', $request->id) }}" method="POST" class="inline"
                              onsubmit="return window.Alerts.confirmDeleteForm(event, 'Cancel Request', 'Are you sure you want to cancel this request?')">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-sm text-error-600 hover:text-error-700 hover:bg-error-50 rounded-lg transition-colors">
                                Cancel
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($budgetRequests->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100 bg-neutral-50">
            {{ $budgetRequests->links() }}
        </div>
        @endif
        @endif
    </x-ui.card>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Budget Change Request Details')
@section('page-title', 'Budget Change Request Details')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Budget Requests', 'route' => 'admin.budget-requests.index', 'icon' => 'wallet'],
        ['label' => 'Request #' . $budgetRequest->id, 'icon' => 'file-text'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Budget Change Request" 
        description="Review details and take action on this budget modification request"
        class="mb-6"
    >
        <x-slot:actions>
            <a href="{{ route('admin.budget-requests.index') }}">
                <x-ui.button variant="secondary" icon="arrow-left">
                    Back to Requests
                </x-ui.button>
            </a>
        </x-slot:actions>
    </x-ui.page-header>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Overview -->
            <x-ui.card>
                <div class="p-6 border-b border-neutral-100">
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                            <x-lucide-wallet class="w-5 h-5 text-neutral-400" />
                            Budget Change Request
                        </h3>
                        @if($budgetRequest->status === 'pending')
                            <x-ui.badge type="warning">Pending Review</x-ui.badge>
                        @elseif($budgetRequest->status === 'approved')
                            <x-ui.badge type="success">Approved</x-ui.badge>
                        @else
                            <x-ui.badge type="error">Rejected</x-ui.badge>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    <!-- Budget Comparison -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="bg-neutral-50 p-4 rounded-xl">
                            <p class="text-sm font-medium text-neutral-500 mb-1">Current Budget</p>
                            <p class="text-2xl font-semibold text-neutral-800">₱{{ number_format($budgetRequest->current_budget, 2) }}</p>
                        </div>
                        <div class="bg-primary-50 p-4 rounded-xl">
                            <p class="text-sm font-medium text-neutral-500 mb-1">Requested Budget</p>
                            <p class="text-2xl font-semibold text-primary-600">₱{{ number_format($budgetRequest->requested_budget, 2) }}</p>
                        </div>
                    </div>

                    <!-- Change Analysis -->
                    @php
                        $difference = $budgetRequest->requested_budget - $budgetRequest->current_budget;
                        $percentage = $budgetRequest->current_budget > 0 ? (($difference / $budgetRequest->current_budget) * 100) : 100;
                    @endphp
                    <div class="bg-{{ $difference > 0 ? 'error' : 'success' }}-50 p-4 rounded-xl mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-neutral-500 mb-1">Budget Change</p>
                                <p class="text-xl font-semibold text-{{ $difference > 0 ? 'error' : 'success' }}-700">
                                    {{ $difference > 0 ? '+' : '' }}₱{{ number_format(abs($difference), 2) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-neutral-500 mb-1">Percentage</p>
                                <p class="text-xl font-semibold text-{{ $difference > 0 ? 'error' : 'success' }}-700">
                                    {{ $difference > 0 ? '+' : '' }}{{ number_format($percentage, 1) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="border-t border-neutral-100 pt-6">
                        <h4 class="text-sm font-medium text-neutral-700 mb-3 flex items-center gap-2">
                            <x-lucide-message-square class="w-4 h-4 text-neutral-400" />
                            Request Reason
                        </h4>
                        <div class="bg-neutral-50 p-4 rounded-xl">
                            <p class="text-sm text-neutral-700">{{ $budgetRequest->reason }}</p>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <!-- Review Details (if reviewed) -->
            @if($budgetRequest->status !== 'pending')
                <x-ui.card>
                    <div class="p-6 border-b border-neutral-100">
                        <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                            <x-lucide-clipboard-check class="w-5 h-5 text-neutral-400" />
                            Review Details
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-500 mb-1">Reviewed By</label>
                                <p class="text-sm font-medium text-neutral-800">{{ $budgetRequest->reviewer->fullName ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-500 mb-1">Reviewed At</label>
                                <p class="text-sm text-neutral-800">{{ $budgetRequest->reviewed_at ? $budgetRequest->reviewed_at->format('F d, Y - h:i A') : 'N/A' }}</p>
                            </div>
                        </div>
                        
                        @if($budgetRequest->review_notes)
                            <div>
                                <label class="block text-sm font-medium text-neutral-500 mb-2">Review Notes</label>
                                <div class="bg-neutral-50 p-4 rounded-xl">
                                    <p class="text-sm text-neutral-700">{{ $budgetRequest->review_notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            @endif

            <!-- Action Buttons (if pending) -->
            @if($budgetRequest->status === 'pending')
                <x-ui.card>
                    <div class="p-6 border-b border-neutral-100">
                        <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                            <x-lucide-check-square class="w-5 h-5 text-neutral-400" />
                            Review Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <form method="POST" action="{{ route('admin.budget-requests.approve', $budgetRequest->id) }}">
                            @csrf
                            <div class="mb-4">
                                <x-ui.input 
                                    type="textarea"
                                    label="Approval Notes (Optional)"
                                    name="review_notes"
                                    rows="3"
                                    placeholder="Add any notes about this approval..."
                                />
                            </div>
                            <x-ui.button type="submit" variant="success" icon="check-circle" class="w-full justify-center">
                                Approve Budget Change
                            </x-ui.button>
                        </form>

                        <div class="border-t border-neutral-100 pt-6">
                            <form method="POST" action="{{ route('admin.budget-requests.reject', $budgetRequest->id) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">
                                        Rejection Reason <span class="text-error-500">*</span>
                                    </label>
                                    <textarea name="review_notes" 
                                              rows="3" 
                                              required
                                              class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                                              placeholder="Explain why this request is being rejected..."></textarea>
                                </div>
                                <x-ui.button type="submit" variant="danger" icon="x-circle" class="w-full justify-center">
                                    Reject Budget Change
                                </x-ui.button>
                            </form>
                        </div>
                    </div>
                </x-ui.card>
            @endif
        </div>

        <!-- Right Column - Task & Adiutor Info -->
        <div class="space-y-6">
            <!-- Task Information -->
            <x-ui.card>
                <div class="p-6 border-b border-neutral-100">
                    <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                        <x-lucide-clipboard-list class="w-5 h-5 text-neutral-400" />
                        Task Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-500 mb-1">Task Title</label>
                        <p class="text-sm font-medium text-neutral-800">{{ $budgetRequest->task->taskTitle ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-500 mb-1">Project</label>
                        <p class="text-sm text-neutral-800">{{ $budgetRequest->task->project->title ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-500 mb-1">Task Status</label>
                        <x-ui.badge type="info">
                            {{ ucfirst($budgetRequest->task->status ?? 'unknown') }}
                        </x-ui.badge>
                    </div>
                    
                    @if($budgetRequest->task)
                        <div class="pt-3 border-t border-neutral-100">
                            <a href="{{ route('admin.tasks.show', $budgetRequest->task->taskID) }}" 
                               class="inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                View Full Task Details 
                                <x-lucide-arrow-right class="w-4 h-4" />
                            </a>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Adiutor Information -->
            <x-ui.card>
                <div class="p-6 border-b border-neutral-100">
                    <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                        <x-lucide-user class="w-5 h-5 text-neutral-400" />
                        Adiutor Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <img src="{{ $budgetRequest->adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($budgetRequest->adiutor->fullName) }}" 
                             alt="{{ $budgetRequest->adiutor->fullName }}"
                             class="w-16 h-16 rounded-full mx-auto mb-3 object-cover">
                        <p class="font-medium text-neutral-800">{{ $budgetRequest->adiutor->fullName }}</p>
                        <p class="text-sm text-neutral-500">{{ $budgetRequest->adiutor->email }}</p>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        @if($budgetRequest->adiutor->phoneNumber)
                            <div class="flex items-center gap-3 text-neutral-700">
                                <x-lucide-phone class="w-4 h-4 text-neutral-400" />
                                <span>{{ $budgetRequest->adiutor->phoneNumber }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center gap-3 text-neutral-700">
                            <x-lucide-shield class="w-4 h-4 text-neutral-400" />
                            <span class="capitalize">{{ $budgetRequest->adiutor->role }}</span>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <!-- Timeline -->
            <x-ui.card>
                <div class="p-6 border-b border-neutral-100">
                    <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                        <x-lucide-clock class="w-5 h-5 text-neutral-400" />
                        Timeline
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-primary-50 rounded-full">
                            <x-lucide-plus class="w-3 h-3 text-primary-600" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-800">Request Created</p>
                            <p class="text-xs text-neutral-500">{{ $budgetRequest->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($budgetRequest->reviewed_at)
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-{{ $budgetRequest->status === 'approved' ? 'success' : 'error' }}-50 rounded-full">
                                <x-lucide-{{ $budgetRequest->status === 'approved' ? 'check' : 'x' }} class="w-3 h-3 text-{{ $budgetRequest->status === 'approved' ? 'success' : 'error' }}-600" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-800">{{ ucfirst($budgetRequest->status) }}</p>
                                <p class="text-xs text-neutral-500">{{ $budgetRequest->reviewed_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection

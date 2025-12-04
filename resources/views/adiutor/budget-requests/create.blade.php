@extends('adiutor.layouts.app')

@section('title', 'New Budget Change Request')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Budget Requests', 'url' => route('adiutor.budget-requests.index')],
        ['label' => 'New Request'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">New Budget Change Request</h1>
        <p class="text-sm text-neutral-500 mt-1">Request a budget adjustment for one of your assigned tasks</p>
    </div>

    @if($errors->any())
    <div class="mb-6 rounded-2xl bg-error-50 border border-error-100 p-4">
        <div class="flex items-start gap-3">
            <div class="p-1.5 bg-error-100 rounded-lg">
                <x-lucide-alert-circle class="w-5 h-5 text-error-600" />
            </div>
            <div>
                <p class="text-sm font-medium text-error-800 mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-sm text-error-700">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <x-ui.card>
        <form action="{{ route('adiutor.budget-requests.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Task Selection -->
            <div>
                <label for="task_id" class="block text-sm font-medium text-neutral-700 mb-2">
                    Select Task <span class="text-error-500">*</span>
                </label>
                @if($selectedTask)
                    <input type="hidden" name="task_id" value="{{ $selectedTask->taskID }}">
                    <div class="p-4 bg-neutral-50 border border-neutral-200 rounded-xl">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-neutral-800">{{ $selectedTask->taskTitle }}</h3>
                                <p class="text-sm text-neutral-500">Project: {{ $selectedTask->project->title ?? 'N/A' }}</p>
                                <p class="text-sm text-neutral-600 mt-1">
                                    Current Budget: <span class="font-semibold">₱{{ number_format($selectedTask->allocated_budget ?? 0, 2) }}</span>
                                </p>
                            </div>
                            <a href="{{ route('adiutor.budget-requests.create') }}" class="text-sm text-primary-600 hover:text-primary-700">
                                Change
                            </a>
                        </div>
                    </div>
                @else
                    <select name="task_id" id="task_id" required
                            class="w-full border-neutral-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 @error('task_id') border-error-500 @enderror">
                        <option value="">Select a task...</option>
                        @foreach($availableTasks as $task)
                        <option value="{{ $task->taskID }}" 
                                data-budget="{{ $task->allocated_budget ?? 0 }}"
                                {{ old('task_id') == $task->taskID ? 'selected' : '' }}>
                            {{ $task->taskTitle }} ({{ $task->project->title ?? 'No Project' }}) - Current: ₱{{ number_format($task->allocated_budget ?? 0, 2) }}
                        </option>
                        @endforeach
                    </select>
                    @if($availableTasks->isEmpty())
                    <p class="mt-2 text-sm text-warning-600">
                        <x-lucide-alert-triangle class="w-4 h-4 inline" />
                        You don't have any active tasks assigned to you.
                    </p>
                    @endif
                @endif
            </div>

            <!-- Current Budget Display (for non-preselected) -->
            @if(!$selectedTask)
            <div id="current-budget-display" class="hidden">
                <label class="block text-sm font-medium text-neutral-700 mb-2">Current Budget</label>
                <div class="p-3 bg-neutral-100 border border-neutral-200 rounded-xl">
                    <span id="current-budget-value" class="text-lg font-semibold text-neutral-800">₱0.00</span>
                </div>
            </div>
            @endif

            <!-- Requested Budget -->
            <div>
                <label for="requested_budget" class="block text-sm font-medium text-neutral-700 mb-2">
                    Requested Budget <span class="text-error-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-500">₱</span>
                    <input type="number" 
                           name="requested_budget" 
                           id="requested_budget" 
                           step="0.01" 
                           min="0" 
                           value="{{ old('requested_budget') }}"
                           required
                           class="w-full pl-8 border-neutral-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 @error('requested_budget') border-error-500 @enderror"
                           placeholder="0.00">
                </div>
                <p class="mt-1 text-xs text-neutral-500">Enter the new budget amount you're requesting</p>
            </div>

            <!-- Budget Difference Preview -->
            <div id="budget-difference" class="hidden p-4 rounded-xl border">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-neutral-600">Budget Difference:</span>
                    <span id="difference-value" class="text-lg font-semibold"></span>
                </div>
            </div>

            <!-- Reason -->
            <div>
                <label for="reason" class="block text-sm font-medium text-neutral-700 mb-2">
                    Reason for Request <span class="text-error-500">*</span>
                </label>
                <textarea name="reason" 
                          id="reason" 
                          rows="4" 
                          required
                          minlength="20"
                          maxlength="1000"
                          class="w-full border-neutral-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 @error('reason') border-error-500 @enderror"
                          placeholder="Please explain why you need this budget change (minimum 20 characters)...">{{ old('reason') }}</textarea>
                <p class="mt-1 text-xs text-neutral-500">
                    <span id="reason-count">0</span>/1000 characters (minimum 20)
                </p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
                <a href="{{ route('adiutor.budget-requests.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-800 hover:bg-neutral-100 rounded-xl transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors">
                    <x-lucide-send class="w-4 h-4" />
                    Submit Request
                </button>
            </div>
        </form>
    </x-ui.card>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const taskSelect = document.getElementById('task_id');
    const requestedBudgetInput = document.getElementById('requested_budget');
    const budgetDifferenceDiv = document.getElementById('budget-difference');
    const differenceValue = document.getElementById('difference-value');
    const reasonTextarea = document.getElementById('reason');
    const reasonCount = document.getElementById('reason-count');
    
    let currentBudget = {{ $selectedTask ? ($selectedTask->allocated_budget ?? 0) : 0 }};

    // Task selection change
    if (taskSelect) {
        const currentBudgetDisplay = document.getElementById('current-budget-display');
        const currentBudgetValue = document.getElementById('current-budget-value');
        
        taskSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                currentBudget = parseFloat(selectedOption.dataset.budget) || 0;
                currentBudgetValue.textContent = '₱' + currentBudget.toLocaleString('en-PH', { minimumFractionDigits: 2 });
                currentBudgetDisplay.classList.remove('hidden');
            } else {
                currentBudgetDisplay.classList.add('hidden');
            }
            updateDifference();
        });
    }

    // Requested budget change
    requestedBudgetInput.addEventListener('input', updateDifference);

    function updateDifference() {
        const requested = parseFloat(requestedBudgetInput.value) || 0;
        const difference = requested - currentBudget;
        
        if (requestedBudgetInput.value && difference !== 0) {
            budgetDifferenceDiv.classList.remove('hidden');
            
            if (difference > 0) {
                budgetDifferenceDiv.classList.remove('bg-error-50', 'border-error-100');
                budgetDifferenceDiv.classList.add('bg-success-50', 'border-success-100');
                differenceValue.textContent = '+₱' + difference.toLocaleString('en-PH', { minimumFractionDigits: 2 });
                differenceValue.classList.remove('text-error-600');
                differenceValue.classList.add('text-success-600');
            } else {
                budgetDifferenceDiv.classList.remove('bg-success-50', 'border-success-100');
                budgetDifferenceDiv.classList.add('bg-error-50', 'border-error-100');
                differenceValue.textContent = '-₱' + Math.abs(difference).toLocaleString('en-PH', { minimumFractionDigits: 2 });
                differenceValue.classList.remove('text-success-600');
                differenceValue.classList.add('text-error-600');
            }
        } else {
            budgetDifferenceDiv.classList.add('hidden');
        }
    }

    // Character count for reason
    reasonTextarea.addEventListener('input', function() {
        reasonCount.textContent = this.value.length;
    });
    reasonCount.textContent = reasonTextarea.value.length;
});
</script>
@endpush
@endsection

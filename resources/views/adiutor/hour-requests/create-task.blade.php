@extends('adiutor.layouts.app')

@section('title', 'Request Task Hour Increase')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Hour Requests', 'url' => route('adiutor.hour-requests.index')],
        ['label' => 'New Task Request'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Request Task Hour Increase</h1>
        <p class="text-sm text-neutral-500 mt-1">Request additional hours for a specific task</p>
    </div>

    @if($errors->any())
    <div class="mb-6 rounded-2xl bg-error-50 border border-error-100 p-4">
        <div class="flex">
            <div class="p-1.5 bg-error-100 rounded-lg">
                <x-lucide-x-circle class="w-5 h-5 text-error-600" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-error-800">There were errors with your request</h3>
                <ul class="mt-2 text-sm text-error-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Task Info Card -->
    <x-ui.card class="p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-primary-50 rounded-lg">
                <x-lucide-list-todo class="w-5 h-5 text-primary-600" />
            </div>
            <h2 class="text-lg font-semibold text-neutral-800">Task Details</h2>
        </div>
        
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-neutral-500">Task</p>
                <p class="font-semibold text-neutral-800">{{ $task->taskTitle }}</p>
            </div>
            <div>
                <p class="text-sm text-neutral-500">Project</p>
                <p class="font-semibold text-neutral-800">{{ $task->project->title ?? 'Unknown Project' }}</p>
            </div>
            <div>
                <p class="text-sm text-neutral-500">Current Max Hours</p>
                <p class="font-semibold text-neutral-800">{{ $task->max_hours ?? 'No limit' }} hours</p>
            </div>
            <div>
                <p class="text-sm text-neutral-500">Hours Already Tracked</p>
                <p class="font-semibold text-neutral-800">{{ number_format($task->total_billable_hours ?? 0, 2) }} hours</p>
            </div>
        </div>

        @php $maxHoursStatus = $task->getMaxHoursStatus(); @endphp
        @if($maxHoursStatus['has_limit'])
        <div class="mt-4">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-neutral-500">Hours Utilization</span>
                <span class="font-medium text-neutral-700">
                    {{ number_format($maxHoursStatus['percentage'], 1) }}%
                </span>
            </div>
            <div class="h-3 bg-neutral-200 rounded-full overflow-hidden">
                @php
                    $barColor = $maxHoursStatus['status'] === 'reached' ? 'bg-error-500' : 
                               ($maxHoursStatus['status'] === 'approaching' ? 'bg-warning-500' : 'bg-success-500');
                @endphp
                <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ $maxHoursStatus['percentage'] }}%"></div>
            </div>
            <p class="text-sm text-neutral-500 mt-1">
                {{ number_format($maxHoursStatus['remaining_hours'], 2) }} hours remaining
            </p>
        </div>

        @if($maxHoursStatus['status'] === 'reached')
        <div class="mt-4 p-3 bg-error-50 border border-error-100 rounded-xl">
            <div class="flex items-center gap-2">
                <x-lucide-alert-circle class="w-5 h-5 text-error-600" />
                <p class="text-sm font-medium text-error-700">Maximum hours reached - new time tracked will be non-billable</p>
            </div>
        </div>
        @endif
        @endif
    </x-ui.card>

    <!-- Request Form -->
    <x-ui.card class="p-6">
        <h2 class="text-lg font-semibold text-neutral-800 mb-4">Request Details</h2>
        
        <form action="{{ route('adiutor.hour-requests.store') }}" method="POST">
            @csrf
            <input type="hidden" name="task_id" value="{{ $task->taskID }}">

            <div class="space-y-6">
                <!-- Requested Hours -->
                <div>
                    <label for="requested_hours" class="block text-sm font-medium text-neutral-700 mb-1">
                        New Max Hours Requested <span class="text-error-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               name="requested_hours" 
                               id="requested_hours"
                               step="0.5"
                               min="{{ ($task->max_hours ?? 0) + 0.5 }}"
                               value="{{ old('requested_hours', ($task->max_hours ?? 0) + 10) }}"
                               class="block w-full rounded-xl border-neutral-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 pr-16"
                               required>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-neutral-500">hours</span>
                    </div>
                    <p class="mt-1 text-sm text-neutral-500">
                        Must be greater than current max of {{ $task->max_hours ?? 0 }} hours
                    </p>
                    @if($task->max_hours)
                    <p class="mt-1 text-sm text-primary-600" id="increaseAmount">
                        That's an increase of <span class="font-semibold">{{ old('requested_hours', ($task->max_hours ?? 0) + 10) - $task->max_hours }}</span> hours
                    </p>
                    @endif
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-neutral-700 mb-1">
                        Reason for Request <span class="text-error-500">*</span>
                    </label>
                    <textarea name="reason" 
                              id="reason"
                              rows="5"
                              class="block w-full rounded-xl border-neutral-300 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Please explain why you need additional hours for this task. Include details about scope changes, unexpected complexity, or other factors..."
                              required
                              minlength="20"
                              maxlength="1000">{{ old('reason') }}</textarea>
                    <p class="mt-1 text-sm text-neutral-500">
                        Minimum 20 characters. Be specific about why you need more hours.
                    </p>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
                    <a href="{{ route('adiutor.hour-requests.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-xl hover:bg-neutral-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 text-sm font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors">
                        Submit Request
                    </button>
                </div>
            </div>
        </form>
    </x-ui.card>
</div>

@push('scripts')
<script>
document.getElementById('requested_hours')?.addEventListener('input', function() {
    const currentMax = {{ $task->max_hours ?? 0 }};
    const requested = parseFloat(this.value) || 0;
    const increase = requested - currentMax;
    const increaseSpan = document.querySelector('#increaseAmount span');
    if (increaseSpan) {
        increaseSpan.textContent = increase.toFixed(1);
    }
});
</script>
@endpush
@endsection

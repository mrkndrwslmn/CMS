@extends('adiutor.layouts.app')

@section('title', 'Request Hour Increase')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('adiutor.hour-requests.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Requests
        </a>
        <h1 class="text-2xl font-semibold text-gray-900">Request Hour Increase</h1>
        <p class="text-sm text-gray-500 mt-1">Request additional hours for your project assignment</p>
    </div>

    @if($errors->any())
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">There were errors with your request</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Assignment Info Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Assignment Details</h2>
        
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Project</p>
                <p class="font-semibold text-gray-900">{{ $assignment->project->title ?? 'Unknown Project' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Payment Type</p>
                <p class="font-semibold text-gray-900 capitalize">{{ $assignment->payment_type ?? 'Hourly' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Current Max Hours</p>
                <p class="font-semibold text-gray-900">{{ $assignment->max_hours ?? 'No limit' }} hours</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Hours Already Logged</p>
                <p class="font-semibold text-gray-900">{{ number_format($assignment->total_hours_logged ?? 0, 2) }} hours</p>
            </div>
        </div>

        @if($assignment->max_hours)
        <div class="mt-4">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-500">Hours Utilization</span>
                <span class="font-medium">
                    {{ number_format(($assignment->total_hours_logged / $assignment->max_hours) * 100, 1) }}%
                </span>
            </div>
            <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                @php
                    $percentage = min(100, (($assignment->total_hours_logged ?? 0) / $assignment->max_hours) * 100);
                    $barColor = $percentage >= 100 ? 'bg-red-500' : ($percentage >= 80 ? 'bg-yellow-500' : 'bg-green-500');
                @endphp
                <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ $percentage }}%"></div>
            </div>
            <p class="text-sm text-gray-500 mt-1">
                {{ number_format(max(0, $assignment->max_hours - ($assignment->total_hours_logged ?? 0)), 2) }} hours remaining
            </p>
        </div>
        @endif
    </div>

    <!-- Request Form -->
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Request Details</h2>
        
        <form action="{{ route('adiutor.hour-requests.store') }}" method="POST">
            @csrf
            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

            <div class="space-y-6">
                <!-- Requested Hours -->
                <div>
                    <label for="requested_hours" class="block text-sm font-medium text-gray-700 mb-1">
                        New Max Hours Requested <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               name="requested_hours" 
                               id="requested_hours"
                               step="0.5"
                               min="{{ ($assignment->max_hours ?? 0) + 1 }}"
                               value="{{ old('requested_hours', ($assignment->max_hours ?? 0) + 10) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 pr-16"
                               required>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">hours</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        Must be greater than current max of {{ $assignment->max_hours ?? 0 }} hours
                    </p>
                    @if($assignment->max_hours)
                    <p class="mt-1 text-sm text-blue-600" id="increaseAmount">
                        That's an increase of <span class="font-semibold">{{ old('requested_hours', ($assignment->max_hours ?? 0) + 10) - $assignment->max_hours }}</span> hours
                    </p>
                    @endif
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">
                        Reason for Request <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" 
                              id="reason"
                              rows="5"
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Please explain why you need additional hours. Include details about scope changes, unexpected complexity, or other factors..."
                              required
                              minlength="20"
                              maxlength="1000">{{ old('reason') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        Minimum 20 characters. Be specific about why you need more hours.
                    </p>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('adiutor.hour-requests.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                        Submit Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('requested_hours')?.addEventListener('input', function() {
    const currentMax = {{ $assignment->max_hours ?? 0 }};
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

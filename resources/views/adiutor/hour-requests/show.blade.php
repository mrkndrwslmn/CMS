@extends('adiutor.layouts.app')

@section('title', 'Hour Request Details')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Hour Requests', 'url' => route('adiutor.hour-requests.index')],
        ['label' => 'Request #' . $hourRequest->id],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Hour Increase Request</h1>
                <p class="text-sm text-neutral-500 mt-1">Request #{{ $hourRequest->id }}</p>
            </div>
            <span class="px-3 py-1.5 text-sm font-medium rounded-full {{ $hourRequest->status_badge_class }}">
                {{ $hourRequest->status_label }}
            </span>
        </div>
    </div>

    <!-- Request Details -->
    <x-ui.card class="overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-neutral-100">
            <h2 class="text-lg font-semibold text-neutral-800">Request Details</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-sm text-neutral-500">Project</p>
                    <p class="font-semibold text-neutral-800">{{ $hourRequest->project->title ?? 'Unknown Project' }}</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Submitted</p>
                    <p class="font-semibold text-neutral-800">{{ $hourRequest->created_at->format('M d, Y g:i A') }}</p>
                </div>
            </div>

            <!-- Hours Comparison -->
            <div class="bg-neutral-50 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="text-center flex-1">
                        <p class="text-sm text-neutral-500 mb-1">Current Max</p>
                        <p class="text-2xl font-bold text-neutral-600">{{ $hourRequest->current_max_hours }}</p>
                        <p class="text-xs text-neutral-400">hours</p>
                    </div>
                    <div class="px-4">
                        <x-lucide-arrow-right class="w-8 h-8 text-neutral-400" />
                    </div>
                    <div class="text-center flex-1">
                        <p class="text-sm text-neutral-500 mb-1">Requested</p>
                        <p class="text-2xl font-bold text-primary-600">{{ $hourRequest->requested_max_hours }}</p>
                        <p class="text-xs text-neutral-400">hours</p>
                    </div>
                    @if($hourRequest->isApproved() && $hourRequest->approved_hours)
                    <div class="px-4">
                        <x-lucide-check class="w-8 h-8 text-success-500" />
                    </div>
                    <div class="text-center flex-1">
                        <p class="text-sm text-neutral-500 mb-1">Approved</p>
                        <p class="text-2xl font-bold text-success-600">{{ $hourRequest->approved_hours }}</p>
                        <p class="text-xs text-neutral-400">hours</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mb-6">
                <p class="text-sm text-neutral-500 mb-1">Hours Tracked at Time of Request</p>
                <p class="font-semibold text-neutral-800">{{ number_format($hourRequest->hours_already_tracked, 2) }} hours ({{ $hourRequest->utilization_percentage }}% of max)</p>
            </div>

            <div>
                <p class="text-sm text-neutral-500 mb-1">Reason</p>
                <div class="bg-neutral-50 rounded-xl p-4">
                    <p class="text-neutral-700 whitespace-pre-wrap">{{ $hourRequest->reason }}</p>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- Review Details (if reviewed) -->
    @if(!$hourRequest->isPending())
    <x-ui.card class="overflow-hidden">
        <div class="px-6 py-4 border-b {{ $hourRequest->isApproved() ? 'border-success-100 bg-success-50' : 'border-error-100 bg-error-50' }}">
            <div class="flex items-center gap-2">
                @if($hourRequest->isApproved())
                <x-lucide-check-circle-2 class="w-5 h-5 text-success-600" />
                <h2 class="text-lg font-semibold text-success-800">Request Approved</h2>
                @else
                <x-lucide-x-circle class="w-5 h-5 text-error-600" />
                <h2 class="text-lg font-semibold text-error-800">Request Rejected</h2>
                @endif
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <p class="text-sm text-neutral-500">Reviewed By</p>
                    <p class="font-semibold text-neutral-800">{{ $hourRequest->reviewer->fullName ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Reviewed At</p>
                    <p class="font-semibold text-neutral-800">{{ $hourRequest->reviewed_at?->format('M d, Y g:i A') }}</p>
                </div>
            </div>

            @if($hourRequest->review_notes)
            <div>
                <p class="text-sm text-neutral-500 mb-1">Admin Notes</p>
                <div class="bg-neutral-50 rounded-xl p-4">
                    <p class="text-neutral-700">{{ $hourRequest->review_notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </x-ui.card>
    @else
    <!-- Pending Actions -->
    <div class="bg-warning-50 border border-warning-100 rounded-2xl p-6 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-warning-100 flex items-center justify-center mb-4">
            <x-lucide-clock class="w-6 h-6 text-warning-600" />
        </div>
        <h3 class="text-lg font-medium text-warning-800 mb-2">Pending Review</h3>
        <p class="text-sm text-warning-600 mb-4">Your request is waiting for admin review. You'll be notified when it's processed.</p>
        <form action="{{ route('adiutor.hour-requests.cancel', $hourRequest->id) }}" method="POST" class="inline"
              onsubmit="return confirm('Are you sure you want to cancel this request?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 text-sm font-medium text-error-600 bg-white border border-error-200 rounded-xl hover:bg-error-50 transition-colors">
                Cancel Request
            </button>
        </form>
    </div>
    @endif
</div>
@endsection

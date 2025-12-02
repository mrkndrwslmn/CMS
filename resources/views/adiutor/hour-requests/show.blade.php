@extends('adiutor.layouts.app')

@section('title', 'Hour Request Details')

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
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Hour Increase Request</h1>
                <p class="text-sm text-gray-500 mt-1">Request #{{ $hourRequest->id }}</p>
            </div>
            <span class="px-3 py-1.5 text-sm font-medium rounded-full {{ $hourRequest->status_badge_class }}">
                {{ $hourRequest->status_label }}
            </span>
        </div>
    </div>

    <!-- Request Details -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Request Details</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Project</p>
                    <p class="font-semibold text-gray-900">{{ $hourRequest->project->title ?? 'Unknown Project' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Submitted</p>
                    <p class="font-semibold text-gray-900">{{ $hourRequest->created_at->format('M d, Y g:i A') }}</p>
                </div>
            </div>

            <!-- Hours Comparison -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="text-center flex-1">
                        <p class="text-sm text-gray-500 mb-1">Current Max</p>
                        <p class="text-2xl font-bold text-gray-600">{{ $hourRequest->current_max_hours }}</p>
                        <p class="text-xs text-gray-400">hours</p>
                    </div>
                    <div class="px-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                    <div class="text-center flex-1">
                        <p class="text-sm text-gray-500 mb-1">Requested</p>
                        <p class="text-2xl font-bold text-primary-600">{{ $hourRequest->requested_max_hours }}</p>
                        <p class="text-xs text-gray-400">hours</p>
                    </div>
                    @if($hourRequest->isApproved() && $hourRequest->approved_hours)
                    <div class="px-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="text-center flex-1">
                        <p class="text-sm text-gray-500 mb-1">Approved</p>
                        <p class="text-2xl font-bold text-green-600">{{ $hourRequest->approved_hours }}</p>
                        <p class="text-xs text-gray-400">hours</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-1">Hours Tracked at Time of Request</p>
                <p class="font-semibold text-gray-900">{{ number_format($hourRequest->hours_already_tracked, 2) }} hours ({{ $hourRequest->utilization_percentage }}% of max)</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-1">Reason</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $hourRequest->reason }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Details (if reviewed) -->
    @if(!$hourRequest->isPending())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 {{ $hourRequest->isApproved() ? 'bg-green-50' : 'bg-red-50' }}">
            <h2 class="text-lg font-semibold {{ $hourRequest->isApproved() ? 'text-green-900' : 'text-red-900' }}">
                {{ $hourRequest->isApproved() ? '✅ Request Approved' : '❌ Request Rejected' }}
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <p class="text-sm text-gray-500">Reviewed By</p>
                    <p class="font-semibold text-gray-900">{{ $hourRequest->reviewer->fullName ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Reviewed At</p>
                    <p class="font-semibold text-gray-900">{{ $hourRequest->reviewed_at?->format('M d, Y g:i A') }}</p>
                </div>
            </div>

            @if($hourRequest->review_notes)
            <div>
                <p class="text-sm text-gray-500 mb-1">Admin Notes</p>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700">{{ $hourRequest->review_notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <!-- Pending Actions -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-yellow-100 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-yellow-800 mb-2">Pending Review</h3>
        <p class="text-sm text-yellow-600 mb-4">Your request is waiting for admin review. You'll be notified when it's processed.</p>
        <form action="{{ route('adiutor.hour-requests.cancel', $hourRequest->id) }}" method="POST" class="inline"
              onsubmit="return confirm('Are you sure you want to cancel this request?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                Cancel Request
            </button>
        </form>
    </div>
    @endif
</div>
@endsection

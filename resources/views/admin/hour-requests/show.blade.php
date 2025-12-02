@extends('admin.layouts.app')

@section('title', 'Review Hour Increase Request')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.hour-requests.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Requests
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Hour Increase Request #{{ $hourRequest->id }}</h1>
                <p class="text-sm text-gray-500 mt-1">Review and approve or reject this request</p>
            </div>
            <span class="px-3 py-1.5 text-sm font-medium rounded-full {{ $hourRequest->status_badge_class }}">
                {{ $hourRequest->status_label }}
            </span>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
        <ul class="text-sm text-red-700 list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Adiutor Info -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Adiutor</h2>
                <div class="flex items-center gap-4">
                    <img class="h-16 w-16 rounded-full object-cover" 
                         src="{{ $hourRequest->adiutor->getProfilePictureUrl() }}" 
                         alt="{{ $hourRequest->adiutor->fullName }}">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $hourRequest->adiutor->fullName }}</p>
                        <p class="text-sm text-gray-500">{{ $hourRequest->adiutor->email }}</p>
                        @if($hourRequest->adiutor->adiutorProfile)
                        <p class="text-sm text-gray-500">
                            Rate: ₱{{ number_format($hourRequest->adiutor->adiutorProfile->standard_hourly_rate ?? 0, 2) }}/hr
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Request Details</h2>
                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Project</p>
                        <p class="font-semibold text-gray-900">{{ $hourRequest->project->title ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Submitted</p>
                        <p class="font-semibold text-gray-900">{{ $hourRequest->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>

                <!-- Hours Visual Comparison -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="text-center flex-1">
                            <p class="text-sm text-gray-500 mb-2">Current Max</p>
                            <p class="text-3xl font-bold text-gray-600">{{ $hourRequest->current_max_hours }}</p>
                            <p class="text-sm text-gray-400">hours</p>
                        </div>
                        <div class="px-6">
                            <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <p class="text-center text-sm font-semibold text-primary-600 mt-1">
                                +{{ $hourRequest->hours_increase }} hrs
                            </p>
                        </div>
                        <div class="text-center flex-1">
                            <p class="text-sm text-gray-500 mb-2">Requested</p>
                            <p class="text-3xl font-bold text-primary-600">{{ $hourRequest->requested_max_hours }}</p>
                            <p class="text-sm text-gray-400">hours</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500">Hours Already Tracked</span>
                            <span class="font-semibold">{{ number_format($hourRequest->hours_already_tracked, 2) }} hrs</span>
                        </div>
                        <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                            @php
                                $percentage = $hourRequest->utilization_percentage;
                                $barColor = $percentage >= 100 ? 'bg-red-500' : ($percentage >= 80 ? 'bg-yellow-500' : 'bg-green-500');
                            @endphp
                            <div class="{{ $barColor }} h-full rounded-full" style="width: {{ min(100, $percentage) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $hourRequest->utilization_percentage }}% of current max utilized</p>
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Reason for Request</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $hourRequest->reason }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Time Entries -->
            @if($recentTimeEntries->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Time Entries</h2>
                <div class="space-y-3">
                    @foreach($recentTimeEntries as $entry)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $entry->task->taskTitle ?? 'Unknown Task' }}</p>
                            <p class="text-xs text-gray-500">{{ $entry->start_time?->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">{{ $entry->getFormattedDuration() }}</p>
                            <span class="text-xs {{ $entry->is_approved ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $entry->is_approved ? 'Approved' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar - Actions -->
        <div class="lg:col-span-1">
            @if($hourRequest->isPending())
            <!-- Approve Form -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Approve Request</h3>
                <form action="{{ route('admin.hour-requests.approve', $hourRequest->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="approved_hours" class="block text-sm font-medium text-gray-700 mb-1">
                                Approved Hours
                            </label>
                            <input type="number" 
                                   name="approved_hours" 
                                   id="approved_hours"
                                   step="0.5"
                                   min="{{ $hourRequest->current_max_hours }}"
                                   value="{{ $hourRequest->requested_max_hours }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">You can approve a different number than requested</p>
                        </div>
                        <div>
                            <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Notes (Optional)
                            </label>
                            <textarea name="review_notes" 
                                      id="approve_notes"
                                      rows="3"
                                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                      placeholder="Add any notes for the adiutor..."></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                            ✓ Approve Request
                        </button>
                    </div>
                </form>
            </div>

            <!-- Reject Form -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Reject Request</h3>
                <form action="{{ route('admin.hour-requests.reject', $hourRequest->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Reason for Rejection <span class="text-red-500">*</span>
                            </label>
                            <textarea name="review_notes" 
                                      id="reject_notes"
                                      rows="4"
                                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                      placeholder="Explain why this request is being rejected..."
                                      required
                                      minlength="10"></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                                onclick="return confirm('Are you sure you want to reject this request?');">
                            ✕ Reject Request
                        </button>
                    </div>
                </form>
            </div>
            @else
            <!-- Already Reviewed -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Details</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-2.5 py-1 text-sm font-medium rounded-full {{ $hourRequest->status_badge_class }}">
                            {{ $hourRequest->status_label }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Reviewed By</p>
                        <p class="font-medium text-gray-900">{{ $hourRequest->reviewer->fullName ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Reviewed At</p>
                        <p class="font-medium text-gray-900">{{ $hourRequest->reviewed_at?->format('M d, Y g:i A') }}</p>
                    </div>
                    @if($hourRequest->isApproved() && $hourRequest->approved_hours)
                    <div>
                        <p class="text-sm text-gray-500">Approved Hours</p>
                        <p class="font-medium text-green-600">{{ $hourRequest->approved_hours }} hours</p>
                    </div>
                    @endif
                    @if($hourRequest->review_notes)
                    <div>
                        <p class="text-sm text-gray-500">Notes</p>
                        <p class="text-gray-700">{{ $hourRequest->review_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Review Hour Increase Request')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Hour Requests', 'route' => 'admin.hour-requests.index', 'icon' => 'clock'],
        ['label' => 'Request #' . $hourRequest->id, 'icon' => 'file-text'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <x-ui.page-header 
            :title="'Hour Increase Request #' . $hourRequest->id"
            description="Review and approve or reject this request"
        />
        @php
            $statusVariants = [
                'pending' => 'warning',
                'approved' => 'success',
                'rejected' => 'error',
            ];
            $variant = $statusVariants[$hourRequest->status] ?? 'neutral';
        @endphp
        <x-ui.badge :variant="$variant" size="lg">
            {{ $hourRequest->status_label }}
        </x-ui.badge>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-error-50 border-l-4 border-error-500 text-error-700 p-4 rounded-lg">
        <ul class="text-sm list-disc list-inside">
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
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center gap-2">
                        <x-lucide-user class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Adiutor</h2>
                    </div>
                </x-slot:header>
                
                <div class="flex items-center gap-4">
                    <img class="h-14 w-14 rounded-full object-cover" 
                         src="{{ $hourRequest->adiutor->getProfilePictureUrl() }}" 
                         alt="{{ $hourRequest->adiutor->fullName }}">
                    <div>
                        <p class="font-medium text-neutral-700">{{ $hourRequest->adiutor->fullName }}</p>
                        <p class="text-sm text-neutral-400">{{ $hourRequest->adiutor->email }}</p>
                        @if($hourRequest->adiutor->adiutorProfile)
                        <p class="text-sm text-neutral-500 mt-1">
                            Rate: P{{ number_format($hourRequest->adiutor->adiutorProfile->standard_hourly_rate ?? 0, 2) }}/hr
                        </p>
                        @endif
                    </div>
                </div>
            </x-ui.card>

            <!-- Request Details -->
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center gap-2">
                        <x-lucide-file-text class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Request Details</h2>
                    </div>
                </x-slot:header>
                
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-neutral-500">Project</p>
                        <p class="font-medium text-neutral-700">{{ $hourRequest->project->title ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500">Submitted</p>
                        <p class="font-medium text-neutral-700">{{ $hourRequest->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>

                <!-- Hours Visual Comparison -->
                <div class="bg-neutral-50 rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="text-center flex-1">
                            <p class="text-sm text-neutral-500 mb-2">Current Max</p>
                            <p class="text-3xl font-semibold text-neutral-600">{{ $hourRequest->current_max_hours }}</p>
                            <p class="text-sm text-neutral-400">hours</p>
                        </div>
                        <div class="px-6">
                            <div class="w-12 h-12 rounded-full bg-primary-50 flex items-center justify-center">
                                <x-lucide-plus class="w-6 h-6 text-primary-600" />
                            </div>
                            <p class="text-center text-sm font-semibold text-primary-600 mt-1">
                                +{{ $hourRequest->hours_increase }} hrs
                            </p>
                        </div>
                        <div class="text-center flex-1">
                            <p class="text-sm text-neutral-500 mb-2">Requested</p>
                            <p class="text-3xl font-semibold text-primary-600">{{ $hourRequest->requested_max_hours }}</p>
                            <p class="text-sm text-neutral-400">hours</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-neutral-200">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-neutral-500">Hours Already Tracked</span>
                            <span class="font-medium text-neutral-700">{{ number_format($hourRequest->hours_already_tracked, 2) }} hrs</span>
                        </div>
                        <div class="h-3 bg-neutral-200 rounded-full overflow-hidden">
                            @php
                                $percentage = $hourRequest->utilization_percentage;
                                $barColor = $percentage >= 100 ? 'bg-error-500' : ($percentage >= 80 ? 'bg-warning-500' : 'bg-success-500');
                            @endphp
                            <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ min(100, $percentage) }}%"></div>
                        </div>
                        <p class="text-xs text-neutral-400 mt-1">{{ $hourRequest->utilization_percentage }}% of current max utilized</p>
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <p class="text-sm font-medium text-neutral-700 mb-2">Reason for Request</p>
                    <div class="bg-neutral-50 rounded-xl p-4 border border-neutral-100">
                        <p class="text-neutral-700 whitespace-pre-wrap">{{ $hourRequest->reason }}</p>
                    </div>
                </div>
            </x-ui.card>

            <!-- Recent Time Entries -->
            @if($recentTimeEntries->isNotEmpty())
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center gap-2">
                        <x-lucide-timer class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Recent Time Entries</h2>
                    </div>
                </x-slot:header>
                
                <div class="space-y-3">
                    @foreach($recentTimeEntries as $entry)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-neutral-100' : '' }}">
                        <div>
                            <p class="text-sm font-medium text-neutral-700">{{ $entry->task->taskTitle ?? 'Unknown Task' }}</p>
                            <p class="text-xs text-neutral-400">{{ $entry->start_time?->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-neutral-700">{{ $entry->getFormattedDuration() }}</p>
                            <x-ui.badge :variant="$entry->is_approved ? 'success' : 'warning'" size="sm">
                                {{ $entry->is_approved ? 'Approved' : 'Pending' }}
                            </x-ui.badge>
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-ui.card>
            @endif
        </div>

        <!-- Sidebar - Actions -->
        <div class="lg:col-span-1 space-y-6">
            @if($hourRequest->isPending())
            <!-- Approve Form -->
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center gap-2">
                        <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                        <h3 class="text-lg font-medium text-neutral-700">Approve Request</h3>
                    </div>
                </x-slot:header>
                
                <form action="{{ route('admin.hour-requests.approve', $hourRequest->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="approved_hours" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                Approved Hours
                            </label>
                            <x-ui.input 
                                type="number" 
                                name="approved_hours" 
                                id="approved_hours"
                                step="0.5"
                                :min="$hourRequest->current_max_hours"
                                :value="$hourRequest->requested_max_hours"
                                required
                            />
                            <p class="mt-1.5 text-xs text-neutral-400">You can approve a different number than requested</p>
                        </div>
                        <div>
                            <label for="approve_notes" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                Notes (Optional)
                            </label>
                            <x-ui.textarea 
                                name="review_notes" 
                                id="approve_notes"
                                rows="3"
                                placeholder="Add any notes for the adiutor..."
                            />
                        </div>
                        <x-ui.button type="submit" variant="success" class="w-full">
                            <x-lucide-check class="w-4 h-4" />
                            Approve Request
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>

            <!-- Reject Form -->
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center gap-2">
                        <x-lucide-x-circle class="w-5 h-5 text-error-500" />
                        <h3 class="text-lg font-medium text-neutral-700">Reject Request</h3>
                    </div>
                </x-slot:header>
                
                <form action="{{ route('admin.hour-requests.reject', $hourRequest->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="reject_notes" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                Reason for Rejection <span class="text-error-500">*</span>
                            </label>
                            <x-ui.textarea 
                                name="review_notes" 
                                id="reject_notes"
                                rows="4"
                                placeholder="Explain why this request is being rejected..."
                                required
                                minlength="10"
                            />
                        </div>
                        <x-ui.button 
                            type="submit" 
                            variant="danger" 
                            class="w-full"
                            onclick="return window.Alerts.confirmForm(event, 'Reject Request', 'Are you sure you want to reject this request?')"
                        >
                            <x-lucide-x class="w-4 h-4" />
                            Reject Request
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
            @else
            <!-- Already Reviewed -->
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center gap-2">
                        <x-lucide-info class="w-5 h-5 text-neutral-400" />
                        <h3 class="text-lg font-medium text-neutral-700">Review Details</h3>
                    </div>
                </x-slot:header>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-neutral-500">Status</p>
                        @php
                            $reviewStatusVariant = $statusVariants[$hourRequest->status] ?? 'neutral';
                        @endphp
                        <x-ui.badge :variant="$reviewStatusVariant">
                            {{ $hourRequest->status_label }}
                        </x-ui.badge>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500">Reviewed By</p>
                        <p class="font-medium text-neutral-700">{{ $hourRequest->reviewer->fullName ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-neutral-500">Reviewed At</p>
                        <p class="font-medium text-neutral-700">{{ $hourRequest->reviewed_at?->format('M d, Y g:i A') }}</p>
                    </div>
                    @if($hourRequest->isApproved() && $hourRequest->approved_hours)
                    <div>
                        <p class="text-sm text-neutral-500">Approved Hours</p>
                        <p class="font-medium text-success-600">{{ $hourRequest->approved_hours }} hours</p>
                    </div>
                    @endif
                    @if($hourRequest->review_notes)
                    <div>
                        <p class="text-sm text-neutral-500">Notes</p>
                        <p class="text-neutral-700">{{ $hourRequest->review_notes }}</p>
                    </div>
                    @endif
                </div>
            </x-ui.card>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('adiutor.layouts.app')

@section('title', 'Hour Increase Requests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Hour Requests'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Hour Increase Requests</h1>
        <p class="text-sm text-neutral-500 mt-1">Request additional hours when approaching your max limit</p>
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

    <!-- Warning Assignments (Approaching Max Hours) -->
    @if($warningAssignments->isNotEmpty())
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-neutral-800 mb-4">Approaching Max Hours</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($warningAssignments as $assignment)
            <div class="bg-warning-50 border border-warning-100 rounded-2xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-neutral-800">{{ $assignment->project->title ?? 'Project' }}</h3>
                        <p class="text-sm text-neutral-500">Max: {{ $assignment->max_hours }} hours</p>
                    </div>
                    <x-ui.badge variant="warning">
                        {{ round(($assignment->total_hours_logged / $assignment->max_hours) * 100) }}% used
                    </x-ui.badge>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-3">
                    <div class="h-2 bg-warning-200 rounded-full overflow-hidden">
                        @php
                            $percentage = min(100, ($assignment->total_hours_logged / $assignment->max_hours) * 100);
                            $barColor = $percentage >= 100 ? 'bg-error-500' : ($percentage >= 90 ? 'bg-warning-500' : 'bg-success-500');
                        @endphp
                        <div class="{{ $barColor }} h-full rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-neutral-500 mt-1">
                        <span>{{ number_format($assignment->total_hours_logged, 1) }} hrs logged</span>
                        <span>{{ number_format($assignment->max_hours - $assignment->total_hours_logged, 1) }} hrs remaining</span>
                    </div>
                </div>
                
                <a href="{{ route('adiutor.hour-requests.create', ['assignment_id' => $assignment->id]) }}" 
                   class="inline-flex items-center gap-2 w-full justify-center px-4 py-2 text-sm font-medium text-warning-800 bg-warning-100 rounded-xl hover:bg-warning-200 transition-colors">
                    <x-lucide-plus class="w-4 h-4" />
                    Request More Hours
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Requests List -->
    <x-ui.card class="overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100">
            <h2 class="text-lg font-semibold text-neutral-800">Your Requests</h2>
        </div>

        @if($requests->isEmpty())
        <div class="p-12 text-center">
            <div class="flex justify-center mb-4">
                <div class="p-4 bg-neutral-100 rounded-full">
                    <x-lucide-clock class="w-8 h-8 text-neutral-400" />
                </div>
            </div>
            <h3 class="text-lg font-semibold text-neutral-800 mb-1">No requests yet</h3>
            <p class="text-sm text-neutral-500">When you need more hours on an assignment, you can request an increase here.</p>
        </div>
        @else
        <div class="divide-y divide-neutral-100">
            @foreach($requests as $request)
            <div class="px-6 py-4 hover:bg-neutral-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-neutral-800">{{ $request->project->title ?? 'Project' }}</h3>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $request->status_badge_class }}">
                                {{ $request->status_label }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-neutral-500">
                            <span>Current: {{ $request->current_max_hours }} hrs</span>
                            <x-lucide-arrow-right class="w-4 h-4" />
                            <span class="font-medium text-neutral-700">Requested: {{ $request->requested_max_hours }} hrs</span>
                            <span class="text-neutral-300">|</span>
                            <span>{{ $request->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($request->isApproved() && $request->approved_hours)
                        <p class="text-sm text-success-600 mt-1 flex items-center gap-1">
                            <x-lucide-check class="w-4 h-4" />
                            Approved for {{ $request->approved_hours }} hours
                        </p>
                        @endif
                        @if($request->isRejected() && $request->review_notes)
                        <p class="text-sm text-error-600 mt-1">
                            Reason: {{ $request->review_notes }}
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('adiutor.hour-requests.show', $request->id) }}" 
                           class="px-3 py-1.5 text-sm text-neutral-600 hover:text-neutral-800 hover:bg-neutral-100 rounded-lg transition-colors">
                            View
                        </a>
                        @if($request->isPending())
                        <form action="{{ route('adiutor.hour-requests.cancel', $request->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to cancel this request?');">
                            @csrf
                            @method('DELETE')
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

        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100 bg-neutral-50">
            {{ $requests->links() }}
        </div>
        @endif
        @endif
    </x-ui.card>
</div>
@endsection

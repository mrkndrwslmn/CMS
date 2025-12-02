@extends('adiutor.layouts.app')

@section('title', 'Hour Increase Requests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Hour Increase Requests</h1>
        <p class="text-sm text-gray-500 mt-1">Request additional hours when approaching your max limit</p>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="ml-3 text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Warning Assignments (Approaching Max Hours) -->
    @if($warningAssignments->isNotEmpty())
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Approaching Max Hours</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($warningAssignments as $assignment)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $assignment->project->title ?? 'Project' }}</h3>
                        <p class="text-sm text-gray-500">Max: {{ $assignment->max_hours }} hours</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                        {{ round(($assignment->total_hours_logged / $assignment->max_hours) * 100) }}% used
                    </span>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-3">
                    <div class="h-2 bg-yellow-200 rounded-full overflow-hidden">
                        @php
                            $percentage = min(100, ($assignment->total_hours_logged / $assignment->max_hours) * 100);
                            $barColor = $percentage >= 100 ? 'bg-red-500' : ($percentage >= 90 ? 'bg-yellow-500' : 'bg-green-500');
                        @endphp
                        <div class="{{ $barColor }} h-full rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>{{ number_format($assignment->total_hours_logged, 1) }} hrs logged</span>
                        <span>{{ number_format($assignment->max_hours - $assignment->total_hours_logged, 1) }} hrs remaining</span>
                    </div>
                </div>
                
                <a href="{{ route('adiutor.hour-requests.create', ['assignment_id' => $assignment->id]) }}" 
                   class="inline-flex items-center gap-2 w-full justify-center px-4 py-2 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Request More Hours
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Requests List -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Your Requests</h2>
        </div>

        @if($requests->isEmpty())
        <div class="p-12 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No requests yet</h3>
            <p class="text-sm text-gray-500">When you need more hours on an assignment, you can request an increase here.</p>
        </div>
        @else
        <div class="divide-y divide-gray-200">
            @foreach($requests as $request)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $request->project->title ?? 'Project' }}</h3>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $request->status_badge_class }}">
                                {{ $request->status_label }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span>Current: {{ $request->current_max_hours }} hrs</span>
                            <span>→</span>
                            <span class="font-medium text-gray-700">Requested: {{ $request->requested_max_hours }} hrs</span>
                            <span class="text-gray-400">|</span>
                            <span>{{ $request->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($request->isApproved() && $request->approved_hours)
                        <p class="text-sm text-green-600 mt-1">
                            ✓ Approved for {{ $request->approved_hours }} hours
                        </p>
                        @endif
                        @if($request->isRejected() && $request->review_notes)
                        <p class="text-sm text-red-600 mt-1">
                            Reason: {{ $request->review_notes }}
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('adiutor.hour-requests.show', $request->id) }}" 
                           class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            View
                        </a>
                        @if($request->isPending())
                        <form action="{{ route('adiutor.hour-requests.cancel', $request->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to cancel this request?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-sm text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
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
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $requests->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection

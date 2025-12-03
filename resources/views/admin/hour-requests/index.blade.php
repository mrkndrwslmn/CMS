@extends('admin.layouts.app')

@section('title', 'Hour Increase Requests')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Hour Requests', 'icon' => 'clock'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Hour Increase Requests" 
        description="Review and manage adiutor requests for additional project hours"
        class="mb-6"
    />

    @if(session('success'))
    <div class="mb-6 bg-success-50 border-l-4 border-success-500 text-success-700 p-4 rounded-lg">
        <div class="flex items-center">
            <x-lucide-check-circle class="w-5 h-5 text-success-500 mr-3" />
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending Review</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Approved This Month</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['approved_this_month'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Rejected This Month</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['rejected_this_month'] }}</p>
                </div>
                <div class="p-3 bg-error-50 rounded-xl">
                    <x-lucide-x class="w-5 h-5 text-error-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Status</label>
                <x-ui.select name="status" onchange="this.form.submit()">
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                </x-ui.select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Adiutor</label>
                <x-ui.select name="adiutor_id" onchange="this.form.submit()">
                    <option value="">All Adiutors</option>
                    @foreach($adiutors as $adiutor)
                    <option value="{{ $adiutor->id }}" {{ request('adiutor_id') == $adiutor->id ? 'selected' : '' }}>
                        {{ $adiutor->fullName }}
                    </option>
                    @endforeach
                </x-ui.select>
            </div>
        </form>
    </x-ui.card>

    <!-- Requests Table -->
    <x-ui.card>
        @if($requests->isEmpty())
        <div class="py-12 text-center">
            <div class="p-4 bg-neutral-50 rounded-full inline-flex mb-4">
                <x-lucide-inbox class="w-12 h-12 text-neutral-300" />
            </div>
            <h3 class="text-lg font-medium text-neutral-700 mb-1">No requests found</h3>
            <p class="text-sm text-neutral-400">
                @if($status === 'pending')
                There are no pending hour increase requests.
                @else
                No requests match your current filters.
                @endif
            </p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Adiutor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($requests as $request)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" 
                                         src="{{ $request->adiutor->getProfilePictureUrl() }}" 
                                         alt="{{ $request->adiutor->fullName }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-700">{{ $request->adiutor->fullName }}</div>
                                    <div class="text-sm text-neutral-400">{{ $request->adiutor->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-neutral-700">{{ $request->project->title ?? 'Unknown' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <span class="text-neutral-500">{{ $request->current_max_hours }} hrs</span>
                                <x-lucide-arrow-right class="w-3 h-3 inline mx-1 text-neutral-300" />
                                <span class="font-semibold text-neutral-800">{{ $request->requested_max_hours }} hrs</span>
                                <span class="text-xs text-primary-600 ml-1">(+{{ $request->hours_increase }})</span>
                            </div>
                            <div class="text-xs text-neutral-400 mt-1">
                                {{ number_format($request->hours_already_tracked, 1) }} hrs tracked
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusVariants = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'error',
                                ];
                                $variant = $statusVariants[$request->status] ?? 'neutral';
                            @endphp
                            <x-ui.badge :variant="$variant">
                                {{ $request->status_label }}
                            </x-ui.badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            {{ $request->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.hour-requests.show', $request->id) }}" 
                                   class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 transition-colors">
                                    <x-lucide-eye class="w-4 h-4" />
                                    View
                                </a>
                                @if($request->isPending())
                                <button type="button" 
                                        onclick="quickApprove({{ $request->id }})"
                                        class="inline-flex items-center gap-1 text-sm text-success-600 hover:text-success-700 transition-colors">
                                    <x-lucide-check class="w-4 h-4" />
                                    Approve
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
        <div class="mt-6 pt-6 border-t border-neutral-100">
            {{ $requests->withQueryString()->links() }}
        </div>
        @endif
        @endif
    </x-ui.card>
</div>

@push('scripts')
<script>
function quickApprove(requestId) {
    if (!confirm('Quick approve this request with the requested hours?')) return;
    
    fetch(`/admin/hour-requests/${requestId}/quick-approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Failed to approve request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>
@endpush
@endsection

@extends('admin.layouts.app')

@section('title', 'Budget Change Requests')
@section('page-title', 'Budget Change Requests')
@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Budget Requests', 'icon' => 'wallet'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Budget Change Requests" 
        description="Review and manage budget modification requests from adiutors"
        class="mb-6"
    />

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.stat-card 
            label="Pending" 
            :value="number_format($stats['pending'])" 
            icon="clock"
            icon-color="warning"
        />
        <x-ui.stat-card 
            label="Approved" 
            :value="number_format($stats['approved'])" 
            icon="check-circle"
            icon-color="success"
        />
        <x-ui.stat-card 
            label="Rejected" 
            :value="number_format($stats['rejected'])" 
            icon="x-circle"
            icon-color="error"
        />
        <x-ui.stat-card 
            label="Total Requests" 
            :value="number_format($stats['total'])" 
            icon="wallet"
            icon-color="primary"
        />
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.budget-requests.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-ui.input 
                    name="search" 
                    :value="request('search')"
                    placeholder="Search by task or adiutor..."
                    label="Search"
                />
                
                <x-ui.select name="status" label="Status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </x-ui.select>
                
                <div class="flex items-end gap-2">
                    <x-ui.button type="submit" icon="search">
                        Filter
                    </x-ui.button>
                    
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.budget-requests.index') }}">
                            <x-ui.button type="button" variant="ghost" icon="x">
                                Clear
                            </x-ui.button>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Budget Requests Table -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        @if($budgetRequests->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-100">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Task / Project</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Adiutor</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Current Budget</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Requested Budget</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Change</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Date Requested</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-medium uppercase tracking-wider text-neutral-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-100">
                        @foreach($budgetRequests as $request)
                            <tr class="hover:bg-neutral-50 transition-colors {{ $request->status === 'pending' ? 'bg-warning-50/30' : '' }}">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-neutral-800">{{ $request->task->taskTitle ?? 'N/A' }}</p>
                                        <p class="text-xs text-neutral-500 flex items-center gap-1 mt-1">
                                            <x-lucide-folder-kanban class="w-3 h-3" />
                                            {{ $request->task->project->title ?? 'No Project' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img src="{{ $request->adiutor->getProfilePictureUrl() }}" 
                                                 alt="{{ $request->adiutor->fullName }}"
                                                 class="w-8 h-8 rounded-full object-cover">
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-neutral-800">{{ $request->adiutor->fullName }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-700">
                                    ₱{{ number_format($request->current_budget, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-neutral-800">
                                    ₱{{ number_format($request->requested_budget, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $difference = $request->requested_budget - $request->current_budget;
                                        $percentage = $request->current_budget > 0 ? (($difference / $request->current_budget) * 100) : 100;
                                    @endphp
                                    <div class="text-sm">
                                        <span class="font-medium {{ $difference > 0 ? 'text-error-600' : 'text-success-600' }}">
                                            {{ $difference > 0 ? '+' : '' }}₱{{ number_format(abs($difference), 2) }}
                                        </span>
                                        <span class="text-xs text-neutral-500 block">
                                            ({{ $difference > 0 ? '+' : '' }}{{ number_format($percentage, 1) }}%)
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($request->status === 'pending')
                                        <x-ui.badge type="warning">
                                            Pending
                                        </x-ui.badge>
                                    @elseif($request->status === 'approved')
                                        <x-ui.badge type="success">
                                            Approved
                                        </x-ui.badge>
                                    @else
                                        <x-ui.badge type="error">
                                            Rejected
                                        </x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-700">
                                    <div class="text-sm font-medium text-neutral-800">{{ $request->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-neutral-500">{{ $request->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        <a href="{{ route('admin.budget-requests.show', $request->id) }}" 
                                           class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                                           title="View Details">
                                            <x-lucide-eye class="w-4 h-4" />
                                        </a>
                                        
                                        @if($request->status === 'pending')
                                            <button onclick="openApproveModal({{ $request->id }}, '{{ $request->task->taskTitle ?? 'N/A' }}', '{{ $request->adiutor->fullName }}')" 
                                                    class="p-2 text-neutral-400 hover:text-success-600 hover:bg-success-50 rounded-lg transition-colors"
                                                    title="Approve">
                                                <x-lucide-check-circle class="w-4 h-4" />
                                            </button>
                                            
                                            <button onclick="openRejectModal({{ $request->id }}, '{{ $request->task->taskTitle ?? 'N/A' }}', '{{ $request->adiutor->fullName }}')" 
                                                    class="p-2 text-neutral-400 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors"
                                                    title="Reject">
                                                <x-lucide-x-circle class="w-4 h-4" />
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($budgetRequests->hasPages())
                <div class="px-6 py-4 border-t border-neutral-100">
                    <x-ui.pagination :paginator="$budgetRequests" />
                </div>
            @endif
        @else
            <x-ui.empty-state 
                icon="wallet"
                title="No budget requests found"
                :description="request()->hasAny(['search', 'status']) 
                    ? 'No requests match your current filters. Try adjusting your search criteria.' 
                    : 'No budget change requests have been submitted yet.'"
                class="py-12"
            >
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.budget-requests.index') }}" class="mt-4 inline-block">
                        <x-ui.button variant="secondary" icon="x">
                            Clear Filters
                        </x-ui.button>
                    </a>
                @endif
            </x-ui.empty-state>
        @endif
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" x-data="{ show: false }" x-show="show" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm" @click="show = false"></div>
            
            <div class="relative bg-white rounded-2xl shadow-lg max-w-md w-full p-6">
                <form id="approveForm" method="POST" action="">
                    @csrf
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-800">Approve Budget Request</h3>
                            <p class="text-sm text-neutral-500 mt-1">Confirm approval for this budget change</p>
                        </div>
                        <button type="button" onclick="closeApproveModal()" class="p-2 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">
                            <x-lucide-x class="w-5 h-5" />
                        </button>
                    </div>
                    
                    <p class="text-sm text-neutral-600 mb-4">
                        Approve budget change request for <strong id="approveTaskName" class="text-neutral-800"></strong> 
                        by <strong id="approveAdiutorName" class="text-neutral-800"></strong>?
                    </p>
                    
                    <!-- Form Content -->
                    <div class="mb-6">
                        <x-ui.input 
                            type="textarea"
                            label="Review Notes (Optional)"
                            name="review_notes"
                            rows="3"
                            placeholder="Add any notes about this approval..."
                        />
                    </div>
                    
                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200">
                        <x-ui.button type="button" variant="ghost" onclick="closeApproveModal()">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="success" icon="check-circle">
                            Approve
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" x-data="{ show: false }" x-show="show" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm" @click="show = false"></div>
            
            <div class="relative bg-white rounded-2xl shadow-lg max-w-md w-full p-6">
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-800">Reject Budget Request</h3>
                            <p class="text-sm text-neutral-500 mt-1">Provide a reason for rejection</p>
                        </div>
                        <button type="button" onclick="closeRejectModal()" class="p-2 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">
                            <x-lucide-x class="w-5 h-5" />
                        </button>
                    </div>
                    
                    <p class="text-sm text-neutral-600 mb-4">
                        Reject budget change request for <strong id="rejectTaskName" class="text-neutral-800"></strong> 
                        by <strong id="rejectAdiutorName" class="text-neutral-800"></strong>?
                    </p>
                    
                    <!-- Form Content -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">
                            Rejection Reason <span class="text-error-500">*</span>
                        </label>
                        <textarea name="review_notes" 
                                  rows="3" 
                                  required
                                  class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                                  placeholder="Explain why this request is being rejected..."></textarea>
                    </div>
                    
                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200">
                        <x-ui.button type="button" variant="ghost" onclick="closeRejectModal()">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="danger" icon="x-circle">
                            Reject
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openApproveModal(id, taskName, adiutorName) {
    document.getElementById('approveTaskName').textContent = taskName;
    document.getElementById('approveAdiutorName').textContent = adiutorName;
    document.getElementById('approveForm').action = `/admin/budget-requests/${id}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
    document.getElementById('approveModal').__x.$data.show = true;
}

function closeApproveModal() {
    document.getElementById('approveModal').__x.$data.show = false;
}

function openRejectModal(id, taskName, adiutorName) {
    document.getElementById('rejectTaskName').textContent = taskName;
    document.getElementById('rejectAdiutorName').textContent = adiutorName;
    document.getElementById('rejectForm').action = `/admin/budget-requests/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').__x.$data.show = true;
}

function closeRejectModal() {
    document.getElementById('rejectModal').__x.$data.show = false;
}

// Close modals on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeApproveModal();
        closeRejectModal();
    }
});
</script>
@endsection

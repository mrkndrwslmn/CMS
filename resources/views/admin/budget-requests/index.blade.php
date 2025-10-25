@extends('admin.layouts.app')

@section('title', 'Budget Change Requests')
@section('page-title', 'Budget Change Requests')
@section('content')
<div class="pt-20 pb-8">
    <div class="container mx-auto px-4">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Approved</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Rejected</p>
                        <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-times-circle text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total</p>
                        <p class="text-3xl font-bold text-primary">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-dollar-sign text-2xl text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('admin.budget-requests.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by task or adiutor..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                </div>
                
                <div class="min-w-[150px]">
                    <select name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-secondary transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.budget-requests.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i> Clear
                </a>
                @endif
            </form>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Budget Requests Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Task / Project
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Adiutor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Current Budget
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Requested Budget
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Change
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date Requested
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($budgetRequests as $request)
                        <tr class="hover:bg-gray-50 {{ $request->status === 'pending' ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $request->task->taskTitle ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-project-diagram mr-1"></i>
                                        {{ $request->task->project->title ?? 'No Project' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ $request->adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($request->adiutor->fullName) }}" 
                                         alt="{{ $request->adiutor->fullName }}"
                                         class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm text-gray-900">{{ $request->adiutor->fullName }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                ₱{{ number_format($request->current_budget, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                ₱{{ number_format($request->requested_budget, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $difference = $request->requested_budget - $request->current_budget;
                                    $percentage = $request->current_budget > 0 ? (($difference / $request->current_budget) * 100) : 100;
                                @endphp
                                <div class="text-sm">
                                    <span class="font-medium {{ $difference > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $difference > 0 ? '+' : '' }}₱{{ number_format(abs($difference), 2) }}
                                    </span>
                                    <span class="text-xs text-gray-500 block">
                                        ({{ $difference > 0 ? '+' : '' }}{{ number_format($percentage, 1) }}%)
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($request->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                @elseif($request->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Approved
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $request->created_at->format('M d, Y') }}
                                <span class="block text-xs">{{ $request->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.budget-requests.show', $request->id) }}" 
                                       class="text-primary hover:text-secondary"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($request->status === 'pending')
                                    <button onclick="openApproveModal({{ $request->id }}, '{{ $request->task->taskTitle ?? 'N/A' }}', '{{ $request->adiutor->fullName }}')" 
                                            class="text-green-600 hover:text-green-800"
                                            title="Approve">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    
                                    <button onclick="openRejectModal({{ $request->id }}, '{{ $request->task->taskTitle ?? 'N/A' }}', '{{ $request->adiutor->fullName }}')" 
                                            class="text-red-600 hover:text-red-800"
                                            title="Reject">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No budget change requests found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($budgetRequests->hasPages())
        <div class="mt-6">
            {{ $budgetRequests->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4 text-gray-800">Approve Budget Change Request</h3>
        <p class="text-gray-600 mb-4">
            Are you sure you want to approve the budget change request for 
            <strong id="approveTaskName"></strong> by <strong id="approveAdiutorName"></strong>?
        </p>
        
        <form id="approveForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Review Notes (Optional)</label>
                <textarea name="review_notes" 
                          rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                          placeholder="Add any notes about this approval..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        class="flex-1 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-check mr-2"></i> Approve
                </button>
                <button type="button" 
                        onclick="closeApproveModal()" 
                        class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4 text-gray-800">Reject Budget Change Request</h3>
        <p class="text-gray-600 mb-4">
            Please provide a reason for rejecting the budget change request for 
            <strong id="rejectTaskName"></strong> by <strong id="rejectAdiutorName"></strong>:
        </p>
        
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea name="review_notes" 
                          rows="3" 
                          required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                          placeholder="Explain why this request is being rejected..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" 
                        class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                    <i class="fas fa-times mr-2"></i> Reject
                </button>
                <button type="button" 
                        onclick="closeRejectModal()" 
                        class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal(id, taskName, adiutorName) {
    document.getElementById('approveTaskName').textContent = taskName;
    document.getElementById('approveAdiutorName').textContent = adiutorName;
    document.getElementById('approveForm').action = `/admin/budget-requests/${id}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function openRejectModal(id, taskName, adiutorName) {
    document.getElementById('rejectTaskName').textContent = taskName;
    document.getElementById('rejectAdiutorName').textContent = adiutorName;
    document.getElementById('rejectForm').action = `/admin/budget-requests/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
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

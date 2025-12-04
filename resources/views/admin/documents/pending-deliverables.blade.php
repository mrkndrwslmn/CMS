@extends('admin.layouts.app')

@section('title', 'Pending Deliverables')
@section('page-title', 'Pending Deliverables')

@section('content')
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">Pending Deliverables</h1>
            <p class="text-neutral-500 text-sm">Review and approve deliverables uploaded by adiutors before clients can view them</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.documents.index') }}" class="flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>All Documents
            </a>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-amber-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-amber-800">About Deliverable Approval</h3>
                <p class="text-sm text-amber-700 mt-1">
                    Deliverables uploaded by adiutors for tasks require admin approval before they become visible to clients. 
                    Review each deliverable carefully to ensure quality before approving.
                </p>
            </div>
        </div>
    </div>

    <!-- Deliverables List -->
    @if($deliverables->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="divide-y divide-neutral-200">
                @foreach($deliverables as $deliverable)
                    <div class="p-5 hover:bg-neutral-50 transition-colors" id="deliverable-{{ $deliverable->documentID }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <!-- File/Link Icon -->
                                <div class="flex-shrink-0">
                                    @if($deliverable->deliverable_type === 'link')
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-link text-blue-500 text-xl"></i>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file text-primary-500 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Deliverable Info -->
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-neutral-900">{{ $deliverable->fileName }}</h3>
                                    
                                    @if($deliverable->description)
                                        <p class="text-sm text-neutral-600 mt-1">{{ $deliverable->description }}</p>
                                    @endif
                                    
                                    <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-neutral-500">
                                        <!-- Type Badge -->
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            {{ $deliverable->deliverable_type === 'link' ? 'bg-blue-100 text-blue-700' : 'bg-primary-100 text-primary-700' }}">
                                            {{ ucfirst($deliverable->deliverable_type) }}
                                        </span>
                                        
                                        <!-- Task Info -->
                                        @if($deliverable->task)
                                            <span>
                                                <i class="fas fa-tasks mr-1"></i>
                                                <a href="{{ route('admin.tasks.show', $deliverable->taskID) }}" class="text-primary-500 hover:underline">
                                                    {{ Str::limit($deliverable->task->taskTitle, 40) }}
                                                </a>
                                            </span>
                                        @endif
                                        
                                        <!-- Project Info -->
                                        @if($deliverable->task?->project)
                                            <span>
                                                <i class="fas fa-project-diagram mr-1"></i>
                                                {{ Str::limit($deliverable->task->project->title, 30) }}
                                            </span>
                                        @endif
                                        
                                        <!-- Uploader -->
                                        @if($deliverable->uploader)
                                            <span>
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $deliverable->uploader->fullName }}
                                            </span>
                                        @endif
                                        
                                        <!-- Upload Date -->
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $deliverable->created_at->diffForHumans() }}
                                        </span>
                                        
                                        <!-- File Size (for files) -->
                                        @if($deliverable->deliverable_type === 'file' && $deliverable->fileSize)
                                            <span>
                                                <i class="fas fa-weight mr-1"></i>
                                                {{ number_format($deliverable->fileSize / 1024, 1) }} KB
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Link URL Preview -->
                                    @if($deliverable->deliverable_type === 'link' && $deliverable->link_url)
                                        <div class="mt-2">
                                            <a href="{{ $deliverable->link_url }}" target="_blank" class="inline-flex items-center text-sm text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                {{ Str::limit($deliverable->link_url, 60) }}
                                            </a>
                                        </div>
                                    @endif
                                    
                                    <!-- Rejection Info (if previously rejected) -->
                                    @if($deliverable->rejection_reason)
                                        <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                            <p class="text-sm text-red-700">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                <strong>Previously Rejected:</strong> {{ $deliverable->rejection_reason }}
                                            </p>
                                            @if($deliverable->rejected_at)
                                                <p class="text-xs text-red-500 mt-1">{{ $deliverable->rejected_at->format('M d, Y h:i A') }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2 ml-4">
                                <!-- Preview/Download -->
                                @if($deliverable->deliverable_type === 'file' && $deliverable->filePath)
                                    <a href="{{ route('admin.documents.download', $deliverable->documentID) }}" 
                                       class="p-2 text-neutral-500 hover:text-primary-500 hover:bg-primary-50 rounded-lg transition-colors"
                                       title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @elseif($deliverable->deliverable_type === 'link')
                                    <a href="{{ $deliverable->link_url }}" target="_blank"
                                       class="p-2 text-neutral-500 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                       title="Open Link">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                                
                                <!-- Approve Button -->
                                <form action="{{ route('admin.deliverables.approve', $deliverable->documentID) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors flex items-center"
                                            onclick="return confirm('Approve this deliverable? The client will be able to view it.')">
                                        <i class="fas fa-check mr-2"></i>Approve
                                    </button>
                                </form>
                                
                                <!-- Reject Button (with modal trigger) -->
                                <button type="button" 
                                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors flex items-center"
                                        onclick="openRejectModal({{ $deliverable->documentID }}, '{{ addslashes($deliverable->fileName) }}')">
                                    <i class="fas fa-times mr-2"></i>Reject
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($deliverables->hasPages())
                <div class="px-5 py-4 border-t border-neutral-200">
                    {{ $deliverables->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-500 text-4xl"></i>
            </div>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">All Caught Up!</h3>
            <p class="text-neutral-500">There are no deliverables pending approval at the moment.</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Reject Deliverable</h3>
                <p class="text-sm text-neutral-600 mb-4">
                    You are about to reject: <strong id="rejectDeliverableName"></strong>
                </p>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-neutral-700 mb-2">
                            Reason for Rejection <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reason" id="rejection_reason" rows="4" required
                            class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Please provide feedback to help the adiutor improve this deliverable..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                            Reject Deliverable
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(documentId, fileName) {
        document.getElementById('rejectDeliverableName').textContent = fileName;
        document.getElementById('rejectForm').action = "{{ url('admin/deliverables') }}/" + documentId + "/reject";
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejection_reason').value = '';
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
        }
    });
    
    // Close modal when clicking outside
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>
@endpush
@endsection

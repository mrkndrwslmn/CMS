@extends('admin.layouts.app')

@section('title', 'Document Management')
@section('page-title', 'Document Management')

@section('content')
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">Document Management</h1>
            <p class="text-neutral-500 text-sm">Upload, manage and organize all documents</p>
        </div>
        <a href="{{ route('admin.documents.create') }}" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Upload New Document
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">
        <!-- Total Documents -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-primary-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-primary-500 uppercase mb-1">Total Documents</div>
                        <div class="text-2xl font-bold text-primary-600">{{ number_format($stats['total_documents']) }}</div>
                    </div>
                    <div class="bg-primary-50 p-3 rounded-lg">
                        <i class="fas fa-file-alt text-xl text-primary-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Size -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-blue-500 uppercase mb-1">Total Size</div>
                        <div class="text-2xl font-bold text-primary-600">{{ number_format($stats['total_size'] / (1024 * 1024), 2) }} MB</div>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <i class="fas fa-database text-xl text-blue-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-green-500 uppercase mb-1">Added This Month</div>
                        <div class="text-2xl font-bold text-primary-600">{{ number_format($stats['this_month']) }}</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-calendar-alt text-xl text-green-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Types -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-indigo-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-indigo-500 uppercase mb-1">File Types</div>
                        <div class="text-2xl font-bold text-primary-600">{{ count($stats['by_type']) }}</div>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-lg">
                        <i class="fas fa-list-alt text-xl text-indigo-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-neutral-200">
            <h6 class="text-lg font-semibold text-primary-500">All Documents</h6>
        </div>

        <div class="p-6">
            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.documents.index') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-neutral-400"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search documents..." 
                                   class="w-full pl-10 pr-4 py-2 border border-neutral-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Document Type</label>
                        <select name="type" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="">All Types</option>
                            <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="report" {{ request('type') == 'report' ? 'selected' : '' }}>Report</option>
                            <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Image</option>
                            <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Document</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Client</label>
                        <select name="client" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                    {{ $client->fullName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-3 items-end">
                        <button type="submit" class="flex-1 bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.documents.index') }}" class="flex-1 border border-neutral-300 text-neutral-700 hover:bg-neutral-100 py-2 px-4 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-times mr-2"></i> Clear
                        </a>
                    </div>
                </div>
            </form>

            <!-- File Types Chart -->
            @if(count($stats['by_type']) > 0)
            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-sm p-5 border border-neutral-100">
                    <h3 class="text-base font-semibold text-primary-500 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-primary-500 mr-2"></i>
                        Documents by Type
                    </h3>
                    <div class="max-w-md mx-auto">
                        <canvas id="documentTypesChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <!-- Bulk Actions Form -->
            <form id="bulk-actions-form" method="POST" action="{{ route('admin.documents.bulk-action') }}">
                @csrf
                <div class="flex flex-col sm:flex-row gap-4 mb-6 items-center">
                    <div class="w-full sm:w-auto">
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Bulk Actions</label>
                        <select name="action" id="bulk-action" class="px-4 py-2 border border-neutral-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 transition-colors" required>
                            <option value="">Select Action</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-auto sm:self-end">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center" id="apply-bulk-action">
                            <i class="fas fa-check mr-2"></i> Apply
                        </button>
                    </div>
                </div>

                <!-- Documents Table -->
                <div class="overflow-x-auto rounded-lg border border-neutral-200">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" id="select-all" class="w-4 h-4 text-primary-600 rounded border-neutral-300 focus:ring-primary-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">File Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Size</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Related Task</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Uploaded</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @forelse($documents as $document)
                                <tr class="hover:bg-neutral-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" name="document_ids[]" value="{{ $document->documentID }}" class="document-checkbox w-4 h-4 text-primary-600 rounded border-neutral-300 focus:ring-primary-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.documents.show', $document->documentID) }}" class="text-primary-600 hover:text-primary-700 font-medium flex items-center">
                                            <i class="fas fa-file-alt text-neutral-400 mr-2"></i>
                                            {{ $document->fileName }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                            {{ $document->fileType ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-neutral-600">
                                        {{ number_format($document->fileSize / 1024, 2) }} KB
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($document->task)
                                            <a href="{{ route('admin.tasks.show', $document->task->taskID) }}" class="text-primary-600 hover:text-primary-700">
                                                {{ Str::limit($document->task->taskTitle, 30) }}
                                            </a>
                                        @else
                                            <span class="text-neutral-400 italic">None</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-neutral-600">
                                        {{ $document->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.documents.show', $document->documentID) }}" 
                                               class="text-primary-600 hover:text-primary-800 hover:bg-primary-50 p-2 rounded-lg transition-colors"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.documents.download', $document->documentID) }}" 
                                               class="text-primary-600 hover:text-primary-800 hover:bg-primary-50 p-2 rounded-lg transition-colors"
                                               title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('admin.documents.edit', $document->documentID) }}" 
                                               class="text-primary-600 hover:text-primary-800 hover:bg-primary-50 p-2 rounded-lg transition-colors"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="text-error-600 hover:text-error-800 hover:bg-error-50 p-2 rounded-lg transition-colors delete-document" 
                                                    data-document-id="{{ $document->documentID }}"
                                                    data-document-name="{{ $document->fileName }}"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-neutral-400">
                                            <div class="bg-neutral-100 p-6 rounded-full mb-4">
                                                <i class="fas fa-folder-open text-4xl text-neutral-400"></i>
                                            </div>
                                            <p class="text-lg font-medium text-neutral-500">No documents found</p>
                                            <p class="text-sm mt-1">Upload your first document to get started</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                @if(isset($documents) && !is_array($documents) && method_exists($documents, 'links'))
                    {{ $documents->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-neutral-900 bg-opacity-50 z-50 items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
        <div class="px-6 py-4 border-b border-neutral-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-neutral-800">Confirm Deletion</h3>
                <button type="button" class="text-neutral-400 hover:text-neutral-600 transition-colors" onclick="document.getElementById('deleteModal').style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="flex items-start space-x-4">
                <div class="bg-error-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-error-500"></i>
                </div>
                <div class="flex-1">
                    <p class="text-neutral-700 text-sm mb-2">Are you sure you want to delete this document? This action cannot be undone.</p>
                    <p class="text-error-600 font-medium text-sm" id="delete-document-name"></p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-neutral-50 rounded-b-lg flex justify-end space-x-3">
            <button type="button" 
                    class="px-4 py-2 bg-white border border-neutral-300 text-neutral-700 font-medium rounded-lg hover:bg-neutral-100 transition-colors"
                    onclick="document.getElementById('deleteModal').style.display='none'">
                Cancel
            </button>
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-error-600 text-white font-medium rounded-lg hover:bg-error-700 transition-colors">
                    Delete Document
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart setup
        @if(count($stats['by_type']) > 0)
        const typeLabels = {!! json_encode(array_keys($stats['by_type'])) !!};
        const typeData = {!! json_encode(array_values($stats['by_type'])) !!};
        
        const ctx = document.getElementById('documentTypesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: typeLabels.map(type => type ? type : 'Unknown'),
                    datasets: [{
                        data: typeData,
                        backgroundColor: [
                            '#3B82F6', '#10B981', '#06B6D4', '#F59E0B', '#EF4444',
                            '#8B5CF6', '#6366F1', '#EC4899', '#F97316', '#14B8A6'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
        @endif

        // Select all checkbox
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.document-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }

        // Bulk action form submission
        const bulkActionsForm = document.getElementById('bulk-actions-form');
        if (bulkActionsForm) {
            bulkActionsForm.addEventListener('submit', function(e) {
                const action = document.getElementById('bulk-action').value;
                const checked = document.querySelectorAll('.document-checkbox:checked');
                
                if (action === '') {
                    e.preventDefault();
                    alert('Please select an action to perform.');
                    return false;
                }
                
                if (checked.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one document.');
                    return false;
                }
                
                if (action === 'delete' && !confirm('Are you sure you want to delete the selected documents? This action cannot be undone.')) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        // Delete modal setup
        const deleteButtons = document.querySelectorAll('.delete-document');
        const deleteModal = document.getElementById('deleteModal');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const documentId = this.getAttribute('data-document-id');
                const documentName = this.getAttribute('data-document-name');
                
                const deleteDocumentName = document.getElementById('delete-document-name');
                const deleteForm = document.getElementById('delete-form');
                
                if (deleteDocumentName) {
                    deleteDocumentName.textContent = documentName;
                }
                
                if (deleteForm) {
                    deleteForm.action = `/admin/documents/${documentId}`;
                }
                
                if (deleteModal) {
                    deleteModal.classList.remove('hidden');
                    deleteModal.style.display = 'flex';
                }
            });
        });

        // Close modal when clicking outside or on close button
        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    deleteModal.style.display = 'none';
                }
            });

            const closeButtons = deleteModal.querySelectorAll('[onclick*="deleteModal"]');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    deleteModal.style.display = 'none';
                });
            });
        }
    });
</script>
@endsection
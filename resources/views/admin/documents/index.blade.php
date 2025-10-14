@extends('admin.layouts.app')

@section('title', 'Document Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Document Management</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">Documents</span>
                </nav>
            </div>
            <a href="{{ route('admin.documents.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-accent-500 to-accent-600 text-white font-medium rounded-lg hover:from-accent-600 hover:to-accent-700 transition-all duration-200 shadow-lg shadow-accent-500/30">
                <i class="fas fa-plus mr-2"></i>
                Upload New Document
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Documents -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Documents</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_documents']) }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Size -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium mb-1">Total Size</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['total_size'] / (1024 * 1024), 2) }} MB</p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <i class="fas fa-database text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Added This Month</p>
                    <p class="text-3xl font-bold">{{ number_format($stats['this_month']) }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- File Types -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">File Types</p>
                    <p class="text-3xl font-bold">{{ count($stats['by_type']) }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <i class="fas fa-list-alt text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <i class="fas fa-folder-open text-gray-600 mr-2"></i>
                <h2 class="text-lg font-semibold text-gray-800">All Documents</h2>
            </div>
        </div>

        <div class="p-6">
            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.documents.index') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-6 lg:grid-cols-12 gap-4">
                    <div class="md:col-span-6 lg:col-span-4">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search documents..." 
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="md:col-span-2 lg:col-span-2">
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            <option value="">All Types</option>
                            <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="report" {{ request('type') == 'report' ? 'selected' : '' }}>Report</option>
                            <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Image</option>
                            <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Document</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <select name="client" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                    {{ $client->fullName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-1 lg:col-span-2">
                        <button type="submit" class="w-full px-4 py-2.5 bg-accent-500 text-white font-medium rounded-lg hover:bg-accent-600 transition-colors">
                            Filter
                        </button>
                    </div>
                    <div class="md:col-span-1 lg:col-span-1">
                        <a href="{{ route('admin.documents.index') }}" class="block w-full px-4 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors text-center">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- File Types Chart -->
            @if(count($stats['by_type']) > 0)
            <div class="mb-8">
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-accent-500 mr-2"></i>
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
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <select name="action" id="bulk-action" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all" required>
                        <option value="">Select Bulk Action</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 text-white font-medium rounded-lg hover:bg-amber-600 transition-colors shadow-md hover:shadow-lg" id="apply-bulk-action">
                        Apply Action
                    </button>
                </div>

                <!-- Documents Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" id="select-all" class="w-4 h-4 text-accent-600 rounded border-gray-300 focus:ring-accent-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">File Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Size</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Related Task</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Uploaded</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($documents as $document)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" name="document_ids[]" value="{{ $document->documentID }}" class="document-checkbox w-4 h-4 text-accent-600 rounded border-gray-300 focus:ring-accent-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.documents.show', $document->documentID) }}" class="text-accent-600 hover:text-accent-700 font-medium flex items-center">
                                            <i class="fas fa-file-alt text-gray-400 mr-2"></i>
                                            {{ $document->fileName }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $document->fileType ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ number_format($document->fileSize / 1024, 2) }} KB
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($document->task)
                                            <a href="{{ route('admin.tasks.show', $document->task->taskID) }}" class="text-accent-600 hover:text-accent-700">
                                                {{ Str::limit($document->task->taskTitle, 30) }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">None</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $document->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.documents.show', $document->documentID) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.documents.download', $document->documentID) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                               title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('admin.documents.edit', $document->documentID) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:bg-red-50 rounded-lg transition-colors delete-document" 
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
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-folder-open text-6xl mb-4"></i>
                                            <p class="text-lg font-medium text-gray-500">No documents found</p>
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
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Confirm Deletion</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="document.getElementById('deleteModal').style.display='none'">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-gray-700 mb-2">Are you sure you want to delete this document? This action cannot be undone.</p>
                    <p class="text-red-600 font-semibold" id="delete-document-name"></p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
            <button type="button" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors"
                    onclick="document.getElementById('deleteModal').style.display='none'">
                Cancel
            </button>
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors shadow-md hover:shadow-lg">
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
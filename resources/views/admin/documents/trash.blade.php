@extends('admin.layouts.app')

@section('title', 'Document Trash')
@section('page-title', 'Document Trash')

@section('content')
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">Document Trash</h1>
            <p class="text-neutral-500 text-sm">Deleted documents can be restored or permanently deleted</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.documents.index') }}" class="flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Documents
            </a>
            @if($documents->count() > 0)
            <form action="{{ route('admin.documents.empty-trash') }}" method="POST" onsubmit="return window.Alerts.confirmDeleteForm(event, 'Empty Trash', 'Are you sure you want to permanently delete ALL documents in trash? This action cannot be undone!')">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-trash mr-2"></i>Empty Trash
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.documents.trash') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by file name..." 
                           class="w-full pl-10 pr-4 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <i class="fas fa-search absolute left-3 top-3 text-neutral-400"></i>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                Search
            </button>
            @if(request('search'))
            <a href="{{ route('admin.documents.trash') }}" class="px-4 py-2 bg-neutral-100 text-neutral-700 rounded-lg hover:bg-neutral-200 transition-colors">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Trashed Documents Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($documents->count() > 0)
        <form id="bulkRestoreForm" action="{{ route('admin.documents.bulk-restore') }}" method="POST">
            @csrf
            <div class="p-4 border-b border-neutral-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="selectAll" class="rounded border-neutral-300 text-primary-500 focus:ring-primary-500">
                    <label for="selectAll" class="text-sm text-neutral-600">Select All</label>
                </div>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm" onclick="return window.Alerts.confirmForm(event, 'Restore Documents', 'Restore selected documents?')">
                    <i class="fas fa-undo mr-2"></i>Restore Selected
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider w-10">
                                <span class="sr-only">Select</span>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">File</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Size</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Deleted</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($documents as $document)
                        <tr class="hover:bg-neutral-50">
                            <td class="px-4 py-4">
                                <input type="checkbox" name="document_ids[]" value="{{ $document->documentID }}" 
                                       class="document-checkbox rounded border-neutral-300 text-primary-500 focus:ring-primary-500">
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-neutral-100 rounded-lg">
                                        @php
                                            $extension = strtolower(pathinfo($document->fileName, PATHINFO_EXTENSION));
                                            $iconClass = match($extension) {
                                                'pdf' => 'fa-file-pdf text-red-500',
                                                'doc', 'docx' => 'fa-file-word text-blue-500',
                                                'xls', 'xlsx' => 'fa-file-excel text-green-500',
                                                'ppt', 'pptx' => 'fa-file-powerpoint text-orange-500',
                                                'jpg', 'jpeg', 'png', 'gif', 'webp' => 'fa-file-image text-purple-500',
                                                'zip', 'rar' => 'fa-file-archive text-yellow-500',
                                                default => 'fa-file text-neutral-500',
                                            };
                                        @endphp
                                        <i class="fas {{ $iconClass }} text-xl"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-neutral-900">{{ Str::limit($document->fileName, 40) }}</p>
                                        @if($document->task)
                                        <p class="text-xs text-neutral-500">Task: {{ Str::limit($document->task->taskTitle, 30) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                    {{ strtoupper($extension) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-neutral-600">
                                {{ $document->formatted_size }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-neutral-900">{{ $document->deleted_at->format('M j, Y') }}</div>
                                <div class="text-xs text-neutral-500">{{ $document->deleted_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <form action="{{ route('admin.documents.restore', $document->documentID) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Restore">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.documents.force-delete', $document->documentID) }}" method="POST" class="inline" onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete Document', 'Permanently delete this document? This cannot be undone!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Permanently">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Pagination -->
        @if($documents->hasPages())
        <div class="px-4 py-3 border-t border-neutral-100">
            <x-ui.pagination :paginator="$documents" />
        </div>
        @endif
        @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-neutral-100 rounded-full mb-4">
                <i class="fas fa-trash-alt text-2xl text-neutral-400"></i>
            </div>
            <h3 class="text-lg font-medium text-neutral-900 mb-2">Trash is Empty</h3>
            <p class="text-neutral-500 mb-4">No deleted documents found.</p>
            <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Documents
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Select All functionality
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.document-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endpush
@endsection

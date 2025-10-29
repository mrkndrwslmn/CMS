@extends('adiutor.layouts.app')

@section('title', 'Documents')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900">Documents</h1>
        <p class="text-neutral-600 mt-2">Manage and access your project documents and files.</p>
    </div>

    <!-- Search and Filter Bar -->
    <div class="glass-card p-6 mb-8">
        <form method="GET" action="{{ route('adiutor.documents') }}" class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search documents..." 
                           class="w-full pl-10 pr-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <svg class="w-5 h-5 text-neutral-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <select name="project" class="border border-neutral-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                            {{ $project->title }}
                        </option>
                    @endforeach
                </select>
                <select name="type" class="border border-neutral-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="pdf" {{ request('type') == 'pdf' ? 'selected' : '' }}>PDF Files</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents</option>
                    <option value="archive" {{ request('type') == 'archive' ? 'selected' : '' }}>Archives</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    @if($documents->count() > 0)
        <!-- Documents Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($documents as $document)
                <div class="glass-card p-6 hover:shadow-lg transition-shadow group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            @if(str_contains($document->fileType ?? '', 'pdf'))
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @elseif(str_contains($document->fileType ?? '', 'image'))
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if($document->filePath)
                                <a href="{{ $document->filePath }}" target="_blank" class="text-neutral-400 hover:text-primary-600 transition-colors" title="Download">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                            @endif
                            <button class="text-neutral-400 hover:text-blue-600 transition-colors" title="View Details" onclick="showDocumentDetails({{ json_encode($document) }})">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <h3 class="font-medium text-neutral-900 mb-2 truncate" title="{{ $document->fileName ?? 'Untitled Document' }}">
                        {{ $document->fileName ?? 'Untitled Document' }}
                    </h3>
                    <p class="text-sm text-neutral-600 mb-3 line-clamp-2">{{ $document->description ?? 'No description available' }}</p>
                    
                    <!-- Document Metadata -->
                    <div class="space-y-2 mb-4">
                        @if($document->uploaded_by_name)
                            <div class="flex items-center text-xs text-neutral-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Uploaded by {{ $document->uploaded_by_name }}
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between text-xs text-neutral-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                @if($document->fileSize)
                                    {{ number_format($document->fileSize / 1024 / 1024, 2) }} MB
                                @else
                                    Unknown size
                                @endif
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($document->created_at)->format('M j, Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Project and Task Tags -->
                    <div class="flex flex-wrap gap-2">
                        @if($document->project_title)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ Str::limit($document->project_title, 20) }}
                            </span>
                        @endif
                        
                        @if($document->task_title)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                {{ Str::limit($document->task_title, 15) }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $documents->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="glass-card p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-neutral-900">No Documents Found</h3>
            <p class="mt-2 text-neutral-600">You don't have any documents yet. Documents will appear here as you work on projects.</p>
            <div class="mt-6">
                <a href="{{ route('adiutor.projects.index') }}" class="btn btn-primary">
                    View Your Projects
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Document Details Modal -->
<div id="documentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full">
            <div class="px-6 py-4 border-b border-neutral-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-neutral-900">Document Details</h3>
                    <button type="button" class="text-neutral-400 hover:text-neutral-600" onclick="closeDocumentModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-6" id="documentModalContent">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="px-6 py-4 bg-neutral-50 rounded-b-xl flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 text-neutral-700 hover:text-neutral-900" onclick="closeDocumentModal()">
                    Close
                </button>
                <a id="documentDownloadBtn" href="#" target="_blank" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Download
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function showDocumentDetails(document) {
    const modal = document.getElementById('documentModal');
    const content = document.getElementById('documentModalContent');
    const downloadBtn = document.getElementById('documentDownloadBtn');
    
    // Format file size
    const fileSize = document.fileSize ? (document.fileSize / 1024 / 1024).toFixed(2) + ' MB' : 'Unknown';
    
    // Format date
    const uploadDate = new Date(document.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    content.innerHTML = `
        <div class="space-y-6">
            <div class="flex items-start space-x-4">
                <div class="w-16 h-16 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-neutral-900">${document.fileName || 'Untitled Document'}</h4>
                    <p class="text-neutral-600 mt-1">${document.description || 'No description available'}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h5 class="font-medium text-neutral-900 mb-3">File Information</h5>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-600">File Size:</dt>
                            <dd class="text-sm font-medium text-neutral-900">${fileSize}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-600">File Type:</dt>
                            <dd class="text-sm font-medium text-neutral-900">${document.fileType || 'Unknown'}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-600">Uploaded:</dt>
                            <dd class="text-sm font-medium text-neutral-900">${uploadDate}</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h5 class="font-medium text-neutral-900 mb-3">Project Details</h5>
                    <dl class="space-y-2">
                        ${document.project_title ? `
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-600">Project:</dt>
                            <dd class="text-sm font-medium text-neutral-900">${document.project_title}</dd>
                        </div>` : ''}
                        ${document.task_title ? `
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-600">Task:</dt>
                            <dd class="text-sm font-medium text-neutral-900">${document.task_title}</dd>
                        </div>` : ''}
                        ${document.uploaded_by_name ? `
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-600">Uploaded by:</dt>
                            <dd class="text-sm font-medium text-neutral-900">${document.uploaded_by_name}</dd>
                        </div>` : ''}
                    </dl>
                </div>
            </div>
        </div>
    `;
    
    // Set download link
    if (document.filePath) {
        downloadBtn.href = document.filePath;
        downloadBtn.style.display = 'inline-block';
    } else {
        downloadBtn.style.display = 'none';
    }
    
    modal.classList.remove('hidden');
}

function closeDocumentModal() {
    document.getElementById('documentModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('documentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDocumentModal();
    }
});
</script>
@endsection
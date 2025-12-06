@extends('adiutor.layouts.app')

@section('title', 'Documents')

@section('content')
<div class="max-w-8xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Documents', 'icon' => 'file-text'],
    ]" />

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-800">Documents</h1>
        <p class="text-sm text-neutral-500 mt-1">Manage and access your project documents and files.</p>
    </div>

    <!-- Search and Filter Bar -->
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('adiutor.documents') }}" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search documents..." 
                           class="w-full pl-10 pr-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <x-lucide-search class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <select name="project" class="text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                            {{ $project->title }}
                        </option>
                    @endforeach
                </select>
                <select name="type" class="text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <option value="">All Types</option>
                    <option value="pdf" {{ request('type') == 'pdf' ? 'selected' : '' }}>PDF Files</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents</option>
                    <option value="archive" {{ request('type') == 'archive' ? 'selected' : '' }}>Archives</option>
                </select>
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-filter class="w-4 h-4" />
                    Filter
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    @if($documents->count() > 0)
        <!-- Documents Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($documents as $document)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            @if(str_contains($document->fileType ?? '', 'pdf'))
                                <div class="w-12 h-12 bg-error-50 rounded-xl flex items-center justify-center">
                                    <x-lucide-file-text class="w-6 h-6 text-error-500" />
                                </div>
                            @elseif(str_contains($document->fileType ?? '', 'image'))
                                <div class="w-12 h-12 bg-success-50 rounded-xl flex items-center justify-center">
                                    <x-lucide-image class="w-6 h-6 text-success-500" />
                                </div>
                            @else
                                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center">
                                    <x-lucide-file class="w-6 h-6 text-primary-500" />
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if($document->filePath)
                                <a href="{{ $document->filePath }}" target="_blank" class="p-1.5 text-neutral-400 hover:text-primary-600 hover:bg-neutral-100 rounded-lg transition-all" title="Download">
                                    <x-lucide-download class="w-4 h-4" />
                                </a>
                            @endif
                            <button class="p-1.5 text-neutral-400 hover:text-primary-600 hover:bg-neutral-100 rounded-lg transition-all" title="View Details" onclick="showDocumentDetails({{ json_encode($document) }})">
                                <x-lucide-info class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                    
                    <h3 class="text-base font-semibold text-neutral-800 group-hover:text-primary-600 transition-colors truncate mb-1" title="{{ $document->fileName ?? 'Untitled Document' }}">
                        {{ $document->fileName ?? 'Untitled Document' }}
                    </h3>
                    <p class="text-sm text-neutral-500 mb-3 line-clamp-2">{{ $document->description ?? 'No description available' }}</p>
                    
                    <!-- Document Metadata -->
                    <div class="space-y-2 mb-4">
                        @if($document->uploaded_by_name)
                            <div class="flex items-center gap-1.5 text-xs text-neutral-500">
                                <x-lucide-user class="w-3.5 h-3.5" />
                                <span>Uploaded by {{ $document->uploaded_by_name }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between text-xs text-neutral-500">
                            <span class="flex items-center gap-1.5">
                                <x-lucide-hard-drive class="w-3.5 h-3.5" />
                                @if($document->fileSize)
                                    {{ number_format($document->fileSize / 1024 / 1024, 2) }} MB
                                @else
                                    Unknown size
                                @endif
                            </span>
                            <span class="flex items-center gap-1.5">
                                <x-lucide-calendar class="w-3.5 h-3.5" />
                                {{ \Carbon\Carbon::parse($document->created_at)->format('M j, Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Project and Task Tags -->
                    <div class="flex flex-wrap gap-1.5">
                        @if($document->project_title)
                            <x-ui.badge variant="primary" size="sm">
                                <x-lucide-folder class="w-3 h-3" />
                                {{ Str::limit($document->project_title, 20) }}
                            </x-ui.badge>
                        @endif
                        
                        @if($document->task_title)
                            <x-ui.badge variant="info" size="sm">
                                <x-lucide-clipboard-list class="w-3 h-3" />
                                {{ Str::limit($document->task_title, 15) }}
                            </x-ui.badge>
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
        <x-ui.card class="p-12 text-center">
            <div class="w-16 h-16 mx-auto bg-neutral-100 rounded-2xl flex items-center justify-center mb-4">
                <x-lucide-file-text class="w-8 h-8 text-neutral-400" />
            </div>
            <h3 class="text-lg font-semibold text-neutral-800">No Documents Found</h3>
            <p class="text-sm text-neutral-500 mt-2 mb-6">You don't have any documents yet. Documents will appear here as you work on projects.</p>
            <x-ui.button variant="primary" href="{{ route('adiutor.projects.index') }}">
                <x-lucide-folder class="w-4 h-4" />
                View Your Projects
            </x-ui.button>
        </x-ui.card>
    @endif
</div>

<!-- Document Details Modal -->
<div id="documentModal" class="fixed inset-0 backdrop-blur-sm bg-neutral-900/50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-lg max-w-2xl w-full">
            <div class="px-6 py-4 border-b border-neutral-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-800">Document Details</h3>
                    <button type="button" class="p-1.5 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-all" onclick="closeDocumentModal()">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
            </div>
            <div class="px-6 py-6" id="documentModalContent">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="px-6 py-4 bg-neutral-50 rounded-b-2xl flex justify-end gap-3">
                <x-ui.button type="button" variant="ghost" onclick="closeDocumentModal()">
                    Close
                </x-ui.button>
                <x-ui.button id="documentDownloadBtn" variant="primary" href="#" target="_blank">
                    <x-lucide-download class="w-4 h-4" />
                    Download
                </x-ui.button>
            </div>
        </div>
    </div>
</div>

<script>
function showDocumentDetails(doc) {
    const modal = window.document.getElementById('documentModal');
    const content = window.document.getElementById('documentModalContent');
    const downloadBtn = window.document.getElementById('documentDownloadBtn');
    
    // Format file size
    const fileSize = doc.fileSize ? (doc.fileSize / 1024 / 1024).toFixed(2) + ' MB' : 'Unknown';
    
    // Format date
    const uploadDate = new Date(doc.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    content.innerHTML = `
        <div class="space-y-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary-500">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-base font-semibold text-neutral-800">${doc.fileName || 'Untitled Document'}</h4>
                    <p class="text-sm text-neutral-500 mt-1">${doc.description || 'No description available'}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h5 class="text-sm font-semibold text-neutral-800 mb-3">File Information</h5>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-500">File Size:</dt>
                            <dd class="text-sm font-medium text-neutral-700">${fileSize}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-500">File Type:</dt>
                            <dd class="text-sm font-medium text-neutral-700">${doc.fileType || 'Unknown'}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-500">Uploaded:</dt>
                            <dd class="text-sm font-medium text-neutral-700">${uploadDate}</dd>
                        </div>
                    </dl>
                </div>
                
                <div>
                    <h5 class="text-sm font-semibold text-neutral-800 mb-3">Project Details</h5>
                    <dl class="space-y-2">
                        ${doc.project_title ? `
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-500">Project:</dt>
                            <dd class="text-sm font-medium text-neutral-700">${doc.project_title}</dd>
                        </div>` : ''}
                        ${doc.task_title ? `
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-500">Task:</dt>
                            <dd class="text-sm font-medium text-neutral-700">${doc.task_title}</dd>
                        </div>` : ''}
                        ${doc.uploaded_by_name ? `
                        <div class="flex justify-between">
                            <dt class="text-sm text-neutral-500">Uploaded by:</dt>
                            <dd class="text-sm font-medium text-neutral-700">${doc.uploaded_by_name}</dd>
                        </div>` : ''}
                    </dl>
                </div>
            </div>
        </div>
    `;
    
    // Set download link
    if (doc.filePath) {
        downloadBtn.href = doc.filePath;
        downloadBtn.style.display = 'inline-flex';
    } else {
        downloadBtn.style.display = 'none';
    }
    
    modal.classList.remove('hidden');
}

function closeDocumentModal() {
    window.document.getElementById('documentModal').classList.add('hidden');
}

// Close modal when clicking outside
window.document.getElementById('documentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDocumentModal();
    }
});
</script>
@endsection
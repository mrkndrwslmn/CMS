@extends('admin.layouts.app')

@section('title', 'Document Details')
@section('page-title', 'Document Details')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Document Details</h1>
                <nav class="flex items-center space-x-2 text-sm text-neutral-500 mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.documents.index') }}" class="hover:text-primary-500 transition-colors">Documents</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-neutral-700">{{ $document->fileName }}</span>
                </nav>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('admin.documents.download', $document->documentID) }}" class="px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-download mr-2"></i> Download
                </a>
                <a href="{{ route('admin.documents.edit', $document->documentID) }}" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-success-50 border-l-4 border-success-500 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-success-500 mr-3"></i>
            <span class="text-success-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif
    
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Document Preview -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200 flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas {{ $document->file_icon }} mr-2"></i>
                        <h3 class="text-lg font-semibold text-neutral-800">Document Preview</h3>
                    </div>
                    @if($document->version > 1)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-code-branch mr-1"></i> Version {{ $document->version }}
                    </span>
                    @endif
                </div>
                <div class="p-6">
                    <div class="document-preview">
                        @if($document->isImage())
                            <div class="text-center">
                                <img src="{{ $document->getDisplayUrl() }}" alt="{{ $document->fileName }}" class="max-w-full h-auto rounded-lg shadow-sm mx-auto" style="max-height: 600px;">
                                <a href="{{ $document->getDisplayUrl() }}" target="_blank" class="inline-flex items-center mt-4 text-sm text-primary-600 hover:text-primary-700">
                                    <i class="fas fa-external-link-alt mr-1"></i> Open Full Size
                                </a>
                            </div>
                        @elseif($document->isPdf())
                            <div class="bg-neutral-100 rounded-lg overflow-hidden">
                                <iframe src="{{ $document->getDisplayUrl() }}#toolbar=1&navpanes=0" class="w-full h-[700px] border-0"></iframe>
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ $document->getDisplayUrl() }}" target="_blank" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700">
                                    <i class="fas fa-external-link-alt mr-1"></i> Open in New Tab
                                </a>
                            </div>
                        @else
                            <div class="py-16 text-center bg-neutral-50 rounded-lg">
                                <div class="bg-neutral-200 p-6 rounded-full inline-block mb-4">
                                    <i class="fas {{ $document->file_icon }} text-4xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-neutral-800 mb-2">{{ $document->fileName }}</h4>
                                <p class="text-neutral-500 mb-6">This file type cannot be previewed. Please download the file to view it.</p>
                                <a href="{{ route('admin.documents.download', $document->documentID) }}" class="px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors inline-flex items-center">
                                    <i class="fas fa-download mr-2"></i> Download File
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Version History (if document has versions) -->
            @php
                $versionCount = $document->getVersionCount();
            @endphp
            @if($versionCount > 1)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <div class="flex items-center">
                        <i class="fas fa-history text-primary-500 mr-2"></i>
                        <h3 class="text-lg font-semibold text-neutral-800">Version History</h3>
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-700">
                            {{ $versionCount }} versions
                        </span>
                    </div>
                </div>
                <div class="divide-y divide-neutral-100">
                    @foreach($document->allVersions()->get() as $version)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-neutral-50 {{ $version->documentID === $document->documentID ? 'bg-primary-50' : '' }}">
                        <div class="flex items-center">
                            <div class="bg-{{ $version->documentID === $document->documentID ? 'primary' : 'neutral' }}-100 p-2 rounded-lg mr-4">
                                <i class="fas {{ $version->file_icon }} text-lg"></i>
                            </div>
                            <div>
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-neutral-900">Version {{ $version->version }}</span>
                                    @if($version->documentID === $document->documentID)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                        Current
                                    </span>
                                    @endif
                                    @if($version->isLatestVersion())
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Latest
                                    </span>
                                    @endif
                                </div>
                                <p class="text-xs text-neutral-500">
                                    {{ $version->fileName }} &bull; {{ $version->formatted_size }} &bull; {{ $version->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($version->documentID !== $document->documentID)
                            <a href="{{ route('admin.documents.show', $version->documentID) }}" class="p-2 text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                            <a href="{{ route('admin.documents.download', $version->documentID) }}" class="p-2 text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Document Information -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-primary-500 mr-2"></i>
                        <h3 class="text-lg font-semibold text-neutral-800">Document Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-neutral-500">File Name</dt>
                            <dd class="mt-1 text-sm text-neutral-900">{{ $document->fileName }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-neutral-500">File Type</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {{ $document->fileType ?? 'Unknown' }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-neutral-500">File Size</dt>
                            <dd class="mt-1 text-sm text-neutral-900">{{ $document->formatted_size }}</dd>
                        </div>

                        @if($document->version > 1)
                        <div>
                            <dt class="text-sm font-medium text-neutral-500">Version</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-code-branch mr-1"></i> Version {{ $document->version }}
                                </span>
                                @if(!$document->isLatestVersion())
                                <span class="ml-2 text-xs text-warning-600">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Newer version available
                                </span>
                                @endif
                            </dd>
                        </div>
                        @endif

                        @if($document->description)
                        <div>
                            <dt class="text-sm font-medium text-neutral-500">Description</dt>
                            <dd class="mt-1 text-sm text-neutral-900">{{ $document->description }}</dd>
                        </div>
                        @endif

                        @if($document->document_type)
                        <div>
                            <dt class="text-sm font-medium text-neutral-500">Category</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-800 capitalize">
                                    {{ $document->document_type }}
                                </span>
                            </dd>
                        </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-neutral-500">Privacy</dt>
                            <dd class="mt-1">
                                @if($document->is_public)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                        <i class="fas fa-globe mr-1"></i> Public
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                        <i class="fas fa-lock mr-1"></i> Private
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-neutral-500">Uploaded On</dt>
                            <dd class="mt-1 text-sm text-neutral-900">{{ $document->created_at->format('F d, Y h:i A') }}</dd>
                        </div>

                        @if($document->task)
                        <div>
                            <dt class="text-sm font-medium text-neutral-500">Related Task</dt>
                            <dd class="mt-1">
                                <a href="{{ route('admin.tasks.show', $document->task->taskID) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    {{ $document->task->taskTitle }}
                                </a>
                            </dd>
                        </div>
                        @endif

                        @if($document->project)
                        <div>
                            <dt class="text-sm font-medium text-neutral-500">Related Project</dt>
                            <dd class="mt-1">
                                <a href="{{ route('admin.projects.show', $document->project->id) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    {{ $document->project->title }}
                                </a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <div class="flex items-center">
                        <i class="fas fa-tools text-primary-500 mr-2"></i>
                        <h3 class="text-lg font-semibold text-neutral-800">Actions</h3>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.documents.edit', $document->documentID) }}" class="w-full px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i> Edit Document
                    </a>
                    <a href="{{ route('admin.documents.download', $document->documentID) }}" class="w-full px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i> Download File
                    </a>
                    <button type="button" onclick="document.getElementById('deleteModal').classList.remove('hidden')" class="w-full px-4 py-2 bg-error-500 hover:bg-error-600 text-white rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i> Delete Document
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-neutral-900 bg-opacity-50 z-50 items-center justify-center p-4 hidden" style="display: none;" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-auto mt-20">
        <div class="px-6 py-4 border-b border-neutral-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-neutral-800">Confirm Deletion</h3>
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="flex items-start space-x-4">
                <div class="bg-warning-100 p-3 rounded-full">
                    <i class="fas fa-trash-alt text-warning-500"></i>
                </div>
                <div class="flex-1">
                    <p class="text-neutral-700 text-sm mb-2">Are you sure you want to delete this document? It will be moved to trash and can be restored later.</p>
                    <p class="text-neutral-600 font-medium text-sm">{{ $document->fileName }}</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-neutral-50 rounded-b-xl flex justify-end space-x-3">
            <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="px-4 py-2 bg-white border border-neutral-300 text-neutral-700 font-medium rounded-lg hover:bg-neutral-100 transition-colors">
                Cancel
            </button>
            <form action="{{ route('admin.documents.destroy', $document->documentID) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-warning-600 text-white font-medium rounded-lg hover:bg-warning-700 transition-colors">
                    Move to Trash
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Show modal properly
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.style.display = 'none';
        }
    });
    
    // Override button to show modal with flex
    document.querySelectorAll('[onclick*="deleteModal"]').forEach(btn => {
        if (btn.textContent.includes('Delete')) {
            btn.onclick = function() {
                const modal = document.getElementById('deleteModal');
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
            };
        }
    });
</script>
@endsection
@extends('layouts.admin')

@section('title', 'Search Documents')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Search Results</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
        <li class="breadcrumb-item active">Search Results: "{{ $query }}"</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-search me-1"></i>
            Search Results for "{{ $query }}"
        </div>
        <div class="card-body">
            @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="documentsTable">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>File Type</th>
                                <th>Size</th>
                                <th>Related Task</th>
                                <th>Uploaded</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.documents.show', $document->documentID) }}" class="text-decoration-none">
                                        <i class="fas fa-file me-2"></i>{{ $document->fileName }}
                                    </a>
                                </td>
                                <td>{{ $document->fileType }}</td>
                                <td>{{ number_format($document->fileSize / 1024, 2) }} KB</td>
                                <td>
                                    @if($document->task)
                                        <a href="{{ route('admin.tasks.show', $document->task->taskID) }}" class="text-decoration-none">
                                            {{ $document->task->taskTitle }}
                                        </a>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>{{ $document->created_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.documents.show', $document->documentID) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.documents.download', $document->documentID) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('admin.documents.edit', $document->documentID) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $document->documentID }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $document->documentID }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $document->documentID }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $document->documentID }}">Confirm Deletion</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this document: <strong>{{ $document->fileName }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admin.documents.destroy', $document->documentID) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $documents->appends(['q' => $query])->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No documents found for your search: "{{ $query }}"
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i> Back to All Documents
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Search Tips -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-lightbulb me-1"></i>
            Search Tips
        </div>
        <div class="card-body">
            <ul>
                <li>Try using shorter keywords for better results</li>
                <li>Search is performed on file names, file types, and task titles</li>
                <li>Use specific file extensions (e.g., "pdf", "doc", "xlsx") to find files by type</li>
                <li>For recent files, try searching by upload dates (e.g., "October", "2023")</li>
            </ul>
        </div>
    </div>
</div>
@endsection
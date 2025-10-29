@extends('layouts.admin')

@section('title', 'Document Details')
@section('page-title', 'Document Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Document Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
        <li class="breadcrumb-item active">{{ $document->fileName }}</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-file-alt me-1"></i>
                        Document Preview
                    </div>
                    <div>
                        <a href="{{ route('admin.documents.download', $document->documentID) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <a href="{{ route('admin.documents.edit', $document->documentID) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="document-preview">
                        @php
                            $extension = pathinfo($document->fileName, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp']);
                            $isPDF = strtolower($extension) === 'pdf';
                        @endphp

                        @if($isImage)
                            <img src="{{ $document->getDisplayUrl() }}" alt="{{ $document->fileName }}" class="img-fluid">
                        @elseif($isPDF)
                            <iframe src="{{ route('admin.documents.preview', $document->documentID) }}" width="100%" height="600px" style="border: none;"></iframe>
                        @else
                            <div class="p-5 text-center">
                                <i class="fas fa-file fa-5x mb-3"></i>
                                <h4>{{ $document->fileName }}</h4>
                                <p>This file type cannot be previewed. Please download the file to view it.</p>
                                <a href="{{ route('admin.documents.download', $document->documentID) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Document Information
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">File Name</dt>
                        <dd class="col-sm-8">{{ $document->fileName }}</dd>

                        <dt class="col-sm-4">File Type</dt>
                        <dd class="col-sm-8">{{ $document->fileType ?? 'Unknown' }}</dd>

                        <dt class="col-sm-4">File Size</dt>
                        <dd class="col-sm-8">{{ number_format($document->fileSize / 1024, 2) }} KB</dd>

                        <dt class="col-sm-4">Uploaded On</dt>
                        <dd class="col-sm-8">{{ $document->created_at->format('F d, Y h:i A') }}</dd>

                        @if($document->task)
                        <dt class="col-sm-4">Related Task</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('admin.tasks.show', $document->task->taskID) }}">
                                {{ $document->task->taskTitle }}
                            </a>
                        </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tools me-1"></i>
                    Actions
                </div>
                <div class="card-body d-flex justify-content-between">
                    <div>
                        <a href="{{ route('admin.documents.edit', $document->documentID) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Document
                        </a>
                        <a href="{{ route('admin.documents.download', $document->documentID) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i> Delete Document
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this document? This action cannot be undone.
                <p class="text-danger mt-2">{{ $document->fileName }}</p>
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
@endsection
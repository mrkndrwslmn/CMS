@extends('layouts.admin')

@section('title', 'Edit Document')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Document</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
        <li class="breadcrumb-item active">Edit: {{ $document->fileName }}</li>
    </ol>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg mt-2 mb-4">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-2">Edit Document Details</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.documents.update', $document->documentID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="title" name="title" type="text" value="{{ old('title', $document->title) }}" required />
                                    <label for="title">Document Title</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="description" name="description" style="height: 100px">{{ old('description', $document->description) }}</textarea>
                                    <label for="description">Document Description</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="document" class="form-label">Current File: {{ $document->fileName }}</label>
                                    <input class="form-control" id="document" name="document" type="file" />
                                    <small class="form-text text-muted">Leave blank to keep the existing file. Max file size: 10MB</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Select Category</option>
                                        <option value="report" {{ old('category', $document->category) == 'report' ? 'selected' : '' }}>Report</option>
                                        <option value="contract" {{ old('category', $document->category) == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="invoice" {{ old('category', $document->category) == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                        <option value="proposal" {{ old('category', $document->category) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                        <option value="presentation" {{ old('category', $document->category) == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                        <option value="other" {{ old('category', $document->category) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <label for="category">Category</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="taskID" name="taskID">
                                        <option value="">None</option>
                                        @foreach($tasks as $task)
                                            <option value="{{ $task->taskID }}" {{ old('taskID', $document->taskID) == $task->taskID ? 'selected' : '' }}>
                                                {{ $task->taskTitle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="taskID">Related Task (Optional)</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" id="isPrivate" name="isPrivate" type="checkbox" value="1" {{ old('isPrivate', $document->isPrivate) ? 'checked' : '' }} />
                                    <label class="form-check-label" for="isPrivate">Mark as Private</label>
                                </div>
                                <small class="form-text text-muted">Private documents are only visible to admins and associated users.</small>
                            </div>
                        </div>

                        <div class="mt-4 mb-0">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block">Update Document</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">
                        <a href="{{ route('admin.documents.show', $document->documentID) }}">Cancel and return to document details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
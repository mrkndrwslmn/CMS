@extends('admin.layouts.app')

@section('title', 'Edit Document')
@section('page-title', 'Edit Document')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Edit Document</h1>
                <nav class="flex items-center space-x-2 text-sm text-neutral-500 mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.documents.index') }}" class="hover:text-primary-500 transition-colors">Documents</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-neutral-700">Edit: {{ $document->fileName }}</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6">
            <div class="flex items-center text-white">
                <div class="bg-white/20 rounded-full p-3 mr-4">
                    <i class="fas fa-edit text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold">Edit Document Details</h2>
                    <p class="text-primary-100 text-sm mt-1">Update document information and file</p>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-8">
            <form action="{{ route('admin.documents.update', $document->documentID) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                @if (session('success'))
                    <div class="bg-success-50 border-l-4 border-success-500 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-success-500 mr-3"></i>
                            <span class="text-success-800">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="bg-error-50 border-l-4 border-error-500 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-error-500 mt-0.5 mr-3"></i>
                            <div class="flex-1">
                                <h3 class="text-error-800 font-semibold mb-2">Please correct the following errors:</h3>
                                <ul class="list-disc list-inside space-y-1 text-error-700 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Document Title (uses fileName since there's no title column) -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Document Title <span class="text-error-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title', $document->fileName) }}" 
                        required
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                        placeholder="Enter document title">
                    <p class="text-xs text-neutral-500 mt-1">This is used for display purposes</p>
                </div>
                
                <!-- Document Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Document Description
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all resize-none"
                        placeholder="Provide a brief description of the document">{{ old('description', $document->description) }}</textarea>
                </div>
                
                <!-- Current File Info -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">Current File</label>
                    <div class="bg-neutral-50 rounded-lg p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-primary-100 p-3 rounded-lg">
                                <i class="fas fa-file-alt text-primary-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">{{ $document->fileName }}</p>
                                <p class="text-xs text-neutral-500">{{ number_format($document->fileSize / 1024, 2) }} KB · {{ $document->fileType }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.documents.download', $document->documentID) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                    </div>
                </div>
                
                <!-- Replace File -->
                <div class="mb-6">
                    <label for="document" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Replace File <span class="text-neutral-400 text-xs font-normal">(Optional)</span>
                    </label>
                    <input 
                        type="file" 
                        id="document" 
                        name="document"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.webp,.zip,.rar,.txt,.csv,.json"
                        class="w-full px-4 py-3 border-2 border-dashed border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                    <p class="text-xs text-neutral-500 mt-1">Leave blank to keep the existing file. Max file size: 10MB</p>
                </div>
                
                <!-- Category (maps to document_type column) -->
                <div class="mb-6">
                    <label for="category" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Category
                    </label>
                    <select 
                        id="category" 
                        name="category"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                        <option value="">Select Category</option>
                        <option value="report" {{ old('category', $document->document_type) == 'report' ? 'selected' : '' }}>Report</option>
                        <option value="contract" {{ old('category', $document->document_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="invoice" {{ old('category', $document->document_type) == 'invoice' ? 'selected' : '' }}>Invoice</option>
                        <option value="proposal" {{ old('category', $document->document_type) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="presentation" {{ old('category', $document->document_type) == 'presentation' ? 'selected' : '' }}>Presentation</option>
                        <option value="other" {{ old('category', $document->document_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <!-- Related Task -->
                <div class="mb-6">
                    <label for="taskID" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Related Task <span class="text-neutral-400 text-xs font-normal">(Optional)</span>
                    </label>
                    <select 
                        id="taskID" 
                        name="taskID"
                        class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                        <option value="">None</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->taskID }}" {{ old('taskID', $document->taskID) == $task->taskID ? 'selected' : '' }}>
                                {{ $task->taskTitle }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Privacy Toggle (uses is_public column, inverted) -->
                <div class="mb-8">
                    <div class="bg-neutral-50 rounded-lg p-4 border border-neutral-200">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input 
                                    type="checkbox" 
                                    id="isPrivate" 
                                    name="isPrivate" 
                                    value="1" 
                                    {{ old('isPrivate', !$document->is_public) ? 'checked' : '' }}
                                    class="w-5 h-5 text-primary-600 border-neutral-300 rounded focus:ring-2 focus:ring-primary-500 transition-all cursor-pointer">
                            </div>
                            <div class="ml-3">
                                <label for="isPrivate" class="font-semibold text-neutral-900 cursor-pointer">
                                    Mark as Private
                                </label>
                                <p class="text-sm text-neutral-600 mt-1">
                                    <i class="fas fa-lock text-neutral-400 mr-1"></i>
                                    Private documents are only visible to admins and associated users
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-neutral-200">
                    <a href="{{ route('admin.documents.show', $document->documentID) }}" 
                       class="px-6 py-3 text-neutral-600 hover:text-neutral-800 font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40">
                        <i class="fas fa-save mr-2"></i>
                        Update Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
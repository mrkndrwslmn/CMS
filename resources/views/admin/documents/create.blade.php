@extends('admin.layouts.app')

@section('title', 'Upload New Document')
@section('page-title', 'Upload New Document')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Upload New Document</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
                    <x-lucide-chevron-right class="w-3 h-3" />
                    <a href="{{ route('admin.documents.index') }}" class="hover:text-accent-500 transition-colors">Documents</a>
                    <x-lucide-chevron-right class="w-3 h-3" />
                    <span class="text-gray-900">Upload New</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Upload Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-accent-500 to-accent-600 px-8 py-6">
            <div class="flex items-center text-white">
                <div class="bg-white/20 rounded-full p-3 mr-4">
                    <x-lucide-cloud-upload class="w-6 h-6" />
                </div>
                <div>
                    <h2 class="text-2xl font-semibold">Document Upload</h2>
                    <p class="text-accent-100 text-sm mt-1">Add a new document to the system</p>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-8">
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <x-lucide-alert-circle class="w-5 h-5 text-red-500 mt-0.5 mr-3" />
                            <div class="flex-1">
                                <h3 class="text-red-800 font-semibold mb-2">Please correct the following errors:</h3>
                                <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Document Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Document Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all"
                        placeholder="Enter document title">
                </div>
                
                <!-- Document Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Document Description
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all resize-none"
                        placeholder="Provide a brief description of the document">{{ old('description') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Optional: Add context or notes about this document</p>
                </div>
                
                <!-- File Upload -->
                <div class="mb-6">
                    <label for="document" class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload File <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="file" 
                            id="document" 
                            name="document" 
                            required
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png"
                            class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-accent-50 file:text-accent-700 hover:file:bg-accent-100 cursor-pointer">
                    </div>
                    <div class="mt-2 flex items-start space-x-2 text-xs text-gray-500">
                        <x-lucide-info class="w-4 h-4 mt-0.5" />
                        <div>
                            <p><strong>Max file size:</strong> 10MB</p>
                            <p><strong>Supported formats:</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG</p>
                        </div>
                    </div>
                    <div id="file-preview" class="mt-3 hidden">
                        <div class="bg-gray-50 rounded-lg p-3 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <x-lucide-file-text class="w-5 h-5 text-accent-500" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900" id="file-name"></p>
                                    <p class="text-xs text-gray-500" id="file-size"></p>
                                </div>
                            </div>
                            <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700 transition-colors">
                                <x-lucide-x-circle class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Category -->
                <div class="mb-6">
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category
                    </label>
                    <select 
                        id="category" 
                        name="category"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                        <option value="">Select Category</option>
                        <option value="report" {{ old('category') == 'report' ? 'selected' : '' }}>Report</option>
                        <option value="contract" {{ old('category') == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="invoice" {{ old('category') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                        <option value="proposal" {{ old('category') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="presentation" {{ old('category') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <!-- Related Task -->
                <div class="mb-6">
                    <label for="taskID" class="block text-sm font-semibold text-gray-700 mb-2">
                        Related Task <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                    </label>
                    <select 
                        id="taskID" 
                        name="taskID"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                        <option value="">None</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->taskID }}" {{ old('taskID') == $task->taskID ? 'selected' : '' }}>
                                {{ $task->taskTitle }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Link this document to a specific task</p>
                </div>
                
                <!-- Privacy Toggle -->
                <div class="mb-8">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input 
                                    type="checkbox" 
                                    id="isPrivate" 
                                    name="isPrivate" 
                                    value="1" 
                                    {{ old('isPrivate') ? 'checked' : '' }}
                                    class="w-5 h-5 text-accent-600 border-gray-300 rounded focus:ring-2 focus:ring-accent-500 transition-all cursor-pointer">
                            </div>
                            <div class="ml-3">
                                <label for="isPrivate" class="font-semibold text-gray-900 cursor-pointer">
                                    Mark as Private
                                </label>
                                <p class="text-sm text-gray-600 mt-1">
                                    <x-lucide-lock class="w-4 h-4 inline text-gray-400 mr-1" />
                                    Private documents are only visible to admins and associated users
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.documents.index') }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <x-lucide-x class="w-4 h-4 mr-2 inline" />
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold rounded-lg hover:from-accent-600 hover:to-accent-700 transition-all duration-200 shadow-lg shadow-accent-500/30 hover:shadow-xl hover:shadow-accent-500/40">
                        <x-lucide-cloud-upload class="w-4 h-4 mr-2 inline" />
                        Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Card -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <x-lucide-lightbulb class="w-5 h-5 text-blue-500 mt-0.5 mr-3" />
            <div>
                <h4 class="text-blue-900 font-semibold mb-1">Quick Tips</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Use descriptive titles to make documents easier to find</li>
                    <li>• Link documents to tasks for better organization</li>
                    <li>• Mark sensitive documents as private to restrict access</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // File preview functionality
    document.getElementById('document').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        
        if (file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });
    
    function clearFile() {
        document.getElementById('document').value = '';
        document.getElementById('file-preview').classList.add('hidden');
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
    // Form validation
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('document');
        const file = fileInput.files[0];
        
        if (file) {
            // Check file size (10MB = 10485760 bytes)
            if (file.size > 10485760) {
                e.preventDefault();
                window.toast.warning('File size exceeds 10MB. Please choose a smaller file.');
                return false;
            }
        }
    });
</script>
@endsection
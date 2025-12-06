@extends('admin.layouts.app')

@section('title', 'Bulk Upload Documents')
@section('page-title', 'Bulk Upload Documents')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bulk Upload Documents</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-500 transition-colors">Dashboard</a>
                    <x-lucide-chevron-right class="w-3 h-3" />
                    <a href="{{ route('admin.documents.index') }}" class="hover:text-primary-500 transition-colors">Documents</a>
                    <x-lucide-chevron-right class="w-3 h-3" />
                    <span class="text-gray-900">Bulk Upload</span>
                </nav>
            </div>
            <a href="{{ route('admin.documents.create') }}" class="flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <x-lucide-file-up class="w-4 h-4 mr-2" />Single Upload
            </a>
        </div>
    </div>

    <!-- Upload Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6">
            <div class="flex items-center text-white">
                <div class="bg-white/20 rounded-full p-3 mr-4">
                    <x-lucide-cloud-upload class="w-6 h-6" />
                </div>
                <div>
                    <h2 class="text-2xl font-semibold">Bulk Document Upload</h2>
                    <p class="text-primary-100 text-sm mt-1">Upload multiple documents at once (up to 20 files)</p>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="p-8">
            <form action="{{ route('admin.documents.bulk-store') }}" method="POST" enctype="multipart/form-data" id="bulkUploadForm">
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
                
                <!-- Drop Zone -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload Files <span class="text-red-500">*</span>
                    </label>
                    <div id="dropZone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-500 transition-all cursor-pointer bg-gray-50 hover:bg-primary-50">
                        <input 
                            type="file" 
                            id="documents" 
                            name="documents[]" 
                            multiple
                            required
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.webp,.zip,.rar,.txt,.csv,.json"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div class="pointer-events-none">
                            <div class="bg-primary-100 w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-4">
                                <x-lucide-cloud-upload class="w-8 h-8 text-primary-500" />
                            </div>
                            <p class="text-lg font-medium text-gray-700 mb-2">Drag and drop files here</p>
                            <p class="text-sm text-gray-500 mb-4">or click to browse</p>
                            <span class="inline-flex items-center px-4 py-2 bg-primary-500 text-white text-sm font-medium rounded-lg">
                                <x-lucide-folder-open class="w-4 h-4 mr-2" />Choose Files
                            </span>
                        </div>
                    </div>
                    <div class="mt-2 flex items-start space-x-2 text-xs text-gray-500">
                        <x-lucide-info class="w-4 h-4 mt-0.5 flex-shrink-0" />
                        <div>
                            <p><strong>Max files:</strong> 20 | <strong>Max size per file:</strong> 10MB</p>
                            <p><strong>Supported:</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, GIF, WEBP, ZIP, RAR, TXT, CSV, JSON</p>
                        </div>
                    </div>
                </div>

                <!-- Selected Files Preview -->
                <div id="filesPreview" class="mb-6 hidden">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">Selected Files (<span id="fileCount">0</span>)</h3>
                        <button type="button" onclick="clearAllFiles()" class="text-sm text-red-600 hover:text-red-700 font-medium inline-flex items-center">
                            <x-lucide-x class="w-4 h-4 mr-1" />Clear All
                        </button>
                    </div>
                    <div id="filesList" class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <!-- Files will be added here dynamically -->
                    </div>
                    <div class="mt-2 flex items-center justify-between text-sm">
                        <span class="text-gray-500">Total size: <span id="totalSize" class="font-medium">0 KB</span></span>
                    </div>
                </div>

                <!-- Common Options -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-lucide-settings class="w-5 h-5 text-gray-400 mr-2" />Common Settings
                        <span class="text-sm font-normal text-gray-500 ml-2">(Applied to all files)</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client -->
                        <div>
                            <label for="client_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Client <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                            </label>
                            <select 
                                id="client_id" 
                                name="client_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="">None</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->fullName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Project -->
                        <div>
                            <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Project <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                            </label>
                            <select 
                                id="project_id" 
                                name="project_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="">None</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Related Task -->
                        <div>
                            <label for="taskID" class="block text-sm font-semibold text-gray-700 mb-2">
                                Related Task <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                            </label>
                            <select 
                                id="taskID" 
                                name="taskID"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="">None</option>
                                @foreach($tasks as $task)
                                    <option value="{{ $task->taskID }}" {{ old('taskID') == $task->taskID ? 'selected' : '' }}>
                                        {{ $task->taskTitle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Document Type -->
                        <div>
                            <label for="document_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                Document Type <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                            </label>
                            <select 
                                id="document_type" 
                                name="document_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="">Auto-detect</option>
                                <option value="report" {{ old('document_type') == 'report' ? 'selected' : '' }}>Report</option>
                                <option value="contract" {{ old('document_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="invoice" {{ old('document_type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                <option value="proposal" {{ old('document_type') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                <option value="presentation" {{ old('document_type') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                <option value="deliverable" {{ old('document_type') == 'deliverable' ? 'selected' : '' }}>Deliverable</option>
                                <option value="other" {{ old('document_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Visibility Toggle -->
                    <div class="mt-6">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input 
                                        type="checkbox" 
                                        id="is_public" 
                                        name="is_public" 
                                        value="1" 
                                        {{ old('is_public', true) ? 'checked' : '' }}
                                        class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-2 focus:ring-primary-500 transition-all cursor-pointer">
                                </div>
                                <div class="ml-3">
                                    <label for="is_public" class="font-semibold text-gray-900 cursor-pointer">
                                        Make Documents Public
                                    </label>
                                    <p class="text-sm text-gray-600 mt-1 flex items-center">
                                        <x-lucide-globe class="w-4 h-4 text-gray-400 mr-1" />
                                        Public documents are visible to the assigned client
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-500 flex items-center">
                        <x-lucide-info class="w-4 h-4 mr-1" />
                        All files will be uploaded to cloud storage
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.documents.index') }}" 
                           class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors inline-flex items-center">
                            <x-lucide-x class="w-4 h-4 mr-2" />Cancel
                        </a>
                        <button 
                            type="submit" 
                            id="uploadButton"
                            disabled
                            class="px-8 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none inline-flex items-center">
                            <x-lucide-cloud-upload class="w-4 h-4 mr-2" />
                            <span id="uploadButtonText">Upload Documents</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Card -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <x-lucide-lightbulb class="w-6 h-6 text-blue-500 mt-0.5 mr-3" />
            <div>
                <h4 class="text-blue-900 font-semibold mb-1">Bulk Upload Tips</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Select multiple files by holding Ctrl (or Cmd on Mac) while clicking</li>
                    <li>• Drag and drop multiple files directly onto the upload area</li>
                    <li>• Common settings will be applied to all uploaded documents</li>
                    <li>• Failed uploads will be reported without affecting successful ones</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Upload Progress Modal -->
<div id="uploadProgressModal" class="fixed inset-0 bg-neutral-900 bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="mb-4">
                <svg class="animate-spin h-10 w-10 text-primary-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Uploading Documents</h3>
            <p class="text-gray-600 mb-4">Please wait while your files are being uploaded...</p>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="uploadProgress" class="bg-primary-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p class="text-sm text-gray-500 mt-2" id="uploadStatus">Preparing...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('documents');
    const filesPreview = document.getElementById('filesPreview');
    const filesList = document.getElementById('filesList');
    const fileCount = document.getElementById('fileCount');
    const totalSize = document.getElementById('totalSize');
    const uploadButton = document.getElementById('uploadButton');
    const uploadButtonText = document.getElementById('uploadButtonText');
    const form = document.getElementById('bulkUploadForm');
    const progressModal = document.getElementById('uploadProgressModal');
    
    let selectedFiles = [];
    
    // Drag and drop handlers
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropZone.classList.add('border-primary-500', 'bg-primary-50');
        dropZone.classList.remove('border-gray-300', 'bg-gray-50');
    }
    
    function unhighlight() {
        dropZone.classList.remove('border-primary-500', 'bg-primary-50');
        dropZone.classList.add('border-gray-300', 'bg-gray-50');
    }
    
    // Handle dropped files
    dropZone.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        handleFiles(files);
    });
    
    // Handle selected files
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });
    
    function handleFiles(files) {
        const maxFiles = 20;
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'zip', 'rar', 'txt', 'csv', 'json'];
        
        selectedFiles = [];
        let errors = [];
        
        Array.from(files).forEach((file, index) => {
            if (selectedFiles.length >= maxFiles) {
                errors.push(`Maximum ${maxFiles} files allowed`);
                return;
            }
            
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(ext)) {
                errors.push(`${file.name}: Invalid file type`);
                return;
            }
            
            if (file.size > maxSize) {
                errors.push(`${file.name}: File exceeds 10MB limit`);
                return;
            }
            
            selectedFiles.push(file);
        });
        
        if (errors.length > 0) {
            window.toast.warning('Some files were not added:\\n' + errors.join(', '));
        }
        
        updatePreview();
    }
    
    function updatePreview() {
        if (selectedFiles.length === 0) {
            filesPreview.classList.add('hidden');
            uploadButton.disabled = true;
            return;
        }
        
        filesPreview.classList.remove('hidden');
        uploadButton.disabled = false;
        fileCount.textContent = selectedFiles.length;
        
        let total = 0;
        filesList.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            total += file.size;
            const iconSvg = getFileIconSvg(file.name);
            
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between bg-white rounded-lg p-3 border border-gray-200';
            fileItem.innerHTML = `
                <div class="flex items-center space-x-3 flex-1 min-w-0">
                    ${iconSvg}
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                        <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                    </div>
                </div>
                <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700 ml-3 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" d="M15 9l-6 6M9 9l6 6" stroke-width="2"/></svg>
                </button>
            `;
            filesList.appendChild(fileItem);
        });
        
        totalSize.textContent = formatFileSize(total);
        uploadButtonText.textContent = `Upload ${selectedFiles.length} Document${selectedFiles.length > 1 ? 's' : ''}`;
        
        // Update the file input with selected files
        updateFileInput();
    }
    
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }
    
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updatePreview();
    };
    
    window.clearAllFiles = function() {
        selectedFiles = [];
        fileInput.value = '';
        updatePreview();
    };
    
    function getFileIconSvg(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        // Lucide-based SVG icons for different file types
        const fileIcon = '<svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6"/></svg>';
        const imageIcon = '<svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2" ry="2" stroke-width="2"/><circle cx="9" cy="9" r="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>';
        const pdfIcon = '<svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6M9 13h6M9 17h6M9 9h1"/></svg>';
        const spreadsheetIcon = '<svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6M8 13h2M14 13h2M8 17h2M14 17h2"/></svg>';
        const presentationIcon = '<svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 3h20M2 3v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V3M12 16v4M8 20h8"/></svg>';
        const archiveIcon = '<svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="20" height="5" x="2" y="3" rx="1" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8M10 12h4"/></svg>';
        const codeIcon = '<svg class="w-5 h-5 text-cyan-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6M10 12l-2 2 2 2M14 12l2 2-2 2"/></svg>';
        const textIcon = '<svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>';
        const wordIcon = '<svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>';
        
        const icons = {
            'pdf': pdfIcon,
            'doc': wordIcon,
            'docx': wordIcon,
            'xls': spreadsheetIcon,
            'xlsx': spreadsheetIcon,
            'ppt': presentationIcon,
            'pptx': presentationIcon,
            'jpg': imageIcon,
            'jpeg': imageIcon,
            'png': imageIcon,
            'gif': imageIcon,
            'webp': imageIcon,
            'zip': archiveIcon,
            'rar': archiveIcon,
            'txt': textIcon,
            'csv': spreadsheetIcon,
            'json': codeIcon,
        };
        return icons[ext] || fileIcon;
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
    // Form submission with progress
    form.addEventListener('submit', function(e) {
        if (selectedFiles.length === 0) {
            e.preventDefault();
            window.toast.warning('Please select at least one file to upload.');
            return;
        }
        
        // Show progress modal
        progressModal.style.display = 'flex';
        document.getElementById('uploadStatus').textContent = `Uploading ${selectedFiles.length} file(s)...`;
        
        // Simulate progress (actual progress would require AJAX upload)
        let progress = 0;
        const progressBar = document.getElementById('uploadProgress');
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
        }, 500);
    });
});
</script>
@endsection

@extends('client.layouts.app')

@section('title', 'Edit Service Request')
@section('site_name', 'Treis Adiutor')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'url' => route('client.dashboard'), 'icon' => 'home'],
            ['label' => 'Service Requests', 'url' => route('client.requests'), 'icon' => 'file-text'],
            ['label' => $request->project_name, 'url' => route('client.requests.show', $request->id), 'icon' => 'file'],
            ['label' => 'Edit', 'icon' => 'pencil']
        ]" class="mb-6" />

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-neutral-50 rounded-xl">
                    <x-lucide-pencil class="w-8 h-8 text-neutral-400" />
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800">Edit Service Request</h1>
                    <p class="text-neutral-500 mt-1">Update your project details before it's reviewed</p>
                </div>
            </div>
        </div>

        <!-- Warning Notice -->
        <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 mb-8">
            <div class="flex items-start gap-3">
                <x-lucide-alert-triangle class="w-5 h-5 text-warning-600 flex-shrink-0 mt-0.5" />
                <div>
                    <p class="text-warning-800 font-medium">Editing is only available for pending requests</p>
                    <p class="text-warning-700 text-sm mt-1">Once your request is reviewed or approved, it can no longer be modified.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form Column -->
            <div class="lg:col-span-2">
                <!-- Form -->
                <form action="{{ route('client.requests.update', $request->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Service Request Details -->
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-neutral-800 mb-6">Project Details</h2>
                        
                        <!-- Project Name -->
                        <div class="mb-6">
                            <label for="project_name" class="block text-sm font-medium text-neutral-700 mb-2">
                                Project Name <span class="text-error-500">*</span>
                            </label>
                            <input type="text" 
                                   name="project_name" 
                                   id="project_name" 
                                   value="{{ old('project_name', $request->project_name) }}"
                                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="e.g., Company Website Redesign"
                                   required>
                            @error('project_name')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Service Type -->
                        <div class="mb-6">
                            <label for="service_type" class="block text-sm font-medium text-neutral-700 mb-2">
                                Service Type <span class="text-error-500">*</span>
                            </label>
                            <select name="service_type" 
                                    id="service_type" 
                                    class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    required>
                                <option value="">Select a service type...</option>
                                <option value="web_development" {{ old('service_type', $request->service_type) == 'web_development' ? 'selected' : '' }}>Web Development</option>
                                <option value="mobile_development" {{ old('service_type', $request->service_type) == 'mobile_development' ? 'selected' : '' }}>Mobile Development</option>
                                <option value="ui_ux_design" {{ old('service_type', $request->service_type) == 'ui_ux_design' ? 'selected' : '' }}>UI/UX Design</option>
                                <option value="graphic_design" {{ old('service_type', $request->service_type) == 'graphic_design' ? 'selected' : '' }}>Graphic Design</option>
                                <option value="data_entry" {{ old('service_type', $request->service_type) == 'data_entry' ? 'selected' : '' }}>Data Entry</option>
                                <option value="content_writing" {{ old('service_type', $request->service_type) == 'content_writing' ? 'selected' : '' }}>Content Writing</option>
                                <option value="video_editing" {{ old('service_type', $request->service_type) == 'video_editing' ? 'selected' : '' }}>Video Editing</option>
                                <option value="virtual_assistance" {{ old('service_type', $request->service_type) == 'virtual_assistance' ? 'selected' : '' }}>Virtual Assistance</option>
                                <option value="other" {{ old('service_type', $request->service_type) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('service_type')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Request Description -->
                        <div class="mb-6">
                            <label for="request_description" class="block text-sm font-medium text-neutral-700 mb-2">
                                Project Description <span class="text-error-500">*</span>
                            </label>
                            <textarea name="request_description" 
                                      id="request_description" 
                                      rows="5"
                                      class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                      placeholder="Describe your project requirements in detail..."
                                      required>{{ old('request_description', $request->request_description) }}</textarea>
                            @error('request_description')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div class="mb-6">
                            <label for="deadline" class="block text-sm font-medium text-neutral-700 mb-2">
                                Preferred Deadline
                            </label>
                            <input type="date" 
                                   name="deadline" 
                                   id="deadline" 
                                   value="{{ old('deadline', $request->deadline?->format('Y-m-d')) }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            @error('deadline')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estimated Budget -->
                        <div class="mb-6">
                            <label for="estimated_budget" class="block text-sm font-medium text-neutral-700 mb-2">
                                Estimated Budget (₱)
                            </label>
                            <input type="number" 
                                   name="estimated_budget" 
                                   id="estimated_budget" 
                                   value="{{ old('estimated_budget', $request->estimated_budget) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="e.g., 50000">
                            @error('estimated_budget')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Preferences -->
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-neutral-800 mb-6">Contact Preferences</h2>
                        
                        <!-- Contact Method -->
                        <div class="mb-6">
                            <label for="contact_method" class="block text-sm font-medium text-neutral-700 mb-2">
                                Preferred Contact Method <span class="text-error-500">*</span>
                            </label>
                            <select name="contact_method" 
                                    id="contact_method" 
                                    class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                    required>
                                <option value="email" {{ old('contact_method', $request->contact_method) == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="messenger" {{ old('contact_method', $request->contact_method) == 'messenger' ? 'selected' : '' }}>Messenger</option>
                                <option value="phone" {{ old('contact_method', $request->contact_method) == 'phone' ? 'selected' : '' }}>Phone</option>
                            </select>
                            @error('contact_method')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Details -->
                        <div class="mb-6">
                            <label for="contact_details" class="block text-sm font-medium text-neutral-700 mb-2">
                                Contact Details <span class="text-error-500">*</span>
                            </label>
                            <input type="text" 
                                   name="contact_details" 
                                   id="contact_details" 
                                   value="{{ old('contact_details', $request->contact_details) }}"
                                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="Email, Messenger username, or phone number"
                                   required>
                            @error('contact_details')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-neutral-800 mb-6">Additional Information</h2>
                        
                        <!-- Expectations -->
                        <div class="mb-6">
                            <label for="expectations" class="block text-sm font-medium text-neutral-700 mb-2">
                                Expectations
                            </label>
                            <textarea name="expectations" 
                                      id="expectations" 
                                      rows="3"
                                      class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                      placeholder="What do you expect from this project?">{{ old('expectations', $request->expectations) }}</textarea>
                            @error('expectations')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-6">
                            <label for="additional_notes" class="block text-sm font-medium text-neutral-700 mb-2">
                                Additional Notes
                            </label>
                            <textarea name="additional_notes" 
                                      id="additional_notes" 
                                      rows="3"
                                      class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                      placeholder="Any other information you'd like to share...">{{ old('additional_notes', $request->additional_notes) }}</textarea>
                            @error('additional_notes')
                                <p class="text-sm text-error-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Existing Attachments -->
                    @if($attachments->count() > 0)
                        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-neutral-800 mb-6">Current Attachments</h2>
                            <div class="space-y-3">
                                @foreach($attachments as $attachment)
                                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <x-lucide-file class="w-5 h-5 text-neutral-400" />
                                            <div>
                                                <p class="text-sm font-medium text-neutral-700">{{ $attachment->file_name }}</p>
                                                <p class="text-xs text-neutral-500">{{ number_format($attachment->file_size / 1024, 2) }} KB</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('client.requests.attachment.download', [$request->id, $attachment->id]) }}" 
                                           class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                            Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Add New Attachments -->
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-neutral-800 mb-6">Add New Attachments</h2>
                        <div class="border-2 border-dashed border-neutral-200 rounded-xl p-6 text-center">
                            <x-lucide-upload-cloud class="w-12 h-12 mx-auto text-neutral-400 mb-4" />
                            <p class="text-neutral-600 mb-2">Drag and drop files here, or click to browse</p>
                            <p class="text-sm text-neutral-500 mb-4">PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP (max 10MB each)</p>
                            <input type="file" 
                                   name="attachments[]" 
                                   id="attachments" 
                                   multiple 
                                   class="hidden" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.zip,.rar">
                            <button type="button" 
                                    onclick="document.getElementById('attachments').click()"
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-neutral-200 rounded-lg text-neutral-700 hover:bg-neutral-50 transition-colors">
                                <x-lucide-folder-open class="w-4 h-4" />
                                Browse Files
                            </button>
                        </div>
                        @error('attachments.*')
                            <p class="text-sm text-error-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-between gap-4">
                        <a href="{{ route('client.requests.show', $request->id) }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 border border-neutral-200 text-neutral-700 font-medium rounded-lg hover:bg-neutral-50 transition-colors">
                            <x-lucide-arrow-left class="w-5 h-5" />
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <x-lucide-save class="w-5 h-5" />
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Request Status</h3>
                    <div class="flex items-center gap-3 p-4 bg-warning-50 rounded-xl mb-6">
                        <x-lucide-clock class="w-6 h-6 text-warning-600" />
                        <div>
                            <p class="font-medium text-warning-800">Pending Review</p>
                            <p class="text-sm text-warning-700">Your request is awaiting review</p>
                        </div>
                    </div>

                    <div class="border-t border-neutral-100 pt-6">
                        <h4 class="text-sm font-medium text-neutral-700 mb-3">Request Info</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Request ID</span>
                                <span class="font-medium text-neutral-800">#{{ $request->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Submitted</span>
                                <span class="font-medium text-neutral-800">{{ $request->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Request Option -->
                    <div class="border-t border-neutral-100 mt-6 pt-6">
                        <h4 class="text-sm font-medium text-neutral-700 mb-3">Danger Zone</h4>
                        <form action="{{ route('client.requests.destroy', $request->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this request? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 border border-error-200 text-error-600 font-medium rounded-lg hover:bg-error-50 transition-colors">
                                <x-lucide-trash-2 class="w-4 h-4" />
                                Delete Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // File input preview
    document.getElementById('attachments').addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            let fileNames = Array.from(files).map(f => f.name).join(', ');
            alert('Selected files: ' + fileNames);
        }
    });
</script>
@endpush

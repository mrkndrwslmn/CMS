@extends('admin.layouts.app')

@section('title', 'Edit Project - ' . $project->title)
@section('page-title', 'Edit Project')

@section('content')
<div class="px-6 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-primary-600 mb-1">Edit Project</h1>
            <p class="text-neutral-500">Update project details and settings</p>
        </div>
        
        <a href="{{ route('admin.projects.show', $project->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Project
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm">
        <form action="{{ route('admin.projects.update', $project->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Project Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">
                                Project Title <span class="text-error-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $project->title) }}"
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('title') border-error-500 @enderror"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Client -->
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-neutral-700 mb-2">
                                Client
                            </label>
                            <input type="text" 
                                   value="{{ $project->client->fullName ?? 'N/A' }}"
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-lg bg-neutral-50"
                                   disabled>
                            <p class="mt-1 text-xs text-neutral-500">Client cannot be changed after project creation</p>
                        </div>

                        <!-- Service Request -->
                        <div>
                            <label for="service_request_id" class="block text-sm font-medium text-neutral-700 mb-2">
                                Service Request
                            </label>
                            <input type="text" 
                                   value="#{{ $project->service_request_id }}"
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-lg bg-neutral-50"
                                   disabled>
                            <p class="mt-1 text-xs text-neutral-500">Linked service request cannot be changed</p>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-neutral-700 mb-2">
                                Priority <span class="text-error-500">*</span>
                            </label>
                            <select id="priority" 
                                    name="priority" 
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('priority') border-error-500 @enderror"
                                    required>
                                <option value="low" {{ old('priority', $project->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $project->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $project->priority) == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority', $project->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deadline -->
                        <div>
                            <label for="deadline" class="block text-sm font-medium text-neutral-700 mb-2">
                                Deadline
                            </label>
                            <input type="date" 
                                   id="deadline" 
                                   name="deadline" 
                                   value="{{ old('deadline', $project->deadline ? $project->deadline->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('deadline') border-error-500 @enderror">
                            @error('deadline')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-neutral-700 mb-2">
                                Description <span class="text-error-500">*</span>
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="5"
                                      class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('description') border-error-500 @enderror"
                                      required>{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Budget Information -->
                <div class="border-t border-neutral-200 pt-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Budget Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Budget Amount -->
                        <div>
                            <label for="budget" class="block text-sm font-medium text-neutral-700 mb-2">
                                Budget Amount (₱) <span class="text-error-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-neutral-500">₱</span>
                                <input type="number" 
                                       id="budget" 
                                       name="budget" 
                                       value="{{ old('budget', $project->budget) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full pl-8 pr-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('budget') border-error-500 @enderror"
                                       required>
                            </div>
                            @error('budget')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Budget Type -->
                        <div>
                            <label for="budget_type" class="block text-sm font-medium text-neutral-700 mb-2">
                                Budget Type <span class="text-error-500">*</span>
                            </label>
                            <select id="budget_type" 
                                    name="budget_type" 
                                    class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('budget_type') border-error-500 @enderror"
                                    required>
                                <option value="fixed" {{ old('budget_type', $project->budget_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                <option value="hourly" {{ old('budget_type', $project->budget_type) == 'hourly' ? 'selected' : '' }}>Hourly</option>
                            </select>
                            @error('budget_type')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Requirements & Skills -->
                <div class="border-t border-neutral-200 pt-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Requirements & Skills</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Requirements -->
                        <div>
                            <label for="requirements" class="block text-sm font-medium text-neutral-700 mb-2">
                                Project Requirements
                            </label>
                            <textarea id="requirements" 
                                      name="requirements" 
                                      rows="8"
                                      class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('requirements') border-error-500 @enderror"
                                      placeholder='Enter requirements as JSON array, e.g., ["Requirement 1", "Requirement 2"]'>{{ old('requirements', is_array($project->requirements) ? json_encode($project->requirements) : $project->requirements) }}</textarea>
                            <p class="mt-1 text-xs text-neutral-500">Enter as JSON array format</p>
                            @error('requirements')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Skills Required -->
                        <div>
                            <label for="skills_required" class="block text-sm font-medium text-neutral-700 mb-2">
                                Skills Required
                            </label>
                            <textarea id="skills_required" 
                                      name="skills_required" 
                                      rows="8"
                                      class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('skills_required') border-error-500 @enderror"
                                      placeholder='Enter skills as JSON array, e.g., ["Skill 1", "Skill 2"]'>{{ old('skills_required', is_array($project->skills_required) ? json_encode($project->skills_required) : $project->skills_required) }}</textarea>
                            <p class="mt-1 text-xs text-neutral-500">Enter as JSON array format</p>
                            @error('skills_required')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-200 flex justify-between items-center rounded-b-lg">
                <a href="{{ route('admin.projects.show', $project->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-neutral-300 hover:bg-neutral-50 text-neutral-700 rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Current Information Card -->
    <div class="mt-6 bg-info-50 border border-info-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-info-600 mt-1 mr-3"></i>
            <div>
                <h4 class="font-semibold text-info-800 mb-2">Important Notes:</h4>
                <ul class="text-sm text-info-700 space-y-1">
                    <li>• Client and Service Request associations cannot be changed after project creation</li>
                    <li>• Requirements and Skills should be entered in JSON array format</li>
                    <li>• Budget changes may require client notification and approval</li>
                    <li>• All fields marked with <span class="text-error-600">*</span> are required</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-format JSON fields on blur
    document.getElementById('requirements')?.addEventListener('blur', function() {
        try {
            if (this.value.trim()) {
                const parsed = JSON.parse(this.value);
                this.value = JSON.stringify(parsed);
            }
        } catch(e) {
            // Keep original value if not valid JSON
        }
    });

    document.getElementById('skills_required')?.addEventListener('blur', function() {
        try {
            if (this.value.trim()) {
                const parsed = JSON.parse(this.value);
                this.value = JSON.stringify(parsed);
            }
        } catch(e) {
            // Keep original value if not valid JSON
        }
    });
</script>
@endpush
@endsection

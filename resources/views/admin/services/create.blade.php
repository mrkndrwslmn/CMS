@extends('admin.layouts.app')

@section('title', 'Create Service')
@section('page-title', 'Create Service')

@section('content')
<div class="px-6 py-8" x-data="serviceForm()">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">Create New Service</h1>
            <p class="text-neutral-500 text-sm">Add a new service to the catalog that clients can browse and request</p>
        </div>
        <a href="{{ route('admin.services.index') }}" 
           class="flex items-center px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg transition-colors">
            <x-lucide-arrow-left class="w-4 h-4 mr-2" />Back to Services
        </a>
    </div>

    <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <h3 class="text-lg font-medium text-neutral-900 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Service Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-1">Service Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                           placeholder="e.g., Website Development">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-neutral-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required
                            class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('category') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-neutral-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea id="description" name="description" rows="4" required
                          class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror"
                          placeholder="Describe what this service includes...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Pricing & Duration -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <h3 class="text-lg font-medium text-neutral-900 mb-4">Pricing & Duration</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Base Price -->
                <div>
                    <label for="base_price" class="block text-sm font-medium text-neutral-700 mb-1">Base Price (₱) <span class="text-red-500">*</span></label>
                    <input type="number" id="base_price" name="base_price" value="{{ old('base_price') }}" required min="0" step="0.01"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('base_price') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('base_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-neutral-500">Starting price shown to clients (displayed as "Starts at")</p>
                </div>
                
                <!-- Duration -->
                <div>
                    <label for="estimated_duration_days" class="block text-sm font-medium text-neutral-700 mb-1">Estimated Duration (Days)</label>
                    <input type="number" id="estimated_duration_days" name="estimated_duration_days" value="{{ old('estimated_duration_days') }}" min="1"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="e.g., 14">
                    <p class="mt-1 text-sm text-neutral-500">Typical completion time for this service</p>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-neutral-900">Features</h3>
                    <p class="text-sm text-neutral-500">List what's included in this service</p>
                </div>
                <button type="button" @click="addFeature()" 
                        class="flex items-center px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-colors">
                    <x-lucide-plus class="w-4 h-4 mr-1" />Add Feature
                </button>
            </div>
            
            <div x-show="features.length === 0" class="text-center py-8 text-neutral-500">
                <x-lucide-list-checks class="w-8 h-8 mx-auto mb-2" />
                <p>No features added yet. Click "Add Feature" to get started.</p>
            </div>
            
            <div class="space-y-3">
                <template x-for="(feature, index) in features" :key="index">
                    <div class="flex items-center gap-3">
                        <x-lucide-check-circle class="w-5 h-5 text-green-500 flex-shrink-0" />
                        <input type="text" x-model="feature.value" :name="`features[${index}]`" 
                               placeholder="e.g., Responsive Design"
                               class="flex-1 px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <button type="button" @click="removeFeature(index)" 
                                class="text-red-600 hover:text-red-700 transition-colors p-2">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Required Skills -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-neutral-900">Required Skills</h3>
                    <p class="text-sm text-neutral-500">Skills needed to complete this service (for team assignment)</p>
                </div>
                <button type="button" @click="addSkill()" 
                        class="flex items-center px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-colors">
                    <x-lucide-plus class="w-4 h-4 mr-1" />Add Skill
                </button>
            </div>
            
            <div x-show="skills.length === 0" class="text-center py-8 text-neutral-500">
                <x-lucide-settings class="w-8 h-8 mx-auto mb-2" />
                <p>No skills added yet. Click "Add Skill" to get started.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <template x-for="(skill, index) in skills" :key="index">
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="skill.value" :name="`required_skills[${index}]`" 
                               placeholder="e.g., PHP Development"
                               class="flex-1 px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <button type="button" @click="removeSkill(index)" 
                                class="text-red-600 hover:text-red-700 transition-colors p-2">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Requirements -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <h3 class="text-lg font-medium text-neutral-900 mb-4">Client Requirements</h3>
            <div>
                <label for="requirements" class="block text-sm font-medium text-neutral-700 mb-1">What clients need to provide</label>
                <textarea id="requirements" name="requirements" rows="4"
                          class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                          placeholder="e.g., Brand guidelines, content for pages, logo files, etc.">{{ old('requirements') }}</textarea>
                <p class="mt-1 text-sm text-neutral-500">This helps clients understand what information/assets they need to prepare.</p>
            </div>
        </div>

        <!-- Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <h3 class="text-lg font-medium text-neutral-900 mb-4">Settings</h3>
            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-neutral-900">
                    Make this service active and visible to clients
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('admin.services.index') }}" 
               class="px-6 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                Create Service
            </button>
        </div>
    </form>
</div>

<script>
function serviceForm() {
    return {
        features: [],
        skills: [],
        
        addFeature() {
            this.features.push({ value: '' });
        },
        
        removeFeature(index) {
            this.features.splice(index, 1);
        },
        
        addSkill() {
            this.skills.push({ value: '' });
        },
        
        removeSkill(index) {
            this.skills.splice(index, 1);
        }
    }
}
</script>
@endsection

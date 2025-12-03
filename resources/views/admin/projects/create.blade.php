@extends('admin.layouts.app')

@section('title', 'Create New Project')
@section('page-title', 'Create Project')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Projects', 'url' => route('admin.projects.index'), 'icon' => 'folder-kanban'],
        ['label' => 'Create Project', 'icon' => 'plus']
    ]" class="mb-6" />

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800 mb-1">Create New Project</h1>
            <p class="text-neutral-500">Create a project from a paid service request</p>
        </div>
    </div>

    @if($serviceRequests->isEmpty())
    <!-- No Service Requests Available -->
    <x-ui.card class="p-8 text-center">
        <div class="w-16 h-16 bg-warning-50 rounded-xl flex items-center justify-center mx-auto mb-4">
            <x-lucide-alert-triangle class="w-8 h-8 text-warning-600" />
        </div>
        <h3 class="text-lg font-semibold text-neutral-800 mb-2">No Paid Service Requests Available</h3>
        <p class="text-neutral-500 mb-6">There are no paid service requests that haven't been converted to projects yet.</p>
        <x-ui.button variant="primary" href="{{ route('admin.requests.index') }}">
            <x-lucide-clipboard-list class="w-4 h-4" />
            View Service Requests
        </x-ui.button>
    </x-ui.card>
    @else
    <!-- Create Form -->
    <x-ui.card>
        <form action="{{ route('admin.projects.store') }}" method="POST">
            @csrf

            <div class="p-6 space-y-6">
                <!-- Service Request Selection -->
                <div>
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Select Service Request</h3>
                    
                    <div>
                        <label for="service_request_id" class="block text-sm font-medium text-neutral-700 mb-2">
                            Paid Service Request <span class="text-error-500">*</span>
                        </label>
                        <select id="service_request_id" 
                                name="service_request_id" 
                                class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors @error('service_request_id') border-error-500 @enderror"
                                required
                                onchange="populateServiceRequestDetails()">
                            <option value="">-- Select a Service Request --</option>
                            @foreach($serviceRequests as $request)
                                <option value="{{ $request->id }}" 
                                        data-client="{{ $request->client->fullName ?? 'Unknown' }}"
                                        data-budget="{{ $request->approved_budget ?? 0 }}"
                                        data-service-type="{{ $request->service_type ?? '' }}"
                                        data-description="{{ $request->description ?? '' }}"
                                        {{ old('service_request_id') == $request->id ? 'selected' : '' }}>
                                    #{{ $request->id }} - {{ $request->client->fullName ?? 'Unknown Client' }} 
                                    (₱{{ number_format($request->approved_budget ?? 0, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('service_request_id')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selected Service Request Details -->
                    <div id="serviceRequestDetails" class="mt-4 p-4 bg-neutral-50 rounded-xl hidden">
                        <h4 class="text-sm font-medium text-neutral-700 mb-2">Service Request Details</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-neutral-500">Client:</span>
                                <p id="srClient" class="font-medium text-neutral-800">-</p>
                            </div>
                            <div>
                                <span class="text-neutral-500">Service Type:</span>
                                <p id="srServiceType" class="font-medium text-neutral-800">-</p>
                            </div>
                            <div>
                                <span class="text-neutral-500">Approved Budget:</span>
                                <p id="srBudget" class="font-medium text-success-600">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="border-t border-neutral-100 pt-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Project Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Project Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">
                                Project Title <span class="text-error-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors @error('title') border-error-500 @enderror"
                                   placeholder="Enter project title"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-neutral-700 mb-2">
                                Priority <span class="text-error-500">*</span>
                            </label>
                            <select id="priority" 
                                    name="priority" 
                                    class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors @error('priority') border-error-500 @enderror"
                                    required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
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
                                   value="{{ old('deadline') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors @error('deadline') border-error-500 @enderror">
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
                                      class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors @error('description') border-error-500 @enderror"
                                      placeholder="Enter project description and details"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Budget Information -->
                <div class="border-t border-neutral-100 pt-6">
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
                                       value="{{ old('budget') }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full pl-8 pr-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors @error('budget') border-error-500 @enderror"
                                       placeholder="0.00"
                                       required>
                            </div>
                            <p class="mt-1 text-xs text-neutral-500">This will be auto-filled from the service request's approved budget</p>
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
                                    class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors @error('budget_type') border-error-500 @enderror"
                                    required>
                                <option value="fixed" {{ old('budget_type', 'fixed') == 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                                <option value="hourly" {{ old('budget_type') == 'hourly' ? 'selected' : '' }}>Hourly Rate</option>
                            </select>
                            @error('budget_type')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Requirements & Skills (Optional) -->
                <div class="border-t border-neutral-100 pt-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Requirements & Skills <span class="text-sm font-normal text-neutral-500">(Optional)</span></h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Requirements -->
                        <div>
                            <label for="requirements" class="block text-sm font-medium text-neutral-700 mb-2">
                                Project Requirements
                            </label>
                            <textarea id="requirements" 
                                      name="requirements" 
                                      rows="6"
                                      class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors @error('requirements') border-error-500 @enderror"
                                      placeholder='["Requirement 1", "Requirement 2", "Requirement 3"]'>{{ old('requirements') }}</textarea>
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
                                      rows="6"
                                      class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors @error('skills_required') border-error-500 @enderror"
                                      placeholder='["PHP", "Laravel", "JavaScript"]'>{{ old('skills_required') }}</textarea>
                            <p class="mt-1 text-xs text-neutral-500">Enter as JSON array format</p>
                            @error('skills_required')
                                <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-100 flex justify-between items-center rounded-b-2xl">
                <x-ui.button variant="secondary" href="{{ route('admin.projects.index') }}">
                    <x-lucide-x class="w-4 h-4" />
                    Cancel
                </x-ui.button>
                
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-plus class="w-4 h-4" />
                    Create Project
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    <!-- Info Card -->
    <div class="mt-6 bg-info-50 border border-info-200 rounded-2xl p-4">
        <div class="flex items-start">
            <x-lucide-info class="w-5 h-5 text-info-600 mt-0.5 mr-3 flex-shrink-0" />
            <div>
                <h4 class="font-semibold text-info-800 mb-2">Creating a Project</h4>
                <ul class="text-sm text-info-700 space-y-1">
                    <li class="flex items-center gap-2"><x-lucide-circle class="w-1.5 h-1.5" /> Projects can only be created from paid service requests</li>
                    <li class="flex items-center gap-2"><x-lucide-circle class="w-1.5 h-1.5" /> The project budget will be populated from the approved service request budget</li>
                    <li class="flex items-center gap-2"><x-lucide-circle class="w-1.5 h-1.5" /> Once created, the project will be in "Active" status and ready for task assignment</li>
                    <li class="flex items-center gap-2"><x-lucide-circle class="w-1.5 h-1.5" /> You can assign Adiutors to the project after creation</li>
                    <li class="flex items-center gap-2"><x-lucide-circle class="w-1.5 h-1.5" /> All fields marked with <span class="text-error-600">*</span> are required</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function populateServiceRequestDetails() {
        const select = document.getElementById('service_request_id');
        const detailsDiv = document.getElementById('serviceRequestDetails');
        const budgetInput = document.getElementById('budget');
        const descriptionInput = document.getElementById('description');
        
        if (select.value) {
            const selectedOption = select.options[select.selectedIndex];
            const client = selectedOption.dataset.client || '-';
            const budget = selectedOption.dataset.budget || 0;
            const serviceType = selectedOption.dataset.serviceType || '-';
            const description = selectedOption.dataset.description || '';
            
            document.getElementById('srClient').textContent = client;
            document.getElementById('srServiceType').textContent = serviceType || 'General';
            document.getElementById('srBudget').textContent = '₱' + parseFloat(budget).toLocaleString('en-PH', { minimumFractionDigits: 2 });
            
            detailsDiv.classList.remove('hidden');
            
            // Auto-populate budget if empty
            if (!budgetInput.value) {
                budgetInput.value = budget;
            }
            
            // Auto-populate description if empty
            if (!descriptionInput.value && description) {
                descriptionInput.value = description;
            }
        } else {
            detailsDiv.classList.add('hidden');
        }
    }

    // Run on page load if service request is already selected
    document.addEventListener('DOMContentLoaded', function() {
        populateServiceRequestDetails();
    });

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

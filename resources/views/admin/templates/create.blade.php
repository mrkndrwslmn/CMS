@extends('admin.layouts.app')

@section('title', 'Create Project Template')
@section('page-title', 'Create Project Template')

@section('content')
<div class="p-6 lg:p-8" x-data="templateForm()">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Templates', 'route' => 'admin.templates.index', 'icon' => 'file-text'],
        ['label' => 'Create Template', 'icon' => 'plus'],
    ]" />

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-800">Create Project Template</h1>
        <p class="text-sm text-neutral-500 mt-1">Create a reusable template for faster project setup</p>
    </div>

    <form action="{{ route('admin.templates.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <h3 class="text-lg font-medium text-neutral-700 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Template Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-1">Template Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all @error('name') border-error-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-neutral-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required
                            class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all @error('category') border-error-500 @enderror">
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
                <textarea id="description" name="description" rows="3" required
                          class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all @error('description') border-error-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Budget & Timeline -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <h3 class="text-lg font-medium text-neutral-700 mb-4">Budget & Timeline</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Budget Type -->
                <div>
                    <label for="budget_type" class="block text-sm font-medium text-neutral-700 mb-1">Budget Type <span class="text-red-500">*</span></label>
                    <select id="budget_type" name="budget_type" required
                            class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="fixed" {{ old('budget_type') === 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                        <option value="hourly" {{ old('budget_type') === 'hourly' ? 'selected' : '' }}>Hourly Rate</option>
                    </select>
                </div>
                
                <!-- Payment Type -->
                <div>
                    <label for="payment_type" class="block text-sm font-medium text-neutral-700 mb-1">Payment Type <span class="text-red-500">*</span></label>
                    <select id="payment_type" name="payment_type" required
                            class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="milestone_payment" {{ old('payment_type') === 'milestone_payment' ? 'selected' : '' }}>Milestone Payment</option>
                        <option value="full_payment" {{ old('payment_type') === 'full_payment' ? 'selected' : '' }}>Full Payment</option>
                        <option value="downpayment" {{ old('payment_type') === 'downpayment' ? 'selected' : '' }}>Down Payment</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Budget Range -->
                <div>
                    <label for="estimated_budget_min" class="block text-sm font-medium text-neutral-700 mb-1">Minimum Budget ($)</label>
                    <input type="number" id="estimated_budget_min" name="estimated_budget_min" value="{{ old('estimated_budget_min') }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                
                <div>
                    <label for="estimated_budget_max" class="block text-sm font-medium text-neutral-700 mb-1">Maximum Budget ($)</label>
                    <input type="number" id="estimated_budget_max" name="estimated_budget_max" value="{{ old('estimated_budget_max') }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                
                <!-- Duration -->
                <div>
                    <label for="estimated_duration_days" class="block text-sm font-medium text-neutral-700 mb-1">Duration (Days)</label>
                    <input type="number" id="estimated_duration_days" name="estimated_duration_days" value="{{ old('estimated_duration_days') }}" min="1"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
        </div>

        <!-- Default Tasks -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-neutral-700">Default Tasks</h3>
                <button type="button" @click="addTask()" 
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                    <x-lucide-plus class="w-4 h-4" />
                    Add Task
                </button>
            </div>
            
            <div x-show="tasks.length === 0" class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-3 bg-neutral-50 rounded-full flex items-center justify-center">
                    <x-lucide-list-todo class="w-8 h-8 text-neutral-300" />
                </div>
                <p class="text-sm text-neutral-500">No tasks added yet. Click "Add Task" to get started.</p>
            </div>
            
            <div class="space-y-4">
                <template x-for="(task, index) in tasks" :key="index">
                    <div class="border border-neutral-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-neutral-700" x-text="`Task ${index + 1}`"></h4>
                            <button type="button" @click="removeTask(index)" 
                                    class="text-error-600 hover:text-error-700 transition-colors">
                                <x-lucide-trash-2 class="w-4 h-4" />
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Task Title <span class="text-red-500">*</span></label>
                                <input type="text" x-model="task.title" :name="`default_tasks[${index}][title]`" required
                                       class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Priority <span class="text-red-500">*</span></label>
                                <select x-model="task.priority" :name="`default_tasks[${index}][priority]`" required
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Task Description <span class="text-red-500">*</span></label>
                            <textarea x-model="task.description" :name="`default_tasks[${index}][description]`" rows="2" required
                                      class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Estimated Hours <span class="text-red-500">*</span></label>
                            <input type="number" x-model="task.estimated_hours" :name="`default_tasks[${index}][estimated_hours]`" min="0.5" step="0.5" required
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Skills Required -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-neutral-700">Required Skills</h3>
                <button type="button" @click="addSkill()" 
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                    <x-lucide-plus class="w-4 h-4" />
                    Add Skill
                </button>
            </div>
            
            <div x-show="skills.length === 0" class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-3 bg-neutral-50 rounded-full flex items-center justify-center">
                    <x-lucide-settings class="w-8 h-8 text-neutral-300" />
                </div>
                <p class="text-sm text-neutral-500">No skills added yet. Click "Add Skill" to get started.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="(skill, index) in skills" :key="index">
                    <div class="flex items-center space-x-2">
                        <input type="text" x-model="skill.value" :name="`skills_required[${index}]`" placeholder="Enter skill"
                               class="flex-1 px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <button type="button" @click="removeSkill(index)" 
                                class="text-red-600 hover:text-red-700 transition-colors">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Milestones Template -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-neutral-700">Milestone Template</h3>
                <button type="button" @click="addMilestone()" 
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                    <x-lucide-plus class="w-4 h-4" />
                    Add Milestone
                </button>
            </div>
            
            <div x-show="milestones.length === 0" class="text-center py-8">
                <div class="w-16 h-16 mx-auto mb-3 bg-neutral-50 rounded-full flex items-center justify-center">
                    <x-lucide-flag class="w-8 h-8 text-neutral-300" />
                </div>
                <p class="text-sm text-neutral-500">No milestones added yet. Click "Add Milestone" to get started.</p>
            </div>
            
            <div class="space-y-4">
                <template x-for="(milestone, index) in milestones" :key="index">
                    <div class="border border-neutral-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-neutral-900" x-text="`Milestone ${index + 1}`"></h4>
                            <button type="button" @click="removeMilestone(index)" 
                                    class="text-red-600 hover:text-red-700 transition-colors">
                                <x-lucide-trash-2 class="w-4 h-4" />
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Phase Name <span class="text-red-500">*</span></label>
                                <input type="text" x-model="milestone.phase_name" :name="`milestones_template[${index}][phase_name]`" required
                                       class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-1">Percentage (%) <span class="text-red-500">*</span></label>
                                <input type="number" x-model="milestone.percentage" :name="`milestones_template[${index}][percentage]`" min="1" max="100" required
                                       class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Description <span class="text-red-500">*</span></label>
                            <textarea x-model="milestone.description" :name="`milestones_template[${index}][description]`" rows="2" required
                                      class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>
                    </div>
                </template>
            </div>
            
            <div x-show="milestones.length > 0" class="mt-4 p-3 bg-primary-50 rounded-lg">
                <div class="flex items-center gap-2 text-sm text-primary-700">
                    <x-lucide-info class="w-4 h-4" />
                    <span>Total percentage: <span x-text="milestones.reduce((sum, milestone) => sum + parseInt(milestone.percentage || 0), 0)"></span>% (should equal 100%)</span>
                </div>
            </div>
        </div>

        <!-- Requirements Template -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <h3 class="text-lg font-medium text-neutral-700 mb-4">Requirements Template</h3>
            <div>
                <label for="requirements_template" class="block text-sm font-medium text-neutral-700 mb-1">Default Requirements Text</label>
                <textarea id="requirements_template" name="requirements_template" rows="4" 
                          placeholder="Enter template text that will help gather project requirements from clients..."
                          class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('requirements_template') }}</textarea>
                <p class="mt-1 text-sm text-neutral-500">This text will be shown to clients when they request this type of project to help them provide the necessary information.</p>
            </div>
        </div>

        <!-- Settings -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <h3 class="text-lg font-medium text-neutral-700 mb-4">Settings</h3>
            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-neutral-900">
                    Make this template active and available for use
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.templates.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 text-neutral-600 text-sm font-medium rounded-lg hover:bg-neutral-100 transition-all">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                <x-lucide-check class="w-4 h-4" />
                Create Template
            </button>
        </div>
    </form>
</div>

<script>
function templateForm() {
    return {
        tasks: [],
        skills: [],
        milestones: [],
        
        addTask() {
            this.tasks.push({
                title: '',
                description: '',
                priority: 'medium',
                estimated_hours: 1
            });
        },
        
        removeTask(index) {
            this.tasks.splice(index, 1);
        },
        
        addSkill() {
            this.skills.push({ value: '' });
        },
        
        removeSkill(index) {
            this.skills.splice(index, 1);
        },
        
        addMilestone() {
            this.milestones.push({
                phase_name: '',
                percentage: 25,
                description: ''
            });
        },
        
        removeMilestone(index) {
            this.milestones.splice(index, 1);
        }
    }
}
</script>
@endsection
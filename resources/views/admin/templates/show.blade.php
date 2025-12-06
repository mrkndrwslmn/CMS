@extends('admin.layouts.app')

@section('title', 'View Project Template')
@section('page-title', 'View Project Template')

@section('content')
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">{{ $template->name }}</h1>
            <p class="text-neutral-500 text-sm">{{ $template->description }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.templates.edit', $template) }}" 
               class="flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                <x-lucide-pencil class="w-4 h-4 mr-2" />Edit Template
            </a>
            <a href="{{ route('admin.templates.index') }}" 
               class="flex items-center px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg transition-colors">
                <x-lucide-arrow-left class="w-4 h-4 mr-2" />Back to Templates
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Template Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4">Template Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Category</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $template->category === 'web-development' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $template->category === 'mobile-development' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $template->category === 'design' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $template->category === 'marketing' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ !in_array($template->category, ['web-development', 'mobile-development', 'design', 'marketing']) ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucwords(str_replace('-', ' ', $template->category)) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                        @if($template->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Budget Type</label>
                        <p class="text-sm text-neutral-900">{{ ucfirst(str_replace('_', ' ', $template->budget_type)) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Payment Type</label>
                        <p class="text-sm text-neutral-900">{{ ucfirst(str_replace('_', ' ', $template->payment_type)) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Duration</label>
                        <p class="text-sm text-neutral-900">
                            @if($template->estimated_duration_days)
                                {{ $template->estimated_duration_days }} days
                            @else
                                <span class="text-neutral-400">Not specified</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                @if($template->estimated_budget_min || $template->estimated_budget_max)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Budget Range</label>
                        <p class="text-sm text-neutral-900">
                            @if($template->estimated_budget_min && $template->estimated_budget_max)
                                ₱{{ number_format($template->estimated_budget_min) }} - ₱{{ number_format($template->estimated_budget_max) }}
                            @elseif($template->estimated_budget_min)
                                From ₱{{ number_format($template->estimated_budget_min) }}
                            @else
                                Up to ₱{{ number_format($template->estimated_budget_max) }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            <!-- Default Tasks -->
            @if($template->default_tasks && count($template->default_tasks) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">Default Tasks ({{ count($template->default_tasks) }})</h3>
                    
                    <div class="space-y-4">
                        @foreach($template->default_tasks as $index => $task)
                            <div class="border border-neutral-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-neutral-900">{{ $task['title'] }}</h4>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            {{ $task['priority'] === 'high' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $task['priority'] === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $task['priority'] === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($task['priority']) }} Priority
                                        </span>
                                        <span class="text-xs text-neutral-500">{{ $task['estimated_hours'] }}h</span>
                                    </div>
                                </div>
                                <p class="text-sm text-neutral-600">{{ $task['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center text-sm text-blue-600">
                            <x-lucide-clock class="w-4 h-4 mr-2" />
                            <span>Total estimated hours: {{ collect($template->default_tasks)->sum('estimated_hours') }} hours</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Milestones Template -->
            @if($template->milestones_template && count($template->milestones_template) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">Milestone Template ({{ count($template->milestones_template) }})</h3>
                    
                    <div class="space-y-4">
                        @foreach($template->milestones_template as $milestone)
                            <div class="flex items-center justify-between p-4 border border-neutral-200 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium text-neutral-900">{{ $milestone['phase_name'] }}</h4>
                                    <p class="text-sm text-neutral-600 mt-1">{{ $milestone['description'] }}</p>
                                </div>
                                <div class="ml-4 text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                        {{ $milestone['percentage'] }}%
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Requirements Template -->
            @if($template->requirements_template)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">Requirements Template</h3>
                    <div class="prose max-w-none">
                        <p class="text-sm text-neutral-700 whitespace-pre-line">{{ $template->requirements_template }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4">Quick Stats</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-600">Created</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $template->created_at->format('M j, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-600">Last Updated</span>
                        <span class="text-sm font-medium text-neutral-900">{{ $template->updated_at->format('M j, Y') }}</span>
                    </div>
                    
                    @if($template->creator)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600">Created By</span>
                            <span class="text-sm font-medium text-neutral-900">{{ $template->creator->fullName }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Required Skills -->
            @if($template->skills_required && count($template->skills_required) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">Required Skills</h3>
                    
                    <div class="flex flex-wrap gap-2">
                        @foreach($template->skills_required as $skill)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4">Actions</h3>
                
                <div class="space-y-3">
                    <form action="{{ route('admin.templates.toggle', $template) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 {{ $template->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition-colors">
                            @if($template->is_active)
                                <x-lucide-pause class="w-4 h-4 mr-2" />
                            @else
                                <x-lucide-play class="w-4 h-4 mr-2" />
                            @endif
                            {{ $template->is_active ? 'Deactivate' : 'Activate' }} Template
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.templates.duplicate', $template) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors">
                            <x-lucide-copy class="w-4 h-4 mr-2" />Duplicate Template
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" 
                          onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete Template', 'Are you sure you want to delete this template? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                            <x-lucide-trash-2 class="w-4 h-4 mr-2" />Delete Template
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
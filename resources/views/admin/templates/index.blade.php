@extends('admin.layouts.app')

@section('title', 'Project Templates Management')
@section('page-title', 'Project Templates Management')

@section('content')
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">Project Templates Management</h1>
            <p class="text-neutral-500 text-sm">Create and manage reusable project templates for faster project setup</p>
        </div>
        <a href="{{ route('admin.templates.create') }}" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Create Template
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
        <!-- Total Templates -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Total Templates</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $templates->total() }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Active Templates -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Active Templates</p>
                    <p class="text-2xl font-bold text-green-600">{{ $templates->where('is_active', true)->count() }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Inactive Templates -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Inactive Templates</p>
                    <p class="text-2xl font-bold text-red-600">{{ $templates->where('is_active', false)->count() }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-lg">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Categories -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Categories</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $categories->count() }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg">
                    <i class="fas fa-tags text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.templates.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Search templates..." 
                       class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-neutral-700 mb-1">Category</label>
                <select id="category" name="category" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ ucwords(str_replace('-', ' ', $category)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.templates.index') }}" class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Templates List -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200">
        @if($templates->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Template</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Budget Range</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($templates as $template)
                            <tr class="hover:bg-neutral-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                                <i class="fas fa-file-alt text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-neutral-900">{{ $template->name }}</div>
                                            <div class="text-sm text-neutral-500">{{ Str::limit($template->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $template->category === 'web-development' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $template->category === 'mobile-development' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $template->category === 'design' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $template->category === 'marketing' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ !in_array($template->category, ['web-development', 'mobile-development', 'design', 'marketing']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucwords(str_replace('-', ' ', $template->category)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                    @if($template->estimated_budget_min && $template->estimated_budget_max)
                                        ${{ number_format($template->estimated_budget_min) }} - ${{ number_format($template->estimated_budget_max) }}
                                    @elseif($template->estimated_budget_min)
                                        From ${{ number_format($template->estimated_budget_min) }}
                                    @else
                                        <span class="text-neutral-400">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                    @if($template->estimated_duration_days)
                                        {{ $template->estimated_duration_days }} days
                                    @else
                                        <span class="text-neutral-400">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.templates.show', $template) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.templates.edit', $template) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.templates.toggle', $template) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="{{ $template->is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }} transition-colors" 
                                                    title="{{ $template->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $template->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.templates.duplicate', $template) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-purple-600 hover:text-purple-900 transition-colors" 
                                                    title="Duplicate">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this template?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 transition-colors" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-neutral-200">
                <x-ui.pagination :paginator="$templates->appends(request()->query())" />
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 bg-neutral-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-neutral-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-neutral-900 mb-2">No templates found</h3>
                <p class="text-neutral-500 mb-6">
                    @if(request()->hasAny(['search', 'category', 'status']))
                        No templates match your current filters. Try adjusting your search criteria.
                    @else
                        Get started by creating your first project template.
                    @endif
                </p>
                <a href="{{ route('admin.templates.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Template
                </a>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" 
         class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button @click="show = false" class="ml-4 text-green-200 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif
@endsection
@extends('admin.layouts.app')

@section('title', 'Project Templates Management')
@section('page-title', 'Project Templates Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Templates', 'icon' => 'file-text'],
    ]" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Project Templates</h1>
            <p class="text-sm text-neutral-500 mt-1">Create and manage reusable project templates for faster project setup</p>
        </div>
        <a href="{{ route('admin.templates.create') }}" class="mt-4 sm:mt-0 inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
            <x-lucide-plus class="w-4 h-4" />
            Create Template
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Templates -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Templates</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $templates->total() }}</p>
                </div>
                <div class="p-3 bg-neutral-50 rounded-xl">
                    <x-lucide-file-text class="w-5 h-5 text-neutral-400" />
                </div>
            </div>
        </div>
        
        <!-- Active Templates -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Active Templates</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $templates->where('is_active', true)->count() }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>
        
        <!-- Inactive Templates -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Inactive Templates</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $templates->where('is_active', false)->count() }}</p>
                </div>
                <div class="p-3 bg-error-50 rounded-xl">
                    <x-lucide-x-circle class="w-5 h-5 text-error-500" />
                </div>
            </div>
        </div>
        
        <!-- Categories -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Categories</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $categories->count() }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-tags class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.templates.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Search templates..." 
                       class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
            </div>
            
            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-neutral-700 mb-1">Category</label>
                <select id="category" name="category" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
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
                <select id="status" name="status" class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                    <x-lucide-search class="w-4 h-4" />
                    Filter
                </button>
                <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-lg border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                    <x-lucide-x class="w-4 h-4" />
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Templates List -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        @if($templates->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Template</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Budget Range</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($templates as $template)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-lg bg-primary-50 flex items-center justify-center">
                                                <x-lucide-file-text class="w-5 h-5 text-primary-500" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-neutral-700">{{ $template->name }}</div>
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
                                        ₱{{ number_format($template->estimated_budget_min) }} - ₱{{ number_format($template->estimated_budget_max) }}
                                    @elseif($template->estimated_budget_min)
                                        From ₱{{ number_format($template->estimated_budget_min) }}
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
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                            <x-lucide-check-circle class="w-3 h-3" />
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                            <x-lucide-circle-dashed class="w-3 h-3" />
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.templates.show', $template) }}" 
                                           class="text-primary-600 hover:text-primary-700 transition-colors" title="View">
                                            <x-lucide-eye class="w-4 h-4" />
                                        </a>
                                        <a href="{{ route('admin.templates.edit', $template) }}" 
                                           class="text-primary-600 hover:text-primary-700 transition-colors" title="Edit">
                                            <x-lucide-pencil class="w-4 h-4" />
                                        </a>
                                        <form action="{{ route('admin.templates.toggle', $template) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="{{ $template->is_active ? 'text-warning-600 hover:text-warning-700' : 'text-success-600 hover:text-success-700' }} transition-colors" 
                                                    title="{{ $template->is_active ? 'Deactivate' : 'Activate' }}">
                                                @if($template->is_active)
                                                    <x-lucide-pause class="w-4 h-4" />
                                                @else
                                                    <x-lucide-play class="w-4 h-4" />
                                                @endif
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.templates.duplicate', $template) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-neutral-600 hover:text-neutral-700 transition-colors" 
                                                    title="Duplicate">
                                                <x-lucide-copy class="w-4 h-4" />
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" class="inline" 
                                              onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete Template', 'Are you sure you want to delete this template?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-error-600 hover:text-error-700 transition-colors" 
                                                    title="Delete">
                                                <x-lucide-trash-2 class="w-4 h-4" />
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
            <div class="px-6 py-4 border-t border-neutral-100">
                <x-ui.pagination :paginator="$templates->appends(request()->query())" />
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 bg-neutral-50 rounded-full flex items-center justify-center">
                    <x-lucide-file-text class="w-10 h-10 text-neutral-300" />
                </div>
                <h3 class="text-lg font-medium text-neutral-800 mb-2">No templates found</h3>
                <p class="text-sm text-neutral-500 mb-6">
                    @if(request()->hasAny(['search', 'category', 'status']))
                        No templates match your current filters. Try adjusting your search criteria.
                    @else
                        Get started by creating your first project template.
                    @endif
                </p>
                <a href="{{ route('admin.templates.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                    <x-lucide-plus class="w-4 h-4" />
                    Create Template
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
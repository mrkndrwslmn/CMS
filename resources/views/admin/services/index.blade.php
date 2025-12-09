@extends('admin.layouts.app')

@section('title', 'Services Management')
@section('page-title', 'Services Management')

@section('content')
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">Services Management</h1>
            <p class="text-neutral-500 text-sm">Manage the services catalog that clients can browse and request</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
            <x-lucide-plus class="w-4 h-4 mr-2" />Add New Service
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
        <!-- Total Services -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Total Services</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $stats['total'] }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                    <x-lucide-briefcase class="w-5 h-5 text-blue-600" />
                </div>
            </div>
        </div>
        
        <!-- Active Services -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Active Services</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                    <x-lucide-check-circle class="w-5 h-5 text-green-600" />
                </div>
            </div>
        </div>
        
        <!-- Inactive Services -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Inactive Services</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['inactive'] }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-lg">
                    <x-lucide-x-circle class="w-5 h-5 text-red-600" />
                </div>
            </div>
        </div>
        
        <!-- Categories -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Categories</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['categories'] }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg">
                    <x-lucide-tags class="w-5 h-5 text-purple-600" />
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.services.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Search services..." 
                       class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-neutral-700 mb-1">Category</label>
                <select id="category" name="category" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            
            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                    <x-lucide-search class="w-4 h-4 inline mr-1" />Filter
                </button>
                <a href="{{ route('admin.services.index') }}" class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg transition-colors">
                    <x-lucide-x class="w-4 h-4" />
                </a>
            </div>
        </form>
    </div>

    <!-- Services Table -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 overflow-hidden">
        @if($services->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Service
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Base Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Duration
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($services as $service)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                            <i class="fas {{ $service->icon ?? 'fa-cogs' }} text-primary-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-neutral-900">{{ $service->name }}</div>
                                            <div class="text-sm text-neutral-500 line-clamp-1 max-w-xs">{{ Str::limit($service->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $service->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                    ₱{{ number_format($service->base_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    @if($service->estimated_duration_days)
                                        {{ $service->estimated_duration_days }} days
                                    @else
                                        <span class="text-neutral-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($service->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <x-lucide-check class="w-3 h-3 mr-1" />Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <x-lucide-x class="w-3 h-3 mr-1" />Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.services.show', $service) }}" 
                                           class="text-neutral-600 hover:text-primary-600 transition-colors" title="View">
                                            <x-lucide-eye class="w-4 h-4" />
                                        </a>
                                        <a href="{{ route('admin.services.edit', $service) }}" 
                                           class="text-neutral-600 hover:text-blue-600 transition-colors" title="Edit">
                                            <x-lucide-pencil class="w-4 h-4" />
                                        </a>
                                        <form action="{{ route('admin.services.toggle-status', $service) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-neutral-600 hover:text-yellow-600 transition-colors" 
                                                    title="{{ $service->is_active ? 'Deactivate' : 'Activate' }}">
                                                @if($service->is_active)
                                                    <x-lucide-toggle-right class="w-4 h-4" />
                                                @else
                                                    <x-lucide-toggle-left class="w-4 h-4" />
                                                @endif
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline"
                                              onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete Service', 'Are you sure you want to delete this service? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-neutral-600 hover:text-red-600 transition-colors" title="Delete">
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
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $services->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <x-lucide-briefcase class="w-12 h-12 text-neutral-300 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-neutral-900 mb-2">No services found</h3>
                <p class="text-neutral-500 mb-4">
                    @if(request()->hasAny(['search', 'category', 'status']))
                        Try adjusting your filters or search query.
                    @else
                        Get started by creating your first service.
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <x-lucide-plus class="w-4 h-4 mr-2" />Add New Service
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

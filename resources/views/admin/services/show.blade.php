@extends('admin.layouts.app')

@section('title', $service->name)
@section('page-title', 'Service Details')

@section('content')
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-14 w-14 bg-primary-100 rounded-xl flex items-center justify-center mr-4">
                <i class="fas {{ $service->icon ?? 'fa-cogs' }} text-2xl text-primary-600"></i>
            </div>
            <div>
                <h1 class="text-2xl font-semibold text-neutral-900">{{ $service->name }}</h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $service->category }}
                    </span>
                    @if($service->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <x-lucide-check class="w-3 h-3 mr-1" />Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <x-lucide-x class="w-3 h-3 mr-1" />Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <a href="{{ route('admin.services.index') }}" 
               class="flex items-center px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg transition-colors">
                <x-lucide-arrow-left class="w-4 h-4 mr-2" />Back
            </a>
            <a href="{{ route('admin.services.edit', $service) }}" 
               class="flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                <x-lucide-pencil class="w-4 h-4 mr-2" />Edit
            </a>
            <form action="{{ route('admin.services.toggle-status', $service) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="flex items-center px-4 py-2 {{ $service->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition-colors">
                    @if($service->is_active)
                        <x-lucide-toggle-right class="w-4 h-4 mr-2" />Deactivate
                    @else
                        <x-lucide-toggle-left class="w-4 h-4 mr-2" />Activate
                    @endif
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description Card -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4 flex items-center gap-2">
                    <x-lucide-file-text class="w-5 h-5 text-primary-500" />
                    Description
                </h3>
                <div class="prose max-w-none text-neutral-700">
                    {{ $service->description }}
                </div>
            </div>

            <!-- Features Card -->
            @if($service->features && count($service->features) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4 flex items-center gap-2">
                        <x-lucide-list-checks class="w-5 h-5 text-green-500" />
                        Included Features
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($service->features as $feature)
                            <div class="flex items-center gap-2 p-3 bg-green-50 rounded-lg">
                                <x-lucide-check-circle class="w-5 h-5 text-green-600 flex-shrink-0" />
                                <span class="text-neutral-700">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Required Skills Card -->
            @if($service->required_skills && count($service->required_skills) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4 flex items-center gap-2">
                        <x-lucide-settings class="w-5 h-5 text-blue-500" />
                        Required Skills
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($service->required_skills as $skill)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Client Requirements Card -->
            @if($service->requirements)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4 flex items-center gap-2">
                        <x-lucide-clipboard-list class="w-5 h-5 text-orange-500" />
                        Client Requirements
                    </h3>
                    <div class="prose max-w-none text-neutral-700 bg-orange-50 p-4 rounded-lg border border-orange-100">
                        {{ $service->requirements }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Stats & Info -->
        <div class="space-y-6">
            <!-- Pricing Card -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4 flex items-center gap-2">
                    <x-lucide-banknote class="w-5 h-5 text-green-500" />
                    Pricing
                </h3>
                <div class="text-center py-4">
                    <p class="text-sm text-neutral-500 mb-1">Starts at</p>
                    <p class="text-3xl font-bold text-green-600">₱{{ number_format($service->base_price, 2) }}</p>
                </div>
            </div>

            <!-- Duration Card -->
            @if($service->estimated_duration_days)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4 flex items-center gap-2">
                        <x-lucide-clock class="w-5 h-5 text-blue-500" />
                        Estimated Duration
                    </h3>
                    <div class="text-center py-4">
                        <p class="text-3xl font-bold text-blue-600">{{ $service->estimated_duration_days }}</p>
                        <p class="text-sm text-neutral-500">days</p>
                    </div>
                </div>
            @endif

            <!-- Request Statistics Card -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4 flex items-center gap-2">
                    <x-lucide-bar-chart-3 class="w-5 h-5 text-purple-500" />
                    Request Statistics
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-600">Total Requests</span>
                        <span class="text-lg font-semibold text-neutral-900">{{ $requestStats['total'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-600">Pending</span>
                        <span class="text-lg font-semibold text-yellow-600">{{ $requestStats['pending'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-600">Approved</span>
                        <span class="text-lg font-semibold text-green-600">{{ $requestStats['approved'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Metadata Card -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4 flex items-center gap-2">
                    <x-lucide-info class="w-5 h-5 text-neutral-500" />
                    Information
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-500">Created</span>
                        <span class="text-neutral-900">{{ $service->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-500">Last Updated</span>
                        <span class="text-neutral-900">{{ $service->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-500">Service ID</span>
                        <span class="text-neutral-900">#{{ $service->id }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.services.edit', $service) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                        <x-lucide-pencil class="w-4 h-4" />Edit Service
                    </a>
                    <a href="{{ url('/services') }}" target="_blank"
                       class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                        <x-lucide-external-link class="w-4 h-4" />View Public Page
                    </a>
                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                          onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete Service', 'Are you sure you want to delete this service? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors">
                            <x-lucide-trash-2 class="w-4 h-4" />Delete Service
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

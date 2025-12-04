@extends('admin.layouts.app')

@section('title', 'Revision Requests')
@section('page-title', 'Revision Requests')

@section('content')
<div class="px-6 py-8" x-data="{ showFilters: false }">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Home', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Revisions', 'icon' => 'rotate-ccw']
    ]" class="mb-4" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <x-ui.page-header
            title="Revision Requests"
            subtitle="Manage and review client revision requests"
        />
        <div class="mt-4 sm:mt-0 flex gap-3">
            <x-ui.button 
                @click="showFilters = !showFilters" 
                variant="secondary"
                class="inline-flex items-center gap-2"
            >
                <x-lucide-filter class="w-4 h-4" />
                <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
            </x-ui.button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
        <x-ui.card class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Requests</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $statusCounts['all'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-rotate-ccw class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-warning-600 mt-1">{{ $statusCounts['pending'] }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Approved</p>
                    <p class="text-2xl font-semibold text-info-600 mt-1">{{ $statusCounts['approved'] }}</p>
                </div>
                <div class="p-3 bg-info-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-info-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Completed</p>
                    <p class="text-2xl font-semibold text-success-600 mt-1">{{ $statusCounts['completed'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-check class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Rejected</p>
                    <p class="text-2xl font-semibold text-error-600 mt-1">{{ $statusCounts['rejected'] }}</p>
                </div>
                <div class="p-3 bg-error-50 rounded-xl">
                    <x-lucide-x-circle class="w-5 h-5 text-error-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Filters Panel -->
    <div x-show="showFilters" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="mb-6">
        <x-ui.card>
            <div class="px-6 py-4 border-b border-neutral-200">
                <div class="flex items-center gap-2">
                    <x-lucide-filter class="w-5 h-5 text-primary-500" />
                    <h2 class="text-lg font-semibold text-neutral-800">Search & Filters</h2>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.revisions.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                        <x-ui.input 
                            type="text" 
                            name="search" 
                            :value="request('search')"
                            placeholder="Search by reason, client, or project..."
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                        <x-ui.select name="status">
                            <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </x-ui.select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Source Type</label>
                        <x-ui.select name="source_type">
                            <option value="all" {{ request('source_type', 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="project" {{ request('source_type') == 'project' ? 'selected' : '' }}>Project</option>
                            <option value="task" {{ request('source_type') == 'task' ? 'selected' : '' }}>Task</option>
                            <option value="document" {{ request('source_type') == 'document' ? 'selected' : '' }}>Document</option>
                        </x-ui.select>
                    </div>

                    <div class="flex items-end gap-2">
                        <x-ui.button type="submit" variant="primary" class="flex-1 inline-flex items-center justify-center gap-2">
                            <x-lucide-search class="w-4 h-4" />
                            Apply Filters
                        </x-ui.button>
                        <x-ui.button href="{{ route('admin.revisions.index') }}" variant="secondary" class="inline-flex items-center gap-2">
                            <x-lucide-refresh-cw class="w-4 h-4" />
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </x-ui.card>
    </div>

    <!-- Revisions Table -->
    <x-ui.card class="overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200">
            <div class="flex items-center gap-2">
                <x-lucide-list class="w-5 h-5 text-primary-500" />
                <h2 class="text-lg font-semibold text-neutral-800">Revision Requests</h2>
            </div>
        </div>
        @if($revisions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Source
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Type & Priority
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Reason
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Requested Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-100">
                        @foreach($revisions as $revision)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-neutral-900">#{{ $revision->id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-neutral-900">
                                            {{ $revision->requestedBy->fullName ?? 'N/A' }}
                                        </div>
                                        @if($revision->requestedBy)
                                        <div class="text-sm text-neutral-500">
                                            {{ $revision->requestedBy->email }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($revision->project)
                                    <a href="{{ route('admin.projects.show', $revision->project->id) }}" 
                                       class="text-sm text-primary-600 hover:text-primary-700 font-medium inline-flex items-center gap-1">
                                        <x-lucide-folder-kanban class="w-4 h-4" />
                                        {{ Str::limit($revision->project->projectName, 30) }}
                                    </a>
                                @elseif($revision->task)
                                    <div class="text-sm text-neutral-900 inline-flex items-center gap-1">
                                        <x-lucide-list-todo class="w-4 h-4" />
                                        {{ Str::limit($revision->task->taskTitle, 30) }}
                                    </div>
                                @elseif($revision->document)
                                    <div class="text-sm text-neutral-900 inline-flex items-center gap-1">
                                        <x-lucide-file-text class="w-4 h-4" />
                                        {{ Str::limit($revision->document->fileName, 30) }}
                                    </div>
                                @else
                                    <span class="text-sm text-neutral-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    @if($revision->source_type === 'project')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-800">
                                            <x-lucide-folder-kanban class="w-3 h-3 mr-1" />
                                            Project
                                        </span>
                                    @elseif($revision->source_type === 'task')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <x-lucide-list-todo class="w-3 h-3 mr-1" />
                                            Task
                                        </span>
                                    @elseif($revision->source_type === 'document')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                            <x-lucide-file-text class="w-3 h-3 mr-1" />
                                            Document
                                        </span>
                                    @endif
                                    
                                    @if($revision->priority === 'urgent')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-800">
                                            <x-lucide-alert-triangle class="w-3 h-3 mr-1" />
                                            Urgent
                                        </span>
                                    @elseif($revision->priority === 'high')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <x-lucide-arrow-up class="w-3 h-3 mr-1" />
                                            High
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                            Normal
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-neutral-900 max-w-xs">
                                    {{ Str::limit($revision->reason, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($revision->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                        <x-lucide-clock class="w-3 h-3 mr-1" />
                                        Pending
                                    </span>
                                @elseif($revision->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-800">
                                        <x-lucide-check-circle class="w-3 h-3 mr-1" />
                                        Approved
                                    </span>
                                @elseif($revision->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-800">
                                        <x-lucide-x-circle class="w-3 h-3 mr-1" />
                                        Rejected
                                    </span>
                                @elseif($revision->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                        <x-lucide-check-check class="w-3 h-3 mr-1" />
                                        Completed
                                    </span>
                                @elseif($revision->status === 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                        <x-lucide-ban class="w-3 h-3 mr-1" />
                                        Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-900">
                                    {{ $revision->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-neutral-500">
                                    {{ $revision->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.revisions.show', $revision->id) }}" 
                                   class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700">
                                    <x-lucide-eye class="w-4 h-4" />
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-neutral-100">
                {{ $revisions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-lucide-rotate-ccw class="w-8 h-8 text-neutral-400" />
                </div>
                <h3 class="text-lg font-medium text-neutral-900 mb-2">No revision requests found</h3>
                <p class="text-neutral-500 mb-4">
                    @if(request()->has('search') || request()->has('status') || request()->has('source_type'))
                        No revisions match your current filters. Try adjusting your search criteria.
                    @else
                        Revision requests from clients will appear here.
                    @endif
                </p>
                @if(request()->has('search') || request()->has('status') || request()->has('source_type'))
                    <x-ui.button href="{{ route('admin.revisions.index') }}" variant="primary" class="inline-flex items-center gap-2">
                        <x-lucide-refresh-cw class="w-4 h-4" />
                        Clear Filters
                    </x-ui.button>
                @endif
            </div>
        @endif
    </x-ui.card>
</div>
@endsection

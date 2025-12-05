@extends('admin.layouts.app')

@section('title', 'Projects Management')
@section('page-title', 'Projects Management')

@section('content')
<div class="container-fluid px-4" x-data="{ 
    showFilters: false,
    selectedProjects: [],
    selectAll: false,
    toggleSelectAll() {
        if (this.selectAll) {
            this.selectedProjects = Array.from(document.querySelectorAll('input[name=\'project_ids[]\']')).map(el => el.value);
        } else {
            this.selectedProjects = [];
        }
    }
}">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Projects', 'icon' => 'folder-kanban']
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Projects Management</h1>
            <p class="text-neutral-500 mt-1">Manage and track all client projects</p>
        </div>
        <div class="flex space-x-3">
            <x-ui.button variant="secondary" @click="showFilters = !showFilters">
                <x-lucide-filter class="w-4 h-4" />
                <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
            </x-ui.button>
            <x-ui.button variant="primary" href="{{ route('admin.projects.create') }}">
                <x-lucide-plus class="w-4 h-4" />
                New Project
            </x-ui.button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <x-ui.card class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Total Projects</p>
                    <p class="text-2xl font-bold text-neutral-800">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-folder-kanban class="w-5 h-5 text-primary-600" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Active</p>
                    <p class="text-2xl font-bold text-primary-600">{{ $stats['active'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-play class="w-5 h-5 text-primary-600" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-500">In Progress</p>
                    <p class="text-2xl font-bold text-info-600">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="p-3 bg-info-50 rounded-xl">
                    <x-lucide-loader class="w-5 h-5 text-info-600" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-500">In Review</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['review'] }}</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl">
                    <x-lucide-eye class="w-5 h-5 text-purple-600" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Completed</p>
                    <p class="text-2xl font-bold text-success-600">{{ $stats['completed'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-600" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Cancelled</p>
                    <p class="text-2xl font-bold text-error-600">{{ $stats['cancelled'] }}</p>
                </div>
                <div class="p-3 bg-error-50 rounded-xl">
                    <x-lucide-x-circle class="w-5 h-5 text-error-600" />
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
        <x-ui.card class="p-6">
            <form method="GET" action="{{ route('admin.projects.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Search</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Project title, description, or client..." 
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Client</label>
                    <select name="client_id" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->fullName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Priority</label>
                    <select name="priority" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Date From</label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Date To</label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>

                <div class="flex items-end space-x-2">
                    <x-ui.button type="submit" variant="primary" class="flex-1">
                        <x-lucide-search class="w-4 h-4" />
                        Apply Filters
                    </x-ui.button>
                    <x-ui.button type="button" variant="secondary" href="{{ route('admin.projects.index') }}">
                        <x-lucide-rotate-ccw class="w-4 h-4" />
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>

    <!-- Projects Table -->
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-100">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" 
                                   x-model="selectAll" 
                                   @change="toggleSelectAll()"
                                   class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Project
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Priority
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Budget
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Team
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Dates
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse($projects as $project)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" 
                                       name="project_ids[]" 
                                       value="{{ $project->id }}"
                                       x-model="selectedProjects"
                                       class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center mr-3">
                                        <x-lucide-folder-kanban class="w-5 h-5 text-primary-600" />
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.projects.show', $project->id) }}" 
                                           class="text-sm font-medium text-neutral-800 hover:text-primary-600 transition-colors">
                                            {{ $project->title }}
                                        </a>
                                        <p class="text-xs text-neutral-500 mt-1">
                                            ID: #{{ $project->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="font-medium text-neutral-800">{{ $project->client->fullName }}</p>
                                    <p class="text-neutral-500">{{ $project->client->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'active' => ['class' => 'bg-primary-50 text-primary-700', 'text' => 'Active'],
                                        'in_progress' => ['class' => 'bg-info-50 text-info-700', 'text' => 'In Progress'],
                                        'review' => ['class' => 'bg-purple-50 text-purple-700', 'text' => 'Review'],
                                        'completed' => ['class' => 'bg-success-50 text-success-700', 'text' => 'Completed'],
                                        'cancelled' => ['class' => 'bg-error-50 text-error-700', 'text' => 'Cancelled'],
                                        'on_hold' => ['class' => 'bg-neutral-100 text-neutral-700', 'text' => 'On Hold'],
                                    ];
                                    $config = $statusConfig[$project->status] ?? ['class' => 'bg-neutral-100 text-neutral-700', 'text' => ucfirst($project->status)];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $config['class'] }}">
                                    {{ $config['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($project->priority)
                                    @php
                                        $priorityConfig = [
                                            'low' => ['class' => 'bg-neutral-100 text-neutral-700', 'icon' => 'arrow-down'],
                                            'medium' => ['class' => 'bg-warning-50 text-warning-700', 'icon' => 'minus'],
                                            'high' => ['class' => 'bg-orange-50 text-orange-700', 'icon' => 'arrow-up'],
                                            'urgent' => ['class' => 'bg-error-50 text-error-700', 'icon' => 'alert-triangle'],
                                        ];
                                        $pConfig = $priorityConfig[$project->priority] ?? ['class' => 'bg-neutral-100 text-neutral-700', 'icon' => 'minus'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium {{ $pConfig['class'] }}">
                                        <x-dynamic-component :component="'lucide-' . $pConfig['icon']" class="w-3 h-3" />
                                        {{ ucfirst($project->priority) }}
                                    </span>
                                @else
                                    <span class="text-neutral-400 text-sm">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    @if($project->serviceRequest && ($project->serviceRequest->coupon_discount_amount > 0 || $project->serviceRequest->loyalty_discount_amount > 0))
                                        <p class="text-xs text-neutral-400 line-through">₱{{ number_format($project->serviceRequest->getOriginalBudget(), 2) }}</p>
                                        <p class="font-semibold text-neutral-800">₱{{ number_format($project->budget ?? 0, 2) }}</p>
                                        <p class="text-xs text-success-600 flex items-center gap-1">
                                            @if($project->serviceRequest->coupon_discount_amount > 0)
                                                <x-lucide-ticket class="w-3 h-3" />
                                            @endif
                                            @if($project->serviceRequest->loyalty_discount_amount > 0)
                                                <x-lucide-award class="w-3 h-3" />
                                            @endif
                                            -₱{{ number_format(($project->serviceRequest->coupon_discount_amount ?? 0) + ($project->serviceRequest->loyalty_discount_amount ?? 0), 2) }}
                                        </p>
                                    @else
                                        <p class="font-semibold text-neutral-800">₱{{ number_format($project->budget ?? 0, 2) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex -space-x-2">
                                    @if($project->adiutors->count() > 0)
                                        @foreach($project->adiutors->take(3) as $adiutor)
                                            <div class="h-8 w-8 rounded-full bg-primary-600 border-2 border-white flex items-center justify-center text-white text-xs font-medium"
                                                 title="{{ $adiutor->fullName }}">
                                                {{ substr($adiutor->fullName, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($project->adiutors->count() > 3)
                                            <div class="h-8 w-8 rounded-full bg-neutral-200 border-2 border-white flex items-center justify-center text-neutral-600 text-xs font-medium">
                                                +{{ $project->adiutors->count() - 3 }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-neutral-400 text-sm">No team</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-neutral-500">
                                    <p><strong>Created:</strong> {{ $project->created_at->format('M d, Y') }}</p>
                                    @if($project->deadline)
                                        <p class="mt-1"><strong>Deadline:</strong> {{ $project->deadline->format('M d, Y') }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.projects.show', $project->id) }}" 
                                       class="text-neutral-400 hover:text-primary-600 transition-colors"
                                       title="View Details">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </a>
                                    <a href="{{ route('admin.projects.edit', $project->id) }}" 
                                       class="text-neutral-400 hover:text-primary-600 transition-colors"
                                       title="Edit Project">
                                        <x-lucide-pencil class="w-4 h-4" />
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-neutral-400 hover:text-error-600 transition-colors"
                                                title="Delete Project">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-neutral-100 rounded-xl flex items-center justify-center mb-4">
                                        <x-lucide-folder-kanban class="w-8 h-8 text-neutral-400" />
                                    </div>
                                    <h3 class="text-lg font-medium text-neutral-800 mb-2">No Projects Found</h3>
                                    <p class="text-neutral-500 mb-4">Get started by creating your first project.</p>
                                    <x-ui.button variant="primary" href="{{ route('admin.projects.create') }}">
                                        <x-lucide-plus class="w-4 h-4" />
                                        Create Project
                                    </x-ui.button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="px-6 py-4 border-t border-neutral-100">
                <x-ui.pagination :paginator="$projects" />
            </div>
        @endif
    </x-ui.card>

    <!-- Bulk Actions Bar (shown when projects are selected) -->
    <div x-show="selectedProjects.length > 0" 
         x-cloak
         class="fixed bottom-0 left-0 right-0 bg-primary-600 text-white px-6 py-4 shadow-lg md:ml-64 z-20">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <x-lucide-check-square class="w-5 h-5" />
                <span x-text="selectedProjects.length"></span> project(s) selected
            </div>
            <div class="flex space-x-3">
                <form action="{{ route('admin.projects.bulk-action') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="action" value="mark_active">
                    <input type="hidden" name="project_ids" :value="JSON.stringify(selectedProjects)">
                    <button type="submit" class="inline-flex items-center gap-2 bg-white text-primary-600 px-4 py-2 rounded-lg hover:bg-neutral-100 transition-colors text-sm font-medium">
                        <x-lucide-play class="w-4 h-4" />
                        Mark as Active
                    </button>
                </form>
                <form action="{{ route('admin.projects.bulk-action') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="action" value="mark_completed">
                    <input type="hidden" name="project_ids" :value="JSON.stringify(selectedProjects)">
                    <button type="submit" class="inline-flex items-center gap-2 bg-success-500 text-white px-4 py-2 rounded-lg hover:bg-success-600 transition-colors text-sm font-medium">
                        <x-lucide-check-circle class="w-4 h-4" />
                        Mark as Completed
                    </button>
                </form>
                <button @click="selectedProjects = []; selectAll = false" 
                        class="inline-flex items-center gap-2 bg-error-500 text-white px-4 py-2 rounded-lg hover:bg-error-600 transition-colors text-sm font-medium">
                    <x-lucide-x class="w-4 h-4" />
                    Clear Selection
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

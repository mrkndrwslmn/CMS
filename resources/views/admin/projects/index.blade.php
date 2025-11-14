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
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Projects Management</h1>
            <p class="text-gray-600 mt-1">Manage and track all client projects</p>
        </div>
        <div class="flex space-x-3">
            <button @click="showFilters = !showFilters" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-filter mr-2"></i>
                <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
            </button>
            <a href="{{ route('admin.projects.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i>
                New Project
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Projects</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-project-diagram text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-play text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">In Progress</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-spinner text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">In Review</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['review'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-eye text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cancelled</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div x-show="showFilters" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.projects.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Project title, description, or client..." 
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                <select name="client_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->fullName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" 
                       name="date_to" 
                       value="{{ request('date_to') }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-search mr-2"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.projects.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" 
                                   x-model="selectAll" 
                                   @change="toggleSelectAll()"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Project
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Priority
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Budget
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Team
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dates
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" 
                                       name="project_ids[]" 
                                       value="{{ $project->id }}"
                                       x-model="selectedProjects"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-project-diagram text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.projects.show', $project->id) }}" 
                                           class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $project->title }}
                                        </a>
                                        <p class="text-xs text-gray-500 mt-1">
                                            ID: #{{ $project->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="font-medium text-gray-900">{{ $project->client->fullName }}</p>
                                    <p class="text-gray-500">{{ $project->client->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'active' => ['color' => 'indigo', 'text' => 'Active'],
                                        'in_progress' => ['color' => 'blue', 'text' => 'In Progress'],
                                        'review' => ['color' => 'purple', 'text' => 'Review'],
                                        'completed' => ['color' => 'green', 'text' => 'Completed'],
                                        'cancelled' => ['color' => 'red', 'text' => 'Cancelled'],
                                        'on_hold' => ['color' => 'gray', 'text' => 'On Hold'],
                                    ];
                                    $config = $statusConfig[$project->status] ?? ['color' => 'gray', 'text' => ucfirst($project->status)];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800">
                                    {{ $config['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($project->priority)
                                    @php
                                        $priorityConfig = [
                                            'low' => ['color' => 'gray', 'icon' => 'arrow-down'],
                                            'medium' => ['color' => 'yellow', 'icon' => 'minus'],
                                            'high' => ['color' => 'orange', 'icon' => 'arrow-up'],
                                            'urgent' => ['color' => 'red', 'icon' => 'exclamation'],
                                        ];
                                        $pConfig = $priorityConfig[$project->priority] ?? ['color' => 'gray', 'icon' => 'minus'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $pConfig['color'] }}-100 text-{{ $pConfig['color'] }}-800">
                                        <i class="fas fa-{{ $pConfig['icon'] }} mr-1"></i>
                                        {{ ucfirst($project->priority) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    @if($project->serviceRequest && ($project->serviceRequest->coupon_discount_amount > 0 || $project->serviceRequest->loyalty_discount_amount > 0))
                                        <p class="text-xs text-gray-400 line-through">₱{{ number_format($project->serviceRequest->getOriginalBudget(), 2) }}</p>
                                        <p class="font-semibold text-gray-900">₱{{ number_format($project->budget ?? 0, 2) }}</p>
                                        <p class="text-xs text-success-600">
                                            @if($project->serviceRequest->coupon_discount_amount > 0)
                                                <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                                </svg>
                                            @endif
                                            @if($project->serviceRequest->loyalty_discount_amount > 0)
                                                <i class="fas fa-medal text-xs"></i>
                                            @endif
                                            -₱{{ number_format(($project->serviceRequest->coupon_discount_amount ?? 0) + ($project->serviceRequest->loyalty_discount_amount ?? 0), 2) }}
                                        </p>
                                    @else
                                        <p class="font-semibold text-gray-900">₱{{ number_format($project->budget ?? 0, 2) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex -space-x-2">
                                    @if($project->adiutors->count() > 0)
                                        @foreach($project->adiutors->take(3) as $adiutor)
                                            <div class="h-8 w-8 rounded-full bg-indigo-600 border-2 border-white flex items-center justify-center text-white text-xs font-medium"
                                                 title="{{ $adiutor->fullName }}">
                                                {{ substr($adiutor->fullName, 0, 1) }}
                                            </div>
                                        @endforeach
                                        @if($project->adiutors->count() > 3)
                                            <div class="h-8 w-8 rounded-full bg-gray-300 border-2 border-white flex items-center justify-center text-gray-600 text-xs font-medium">
                                                +{{ $project->adiutors->count() - 3 }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">No team</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    <p><strong>Created:</strong> {{ $project->created_at->format('M d, Y') }}</p>
                                    @if($project->deadline)
                                        <p class="mt-1"><strong>Deadline:</strong> {{ $project->deadline->format('M d, Y') }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.projects.show', $project->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.projects.edit', $project->id) }}" 
                                       class="text-blue-600 hover:text-blue-900"
                                       title="Edit Project">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this project?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                title="Delete Project">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-project-diagram text-gray-400 text-5xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Projects Found</h3>
                                    <p class="text-gray-500 mb-4">Get started by creating your first project.</p>
                                    <a href="{{ route('admin.projects.create') }}" 
                                       class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                                        <i class="fas fa-plus mr-2"></i>
                                        Create Project
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $projects->links() }}
            </div>
        @endif
    </div>

    <!-- Bulk Actions Bar (shown when projects are selected) -->
    <div x-show="selectedProjects.length > 0" 
         x-cloak
         class="fixed bottom-0 left-0 right-0 bg-indigo-600 text-white px-6 py-4 shadow-lg md:ml-64 z-20">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div>
                <span x-text="selectedProjects.length"></span> project(s) selected
            </div>
            <div class="flex space-x-3">
                <form action="{{ route('admin.projects.bulk-action') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="action" value="mark_active">
                    <input type="hidden" name="project_ids" :value="JSON.stringify(selectedProjects)">
                    <button type="submit" class="bg-white text-indigo-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        Mark as Active
                    </button>
                </form>
                <form action="{{ route('admin.projects.bulk-action') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="action" value="mark_completed">
                    <input type="hidden" name="project_ids" :value="JSON.stringify(selectedProjects)">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                        Mark as Completed
                    </button>
                </form>
                <button @click="selectedProjects = []; selectAll = false" 
                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    Clear Selection
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

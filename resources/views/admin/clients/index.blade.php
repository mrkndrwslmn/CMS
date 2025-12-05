@extends('admin.layouts.app')

@section('title', 'Client Management')
@section('page-title', 'Client Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Clients', 'icon' => 'users'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Client Management" 
            description="Manage all clients and their projects"
        />
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.clients.export', request()->only(['status', 'date_from', 'date_to'])) }}">
                <x-ui.button variant="secondary" icon="download">
                    Export CSV
                </x-ui.button>
            </a>
            <a href="{{ route('admin.clients.create') }}">
                <x-ui.button icon="plus">
                    Add New Client
                </x-ui.button>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <x-ui.stat-card 
            label="Total Clients" 
            :value="$stats['total_clients']" 
            icon="users"
        />
        <x-ui.stat-card 
            label="Active Clients" 
            :value="$stats['active_clients']" 
            icon="user-check"
            iconBg="success"
        />
        <x-ui.stat-card 
            label="Total Requests" 
            :value="$stats['total_requests']" 
            icon="file-text"
            iconBg="primary"
        />
        <x-ui.stat-card 
            label="Active Projects" 
            :value="$stats['active_projects']" 
            icon="folder-kanban"
            iconBg="warning"
        />
        <a href="{{ route('admin.clients.archived') }}" class="block">
            <x-ui.stat-card 
                label="Archived Clients" 
                :value="$stats['archived_clients'] ?? 0" 
                icon="archive"
                iconBg="neutral"
            />
        </a>
    </div>

    <!-- Filters and Search -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <div class="flex items-center gap-2 mb-4">
                <x-lucide-filter class="w-5 h-5 text-neutral-400" />
                <h3 class="text-lg font-medium text-neutral-700">Filters</h3>
            </div>
            <form method="GET" action="{{ route('admin.clients.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
                    <div>
                        <label for="search" class="block text-sm font-medium text-neutral-700 mb-1.5">Search</label>
                        <x-ui.input 
                            type="text" 
                            name="search" 
                            id="search" 
                            icon="search"
                            placeholder="Name, email, phone..." 
                            :value="request('search')"
                        />
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-700 mb-1.5">Status</label>
                        <x-ui.select name="status" id="status">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                        </x-ui.select>
                    </div>
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-neutral-700 mb-1.5">Joined From</label>
                        <x-ui.input 
                            type="date" 
                            name="date_from" 
                            id="date_from" 
                            :value="request('date_from')"
                        />
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-neutral-700 mb-1.5">Joined To</label>
                        <x-ui.input 
                            type="date" 
                            name="date_to" 
                            id="date_to" 
                            :value="request('date_to')"
                        />
                    </div>
                    <div>
                        <label for="sort" class="block text-sm font-medium text-neutral-700 mb-1.5">Sort By</label>
                        <x-ui.select name="sort" id="sort">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                            <option value="fullName" {{ request('sort') == 'fullName' ? 'selected' : '' }}>Name</option>
                            <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                        </x-ui.select>
                    </div>
                    <div class="flex items-end gap-3">
                        <x-ui.button type="submit" icon="search" class="flex-1">
                            Filter
                        </x-ui.button>
                        <a href="{{ route('admin.clients.index') }}" class="flex-1">
                            <x-ui.button type="button" variant="secondary" icon="x" class="w-full">
                                Clear
                            </x-ui.button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Clients Table -->
    <x-ui.card class="mb-6" x-data="{ 
        selectedClients: [],
        selectAll: false,
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedClients = [...document.querySelectorAll('.client-checkbox')].map(el => el.value);
            } else {
                this.selectedClients = [];
            }
        }
    }">
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-lucide-users class="w-5 h-5 text-neutral-400" />
                    <h3 class="text-lg font-medium text-neutral-700">Clients List</h3>
                </div>
                <!-- Bulk Actions -->
                <div x-show="selectedClients.length > 0" x-cloak class="flex items-center gap-3">
                    <span class="text-sm text-neutral-600" x-text="selectedClients.length + ' selected'"></span>
                    <form method="POST" action="{{ route('admin.clients.bulk-action') }}" class="flex items-center gap-2">
                        @csrf
                        <template x-for="id in selectedClients" :key="id">
                            <input type="hidden" name="client_ids[]" :value="id">
                        </template>
                        <select name="action" required class="text-sm border-neutral-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Select Action</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="ban">Ban</option>
                            <option value="archive">Archive</option>
                        </select>
                        <x-ui.button type="submit" size="sm" onclick="return window.Alerts.confirmForm(event, 'Bulk Action', 'Are you sure you want to perform this action on the selected clients?')">
                            Apply
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left">
                            <input type="checkbox" 
                                   x-model="selectAll" 
                                   @change="toggleSelectAll()"
                                   class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Client</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Contact Info</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Projects</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Joined</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($clients as $client)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" 
                                   name="client_checkbox"
                                   value="{{ $client->id }}"
                                   x-model="selectedClients"
                                   class="client-checkbox rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <x-lucide-user class="w-5 h-5 text-primary-600" />
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-800">{{ $client->fullName }}</div>
                                    <div class="text-xs text-neutral-500">ID: {{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-neutral-700">{{ $client->email }}</div>
                            <div class="text-xs text-neutral-500">{{ $client->phoneNumber }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                <x-ui.badge type="info">
                                    {{ $client->created_projects_count ?? 0 }} Projects
                                </x-ui.badge>
                                <x-ui.badge type="neutral">
                                    {{ $client->service_requests_count ?? 0 }} Requests
                                </x-ui.badge>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($client->status == 'active')
                                <x-ui.badge type="success">
                                    <span class="w-1.5 h-1.5 bg-success-500 rounded-full mr-1.5"></span>
                                    Active
                                </x-ui.badge>
                            @elseif($client->status == 'inactive')
                                <x-ui.badge type="warning">
                                    <span class="w-1.5 h-1.5 bg-warning-500 rounded-full mr-1.5"></span>
                                    Inactive
                                </x-ui.badge>
                            @else
                                <x-ui.badge type="error">
                                    <span class="w-1.5 h-1.5 bg-error-500 rounded-full mr-1.5"></span>
                                    Banned
                                </x-ui.badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-700">{{ $client->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-neutral-500">{{ $client->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.clients.show', $client->id) }}" 
                                   class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                   title="View Details">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.clients.edit', $client->id) }}" 
                                   class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                   title="Edit">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </a>
                                <button type="button" 
                                        class="p-2 text-neutral-400 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors"
                                        onclick="confirmDelete('{{ $client->id }}')" 
                                        title="Delete">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12">
                            <x-ui.empty-state 
                                icon="users"
                                title="No clients found"
                                description="Try adjusting your search criteria or add a new client."
                            >
                                <a href="{{ route('admin.clients.create') }}">
                                    <x-ui.button icon="plus">
                                        Add New Client
                                    </x-ui.button>
                                </a>
                            </x-ui.empty-state>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(is_object($clients) && method_exists($clients, 'hasPages') && $clients->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100">
            <x-ui.pagination :paginator="$clients" />
        </div>
        @endif
    </x-ui.card>
</div>

<!-- Delete Confirmation Modal -->
<div x-data="{ open: false, clientId: null }" 
     x-show="open" 
     x-cloak
     @open-delete-modal.window="open = true; clientId = $event.detail.id"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm transition-opacity" 
         @click="open = false"></div>

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 transform transition-all"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="open = false">
            
            <!-- Icon -->
            <div class="w-12 h-12 rounded-full bg-error-50 flex items-center justify-center mx-auto mb-4">
                <x-lucide-alert-triangle class="w-6 h-6 text-error-500" />
            </div>
            
            <!-- Content -->
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-2">Confirm Delete</h3>
                <p class="text-sm text-neutral-500">
                    Are you sure you want to delete this client? All associated data will be permanently removed. This action cannot be undone.
                </p>
            </div>
            
            <!-- Actions -->
            <div class="flex gap-3 justify-center">
                <x-ui.button variant="secondary" @click="open = false">
                    Cancel
                </x-ui.button>
                <form :action="'/admin/clients/' + clientId" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <x-ui.button type="submit" variant="danger" icon="trash-2">
                        Delete
                    </x-ui.button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function confirmDelete(clientId) {
    window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: clientId } }));
}
</script>
@endsection
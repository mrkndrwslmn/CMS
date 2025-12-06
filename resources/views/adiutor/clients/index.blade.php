@extends('adiutor.layouts.app')

@section('title', 'My Clients')

@section('content')
<div class="max-w-8xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Clients', 'icon' => 'users'],
    ]" />

    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">My Clients</h1>
            <p class="text-sm text-neutral-500 mt-1">View and manage clients you work with</p>
        </div>
        <div class="flex items-center gap-2 bg-primary-50 px-4 py-2 rounded-xl">
            <x-lucide-users class="w-5 h-5 text-primary-500" />
            <div>
                <p class="text-xs text-neutral-500">Total Clients</p>
                <p class="text-xl font-semibold text-primary-600">{{ count($clients) }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Clients -->
        <x-ui.card>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Clients</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ count($clients) }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-users class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Total Projects -->
        <x-ui.card>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Projects</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ collect($clients)->sum('total_projects') }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-folder-kanban class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Completed Projects -->
        <x-ui.card>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Completed</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ collect($clients)->sum('completed_projects') }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <!-- Active Clients -->
        <x-ui.card>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Active Clients</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ collect($clients)->where('total_projects', '>', 0)->count() }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-user-check class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Main Content Card -->
    <x-ui.card padding="none">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-lg font-medium text-neutral-700 flex items-center gap-2">
                    <x-lucide-briefcase class="w-5 h-5 text-neutral-400" />
                    Client List
                </h2>
                <div class="relative">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search clients..." 
                           class="w-full sm:w-64 pl-10 pr-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <x-lucide-search class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 transform -translate-y-1/2" />
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Clients Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="clientsGrid">
                @forelse($clients as $client)
                    <div class="client-card bg-white rounded-xl border border-neutral-200 p-6 hover:shadow-md transition-all duration-200">
                        <!-- Client Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold text-lg">
                                    {{ strtoupper(substr($client->fullName, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-medium text-neutral-800 client-name">{{ $client->fullName }}</h3>
                                    <p class="text-xs text-neutral-400">Client ID: #{{ $client->id }}</p>
                                </div>
                            </div>
                            @if(isset($client->total_projects) && $client->total_projects > 0)
                                <x-ui.badge type="success" dot>Active</x-ui.badge>
                            @else
                                <x-ui.badge type="default">Inactive</x-ui.badge>
                            @endif
                        </div>

                        <!-- Contact Info -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-neutral-600">
                                <x-lucide-mail class="w-4 h-4 text-neutral-400" />
                                <span class="ml-2 truncate">{{ $client->email }}</span>
                            </div>
                            @if($client->phoneNumber)
                                <div class="flex items-center text-sm text-neutral-600">
                                    <x-lucide-phone class="w-4 h-4 text-neutral-400" />
                                    <span class="ml-2">{{ $client->phoneNumber }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Project Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-4 pt-4 border-t border-neutral-100">
                            <div class="text-center">
                                <p class="text-2xl font-semibold text-primary-600">{{ $client->total_projects ?? 0 }}</p>
                                <p class="text-xs text-neutral-500">Total Projects</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-semibold text-success-600">{{ $client->completed_projects ?? 0 }}</p>
                                <p class="text-xs text-neutral-500">Completed</p>
                            </div>
                        </div>

                        <!-- Last Project Date -->
                        @if($client->last_project_date)
                            <div class="flex items-center gap-1 text-xs text-neutral-400 mb-4">
                                <x-lucide-calendar class="w-3 h-3" />
                                Last project: {{ \Carbon\Carbon::parse($client->last_project_date)->format('M d, Y') }}
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('adiutor.tasks.index') }}?client={{ $client->id }}" 
                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-all">
                                <x-lucide-list-todo class="w-4 h-4" />
                                View Tasks
                            </a>
                            <button class="px-3 py-2.5 bg-neutral-100 text-neutral-600 text-sm font-medium rounded-lg hover:bg-neutral-200 transition-all"
                                    onclick="showClientDetails({{ json_encode($client) }})">
                                <x-lucide-info class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-neutral-400">
                        <x-lucide-users class="w-16 h-16 mb-4" />
                        <p class="text-lg font-medium text-neutral-500">No clients found</p>
                        <p class="text-sm mt-1">You haven't been assigned to any clients yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </x-ui.card>
</div>

<!-- Client Details Modal -->
<div id="clientDetailsModal" class="fixed inset-0 backdrop-blur-sm bg-neutral-900/50 z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl shadow-lg max-w-2xl w-full transform transition-all">
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                    <x-lucide-user class="w-5 h-5 text-neutral-400" />
                    Client Details
                </h3>
                <button type="button" class="text-neutral-400 hover:text-neutral-600 transition-colors" onclick="closeClientDetails()">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
        </div>
        <div class="px-6 py-6" id="clientDetailsContent">
            <!-- Content will be populated by JavaScript -->
        </div>
        <div class="px-6 py-4 bg-neutral-50 rounded-b-2xl flex justify-end">
            <x-ui.button variant="ghost" onclick="closeClientDetails()">
                Close
            </x-ui.button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const clientCards = document.querySelectorAll('.client-card');
    
    clientCards.forEach(card => {
        const clientName = card.querySelector('.client-name').textContent.toLowerCase();
        if (clientName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Show client details modal
function showClientDetails(client) {
    const modal = document.getElementById('clientDetailsModal');
    const content = document.getElementById('clientDetailsContent');
    
    content.innerHTML = `
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold text-2xl">
                ${client.fullName.charAt(0).toUpperCase()}
            </div>
            <div>
                <h4 class="text-xl font-semibold text-neutral-800">${client.fullName}</h4>
                <p class="text-sm text-neutral-500">Client ID: #${client.id}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-neutral-50 rounded-xl p-4">
                <p class="text-sm text-neutral-500 mb-1">Email</p>
                <p class="font-medium text-neutral-800">${client.email}</p>
            </div>
            <div class="bg-neutral-50 rounded-xl p-4">
                <p class="text-sm text-neutral-500 mb-1">Phone</p>
                <p class="font-medium text-neutral-800">${client.phoneNumber || 'Not provided'}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-primary-50 rounded-xl p-4 text-center">
                <p class="text-3xl font-semibold text-primary-600">${client.total_projects || 0}</p>
                <p class="text-sm text-neutral-500">Total Projects</p>
            </div>
            <div class="bg-success-50 rounded-xl p-4 text-center">
                <p class="text-3xl font-semibold text-success-600">${client.completed_projects || 0}</p>
                <p class="text-sm text-neutral-500">Completed</p>
            </div>
        </div>
    `;
    
    modal.style.display = 'flex';
    document.body.classList.add('overflow-hidden');
}

// Close client details modal
function closeClientDetails() {
    const modal = document.getElementById('clientDetailsModal');
    modal.style.display = 'none';
    document.body.classList.remove('overflow-hidden');
}

// Close modal on backdrop click
document.getElementById('clientDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeClientDetails();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeClientDetails();
    }
});
</script>
@endsection

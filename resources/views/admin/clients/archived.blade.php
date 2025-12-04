@extends('admin.layouts.app')

@section('title', 'Archived Clients')
@section('page-title', 'Archived Clients')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Clients', 'route' => 'admin.clients.index', 'icon' => 'users'],
        ['label' => 'Archived', 'icon' => 'archive'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Archived Clients" 
            description="View and restore archived clients"
        />
        <a href="{{ route('admin.clients.index') }}">
            <x-ui.button variant="secondary" icon="arrow-left">
                Back to Clients
            </x-ui.button>
        </a>
    </div>

    <!-- Info Banner -->
    <x-ui.card class="mb-6 bg-warning-50 border-warning-200">
        <div class="p-4 flex items-start gap-3">
            <x-lucide-info class="w-5 h-5 text-warning-600 flex-shrink-0 mt-0.5" />
            <div>
                <h4 class="text-sm font-medium text-warning-800">Archived Clients</h4>
                <p class="text-sm text-warning-700 mt-1">
                    These clients have been archived and are no longer active. You can restore them to make them accessible again.
                </p>
            </div>
        </div>
    </x-ui.card>

    <!-- Search -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.clients.archived') }}">
                <div class="flex items-end gap-4">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-neutral-700 mb-1.5">Search Archived Clients</label>
                        <x-ui.input 
                            type="text" 
                            name="search" 
                            id="search" 
                            icon="search"
                            placeholder="Search by name, email, or phone..." 
                            :value="request('search')"
                        />
                    </div>
                    <x-ui.button type="submit" icon="search">
                        Search
                    </x-ui.button>
                    @if(request('search'))
                        <a href="{{ route('admin.clients.archived') }}">
                            <x-ui.button type="button" variant="secondary" icon="x">
                                Clear
                            </x-ui.button>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Archived Clients Table -->
    <x-ui.card>
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex items-center gap-2">
                <x-lucide-archive class="w-5 h-5 text-neutral-400" />
                <h3 class="text-lg font-medium text-neutral-700">Archived Clients</h3>
                <x-ui.badge type="neutral">{{ $clients->total() }} total</x-ui.badge>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Client</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Contact Info</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Projects</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Archived</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($clients as $client)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-neutral-200 rounded-full flex items-center justify-center">
                                    <x-lucide-user class="w-5 h-5 text-neutral-500" />
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
                                <x-ui.badge type="neutral">
                                    {{ $client->created_projects_count ?? 0 }} Projects
                                </x-ui.badge>
                                <x-ui.badge type="neutral">
                                    {{ $client->service_requests_count ?? 0 }} Requests
                                </x-ui.badge>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-700">{{ $client->updated_at->format('M d, Y') }}</div>
                            <div class="text-xs text-neutral-500">{{ $client->updated_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.clients.restore', $client->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-success-700 bg-success-50 hover:bg-success-100 rounded-lg transition-colors"
                                            onclick="return confirm('Are you sure you want to restore this client?')">
                                        <x-lucide-rotate-ccw class="w-4 h-4" />
                                        Restore
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12">
                            <x-ui.empty-state 
                                icon="archive"
                                title="No archived clients"
                                description="There are no archived clients at the moment."
                            >
                                <a href="{{ route('admin.clients.index') }}">
                                    <x-ui.button variant="secondary" icon="arrow-left">
                                        Back to Clients
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
        @if($clients->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100">
            {{ $clients->links() }}
        </div>
        @endif
    </x-ui.card>
</div>
@endsection

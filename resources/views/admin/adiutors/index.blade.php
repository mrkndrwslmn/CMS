@extends('admin.layouts.app')

@section('title', 'Adiutor Management')
@section('page-title', 'Adiutor Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Adiutors', 'icon' => 'hard-hat'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Adiutor Management" 
            description="Manage all adiutors, their projects, and earnings"
        />
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.adiutors.export', request()->only(['status', 'date_from', 'date_to'])) }}">
                <x-ui.button variant="secondary" icon="download">
                    Export CSV
                </x-ui.button>
            </a>
            <a href="{{ route('admin.adiutors.create') }}">
                <x-ui.button icon="plus">
                    Add New Adiutor
                </x-ui.button>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <x-ui.stat-card 
            label="Total Adiutors" 
            :value="$stats['total_adiutors']" 
            icon="hard-hat"
        />
        <x-ui.stat-card 
            label="Active Adiutors" 
            :value="$stats['active_adiutors']" 
            icon="user-check"
            iconBg="success"
        />
        <x-ui.stat-card 
            label="Total Projects" 
            :value="$stats['total_projects']" 
            icon="folder-kanban"
            iconBg="primary"
        />
        <x-ui.stat-card 
            label="Total Earnings" 
            value="₱{{ number_format($stats['total_earnings'], 2) }}" 
            icon="wallet"
            iconBg="warning"
        />
        <a href="{{ route('admin.adiutors.archived') }}" class="block">
            <x-ui.stat-card 
                label="Archived Adiutors" 
                :value="$stats['archived_adiutors'] ?? 0" 
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
            <form method="GET" action="{{ route('admin.adiutors.index') }}">
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
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Joined</option>
                            <option value="fullName" {{ request('sort') == 'fullName' ? 'selected' : '' }}>Name</option>
                            <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="work_earnings_balance" {{ request('sort') == 'work_earnings_balance' ? 'selected' : '' }}>Earnings</option>
                        </x-ui.select>
                    </div>
                    <div class="flex items-end gap-3">
                        <x-ui.button type="submit" icon="search" class="flex-1">
                            Filter
                        </x-ui.button>
                        <a href="{{ route('admin.adiutors.index') }}" class="flex-1">
                            <x-ui.button type="button" variant="secondary" icon="x" class="w-full">
                                Clear
                            </x-ui.button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Adiutors Table -->
    <x-ui.card class="mb-6" x-data="{ 
        selectedAdiutors: [],
        selectAll: false,
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedAdiutors = [...document.querySelectorAll('.adiutor-checkbox')].map(el => el.value);
            } else {
                this.selectedAdiutors = [];
            }
        }
    }">
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-lucide-hard-hat class="w-5 h-5 text-neutral-400" />
                    <h3 class="text-lg font-medium text-neutral-700">Adiutors List</h3>
                </div>
                <!-- Bulk Actions -->
                <div x-show="selectedAdiutors.length > 0" x-cloak class="flex items-center gap-3">
                    <span class="text-sm text-neutral-600" x-text="selectedAdiutors.length + ' selected'"></span>
                    <form method="POST" action="{{ route('admin.adiutors.bulk-action') }}" class="flex items-center gap-2">
                        @csrf
                        <template x-for="id in selectedAdiutors" :key="id">
                            <input type="hidden" name="adiutor_ids[]" :value="id">
                        </template>
                        <select name="action" required class="text-sm border-neutral-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Select Action</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="ban">Ban</option>
                            <option value="archive">Archive</option>
                        </select>
                        <x-ui.button type="submit" size="sm" onclick="return window.Alerts.confirmForm(event, 'Bulk Action', 'Are you sure you want to perform this action on the selected adiutors?')">
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Adiutor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Contact Info</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Projects</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Earnings</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Joined</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($adiutors as $adiutor)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" 
                                   name="adiutor_checkbox"
                                   value="{{ $adiutor->id }}"
                                   x-model="selectedAdiutors"
                                   class="adiutor-checkbox rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-violet-100 rounded-full flex items-center justify-center">
                                    <x-lucide-hard-hat class="w-5 h-5 text-violet-600" />
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-800">{{ $adiutor->fullName }}</div>
                                    <div class="text-xs text-neutral-500">
                                        @if($adiutor->adiutorProfile && $adiutor->adiutorProfile->title)
                                            {{ $adiutor->adiutorProfile->title }}
                                        @else
                                            ID: {{ str_pad($adiutor->id, 4, '0', STR_PAD_LEFT) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-neutral-700">{{ $adiutor->email }}</div>
                            <div class="text-xs text-neutral-500">{{ $adiutor->phoneNumber }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                <x-ui.badge type="info">
                                    {{ $adiutor->project_assignments_count ?? 0 }} Projects
                                </x-ui.badge>
                                <x-ui.badge type="neutral">
                                    {{ $adiutor->time_entries_count ?? 0 }} Time Entries
                                </x-ui.badge>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-800">₱{{ number_format($adiutor->work_earnings_balance, 2) }}</div>
                            @if($adiutor->referral_credits > 0)
                                <div class="text-xs text-neutral-500">+₱{{ number_format($adiutor->referral_credits, 2) }} referrals</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($adiutor->status == 'active')
                                <x-ui.badge type="success">
                                    <span class="w-1.5 h-1.5 bg-success-500 rounded-full mr-1.5"></span>
                                    Active
                                </x-ui.badge>
                            @elseif($adiutor->status == 'inactive')
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
                            <div class="text-sm text-neutral-700">{{ $adiutor->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-neutral-500">{{ $adiutor->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.adiutors.show', $adiutor->id) }}" 
                                   class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                   title="View Details">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.adiutors.edit', $adiutor->id) }}" 
                                   class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                   title="Edit">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </a>
                                <button type="button" 
                                        class="p-2 text-neutral-400 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors"
                                        onclick="confirmDelete('{{ $adiutor->id }}')" 
                                        title="Archive">
                                    <x-lucide-archive class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12">
                            <x-ui.empty-state 
                                icon="hard-hat"
                                title="No adiutors found"
                                description="Try adjusting your search criteria or add a new adiutor."
                            >
                                <a href="{{ route('admin.adiutors.create') }}">
                                    <x-ui.button icon="plus">
                                        Add New Adiutor
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
        @if(is_object($adiutors) && method_exists($adiutors, 'hasPages') && $adiutors->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100">
            <x-ui.pagination :paginator="$adiutors" />
        </div>
        @endif
    </x-ui.card>
</div>

<!-- Delete Confirmation Modal -->
<div x-data="{ open: false, adiutorId: null }" 
     x-show="open" 
     x-cloak
     @open-delete-modal.window="open = true; adiutorId = $event.detail.id"
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
            <div class="w-12 h-12 rounded-full bg-warning-50 flex items-center justify-center mx-auto mb-4">
                <x-lucide-archive class="w-6 h-6 text-warning-500" />
            </div>
            
            <!-- Content -->
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-2">Confirm Archive</h3>
                <p class="text-sm text-neutral-500">
                    Are you sure you want to archive this adiutor? They will no longer be able to access their account. You can restore them later from the archived list.
                </p>
            </div>
            
            <!-- Actions -->
            <div class="flex gap-3 justify-center">
                <x-ui.button variant="secondary" @click="open = false">
                    Cancel
                </x-ui.button>
                <form :action="'/admin/adiutors/' + adiutorId" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <x-ui.button type="submit" variant="warning" icon="archive">
                        Archive
                    </x-ui.button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function confirmDelete(adiutorId) {
    window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: adiutorId } }));
}
</script>
@endsection

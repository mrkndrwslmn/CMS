@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Home', 'href' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Users', 'icon' => 'users']
    ]" class="mb-6" />

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="User Management"
            subtitle="Manage roles and permissions"
        />
        <x-ui.button href="{{ route('admin.clients.create') }}" variant="primary" class="inline-flex items-center gap-2">
            <x-lucide-plus class="w-4 h-4" />
            Add User
        </x-ui.button>
    </div>

    <!-- Statistics Row -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <x-ui.card class="p-4" padding="none">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="p-2 bg-primary-50 rounded-lg">
                    <x-lucide-users class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
        <x-ui.card class="p-4" padding="none">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Admins</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['admins'] }}</p>
                </div>
                <div class="p-2 bg-secondary-50 rounded-lg">
                    <x-lucide-shield class="w-5 h-5 text-secondary-500" />
                </div>
            </div>
        </x-ui.card>
        <x-ui.card class="p-4" padding="none">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Clients</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['clients'] }}</p>
                </div>
                <div class="p-2 bg-accent-50 rounded-lg">
                    <x-lucide-briefcase class="w-5 h-5 text-accent-500" />
                </div>
            </div>
        </x-ui.card>
        <x-ui.card class="p-4" padding="none">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Adiutors</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['adiutors'] }}</p>
                </div>
                <div class="p-2 bg-info-50 rounded-lg">
                    <x-lucide-user-check class="w-5 h-5 text-info-500" />
                </div>
            </div>
        </x-ui.card>
        <x-ui.card class="p-4" padding="none">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Active</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="p-2 bg-success-50 rounded-lg">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>
        <x-ui.card class="p-4" padding="none">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Inactive</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['inactive'] }}</p>
                </div>
                <div class="p-2 bg-warning-50 rounded-lg">
                    <x-lucide-pause-circle class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Main Content Card -->
    <x-ui.card padding="none">
        
        <!-- Inline Filters Bar -->
        <div class="p-4 border-b border-neutral-100">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[200px] max-w-xs">
                    <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" />
                    <input type="text" name="search" 
                        class="w-full pl-10 pr-4 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 bg-white transition-all" 
                        placeholder="Search users..." value="{{ request('search') }}">
                </div>
                <select name="role" class="px-4 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 bg-white transition-all">
                    <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
                    <option value="adiutor" {{ request('role') == 'adiutor' ? 'selected' : '' }}>Adiutor</option>
                </select>
                <select name="status" class="px-4 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 bg-white transition-all">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <x-ui.button type="submit" variant="primary" size="sm">
                    <x-lucide-filter class="w-4 h-4 mr-1" />
                    Filter
                </x-ui.button>
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm text-neutral-500 hover:text-neutral-700 transition-colors">
                    Clear
                </a>
            </form>
        </div>

        <!-- Table Header with Bulk Actions -->
        <div class="px-4 py-3 border-b border-neutral-100 flex items-center justify-between bg-neutral-50">
            <p class="text-xs text-neutral-500 font-medium uppercase tracking-wide">Users List</p>
            <form method="POST" action="{{ route('admin.users.bulk-action') }}" id="bulkActionForm" class="flex items-center gap-2">
                @csrf
                <select name="action" class="px-3 py-1.5 text-xs border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 bg-white">
                    <option value="">Bulk Actions</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="delete">Delete</option>
                </select>
                <x-ui.button type="submit" variant="secondary" size="sm" onclick="return confirmBulkAction()">
                    Apply
                </x-ui.button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            @if(!empty($users))
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-neutral-100">
                            <th class="w-10 px-4 py-3 text-left">
                                <input id="selectAll" type="checkbox" class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wide">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wide">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wide">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wide">Joined</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 user-checkbox">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($user->profilePic)
                                        <img src="{{ $user->getProfilePictureUrl() }}" class="h-9 w-9 rounded-full object-cover ring-2 ring-neutral-100">
                                    @else
                                        <div class="h-9 w-9 rounded-full bg-primary-100 flex items-center justify-center">
                                            <span class="text-primary-600 text-sm font-medium">{{ substr($user->fullName, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-neutral-900">{{ $user->fullName }}</p>
                                        <p class="text-xs text-neutral-400">ID: {{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5 text-sm text-neutral-700">
                                    <x-lucide-mail class="w-3.5 h-3.5 text-neutral-400" />
                                    {{ $user->email }}
                                </div>
                                @if($user->phoneNumber)
                                    <div class="flex items-center gap-1.5 text-xs text-neutral-400 mt-0.5">
                                        <x-lucide-phone class="w-3 h-3" />
                                        {{ $user->phoneNumber }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($user->role == 'admin')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-secondary-50 text-secondary-700">
                                        <x-lucide-shield class="w-3 h-3" />
                                        Admin
                                    </span>
                                @elseif($user->role == 'client')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-accent-50 text-accent-700">
                                        <x-lucide-briefcase class="w-3 h-3" />
                                        Client
                                    </span>
                                @elseif($user->role == 'adiutor')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-info-50 text-info-700">
                                        <x-lucide-user-check class="w-3 h-3" />
                                        Adiutor
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                        <x-lucide-user class="w-3 h-3" />
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($user->status == 'active')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-success-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-success-500"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-neutral-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-neutral-300"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5 text-sm text-neutral-500">
                                    <x-lucide-calendar class="w-3.5 h-3.5 text-neutral-400" />
                                    {{ $user->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                       title="View">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="p-2 text-neutral-400 hover:text-warning-600 hover:bg-warning-50 rounded-lg transition-colors"
                                                    title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                @if($user->status === 'active')
                                                    <x-lucide-pause class="w-4 h-4" />
                                                @else
                                                    <x-lucide-play class="w-4 h-4" />
                                                @endif
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block" onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete User', 'Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-neutral-400 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors" 
                                                    title="Delete">
                                                <x-lucide-trash-2 class="w-4 h-4" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Footer -->
                <div class="px-4 py-3 border-t border-neutral-100 flex items-center justify-between">
                    <p class="text-xs text-neutral-500">Showing {{ count($users) }} users</p>
                    <div>
                        <!-- Pagination controls -->
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="w-16 h-16 rounded-full bg-neutral-100 flex items-center justify-center mb-4">
                        <x-lucide-users class="w-8 h-8 text-neutral-400" />
                    </div>
                    <p class="text-base font-medium text-neutral-600 mb-1">No users found</p>
                    <p class="text-sm text-neutral-400 mb-4">Try adjusting your filters</p>
                    <x-ui.button href="{{ route('admin.clients.create') }}" variant="primary" class="inline-flex items-center gap-2">
                        <x-lucide-plus class="w-4 h-4" />
                        Add User
                    </x-ui.button>
                </div>
            @endif
        </div>
    </x-ui.card>
</div>
@endsection

@push('scripts')
<script>
    // Select all checkbox functionality
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Update select all when individual checkboxes change
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
                
                selectAll.checked = checkedCount === checkboxes.length;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
            }
        });
    });

    function confirmBulkAction() {
        const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
        const action = document.querySelector('select[name="action"]').value;
        
        if (selectedUsers.length === 0) {
            window.toast.warning('Please select at least one user.');
            return false;
        }
        
        if (!action) {
            window.toast.warning('Please select an action.');
            return false;
        }
        
        return window.Alerts.confirmFormSync(`Are you sure you want to ${action} ${selectedUsers.length} selected user(s)?`);
    }
</script>
@endpush

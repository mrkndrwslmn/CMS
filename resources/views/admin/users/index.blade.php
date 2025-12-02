@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="min-h-screen bg-neutral-50">
    <div class="max-w-8xl mx-auto px-6 py-8">
        
        <!-- Header/Taskbar -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-primary-500">Users</h1>
                <p class="text-sm text-neutral-400 mt-0.5">Manage roles and permissions</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2 text-xs"></i>Add User
            </a>
        </div>

        <!-- Statistics Row -->
        <div class="grid grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <div class="bg-white rounded-lg border border-neutral-200 p-4">
                <p class="text-xs text-neutral-400 font-medium">Total</p>
                <p class="text-xl font-bold text-primary-500 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg border border-neutral-200 p-4">
                <p class="text-xs text-neutral-400 font-medium">Admins</p>
                <p class="text-xl font-bold text-secondary-500 mt-1">{{ $stats['admins'] }}</p>
            </div>
            <div class="bg-white rounded-lg border border-neutral-200 p-4">
                <p class="text-xs text-neutral-400 font-medium">Clients</p>
                <p class="text-xl font-bold text-accent-500 mt-1">{{ $stats['clients'] }}</p>
            </div>
            <div class="bg-white rounded-lg border border-neutral-200 p-4">
                <p class="text-xs text-neutral-400 font-medium">Adiutors</p>
                <p class="text-xl font-bold text-tertiary-500 mt-1">{{ $stats['adiutors'] }}</p>
            </div>
            <div class="bg-white rounded-lg border border-neutral-200 p-4">
                <p class="text-xs text-neutral-400 font-medium">Active</p>
                <p class="text-xl font-bold text-success-500 mt-1">{{ $stats['active'] }}</p>
            </div>
            <div class="bg-white rounded-lg border border-neutral-200 p-4">
                <p class="text-xs text-neutral-400 font-medium">Inactive</p>
                <p class="text-xl font-bold text-warning-500 mt-1">{{ $stats['inactive'] }}</p>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-lg border border-neutral-200">
            
            <!-- Inline Filters Bar -->
            <div class="p-4 border-b border-neutral-100">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[200px] max-w-xs">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-neutral-300 text-sm"></i>
                        <input type="text" name="search" 
                            class="w-full pl-9 pr-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-neutral-50" 
                            placeholder="Search users..." value="{{ request('search') }}">
                    </div>
                    <select name="role" class="px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-neutral-50">
                        <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="adiutor" {{ request('role') == 'adiutor' ? 'selected' : '' }}>Adiutor</option>
                    </select>
                    <select name="status" class="px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-neutral-50">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-colors">
                        Filter
                    </button>
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
                    <select name="action" class="px-3 py-1.5 text-xs border border-neutral-200 rounded-md focus:ring-2 focus:ring-primary-500 bg-white">
                        <option value="">Bulk Actions</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="px-3 py-1.5 text-xs border border-neutral-300 text-neutral-600 hover:bg-neutral-100 rounded-md transition-colors" 
                        onclick="return confirmBulkAction()">
                        Apply
                    </button>
                </form>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                @if(!empty($users))
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-neutral-100">
                                <th class="w-10 px-4 py-3 text-left">
                                    <input id="selectAll" type="checkbox" class="h-4 w-4 rounded border-neutral-300 text-primary-500 focus:ring-primary-500">
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
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="h-4 w-4 rounded border-neutral-300 text-primary-500 focus:ring-primary-500 user-checkbox">
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($user->profilePic)
                                            <img src="{{ $user->getProfilePictureUrl() }}" class="h-9 w-9 rounded-full object-cover">
                                        @else
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                                <span class="text-white text-sm font-medium">{{ substr($user->fullName, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-neutral-900">{{ $user->fullName }}</p>
                                            <p class="text-xs text-neutral-400">ID: {{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-neutral-700">{{ $user->email }}</p>
                                    @if($user->phoneNumber)
                                        <p class="text-xs text-neutral-400">{{ $user->phoneNumber }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->role == 'admin')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-medium bg-secondary-50 text-secondary-600">Admin</span>
                                    @elseif($user->role == 'client')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-medium bg-accent-50 text-accent-600">Client</span>
                                    @elseif($user->role == 'adiutor')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-medium bg-tertiary-50 text-tertiary-600">Adiutor</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-medium bg-neutral-100 text-neutral-600">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->status == 'active')
                                        <span class="inline-flex items-center gap-1 text-xs text-success-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-success-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-neutral-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-300"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-neutral-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="p-2 text-neutral-400 hover:text-accent-500 hover:bg-accent-50 rounded-md transition-colors" 
                                           title="View">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="p-2 text-neutral-400 hover:text-primary-500 hover:bg-primary-50 rounded-md transition-colors" 
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="p-2 text-neutral-400 hover:text-warning-500 hover:bg-warning-50 rounded-md transition-colors"
                                                        title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $user->status === 'active' ? 'pause' : 'play' }} text-sm"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2 text-neutral-400 hover:text-error-500 hover:bg-error-50 rounded-md transition-colors" 
                                                        title="Delete">
                                                    <i class="fas fa-trash text-sm"></i>
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
                        <div class="w-14 h-14 rounded-full bg-neutral-100 flex items-center justify-center mb-4">
                            <i class="fas fa-users text-neutral-300 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-neutral-600 mb-1">No users found</p>
                        <p class="text-xs text-neutral-400 mb-4">Try adjusting your filters</p>
                        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2 text-xs"></i>Add User
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
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
            alert('Please select at least one user.');
            return false;
        }
        
        if (!action) {
            alert('Please select an action.');
            return false;
        }
        
        return confirm(`Are you sure you want to ${action} ${selectedUsers.length} selected user(s)?`);
    }
</script>
@endpush

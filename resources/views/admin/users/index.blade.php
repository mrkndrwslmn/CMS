@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">User Management</h1>
            <p class="text-neutral-500 text-sm">Manage all users, their roles and permissions</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Add New User
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5 mb-6">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-primary-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-primary-500 uppercase mb-1">Total Users</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['total'] }}</div>
                    </div>
                    <div class="bg-primary-50 p-3 rounded-lg">
                        <i class="fas fa-users text-xl text-primary-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admins -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-secondary-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-secondary-500 uppercase mb-1">Admins</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['admins'] }}</div>
                    </div>
                    <div class="bg-secondary-50 p-3 rounded-lg">
                        <i class="fas fa-user-shield text-xl text-secondary-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-accent-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-accent-500 uppercase mb-1">Clients</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['clients'] }}</div>
                    </div>
                    <div class="bg-accent-50 p-3 rounded-lg">
                        <i class="fas fa-user-tie text-xl text-accent-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adiutors -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-tertiary-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-tertiary-500 uppercase mb-1">Adiutors</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['adiutors'] }}</div>
                    </div>
                    <div class="bg-tertiary-50 p-3 rounded-lg">
                        <i class="fas fa-user-cog text-xl text-tertiary-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-success-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-success-500 uppercase mb-1">Active</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['active'] }}</div>
                    </div>
                    <div class="bg-success-50 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-xl text-success-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Users -->
        <div class="bg-white rounded-xl shadow-sm transition-all duration-300 hover:-translate-y-1 border-l-4 border-warning-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-semibold text-warning-500 uppercase mb-1">Inactive</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $stats['inactive'] }}</div>
                    </div>
                    <div class="bg-warning-50 p-3 rounded-lg">
                        <i class="fas fa-pause-circle text-xl text-warning-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-neutral-200">
            <h6 class="text-lg font-semibold text-primary-500">Filters</h6>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-neutral-400"></i>
                        </div>
                        <input type="text" name="search" class="pl-10 w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                            placeholder="Search by name, email, or phone" value="{{ request('search') }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Role</label>
                    <select name="role" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="adiutor" {{ request('role') == 'adiutor' ? 'selected' : '' }}>Adiutor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="flex-1 border border-neutral-300 text-neutral-700 hover:bg-neutral-100 py-2 px-4 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fas fa-times mr-2"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h6 class="text-lg font-semibold text-primary-500 mb-3 sm:mb-0">Users List</h6>
            <div>
                <form method="POST" action="{{ route('admin.users.bulk-action') }}" id="bulkActionForm" class="flex items-center space-x-2">
                    @csrf
                    <div class="relative" x-data="{ open: false }">
                        <select name="action" class="pl-3 pr-8 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 appearance-none bg-white">
                            <option value="">Bulk Actions</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    <button type="submit" class="px-3 py-2 border border-primary-500 text-primary-500 hover:bg-primary-50 rounded-lg text-sm flex items-center transition-colors" 
                        onclick="return confirmBulkAction()">
                        Apply
                    </button>
                </form>
            </div>
        </div>
        <div class="p-6">
            @if(!empty($users))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th scope="col" class="w-12 px-6 py-3 text-left">
                                    <div class="flex items-center">
                                        <input id="selectAll" type="checkbox" class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    Joined
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider w-36">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 user-checkbox">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($user->profilePic)
                                                <img src="{{ $user->getProfilePictureUrl() }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center">
                                                    <span class="text-white font-bold">{{ substr($user->fullName, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-neutral-900">{{ $user->fullName }}</div>
                                            <div class="text-xs text-neutral-500">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900">{{ $user->email }}</div>
                                    @if($user->phoneNumber)
                                        <div class="text-xs text-neutral-500">{{ $user->phoneNumber }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role == 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800">
                                            Admin
                                        </span>
                                    @elseif($user->role == 'client')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent-100 text-accent-800">
                                            Client
                                        </span>
                                    @elseif($user->role == 'adiutor')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-tertiary-100 text-tertiary-800">
                                            Adiutor
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->status == 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="text-accent-600 hover:text-accent-900 bg-accent-50 hover:bg-accent-100 p-2 rounded-lg transition-colors" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="text-primary-600 hover:text-primary-900 bg-primary-50 hover:bg-primary-100 p-2 rounded-lg transition-colors" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="{{ $user->status === 'active' ? 'text-neutral-600 bg-neutral-50 hover:bg-neutral-100' : 'text-success-600 bg-success-50 hover:bg-success-100' }} p-2 rounded-lg transition-colors"
                                                        title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $user->status === 'active' ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-error-600 hover:text-error-900 bg-error-50 hover:bg-error-100 p-2 rounded-lg transition-colors" 
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row justify-between items-center mt-6 space-y-3 sm:space-y-0">
                    <div class="text-sm text-neutral-700">
                        Showing users
                    </div>
                    <div>
                        <!-- Pagination controls will appear here if supported by the collection -->
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="bg-neutral-100 p-6 rounded-full mb-4">
                        <i class="fas fa-users text-4xl text-neutral-400"></i>
                    </div>
                    <h5 class="text-lg font-medium text-neutral-700 mb-1">No users found</h5>
                    <p class="text-neutral-500 mb-4">Try adjusting your search criteria or add a new user.</p>
                    <a href="{{ route('admin.users.create') }}" class="flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add New User
                    </a>
                </div>
            @endif
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
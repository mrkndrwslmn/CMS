@extends('admin.layouts.app')

@section('title', 'Tasks Management')

@section('content')
<div class="px-6 py-8">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-success-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-success-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-error-50 border-l-4 border-error-500 text-error-700 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-error-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-error-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-primary-600 mb-4 md:mb-0">
            Task Management
        </h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Create Task
            </a>
            <button type="button" id="bulkActionBtn" class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-cog mr-2"></i>Bulk Actions
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Tasks -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-primary-100 rounded-full p-3 mr-4">
                    <i class="fas fa-tasks text-primary-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Total Tasks</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ $stats['total_tasks'] }}</h3>
                </div>
            </div>
        </div>
        
        <!-- Pending Tasks -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-warning-100 rounded-full p-3 mr-4">
                    <i class="fas fa-clock text-warning-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Pending Tasks</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ $stats['pending_tasks'] }}</h3>
                </div>
            </div>
        </div>
        
        <!-- In Progress Tasks -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-info-100 rounded-full p-3 mr-4">
                    <i class="fas fa-spinner text-info-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">In Progress</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ $stats['in_progress_tasks'] }}</h3>
                </div>
            </div>
        </div>
        
        <!-- Completed Tasks -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-success-100 rounded-full p-3 mr-4">
                    <i class="fas fa-check-circle text-success-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-neutral-500">Completed</p>
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ $stats['completed_tasks'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Area -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="p-6">
            <form action="{{ route('admin.tasks.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-neutral-600 mb-1">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search tasks..."
                               class="w-full rounded-lg border border-neutral-300 px-4 py-2 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-600 mb-1">Status</label>
                        <select name="status" id="status" 
                                class="w-full rounded-lg border border-neutral-300 px-4 py-2 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="priority" class="block text-sm font-medium text-neutral-600 mb-1">Priority</label>
                        <select name="priority" id="priority" 
                                class="w-full rounded-lg border border-neutral-300 px-4 py-2 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="assignee" class="block text-sm font-medium text-neutral-600 mb-1">Assignee</label>
                        <select name="assignee" id="assignee" 
                                class="w-full rounded-lg border border-neutral-300 px-4 py-2 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="">All Assignees</option>
                            @foreach($adiutors as $adiutor)
                                <option value="{{ $adiutor->id }}" {{ request('assignee') == $adiutor->id ? 'selected' : '' }}>
                                    {{ $adiutor->fullName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="client" class="block text-sm font-medium text-neutral-600 mb-1">Client</label>
                        <select name="client" id="client" 
                                class="w-full rounded-lg border border-neutral-300 px-4 py-2 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                    {{ $client->fullName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="sort" class="block text-sm font-medium text-neutral-600 mb-1">Sort By</label>
                        <select name="sort" id="sort" 
                                class="w-full rounded-lg border border-neutral-300 px-4 py-2 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                            <option value="deadline" {{ request('sort') === 'deadline' ? 'selected' : '' }}>Deadline</option>
                            <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>Status</option>
                            <option value="priority" {{ request('sort') === 'priority' ? 'selected' : '' }}>Priority</option>
                            <option value="taskTitle" {{ request('sort') === 'taskTitle' ? 'selected' : '' }}>Task Title</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="direction" class="block text-sm font-medium text-neutral-600 mb-1">Order</label>
                        <select name="direction" id="direction" 
                                class="w-full rounded-lg border border-neutral-300 px-4 py-2 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.tasks.index') }}" class="ml-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <form action="{{ route('admin.tasks.bulk-action') }}" method="POST" id="bulkActionForm">
                @csrf
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Task
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Assignee
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Priority
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Deadline
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-neutral-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="task_ids[]" value="{{ $task->taskID }}" class="taskCheckbox rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-neutral-900 mb-1">
                                            <a href="{{ route('admin.tasks.show', $task->taskID) }}" class="hover:text-primary-600">
                                                {{ $task->taskTitle }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-neutral-500">
                                            {{ Str::limit($task->taskDescription, 50) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->client)
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-neutral-200 text-neutral-600 flex items-center justify-center mr-2">
                                                <i class="fas fa-user text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-neutral-900">{{ $task->client->fullName }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-neutral-400">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->assignedUser)
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-2">
                                                <i class="fas fa-user-tie text-xs"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-neutral-900">{{ $task->assignedUser->fullName }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-neutral-400">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->getStatusBadgeClass() }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->getPriorityBadgeClass() }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->deadline)
                                        @php
                                            $isPast = $task->deadline->isPast() && !$task->isCompleted();
                                            $isClose = !$isPast && $task->deadline->diffInDays(now()) <= 3;
                                        @endphp
                                        
                                        <span class="{{ $isPast ? 'text-error-600' : ($isClose ? 'text-warning-600' : 'text-neutral-500') }}">
                                            {{ $task->deadline->format('M d, Y') }}
                                            @if($isPast)
                                                <span class="block text-xs">
                                                    <i class="fas fa-exclamation-circle"></i> 
                                                    Overdue
                                                </span>
                                            @elseif($isClose)
                                                <span class="block text-xs">
                                                    <i class="fas fa-clock"></i> 
                                                    Soon
                                                </span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-neutral-400">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2 justify-end">
                                        <a href="{{ route('admin.tasks.show', $task->taskID) }}" class="text-primary-600 hover:text-primary-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.tasks.edit', $task->taskID) }}" class="text-info-600 hover:text-info-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" onclick="deleteTask('{{ $task->taskID }}')" class="text-error-600 hover:text-error-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center">
                                    <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-tasks text-neutral-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-neutral-500 text-base">No tasks found</h3>
                                    <p class="text-neutral-400 text-sm mt-1">Try adjusting your search or filter to find what you're looking for</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Bulk Action Controls (Hidden initially) -->
                <div id="bulkActionControls" class="bg-neutral-50 border-t border-neutral-200 p-4 hidden">
                    <div class="flex flex-wrap items-center space-x-4">
                        <select name="action" id="bulkActionSelect" class="rounded-lg border border-neutral-300 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="">Select Action</option>
                            <option value="assign">Assign Tasks</option>
                            <option value="status_update">Update Status</option>
                            <option value="delete">Delete Tasks</option>
                        </select>
                        
                        <div id="assigneeSelect" class="hidden">
                            <select name="assignedTo" class="rounded-lg border border-neutral-300 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <option value="">Select Assignee</option>
                                @foreach($adiutors as $adiutor)
                                    <option value="{{ $adiutor->id }}">{{ $adiutor->fullName }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div id="statusSelect" class="hidden">
                            <select name="status" class="rounded-lg border border-neutral-300 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <button type="submit" id="applyBulkAction" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                Apply
                            </button>
                            <button type="button" id="cancelBulkAction" class="ml-2 inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $tasks->links() }}
        </div>
    </div>
</div>

<!-- Delete Task Confirmation Modal -->
<div id="deleteTaskModal" class="fixed inset-0 bg-neutral-900 bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-neutral-200">
            <h3 class="text-lg font-semibold text-neutral-800">Confirm Deletion</h3>
        </div>
        <div class="p-6">
            <p class="text-neutral-700">Are you sure you want to delete this task? This action cannot be undone.</p>
        </div>
        <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-200 flex justify-end space-x-2">
            <button type="button" id="cancelDeleteTask" class="px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                Cancel
            </button>
            <form id="deleteTaskForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-error-500 hover:bg-error-600 text-white rounded-lg transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select All Checkbox
        const selectAllCheckbox = document.getElementById('selectAll');
        const taskCheckboxes = document.querySelectorAll('.taskCheckbox');
        
        selectAllCheckbox.addEventListener('change', function() {
            taskCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            toggleBulkActionControls();
        });
        
        taskCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleBulkActionControls();
                
                // Update "Select All" checkbox state
                let allChecked = true;
                taskCheckboxes.forEach(cb => {
                    if (!cb.checked) allChecked = false;
                });
                selectAllCheckbox.checked = allChecked;
            });
        });
        
        // Bulk Action Controls
        const bulkActionBtn = document.getElementById('bulkActionBtn');
        const bulkActionControls = document.getElementById('bulkActionControls');
        const bulkActionSelect = document.getElementById('bulkActionSelect');
        const assigneeSelect = document.getElementById('assigneeSelect');
        const statusSelect = document.getElementById('statusSelect');
        const applyBulkAction = document.getElementById('applyBulkAction');
        const cancelBulkAction = document.getElementById('cancelBulkAction');
        
        bulkActionBtn.addEventListener('click', function() {
            bulkActionControls.classList.toggle('hidden');
        });
        
        cancelBulkAction.addEventListener('click', function() {
            bulkActionControls.classList.add('hidden');
        });
        
        bulkActionSelect.addEventListener('change', function() {
            assigneeSelect.classList.add('hidden');
            statusSelect.classList.add('hidden');
            
            if (bulkActionSelect.value === 'assign') {
                assigneeSelect.classList.remove('hidden');
            } else if (bulkActionSelect.value === 'status_update') {
                statusSelect.classList.remove('hidden');
            }
        });
        
        function toggleBulkActionControls() {
            const anyChecked = Array.from(taskCheckboxes).some(cb => cb.checked);
            if (anyChecked && !bulkActionControls.classList.contains('hidden')) {
                bulkActionBtn.classList.add('bg-primary-500', 'hover:bg-primary-600', 'text-white');
                bulkActionBtn.classList.remove('bg-neutral-100', 'hover:bg-neutral-200', 'text-neutral-700');
            } else {
                bulkActionBtn.classList.remove('bg-primary-500', 'hover:bg-primary-600', 'text-white');
                bulkActionBtn.classList.add('bg-neutral-100', 'hover:bg-neutral-200', 'text-neutral-700');
            }
        }
        
        // Delete Task Modal
        const deleteTaskModal = document.getElementById('deleteTaskModal');
        const deleteTaskForm = document.getElementById('deleteTaskForm');
        const cancelDeleteTask = document.getElementById('cancelDeleteTask');
        
        window.deleteTask = function(taskId) {
            deleteTaskModal.classList.remove('hidden');
            deleteTaskForm.action = `/admin/tasks/${taskId}`;
        }
        
        cancelDeleteTask.addEventListener('click', function() {
            deleteTaskModal.classList.add('hidden');
        });
        
        // Form submission validation for bulk actions
        document.getElementById('bulkActionForm').addEventListener('submit', function(event) {
            const selectedAction = bulkActionSelect.value;
            const checkedTasks = document.querySelectorAll('.taskCheckbox:checked');
            
            if (checkedTasks.length === 0) {
                event.preventDefault();
                alert('Please select at least one task to perform this action');
                return false;
            }
            
            if (selectedAction === '') {
                event.preventDefault();
                alert('Please select an action to perform');
                return false;
            }
            
            if (selectedAction === 'assign' && assigneeSelect.querySelector('select').value === '') {
                event.preventDefault();
                alert('Please select an assignee');
                return false;
            }
            
            if (selectedAction === 'status_update' && statusSelect.querySelector('select').value === '') {
                event.preventDefault();
                alert('Please select a status');
                return false;
            }
            
            if (selectedAction === 'delete') {
                if (!confirm('Are you sure you want to delete the selected tasks? This action cannot be undone.')) {
                    event.preventDefault();
                    return false;
                }
            }
        });
    });
</script>
@endpush
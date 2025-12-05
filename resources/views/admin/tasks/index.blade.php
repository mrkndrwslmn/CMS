@extends('admin.layouts.app')

@section('title', 'Tasks Management')
@section('page-title', 'Tasks Management')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Home', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Tasks', 'icon' => 'list-checks']
    ]" class="mb-6" />

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-success-50 border border-success-200 text-success-700 p-4 rounded-lg mb-6">
            <div class="flex items-center gap-3">
                <x-lucide-check-circle class="w-5 h-5 text-success-500 flex-shrink-0" />
                <p class="text-success-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-error-50 border border-error-200 text-error-700 p-4 rounded-lg mb-6">
            <div class="flex items-center gap-3">
                <x-lucide-alert-circle class="w-5 h-5 text-error-500 flex-shrink-0" />
                <p class="text-error-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Task Management"
            subtitle="Manage and track all tasks"
        />
        <div class="flex gap-2">
            <x-ui.button href="{{ route('admin.tasks.create') }}" variant="primary" class="inline-flex items-center gap-2">
                <x-lucide-plus class="w-4 h-4" />
                Create Task
            </x-ui.button>
            <x-ui.button type="button" id="bulkActionBtn" variant="secondary" class="inline-flex items-center gap-2">
                <x-lucide-settings class="w-4 h-4" />
                Bulk Actions
            </x-ui.button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Tasks -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Tasks</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['total_tasks'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-list-checks class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
        
        <!-- Pending Tasks -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending Tasks</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['pending_tasks'] }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>
        
        <!-- In Progress Tasks -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">In Progress</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['in_progress_tasks'] }}</p>
                </div>
                <div class="p-3 bg-info-50 rounded-xl">
                    <x-lucide-loader class="w-5 h-5 text-info-500" />
                </div>
            </div>
        </x-ui.card>
        
        <!-- Completed Tasks -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Completed</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['completed_tasks'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Filter and Search Area -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <form action="{{ route('admin.tasks.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <x-ui.input 
                            type="text" 
                            name="search" 
                            id="search" 
                            label="Search"
                            :value="request('search')" 
                            placeholder="Search tasks..."
                        />
                    </div>
                    
                    <div>
                        <x-ui.select name="status" id="status" label="Status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </x-ui.select>
                    </div>
                    
                    <div>
                        <x-ui.select name="priority" id="priority" label="Priority">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        </x-ui.select>
                    </div>
                    
                    <div>
                        <x-ui.select name="assignee" id="assignee" label="Adiutor">
                            <option value="">All Adiutors</option>
                            @foreach($adiutors as $adiutor)
                                <option value="{{ $adiutor->id }}" {{ request('assignee') == $adiutor->id ? 'selected' : '' }}>
                                    {{ $adiutor->fullName }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <x-ui.select name="client" id="client" label="Client">
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                    {{ $client->fullName }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>
                    
                    <div>
                        <x-ui.select name="sort" id="sort" label="Sort By">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                            <option value="deadline" {{ request('sort') === 'deadline' ? 'selected' : '' }}>Deadline</option>
                            <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>Status</option>
                            <option value="priority" {{ request('sort') === 'priority' ? 'selected' : '' }}>Priority</option>
                            <option value="taskTitle" {{ request('sort') === 'taskTitle' ? 'selected' : '' }}>Task Title</option>
                        </x-ui.select>
                    </div>
                    
                    <div>
                        <x-ui.select name="direction" id="direction" label="Order">
                            <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                        </x-ui.select>
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <x-ui.button type="submit" variant="primary" class="inline-flex items-center gap-2">
                            <x-lucide-search class="w-4 h-4" />
                            Filter
                        </x-ui.button>
                        <x-ui.button href="{{ route('admin.tasks.index') }}" variant="secondary" class="inline-flex items-center gap-2">
                            <x-lucide-x class="w-4 h-4" />
                            Clear
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Tasks Table -->
    <x-ui.card class="overflow-hidden">
        <div class="overflow-x-auto">
            <form action="{{ route('admin.tasks.bulk-action') }}" method="POST" id="bulkActionForm">
                @csrf
                <table class="min-w-full divide-y divide-neutral-100">
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
                                Adiutor
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
                    <tbody class="bg-white divide-y divide-neutral-100">
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
                                        <div class="flex items-center gap-2">
                                            <div class="h-8 w-8 rounded-full bg-neutral-100 text-neutral-600 flex items-center justify-center">
                                                <x-lucide-user class="w-4 h-4" />
                                            </div>
                                            <div class="text-sm font-medium text-neutral-900">{{ $task->client->fullName }}</div>
                                        </div>
                                    @else
                                        <span class="text-neutral-400">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($task->assignedUser)
                                        <div class="flex items-center gap-2">
                                            <div class="h-8 w-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                                                <x-lucide-user-cog class="w-4 h-4" />
                                            </div>
                                            <div class="text-sm font-medium text-neutral-900">{{ $task->assignedUser->fullName }}</div>
                                        </div>
                                    @else
                                        <span class="text-neutral-400">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['class' => 'bg-warning-100 text-warning-800', 'icon' => 'clock'],
                                            'in_progress' => ['class' => 'bg-info-100 text-info-800', 'icon' => 'loader'],
                                            'completed' => ['class' => 'bg-success-100 text-success-800', 'icon' => 'check-circle'],
                                            'cancelled' => ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'circle-slash'],
                                        ];
                                        $sConfig = $statusConfig[$task->status] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'circle'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sConfig['class'] }}">
                                        <x-dynamic-component :component="'lucide-' . $sConfig['icon']" class="w-3.5 h-3.5" />
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $priorityConfig = [
                                            'low' => ['class' => 'bg-success-100 text-success-800', 'icon' => 'arrow-down'],
                                            'medium' => ['class' => 'bg-warning-100 text-warning-800', 'icon' => 'minus'],
                                            'high' => ['class' => 'bg-error-100 text-error-800', 'icon' => 'arrow-up'],
                                        ];
                                        $pConfig = $priorityConfig[$task->priority] ?? ['class' => 'bg-neutral-100 text-neutral-800', 'icon' => 'circle'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pConfig['class'] }}">
                                        <x-dynamic-component :component="'lucide-' . $pConfig['icon']" class="w-3.5 h-3.5" />
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
                                                <span class="flex items-center gap-1 text-xs mt-0.5">
                                                    <x-lucide-alert-circle class="w-3 h-3" />
                                                    Overdue
                                                </span>
                                            @elseif($isClose)
                                                <span class="flex items-center gap-1 text-xs mt-0.5">
                                                    <x-lucide-clock class="w-3 h-3" />
                                                    Soon
                                                </span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-neutral-400">Not set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center gap-2 justify-end">
                                        <a href="{{ route('admin.tasks.show', $task->taskID) }}" class="p-1.5 text-neutral-400 hover:text-primary-600 rounded-lg hover:bg-primary-50 transition-colors" title="View">
                                            <x-lucide-eye class="w-4 h-4" />
                                        </a>
                                        <a href="{{ route('admin.tasks.edit', $task->taskID) }}" class="p-1.5 text-neutral-400 hover:text-info-600 rounded-lg hover:bg-info-50 transition-colors" title="Edit">
                                            <x-lucide-pencil class="w-4 h-4" />
                                        </a>
                                        <button type="button" onclick="deleteTask('{{ $task->taskID }}')" class="p-1.5 text-neutral-400 hover:text-error-600 rounded-lg hover:bg-error-50 transition-colors" title="Delete">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center">
                                    <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <x-lucide-list-checks class="w-8 h-8 text-neutral-400" />
                                    </div>
                                    <h3 class="text-neutral-500 text-base">No tasks found</h3>
                                    <p class="text-neutral-400 text-sm mt-1">Try adjusting your search or filter to find what you're looking for</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Bulk Action Controls (Hidden initially) -->
                <div id="bulkActionControls" class="bg-neutral-50 border-t border-neutral-100 p-4 hidden">
                    <div class="flex flex-wrap items-center gap-4">
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
                        
                        <div class="flex gap-2">
                            <x-ui.button type="submit" id="applyBulkAction" variant="primary">
                                Apply
                            </x-ui.button>
                            <x-ui.button type="button" id="cancelBulkAction" variant="secondary">
                                Cancel
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-neutral-100">
            <x-ui.pagination :paginator="$tasks" />
        </div>
    </x-ui.card>
</div>

<!-- Delete Task Confirmation Modal -->
<div id="deleteTaskModal" class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-neutral-100">
            <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                <x-lucide-alert-triangle class="w-5 h-5 text-error-500" />
                Confirm Deletion
            </h3>
        </div>
        <div class="p-6">
            <p class="text-neutral-700">Are you sure you want to delete this task? This action cannot be undone.</p>
        </div>
        <div class="px-6 py-4 bg-neutral-50 rounded-b-2xl flex justify-end gap-2">
            <x-ui.button type="button" id="cancelDeleteTask" variant="secondary">
                Cancel
            </x-ui.button>
            <form id="deleteTaskForm" method="POST">
                @csrf
                @method('DELETE')
                <x-ui.button type="submit" variant="danger" class="inline-flex items-center gap-2">
                    <x-lucide-trash-2 class="w-4 h-4" />
                    Delete
                </x-ui.button>
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
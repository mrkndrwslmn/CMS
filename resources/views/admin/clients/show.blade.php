@extends('admin.layouts.app')

@section('title', 'Client Details' . (isset($client) && is_object($client) ? ' - ' . $client->fullName : ''))
@section('page-title', 'Client Management')

@section('content')
<div class="px-6 py-8" data-client-id="{{ isset($client) && is_object($client) ? $client->id : 0 }}">
    @if(!isset($client) || !is_object($client))
        <div class="bg-error-50 border-l-4 border-error-500 text-error-700 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-error-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h4 class="text-lg font-medium">Client Not Found</h4>
                    <p class="mt-2">The requested client could not be found.</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.clients.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Clients
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 pb-6 border-b border-neutral-200">
        <div class="flex items-center space-x-4">
            <div class="bg-primary-500 h-16 w-16 rounded-full flex items-center justify-center text-white shadow-sm">
                <i class="fas fa-user text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-semibold text-primary-600 mb-1">{{ $client->fullName }}</h1>
                <div class="flex items-center text-neutral-500">
                    <i class="fas fa-envelope mr-2"></i>
                    <span>{{ $client->email }}</span>
                </div>
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <a href="{{ route('admin.clients.edit', $client->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Client
            </a>
            <a href="{{ route('admin.clients.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Client Profile</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div>
                            <div class="text-sm font-medium text-neutral-500 mb-1">Status</div>
                            @if($client->status == 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                    <span class="h-2 w-2 rounded-full bg-success-500 mr-1.5"></span>
                                    Active
                                </span>
                            @elseif($client->status == 'inactive')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                    <span class="h-2 w-2 rounded-full bg-warning-500 mr-1.5"></span>
                                    Inactive
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-800">
                                    <span class="h-2 w-2 rounded-full bg-error-500 mr-1.5"></span>
                                    Banned
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex border-t border-neutral-100 pt-4">
                            <div class="w-10 flex-shrink-0 text-neutral-400">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Full Name</div>
                                <div class="text-neutral-900">{{ $client->fullName }}</div>
                            </div>
                        </div>
                        
                        <div class="flex border-t border-neutral-100 pt-4">
                            <div class="w-10 flex-shrink-0 text-neutral-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Email Address</div>
                                <div class="text-neutral-900 break-all">{{ $client->email }}</div>
                            </div>
                        </div>
                        
                        <div class="flex border-t border-neutral-100 pt-4">
                            <div class="w-10 flex-shrink-0 text-neutral-400">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Phone Number</div>
                                <div class="text-neutral-900">{{ $client->phoneNumber }}</div>
                            </div>
                        </div>
                        
                        <div class="flex border-t border-neutral-100 pt-4">
                            <div class="w-10 flex-shrink-0 text-neutral-400">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Member Since</div>
                                <div class="text-neutral-900">{{ $client->created_at->format('F d, Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex border-t border-neutral-100 pt-4">
                            <div class="w-10 flex-shrink-0 text-neutral-400">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Last Updated</div>
                                <div class="text-neutral-900">{{ $client->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-3">
                        <button onclick="$('#addNoteModal').modal('show')" 
                                class="flex items-center justify-between w-full px-4 py-3 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-lg transition-colors">
                            <span class="flex items-center">
                                <i class="fas fa-sticky-note mr-3"></i>
                                <span>Add Client Note</span>
                            </span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <a href="#" 
                           class="flex items-center justify-between w-full px-4 py-3 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-lg transition-colors">
                            <span class="flex items-center">
                                <i class="fas fa-envelope mr-3"></i>
                                <span>Send Message</span>
                            </span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="#" 
                           class="flex items-center justify-between w-full px-4 py-3 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-lg transition-colors">
                            <span class="flex items-center">
                                <i class="fas fa-tasks mr-3"></i>
                                <span>Create Task</span>
                            </span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="lg:col-span-2">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-primary-500">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    Total Projects
                                </div>
                                <div class="mt-1 text-2xl font-semibold text-neutral-900">
                                    {{ $stats['total_projects'] }}
                                </div>
                            </div>
                            <div class="rounded-full p-3 bg-primary-50 text-primary-500">
                                <i class="fas fa-project-diagram fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-success-500">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    Completed
                                </div>
                                <div class="mt-1 text-2xl font-semibold text-neutral-900">
                                    {{ $stats['completed_projects'] }}
                                </div>
                            </div>
                            <div class="rounded-full p-3 bg-success-50 text-success-500">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-warning-500">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    Active
                                </div>
                                <div class="mt-1 text-2xl font-semibold text-neutral-900">
                                    {{ $stats['active_projects'] }}
                                </div>
                            </div>
                            <div class="rounded-full p-3 bg-warning-50 text-warning-500">
                                <i class="fas fa-tasks fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-info-500">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    Feedback
                                </div>
                                <div class="mt-1 text-2xl font-semibold text-neutral-900">
                                    {{ $stats['total_feedback'] }}
                                </div>
                            </div>
                            <div class="rounded-full p-3 bg-info-50 text-info-500">
                                <i class="fas fa-comments fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tasks -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Recent Tasks</h2>
                    <a href="#" class="text-sm text-primary-500 hover:text-primary-700 flex items-center">
                        <span>View All</span>
                        <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($recentTasks) && !empty($recentTasks) && ((is_array($recentTasks) && count($recentTasks) > 0) || (is_object($recentTasks) && method_exists($recentTasks, 'count') && $recentTasks->count() > 0)))
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-nowrap">
                                <thead>
                                    <tr class="bg-neutral-50 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                        <th class="px-4 py-3 rounded-l-lg">Task</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Priority</th>
                                        <th class="px-4 py-3 rounded-r-lg">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @foreach($recentTasks as $task)
                                    <tr class="hover:bg-neutral-50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-neutral-900">{{ $task->title ?? 'Task #' . $task->id }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($task->status == 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-success-500 mr-1.5"></span>
                                                    Completed
                                                </span>
                                            @elseif($task->status == 'in_progress')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-warning-500 mr-1.5"></span>
                                                    In Progress
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-neutral-500 mr-1.5"></span>
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($task->priority == 'high')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-800">
                                                    <i class="fas fa-arrow-up mr-1 text-xs"></i>
                                                    High
                                                </span>
                                            @elseif($task->priority == 'medium')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                                    <i class="fas fa-minus mr-1 text-xs"></i>
                                                    Medium
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-800">
                                                    <i class="fas fa-arrow-down mr-1 text-xs"></i>
                                                    Low
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-neutral-600">
                                            {{ $task->due_date ? $task->due_date : 'Not set' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tasks text-neutral-400 text-xl"></i>
                            </div>
                            <h3 class="text-neutral-500 text-base">No tasks found for this client</h3>
                            <p class="text-neutral-400 text-sm mt-1">Tasks assigned to this client will appear here</p>
                            <a href="#" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Assign New Task
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Forms -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Recent Forms</h2>
                    <a href="#" class="text-sm text-primary-500 hover:text-primary-700 flex items-center">
                        <span>View All</span>
                        <i class="fas fa-chevron-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($recentForms) && !empty($recentForms) && ((is_array($recentForms) && count($recentForms) > 0) || (is_object($recentForms) && method_exists($recentForms, 'count') && $recentForms->count() > 0)))
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-nowrap">
                                <thead>
                                    <tr class="bg-neutral-50 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                        <th class="px-4 py-3 rounded-l-lg">Form</th>
                                        <th class="px-4 py-3">Type</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 rounded-r-lg">Submitted</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @foreach($recentForms as $form)
                                    <tr class="hover:bg-neutral-50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-neutral-900">{{ $form->title ?? 'Form #' . $form->id }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-neutral-600">
                                            {{ ucfirst($form->type ?? 'general') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($form->status == 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-success-500 mr-1.5"></span>
                                                    Completed
                                                </span>
                                            @elseif($form->status == 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-warning-500 mr-1.5"></span>
                                                    Pending
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-neutral-500 mr-1.5"></span>
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-neutral-600">
                                            {{ $form->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-file-alt text-neutral-400 text-xl"></i>
                            </div>
                            <h3 class="text-neutral-500 text-base">No forms found for this client</h3>
                            <p class="text-neutral-400 text-sm mt-1">Forms submitted by this client will appear here</p>
                            <a href="#" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Create New Form
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Client Notes</h2>
                    <button onclick="$('#addNoteModal').modal('show')" 
                            class="inline-flex items-center px-3 py-1.5 bg-primary-500 hover:bg-primary-600 text-white text-sm rounded-lg transition-colors">
                        <i class="fas fa-plus mr-1"></i>
                        Add Note
                    </button>
                </div>
                <div class="p-6 max-h-[500px] overflow-y-auto">
                    @if(isset($notes) && is_object($notes) && $notes->count() > 0)
                        <div class="space-y-6">
                            @foreach($notes as $note)
                            <div class="bg-white border border-neutral-200 rounded-lg p-5 shadow-sm">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center mb-3">
                                            @if($note->type == 'important')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-800 mr-2">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                                    Important
                                                </span>
                                            @elseif($note->type == 'reminder')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800 mr-2">
                                                    <i class="fas fa-bell mr-1"></i>
                                                    Reminder
                                                </span>
                                            @elseif($note->type == 'issue')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-800 text-white mr-2">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Issue
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-info-100 text-info-800 mr-2">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    General
                                                </span>
                                            @endif
                                            <span class="text-neutral-500 text-xs">{{ $note->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h3 class="text-lg font-medium text-neutral-900 mb-1">{{ $note->title }}</h3>
                                        <p class="text-neutral-600">{{ $note->content }}</p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="p-1.5 rounded-lg hover:bg-neutral-100 text-neutral-500" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item edit-note-btn"
                                               data-note-id="{{ $note->id }}" 
                                               data-note-title="{{ $note->title }}" 
                                               data-note-content="{{ $note->content }}" 
                                               data-note-type="{{ $note->type }}">
                                                <i class="fas fa-edit mr-2"></i>Edit
                                            </a>
                                            <a href="#" class="dropdown-item text-error-600 delete-note-btn" 
                                               data-note-id="{{ $note->id }}">
                                                <i class="fas fa-trash mr-2"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-sticky-note text-neutral-400 text-xl"></i>
                            </div>
                            <h3 class="text-neutral-500 text-base">No notes for this client</h3>
                            <p class="text-neutral-400 text-sm mt-1">Add important information about this client</p>
                            <button onclick="$('#addNoteModal').modal('show')" 
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Add First Note
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-lg border-0">
            <form action="{{ route('admin.clients.notes.store', $client->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-neutral-50 border-b border-neutral-200 px-6 py-4">
                    <h5 class="text-lg font-semibold text-neutral-800">Add Client Note</h5>
                    <button type="button" class="text-neutral-500 hover:text-neutral-700 focus:outline-none" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body p-6">
                    <div class="mb-5">
                        <label for="title" class="block text-sm font-medium text-neutral-700 mb-1">Title</label>
                        <input type="text" name="title" id="title" 
                               class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" 
                               placeholder="Note title" required>
                    </div>
                    <div class="mb-5">
                        <label for="content" class="block text-sm font-medium text-neutral-700 mb-1">Content</label>
                        <textarea name="content" id="content" rows="4" 
                                  class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" 
                                  placeholder="Note details..." required></textarea>
                    </div>
                    <div class="mb-2">
                        <label for="type" class="block text-sm font-medium text-neutral-700 mb-1">Note Type</label>
                        <div class="relative">
                            <select name="type" id="type" 
                                    class="w-full appearance-none rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                    required>
                                <option value="general">General</option>
                                <option value="important">Important</option>
                                <option value="reminder">Reminder</option>
                                <option value="issue">Issue</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-3">
                        <div class="flex items-center text-xs text-neutral-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span>Notes are visible to all administrators</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 mr-3" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Note
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Note Modal -->
<div class="modal fade" id="editNoteModal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content rounded-lg shadow-lg border-0">
            <form id="editNoteForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-neutral-50 border-b border-neutral-200 px-6 py-4">
                    <h5 class="text-lg font-semibold text-neutral-800">Edit Note</h5>
                    <button type="button" class="text-neutral-500 hover:text-neutral-700 focus:outline-none" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body p-6">
                    <div class="mb-5">
                        <label for="edit_title" class="block text-sm font-medium text-neutral-700 mb-1">Title</label>
                        <input type="text" name="title" id="edit_title" 
                               class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" 
                               placeholder="Note title" required>
                    </div>
                    <div class="mb-5">
                        <label for="edit_content" class="block text-sm font-medium text-neutral-700 mb-1">Content</label>
                        <textarea name="content" id="edit_content" rows="4" 
                                  class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" 
                                  placeholder="Note details..." required></textarea>
                    </div>
                    <div class="mb-2">
                        <label for="edit_type" class="block text-sm font-medium text-neutral-700 mb-1">Note Type</label>
                        <div class="relative">
                            <select name="type" id="edit_type" 
                                    class="w-full appearance-none rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50" 
                                    required>
                                <option value="general">General</option>
                                <option value="important">Important</option>
                                <option value="reminder">Reminder</option>
                                <option value="issue">Issue</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-neutral-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-3">
                        <div class="flex items-center text-xs text-neutral-500">
                            <i class="fas fa-clock mr-1"></i>
                            <span>Last edited: <span class="font-medium">Now</span></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 mr-3" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    @endif
</div>

@endsection

@section('scripts')
@if(isset($client) && is_object($client))
<script>
$(document).ready(function() {
    const clientId = $('[data-client-id]').data('client-id') || 0;
    
    // Make sure modals are hidden initially
    $('#addNoteModal').modal('hide');
    $('#editNoteModal').modal('hide');
    
    // Edit note button handler
    $('.edit-note-btn').on('click', function(e) {
        e.preventDefault();
        const noteId = $(this).data('note-id');
        const title = $(this).data('note-title');
        const content = $(this).data('note-content');
        const type = $(this).data('note-type');
        
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_content').value = content;
        document.getElementById('edit_type').value = type;
        document.getElementById('editNoteForm').action = `/admin/clients/${clientId}/notes/${noteId}`;
        $('#editNoteModal').modal('show');
    });
    
    // Delete note button handler
    $('.delete-note-btn').on('click', function(e) {
        e.preventDefault();
        const noteId = $(this).data('note-id');
        
        if (confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/clients/${clientId}/notes/${noteId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
@endif
@endsection
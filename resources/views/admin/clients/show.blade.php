@extends('admin.layouts.app')

@section('title', 'Client Details' . (isset($client) && is_object($client) ? ' - ' . $client->fullName : ''))
@section('page-title', 'Client Management')

@section('content')
<div class="p-6 lg:p-8" x-data="{ addNoteModal: false, editNoteModal: false, editNote: { id: null, title: '', content: '', type: 'general' } }" data-client-id="{{ isset($client) && is_object($client) ? $client->id : 0 }}">
    @if(!isset($client) || !is_object($client))
        <x-ui.card class="border-l-4 border-error-500">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-error-50 rounded-xl">
                        <x-lucide-alert-circle class="w-6 h-6 text-error-500" />
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-neutral-800">Client Not Found</h4>
                        <p class="text-neutral-600 mt-1">The requested client could not be found.</p>
                        <a href="{{ route('admin.clients.index') }}" class="mt-4 inline-block">
                            <x-ui.button variant="secondary" icon="arrow-left">
                                Back to Clients
                            </x-ui.button>
                        </a>
                    </div>
                </div>
            </div>
        </x-ui.card>
    @else
    
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Clients', 'route' => 'admin.clients.index', 'icon' => 'users'],
        ['label' => $client->fullName, 'icon' => 'user'],
    ]" class="mb-6" />

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="bg-primary-100 h-16 w-16 rounded-full flex items-center justify-center">
                <x-lucide-user class="w-8 h-8 text-primary-600" />
            </div>
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">{{ $client->fullName }}</h1>
                <div class="flex items-center gap-1.5 text-neutral-500 mt-1">
                    <x-lucide-mail class="w-4 h-4" />
                    <span class="text-sm">{{ $client->email }}</span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.clients.edit', $client->id) }}">
                <x-ui.button icon="pencil">
                    Edit Client
                </x-ui.button>
            </a>
            <a href="{{ route('admin.clients.index') }}">
                <x-ui.button variant="secondary" icon="arrow-left">
                    Back to List
                </x-ui.button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content Area -->
        <div class="lg:col-span-2">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <x-ui.stat-card 
                    label="Total Projects" 
                    :value="$stats['total_projects']" 
                    icon="folder-kanban"
                />
                <x-ui.stat-card 
                    label="Completed" 
                    :value="$stats['completed_projects']" 
                    icon="check-circle"
                    iconBg="success"
                />
                <x-ui.stat-card 
                    label="Active" 
                    :value="$stats['active_projects']" 
                    icon="clock"
                    iconBg="warning"
                />
                <x-ui.stat-card 
                    label="Feedback" 
                    :value="$stats['total_feedback']" 
                    icon="message-square"
                    iconBg="primary"
                />
            </div>

            <!-- Recent Tasks -->
            <x-ui.card class="mb-6">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-folder-kanban class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Recent Projects</h2>
                    </div>
                    <a href="#" class="text-sm text-primary-600 hover:text-primary-700 flex items-center gap-1 transition-colors">
                        <span>View All</span>
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($recentProjects) && !empty($recentProjects) && ((is_array($recentProjects) && count($recentProjects) > 0) || (is_object($recentProjects) && method_exists($recentProjects, 'count') && $recentProjects->count() > 0)))
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-neutral-50 border-b border-neutral-100">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider rounded-l-lg">Project</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Budget</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider rounded-r-lg">Deadline</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @foreach($recentProjects as $project)
                                    <tr class="hover:bg-neutral-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-neutral-800">{{ $project->title ?? 'Project #' . $project->id }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($project->status == 'completed')
                                                <x-ui.badge type="success">
                                                    <span class="w-1.5 h-1.5 bg-success-500 rounded-full mr-1.5"></span>
                                                    Completed
                                                </x-ui.badge>
                                            @elseif($project->status == 'in_progress')
                                                <x-ui.badge type="warning">
                                                    <span class="w-1.5 h-1.5 bg-warning-500 rounded-full mr-1.5"></span>
                                                    In Progress
                                                </x-ui.badge>
                                            @else
                                                <x-ui.badge type="neutral">
                                                    <span class="w-1.5 h-1.5 bg-neutral-500 rounded-full mr-1.5"></span>
                                                    {{ ucfirst($project->status ?? 'Pending') }}
                                                </x-ui.badge>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-neutral-600 text-sm">
                                            @if($project->budget)
                                                ₱{{ number_format($project->budget, 2) }}
                                            @else
                                                <span class="text-neutral-400">Not set</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-neutral-600 text-sm">
                                            @if($project->deadline)
                                                {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                                            @else
                                                <span class="text-neutral-400">Not set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="folder-kanban"
                            title="No projects found for this client"
                            description="Projects created for this client will appear here"
                        >
                            <a href="#">
                                <x-ui.button icon="plus">
                                    Create New Project
                                </x-ui.button>
                            </a>
                        </x-ui.empty-state>
                    @endif
                </div>
            </x-ui.card>

            <!-- Recent Forms -->
            <x-ui.card class="mb-6">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-file-text class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Recent Forms</h2>
                    </div>
                    <a href="#" class="text-sm text-primary-600 hover:text-primary-700 flex items-center gap-1 transition-colors">
                        <span>View All</span>
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($recentForms) && !empty($recentForms) && ((is_array($recentForms) && count($recentForms) > 0) || (is_object($recentForms) && method_exists($recentForms, 'count') && $recentForms->count() > 0)))
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-neutral-50 border-b border-neutral-100">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider rounded-l-lg">Form</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider rounded-r-lg">Submitted</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @foreach($recentForms as $form)
                                    <tr class="hover:bg-neutral-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-neutral-800">{{ $form->title ?? 'Form #' . $form->id }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-neutral-600 text-sm">
                                            {{ ucfirst($form->type ?? 'general') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($form->status == 'completed')
                                                <x-ui.badge type="success">
                                                    <span class="w-1.5 h-1.5 bg-success-500 rounded-full mr-1.5"></span>
                                                    Completed
                                                </x-ui.badge>
                                            @elseif($form->status == 'pending')
                                                <x-ui.badge type="warning">
                                                    <span class="w-1.5 h-1.5 bg-warning-500 rounded-full mr-1.5"></span>
                                                    Pending
                                                </x-ui.badge>
                                            @else
                                                <x-ui.badge type="neutral">
                                                    <span class="w-1.5 h-1.5 bg-neutral-500 rounded-full mr-1.5"></span>
                                                    Draft
                                                </x-ui.badge>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-neutral-600 text-sm">
                                            {{ $form->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="file-text"
                            title="No forms found for this client"
                            description="Forms submitted by this client will appear here"
                        >
                            <a href="#">
                                <x-ui.button icon="plus">
                                    Create New Form
                                </x-ui.button>
                            </a>
                        </x-ui.empty-state>
                    @endif
                </div>
            </x-ui.card>
        </div>

        <!-- Client Profile Card (Right Sidebar) -->
        <div class="lg:col-span-1">
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-user class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Client Profile</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-5">
                        <div>
                            <div class="text-sm font-medium text-neutral-500 mb-1.5">Status</div>
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
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-user class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Full Name</div>
                                <div class="text-neutral-800">{{ $client->fullName }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-mail class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Email Address</div>
                                <div class="text-neutral-800 break-all">{{ $client->email }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-phone class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Phone Number</div>
                                <div class="text-neutral-800">{{ $client->phoneNumber }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-calendar class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Member Since</div>
                                <div class="text-neutral-800">{{ $client->created_at->format('F d, Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-clock class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Last Updated</div>
                                <div class="text-neutral-800">{{ $client->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
            
            <!-- Quick Actions Card -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-zap class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Quick Actions</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <button type="button" 
                                @click="addNoteModal = true"
                                class="flex items-center justify-between w-full px-4 py-3 bg-neutral-50 hover:bg-primary-50 text-neutral-700 hover:text-primary-700 rounded-xl transition-colors group">
                            <span class="flex items-center gap-3">
                                <x-lucide-sticky-note class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" />
                                <span>Add Client Note</span>
                            </span>
                            <x-lucide-chevron-right class="w-4 h-4 text-neutral-400 group-hover:text-primary-500" />
                        </button>
                        <a href="mailto:{{ $client->email }}" 
                           class="flex items-center justify-between w-full px-4 py-3 bg-neutral-50 hover:bg-primary-50 text-neutral-700 hover:text-primary-700 rounded-xl transition-colors group">
                            <span class="flex items-center gap-3">
                                <x-lucide-mail class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" />
                                <span>Send Email</span>
                            </span>
                            <x-lucide-chevron-right class="w-4 h-4 text-neutral-400 group-hover:text-primary-500" />
                        </a>
                        <a href="tel:{{ $client->phoneNumber }}" 
                           class="flex items-center justify-between w-full px-4 py-3 bg-neutral-50 hover:bg-primary-50 text-neutral-700 hover:text-primary-700 rounded-xl transition-colors group">
                            <span class="flex items-center gap-3">
                                <x-lucide-phone class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" />
                                <span>Call Client</span>
                            </span>
                            <x-lucide-chevron-right class="w-4 h-4 text-neutral-400 group-hover:text-primary-500" />
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Notes Section -->
        <div class="lg:col-span-3">
            <x-ui.card>
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-sticky-note class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Client Notes</h2>
                    </div>
                    <x-ui.button size="sm" icon="plus" @click="addNoteModal = true">
                        Add Note
                    </x-ui.button>
                </div>
                <div class="p-6 max-h-[500px] overflow-y-auto">
                    @if(isset($notes) && is_object($notes) && $notes->count() > 0)
                        <div class="space-y-4">
                            @foreach($notes as $note)
                            <div class="bg-neutral-50 border border-neutral-100 rounded-xl p-5">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-3">
                                            @if($note->type == 'important')
                                                <x-ui.badge type="error">
                                                    <x-lucide-alert-circle class="w-3 h-3 mr-1" />
                                                    Important
                                                </x-ui.badge>
                                            @elseif($note->type == 'reminder')
                                                <x-ui.badge type="warning">
                                                    <x-lucide-bell class="w-3 h-3 mr-1" />
                                                    Reminder
                                                </x-ui.badge>
                                            @elseif($note->type == 'issue')
                                                <x-ui.badge type="neutral">
                                                    <x-lucide-alert-triangle class="w-3 h-3 mr-1" />
                                                    Issue
                                                </x-ui.badge>
                                            @else
                                                <x-ui.badge type="info">
                                                    <x-lucide-info class="w-3 h-3 mr-1" />
                                                    General
                                                </x-ui.badge>
                                            @endif
                                            <span class="text-neutral-500 text-xs">{{ $note->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h3 class="text-base font-medium text-neutral-800 mb-1">{{ $note->title }}</h3>
                                        <p class="text-neutral-600 text-sm">{{ $note->content }}</p>
                                    </div>
                                    <div class="dropdown ml-4">
                                        <button class="p-2 rounded-lg hover:bg-neutral-100 text-neutral-400 hover:text-neutral-600 transition-colors" data-toggle="dropdown">
                                            <x-lucide-more-vertical class="w-4 h-4" />
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item flex items-center gap-2"
                                               @click.prevent="editNote = { id: {{ $note->id }}, title: '{{ addslashes($note->title) }}', content: '{{ addslashes($note->content) }}', type: '{{ $note->type }}' }; editNoteModal = true">
                                                <x-lucide-pencil class="w-4 h-4" />
                                                Edit
                                            </a>
                                            <a href="#" class="dropdown-item text-error-600 delete-note-btn flex items-center gap-2" 
                                               data-note-id="{{ $note->id }}">
                                                <x-lucide-trash-2 class="w-4 h-4" />
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="sticky-note"
                            title="No notes for this client"
                            description="Add important information about this client"
                        >
                            <x-ui.button icon="plus" @click="addNoteModal = true">
                                Add First Note
                            </x-ui.button>
                        </x-ui.empty-state>
                    @endif
                </div>
            </x-ui.card>
        </div>
    </div>

<!-- Add Note Modal -->
<div x-show="addNoteModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-neutral-900/50 transition-opacity" @click="addNoteModal = false"></div>
        
        <!-- Modal Panel -->
        <div class="relative bg-white rounded-2xl shadow-xl border-0 overflow-hidden w-full max-w-lg mx-auto z-10"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop>
            <form action="{{ route('admin.clients.notes.store', $client->id) }}" method="POST">
                @csrf
                <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 flex items-center justify-between">
                    <h5 class="text-lg font-semibold text-neutral-800">Add Client Note</h5>
                    <button type="button" class="p-2 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors" @click="addNoteModal = false">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
                <div class="p-6">
                    <div class="mb-5">
                        <label for="title" class="block text-sm font-medium text-neutral-700 mb-1.5">Title</label>
                        <x-ui.input type="text" name="title" id="title" placeholder="Note title" required />
                    </div>
                    <div class="mb-5">
                        <label for="content" class="block text-sm font-medium text-neutral-700 mb-1.5">Content</label>
                        <textarea name="content" id="content" rows="4" 
                                  class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" 
                                  placeholder="Note details..." required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-neutral-700 mb-1.5">Note Type</label>
                        <x-ui.select name="type" id="type" required>
                            <option value="general">General</option>
                            <option value="important">Important</option>
                            <option value="reminder">Reminder</option>
                            <option value="issue">Issue</option>
                        </x-ui.select>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-neutral-500">
                        <x-lucide-info class="w-3.5 h-3.5" />
                        <span>Notes are visible to all administrators</span>
                    </div>
                </div>
                <div class="bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3">
                    <x-ui.button type="button" variant="secondary" @click="addNoteModal = false">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" icon="plus">
                        Add Note
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Note Modal -->
<div x-show="editNoteModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-neutral-900/50 transition-opacity" @click="editNoteModal = false"></div>
        
        <!-- Modal Panel -->
        <div class="relative bg-white rounded-2xl shadow-xl border-0 overflow-hidden w-full max-w-lg mx-auto z-10"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop>
            <form :action="`/admin/clients/{{ $client->id }}/notes/${editNote.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-neutral-50 border-b border-neutral-100 px-6 py-4 flex items-center justify-between">
                    <h5 class="text-lg font-semibold text-neutral-800">Edit Note</h5>
                    <button type="button" class="p-2 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors" @click="editNoteModal = false">
                        <x-lucide-x class="w-5 h-5" />
                    </button>
                </div>
                <div class="p-6">
                    <div class="mb-5">
                        <label for="edit_title" class="block text-sm font-medium text-neutral-700 mb-1.5">Title</label>
                        <input type="text" name="title" id="edit_title" x-model="editNote.title" 
                               class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                               placeholder="Note title" required />
                    </div>
                    <div class="mb-5">
                        <label for="edit_content" class="block text-sm font-medium text-neutral-700 mb-1.5">Content</label>
                        <textarea name="content" id="edit_content" rows="4" x-model="editNote.content"
                                  class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all" 
                                  placeholder="Note details..." required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="edit_type" class="block text-sm font-medium text-neutral-700 mb-1.5">Note Type</label>
                        <select name="type" id="edit_type" x-model="editNote.type" required
                                class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            <option value="general">General</option>
                            <option value="important">Important</option>
                            <option value="reminder">Reminder</option>
                            <option value="issue">Issue</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-neutral-500">
                        <x-lucide-clock class="w-3.5 h-3.5" />
                        <span>Last edited: <span class="font-medium">Now</span></span>
                    </div>
                </div>
                <div class="bg-neutral-50 border-t border-neutral-100 px-6 py-4 flex justify-end gap-3">
                    <x-ui.button type="button" variant="secondary" @click="editNoteModal = false">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" icon="save">
                        Save Changes
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>

    @endif
</div> <!-- End x-data wrapper -->

@endsection

@section('scripts')
@if(isset($client) && is_object($client))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientId = {{ $client->id }};
    
    // Delete note button handler
    document.querySelectorAll('.delete-note-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const noteId = this.dataset.noteId;
            
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
});
</script>
@endif
@endsection
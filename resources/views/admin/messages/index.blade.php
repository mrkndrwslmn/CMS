@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Messages', 'icon' => 'message-square'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-6">
        <x-ui.page-header 
            title="Messages" 
            description="Manage all project conversations"
        />
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.messages.index') }}" class="flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <x-lucide-search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by project, client name, email, or message content..."
                    class="w-full pl-10 pr-4 py-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                >
            </div>

            <!-- Filters Row -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Status Filter -->
                <select 
                    name="status" 
                    class="px-3 py-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white"
                    onchange="this.form.submit()"
                >
                    <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Messages</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                </select>

                <!-- Project Status Filter -->
                <select 
                    name="project_status" 
                    class="px-3 py-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white"
                    onchange="this.form.submit()"
                >
                    <option value="all" {{ request('project_status') === 'all' || !request('project_status') ? 'selected' : '' }}>All Projects</option>
                    <option value="active" {{ request('project_status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="in_progress" {{ request('project_status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="pending" {{ request('project_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('project_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="on_hold" {{ request('project_status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                </select>

                <!-- Sort -->
                <select 
                    name="sort" 
                    class="px-3 py-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white"
                    onchange="this.form.submit()"
                >
                    <option value="latest" {{ request('sort') === 'latest' || !request('sort') ? 'selected' : '' }}>Latest First</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="unread" {{ request('sort') === 'unread' ? 'selected' : '' }}>Most Unread</option>
                </select>

                <!-- Search Button -->
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">
                    <x-lucide-search class="w-4 h-4" />
                    Search
                </button>

                <!-- Clear Filters -->
                @if(request()->hasAny(['search', 'status', 'project_status', 'sort']))
                    <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-neutral-600 text-sm font-medium rounded-xl hover:bg-neutral-100 transition-colors">
                        <x-lucide-x class="w-4 h-4" />
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Active Filters Display -->
    @if(request()->hasAny(['search', 'status', 'project_status']) && (request('search') || request('status') !== 'all' || request('project_status') !== 'all'))
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="text-sm text-neutral-500">Active filters:</span>
            @if(request('search'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-lg">
                    <x-lucide-search class="w-3 h-3" />
                    "{{ request('search') }}"
                    <a href="{{ route('admin.messages.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="hover:text-primary-900">
                        <x-lucide-x class="w-3 h-3" />
                    </a>
                </span>
            @endif
            @if(request('status') && request('status') !== 'all')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-lg">
                    {{ ucfirst(request('status')) }}
                    <a href="{{ route('admin.messages.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="hover:text-primary-900">
                        <x-lucide-x class="w-3 h-3" />
                    </a>
                </span>
            @endif
            @if(request('project_status') && request('project_status') !== 'all')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-lg">
                    Project: {{ ucfirst(str_replace('_', ' ', request('project_status'))) }}
                    <a href="{{ route('admin.messages.index', array_merge(request()->except('project_status'), ['page' => 1])) }}" class="hover:text-primary-900">
                        <x-lucide-x class="w-3 h-3" />
                    </a>
                </span>
            @endif
        </div>
    @endif

    @php
        // Merge conversations and group chats by project_id for unified view
        $projectChatsArray = [];
        
        // Add direct conversations
        foreach ($conversations as $conv) {
            $projectId = $conv->project_id;
            if (!isset($projectChatsArray[$projectId])) {
                $projectChatsArray[$projectId] = [
                    'project' => $conv->project,
                    'client' => $conv->client,
                    'direct' => null,
                    'group' => null,
                    'latest_activity' => null,
                ];
            }
            $projectChatsArray[$projectId]['direct'] = $conv;
            $latestDirect = $conv->last_message_at;
            if (!$projectChatsArray[$projectId]['latest_activity'] || ($latestDirect && $latestDirect > $projectChatsArray[$projectId]['latest_activity'])) {
                $projectChatsArray[$projectId]['latest_activity'] = $latestDirect;
            }
        }
        
        // Add group chats
        foreach ($groupChats as $gc) {
            $projectId = $gc->project_id;
            if (!isset($projectChatsArray[$projectId])) {
                $projectChatsArray[$projectId] = [
                    'project' => $gc->project,
                    'client' => $gc->project->client ?? null,
                    'direct' => null,
                    'group' => null,
                    'latest_activity' => null,
                ];
            }
            $projectChatsArray[$projectId]['group'] = $gc;
            $latestGroup = $gc->last_message_at;
            if (!$projectChatsArray[$projectId]['latest_activity'] || ($latestGroup && $latestGroup > $projectChatsArray[$projectId]['latest_activity'])) {
                $projectChatsArray[$projectId]['latest_activity'] = $latestGroup;
            }
        }
        
        // Convert to collection and sort by latest activity
        $projectChats = collect($projectChatsArray)->sortByDesc('latest_activity');
        
        // Calculate total unread
        $totalUnread = $conversations->sum('unread_count_admin') + $groupChats->sum('my_unread_count');
    @endphp

    <!-- Conversations Card -->
    <x-ui.card>
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-neutral-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-neutral-800">Project Conversations</h2>
            <div class="flex items-center gap-3">
                @if($totalUnread > 0)
                    <x-ui.badge variant="error">
                        {{ $totalUnread }} Unread
                    </x-ui.badge>
                @endif
                <x-ui.badge variant="primary">
                    {{ $projectChats->count() }} Projects
                </x-ui.badge>
            </div>
        </div>

        <!-- Card Body -->
        <div class="divide-y divide-neutral-200">
            @if($projectChats->isEmpty())
                <div class="px-6 py-16 text-center">
                    @if(request()->hasAny(['search', 'status', 'project_status']))
                        <x-lucide-search-x class="mx-auto w-16 h-16 text-neutral-300 mb-4" />
                        <p class="text-neutral-500 text-base">No conversations found</p>
                        <p class="text-neutral-400 text-sm mt-1">Try adjusting your search or filter criteria</p>
                        <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <x-lucide-x class="w-4 h-4" />
                            Clear Filters
                        </a>
                    @else
                        <x-lucide-message-square class="mx-auto w-16 h-16 text-neutral-300 mb-4" />
                        <p class="text-neutral-500 text-base">No conversations yet</p>
                        <p class="text-neutral-400 text-sm mt-1">Conversations will appear here when clients start messaging</p>
                    @endif
                </div>
            @else
                @foreach($projectChats as $projectId => $data)
                    @php
                        $project = $data['project'];
                        $client = $data['client'];
                        $direct = $data['direct'];
                        $group = $data['group'];
                        $hasUnread = ($direct?->unread_count_admin > 0) || ($group?->my_unread_count > 0);
                    @endphp
                    <div class="px-6 py-5 hover:bg-neutral-50/50 transition-colors {{ $hasUnread ? 'bg-primary-50/20' : '' }}">
                        <!-- Project Header Row -->
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1.5">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary-100 flex-shrink-0">
                                        <x-lucide-folder-kanban class="w-5 h-5 text-primary-600" />
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-base font-semibold text-neutral-800 truncate">
                                            {{ $project->title }}
                                        </h3>
                                        <div class="flex items-center gap-3 text-sm text-neutral-500">
                                            <span class="flex items-center gap-1.5">
                                                <x-lucide-user class="w-3.5 h-3.5" />
                                                {{ $client?->fullName ?? 'No client' }}
                                            </span>
                                            <span class="text-neutral-300">|</span>
                                            <span>Project #{{ $projectId }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Time & Status -->
                            <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                                @if($data['latest_activity'])
                                    <div class="flex items-center gap-1.5 text-xs text-neutral-500">
                                        <x-lucide-clock class="w-3.5 h-3.5" />
                                        {{ $data['latest_activity']->diffForHumans() }}
                                    </div>
                                @endif
                                @if($hasUnread)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-700">
                                        <x-lucide-bell class="w-3 h-3" />
                                        New messages
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Chat Channels Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 ml-13">
                            <!-- Direct Message Channel -->
                            <a href="{{ route('admin.messages.show', $projectId) }}" 
                               class="group flex items-center gap-3 p-3 rounded-xl border transition-all
                                      {{ $direct?->unread_count_admin > 0 
                                         ? 'border-primary-200 bg-primary-50 hover:bg-primary-100' 
                                         : 'border-neutral-200 bg-white hover:bg-neutral-50 hover:border-neutral-300' }}">
                                <div class="flex items-center justify-center w-9 h-9 rounded-lg flex-shrink-0
                                            {{ $direct?->unread_count_admin > 0 ? 'bg-primary-200' : 'bg-neutral-100 group-hover:bg-neutral-200' }}">
                                    <x-lucide-user class="w-4 h-4 {{ $direct?->unread_count_admin > 0 ? 'text-primary-700' : 'text-neutral-500' }}" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-sm font-medium {{ $direct?->unread_count_admin > 0 ? 'text-primary-800' : 'text-neutral-700' }}">
                                            Direct Message
                                        </span>
                                        @if($direct?->unread_count_admin > 0)
                                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-xs font-bold bg-primary-600 text-white">
                                                {{ $direct->unread_count_admin }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($direct?->lastMessage)
                                        <p class="text-xs text-neutral-500 truncate mt-0.5">
                                            {{ Str::limit($direct->lastMessage->message, 50) }}
                                        </p>
                                    @else
                                        <p class="text-xs text-neutral-400 mt-0.5">No messages yet</p>
                                    @endif
                                </div>
                                <x-lucide-chevron-right class="w-4 h-4 text-neutral-400 group-hover:text-neutral-600 flex-shrink-0" />
                            </a>

                            <!-- Group Chat Channel -->
                            <a href="{{ route('admin.messages.show', $projectId) }}?tab=group" 
                               class="group flex items-center gap-3 p-3 rounded-xl border transition-all
                                      {{ $group?->my_unread_count > 0 
                                         ? 'border-success-200 bg-success-50 hover:bg-success-100' 
                                         : 'border-neutral-200 bg-white hover:bg-neutral-50 hover:border-neutral-300' }}">
                                <div class="flex items-center justify-center w-9 h-9 rounded-lg flex-shrink-0
                                            {{ $group?->my_unread_count > 0 ? 'bg-success-200' : 'bg-neutral-100 group-hover:bg-neutral-200' }}">
                                    <x-lucide-users class="w-4 h-4 {{ $group?->my_unread_count > 0 ? 'text-success-700' : 'text-neutral-500' }}" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium {{ $group?->my_unread_count > 0 ? 'text-success-800' : 'text-neutral-700' }}">
                                                Team Chat
                                            </span>
                                            @if($group?->status === 'archived')
                                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-xs bg-neutral-100 text-neutral-500">
                                                    <x-lucide-archive class="w-2.5 h-2.5" />
                                                    Archived
                                                </span>
                                            @endif
                                        </div>
                                        @if($group?->my_unread_count > 0)
                                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-xs font-bold bg-success-600 text-white">
                                                {{ $group->my_unread_count }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($group?->lastMessage)
                                        <p class="text-xs text-neutral-500 truncate mt-0.5">
                                            <span class="font-medium">{{ $group->lastMessage->sender->fullName }}:</span>
                                            {{ Str::limit($group->lastMessage->message, 40) }}
                                        </p>
                                    @elseif($group)
                                        <p class="text-xs text-neutral-400 mt-0.5">{{ $group->members_count }} members</p>
                                    @else
                                        <p class="text-xs text-neutral-400 mt-0.5">No group chat</p>
                                    @endif
                                </div>
                                <x-lucide-chevron-right class="w-4 h-4 text-neutral-400 group-hover:text-neutral-600 flex-shrink-0" />
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination -->
        @if(!$conversations->isEmpty())
            <div class="px-6 py-4 border-t border-neutral-200">
                <x-ui.pagination :paginator="$conversations" />
            </div>
        @endif
    </x-ui.card>
</div>

<script>
    // Update unread count badge in navigation
    document.addEventListener('DOMContentLoaded', async () => {
        if (window.messagingService) {
            await window.messagingService.updateUnreadCount();
        }
    });
</script>
@endsection

@extends('client.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Messages', 'icon' => 'message-square'],
    ]" />

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Messages</h1>
            <p class="text-sm text-neutral-500 mt-1">Communicate with your project team</p>
        </div>
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm px-4 py-3">
            <p class="text-xs text-neutral-500">Total Conversations</p>
            <p class="text-lg font-semibold text-neutral-800">{{ $conversations->total() }}</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('client.messages.index') }}" class="flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <x-lucide-search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search conversations by project name or message..."
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
                    <option value="completed" {{ request('project_status') === 'completed' ? 'selected' : '' }}>Completed</option>
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
                    <a href="{{ route('client.messages.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-neutral-600 text-sm font-medium rounded-xl hover:bg-neutral-100 transition-colors">
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
                    <a href="{{ route('client.messages.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="hover:text-primary-900">
                        <x-lucide-x class="w-3 h-3" />
                    </a>
                </span>
            @endif
            @if(request('status') && request('status') !== 'all')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-lg">
                    {{ ucfirst(request('status')) }}
                    <a href="{{ route('client.messages.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="hover:text-primary-900">
                        <x-lucide-x class="w-3 h-3" />
                    </a>
                </span>
            @endif
            @if(request('project_status') && request('project_status') !== 'all')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-lg">
                    Project: {{ ucfirst(str_replace('_', ' ', request('project_status'))) }}
                    <a href="{{ route('client.messages.index', array_merge(request()->except('project_status'), ['page' => 1])) }}" class="hover:text-primary-900">
                        <x-lucide-x class="w-3 h-3" />
                    </a>
                </span>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
            @if($conversations->isEmpty())
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-8 text-center">
                    <div class="w-14 h-14 bg-neutral-50 rounded-2xl flex items-center justify-center mb-6">
                        @if(request()->hasAny(['search', 'status', 'project_status']))
                            <x-lucide-search-x class="w-7 h-7 text-neutral-400" />
                        @else
                            <x-lucide-message-square class="w-7 h-7 text-neutral-400" />
                        @endif
                    </div>
                    @if(request()->hasAny(['search', 'status', 'project_status']))
                        <h3 class="text-base font-medium text-neutral-700 mb-2">No conversations found</h3>
                        <p class="text-sm text-neutral-500 mb-6 max-w-sm">Try adjusting your search or filter criteria.</p>
                        <a href="{{ route('client.messages.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                            <x-lucide-x class="w-4 h-4" />
                            Clear Filters
                        </a>
                    @else
                        <h3 class="text-base font-medium text-neutral-700 mb-2">No conversations yet</h3>
                        <p class="text-sm text-neutral-500 mb-6 max-w-sm">Messages will appear here when you communicate with our team about your projects.</p>
                        <a href="{{ route('client.dashboard') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Back to Dashboard
                        </a>
                    @endif
                </div>
            @else
                <!-- Conversations List -->
                <div class="divide-y divide-neutral-100">
                    @foreach($conversations as $conversation)
                        <a href="{{ route('client.messages.show', $conversation->project_id) }}" 
                           class="group block p-6 hover:bg-neutral-50 transition-colors {{ $conversation->unread_count_client > 0 ? 'bg-primary-50/50 border-l-4 border-primary-500' : '' }}">
                            
                            <div class="flex items-start justify-between gap-4">
                                <!-- Left Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Project Title & Unread Badge -->
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-base font-medium text-neutral-700 group-hover:text-primary-600 transition-colors truncate">
                                            {{ $conversation->project->title }}
                                        </h3>
                                        @if($conversation->unread_count_client > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-primary-600 text-white text-xs font-medium rounded-full">
                                                {{ $conversation->unread_count_client }} new
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Project Status -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-2 h-2 rounded-full {{ $conversation->project->status === 'completed' ? 'bg-success-500' : 'bg-warning-500' }}"></div>
                                        <span class="text-sm font-medium text-neutral-600">
                                            {{ ucfirst($conversation->project->status) }}
                                        </span>
                                        <span class="text-neutral-300">|</span>
                                        <span class="text-sm text-neutral-500">
                                            Project #{{ $conversation->project_id }}
                                        </span>
                                    </div>

                                    <!-- Last Message -->
                                    @if($conversation->lastMessage)
                                        <div class="flex items-start gap-3">
                                            @if($conversation->lastMessage->sender->profilePic)
                                                <img src="{{ $conversation->lastMessage->sender->getProfilePictureUrl() }}" alt="{{ $conversation->lastMessage->sender->fullName }}" class="w-7 h-7 rounded-full object-cover flex-shrink-0">
                                            @else
                                                <div class="w-7 h-7 bg-neutral-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <span class="text-xs font-medium text-neutral-600">
                                                        {{ substr($conversation->lastMessage->sender->fullName, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-neutral-700 mb-0.5">
                                                    {{ $conversation->lastMessage->sender->fullName }}
                                                </p>
                                                <p class="text-sm text-neutral-500 line-clamp-2">
                                                    {{ Str::limit($conversation->lastMessage->message, 120) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Right Content -->
                                <div class="flex flex-col items-end gap-3 flex-shrink-0">
                                    @if($conversation->last_message_at)
                                        <div class="text-sm text-neutral-400">
                                            {{ $conversation->last_message_at->diffForHumans() }}
                                        </div>
                                    @endif
                                    
                                    <x-lucide-chevron-right class="w-4 h-4 text-neutral-300 group-hover:text-primary-500 transition-colors" />
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($conversations->hasPages())
                    <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-100">
                        <x-ui.pagination :paginator="$conversations" />
                    </div>
                @endif
            @endif
        </div>
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

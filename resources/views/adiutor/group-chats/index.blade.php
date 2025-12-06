@extends('adiutor.layouts.app')

@section('title', 'Group Chats')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Group Chats'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-800 flex items-center gap-3">
            <x-lucide-messages-square class="w-7 h-7 text-primary-600" />
            Project Group Chats
        </h1>
        <p class="text-neutral-500 mt-1">Collaborate with your team on assigned projects</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('adiutor.group-chats.index') }}" class="flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <x-lucide-search class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-neutral-400" />
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by project name or message content..."
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

                <!-- Chat Status Filter -->
                <select 
                    name="chat_status" 
                    class="px-3 py-2.5 border border-neutral-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white"
                    onchange="this.form.submit()"
                >
                    <option value="all" {{ request('chat_status') === 'all' || !request('chat_status') ? 'selected' : '' }}>All Chats</option>
                    <option value="open" {{ request('chat_status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="archived" {{ request('chat_status') === 'archived' ? 'selected' : '' }}>Archived</option>
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
                @if(request()->hasAny(['search', 'status', 'chat_status', 'sort']))
                    <a href="{{ route('adiutor.group-chats.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-neutral-600 text-sm font-medium rounded-xl hover:bg-neutral-100 transition-colors">
                        <x-lucide-x class="w-4 h-4" />
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Active Filters Display -->
    @if(request()->hasAny(['search', 'status', 'chat_status']) && (request('search') || request('status') !== 'all' || request('chat_status') !== 'all'))
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="text-sm text-neutral-500">Active filters:</span>
            @if(request('search'))
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-lg">
                    <x-lucide-search class="w-3 h-3" />
                    "{{ request('search') }}"
                    <a href="{{ route('adiutor.group-chats.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="hover:text-primary-900">
                        <x-lucide-x class="w-3 h-3" />
                    </a>
                </span>
            @endif
            @if(request('status') && request('status') !== 'all')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-lg">
                    {{ ucfirst(request('status')) }}
                    <a href="{{ route('adiutor.group-chats.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="hover:text-primary-900">
                        <x-lucide-x class="w-3 h-3" />
                    </a>
                </span>
            @endif
            @if(request('chat_status') && request('chat_status') !== 'all')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-lg">
                    Chat: {{ ucfirst(request('chat_status')) }}
                    <a href="{{ route('adiutor.group-chats.index', array_merge(request()->except('chat_status'), ['page' => 1])) }}" class="hover:text-primary-900">
                        <x-lucide-x class="w-3 h-3" />
                    </a>
                </span>
            @endif
        </div>
    @endif

    <!-- Group Chats Card -->
    <x-ui.card class="overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-neutral-800">Your Project Chats</h2>
            <x-ui.badge variant="info">
                {{ $groupChats->count() }} {{ $groupChats->count() === 1 ? 'Chat' : 'Chats' }}
            </x-ui.badge>
        </div>

        <!-- Card Body -->
        <div class="divide-y divide-neutral-100">
            @if($groupChats->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="p-4 bg-neutral-100 rounded-full">
                            @if(request()->hasAny(['search', 'status', 'chat_status']))
                                <x-lucide-search-x class="w-12 h-12 text-neutral-400" />
                            @else
                                <x-lucide-messages-square class="w-12 h-12 text-neutral-400" />
                            @endif
                        </div>
                    </div>
                    @if(request()->hasAny(['search', 'status', 'chat_status']))
                        <h3 class="text-lg font-semibold text-neutral-800">No Group Chats Found</h3>
                        <p class="text-neutral-500 text-sm mt-1">Try adjusting your search or filter criteria</p>
                        <a href="{{ route('adiutor.group-chats.index') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <x-lucide-x class="w-4 h-4" />
                            Clear Filters
                        </a>
                    @else
                        <h3 class="text-lg font-semibold text-neutral-800">No Group Chats Yet</h3>
                        <p class="text-neutral-500 text-sm mt-1">You'll see group chats here when you're assigned to projects</p>
                    @endif
                </div>
            @else
                @foreach($groupChats as $groupChat)
                    @php
                        $isArchived = $groupChat->status === 'archived';
                    @endphp
                    <a href="{{ route('adiutor.group-chats.show', $groupChat->project_id) }}" 
                       class="block px-6 py-4 hover:bg-neutral-50 transition-colors duration-150 {{ $groupChat->unread_count > 0 && !$isArchived ? 'bg-success-50/30 border-l-4 border-l-success-600' : '' }} {{ $isArchived ? 'opacity-60' : '' }}">
                        <div class="flex items-start justify-between gap-4">
                            <!-- Left side -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-base font-semibold text-neutral-800 truncate">
                                        {{ $groupChat->project->title }}
                                    </h3>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-success-50 text-success-700">
                                        <x-lucide-users class="w-3 h-3" />
                                        Group · {{ $groupChat->members->count() }} members
                                    </span>
                                    @if($isArchived)
                                        <x-ui.badge variant="default">
                                            <x-lucide-archive class="w-3 h-3" />
                                            Archived
                                        </x-ui.badge>
                                    @elseif($groupChat->unread_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-700 animate-pulse">
                                            {{ $groupChat->unread_count }} New
                                        </span>
                                    @endif
                                </div>
                                
                                @if($groupChat->lastMessage)
                                    <div class="flex items-start gap-2 text-sm">
                                        <p class="text-neutral-600 line-clamp-1">
                                            <span class="font-medium">{{ $groupChat->lastMessage->sender->firstName }}:</span>
                                            {{ $groupChat->lastMessage->message_text ?? 'Sent an attachment' }}
                                        </p>
                                    </div>
                                @else
                                    <p class="text-neutral-500 text-sm italic">No messages yet</p>
                                @endif
                            </div>

                            <!-- Right side -->
                            <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                @if($groupChat->last_message_at)
                                    <span class="text-xs text-neutral-500">
                                        {{ \Carbon\Carbon::parse($groupChat->last_message_at)->diffForHumans() }}
                                    </span>
                                @endif
                                
                                <!-- Member Avatars -->
                                <div class="flex -space-x-2">
                                    @foreach($groupChat->members->take(3) as $member)
                                        @if($member->profilePic)
                                            <img src="{{ $member->getProfilePictureUrl() }}" alt="{{ $member->fullName }}" class="w-6 h-6 rounded-full border-2 border-white object-cover" title="{{ $member->fullName }}">
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-primary-100 border-2 border-white flex items-center justify-center" title="{{ $member->fullName }}">
                                                <span class="text-primary-700 text-xs font-medium">
                                                    {{ substr($member->firstName, 0, 1) }}{{ substr($member->lastName, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if($groupChat->members->count() > 3)
                                        <div class="w-6 h-6 rounded-full bg-neutral-200 border-2 border-white flex items-center justify-center">
                                            <span class="text-neutral-600 text-xs font-medium">
                                                +{{ $groupChat->members->count() - 3 }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </x-ui.card>
</div>
@endsection

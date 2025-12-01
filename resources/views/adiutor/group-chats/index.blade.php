@extends('adiutor.layouts.app')

@section('title', 'Group Chats')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900 flex items-center gap-3">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
            </svg>
            Project Group Chats
        </h1>
        <p class="text-neutral-600 mt-2">Collaborate with your team on assigned projects</p>
    </div>

    <!-- Group Chats Card -->
    <div class="glass-card overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-neutral-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-neutral-900">Your Project Chats</h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                {{ $groupChats->count() }} {{ $groupChats->count() === 1 ? 'Chat' : 'Chats' }}
            </span>
        </div>

        <!-- Card Body -->
        <div class="divide-y divide-neutral-200">
            @if($groupChats->isEmpty())
                <div class="px-6 py-16 text-center">
                    <svg class="mx-auto h-16 w-16 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                    </svg>
                    <p class="text-neutral-500 text-base">No Group Chats Yet</p>
                    <p class="text-neutral-400 text-sm mt-1">You'll see group chats here when you're assigned to projects</p>
                </div>
            @else
                @foreach($groupChats as $groupChat)
                    @php
                        $isArchived = $groupChat->status === 'archived';
                    @endphp
                    <a href="{{ route('adiutor.group-chats.show', $groupChat->project_id) }}" 
                       class="block px-6 py-4 hover:bg-neutral-50 transition-colors duration-150 {{ $groupChat->unread_count > 0 && !$isArchived ? 'bg-green-50/30 border-l-4 border-l-green-600' : '' }} {{ $isArchived ? 'opacity-60' : '' }}">
                        <div class="flex items-start justify-between gap-4">
                            <!-- Left side -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-base font-semibold text-neutral-900 truncate">
                                        {{ $groupChat->project->title }}
                                    </h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                        </svg>
                                        Group · {{ $groupChat->members->count() }} members
                                    </span>
                                    @if($isArchived)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Archived
                                        </span>
                                    @elseif($groupChat->unread_count > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
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
                                        <div class="w-6 h-6 rounded-full bg-primary-100 border-2 border-white flex items-center justify-center" title="{{ $member->fullName }}">
                                            <span class="text-primary-700 text-xs font-medium">
                                                {{ substr($member->firstName, 0, 1) }}{{ substr($member->lastName, 0, 1) }}
                                            </span>
                                        </div>
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
    </div>
</div>
@endsection

@extends('adiutor.layouts.app')

@section('title', 'Group Chats')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Group Chats'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800 flex items-center gap-3">
            <x-lucide-messages-square class="w-7 h-7 text-primary-600" />
            Project Group Chats
        </h1>
        <p class="text-neutral-500 mt-1">Collaborate with your team on assigned projects</p>
    </div>

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
                            <x-lucide-messages-square class="w-12 h-12 text-neutral-400" />
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-800">No Group Chats Yet</h3>
                    <p class="text-neutral-500 text-sm mt-1">You'll see group chats here when you're assigned to projects</p>
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
    </x-ui.card>
</div>
@endsection

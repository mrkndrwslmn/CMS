@extends('client.layout')

@section('title', 'Messages')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-neutral-50 to-primary-50/30">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm border-b border-neutral-200/60">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-900 mb-1">Messages</h1>
                    <p class="text-neutral-600 text-sm">Communicate with your project team</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-sm text-neutral-500">Total Conversations</div>
                        <div class="text-lg font-semibold text-primary-600">{{ $conversations->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-6 pt-6">
            <div class="bg-success-50 border border-success-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-success-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-success-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl border border-neutral-200/60 shadow-sm overflow-hidden">
            @if($conversations->isEmpty())
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16 px-8 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-accent-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-2">No conversations yet</h3>
                    <p class="text-neutral-600 mb-6 max-w-sm">Messages will appear here when you communicate with our team about your projects.</p>
                    <a href="{{ route('client.dashboard') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            @else
                <!-- Conversations List -->
                <div class="divide-y divide-neutral-100">
                    @foreach($conversations as $conversation)
                        <a href="{{ route('client.messages.show', $conversation->project_id) }}" 
                           class="group block p-6 hover:bg-gradient-to-r hover:from-primary-50/50 hover:to-accent-50/30 transition-all duration-200 {{ $conversation->unread_count_client > 0 ? 'bg-gradient-to-r from-primary-50/70 to-accent-50/40 border-l-4 border-primary-500' : '' }}">
                            
                            <div class="flex items-start justify-between gap-4">
                                <!-- Left Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Project Title & Unread Badge -->
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-semibold text-neutral-900 group-hover:text-primary-700 transition-colors duration-200 truncate">
                                            {{ $conversation->project->title }}
                                        </h3>
                                        @if($conversation->unread_count_client > 0)
                                            <span class="inline-flex items-center px-2.5 py-1 bg-primary-600 text-white text-xs font-medium rounded-full">
                                                {{ $conversation->unread_count_client }} new
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Project Status -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-2 h-2 rounded-full {{ $conversation->project->status === 'completed' ? 'bg-success-500' : 'bg-accent-500' }}"></div>
                                        <span class="text-sm font-medium text-neutral-700">
                                            {{ ucfirst($conversation->project->status) }}
                                        </span>
                                        <span class="text-neutral-400">•</span>
                                        <span class="text-sm text-neutral-600">
                                            Project #{{ $conversation->project_id }}
                                        </span>
                                    </div>

                                    <!-- Last Message -->
                                    @if($conversation->lastMessage)
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-neutral-100 to-neutral-200 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span class="text-xs font-medium text-neutral-600">
                                                    {{ substr($conversation->lastMessage->sender->fullName, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-neutral-800 mb-1">
                                                    {{ $conversation->lastMessage->sender->fullName }}
                                                </p>
                                                <p class="text-sm text-neutral-600 line-clamp-2 leading-relaxed">
                                                    {{ Str::limit($conversation->lastMessage->message, 120) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Right Content -->
                                <div class="flex flex-col items-end gap-3 flex-shrink-0">
                                    @if($conversation->last_message_at)
                                        <div class="text-sm text-neutral-500 text-right">
                                            {{ $conversation->last_message_at->diffForHumans() }}
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-neutral-400 group-hover:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($conversations->hasPages())
                    <div class="px-6 py-4 bg-neutral-50/50 border-t border-neutral-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-neutral-600">
                                Showing {{ $conversations->firstItem() }}-{{ $conversations->lastItem() }} of {{ $conversations->total() }} conversations
                            </div>
                            <div class="flex items-center gap-2">
                                {{ $conversations->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
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

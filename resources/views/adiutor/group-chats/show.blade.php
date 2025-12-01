@extends('adiutor.layouts.app')

@section('title', 'Group Chat - ' . $project->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a href="{{ route('adiutor.group-chats.index') }}" class="flex items-center gap-2 text-neutral-600 hover:text-primary-600 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                    </svg>
                    Group Chats
                </a>
            </li>
            <li>
                <svg class="w-4 h-4 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-neutral-900 font-medium truncate">{{ $project->title }}</li>
        </ol>
    </nav>

    <!-- Chat Container -->
    <div class="glass-card overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-6 h-6 text-white/90" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                        <h1 class="text-xl font-bold text-white truncate">{{ $project->title }}</h1>
                        @if($groupChat->status === 'archived')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-white/20 text-white">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Archived
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 text-sm text-white/80">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                            </svg>
                            {{ $groupChat->members->count() }} Team Members
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                            </svg>
                            Project #{{ $project->id }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('adiutor.projects.show', $project->id) }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors text-sm font-medium backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    View Project
                </a>
            </div>
        </div>

        <!-- Team Members Info -->
        <div class="border-b border-neutral-200 bg-neutral-50 px-6 py-3">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-neutral-700">Team:</span>
                <div class="flex -space-x-2">
                    @foreach($groupChat->members as $member)
                        <div class="relative group">
                            <div class="w-8 h-8 rounded-full bg-primary-100 border-2 border-white flex items-center justify-center">
                                <span class="text-primary-700 text-xs font-medium">
                                    {{ substr($member->firstName, 0, 1) }}{{ substr($member->lastName, 0, 1) }}
                                </span>
                            </div>
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-neutral-900 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
                                {{ $member->fullName }}
                                @if($member->role === 'admin')
                                    <span class="text-primary-300">(Admin)</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="messages-container" class="h-[600px] overflow-y-auto bg-neutral-50 p-6 scroll-smooth">
            @if($messages->isEmpty())
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-neutral-500 text-base">No messages yet</p>
                        <p class="text-neutral-400 text-sm mt-1">Start the conversation with your team</p>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($messages as $message)
                        @php
                            $isOwnMessage = $message->sender_id === $user->id;
                        @endphp
                        <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-2xl {{ $isOwnMessage ? 'items-end' : 'items-start' }} flex flex-col">
                                <!-- Sender Info -->
                                @if(!$isOwnMessage)
                                    <div class="flex items-center gap-2 mb-1 px-4">
                                        <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center">
                                            <span class="text-primary-700 text-xs font-medium">
                                                {{ substr($message->sender->firstName, 0, 1) }}{{ substr($message->sender->lastName, 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="text-sm font-medium text-neutral-700">{{ $message->sender->fullName }}</span>
                                        @if($message->sender->role === 'admin')
                                            <span class="text-xs text-primary-600 font-medium">(Admin)</span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Message Bubble -->
                                <div class="relative group">
                                    <div class="px-4 py-3 rounded-2xl {{ $isOwnMessage ? 'bg-primary-600 text-white' : 'bg-white text-neutral-900 border border-neutral-200' }}">
                                        <p class="text-sm whitespace-pre-wrap break-words">{{ $message->message_text }}</p>
                                    </div>
                                    <div class="mt-1 px-4 text-xs text-neutral-500">
                                        {{ \Carbon\Carbon::parse($message->created_at)->format('M j, Y g:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Message Input -->
        <div class="border-t border-neutral-200 bg-white p-4">
            @if($groupChat->status === 'archived')
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-neutral-400 mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-neutral-600 font-medium">This chat has been archived</p>
                    <p class="text-neutral-500 text-sm mt-1">No new messages can be sent</p>
                </div>
            @else
                <form id="message-form" onsubmit="sendMessage(event)">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <textarea 
                                id="message-textarea"
                                name="message" 
                                rows="3" 
                                placeholder="Type your message... (Press Enter to send, Shift+Enter for new line)"
                                required
                                maxlength="5000"
                                onkeydown="handleKeyPress(event)"
                                oninput="updateCharCount()"
                                class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none text-sm placeholder-neutral-400"></textarea>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-xs text-neutral-500">
                                    <span id="char-count" class="font-medium">0</span>/5000 characters
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end">
                            <button type="submit" id="send-btn" class="btn btn-primary inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Send Message
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
let projectId = {{ $project->id }};
let groupChatId = {{ $groupChat->id }};
let isArchived = {{ $groupChat->status === 'archived' ? 'true' : 'false' }};

// Auto-scroll to bottom on load
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});

// Character counter
function updateCharCount() {
    const textarea = document.getElementById('message-textarea');
    const charCount = document.getElementById('char-count');
    charCount.textContent = textarea.value.length;
}

// Handle Enter key to send
function handleKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        document.getElementById('message-form').dispatchEvent(new Event('submit'));
    }
}

// Send message function
function sendMessage(event) {
    event.preventDefault();
    
    if (isArchived) {
        return;
    }
    
    const form = event.target;
    const messageText = form.message.value.trim();
    const sendBtn = document.getElementById('send-btn');
    
    if (!messageText) {
        return;
    }
    
    // Disable button to prevent double submission
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';
    
    // Send via API
    fetch(`/api/group-chats/${groupChatId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            message: messageText
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            form.reset();
            updateCharCount();
            // Reload to show new message
            window.location.reload();
        } else {
            alert('Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the message');
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg> Send Message';
    });
}

function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
}
</script>
@endsection

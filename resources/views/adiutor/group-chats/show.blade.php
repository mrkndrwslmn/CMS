@extends('adiutor.layouts.app')

@section('title', 'Group Chat - ' . $project->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Group Chats', 'url' => route('adiutor.group-chats.index')],
        ['label' => $project->title],
    ]" class="mb-6" />

    <!-- Chat Container -->
    <x-ui.card class="overflow-hidden">
        <!-- Header -->
        <div class="bg-white border-b border-neutral-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-primary-50 rounded-xl">
                            <x-lucide-users class="w-5 h-5 text-primary-600" />
                        </div>
                        <h1 class="text-xl font-semibold text-neutral-800 truncate">{{ $project->title }}</h1>
                        @if($groupChat->status === 'archived')
                            <x-ui.badge variant="default">
                                <x-lucide-archive class="w-3 h-3" />
                                Archived
                            </x-ui.badge>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 text-sm text-neutral-500">
                        <div class="flex items-center gap-1.5">
                            <x-lucide-users class="w-4 h-4" />
                            {{ $groupChat->members->count() }} Team Members
                        </div>
                        <div class="flex items-center gap-1.5">
                            <x-lucide-folder class="w-4 h-4" />
                            Project #{{ $project->id }}
                        </div>
                    </div>
                </div>
                <x-ui.button href="{{ route('adiutor.projects.show', $project->id) }}" variant="secondary" size="sm">
                    <x-lucide-external-link class="w-4 h-4" />
                    View Project
                </x-ui.button>
            </div>
        </div>

        <!-- Team Members Info -->
        <div class="border-b border-neutral-100 bg-neutral-50 px-6 py-3">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-neutral-600">Team:</span>
                <div class="flex -space-x-2">
                    @foreach($groupChat->members as $member)
                        <div class="relative group">
                            <div class="w-8 h-8 rounded-full bg-primary-50 border-2 border-white flex items-center justify-center">
                                <span class="text-primary-600 text-xs font-medium">
                                    {{ substr($member->fullName, 0, 1) }}
                                </span>
                            </div>
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-neutral-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10">
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
                        <div class="flex justify-center mb-4">
                            <div class="p-4 bg-neutral-100 rounded-full">
                                <x-lucide-message-circle class="w-12 h-12 text-neutral-400" />
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-neutral-800">No messages yet</h3>
                        <p class="text-neutral-500 text-sm mt-1">Start the conversation with your team</p>
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
                                        <div class="w-6 h-6 rounded-full bg-primary-50 flex items-center justify-center">
                                            <span class="text-primary-600 text-xs font-medium">
                                                {{ substr($message->sender->fullName, 0, 1) }}
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
                                    <div class="px-4 py-3 rounded-2xl {{ $isOwnMessage ? 'bg-primary-600 text-white' : 'bg-white text-neutral-800 border border-neutral-200' }}">
                                        <p class="text-sm whitespace-pre-wrap break-words">{{ $message->message }}</p>
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
        <div class="border-t border-neutral-100 bg-white p-4">
            @if($groupChat->status === 'archived')
                <div class="text-center py-8">
                    <div class="flex justify-center mb-3">
                        <div class="p-3 bg-neutral-100 rounded-full">
                            <x-lucide-archive class="w-8 h-8 text-neutral-400" />
                        </div>
                    </div>
                    <p class="text-neutral-700 font-medium">This chat has been archived</p>
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
                                class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 resize-none text-sm placeholder-neutral-400 transition-colors"></textarea>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-xs text-neutral-500">
                                    <span id="char-count" class="font-medium">0</span>/5000 characters
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end">
                            <x-ui.button type="submit" id="send-btn" variant="primary">
                                <x-lucide-send class="w-4 h-4" />
                                Send Message
                            </x-ui.button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </x-ui.card>
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
            // Reset form
            form.reset();
            updateCharCount();
            
            // Append new message to the chat
            const messagesContainer = document.getElementById('messages-container');
            const messagesDiv = messagesContainer.querySelector('.space-y-4') || createMessagesDiv();
            
            messagesDiv.insertAdjacentHTML('beforeend', createMessageElement(data.message));
            scrollToBottom();
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

// Create messages div if it doesn't exist
function createMessagesDiv() {
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.innerHTML = '<div class="space-y-4"></div>';
    return messagesContainer.querySelector('.space-y-4');
}

// Create message element
function createMessageElement(message) {
    const currentUserId = {{ $user->id }};
    const isOwnMessage = message.sender_id === currentUserId;
    const alignClass = isOwnMessage ? 'justify-end' : 'justify-start';
    const itemsClass = isOwnMessage ? 'items-end' : 'items-start';
    const bubbleClass = isOwnMessage ? 'bg-primary-600 text-white' : 'bg-white text-neutral-800 border border-neutral-200';
    
    let senderInfo = '';
    if (!isOwnMessage && message.sender) {
        const initial = message.sender.fullName.substring(0, 1);
        const roleTag = message.sender.role === 'admin' ? '<span class="text-xs text-primary-600 font-medium">(Admin)</span>' : '';
        senderInfo = `
            <div class="flex items-center gap-2 mb-1 px-4">
                <div class="w-6 h-6 rounded-full bg-primary-50 flex items-center justify-center">
                    <span class="text-primary-600 text-xs font-medium">${initial}</span>
                </div>
                <span class="text-sm font-medium text-neutral-700">${escapeHtml(message.sender.fullName)}</span>
                ${roleTag}
            </div>
        `;
    }
    
    const timestamp = formatMessageTime(message.created_at);
    
    return `
        <div class="flex ${alignClass}" data-message-id="${message.id}">
            <div class="max-w-2xl ${itemsClass} flex flex-col">
                ${senderInfo}
                <div class="relative group">
                    <div class="px-4 py-3 rounded-2xl ${bubbleClass}">
                        <p class="text-sm whitespace-pre-wrap break-words">${escapeHtml(message.message)}</p>
                    </div>
                    <div class="mt-1 px-4 text-xs text-neutral-500">
                        ${timestamp}
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Format message time
function formatMessageTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const options = { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' };
    return date.toLocaleString('en-US', options);
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
}

// Load messages from API
async function loadMessages() {
    try {
        const response = await fetch(`/api/group-chats/${groupChatId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.messages && data.messages.data) {
            const messagesContainer = document.getElementById('messages-container');
            const messagesDiv = messagesContainer.querySelector('.space-y-4');
            
            if (messagesDiv) {
                // Check if we have new messages
                const existingMessages = messagesDiv.querySelectorAll('[data-message-id]');
                const existingIds = Array.from(existingMessages).map(el => el.dataset.messageId);
                const newMessages = data.messages.data.filter(msg => !existingIds.includes(msg.id.toString()));
                
                // Append only new messages
                if (newMessages.length > 0) {
                    newMessages.forEach(message => {
                        messagesDiv.insertAdjacentHTML('beforeend', createMessageElement(message));
                    });
                    scrollToBottom();
                }
            }
        }
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

// Start polling for new messages every 5 seconds
setInterval(loadMessages, 5000);

</script>
@endsection

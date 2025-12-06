@extends('adiutor.layouts.app')

@section('title', 'Group Chat - ' . $project->title)

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                            @if($member->profilePic)
                                <img src="{{ $member->getProfilePictureUrl() }}" alt="{{ $member->fullName }}" class="w-8 h-8 rounded-full border-2 border-white object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-primary-50 border-2 border-white flex items-center justify-center">
                                    <span class="text-primary-600 text-xs font-medium">
                                        {{ substr($member->fullName, 0, 1) }}
                                    </span>
                                </div>
                            @endif
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
                        <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                            <div class="max-w-2xl {{ $isOwnMessage ? 'items-end' : 'items-start' }} flex flex-col">
                                <!-- Sender Info -->
                                @if(!$isOwnMessage)
                                    <div class="flex items-center gap-2 mb-1 px-4">
                                        @if($message->sender->profilePic)
                                            <img src="{{ $message->sender->getProfilePictureUrl() }}" alt="{{ $message->sender->fullName }}" class="w-6 h-6 rounded-full object-cover">
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-primary-50 flex items-center justify-center">
                                                <span class="text-primary-600 text-xs font-medium">
                                                    {{ substr($message->sender->fullName, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        <span class="text-sm font-medium text-neutral-700">{{ $message->sender->fullName }}</span>
                                        @if($message->sender->role === 'admin')
                                            <span class="text-xs text-primary-600 font-medium">(Admin)</span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Message Bubble -->
                                <div class="relative group">
                                    <div class="px-4 py-3 rounded-2xl {{ $isOwnMessage ? 'bg-primary-600 text-white' : 'bg-white text-neutral-800 border border-neutral-200' }}">
                                        <p class="text-sm whitespace-pre-wrap break-words">{!! \App\Services\MessagingService::formatMentions($message->message, $isOwnMessage) !!}</p>
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
                        <div class="relative">
                            <textarea 
                                id="message-textarea"
                                name="message" 
                                rows="3" 
                                placeholder="Type your message... (Press Enter to send, Shift+Enter for new line, @ to mention)"
                                required
                                maxlength="5000"
                                class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 resize-none text-sm placeholder-neutral-400 transition-colors"></textarea>
                            
                            <!-- Mention Autocomplete Dropdown -->
                            <div id="mention-dropdown" class="hidden absolute bottom-full left-0 mb-2 z-50 bg-white border border-neutral-200 rounded-xl shadow-lg max-h-60 overflow-y-auto w-64">
                                <div class="p-2 text-xs font-medium text-neutral-500 border-b border-neutral-100">
                                    <span class="flex items-center gap-1">
                                        <x-lucide-at-sign class="w-3.5 h-3.5" />
                                        Mention someone
                                    </span>
                                </div>
                                <div id="mention-list" class="py-1">
                                    <!-- Members will be populated here -->
                                </div>
                            </div>
                            
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

<style>
/* Mention tag styling */
.mention-tag {
    display: inline;
    text-decoration: none;
    border-radius: 0.25rem;
}

.mention-tag:hover {
    text-decoration: underline;
}

/* Mention dropdown item hover state */
.mention-item:hover, .mention-item.selected {
    background-color: rgb(243 244 246);
}
</style>

<script>
let projectId = {{ $project->id }};
let groupChatId = {{ $groupChat->id }};
let isArchived = {{ $groupChat->status === 'archived' ? 'true' : 'false' }};

// Group chat members for mentions
@php
    $membersData = $groupChat->members->map(function($m) {
        return [
            'id' => $m->id,
            'name' => $m->fullName,
            'pic' => $m->profilePic ? $m->getProfilePictureUrl() : null,
            'role' => $m->role
        ];
    })->toArray();
@endphp
const groupChatMembers = @json($membersData);

// Mention system variables
let mentionedUsers = [];
let mentionDropdownOpen = false;
let mentionSearchStart = -1;
let selectedMentionIndex = 0;

const currentUserId = {{ $user->id }};

// These will be initialized after DOM is ready
let messageTextarea, mentionDropdown, mentionList;

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements first
    messageTextarea = document.getElementById('message-textarea');
    mentionDropdown = document.getElementById('mention-dropdown');
    mentionList = document.getElementById('mention-list');
    
    // Scroll to bottom
    scrollToBottom();
    
    // Initialize mention system
    if (messageTextarea && mentionDropdown && mentionList) {
        // Handle textarea input for @ detection
        messageTextarea.addEventListener('input', handleMentionInput);
        
        // Handle keyboard navigation in mention dropdown
        messageTextarea.addEventListener('keydown', handleMentionKeydown);
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (mentionDropdown && !mentionDropdown.contains(e.target) && e.target !== messageTextarea) {
                closeMentionDropdown();
            }
        });
        
        console.log('Mention system initialized', { 
            members: groupChatMembers.length,
            textarea: !!messageTextarea,
            dropdown: !!mentionDropdown
        });
    } else {
        console.warn('Mention system could not be initialized - elements not found');
    }
});

// Handle input for mention detection
function handleMentionInput(e) {
    const charCount = document.getElementById('char-count');
    charCount.textContent = e.target.value.length;
    
    const textarea = e.target;
    const cursorPos = textarea.selectionStart;
    const textBeforeCursor = textarea.value.substring(0, cursorPos);
    
    // Check if we're in a mention (after @ and before space)
    const lastAtIndex = textBeforeCursor.lastIndexOf('@');
    
    if (lastAtIndex !== -1) {
        const textAfterAt = textBeforeCursor.substring(lastAtIndex + 1);
        
        // Check if there's a space after the @, which would mean the mention is complete
        if (!textAfterAt.includes(' ') && !textAfterAt.includes('\n')) {
            mentionSearchStart = lastAtIndex;
            openMentionDropdown(textAfterAt);
            return;
        }
    }
    
    closeMentionDropdown();
}

// Handle keyboard navigation
function handleMentionKeydown(e) {
    if (!mentionDropdownOpen) {
        // Original enter-to-send behavior
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('message-form').dispatchEvent(new Event('submit'));
        }
        return;
    }
    
    const items = mentionList.querySelectorAll('.mention-item');
    
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedMentionIndex = Math.min(selectedMentionIndex + 1, items.length - 1);
        updateMentionSelection();
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedMentionIndex = Math.max(selectedMentionIndex - 1, 0);
        updateMentionSelection();
    } else if (e.key === 'Enter' || e.key === 'Tab') {
        e.preventDefault();
        const selectedItem = items[selectedMentionIndex];
        if (selectedItem) {
            const userId = parseInt(selectedItem.dataset.userId);
            const userName = selectedItem.dataset.userName;
            selectMention(userId, userName);
        }
    } else if (e.key === 'Escape') {
        e.preventDefault();
        closeMentionDropdown();
    }
}

// Open mention dropdown and filter members
function openMentionDropdown(searchTerm = '') {
    if (!groupChatMembers.length) return;
    
    const filteredMembers = groupChatMembers.filter(member => {
        // Don't show current user in mentions
        if (member.id === currentUserId) return false;
        // Filter by search term
        if (searchTerm) {
            return member.name.toLowerCase().includes(searchTerm.toLowerCase());
        }
        return true;
    });
    
    if (filteredMembers.length === 0) {
        closeMentionDropdown();
        return;
    }
    
    // Build dropdown content
    mentionList.innerHTML = filteredMembers.map((member, index) => `
        <div class="mention-item flex items-center gap-3 px-3 py-2 cursor-pointer ${index === selectedMentionIndex ? 'selected' : ''}"
             data-user-id="${member.id}"
             data-user-name="${escapeHtml(member.name)}"
             onclick="selectMention(${member.id}, '${escapeHtml(member.name).replace(/'/g, "\\'")}')">
            ${member.pic 
                ? `<img src="${escapeHtml(member.pic)}" alt="${escapeHtml(member.name)}" class="w-8 h-8 rounded-full border border-neutral-200 object-cover">`
                : `<div class="w-8 h-8 rounded-full bg-primary-50 border border-neutral-200 flex items-center justify-center">
                    <span class="text-primary-600 text-xs font-medium">
                        ${escapeHtml(member.name.substring(0, 1))}
                    </span>
                </div>`
            }
            <div class="flex-1 min-w-0">
                <div class="font-medium text-sm text-neutral-900 truncate">${escapeHtml(member.name)}</div>
                <div class="text-xs text-neutral-500 capitalize">${member.role}</div>
            </div>
        </div>
    `).join('');
    
    mentionDropdown.classList.remove('hidden');
    mentionDropdownOpen = true;
    selectedMentionIndex = 0;
    updateMentionSelection();
}

// Close mention dropdown
function closeMentionDropdown() {
    mentionDropdown.classList.add('hidden');
    mentionDropdownOpen = false;
    mentionSearchStart = -1;
    selectedMentionIndex = 0;
}

// Update visual selection in dropdown
function updateMentionSelection() {
    const items = mentionList.querySelectorAll('.mention-item');
    items.forEach((item, index) => {
        if (index === selectedMentionIndex) {
            item.classList.add('selected');
        } else {
            item.classList.remove('selected');
        }
    });
}

// Select a mention from dropdown
function selectMention(userId, userName) {
    const textarea = messageTextarea;
    const cursorPos = textarea.selectionStart;
    const textBefore = textarea.value.substring(0, mentionSearchStart);
    const textAfter = textarea.value.substring(cursorPos);
    
    // Insert the mention
    const mentionText = `@${userName} `;
    textarea.value = textBefore + mentionText + textAfter;
    
    // Add user to mentioned list if not already there
    if (!mentionedUsers.includes(userId)) {
        mentionedUsers.push(userId);
    }
    
    // Set cursor position after the mention
    const newCursorPos = mentionSearchStart + mentionText.length;
    textarea.setSelectionRange(newCursorPos, newCursorPos);
    textarea.focus();
    
    // Update character count
    document.getElementById('char-count').textContent = textarea.value.length;
    
    closeMentionDropdown();
}

// Parse mentions from message text
function parseMentionsFromText(text) {
    const mentionPattern = /@([^@\s]+(?:\s[^@\s]+)*?)(?=\s|$|@)/g;
    const foundMentions = [];
    let match;
    
    while ((match = mentionPattern.exec(text)) !== null) {
        const mentionName = match[1].trim();
        // Find matching user
        const user = groupChatMembers.find(m => 
            m.name.toLowerCase() === mentionName.toLowerCase()
        );
        if (user && !foundMentions.includes(user.id)) {
            foundMentions.push(user.id);
        }
    }
    
    return foundMentions;
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
    
    // Parse mentions from the final message text
    const finalMentions = parseMentionsFromText(messageText);
    // Merge with tracked mentions and dedupe
    const allMentions = [...new Set([...mentionedUsers, ...finalMentions])];
    
    // Disable button to prevent double submission
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';
    
    // Build request body with mentions
    const requestBody = {
        message: messageText
    };
    if (allMentions.length > 0) {
        requestBody.mentions = allMentions;
    }
    
    // Send via API
    fetch(`/api/group-chats/${groupChatId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(requestBody)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset form and mentions
            form.reset();
            document.getElementById('char-count').textContent = '0';
            mentionedUsers = [];
            
            // Append new message to the chat
            const messagesContainer = document.getElementById('messages-container');
            const messagesDiv = messagesContainer.querySelector('.space-y-4') || createMessagesDiv();
            
            messagesDiv.insertAdjacentHTML('beforeend', createMessageElement(data.message));
            scrollToBottom();
        } else {
            window.toast.error('Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.toast.error('An error occurred while sending the message');
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

// Create message element using shared utility
function createMessageElement(message) {
    return window.MessagingUtils.createMessageElement(message, currentUserId, {
        showSenderInfo: true,
        showReadStatus: false  // Group chats don't show read status per message
    });
}

// Format message time - use shared utility
function formatMessageTime(timestamp) {
    return window.MessagingUtils.formatDateTime(timestamp);
}

// Escape HTML - local fallback in case MessagingUtils not loaded yet
function escapeHtml(text) {
    if (!text) return '';
    if (window.MessagingUtils && window.MessagingUtils.escapeHtml) {
        return window.MessagingUtils.escapeHtml(text);
    }
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

@extends('admin.layouts.app')

@section('title', 'Messages - ' . $project->title)

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Messages', 'route' => 'admin.messages.index', 'icon' => 'message-square'],
        ['label' => $project->title, 'icon' => 'folder'],
    ]" class="mb-6" />

    <!-- Chat Container -->
    <x-ui.card class="overflow-hidden">
        <!-- Header -->
        <div class="bg-primary-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <x-lucide-messages-square class="w-6 h-6 text-white/90" />
                        <h1 class="text-xl font-bold text-white truncate">{{ $project->title }}</h1>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-white/80">
                        <div class="flex items-center gap-1.5">
                            <x-lucide-user class="w-4 h-4" />
                            {{ $project->client->fullName }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <x-lucide-folder class="w-4 h-4" />
                            Project #{{ $project->id }}
                        </div>
                    </div>
                </div>
                <x-ui.button href="{{ route('admin.projects.show', $project->id) }}" variant="secondary" class="bg-white/10 hover:bg-white/20 text-white border-0">
                    <x-lucide-external-link class="w-4 h-4" />
                    View Project
                </x-ui.button>
            </div>
        </div>

        <!-- Chat Type Switcher -->
        <div class="border-b border-neutral-200 bg-white px-6 py-3">
            <div class="flex items-center gap-2">
                <button id="direct-tab" onclick="switchChatType('direct')" 
                        class="chat-tab-btn flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    <x-lucide-user class="w-4 h-4" />
                    Direct Message (Client)
                </button>
                <button id="group-tab" onclick="switchChatType('group')" 
                        class="chat-tab-btn flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                    <x-lucide-users class="w-4 h-4" />
                    Group Chat (Team)
                    <span id="group-chat-status" class="hidden items-center gap-1 px-1.5 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-600">
                        <x-lucide-archive class="w-3 h-3" />
                        Archived
                    </span>
                </button>
                <!-- Archive Button (Admin Only, Group Chat Only) -->
                <div class="ml-auto" id="group-actions" style="display: none;">
                    <button id="archive-btn" onclick="toggleArchiveGroupChat()" 
                            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all bg-neutral-100 hover:bg-neutral-200 text-neutral-700">
                        <x-lucide-archive class="w-4 h-4" />
                        <span id="archive-btn-text">Archive Chat</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Pinned Meeting Card (if exists) -->
        <div id="pinned-meeting-container"></div>

        <!-- Pending Meetings Section -->
        <div id="pending-meetings-container" class="border-b border-neutral-200 bg-warning-50"></div>

        <!-- Messages Container -->
        <div id="messages-container" 
             class="h-[600px] overflow-y-auto bg-neutral-50 p-6 scroll-smooth">
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary-100 mb-3">
                        <x-lucide-loader-2 class="w-6 h-6 text-primary-600 animate-spin" />
                    </div>
                    <p class="text-sm text-neutral-600">Loading messages...</p>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="border-t border-neutral-200 bg-white p-4">
            <form id="message-form">
                @csrf
                <div class="space-y-3">
                    <div>
                        <x-ui.textarea 
                            id="message-textarea"
                            name="message" 
                            rows="3" 
                            placeholder="Type your message here... (Press Enter to send, Shift+Enter for new line)"
                            required
                            maxlength="5000"
                        />
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-xs text-neutral-500">
                                <span id="char-count" class="font-medium">0</span>/5000 characters
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <label for="attachments" class="inline-flex items-center gap-2 px-4 py-2.5 border border-neutral-200 rounded-lg hover:bg-neutral-50 cursor-pointer transition-colors text-sm font-medium text-neutral-700">
                                <x-lucide-paperclip class="w-4 h-4" />
                                Attach Files
                            </label>
                            <input 
                                type="file" 
                                id="attachments" 
                                name="attachments[]" 
                                multiple 
                                class="hidden"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
                            <span class="text-xs text-neutral-500">Max 10MB per file</span>
                        </div>
                        
                        <x-ui.button type="submit" variant="primary">
                            <x-lucide-send class="w-4 h-4" />
                            Send Message
                        </x-ui.button>
                    </div>
                    
                    <div id="selected-files" class="flex flex-wrap gap-2"></div>
                </div>
            </form>
        </div>
    </x-ui.card>
</div>

<style>
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(10px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-in;
}
</style>

<script>
    const projectId = {{ $project->id }};
    const groupChatId = {{ $groupChat?->id ?? 'null' }};
    const groupChatStatus = '{{ $groupChat?->status ?? '' }}';
    @php
        $membersData = $groupChat ? $groupChat->members->map(function($m) {
            return [
                'id' => $m->id,
                'name' => $m->fullName,
                'pic' => $m->profilePic,
                'role' => $m->role
            ];
        })->toArray() : [];
    @endphp
    const groupChatMembers = @json($membersData);
    
    let currentChatType = new URLSearchParams(window.location.search).get('tab') || 'direct'; // 'direct' or 'group'
    let currentApiEndpoint = '';
    let isInitialLoad = true;
    let lastMessageId = null;
    
    const messageForm = document.getElementById('message-form');
    const messageTextarea = document.getElementById('message-textarea');
    const attachmentsInput = document.getElementById('attachments');
    const selectedFilesDiv = document.getElementById('selected-files');
    const charCount = document.getElementById('char-count');
    const messagesContainer = document.getElementById('messages-container');
    const messagingService = window.messagingService;

    // Initialize chat type on page load
    function initializeChatType() {
        switchChatType(currentChatType);
    }

    // Switch between direct and group chat
    function switchChatType(type) {
        currentChatType = type;
        
        // Update API endpoint
        if (type === 'group') {
            currentApiEndpoint = `/api/group-chats/${groupChatId}`;
        } else {
            currentApiEndpoint = `/api/messages/projects/${projectId}`;
        }
        
        // Update tab styles
        const directTab = document.getElementById('direct-tab');
        const groupTab = document.getElementById('group-tab');
        const groupActions = document.getElementById('group-actions');
        const groupStatusBadge = document.getElementById('group-chat-status');
        const messageInput = document.getElementById('message-textarea');
        const sendButton = messageForm.querySelector('button[type="submit"]');
        
        if (type === 'group') {
            directTab.className = 'chat-tab-btn flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all bg-neutral-100 text-neutral-600 hover:bg-neutral-200';
            groupTab.className = 'chat-tab-btn flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all bg-success-100 text-success-800 ring-2 ring-success-300';
            groupActions.style.display = 'block';
            
            // Update archive button based on status
            const archiveBtn = document.getElementById('archive-btn');
            const archiveBtnText = document.getElementById('archive-btn-text');
            if (groupChatStatus === 'archived') {
                archiveBtnText.textContent = 'Reopen Chat';
                groupStatusBadge.classList.remove('hidden');
                groupStatusBadge.classList.add('inline-flex');
                messageInput.disabled = true;
                messageInput.placeholder = 'This group chat has been archived. Messages cannot be sent.';
                sendButton.disabled = true;
                archiveBtn.className = 'flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all bg-success-100 hover:bg-success-200 text-success-700';
            } else {
                archiveBtnText.textContent = 'Archive Chat';
                groupStatusBadge.classList.add('hidden');
                groupStatusBadge.classList.remove('inline-flex');
                messageInput.disabled = false;
                messageInput.placeholder = 'Type your message here... (Press Enter to send, Shift+Enter for new line)';
                sendButton.disabled = false;
                archiveBtn.className = 'flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all bg-neutral-100 hover:bg-neutral-200 text-neutral-700';
            }
        } else {
            directTab.className = 'chat-tab-btn flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all bg-primary-100 text-primary-800 ring-2 ring-primary-300';
            groupTab.className = 'chat-tab-btn flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all bg-neutral-100 text-neutral-600 hover:bg-neutral-200';
            groupActions.style.display = 'none';
            messageInput.disabled = false;
            messageInput.placeholder = 'Type your message here... (Press Enter to send, Shift+Enter for new line)';
            sendButton.disabled = false;
        }
        
        // Reset initial load flag when switching chat types
        isInitialLoad = true;
        lastMessageId = null;
        
        // Reload messages for the selected chat type
        loadMessages();
        
        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('tab', type);
        window.history.pushState({}, '', url);
    }

    // Toggle archive/reopen group chat
    async function toggleArchiveGroupChat() {
        const action = groupChatStatus === 'archived' ? 'reopen' : 'archive';
        const confirmMsg = action === 'archive' 
            ? 'Are you sure you want to archive this group chat? Members will no longer be able to send messages.'
            : 'Are you sure you want to reopen this group chat?';
        
        const confirmed = await window.Alerts.confirm(
            action === 'archive' ? 'Archive Group Chat' : 'Reopen Group Chat',
            confirmMsg,
            'warning'
        );
        if (!confirmed) return;
        
        try {
            const response = await fetch(`/api/group-chats/${groupChatId}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Reload the page to reflect changes
                window.location.reload();
            } else {
                window.toast.error(`Failed to ${action} group chat: ` + (data.error || 'Unknown error'));
            }
        } catch (error) {
            console.error(`Error ${action}ing group chat:`, error);
            window.toast.error(`Failed to ${action} group chat. Please try again.`);
        }
    }

    // Load messages when page loads
    async function loadMessages() {
        try {
            const response = await fetch(currentApiEndpoint, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                const messages = data.messages.data;
                
                if (isInitialLoad) {
                    // First load: render all messages
                    renderMessages(messages);
                    isInitialLoad = false;
                    if (messages.length > 0) {
                        lastMessageId = messages[messages.length - 1].id;
                    }
                } else {
                    // Subsequent loads: only append new messages
                    appendNewMessages(messages);
                }
                
                if (window.messagingService) {
                    await window.messagingService.updateUnreadCount();
                }
            }
        } catch (error) {
            console.error('Error loading messages:', error);
            if (isInitialLoad) {
                messagesContainer.innerHTML = `
                    <div class="alert alert-danger flex items-center gap-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        Failed to load messages. Please refresh the page.
                    </div>
                `;
            }
        }
    }

    // Append only new messages (for polling updates)
    function appendNewMessages(messages) {
        if (!messages || messages.length === 0) return;
        
        // Get existing message IDs
        const existingMessages = messagesContainer.querySelectorAll('[data-message-id]');
        const existingIds = new Set(Array.from(existingMessages).map(el => parseInt(el.dataset.messageId)));
        
        // Filter to only new messages
        const newMessages = messages.filter(msg => !existingIds.has(msg.id));
        
        if (newMessages.length > 0) {
            newMessages.forEach(msg => {
                messagesContainer.insertAdjacentHTML('beforeend', createMessageElement(msg));
            });
            lastMessageId = newMessages[newMessages.length - 1].id;
            scrollToBottom();
        }
    }

    // Render messages
    function renderMessages(messages) {
        if (messages.length === 0) {
            messagesContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <svg class="w-16 h-16 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-neutral-500 text-base font-medium">No messages yet</p>
                    <p class="text-neutral-400 text-sm mt-1">Start the conversation!</p>
                </div>
            `;
            return;
        }

        messagesContainer.innerHTML = messages.map(msg => createMessageElement(msg)).join('');
        scrollToBottom();
    }

    // Create message HTML element using shared utility
    const currentUserId = {{ auth()->id() }};
    
    function createMessageElement(message) {
        return window.MessagingUtils.createMessageElement(message, currentUserId, {
            showSenderInfo: true,
            showReadStatus: true
        });
    }

    // Scroll to bottom
    function scrollToBottom() {
        window.MessagingUtils.scrollToBottom(messagesContainer);
    }

    // Escape HTML - use shared utility
    function escapeHtml(text) {
        return window.MessagingUtils.escapeHtml(text);
    }

    // Handle form submission
    messageForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = messageTextarea.value.trim();
        if (!message) return;

        const formData = new FormData();
        formData.append('message', message);

        // Add attachments
        const files = attachmentsInput.files;
        for (let i = 0; i < files.length; i++) {
            formData.append('attachments[]', files[i]);
        }

        try {
            const response = await fetch(currentApiEndpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Append new message
                messagesContainer.insertAdjacentHTML('beforeend', createMessageElement(data.message));
                scrollToBottom();
                
                // Reset form
                messageForm.reset();
                selectedFilesDiv.innerHTML = '';
                charCount.textContent = '0';
            } else {
                window.toast.error('Failed to send message. Please try again.');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            window.toast.error('Failed to send message. Please try again.');
        }
    });

    // Character count
    messageTextarea.addEventListener('input', (e) => {
        charCount.textContent = e.target.value.length;
    });

    // Handle file selection
    attachmentsInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        selectedFilesDiv.innerHTML = files.map(file => `
            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 text-primary-700 rounded-lg text-sm font-medium">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                </svg>
                ${file.name}
            </span>
        `).join('');
    });

    // Enable Enter to send (Shift+Enter for new line)
    messageTextarea.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            messageForm.dispatchEvent(new Event('submit'));
        }
    });

    // Start polling for new messages every 5 seconds
    setInterval(loadMessages, 5000);

    // Initialize chat type and load initial messages
    initializeChatType();
</script>

<!-- Meeting Management Modals -->

<!-- Approve Meeting Modal -->
<div id="approveMeetingModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="bg-success-600 px-6 py-4 rounded-t-2xl flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Approve Meeting</h3>
            <button type="button" onclick="closeApproveMeetingModal()" class="text-white/80 hover:text-white transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>
        <div class="p-6">
            <p class="text-neutral-700 mb-4">Are you sure you want to approve this meeting? A Zoom link will be generated for the requested date and time.</p>
            <div id="approve-meeting-details" class="bg-neutral-50 rounded-xl p-4 mb-6 space-y-2 text-sm"></div>
            <div class="flex items-center gap-3">
                <x-ui.button type="button" onclick="closeApproveMeetingModal()" variant="secondary" class="flex-1">
                    Cancel
                </x-ui.button>
                <x-ui.button type="button" onclick="confirmApproveMeeting()" variant="primary" class="flex-1 bg-success-600 hover:bg-success-700">
                    Approve
                </x-ui.button>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Meeting Modal -->
<div id="rescheduleMeetingModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-primary-600 px-6 py-4 rounded-t-2xl flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Reschedule Meeting</h3>
            <button type="button" onclick="closeRescheduleMeetingModal()" class="text-white/80 hover:text-white transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>
        <form id="reschedule-meeting-form" class="p-6 space-y-4">
            <div id="reschedule-meeting-current" class="bg-neutral-50 rounded-xl p-4 space-y-2 text-sm"></div>
            
            <x-ui.input type="date" label="New Date" id="reschedule-date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" />
            
            <x-ui.input type="time" label="New Time" id="reschedule-time" required />
            
            <x-ui.textarea label="Notes to Client (Optional)" id="reschedule-notes" rows="3" maxlength="500" placeholder="Explain why you're proposing a new time..." />
            <p class="text-xs text-neutral-500 -mt-2">Max 500 characters</p>
            
            <div class="flex items-center gap-3 pt-4">
                <x-ui.button type="button" onclick="closeRescheduleMeetingModal()" variant="secondary" class="flex-1">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="flex-1">
                    Propose New Time
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Meeting Modal -->
<div id="rejectMeetingModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="bg-error-600 px-6 py-4 rounded-t-2xl flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Reject Meeting</h3>
            <button type="button" onclick="closeRejectMeetingModal()" class="text-white/80 hover:text-white transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>
        <form id="reject-meeting-form" class="p-6 space-y-4">
            <p class="text-neutral-700">Please provide a reason for rejecting this meeting request.</p>
            
            <div id="reject-meeting-details" class="bg-neutral-50 rounded-xl p-4 space-y-2 text-sm"></div>
            
            <x-ui.textarea label="Reason for Rejection" id="reject-notes" required rows="4" maxlength="500" placeholder="Explain why you're rejecting this meeting..." />
            <p class="text-xs text-neutral-500 -mt-2">Max 500 characters</p>
            
            <div class="flex items-center gap-3 pt-4">
                <x-ui.button type="button" onclick="closeRejectMeetingModal()" variant="secondary" class="flex-1">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="flex-1 bg-error-600 hover:bg-error-700">
                    Reject Meeting
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<script>
    // Meeting Management Variables
    let currentMeetingId = null;

    // Load Meetings for the Project
    async function loadMeetings() {
        try {
            const response = await fetch('/api/meetings/projects/{{ $project->id }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                renderPendingMeetings(data.meetings || []);
                renderPinnedMeetings(data.meetings || []);
            }
        } catch (error) {
            console.error('Error loading meetings:', error);
        }
    }

    // Render Pending Meetings
    function renderPendingMeetings(meetings) {
        const container = document.getElementById('pending-meetings-container');
        
        const pendingMeetings = meetings.filter(m => m.status === 'pending');
        
        if (pendingMeetings.length === 0) {
            container.innerHTML = '';
            container.classList.remove('px-6', 'py-4');
            return;
        }
        
        container.classList.add('px-6', 'py-4');
        container.innerHTML = `
            <div class="flex items-start gap-3 mb-3">
                <svg class="w-5 h-5 text-warning-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-neutral-900 mb-1">Pending Meeting Requests (${pendingMeetings.length})</h3>
                    <p class="text-xs text-neutral-600">Review and approve, reschedule, or reject these meeting requests.</p>
                </div>
            </div>
            <div class="space-y-3">
                ${pendingMeetings.map(meeting => `
                    <div class="bg-white border-2 border-warning-200 rounded-xl p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h4 class="font-semibold text-neutral-900 mb-1">${escapeHtml(meeting.title)}</h4>
                                <div class="space-y-1 text-sm text-neutral-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>${formatDate(meeting.requested_date)} at ${meeting.requested_time}</span>
                                    </div>
                                    ${meeting.description ? `<p class="text-xs text-neutral-500 mt-2">${escapeHtml(meeting.description)}</p>` : ''}
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <button onclick="openApproveMeetingModal(${meeting.id})" class="px-3 py-1.5 bg-success-600 hover:bg-success-700 text-white rounded-lg text-xs font-medium transition-colors">
                                    Approve
                                </button>
                                <button onclick="openRescheduleMeetingModal(${meeting.id})" class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs font-medium transition-colors">
                                    Reschedule
                                </button>
                                <button onclick="openRejectMeetingModal(${meeting.id})" class="px-3 py-1.5 bg-error-600 hover:bg-error-700 text-white rounded-lg text-xs font-medium transition-colors">
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    // Render Pinned Meetings
    function renderPinnedMeetings(meetings) {
        const container = document.getElementById('pinned-meeting-container');
        
        const upcomingMeetings = meetings.filter(meeting => {
            
            if (meeting.status !== 'approved' && meeting.status !== 'rescheduled') {
                return false;
            }
            
            if (!meeting.scheduled_date || !meeting.scheduled_time) {
                return false;
            }
            
            // Extract date part from ISO string (YYYY-MM-DD)
            const datePart = meeting.scheduled_date.split('T')[0];
            const dateTimeString = `${datePart} ${meeting.scheduled_time}`;
            const meetingDateTime = new Date(dateTimeString);
            
            return meetingDateTime > new Date();
        });
        
        
        if (upcomingMeetings.length === 0) {
            container.innerHTML = '';
            return;
        }
        
        upcomingMeetings.sort((a, b) => {
            const dateA = new Date(a.scheduled_date.split('T')[0] + ' ' + a.scheduled_time);
            const dateB = new Date(b.scheduled_date.split('T')[0] + ' ' + b.scheduled_time);
            return dateA - dateB;
        });
        
        const meeting = upcomingMeetings[0];
        const datePart = meeting.scheduled_date.split('T')[0];
        const scheduledDateTime = new Date(datePart + ' ' + meeting.scheduled_time);
        
        container.innerHTML = `
            <div class="border-b border-neutral-200 bg-primary-50 px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-primary-700 uppercase tracking-wide">Upcoming Meeting</span>
                        </div>
                        <h4 class="text-lg font-bold text-neutral-900 mb-2">${escapeHtml(meeting.title)}</h4>
                        <div class="flex flex-col gap-2 text-sm text-neutral-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">${formatDateTime(scheduledDateTime)}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="admin-meeting-countdown-${meeting.id}" class="text-primary-600 font-semibold"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <a href="${meeting.zoom_start_url}" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-semibold shadow-sm hover:shadow-md transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                            </svg>
                            Start Meeting (Host)
                        </a>
                        ${meeting.zoom_password ? `
                            <div class="text-xs text-center">
                                <span class="text-neutral-600">Password:</span>
                                <code class="ml-1 px-2 py-1 bg-white rounded font-mono">${meeting.zoom_password}</code>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        
        startCountdown(meeting.id, scheduledDateTime, 'admin-meeting-countdown');
    }

    // Modal Functions - Approve
    function openApproveMeetingModal(meetingId) {
        currentMeetingId = meetingId;
        fetch(`/api/meetings/projects/{{ $project->id }}`)
            .then(res => res.json())
            .then(data => {
                const meeting = data.meetings.find(m => m.id === meetingId);
                if (meeting) {
                    document.getElementById('approve-meeting-details').innerHTML = `
                        <div><span class="font-medium text-neutral-700">Title:</span> ${escapeHtml(meeting.title)}</div>
                        <div><span class="font-medium text-neutral-700">Date:</span> ${formatDate(meeting.requested_date)}</div>
                        <div><span class="font-medium text-neutral-700">Time:</span> ${meeting.requested_time}</div>
                        ${meeting.description ? `<div><span class="font-medium text-neutral-700">Agenda:</span> ${escapeHtml(meeting.description)}</div>` : ''}
                    `;
                }
            });
        document.getElementById('approveMeetingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeApproveMeetingModal() {
        document.getElementById('approveMeetingModal').classList.add('hidden');
        document.body.style.overflow = '';
        currentMeetingId = null;
    }

    async function confirmApproveMeeting() {
        if (!currentMeetingId) return;
        
        try {
            const response = await fetch(`/api/meetings/${currentMeetingId}/approve`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                window.toast.success('Meeting approved! Zoom link has been generated.');
                closeApproveMeetingModal();
                loadMeetings();
            } else {
                window.toast.error(data.error || 'Failed to approve meeting. Please try again.');
            }
        } catch (error) {
            console.error('Error approving meeting:', error);
            window.toast.error('An error occurred. Please try again.');
        }
    }

    // Modal Functions - Reschedule
    function openRescheduleMeetingModal(meetingId) {
        currentMeetingId = meetingId;
        fetch(`/api/meetings/projects/{{ $project->id }}`)
            .then(res => res.json())
            .then(data => {
                const meeting = data.meetings.find(m => m.id === meetingId);
                if (meeting) {
                    document.getElementById('reschedule-meeting-current').innerHTML = `
                        <div class="font-medium text-neutral-700 mb-2">Current Request:</div>
                        <div class="text-neutral-600"><span class="font-medium">Title:</span> ${escapeHtml(meeting.title)}</div>
                        <div class="text-neutral-600"><span class="font-medium">Requested:</span> ${formatDate(meeting.requested_date)} at ${meeting.requested_time}</div>
                    `;
                }
            });
        document.getElementById('rescheduleMeetingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRescheduleMeetingModal() {
        document.getElementById('rescheduleMeetingModal').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('reschedule-meeting-form').reset();
        currentMeetingId = null;
    }

    document.getElementById('reschedule-meeting-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!currentMeetingId) return;
        
        const formData = {
            rescheduled_date: document.getElementById('reschedule-date').value,
            rescheduled_time: document.getElementById('reschedule-time').value,
            admin_notes: document.getElementById('reschedule-notes').value || null
        };
        
        try {
            const response = await fetch(`/api/meetings/${currentMeetingId}/reschedule`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (response.ok) {
                window.toast.success('Meeting rescheduled! Client will be notified to approve the new time.');
                closeRescheduleMeetingModal();
                loadMeetings();
            } else {
                window.toast.error(data.error || 'Failed to reschedule meeting. Please try again.');
            }
        } catch (error) {
            console.error('Error rescheduling meeting:', error);
            window.toast.error('An error occurred. Please try again.');
        }
    });

    // Modal Functions - Reject
    function openRejectMeetingModal(meetingId) {
        currentMeetingId = meetingId;
        fetch(`/api/meetings/projects/{{ $project->id }}`)
            .then(res => res.json())
            .then(data => {
                const meeting = data.meetings.find(m => m.id === meetingId);
                if (meeting) {
                    document.getElementById('reject-meeting-details').innerHTML = `
                        <div><span class="font-medium text-neutral-700">Title:</span> ${escapeHtml(meeting.title)}</div>
                        <div><span class="font-medium text-neutral-700">Date:</span> ${formatDate(meeting.requested_date)} at ${meeting.requested_time}</div>
                    `;
                }
            });
        document.getElementById('rejectMeetingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectMeetingModal() {
        document.getElementById('rejectMeetingModal').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('reject-meeting-form').reset();
        currentMeetingId = null;
    }

    document.getElementById('reject-meeting-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!currentMeetingId) return;
        
        const formData = {
            admin_notes: document.getElementById('reject-notes').value
        };
        
        try {
            const response = await fetch(`/api/meetings/${currentMeetingId}/reject`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (response.ok) {
                window.toast.success('Meeting rejected. Client will be notified.');
                closeRejectMeetingModal();
                loadMeetings();
            } else {
                window.toast.error(data.error || 'Failed to reject meeting. Please try again.');
            }
        } catch (error) {
            console.error('Error rejecting meeting:', error);
            window.toast.error('An error occurred. Please try again.');
        }
    });

    // Utility Functions
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    function formatDateTime(date) {
        return date.toLocaleString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    function startCountdown(meetingId, scheduledDateTime, prefix = 'admin-meeting-countdown') {
        const countdownElement = document.getElementById(`${prefix}-${meetingId}`);
        if (!countdownElement) return;
        
        function updateCountdown() {
            const now = new Date();
            const diff = scheduledDateTime - now;
            
            if (diff <= 0) {
                countdownElement.textContent = 'Meeting is starting now!';
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            let countdownText = '';
            if (days > 0) {
                countdownText = `${days}d ${hours}h ${minutes}m`;
            } else if (hours > 0) {
                countdownText = `${hours}h ${minutes}m`;
            } else {
                countdownText = `${minutes} minutes`;
            }
            
            countdownElement.textContent = `Starts in ${countdownText}`;
        }
        
        updateCountdown();
        setInterval(updateCountdown, 60000);
    }

    // Close modals on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeApproveMeetingModal();
            closeRescheduleMeetingModal();
            closeRejectMeetingModal();
        }
    });

    // Load meetings on page load and refresh every 30 seconds
    loadMeetings();
    setInterval(loadMeetings, 30000);
</script>
@endsection

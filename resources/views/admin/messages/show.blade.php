@extends('admin.layouts.app')

@section('title', 'Messages - ' . $project->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a href="{{ route('admin.messages.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                    Messages
                </a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900 font-medium truncate">{{ $project->title }}</li>
        </ol>
    </nav>

    <!-- Chat Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-600 to-accent-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-6 h-6 text-white/90" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                        </svg>
                        <h1 class="text-xl font-bold text-white truncate">{{ $project->title }}</h1>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-white/80">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $project->client->fullName }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                            </svg>
                            Project #{{ $project->id }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.projects.show', $project->id) }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors text-sm font-medium backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    View Project
                </a>
            </div>
        </div>

        <!-- Pinned Meeting Card (if exists) -->
        <div id="pinned-meeting-container"></div>

        <!-- Pending Meetings Section -->
        <div id="pending-meetings-container" class="border-b border-gray-200 bg-amber-50"></div>

        <!-- Messages Container -->
        <div id="messages-container" 
             class="h-[600px] overflow-y-auto bg-gray-50 p-6 scroll-smooth">
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary-100 mb-3">
                        <svg class="w-6 h-6 text-primary-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">Loading messages...</p>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="border-t border-gray-200 bg-white p-4">
            <form id="message-form">
                @csrf
                <div class="space-y-3">
                    <div>
                        <textarea 
                            id="message-textarea"
                            name="message" 
                            rows="3" 
                            placeholder="Type your message here... (Press Enter to send, Shift+Enter for new line)"
                            required
                            maxlength="5000"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none text-sm placeholder-gray-400"></textarea>
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                <span id="char-count" class="font-medium">0</span>/5000 characters
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <label for="attachments" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                Attach Files
                            </label>
                            <input 
                                type="file" 
                                id="attachments" 
                                name="attachments[]" 
                                multiple 
                                class="hidden"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
                            <span class="text-xs text-gray-500">Max 10MB per file</span>
                        </div>
                        
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white rounded-lg transition-all shadow-sm hover:shadow-md text-sm font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                            </svg>
                            Send Message
                        </button>
                    </div>
                    
                    <div id="selected-files" class="flex flex-wrap gap-2"></div>
                </div>
            </form>
        </div>
    </div>
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
    const messageForm = document.getElementById('message-form');
    const messageTextarea = document.getElementById('message-textarea');
    const attachmentsInput = document.getElementById('attachments');
    const selectedFilesDiv = document.getElementById('selected-files');
    const charCount = document.getElementById('char-count');
    const messagesContainer = document.getElementById('messages-container');
    const messagingService = window.messagingService;

    // Load messages when page loads
    async function loadMessages() {
        try {
            const response = await fetch(`/api/messages/projects/${projectId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                renderMessages(data.messages.data);
                if (window.messagingService) {
                    await window.messagingService.updateUnreadCount();
                }
            }
        } catch (error) {
            console.error('Error loading messages:', error);
            messagesContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Failed to load messages. Please refresh the page.
                </div>
            `;
        }
    }

    // Render messages
    function renderMessages(messages) {
        if (messages.length === 0) {
            messagesContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500 text-base font-medium">No messages yet</p>
                    <p class="text-gray-400 text-sm mt-1">Start the conversation!</p>
                </div>
            `;
            return;
        }

        messagesContainer.innerHTML = messages.map(msg => createMessageElement(msg)).join('');
        scrollToBottom();
    }

    // Create message HTML element
    function createMessageElement(message) {
        const isSender = message.sender_id === {{ auth()->id() }};
        const alignClass = isSender ? 'justify-end' : 'justify-start';
        
        let attachmentsHtml = '';
        if (message.attachments && message.attachments.length > 0) {
            attachmentsHtml = message.attachments.map(att => `
                <a href="/storage/${att.path}" 
                   download="${att.name}" 
                   class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors ${isSender ? 'bg-white/20 hover:bg-white/30 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'}">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                    </svg>
                    ${att.name}
                </a>
            `).join('');
        }

        return `
            <div class="flex ${alignClass} mb-4 animate-fade-in">
                <div class="max-w-[70%]">
                    ${!isSender ? `
                        <div class="flex items-center gap-2 mb-1.5 px-1">
                            <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">${escapeHtml(message.sender.fullName)}</span>
                        </div>
                    ` : ''}
                    <div class="rounded-2xl px-4 py-3 shadow-sm ${isSender ? 'bg-gradient-to-br from-primary-600 to-accent-600 text-white rounded-br-md' : 'bg-white border border-gray-200 text-gray-900 rounded-bl-md'}">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap break-words">${escapeHtml(message.message)}</p>
                        ${attachmentsHtml}
                    </div>
                    <div class="flex items-center gap-1.5 mt-1.5 px-1 text-xs text-gray-500 ${isSender ? 'justify-end' : 'justify-start'}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <span>${formatTime(message.created_at)}</span>
                        ${message.status === 'read' && isSender ? `
                            <svg class="w-3.5 h-3.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // Format time helper
    function formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined });
    }

    // Scroll to bottom
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
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
            const response = await fetch(`/api/messages/projects/${projectId}`, {
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
                alert('Failed to send message. Please try again.');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Failed to send message. Please try again.');
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

    // Initial load
    loadMessages();
</script>

<!-- Meeting Management Modals -->

<!-- Approve Meeting Modal -->
<div id="approveMeetingModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-white">Approve Meeting</h3>
            <button type="button" onclick="closeApproveMeetingModal()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">Are you sure you want to approve this meeting? A Zoom link will be generated for the requested date and time.</p>
            <div id="approve-meeting-details" class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2 text-sm"></div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeApproveMeetingModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button type="button" onclick="confirmApproveMeeting()" class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg transition-all shadow-sm hover:shadow-md font-medium">
                    Approve & Generate Zoom
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reschedule Meeting Modal -->
<div id="rescheduleMeetingModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-white">Reschedule Meeting</h3>
            <button type="button" onclick="closeRescheduleMeetingModal()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="reschedule-meeting-form" class="p-6 space-y-4">
            <div id="reschedule-meeting-current" class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm"></div>
            
            <div>
                <label for="reschedule-date" class="block text-sm font-medium text-gray-700 mb-1">
                    New Date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="reschedule-date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            
            <div>
                <label for="reschedule-time" class="block text-sm font-medium text-gray-700 mb-1">
                    New Time <span class="text-red-500">*</span>
                </label>
                <input type="time" id="reschedule-time" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            
            <div>
                <label for="reschedule-notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Notes to Client (Optional)
                </label>
                <textarea id="reschedule-notes" rows="3" maxlength="500" placeholder="Explain why you're proposing a new time..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"></textarea>
                <p class="text-xs text-gray-500 mt-1">Max 500 characters</p>
            </div>
            
            <div class="flex items-center gap-3 pt-4">
                <button type="button" onclick="closeRescheduleMeetingModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg transition-all shadow-sm hover:shadow-md font-medium">
                    Propose New Time
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Meeting Modal -->
<div id="rejectMeetingModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-white">Reject Meeting</h3>
            <button type="button" onclick="closeRejectMeetingModal()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="reject-meeting-form" class="p-6 space-y-4">
            <p class="text-gray-700">Please provide a reason for rejecting this meeting request.</p>
            
            <div id="reject-meeting-details" class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm"></div>
            
            <div>
                <label for="reject-notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Reason for Rejection <span class="text-red-500">*</span>
                </label>
                <textarea id="reject-notes" required rows="4" maxlength="500" placeholder="Explain why you're rejecting this meeting..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"></textarea>
                <p class="text-xs text-gray-500 mt-1">Max 500 characters</p>
            </div>
            
            <div class="flex items-center gap-3 pt-4">
                <button type="button" onclick="closeRejectMeetingModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-lg transition-all shadow-sm hover:shadow-md font-medium">
                    Reject Meeting
                </button>
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
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-gray-900 mb-1">Pending Meeting Requests (${pendingMeetings.length})</h3>
                    <p class="text-xs text-gray-600">Review and approve, reschedule, or reject these meeting requests.</p>
                </div>
            </div>
            <div class="space-y-3">
                ${pendingMeetings.map(meeting => `
                    <div class="bg-white border-2 border-amber-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-1">${escapeHtml(meeting.title)}</h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>${formatDate(meeting.requested_date)} at ${meeting.requested_time}</span>
                                    </div>
                                    ${meeting.description ? `<p class="text-xs text-gray-500 mt-2">${escapeHtml(meeting.description)}</p>` : ''}
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <button onclick="openApproveMeetingModal(${meeting.id})" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-medium transition-colors">
                                    Approve
                                </button>
                                <button onclick="openRescheduleMeetingModal(${meeting.id})" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium transition-colors">
                                    Reschedule
                                </button>
                                <button onclick="openRejectMeetingModal(${meeting.id})" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-medium transition-colors">
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
            <div class="border-b border-gray-200 bg-gradient-to-r from-primary-50 to-accent-50 px-6 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-primary-700 uppercase tracking-wide">Upcoming Meeting</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">${escapeHtml(meeting.title)}</h4>
                        <div class="flex flex-col gap-2 text-sm text-gray-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">${formatDateTime(scheduledDateTime)}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="admin-meeting-countdown-${meeting.id}" class="text-accent-600 font-semibold"></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <a href="${meeting.zoom_start_url}" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                            </svg>
                            Start Meeting (Host)
                        </a>
                        ${meeting.zoom_password ? `
                            <div class="text-xs text-center">
                                <span class="text-gray-600">Password:</span>
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
                        <div><span class="font-medium text-gray-700">Title:</span> ${escapeHtml(meeting.title)}</div>
                        <div><span class="font-medium text-gray-700">Date:</span> ${formatDate(meeting.requested_date)}</div>
                        <div><span class="font-medium text-gray-700">Time:</span> ${meeting.requested_time}</div>
                        ${meeting.description ? `<div><span class="font-medium text-gray-700">Agenda:</span> ${escapeHtml(meeting.description)}</div>` : ''}
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
                alert('Meeting approved! Zoom link has been generated.');
                closeApproveMeetingModal();
                loadMeetings();
            } else {
                alert(data.error || 'Failed to approve meeting. Please try again.');
            }
        } catch (error) {
            console.error('Error approving meeting:', error);
            alert('An error occurred. Please try again.');
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
                        <div class="font-medium text-gray-700 mb-2">Current Request:</div>
                        <div class="text-gray-600"><span class="font-medium">Title:</span> ${escapeHtml(meeting.title)}</div>
                        <div class="text-gray-600"><span class="font-medium">Requested:</span> ${formatDate(meeting.requested_date)} at ${meeting.requested_time}</div>
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
                alert('Meeting rescheduled! Client will be notified to approve the new time.');
                closeRescheduleMeetingModal();
                loadMeetings();
            } else {
                alert(data.error || 'Failed to reschedule meeting. Please try again.');
            }
        } catch (error) {
            console.error('Error rescheduling meeting:', error);
            alert('An error occurred. Please try again.');
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
                        <div><span class="font-medium text-gray-700">Title:</span> ${escapeHtml(meeting.title)}</div>
                        <div><span class="font-medium text-gray-700">Date:</span> ${formatDate(meeting.requested_date)} at ${meeting.requested_time}</div>
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
                alert('Meeting rejected. Client will be notified.');
                closeRejectMeetingModal();
                loadMeetings();
            } else {
                alert(data.error || 'Failed to reject meeting. Please try again.');
            }
        } catch (error) {
            console.error('Error rejecting meeting:', error);
            alert('An error occurred. Please try again.');
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

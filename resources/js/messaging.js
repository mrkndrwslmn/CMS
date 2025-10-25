// Firebase Messaging Module for Real-time Chat
import { initializeApp } from 'firebase/app';
import { getMessaging, getToken, onMessage } from 'firebase/messaging';

class MessagingService {
    constructor() {
        this.firebaseApp = null;
        this.messaging = null;
        this.currentConversation = null;
        this.messagePollingInterval = null;
    }

    /**
     * Initialize Firebase
     */
    async initialize() {
        try {
            const firebaseConfig = {
                apiKey: window.firebaseConfig?.apiKey,
                authDomain: window.firebaseConfig?.authDomain,
                projectId: window.firebaseConfig?.projectId,
                storageBucket: window.firebaseConfig?.storageBucket,
                messagingSenderId: window.firebaseConfig?.messagingSenderId,
                appId: window.firebaseConfig?.appId,
                measurementId: window.firebaseConfig?.measurementId,
            };

            this.firebaseApp = initializeApp(firebaseConfig);
            this.messaging = getMessaging(this.firebaseApp);

            // Request notification permission
            await this.requestNotificationPermission();

            // Listen for foreground messages
            this.setupForegroundMessageListener();

<<<<<<< HEAD
=======
            console.log('Firebase Messaging initialized successfully');
>>>>>>> 7c71488 (Initial commit from Princess)
        } catch (error) {
            console.error('Failed to initialize Firebase:', error);
        }
    }

    /**
     * Request notification permission and get FCM token
     */
    async requestNotificationPermission() {
        try {
            const permission = await Notification.requestPermission();
            
            if (permission === 'granted') {
                const token = await getToken(this.messaging, {
                    vapidKey: window.firebaseConfig?.vapidKey
                });

                if (token) {
                    await this.updateFcmToken(token);
<<<<<<< HEAD
                }
=======
                    console.log('FCM token obtained:', token);
                }
            } else {
                console.log('Notification permission denied');
>>>>>>> 7c71488 (Initial commit from Princess)
            }
        } catch (error) {
            console.error('Error getting FCM token:', error);
        }
    }

    /**
     * Update FCM token on server
     */
    async updateFcmToken(token) {
        try {
            const response = await fetch('/api/messages/fcm-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({ token })
            });

            const data = await response.json();
            return data.success;
        } catch (error) {
            console.error('Error updating FCM token:', error);
            return false;
        }
    }

    /**
     * Setup listener for foreground messages
     */
    setupForegroundMessageListener() {
        onMessage(this.messaging, (payload) => {
<<<<<<< HEAD
=======
            console.log('Foreground message received:', payload);
            
>>>>>>> 7c71488 (Initial commit from Princess)
            const { notification, data } = payload;
            
            // Show browser notification
            if (notification) {
                this.showNotification(notification.title, notification.body, data);
            }

            // Update UI if in the same conversation
            if (data && data.conversation_id === this.currentConversation) {
                this.appendMessage(JSON.parse(data.message));
            }

            // Update unread count
            this.updateUnreadCount();
        });
    }

    /**
     * Show browser notification
     */
    showNotification(title, body, data = {}) {
        if ('Notification' in window && Notification.permission === 'granted') {
            const notification = new Notification(title, {
                body,
                icon: '/favicon.ico',
                badge: '/favicon.ico',
                tag: data.conversation_id || 'message',
                requireInteraction: false,
                data: data
            });

            notification.onclick = function(event) {
                event.preventDefault();
                if (data.project_id) {
                    window.location.href = `/messages/projects/${data.project_id}`;
                }
                notification.close();
            };
        }
    }

    /**
     * Load conversations
     */
    async loadConversations() {
        try {
            const response = await fetch('/api/messages/conversations', {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.renderConversations(data.conversations.data);
            }
        } catch (error) {
            console.error('Error loading conversations:', error);
        }
    }

    /**
     * Load messages for a project
     */
    async loadMessages(projectId) {
        try {
            const response = await fetch(`/api/messages/projects/${projectId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.currentConversation = data.conversation.conversation_id;
                this.renderMessages(data.messages.data);
                this.startPolling(projectId);
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    /**
     * Send a message
     */
    async sendMessage(projectId, message, attachments = null) {
        try {
            const formData = new FormData();
            formData.append('message', message);
            
            if (attachments) {
                for (let i = 0; i < attachments.length; i++) {
                    formData.append('attachments[]', attachments[i]);
                }
            }

            const response = await fetch(`/api/messages/projects/${projectId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: formData
            });

            const data = await response.json();
            
            if (data.success) {
                this.appendMessage(data.message);
                return true;
            } else {
                console.error('Error sending message:', data.errors);
                return false;
            }
        } catch (error) {
            console.error('Error sending message:', error);
            return false;
        }
    }

    /**
     * Mark messages as read
     */
    async markAsRead(projectId) {
        try {
            const response = await fetch(`/api/messages/projects/${projectId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();
            return data.success;
        } catch (error) {
            console.error('Error marking messages as read:', error);
            return false;
        }
    }

    /**
     * Get unread message count
     */
    async getUnreadCount() {
        try {
            const response = await fetch('/api/messages/unread-count', {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                return data.unread_count;
            }
            return 0;
        } catch (error) {
            console.error('Error getting unread count:', error);
            return 0;
        }
    }

    /**
     * Update unread count in UI
     */
    async updateUnreadCount() {
        const count = await this.getUnreadCount();
        const badge = document.querySelector('.unread-messages-badge');
        
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    /**
     * Start polling for new messages
     */
    startPolling(projectId, interval = 5000) {
        this.stopPolling();
        
        this.messagePollingInterval = setInterval(async () => {
            await this.loadMessages(projectId);
        }, interval);
    }

    /**
     * Stop polling
     */
    stopPolling() {
        if (this.messagePollingInterval) {
            clearInterval(this.messagePollingInterval);
            this.messagePollingInterval = null;
        }
    }

    /**
     * Render conversations list
     */
    renderConversations(conversations) {
        const container = document.getElementById('conversations-list');
        if (!container) return;

        container.innerHTML = conversations.map(conv => `
            <a href="/messages/projects/${conv.project_id}" 
               class="block p-4 border-b hover:bg-gray-50 transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">${conv.project.title}</h3>
                        <p class="text-sm text-gray-600 mt-1">${conv.client.fullName}</p>
                        ${conv.last_message ? `
                            <p class="text-sm text-gray-500 mt-2 truncate">
                                ${conv.last_message.message}
                            </p>
                        ` : ''}
                    </div>
                    <div class="ml-4 text-right">
                        ${conv.last_message_at ? `
                            <span class="text-xs text-gray-500">
                                ${this.formatDate(conv.last_message_at)}
                            </span>
                        ` : ''}
                        ${conv.unread_count > 0 ? `
                            <span class="inline-block mt-2 px-2 py-1 bg-blue-500 text-white text-xs rounded-full">
                                ${conv.unread_count}
                            </span>
                        ` : ''}
                    </div>
                </div>
            </a>
        `).join('');
    }

    /**
     * Render messages
     */
    renderMessages(messages) {
        const container = document.getElementById('messages-container');
        if (!container) return;

        container.innerHTML = messages.map(msg => this.createMessageElement(msg)).join('');
        this.scrollToBottom();
    }

    /**
     * Append a new message
     */
    appendMessage(message) {
        const container = document.getElementById('messages-container');
        if (!container) return;

        container.insertAdjacentHTML('beforeend', this.createMessageElement(message));
        this.scrollToBottom();
    }

    /**
     * Create message HTML element
     */
    createMessageElement(message) {
        const isSender = message.is_sender;
        const alignClass = isSender ? 'justify-end' : 'justify-start';
        const bgClass = isSender ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-900';

        return `
            <div class="flex ${alignClass} mb-4" data-message-id="${message.id}">
                <div class="max-w-[70%]">
                    ${!isSender ? `
                        <div class="text-xs text-gray-500 mb-1">
                            ${message.sender.fullName}
                        </div>
                    ` : ''}
                    <div class="${bgClass} rounded-lg px-4 py-2">
                        <p class="text-sm whitespace-pre-wrap">${this.escapeHtml(message.message)}</p>
                        ${message.attachments ? this.renderAttachments(message.attachments) : ''}
                    </div>
                    <div class="text-xs text-gray-500 mt-1 ${isSender ? 'text-right' : 'text-left'}">
                        ${message.formatted_time}
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Render attachments
     */
    renderAttachments(attachments) {
        return attachments.map(att => `
            <a href="${att.path}" download="${att.name}" 
               class="block mt-2 text-xs underline">
                📎 ${att.name}
            </a>
        `).join('');
    }

    /**
     * Scroll to bottom of messages
     */
    scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    /**
     * Format date
     */
    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        return date.toLocaleDateString();
    }

    /**
     * Escape HTML
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Export singleton instance
export const messagingService = new MessagingService();

// Make it globally available
window.messagingService = messagingService;

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        messagingService.initialize();
        messagingService.updateUnreadCount();
    });
} else {
    messagingService.initialize();
    messagingService.updateUnreadCount();
}

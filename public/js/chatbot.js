/**
 * Chatbot Widget
 * A reusable chatbot component with local storage caching (1 hour expiration)
 */

class ChatbotWidget {
    constructor() {
        this.isOpen = false;
        this.messages = [];
        this.conversationHistory = [];
        this.cacheKey = 'treisadiutor_chatbot_cache';
        this.cacheExpiry = 60 * 60 * 1000; // 1 hour in milliseconds
        this.isTyping = false;
        
        this.init();
    }

    init() {
        // Load cached messages
        this.loadFromCache();
        
        // Create chatbot elements
        this.createChatbotElements();
        
        // Attach event listeners
        this.attachEventListeners();
        
        // Load initial greeting if no cached messages
        if (this.messages.length === 0) {
            this.loadGreeting();
        } else {
            this.renderMessages();
        }
    }

    createChatbotElements() {
        const chatbotHTML = `
            <!-- Chatbot Floating Icon -->
            <div id="chatbot-icon" class="fixed bottom-6 right-6 z-50 cursor-pointer group">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-700 rounded-full blur-lg opacity-50 group-hover:opacity-75 transition-opacity"></div>
                    <div class="relative bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <!-- Notification Badge (optional) -->
                    <div id="chatbot-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 items-center justify-center font-bold">
                        1
                    </div>
                </div>
            </div>

            <!-- Chatbot Window -->
            <div id="chatbot-window" class="fixed bottom-24 right-6 w-96 max-w-[calc(100vw-3rem)] h-[600px] max-h-[calc(100vh-8rem)] bg-white rounded-2xl shadow-2xl z-50 hidden flex-col overflow-hidden border border-neutral-200">
                <!-- Header -->
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Treis Adiutor</h3>
                            <p class="text-xs text-white/80">Online • AI Assistant</p>
                        </div>
                    </div>
                    <button id="chatbot-close" class="hover:bg-white/10 rounded-full p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Messages Container -->
                <div id="chatbot-messages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-neutral-50">
                    <!-- Messages will be rendered here -->
                </div>

                <!-- Typing Indicator -->
                <div id="typing-indicator" class="hidden px-6 py-2">
                    <div class="flex items-center space-x-2 text-neutral-500 text-sm">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="w-2 h-2 bg-neutral-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                        <span class="text-xs">Typing...</span>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t border-neutral-200 p-4 bg-white">
                    <div class="flex space-x-2">
                        <input 
                            type="text" 
                            id="chatbot-input" 
                            placeholder="Type your message..." 
                            class="flex-1 px-4 py-2 border border-neutral-300 rounded-full focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            maxlength="1000"
                        />
                        <button 
                            id="chatbot-send" 
                            class="bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-full w-10 h-10 flex items-center justify-center hover:from-primary-700 hover:to-primary-800 transition-all duration-300 transform hover:scale-105"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-neutral-500 mt-2 text-center">Messages are cached for 1 hour</p>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    attachEventListeners() {
        const icon = document.getElementById('chatbot-icon');
        const closeBtn = document.getElementById('chatbot-close');
        const sendBtn = document.getElementById('chatbot-send');
        const input = document.getElementById('chatbot-input');

        icon.addEventListener('click', () => this.toggleChat());
        closeBtn.addEventListener('click', () => this.toggleChat());
        sendBtn.addEventListener('click', () => this.sendMessage());
        
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.sendMessage();
            }
        });
    }

    toggleChat() {
        this.isOpen = !this.isOpen;
        const window = document.getElementById('chatbot-window');
        const icon = document.getElementById('chatbot-icon');

        if (this.isOpen) {
            window.classList.remove('hidden');
            window.classList.add('flex');
            icon.style.transform = 'scale(0.9)';
            
            // Focus input
            setTimeout(() => {
                document.getElementById('chatbot-input').focus();
            }, 100);
            
            // Scroll to bottom
            this.scrollToBottom();
        } else {
            window.classList.add('hidden');
            window.classList.remove('flex');
            icon.style.transform = 'scale(1)';
        }
    }

    async loadGreeting() {
        try {
            const response = await fetch('/api/chatbot/greeting');
            const data = await response.json();

            if (data.success) {
                this.addMessage('bot', data.message, data.suggestions);
                this.saveToCache();
            }
        } catch (error) {
            console.error('Error loading greeting:', error);
            this.addMessage('bot', 'Hello! How can I help you today?');
        }
    }

    async sendMessage() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();

        if (!message) return;

        // Add user message
        this.addMessage('user', message);
        input.value = '';

        // Show typing indicator
        this.showTyping(true);

        try {
            const response = await fetch('/api/chatbot/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    message: message,
                    conversation_history: this.conversationHistory,
                }),
            });

            const data = await response.json();

            if (data.success) {
                this.addMessage('bot', data.response);
            } else {
                this.addMessage('bot', 'Sorry, I encountered an error. Please try again.');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.addMessage('bot', 'Sorry, I\'m having trouble connecting. Please try again later.');
        } finally {
            this.showTyping(false);
        }

        // Save to cache
        this.saveToCache();
    }

    addMessage(role, content, suggestions = null) {
        const message = {
            role,
            content,
            timestamp: new Date().toISOString(),
            suggestions,
        };

        this.messages.push(message);

        // Add to conversation history (for API context)
        if (role === 'user' || role === 'bot') {
            this.conversationHistory.push({
                role: role === 'user' ? 'user' : 'model',
                content: content,
            });
        }

        this.renderMessage(message);
        this.scrollToBottom();
    }

    renderMessages() {
        const container = document.getElementById('chatbot-messages');
        container.innerHTML = '';
        this.messages.forEach(message => this.renderMessage(message));
        this.scrollToBottom();
    }

    renderMessage(message) {
        const container = document.getElementById('chatbot-messages');
        const isBot = message.role === 'bot';

        const messageHTML = `
            <div class="flex ${isBot ? 'justify-start' : 'justify-end'}">
                <div class="max-w-[80%] ${isBot ? 'bg-white border border-neutral-200' : 'bg-gradient-to-r from-primary-600 to-primary-700 text-white'} rounded-2xl px-4 py-3 shadow-sm">
                    <p class="text-sm whitespace-pre-wrap">${this.escapeHtml(message.content)}</p>
                    ${message.suggestions ? this.renderSuggestions(message.suggestions) : ''}
                    <p class="text-xs ${isBot ? 'text-neutral-400' : 'text-white/70'} mt-1">
                        ${this.formatTime(message.timestamp)}
                    </p>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', messageHTML);
    }

    renderSuggestions(suggestions) {
        if (!suggestions || suggestions.length === 0) return '';

        const suggestionsHTML = suggestions.map(suggestion => `
            <button 
                class="text-xs bg-primary-50 hover:bg-primary-100 text-primary-700 px-3 py-1 rounded-full mt-2 mr-2 transition-colors"
                onclick="chatbot.sendSuggestion('${this.escapeHtml(suggestion)}')"
            >
                ${this.escapeHtml(suggestion)}
            </button>
        `).join('');

        return `<div class="mt-2">${suggestionsHTML}</div>`;
    }

    sendSuggestion(suggestion) {
        const input = document.getElementById('chatbot-input');
        input.value = suggestion;
        this.sendMessage();
    }

    showTyping(show) {
        const indicator = document.getElementById('typing-indicator');
        if (show) {
            indicator.classList.remove('hidden');
        } else {
            indicator.classList.add('hidden');
        }
        this.scrollToBottom();
    }

    scrollToBottom() {
        setTimeout(() => {
            const container = document.getElementById('chatbot-messages');
            container.scrollTop = container.scrollHeight;
        }, 100);
    }

    saveToCache() {
        const cacheData = {
            messages: this.messages,
            conversationHistory: this.conversationHistory,
            timestamp: Date.now(),
        };

        try {
            localStorage.setItem(this.cacheKey, JSON.stringify(cacheData));
        } catch (error) {
            console.error('Error saving to cache:', error);
        }
    }

    loadFromCache() {
        try {
            const cachedData = localStorage.getItem(this.cacheKey);
            
            if (!cachedData) return;

            const data = JSON.parse(cachedData);
            const now = Date.now();
            const age = now - data.timestamp;

            // Check if cache is expired (1 hour)
            if (age > this.cacheExpiry) {
                localStorage.removeItem(this.cacheKey);
                return;
            }

            this.messages = data.messages || [];
            this.conversationHistory = data.conversationHistory || [];
        } catch (error) {
            console.error('Error loading from cache:', error);
            localStorage.removeItem(this.cacheKey);
        }
    }

    formatTime(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize chatbot when DOM is ready
let chatbot;
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        chatbot = new ChatbotWidget();
    });
} else {
    chatbot = new ChatbotWidget();
}

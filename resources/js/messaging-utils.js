/**
 * Messaging Utilities
 * 
 * Shared utilities for rendering messages across admin, client, and adiutor views.
 * Eliminates code duplication in createMessageElement() functions.
 */

export const MessagingUtils = {
    /**
     * Create a message bubble HTML element
     * 
     * @param {Object} message - The message object from the API
     * @param {number} currentUserId - The ID of the currently authenticated user
     * @param {Object} options - Additional options
     * @param {boolean} options.showSenderInfo - Whether to show sender name/avatar (default: true)
     * @param {boolean} options.showReadStatus - Whether to show read status indicators (default: true)
     * @returns {string} HTML string for the message bubble
     */
    createMessageElement(message, currentUserId, options = {}) {
        const {
            showSenderInfo = true,
            showReadStatus = true
        } = options;

        const isSender = message.sender_id === currentUserId;
        const alignClass = isSender ? 'justify-end' : 'justify-start';
        const senderName = message.sender?.fullName || 'Deleted User';
        const senderInitial = senderName.substring(0, 1).toUpperCase();
        const isAdmin = message.sender?.role === 'admin';

        // Build sender info HTML
        let senderInfoHtml = '';
        if (!isSender && showSenderInfo) {
            const adminBadge = isAdmin 
                ? '<span class="px-1.5 py-0.5 text-[10px] font-semibold bg-primary-100 text-primary-700 rounded">Admin</span>' 
                : '';
            
            senderInfoHtml = `
                <div class="flex items-center gap-2 mb-1.5 px-1">
                    <div class="w-6 h-6 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                        <span class="text-primary-600 text-xs font-semibold">${senderInitial}</span>
                    </div>
                    <span class="text-xs font-medium text-neutral-700">${this.escapeHtml(senderName)}</span>
                    ${adminBadge}
                </div>
            `;
        }

        // Build attachments HTML
        let attachmentsHtml = '';
        if (message.attachments && message.attachments.length > 0) {
            attachmentsHtml = message.attachments.map(att => {
                const url = att.url || `/storage/${att.path}`;
                const sizeText = att.size ? ` (${(att.size / 1024).toFixed(1)}KB)` : '';
                const colorClass = isSender 
                    ? 'bg-primary-500/30 text-white hover:bg-primary-500/50' 
                    : 'bg-neutral-100 hover:bg-neutral-200 text-neutral-700';
                
                return `
                    <a href="${url}" 
                       download="${this.escapeHtml(att.name)}" 
                       target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors ${colorClass}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="truncate max-w-[150px]">${this.escapeHtml(att.name)}</span>
                        ${sizeText}
                    </a>
                `;
            }).join('');
            
            attachmentsHtml = `<div class="mt-2 space-y-1">${attachmentsHtml}</div>`;
        }

        // Build read status HTML
        let readStatusHtml = '';
        if (isSender && showReadStatus) {
            if (message.status === 'read') {
                readStatusHtml = `
                    <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Read">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                `;
            } else if (message.status === 'delivered') {
                readStatusHtml = `
                    <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Delivered">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
            } else {
                readStatusHtml = `
                    <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Sent">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
            }
        }

        // Build bubble classes
        const bubbleClass = isSender 
            ? 'bg-primary-600 text-white rounded-br-md' 
            : 'bg-white border border-neutral-100 text-neutral-800 rounded-bl-md shadow-sm';

        // Build timestamp
        const timestamp = this.formatRelativeTime(message.created_at);

        return `
            <div class="flex ${alignClass} mb-4 animate-fade-in" data-message-id="${message.id}">
                <div class="max-w-[70%]">
                    ${senderInfoHtml}
                    <div class="rounded-2xl px-4 py-3 ${bubbleClass}">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap break-words">${this.formatMentions(message.message, isSender)}</p>
                        ${attachmentsHtml}
                    </div>
                    <div class="flex items-center gap-1.5 mt-1.5 px-1 text-xs text-neutral-400 ${isSender ? 'justify-end' : 'justify-start'}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>${timestamp}</span>
                        ${readStatusHtml}
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Format a timestamp into a relative time string
     * 
     * @param {string} timestamp - ISO timestamp string
     * @returns {string} Formatted relative time
     */
    formatRelativeTime(timestamp) {
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
        
        const options = { 
            month: 'short', 
            day: 'numeric'
        };
        
        if (date.getFullYear() !== now.getFullYear()) {
            options.year = 'numeric';
        }
        
        return date.toLocaleDateString('en-US', options);
    },

    /**
     * Format a timestamp into a full datetime string
     * 
     * @param {string} timestamp - ISO timestamp string
     * @returns {string} Formatted datetime
     */
    formatDateTime(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    },

    /**
     * Escape HTML special characters to prevent XSS
     * 
     * @param {string} text - Text to escape
     * @returns {string} Escaped text
     */
    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    /**
     * Format message text with highlighted, clickable @mentions
     * 
     * @param {string} text - Message text that may contain @mentions
     * @param {boolean} isSender - Whether the current user is the sender (affects styling)
     * @returns {string} HTML string with mentions wrapped in styled, clickable spans
     */
    formatMentions(text, isSender = false) {
        if (!text) return '';
        
        // First escape the HTML
        const escapedText = this.escapeHtml(text);
        
        // Match @mentions - name consists of words separated by single spaces
        // A mention ends when we encounter: double space, punctuation followed by space, newline, or end of string
        // The key fix: we need to match names that are likely real names (1-3 words typically)
        // Pattern: @ followed by a capitalized word, optionally followed by more capitalized words
        const mentionPattern = /@([A-Z][a-z]+(?:\s[A-Z][a-z]+){0,2})(?=\s|$|[,\.!\?;:])/g;
        
        // Style classes based on message sender
        const mentionClasses = isSender
            ? 'mention-tag mention-sender cursor-pointer font-semibold bg-white/20 hover:bg-white/30 text-white px-1 py-0.5 rounded transition-colors'
            : 'mention-tag mention-receiver cursor-pointer font-semibold bg-primary-100 hover:bg-primary-200 text-primary-700 px-1 py-0.5 rounded transition-colors';
        
        return escapedText.replace(mentionPattern, (match, name) => {
            const trimmedName = name.trim();
            return `<span class="${mentionClasses}" data-mention-name="${this.escapeHtml(trimmedName)}" onclick="window.MessagingUtils.handleMentionClick(event, '${this.escapeHtml(trimmedName).replace(/'/g, "\\'")}')" title="Click to mention ${this.escapeHtml(trimmedName)}">@${this.escapeHtml(trimmedName)}</span>`;
        });
    },

    /**
     * Handle click on a mention tag - adds the mentioned person to the message input
     * 
     * @param {Event} event - Click event
     * @param {string} name - The name of the mentioned person
     */
    handleMentionClick(event, name) {
        event.preventDefault();
        event.stopPropagation();
        
        const textarea = document.getElementById('message-textarea');
        if (!textarea) return;
        
        // Get current cursor position or end of text
        const cursorPos = textarea.selectionStart || textarea.value.length;
        const textBefore = textarea.value.substring(0, cursorPos);
        const textAfter = textarea.value.substring(cursorPos);
        
        // Add space before @ if needed
        const needsSpace = textBefore.length > 0 && !textBefore.endsWith(' ') && !textBefore.endsWith('\n');
        const mentionText = (needsSpace ? ' ' : '') + `@${name} `;
        
        // Insert the mention
        textarea.value = textBefore + mentionText + textAfter;
        
        // Set cursor position after the mention
        const newCursorPos = cursorPos + mentionText.length;
        textarea.setSelectionRange(newCursorPos, newCursorPos);
        textarea.focus();
        
        // Update character count if it exists
        const charCount = document.getElementById('char-count');
        if (charCount) {
            charCount.textContent = textarea.value.length;
        }
        
        // Trigger input event to update any mention tracking
        textarea.dispatchEvent(new Event('input', { bubbles: true }));
    },

    /**
     * Scroll a container to the bottom
     * 
     * @param {HTMLElement|string} container - Container element or selector
     */
    scrollToBottom(container) {
        const el = typeof container === 'string' 
            ? document.querySelector(container) 
            : container;
        
        if (el) {
            el.scrollTop = el.scrollHeight;
        }
    },

    /**
     * Render an empty state for when there are no messages
     * 
     * @param {Object} options - Display options
     * @param {string} options.title - Title text (default: "No messages yet")
     * @param {string} options.subtitle - Subtitle text (default: "Start the conversation!")
     * @returns {string} HTML string for empty state
     */
    renderEmptyState(options = {}) {
        const {
            title = 'No messages yet',
            subtitle = 'Start the conversation!'
        } = options;

        return `
            <div class="flex flex-col items-center justify-center h-full text-center">
                <div class="w-14 h-14 bg-neutral-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <p class="text-neutral-700 font-medium">${this.escapeHtml(title)}</p>
                <p class="text-neutral-500 text-sm mt-1">${this.escapeHtml(subtitle)}</p>
            </div>
        `;
    },

    /**
     * Render a loading state
     * 
     * @returns {string} HTML string for loading state
     */
    renderLoadingState() {
        return `
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary-50 mb-3">
                        <svg class="w-6 h-6 text-primary-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-neutral-500">Loading messages...</p>
                </div>
            </div>
        `;
    },

    /**
     * Render an error state
     * 
     * @param {string} message - Error message to display
     * @returns {string} HTML string for error state
     */
    renderErrorState(message = 'Failed to load messages. Please refresh the page.') {
        return `
            <div class="flex items-center justify-center h-full p-4">
                <div class="rounded-xl bg-error-50 border border-error-200 p-4 max-w-md">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-error-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-error-800">Error</h3>
                            <p class="text-sm text-error-600 mt-1">${this.escapeHtml(message)}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    /**
     * Search messages via API
     * 
     * @param {string} query - Search query (min 2 characters)
     * @param {Object} options - Search options
     * @param {number} options.projectId - Filter by project ID
     * @param {string} options.dateFrom - Filter from date (YYYY-MM-DD)
     * @param {string} options.dateTo - Filter to date (YYYY-MM-DD)
     * @param {number} options.perPage - Results per page (default: 20)
     * @returns {Promise<Object>} Search results
     */
    async searchMessages(query, options = {}) {
        const params = new URLSearchParams({ query });
        
        if (options.projectId) params.append('project_id', options.projectId);
        if (options.dateFrom) params.append('date_from', options.dateFrom);
        if (options.dateTo) params.append('date_to', options.dateTo);
        if (options.perPage) params.append('per_page', options.perPage);

        const response = await fetch(`/api/messages/search?${params.toString()}`, {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        });

        return response.json();
    },

    /**
     * Create a search result element with highlighted snippet
     * 
     * @param {Object} result - Search result object from API
     * @param {Function} onClick - Click handler for navigation
     * @returns {string} HTML string for search result
     */
    createSearchResultElement(result, onClick) {
        const projectTitle = result.project?.title || 'Unknown Project';
        const senderName = result.sender?.fullName || 'Deleted User';
        const timestamp = this.formatRelativeTime(result.created_at);
        
        return `
            <div class="search-result p-4 border-b border-neutral-100 hover:bg-neutral-50 cursor-pointer transition-colors"
                 data-project-id="${result.project_id}"
                 data-message-id="${result.id}">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-neutral-800">${this.escapeHtml(senderName)}</span>
                        <span class="text-xs text-neutral-400">•</span>
                        <span class="text-xs text-neutral-500">${timestamp}</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 bg-primary-50 text-primary-700 rounded-full font-medium">
                        ${this.escapeHtml(projectTitle)}
                    </span>
                </div>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    ${result.highlight || this.escapeHtml(result.message.substring(0, 150))}
                </p>
            </div>
        `;
    },

    /**
     * Render search results container
     * 
     * @param {Array} results - Array of search results
     * @param {string} query - The search query
     * @param {number} total - Total number of results
     * @returns {string} HTML string for search results
     */
    renderSearchResults(results, query, total) {
        if (!results || results.length === 0) {
            return `
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-neutral-600 font-medium">No messages found</p>
                    <p class="text-neutral-400 text-sm mt-1">Try a different search term</p>
                </div>
            `;
        }

        const resultsHtml = results.map(r => this.createSearchResultElement(r)).join('');
        
        return `
            <div class="border-b border-neutral-200 px-4 py-3 bg-neutral-50">
                <p class="text-sm text-neutral-600">
                    Found <span class="font-semibold text-neutral-800">${total}</span> results for 
                    "<span class="font-semibold text-primary-600">${this.escapeHtml(query)}</span>"
                </p>
            </div>
            <div class="search-results-list max-h-[400px] overflow-y-auto">
                ${resultsHtml}
            </div>
        `;
    }
};

// Make available globally for non-module scripts
if (typeof window !== 'undefined') {
    window.MessagingUtils = MessagingUtils;
}

export default MessagingUtils;

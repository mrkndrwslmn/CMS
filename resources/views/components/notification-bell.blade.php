<!-- Notification Bell Component -->
<div x-data="notificationBell()" 
     x-init="init()"
     class="relative">
    <!-- Notification Bell Button -->
    <button @click="toggleDropdown()" 
            class="relative p-2 text-neutral-500 hover:text-neutral-700 transition-colors focus:outline-none">
        <x-lucide-bell class="w-5 h-5" />
        
        <!-- Unread Count Badge -->
        <span x-show="unreadCount > 0" 
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute -top-1 -right-1 bg-error-500 text-white text-xs font-medium rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1">
        </span>
    </button>
    
    <!-- Dropdown Menu -->
    <div x-show="isOpen" 
         @click.away="closeDropdown()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-lg border border-neutral-100 z-50"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-neutral-100 flex justify-between items-center">
            <h3 class="text-base font-medium text-neutral-700">Notifications</h3>
            <button @click="markAllAsRead()" 
                    x-show="unreadCount > 0"
                    class="text-sm text-primary-600 hover:text-primary-700 transition-colors">
                Mark all as read
            </button>
        </div>
        
        <!-- Notification List -->
        <div class="max-h-96 overflow-y-auto">
            <!-- Loading State -->
            <div x-show="isLoading" class="p-8 text-center">
                <x-lucide-loader-2 class="w-8 h-8 text-neutral-300 animate-spin mx-auto" />
                <p class="text-sm text-neutral-500 mt-2">Loading notifications...</p>
            </div>
            
            <!-- Notifications -->
            <template x-if="!isLoading && notifications.length > 0">
                <div>
                    <template x-for="notification in notifications" :key="notification.id">
                        <div @click="markAsRead(notification.id)"
                             :class="notification.read_at ? 'bg-white' : 'bg-primary-50'"
                             class="px-4 py-3 border-b border-neutral-100 hover:bg-neutral-50 cursor-pointer transition-colors">
                            <div class="flex items-start gap-3">
                                <!-- Icon -->
                                <div :class="getNotificationIcon(notification.type).color" 
                                     class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center">
                                    <span x-html="getNotificationIcon(notification.type).icon"></span>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-700" x-text="notification.data.title || 'Notification'"></p>
                                    <p class="text-sm text-neutral-500 mt-0.5 line-clamp-2" x-text="notification.data.message"></p>
                                    <p class="text-xs text-neutral-400 mt-1" x-text="formatTime(notification.created_at)"></p>
                                </div>
                                
                                <!-- Unread Indicator -->
                                <div x-show="!notification.read_at" 
                                     class="flex-shrink-0 w-2 h-2 bg-primary-500 rounded-full mt-2"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            
            <!-- Empty State -->
            <div x-show="!isLoading && notifications.length === 0" 
                 class="p-8 text-center">
                <div class="w-12 h-12 bg-neutral-50 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <x-lucide-bell-off class="w-6 h-6 text-neutral-400" />
                </div>
                <p class="text-sm text-neutral-500">No notifications yet</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-4 py-3 border-t border-neutral-100">
            <a href="{{ route(auth()->user()->role . '.notifications.index') }}" 
               class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                View all notifications
                <x-lucide-arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>
</div><script>
function notificationBell() {
    return {
        isOpen: false,
        isLoading: false,
        notifications: [],
        unreadCount: 0,
        pollingInterval: null,
        
        init() {
            // Only start polling if no other instance is running
            if (!window.notificationPollingActive) {
                window.notificationPollingActive = true;
                this.fetchNotifications();
                // Poll for new notifications every 60 seconds (reduced from 30s)
                this.pollingInterval = setInterval(() => this.fetchNotifications(), 60000);
            }
        },
        
        async fetchNotifications() {
            this.isLoading = true;
            try {
                const response = await fetch('{{ route("notifications.fetch") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                this.notifications = data.notifications || [];
                this.unreadCount = data.unreadCount || 0;
            } catch (error) {
                console.error('Error fetching notifications:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.fetchNotifications();
            }
        },
        
        closeDropdown() {
            this.isOpen = false;
        },
        
        async markAsRead(notificationId) {
            try {
                await fetch(`{{ url('/notifications') }}/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                // Update local state
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification && !notification.read_at) {
                    notification.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        
        async markAllAsRead() {
            try {
                await fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                // Update local state
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },
        
        // Lucide-style SVG icons (outline, 24x24, stroke-width 2)
        getNotificationIcon(type) {
            const icons = {
                'task_assigned': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>', 
                    color: 'bg-primary-500' 
                },
                'task_completed': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 
                    color: 'bg-success-500' 
                },
                'task_updated': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>', 
                    color: 'bg-warning-500' 
                },
                'project_assigned': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>', 
                    color: 'bg-primary-600' 
                },
                'project_completed': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>', 
                    color: 'bg-success-500' 
                },
                'request_approved': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>', 
                    color: 'bg-success-500' 
                },
                'request_rejected': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>', 
                    color: 'bg-error-500' 
                },
                'payment_received': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 
                    color: 'bg-success-500' 
                },
                'payment_confirmed': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>', 
                    color: 'bg-success-500' 
                },
                'budget_request': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 
                    color: 'bg-warning-500' 
                },
                'budget_approved': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 
                    color: 'bg-success-500' 
                },
                'budget_rejected': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>', 
                    color: 'bg-error-500' 
                },
                'new_message': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>', 
                    color: 'bg-primary-500' 
                },
                'system': { 
                    icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 
                    color: 'bg-neutral-500' 
                }
            };
            return icons[type] || { 
                icon: '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>', 
                color: 'bg-neutral-400' 
            };
        },
        
        formatTime(timestamp) {
            const now = new Date();
            const time = new Date(timestamp);
            const diff = Math.floor((now - time) / 1000); // seconds
            
            if (diff < 60) return 'Just now';
            if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
            if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;
            
            return time.toLocaleDateString();
        }
    }
}
</script>

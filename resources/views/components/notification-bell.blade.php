<!-- Notification Bell Component -->
<div x-data="notificationBell()" 
     x-init="init()"
     class="relative">
    <!-- Notification Bell Button -->
    <button @click="toggleDropdown()" 
            class="relative p-2 text-gray-700 hover:text-primary transition-colors duration-300 focus:outline-none">
        <i class="fas fa-bell text-xl"></i>
        
        <!-- Unread Count Badge -->
        <span x-show="unreadCount > 0" 
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1.5 animate-pulse">
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
         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
            <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
            <button @click="markAllAsRead()" 
                    x-show="unreadCount > 0"
                    class="text-sm text-primary hover:text-secondary transition-colors">
                Mark all as read
            </button>
        </div>
        
        <!-- Notification List -->
        <div class="max-h-96 overflow-y-auto">
            <!-- Loading State -->
            <div x-show="isLoading" class="p-8 text-center">
                <i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i>
                <p class="text-gray-500 mt-2">Loading notifications...</p>
            </div>
            
            <!-- Notifications -->
            <template x-if="!isLoading && notifications.length > 0">
                <div>
                    <template x-for="notification in notifications" :key="notification.id">
                        <div @click="markAsRead(notification.id)"
                             :class="notification.read_at ? 'bg-white' : 'bg-blue-50'"
                             class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors">
                            <div class="flex items-start space-x-3">
                                <!-- Icon -->
                                <div :class="getNotificationIcon(notification.type).color" 
                                     class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center">
                                    <i :class="getNotificationIcon(notification.type).icon" class="text-white"></i>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900" x-text="notification.data.title || 'Notification'"></p>
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2" x-text="notification.data.message"></p>
                                    <p class="text-xs text-gray-400 mt-1" x-text="formatTime(notification.created_at)"></p>
                                </div>
                                
                                <!-- Unread Indicator -->
                                <div x-show="!notification.read_at" 
                                     class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            
            <!-- Empty State -->
            <div x-show="!isLoading && notifications.length === 0" 
                 class="p-8 text-center">
                <i class="fas fa-bell-slash text-4xl text-gray-300"></i>
                <p class="text-gray-500 mt-3">No notifications yet</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 rounded-b-lg border-t border-gray-200">
            <a href="{{ route(auth()->user()->role . '.notifications.index') }}" 
               class="text-sm text-primary hover:text-secondary font-medium transition-colors">
                View all notifications →
            </a>
        </div>
    </div>
</div>

<script>
function notificationBell() {
    return {
        isOpen: false,
        isLoading: false,
        notifications: [],
        unreadCount: 0,
        
        init() {
            this.fetchNotifications();
            // Poll for new notifications every 30 seconds
            setInterval(() => this.fetchNotifications(), 30000);
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
        
        getNotificationIcon(type) {
            const icons = {
                'task_assigned': { icon: 'fas fa-tasks', color: 'bg-blue-500' },
                'task_completed': { icon: 'fas fa-check-circle', color: 'bg-green-500' },
                'task_updated': { icon: 'fas fa-edit', color: 'bg-yellow-500' },
                'project_assigned': { icon: 'fas fa-project-diagram', color: 'bg-purple-500' },
                'project_completed': { icon: 'fas fa-flag-checkered', color: 'bg-green-600' },
                'request_approved': { icon: 'fas fa-thumbs-up', color: 'bg-green-500' },
                'request_rejected': { icon: 'fas fa-times-circle', color: 'bg-red-500' },
                'payment_received': { icon: 'fas fa-money-bill-wave', color: 'bg-green-600' },
                'payment_confirmed': { icon: 'fas fa-credit-card', color: 'bg-green-500' },
                'budget_request': { icon: 'fas fa-dollar-sign', color: 'bg-orange-500' },
                'budget_approved': { icon: 'fas fa-check-double', color: 'bg-green-500' },
                'budget_rejected': { icon: 'fas fa-ban', color: 'bg-red-500' },
                'new_message': { icon: 'fas fa-envelope', color: 'bg-blue-600' },
                'system': { icon: 'fas fa-info-circle', color: 'bg-gray-500' }
            };
            return icons[type] || { icon: 'fas fa-bell', color: 'bg-gray-500' };
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

import './bootstrap';
import { messagingService } from './messaging.js';

// Initialize messaging service when DOM is loaded
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Initialize Firebase messaging
        await messagingService.initialize();
        
        // Request notification permission
        await messagingService.requestNotificationPermission();
        
        // Setup foreground message listener
        messagingService.setupForegroundMessageListener();
        
        // Update unread count
        await messagingService.updateUnreadCount();
        
    } catch (error) {
        console.error('Error initializing messaging service:', error);
    }
});

// Export for use in other modules
window.messagingService = messagingService;

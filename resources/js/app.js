import './bootstrap';
import { messagingService } from './messaging.js';

// Initialize messaging service when DOM is loaded (only for authenticated users)
document.addEventListener('DOMContentLoaded', async () => {
    // Check if user is authenticated (look for meta tag or check for auth-related elements)
    const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.content === 'true'
        || document.querySelector('nav form[action*="logout"]') !== null;
    
    // Only initialize messaging for authenticated users
    if (isAuthenticated) {
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
    }
});

// Export for use in other modules
window.messagingService = messagingService;

importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js');

// Initialize Firebase app in service worker
firebase.initializeApp({
    apiKey: "AIzaSyABqmvQfujeSvFGj7-tYIUwWp_ruKPU394",
    authDomain: "treis-adiutor-cms.firebaseapp.com",
    projectId: "treis-adiutor-cms",
    storageBucket: "treis-adiutor-cms.firebasestorage.app",
    messagingSenderId: "948951588175",
    appId: "1:948951588175:web:66146577c309f3718cf93a"
});

// Retrieve an instance of Firebase Messaging
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);

    // Customize notification
    const notificationTitle = payload.notification?.title || 'New Message';
    const notificationOptions = {
        body: payload.notification?.body || 'You have a new message',
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        tag: payload.data?.conversation_id || 'default',
        data: payload.data,
        requireInteraction: true,
        actions: [
            {
                action: 'open',
                title: 'View Message'
            },
            {
                action: 'close',
                title: 'Dismiss'
            }
        ]
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
    console.log('[firebase-messaging-sw.js] Notification click received.');

    event.notification.close();

    if (event.action === 'open') {
        // Open the message page
        const projectId = event.notification.data?.project_id;
        if (projectId) {
            const role = event.notification.data?.role || 'client';
            const url = `/${role}/messages/${projectId}`;
            event.waitUntil(
                clients.openWindow(url)
            );
        }
    } else if (event.action === 'close') {
        // Just close the notification
        return;
    } else {
        // Default action: open the message page
        const projectId = event.notification.data?.project_id;
        if (projectId) {
            const role = event.notification.data?.role || 'client';
            const url = `/${role}/messages/${projectId}`;
            event.waitUntil(
                clients.openWindow(url)
            );
        } else {
            // Open messages index
            event.waitUntil(
                clients.openWindow('/messages')
            );
        }
    }
});

// Service worker installation
self.addEventListener('install', (event) => {
    console.log('[firebase-messaging-sw.js] Service worker installed');
    self.skipWaiting();
});

// Service worker activation
self.addEventListener('activate', (event) => {
    console.log('[firebase-messaging-sw.js] Service worker activated');
    event.waitUntil(clients.claim());
});

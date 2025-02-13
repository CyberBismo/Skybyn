const CACHE_NAME = 'v0.1'; // Change this to force update
const CACHE_FILES = [
    '/', // Homepage
    '/index.php'
];

// Install: Cache Static Files
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(CACHE_FILES);
        })
    );
    self.skipWaiting(); // Activate immediately
});

// Activate: Delete Old Caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim()) // Take control of clients
    );
});

// Fetch: Serve from Cache First, Then Network
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Bypass cache for JavaScript files by adding a cache-busting query
    if (url.pathname.endsWith('.js')) {
        event.respondWith(fetch(event.request.url + '?v=' + new Date().getTime())); 
        return;
    }

    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request).then(networkResponse => {
                return caches.open(CACHE_NAME).then(cache => {
                    cache.put(event.request, networkResponse.clone()); // Update cache
                    return networkResponse;
                });
            });
        }).catch(() => caches.match('/index.php')) // Fallback for offline
    );
});

// Manual Cache Clear & Reload Clients
self.addEventListener('message', (event) => {
    if (event.data.action === 'clear-cache') {
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => caches.delete(cache))
            );
        }).then(() => {
            self.clients.matchAll().then(clients => {
                clients.forEach(client => client.navigate(client.url)); // Reload all open tabs
            });
        });
    }
});

// Push Notifications
self.addEventListener('push', (event) => {
    console.log('📨 Push event received in Service Worker:', event);

    if (!event.data) {
        console.warn('⚠️ Push event received, but no data.');
        return;
    }

    const data = event.data.json();
    console.log('📥 Push Data:', data);

    const options = {
        body: data.body || 'You have a new notification!',
        icon: '/assets/icons/notification.png',
        badge: '/assets/icons/badge.png',
        data: { url: data.url || '/' }
    };

    console.log('Showing notification:', data);

    event.waitUntil(
        self.registration.showNotification(data.title || 'New Message', options)
    );
});

// Handle Notification Click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const notificationURL = event.notification.data.url || '/';
    event.waitUntil(
        clients.matchAll({ type: 'window' }).then((clientsArr) => {
            for (const client of clientsArr) {
                if (client.url === notificationURL && 'focus' in client) {
                    return client.focus();
                }
            }
            return clients.openWindow(notificationURL);
        })
    );
});

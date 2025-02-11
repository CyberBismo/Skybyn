const CACHE_NAME = 'v1'; // Change this to force update
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
        }).catch(() => caches.match('/index.html')) // Fallback for offline
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

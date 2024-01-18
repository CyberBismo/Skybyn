const CACHE_NAME = 'v4';
const urlsToCache = [
    '/',
    '/assets/js/jquery.min.js',
    '/assets/js/service-worker.js',
    '/fontawe/css/all.css',
    '/fontawe/css/all.min.css',
    '/fontawe/css/brands.css',
    '/fontawe/css/brands.min.css',
    '/fontawe/css/fontawesome.css',
    '/fontawe/css/fontawesome.min.css',
    '/fontawe/css/regular.css',
    '/fontawe/css/regular.min.css',
    '/fontawe/css/solid.css',
    '/fontawe/css/solid.min.css',
    '/assets/images/blank.png',
    '/assets/images/clouds-old.png',
    '/assets/images/clouds.png',
    '/assets/images/icon.png',
    '/assets/images/icon_light.png',
    '/assets/images/logo.png',
    '/assets/images/logo_clean.png',
    '/assets/images/logo_dark.png',
    '/assets/images/logo_faded.png',
    '/assets/images/logo_faded_clean.png',
    '/assets/images/logo_fav.png',
    '/assets/images/logo_light.png',
    '/assets/images/logo_old.png',
    '/assets/images/logo_round.png',
    '/assets/images/logo_rounded.png',
    '/assets/images/shop/5435.jpg',
    '/assets/images/shop/computer.png',
    '/assets/images/shop/music.png',
    '/assets/images/sky.png',
    '/assets/images/status/1b.png',
    '/assets/images/status/2b.png',
    '/assets/images/status/3b.png',
    '/assets/images/status/4b.png',
    '/assets/images/status/5b.png',
    '/assets/images/status/away.png',
    '/assets/images/status/busy.png',
    '/assets/images/status/offline.png',
    '/assets/images/status/online.png',
    // ... add other URLs as needed
];

// Install a service worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache); // Add new files to cache
            })
    );
});

// Cache and return requests
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Cache hit - return response
                if (response) {
                    return response;
                }
                return fetch(event.request);
            }
        )
    );
});

// Update a service worker
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName); // Delete old caches
                    }
                })
            );
        })
    );
});

self.addEventListener('push', function(event) {
    const options = {
        body: event.data.text(),
        // You can customize your options: icons, images, actions, etc.
    };

    event.waitUntil(
        self.registration.showNotification('Notification Title', options)
    );
});

const CACHE_NAME = 'myfarm-static-v4';

// Install event
self.addEventListener('install', event => {
    console.log('Service Worker v4 installing...');
    // Skip waiting to activate immediately
    self.skipWaiting();
});

// Fetch event - Only cache static assets, NEVER cache HTML or API calls
self.addEventListener('fetch', event => {
    const url = new URL(event.request.url);

    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Skip cross-origin requests
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }

    // NEVER cache or intercept HTML pages - always fetch fresh
    const acceptHeader = event.request.headers.get('accept') || '';
    if (acceptHeader.includes('text/html')) {
        event.respondWith(
            fetch(event.request, {
                cache: 'no-store' // Force fresh fetch, no browser cache
            })
        );
        return;
    }

    // NEVER cache admin routes, livewire, or login pages
    if (url.pathname.startsWith('/livewire') ||
        url.pathname.startsWith('/admin') ||
        url.pathname.includes('/login') ||
        url.pathname === '/' ||
        url.pathname.startsWith('/api/')) {
        return; // Don't intercept
    }

    // Only cache specific file types (static assets only)
    const cacheable = /\.(js|css|png|jpg|jpeg|gif|webp|svg|woff|woff2|ttf|eot|ico)$/i.test(url.pathname);

    if (!cacheable) {
        return; // Don't intercept
    }

    // Network-first strategy for static assets
    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Only cache successful responses
                if (response && response.status === 200 && response.type === 'basic') {
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseToCache);
                    });
                }
                return response;
            })
            .catch(() => {
                // On network failure, try cache as fallback
                return caches.match(event.request);
            })
    );
});

// Activate event - Clean up old caches
self.addEventListener('activate', event => {
    console.log('Service Worker v4 activating...');
    event.waitUntil(
        caches.keys().then(cacheNames => {
            // Delete ALL old caches
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            console.log('Service Worker v4 activated, claiming clients');
            // Take control of all pages immediately
            return self.clients.claim();
        })
    );
});

// Message handler for debugging
self.addEventListener('message', event => {
    if (event.data === 'skipWaiting') {
        self.skipWaiting();
    }
});
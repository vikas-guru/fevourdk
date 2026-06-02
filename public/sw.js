const CACHE_VERSION = 'fevourdk-v6';
const OFFLINE_URL = '/offline.html';

const APP_SHELL_ASSETS = [
    '/',
    '/login',
    '/manifest.json',
    '/offline.html',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_VERSION)
            .then(async (cache) => {
                await Promise.all(
                    APP_SHELL_ASSETS.map((asset) =>
                        cache.add(asset).catch(() => null)
                    )
                );
            })
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) => Promise.all(keys.filter((key) => key !== CACHE_VERSION).map((key) => caches.delete(key))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }

    const requestUrl = new URL(event.request.url);

    if (requestUrl.origin !== self.location.origin) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                if (response.ok && event.request.url.startsWith(self.location.origin)) {
                    const responseClone = response.clone();
                    caches.open(CACHE_VERSION).then((cache) => cache.put(event.request, responseClone));
                }
                return response;
            })
            .catch(async () => {
                const cached = await caches.match(event.request);
                if (cached) {
                    return cached;
                }

                if (event.request.mode === 'navigate') {
                    // Only show the branded offline page when the device is actually offline.
                    // Otherwise a failed fetch (server down, TLS, DNS, timeout) was wrongly shown as "You are offline".
                    if (self.navigator?.onLine === false) {
                        const offlinePage = await caches.match(OFFLINE_URL);
                        if (offlinePage) {
                            return offlinePage;
                        }
                    }
                }

                // For non-navigation requests, surface a real network failure
                // instead of returning plain "Offline" text in UI widgets/modals.
                return Response.error();
            })
    );
});

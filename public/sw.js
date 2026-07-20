// NOIR Service Worker — cache-first for static assets, network-first for API
const CACHE_NAME = 'noir-v1';
const STATIC_ASSETS = [
    '/',
    '/home',
    '/protocolos',
    '/sistema',
    '/organizacao',
];

self.addEventListener('install', (e) => {
    e.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (e) => {
    e.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (e) => {
    const { request } = e;
    const url = new URL(request.url);

    // Skip non-GET and external
    if (request.method !== 'GET' || url.origin !== self.location.origin) return;

    // API calls: network-first
    if (url.pathname.startsWith('/api/') || url.pathname.startsWith('/sistema/pasta/')) {
        e.respondWith(
            fetch(request).catch(() => caches.match(request))
        );
        return;
    }

    // Static assets: cache-first
    e.respondWith(
        caches.match(request).then((cached) => {
            if (cached) return cached;
            return fetch(request).then((response) => {
                if (response.ok) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
                }
                return response;
            });
        })
    );
});

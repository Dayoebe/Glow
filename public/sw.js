const CACHE_VERSION = 'v1-2026-03-04';
const STATIC_CACHE = `glow-static-${CACHE_VERSION}`;
const RUNTIME_CACHE = `glow-runtime-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline.html';

const PRECACHE_URLS = [
    OFFLINE_URL,
    '/manifest.webmanifest',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/icons/icon-512x512-maskable.png',
    '/icons/apple-touch-icon.png',
    '/favicon.ico',
];

const EXCLUDED_PREFIXES = [
    '/admin',
    '/api',
    '/livewire',
    '/broadcasting',
    '/sanctum',
    '/login',
    '/register',
    '/logout',
    '/password',
    '/telescope',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches
            .open(STATIC_CACHE)
            .then((cache) => cache.addAll(PRECACHE_URLS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key !== STATIC_CACHE && key !== RUNTIME_CACHE)
                    .map((key) => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

function isExcludedPath(pathname) {
    return EXCLUDED_PREFIXES.some((prefix) => pathname.startsWith(prefix));
}

async function networkFirst(request) {
    const runtimeCache = await caches.open(RUNTIME_CACHE);

    try {
        const response = await fetch(request);
        if (response && response.ok) {
            runtimeCache.put(request, response.clone());
        }
        return response;
    } catch (_error) {
        const cached = await runtimeCache.match(request);
        return cached || caches.match(OFFLINE_URL);
    }
}

async function staleWhileRevalidate(request) {
    const runtimeCache = await caches.open(RUNTIME_CACHE);
    const cached = await runtimeCache.match(request);

    const networkPromise = fetch(request)
        .then((response) => {
            if (response && response.ok) {
                runtimeCache.put(request, response.clone());
            }
            return response;
        })
        .catch(() => null);

    if (cached) {
        return cached;
    }

    const networkResponse = await networkPromise;
    return (
        networkResponse ||
        new Response(null, {
            status: 504,
            statusText: 'Gateway Timeout',
        })
    );
}

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) {
        return;
    }

    if (isExcludedPath(url.pathname)) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(networkFirst(request));
        return;
    }

    if (request.destination === 'audio' || request.destination === 'video') {
        return;
    }

    const shouldCacheStatic =
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font' ||
        url.pathname.startsWith('/build/');

    if (shouldCacheStatic) {
        event.respondWith(staleWhileRevalidate(request));
    }
});

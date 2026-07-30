const CACHE_VERSION = 'v3-2026-07-30';
const STATIC_CACHE = `glow-static-${CACHE_VERSION}`;
const RUNTIME_CACHE = `glow-runtime-${CACHE_VERSION}`;
const APP_BASE_URL = new URL('./', self.location.href);
const appUrl = (path) => new URL(path.replace(/^\/+/, ''), APP_BASE_URL).toString();
const appPath = (path) => new URL(path.replace(/^\/+/, ''), APP_BASE_URL).pathname;
const OFFLINE_URL = appUrl('offline.html');

const PRECACHE_URLS = [
    OFFLINE_URL,
    appUrl('manifest.webmanifest'),
    appUrl('icons/icon-192x192.png'),
    appUrl('icons/icon-512x512.png'),
    appUrl('icons/icon-512x512-maskable.png'),
    appUrl('icons/apple-touch-icon.png'),
    appUrl('favicon.ico'),
];

const EXCLUDED_PREFIXES = [
    'admin',
    'dashboard',
    'profile',
    'settings',
    'api',
    'livewire',
    'broadcasting',
    'sanctum',
    'login',
    'register',
    'logout',
    'password',
    'telescope',
].map(appPath);

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
    return EXCLUDED_PREFIXES.some(
        (prefix) => pathname === prefix || pathname.startsWith(`${prefix}/`)
    );
}

function isCacheableResponse(response) {
    if (!response || !response.ok) {
        return false;
    }

    const cacheControl = response.headers.get('Cache-Control') || '';

    return !/(?:^|,)\s*(?:private|no-store)(?:\s|,|$)/i.test(cacheControl);
}

async function networkFirst(request) {
    const runtimeCache = await caches.open(RUNTIME_CACHE);

    try {
        const response = await fetch(request);
        if (isCacheableResponse(response)) {
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
            if (isCacheableResponse(response)) {
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
        url.pathname.startsWith(appPath('build/'));

    if (shouldCacheStatic) {
        event.respondWith(staleWhileRevalidate(request));
    }
});

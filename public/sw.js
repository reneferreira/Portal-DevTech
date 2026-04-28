const CACHE_NAME = 'portal-devtech-v3';
const OFFLINE_URLS = [
    '/',
    '/manifest.webmanifest',
    '/icons/icon-192.png',
    '/icons/icon-512.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => Promise.allSettled(OFFLINE_URLS.map((url) => cache.add(url))))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request).then((response) => response || caches.match('/')))
    );
});

self.addEventListener('push', (event) => {
    const fallback = {
        title: 'Portal DevTech',
        body: 'Tem novidade no portal.',
        url: '/',
        icon: '/icons/icon-192.png',
        badge: '/icons/badge-96.png'
    };

    event.waitUntil(
        Promise.resolve()
            .then(() => event.data ? event.data.json() : fetch('/push/latest-message', { cache: 'no-store' }).then((response) => response.json()))
            .catch(() => fallback)
            .then((payload) => {
                const data = { ...fallback, ...payload };

                return self.registration.showNotification(data.title, {
                    body: data.body,
                    icon: data.icon,
                    badge: data.badge,
                    data: {
                        url: data.url || '/'
                    }
                });
            })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = new URL(event.notification.data?.url || '/', self.location.origin).href;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if ('focus' in client && client.url === targetUrl) {
                    return client.focus();
                }
            }

            return clients.openWindow(targetUrl);
        })
    );
});

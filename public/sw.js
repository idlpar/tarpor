//
// const CACHE_NAME = 'tarpor-cache-v1';
// const OFFLINE_URL = '/offline.html';
// const ASSETS_TO_CACHE = [
//     '/',
//     '/offline.html',
//     '/build/assets/app.css',
//     '/build/assets/app.js',
//     '/favicon.ico',
//     '/icon-192x192.png',
//     '/icon-512x512.png',
//     '/images/logo.png',
//     '/images/hero-kids.jpg',
//     '/images/hero-men.jpg',
//     '/images/kids-category.jpg',
//     '/images/men-category.jpg',
//     '/images/festive-category.jpg',
//     '/images/kids-summer.jpg',
//     '/images/mens-formal.jpg',
//     '/images/eid-special.jpg',
//     '/images/winter-ethnic.jpg',
//     '/images/kids-kurta.jpg',
//     '/images/mens-panjabi.jpg',
//     '/images/kids-tshirt.jpg',
//     '/images/mens-shirt.jpg',
//     '/images/kids-sherwani.jpg',
//     '/images/mens-kurta.jpg',
//     '/images/blog-trends.jpg',
//     '/images/blog-ethnic.jpg',
//     '/images/blog-sustainable.jpg',
//     '/images/about-tarpor.jpg'
// ];
//
// self.addEventListener('install', (event) => {
//     event.waitUntil(
//         caches.open(CACHE_NAME).then((cache) => {
//             return cache.addAll(ASSETS_TO_CACHE).catch((error) => {
//                 console.error('Failed to cache assets:', error);
//             });
//         }).then(() => self.skipWaiting())
//     );
// });
//
// self.addEventListener('activate', (event) => {
//     event.waitUntil(
//         caches.keys().then((cacheNames) => {
//             return Promise.all(
//                 cacheNames.map((cacheName) => {
//                     if (cacheName !== CACHE_NAME) {
//                         return caches.delete(cacheName);
//                     }
//                 })
//             );
//         }).then(() => self.clients.claim())
//     );
// });
//
// self.addEventListener('fetch', (event) => {
//     const url = new URL(event.request.url);
//     if (event.request.method !== 'GET' ||
//         url.pathname.startsWith('/admin') ||
//         url.pathname.startsWith('/api') ||
//         url.pathname.startsWith('/login') ||
//         url.pathname.startsWith('/logout')) {
//         return;
//     }
//
//     if (event.request.mode === 'navigate') {
//         event.respondWith(
//             fetch(event.request)
//                 .then((response) => {
//                     return caches.open(CACHE_NAME).then((cache) => {
//                         cache.put(event.request, response.clone());
//                         return response;
//                     });
//                 })
//                 .catch(() => {
//                     return caches.match(event.request).then((cachedResponse) => {
//                         return cachedResponse || caches.match(OFFLINE_URL);
//                     });
//                 })
//         );
//         return;
//     }
//
//     if (url.pathname.match(/\.(css|js|png|jpg|jpeg|svg|woff2?|ttf|eot)$/)) {
//         event.respondWith(
//             caches.match(event.request).then((cachedResponse) => {
//                 return cachedResponse || fetch(event.request).then((networkResponse) => {
//                     return caches.open(CACHE_NAME).then((cache) => {
//                         cache.put(event.request, networkResponse.clone());
//                         return networkResponse;
//                     });
//                 });
//             })
//         );
//         return;
//     }
//
//     event.respondWith(
//         fetch(event.request)
//             .then((networkResponse) => {
//                 return caches.open(CACHE_NAME).then((cache) => {
//                     cache.put(event.request, networkResponse.clone());
//                     return networkResponse;
//                 });
//             })
//             .catch(() => caches.match(event.request))
//     );
// });
//
// function limitCacheSize(cacheName, maxItems) {
//     caches.open(cacheName).then((cache) => {
//         cache.keys().then((keys) => {
//             if (keys.length > maxItems) {
//                 cache.delete(keys[0]).then(() => limitCacheSize(cacheName, maxItems));
//             }
//         });
//     });
// }
//
// self.addEventListener('message', (event) => {
//     if (event.data.action === 'clean-cache') {
//         limitCacheSize(CACHE_NAME, 50);
//     }
// });

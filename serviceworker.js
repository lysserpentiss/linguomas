const CACHE_NAME = 'linguomas-cache-v1';
const urlsToCache = [
  '/linguomas/',              // ← ЭТО ГЛАВНОЕ! КОРЕНЬ ПАПКИ
  '/linguomas/index.html',
  'index.html',
  'spanish.html',
  'portugues.html',
  'compare.html',
  'practice.php',
  'practice_advanced.php',
  'downloads.html',
  'style.css',
  'script.js',
  'practice_data.js',
  'images/brazil.jpg',
  'images/spain.jpg',
  'images/bs.jpeg',
  'images/icon-192.png',
  'images/icon-512.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('🔧 Кеш открыт');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});

self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});
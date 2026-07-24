// serviceworker.js
const CACHE_NAME = 'linguomas-cache-v1';
const urlsToCache = [
  '/',
  '/index.html',
  '/spanish.html',
  '/portugues.html',
  '/compare.html',
  '/practice.php',
  '/practice_advanced.php',
  '/downloads.html',
  '/profile.php',
  '/feedback.html',
  '/style.css',
  '/script.js',
  '/practice_data.js',
  '/images/brazil.jpg',
  '/images/spain.jpg',
  '/images/bs.jpeg',
  '/images/icon-192.png',
  '/images/icon-512.png'
];

// Устанавливаем кеш при установке сервис-воркера
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('🔧 Кеш открыт');
        return cache.addAll(urlsToCache);
      })
  );
});

// Перехватываем запросы и отдаём из кеша, если есть
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Если файл есть в кеше — отдаём его
        if (response) {
          return response;
        }
        // Если нет — идём в сеть
        return fetch(event.request);
      })
  );
});

// Обновляем кеш при активации
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
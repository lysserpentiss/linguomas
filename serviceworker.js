const CACHE_NAME = 'linguomas-cache-v1';
const urlsToCache = [
  '.',
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

// Установка Service Worker — кешируем файлы
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('✅ Кеш открыт');
        return cache.addAll(urlsToCache);
      })
      .catch(err => {
        console.warn('⚠️ Ошибка кеширования:', err);
      })
  );
});

// Перехват запросов — отдаём из кеша
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response; // Отдаём из кеша
        }
        return fetch(event.request).catch(() => {
          // Если сеть недоступна и файла нет в кеше — отдаём fallback
          return caches.match('index.html');
        });
      })
  );
});

// Активация — удаляем старые кеши
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
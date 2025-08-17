const CACHE = 'km-cache-v1';
self.addEventListener('install', e=>{
  e.waitUntil(caches.open(CACHE).then(c=>c.addAll(['/','/css/app.css','/js/app.js'])));
});
self.addEventListener('fetch', e=>{
  const url = new URL(e.request.url);
  // API دائمًا أونلاين، باقيها Cache-first
  if (url.pathname.startsWith('/api/')) {
    e.respondWith(fetch(e.request).catch(()=>caches.match(e.request)));
  } else {
    e.respondWith(caches.match(e.request).then(r=> r || fetch(e.request)));
  }
});

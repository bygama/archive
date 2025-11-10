let cacheName ='cache';

let cacheDynamic = 'cache2';

self.addEventListener('install', (e) =>{
    self.skipWaiting();
    /* e.waitUntil(
        caches.open(cacheName)
        .then((cache)=>{
            cache.addAll([
                'assets/banner-LEGENDSGG.webp'
                ,'assets/demacia-LEGENDSGG.webp'
                ,'assets/Logo-Legends.webp'
                ,'assets/Mejora-con-los-poros.webp'
                ,'assets/search.svg'
                ,'js/api.js'
            ])
        })
    ) */
    console.log('sw instalado: ', e) //installEvent
});

self.addEventListener('activate', (e) =>{
    console.log('sw Activado: ', e) //extendableEvent
});

self.addEventListener('fetch', (e) =>{
    e.respondWith(
        caches.match(e.request).then((cached) => {
            if (cached) return cached;

            const requestToFetch = e.request.clone();
            return fetch(requestToFetch)
                .then((networkResponse) => {
                    // Si la respuesta es válida, cachear y devolver
                    if (!networkResponse || networkResponse.status !== 200 || networkResponse.type === 'opaque') {
                        return networkResponse;
                    }

                    const responseToCache = networkResponse.clone();
                    caches.open(cacheDynamic).then((cache) => {
                        cache.put(e.request, responseToCache);
                    });
                    return networkResponse; // devolver la respuesta a la página
                })
                .catch(() => {
                    // Fallback simple: devolver de cache si existe o una respuesta básica
                    return caches.match(e.request).then((fallback) => fallback || new Response('Offline', { status: 503 }));
                });
        })
    )
})

//notificaciones push
self.addEventListener('push', (e)=>{
    let data = e.data ? e.data.text() : 'no vino texto';
    let options = {
        body: data,
        icon: 'assets/icons/android-icon-192x192.png',
        badge: 'assets/icons/android-icon-48x48.png',
        image: 'assets/img/No-Image-Placeholder.svg.png',
        vibrate: [100, 50, 100],
        renotify: true,
        actions: [
            {action: 'SI', title: 'Ver detalle'},
            {action: 'NO', title: 'Cerrar'}
        ],
        tag: 'notificacion-sample'
        
    };
    e.waitUntil(
        self.registration.showNotification('Notificacion desde SW', options)
    );
});

self.addEventListener('notificationclick', (e)=>{
    console.log('notificacion click: ', e);
    if(e.action === 'SI'){
        console.log('el usuario quiere ver el detalle');
        clients.openWindow('https://www.google.com');
    }else{
        console.log('el usuario no quiere ver el detalle');
    }
    e.notification.close();
});
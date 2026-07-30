// Registrar Service Worker (usar ruta relativa para funcionar bajo subrutas como GitHub Pages)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', async () => {
        const tryRegister = async (url) => {
            try {
                const reg = await navigator.serviceWorker.register(url);
                console.log('ServiceWorker registrado ok. Alcance:', reg.scope);
                return true;
            } catch (err) {
                console.warn('Fallo registrando SW en', url, err);
                return false;
            }
        };

        
        const okRelative = await tryRegister('sw.js');
        
        if (!okRelative) {
            await tryRegister('/sw.js');
        }

        
        if(window.Notification && Notification.permission !== 'denied'){
            setTimeout(()=>{
                Notification.requestPermission((status)=>{
                    console.log('Permiso de notificaciones: ', status);
                    
                    
                    if(status === 'granted'){
                        new Notification('Hola! Soy una notificacion',{
                            body: 'Gracias por permitir las notificaciones',
                            icon: 'assets/icons/android-icon-192x192.png',
                            image: 'assets/img/No-Image-Placeholder.svg.png',
                            badge: 'assets/icons/android-icon-48x48.png',
                            vibrate: [100, 50, 100],
                            renotify: true,
                            tag: 'notificacion-sample'
                        });
                    }
                });
            }, 2000);
        }
    });
}

(() => {
    // Mostrar/ocultar botón de instalación en función del evento beforeinstallprompt
    let deferredPrompt = null;

    const setupInstallButton = () => {
        const btn = document.getElementById('installBtn');
        if (!btn) return;

        // Ocultar el botón por defecto hasta que el PWA sea instalable
        btn.style.display = 'none';
        btn.setAttribute('aria-disabled', 'true');
        btn.addEventListener('click', async (ev) => {
            ev.preventDefault();
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const choiceResult = await deferredPrompt.userChoice;
            console.log('Resultado de instalación:', choiceResult);
            // El prompt solo se puede usar una vez
            deferredPrompt = null;
            btn.style.display = 'none';
            btn.setAttribute('aria-disabled', 'true');
        }, { once: false });
    };

    setupInstallButton();

    window.addEventListener('beforeinstallprompt', (e) => {
        // Evitar el mini-infobar y conservar el evento
        e.preventDefault();
        deferredPrompt = e;
        const btn = document.getElementById('installBtn');
        if (btn) {
            btn.style.display = 'flex'; // visible cuando es instalable
            btn.removeAttribute('aria-disabled');
        }
    });

    // Opcional: detectar instalación completada
    window.addEventListener('appinstalled', () => {
        console.log('PWA instalada');
        const btn = document.getElementById('installBtn');
        if (btn) {
            btn.style.display = 'none';
            btn.setAttribute('aria-disabled', 'true');
        }
    });
})();

// Función para mostrar notificación de estado de conexión
async function mostrarNotificacionConexion(titulo, mensaje, isOnline) {
  if (!('Notification' in window)) {
    console.log('Notificaciones no soportadas');
    return;
  }

  // Si el permiso no está concedido, no intentar mostrar notificación
  if (Notification.permission !== 'granted') {
    console.log('Permiso de notificaciones no concedido');
    return;
  }

  try {
    const reg = await navigator.serviceWorker.ready;
    await reg.showNotification(titulo, {
      body: mensaje,
      icon: 'assets/icons/android-icon-192x192.png',
      badge: 'assets/icons/android-icon-48x48.png',
      vibrate: isOnline ? [200, 100] : [100, 50, 100, 50, 100],
      tag: 'connection-status',
      renotify: true,
      requireInteraction: false,
      silent: false
    });
  } catch (err) {
    console.error('Error mostrando notificación:', err);
  }
}

// Detectar cambios en el estado de conexión
window.addEventListener('online', () => {
  console.log('Conexión restaurada');
  mostrarNotificacionConexion(
    '✅ Conexión restaurada',
    'Estás online de nuevo. Todas las funciones están disponibles.',
    true
  );
});

window.addEventListener('offline', () => {
  console.log('Sin conexión');
  mostrarNotificacionConexion(
    '⚠️ Sin conexión',
    'Estás offline. Algunas funciones pueden no estar disponibles.',
    false
  );
});

// Verificar estado inicial al cargar la página
window.addEventListener('load', () => {
  if (!navigator.onLine) {
    console.log('La página se cargó sin conexión');
    setTimeout(() => {
      mostrarNotificacionConexion(
        '⚠️ Sin conexión',
        'Estás offline. Algunas funciones pueden no estar disponibles.',
        false
      );
    }, 1000);
  }
});

// Botón o evento de usuario para pedir permiso + mostrar noti via SW
async function activarNotificaciones() {
  if (!('Notification' in window)) return;

  const perm = await Notification.requestPermission();
  if (perm !== 'granted') return;

  const reg = await navigator.serviceWorker.ready;
  await reg.showNotification('Hola! Soy una notificación', {
    body: 'Gracias por permitir las notificaciones',
    icon: '/assets/icons/android-icon-192x192.png',
    badge: '/assets/icons/android-icon-48x48.png',
    vibrate: [100, 50, 100],
    renotify: true,
    tag: 'notificacion-sample'
  });
}
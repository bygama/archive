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

        // Intento 1: relativo (para GitHub Pages/subcarpetas)
        const okRelative = await tryRegister('sw.js');
        // Fallback: raíz (para localhost o hosting en la raíz del dominio)
        if (!okRelative) {
            await tryRegister('/sw.js');
        }

        //Demorar el popup de permisos de notificaciones
        if(window.Notification && Notification.permission !== 'denied'){
            setTimeout(()=>{
                Notification.requestPermission((status)=>{
                    console.log('Permiso de notificaciones: ', status);
                });
            }, 2000);

            //Mostrar notificacion
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
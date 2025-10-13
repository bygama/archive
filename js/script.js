if('serviceWorker' in navigator) {
    window.addEventListener('load', () =>{
        navigator.serviceWorker.register('/sw.js')
        .then((registration)=>{
            console.log('ServiceWorker registrado ok, alcance: alcance:' + registration.scope);
        })
    })
}

(()=>{
    
    (() => {
        let notice;
    
        window.addEventListener("beforeinstallprompt", (e) => {
            e.preventDefault();
            notice = e;
    
            showAddToHomeScreen();
        });
    
        const showAddToHomeScreen = () => {
            const Btn = document.getElementById("installBtn");
    
            Btn.addEventListener("click", async () => {
                if (notice) {
                    notice.prompt(); 
                    const choiceResult = await notice.userChoice;
                    if (choiceResult.outcome === 'accepted') {
                        console.log('El usuario aceptó la instalación');
                    } else {
                        console.log('El usuario rechazó la instalación');
                    }
                    notice = null; 
                }
            });
        };
    })();
})();
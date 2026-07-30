// lenis es la libreria que hace el "scroll suave" (que la pagina no baje de golpe
// sino con esa inercia tipo deslizado)
import Lenis from 'lenis';

// arranca el scroll suave y devuelve la instancia para que animations.js la use tambien
export function initLenis() {
    const lenis = new Lenis({
        duration: 1.1,          // que tan largo dura el frenado del scroll
        smoothWheel: true,      // suaviza la ruedita del mouse
        wheelMultiplier: 0.9,   // baja un toque la velocidad del scroll
        // esta formula es la curva de desaceleracion, arranca rapido y frena de a poco
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    });

    // este loop se llama solo en cada frame del navegador (60 veces por segundo)
    // y le avisa a lenis que actualice la posicion del scroll
    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

    return lenis;
}

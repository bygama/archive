// esto le da la onda "militar/tactica" a la pagina: un reloj en vivo y los numeros
// que suben desde 0 hasta su valor (los contadores de las estadisticas)
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

// arranca todo lo tactico. el reloj siempre corre; los contadores se animan solo si
// el usuario no pidio reducir movimiento (si lo pidio, los pongo derecho en su valor final)
export function initTactical({ reducedMotion } = {}) {
    initClock();
    if (!reducedMotion) {
        initCounters();
    } else {
        applyCountersInstantly();
    }
}

// reloj en vivo que se actualiza cada segundo con la hora UTC
function initClock() {
    const clock = document.querySelector('[data-tactical-clock]');
    if (!clock) return; // si la pagina no tiene reloj, salgo

    // arma el texto HH:MM:SS. el padStart mete un 0 adelante si el numero es de una cifra (ej 9 -> 09)
    const update = () => {
        const now = new Date();
        const h = String(now.getUTCHours()).padStart(2, '0');
        const m = String(now.getUTCMinutes()).padStart(2, '0');
        const s = String(now.getUTCSeconds()).padStart(2, '0');
        clock.textContent = `${h}:${m}:${s}`;
    };

    update();                 // lo pinto una vez al toque para que no arranque vacio
    setInterval(update, 1000); // y despues lo repito cada 1000ms (1 segundo)
}

// anima los contadores: cada numero sube desde 0 hasta su valor cuando aparece en pantalla
function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    counters.forEach((el) => {
        // leo la config de cada contador desde los data- del html
        const target = parseFloat(el.dataset.counter);                       // valor final
        const decimals = parseInt(el.dataset.counterDecimals || '0', 10);    // cuantos decimales mostrar
        const suffix = el.dataset.counterSuffix || '';                       // texto despues del numero (ej %)
        const prefix = el.dataset.counterPrefix || '';                       // texto antes (ej $)
        const obj = { value: 0 }; // objeto auxiliar que gsap va moviendo de 0 al target

        gsap.to(obj, {
            value: target,
            duration: 1.6,
            ease: 'power3.out',
            // scrollTrigger dispara la animacion recien cuando el elemento entra a la vista
            scrollTrigger: {
                trigger: el,
                start: 'top 92%', // cuando el borde de arriba llega al 92% de la pantalla
                once: true,       // se anima una sola vez, no cada vez que paso
            },
            // en cada frame reescribo el texto con el valor actual ya formateado
            onUpdate: () => {
                const n = decimals > 0 ? obj.value.toFixed(decimals) : Math.round(obj.value);
                el.textContent = `${prefix}${formatNumber(n)}${suffix}`;
            },
        });
    });
}

// version sin animacion: escribe los contadores directo en su valor final.
// se usa cuando el usuario pidio reducir movimiento
function applyCountersInstantly() {
    const counters = document.querySelectorAll('[data-counter]');
    counters.forEach((el) => {
        const target = parseFloat(el.dataset.counter);
        const decimals = parseInt(el.dataset.counterDecimals || '0', 10);
        const suffix = el.dataset.counterSuffix || '';
        const prefix = el.dataset.counterPrefix || '';
        const n = decimals > 0 ? target.toFixed(decimals) : Math.round(target);
        el.textContent = `${prefix}${formatNumber(n)}${suffix}`;
    });
}

// mete los puntos de miles a un numero (ej 1000000 -> 1,000,000).
// si tiene decimales, separo la parte entera de la decimal y solo le pongo los puntos a la entera
function formatNumber(value) {
    const str = String(value);
    if (str.includes('.')) {
        const [int, dec] = str.split('.');
        return `${int.replace(/\B(?=(\d{3})+(?!\d))/g, ',')}.${dec}`;
    }
    // este regex busca cada lugar donde van 3 cifras y mete la coma
    return str.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

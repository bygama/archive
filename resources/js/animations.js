// todas las animaciones al hacer scroll estan aca, hechas con gsap.
// la idea general: los elementos empiezan invisibles/corridos y aparecen cuando
// el usuario los va scrolleando
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

// registro el plugin de scroll de gsap (obligatorio antes de usarlo)
gsap.registerPlugin(ScrollTrigger);

// funcion principal, la llama app.js. decide si anima o muestra todo de una
export function initAnimations({ lenis, reducedMotion } = {}) {
    // si el usuario pidio reducir movimiento, no animo nada: dejo todo visible y quieto
    if (reducedMotion) {
        document.querySelectorAll('[data-animate]').forEach((el) => {
            el.style.opacity = '1';
            el.style.transform = 'none';
        });
        return;
    }

    // conecto el scroll suave (lenis) con gsap para que las animaciones vayan sincronizadas
    // con el scroll y no se sientan cortadas
    if (lenis) {
        lenis.on('scroll', ScrollTrigger.update);
        gsap.ticker.add((time) => lenis.raf(time * 1000));
        gsap.ticker.lagSmoothing(0);
    }

    // prendo cada tipo de animacion. cada una busca sus elementos por su data- en el html
    setupHeroReveals();   // titulo/portada de arriba
    setupFadeUp();        // elementos que aparecen subiendo
    setupLines();         // lineas que se estiran
    setupPanels();        // paneles que entran de costado
    setupTableRows();     // filas de tablas una atras de otra
    setupStaggerGroups(); // grupos que aparecen en cascada
    setupCardHovers();    // efecto al pasar el mouse por las tarjetas
}

// animacion de la portada (hero): los elementos del encabezado aparecen en cascada
// apenas carga la pagina, sin esperar scroll
function setupHeroReveals() {
    const heroes = document.querySelectorAll('[data-hero-reveal]');
    heroes.forEach((hero) => {
        const tl = gsap.timeline(); // linea de tiempo para encadenar la animacion
        const targets = hero.querySelectorAll('[data-hero-item]');
        if (!targets.length) return;
        // .from = arrancan invisibles y corridos 24px para abajo y terminan en su lugar.
        // el stagger de 0.08 hace que entren de a uno con un retraso chiquito entre cada uno
        tl.from(targets, {
            opacity: 0,
            y: 24,
            duration: 0.7,
            stagger: 0.08,
            ease: 'power3.out',
        });
    });
}

// elementos que aparecen "subiendo" (fade + desplazamiento hacia arriba) al scrollear
function setupFadeUp() {
    const items = document.querySelectorAll('[data-animate="fade-up"]');
    items.forEach((el) => {
        gsap.to(el, {
            opacity: 1,
            y: 0,
            duration: 0.7,
            ease: 'power2.out',
            // se dispara cuando el elemento llega al 88% de la altura de la pantalla
            scrollTrigger: {
                trigger: el,
                start: 'top 88%',
                once: true,
            },
        });
    });
}

// lineas decorativas que se estiran de 0 a su ancho completo (scaleX de 0 a 1)
function setupLines() {
    const lines = document.querySelectorAll('[data-animate="line"]');
    lines.forEach((el) => {
        gsap.to(el, {
            scaleX: 1, // escala horizontal al 100% = linea completa
            duration: 0.8,
            ease: 'expo.out',
            scrollTrigger: {
                trigger: el,
                start: 'top 92%',
                once: true,
            },
        });
    });
}

// paneles que entran desde un costado (x vuelve a 0) apareciendo a la vez
function setupPanels() {
    const panels = document.querySelectorAll('[data-animate="panel"]');
    panels.forEach((el) => {
        gsap.to(el, {
            opacity: 1,
            x: 0, // vuelve a su posicion horizontal original
            duration: 0.7,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: el,
                start: 'top 90%',
                once: true,
            },
        });
    });
}

// filas de una tabla que aparecen una detras de la otra (efecto listado que se va llenando)
function setupTableRows() {
    const tables = document.querySelectorAll('[data-animate-table]');
    tables.forEach((table) => {
        const rows = table.querySelectorAll('[data-animate="table-row"]');
        if (!rows.length) return;
        gsap.to(rows, {
            opacity: 1,
            y: 0,
            duration: 0.5,
            stagger: 0.05, // 0.05s de diferencia entre fila y fila
            ease: 'power2.out',
            scrollTrigger: {
                trigger: table,
                start: 'top 88%',
                once: true,
            },
        });
    });
}

// grupos genericos que aparecen en cascada (ej listas de tarjetas o items)
function setupStaggerGroups() {
    const groups = document.querySelectorAll('[data-stagger]');
    groups.forEach((group) => {
        const items = group.querySelectorAll('[data-stagger-item]');
        if (!items.length) return;
        // fromTo = defino explicitamente el estado inicial (invisible, 20px abajo) y el final.
        // clearProps limpia los estilos que gsap dejo puestos, asi el css vuelve a mandar despues
        gsap.fromTo(
            items,
            { opacity: 0, y: 20 },
            {
                opacity: 1,
                y: 0,
                duration: 0.6,
                stagger: 0.08,
                ease: 'power2.out',
                immediateRender: true,
                clearProps: 'transform,opacity',
                scrollTrigger: {
                    trigger: group,
                    start: 'top 85%',
                    once: true,
                },
            }
        );
    });
}

// efecto al pasar el mouse por una tarjeta: se levanta un poquito (4px) y al salir vuelve.
// tambien lo hago con el foco del teclado (focusin/focusout) para que sea accesible sin mouse
function setupCardHovers() {
    const cards = document.querySelectorAll('[data-card-hover]');
    cards.forEach((card) => {
        const enter = () => gsap.to(card, { y: -4, duration: 0.25, ease: 'power2.out' }); // sube
        const leave = () => gsap.to(card, { y: 0, duration: 0.3, ease: 'power2.out' });   // baja
        card.addEventListener('mouseenter', enter);
        card.addEventListener('mouseleave', leave);
        card.addEventListener('focusin', enter);
        card.addEventListener('focusout', leave);
    });
}

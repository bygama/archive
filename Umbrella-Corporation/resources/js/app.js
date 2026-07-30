// este es el archivo principal que vite carga en todas las paginas.
// desde aca arranco cada modulo del front (scroll, animaciones, menu, filtros, etc)
import '../css/app.css';

import { initLenis } from './lenis.js';
import { initAnimations } from './animations.js';
import { initNavigation } from './navigation.js';
import { initFilters } from './filters.js';
import { initTactical } from './tactical.js';

// si el usuario tiene activado "reducir movimiento" en el sistema, apago las animaciones
// asi la pagina es accesible para gente que se marea o que lo pide por config
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// solo prendo el scroll suave (lenis) si no pidieron reducir movimiento
const lenis = reducedMotion ? null : initLenis();
initAnimations({ lenis, reducedMotion }); // animaciones al hacer scroll
initNavigation();                          // menu hamburguesa y su comportamiento
initFilters();                             // filtros del catalogo
initTactical({ reducedMotion });           // reloj y contadores animados

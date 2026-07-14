// maneja el menu de navegacion en celular (el boton hamburguesa que abre y cierra el menu)
export function initNavigation() {
    const toggle = document.querySelector('[data-nav-toggle]'); // el boton hamburguesa
    const menu = document.querySelector('[data-nav-menu]');     // el menu que se despliega
    // si la pagina no tiene ni el boton ni el menu, no hago nada y corto aca
    if (!toggle || !menu) return;

    // funcion central: recibe true/false y deja el menu abierto o cerrado.
    // toco tres cosas: el aria (para lectores de pantalla), el data-open (que el css usa
    // para mostrarlo) y bloqueo el scroll del fondo cuando esta abierto
    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        menu.dataset.open = open ? 'true' : 'false';
        document.body.style.overflow = open ? 'hidden' : '';
    };

    // al clickear el boton, si estaba abierto lo cierro y al reves
    toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';
        setOpen(!isOpen);
    });

    // si toco cualquier link del menu, lo cierro (para que no quede tapando la pantalla)
    menu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    // si aprieto la tecla Escape y el menu estaba abierto, lo cierro y vuelvo el foco al boton
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            setOpen(false);
            toggle.focus();
        }
    });

    // si la pantalla se agranda a tamaño escritorio (1024px o mas), cierro el menu movil
    // porque ahi ya se muestra la barra normal y no hace falta el desplegable
    const mediaQuery = window.matchMedia('(min-width: 1024px)');
    mediaQuery.addEventListener('change', (event) => {
        if (event.matches) setOpen(false);
    });
}

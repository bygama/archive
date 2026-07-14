// filtra el catalogo del lado del navegador (sin recargar la pagina).
// las tarjetas ya vienen en el html y aca las voy mostrando/escondiendo segun el boton apretado
export function initFilters() {
    const root = document.querySelector('[data-filter-root]');
    if (!root) return; // si la pagina no tiene filtros, salgo

    const buttonGroups = root.querySelectorAll('[data-filter-group]'); // cada grupo de botones (ej: categoria, nivel)
    const items = root.querySelectorAll('[data-filter-item]');         // las tarjetas a filtrar

    // guardo que filtro esta elegido en cada grupo. arranco todo en 'all' (mostrar todo)
    const state = {};
    buttonGroups.forEach((group) => {
        state[group.dataset.filterGroup] = 'all';
    });

    // recorre todas las tarjetas y decide cuales se ven segun el estado actual de los filtros.
    // una tarjeta se muestra solo si cumple TODOS los grupos a la vez
    const apply = () => {
        items.forEach((item) => {
            let visible = true;
            for (const [group, value] of Object.entries(state)) {
                if (value === 'all') continue; // ese grupo no filtra nada, sigo con el proximo
                // leo el valor de la tarjeta para ese grupo (ej data-filter-categoria)
                const itemValue = item.dataset[`filter${capitalize(group)}`];
                if (!itemValue) {
                    visible = false; // la tarjeta no tiene ese dato, entonces no matchea
                    break;
                }
                // una tarjeta puede tener varios valores separados por "|", los separo y comparo
                const tokens = itemValue.split('|').map((s) => s.trim().toLowerCase());
                if (!tokens.includes(value.toLowerCase())) {
                    visible = false; // no esta el valor buscado, la escondo
                    break;
                }
            }
            // dejo marcado el estado y muestro/oculto con display
            item.dataset.hidden = visible ? 'false' : 'true';
            item.style.display = visible ? '' : 'none';
        });
    };

    // le pego un click a cada boton de filtro
    buttonGroups.forEach((group) => {
        const groupName = group.dataset.filterGroup;
        const buttons = group.querySelectorAll('[data-filter-value]');
        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                // guardo el valor elegido en el estado de ese grupo
                const value = button.dataset.filterValue;
                state[groupName] = value;
                // marco visualmente cual quedo activo (y aviso a lectores de pantalla con aria-pressed)
                buttons.forEach((b) => {
                    const active = b === button;
                    b.dataset.active = active ? 'true' : 'false';
                    b.setAttribute('aria-pressed', active ? 'true' : 'false');
                });
                apply(); // vuelvo a filtrar con el nuevo estado
            });
        });
    });
}

// pone la primera letra en mayuscula. lo uso para armar el nombre del dataset
// (ej: "categoria" -> "Categoria", asi queda data-filterCategoria)
function capitalize(value) {
    return value.charAt(0).toUpperCase() + value.slice(1);
}

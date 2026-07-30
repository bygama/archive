const API = 'https://ddragon.leagueoflegends.com';
const loader = document.querySelector('.loader');

const FAVORITES_KEY = 'favoriteChamps';
const ALL_CHAMPS_KEY = 'allChamps';

let champsApi = []; 

let currentQuery = '';
let currentFiltered = [];
let currentPage = 1;
const PAGE_SIZE = 20;

const getLatestVersion = async () => {
  const res = await fetch(`${API}/api/versions.json`);
  const versions = await res.json();
  return versions[0];
};



const fetchData = async (endpoint) => {
  try {
    const v = await getLatestVersion();
    const response = await fetch(`${API}/cdn/${v}/data/es_ES${endpoint}`);
    if (!response.ok) throw new Error('Error al conectar con la API');
    return await response.json();
  } catch (error) {
    console.error(error);
    return null; 
  }
};

const isLoading = (isActive) => {

    if(isActive){
        loader.classList.remove('hidden')
    }else{
        loader.classList.add('hidden')
    }
}

// TOAST SYSTEM
let __toastStylesInjected = false;
function ensureToastStyles() {
  if (__toastStylesInjected) return;
  const style = document.createElement('style');
  style.id = 'toast-styles';
  style.textContent = `
@keyframes toast-in {0%{transform:translateY(18px);opacity:0}55%{transform:translateY(-2px);opacity:1}100%{transform:translateY(0);opacity:1}}
@keyframes toast-out {0%{transform:translateY(0);opacity:1}100%{transform:translateY(8px);opacity:0}}
@keyframes overlay-fade-in {0%{opacity:0}100%{opacity:1}}
@keyframes overlay-fade-out {0%{opacity:1}100%{opacity:0}}
.app-toast-container{position:fixed;left:0;right:0;bottom:1.25rem;display:flex;flex-direction:column;align-items:center;gap:.55rem;z-index:70;pointer-events:none;padding:0 .75rem}
.app-toast{pointer-events:auto;min-width:240px;max-width:360px;font-size:.75rem;line-height:1.15rem;font-weight:500;border-radius:0.9rem;padding:.65rem .95rem .65rem .8rem;display:flex;align-items:center;gap:.6rem;backdrop-filter:blur(8px);background:linear-gradient(to top,rgba(15,23,42,.82),rgba(30,41,59,.76));border:1px solid rgba(71,85,105,.45);box-shadow:0 6px 22px -4px rgba(0,0,0,.55),0 0 0 1px rgba(255,255,255,.03) inset;animation:toast-in .45s cubic-bezier(.22,.8,.32,1) forwards;position:relative}
.app-toast[data-leaving='true']{animation:toast-out .38s ease forwards}
.app-toast:before{content:"";position:absolute;inset:0;border-radius:inherit;pointer-events:none;background:radial-gradient(circle at 15% 90%,rgba(99,102,241,.20),transparent 60%);opacity:.32}
.app-toast span.emoji{font-size:1.05rem;filter:grayscale(.25);opacity:.5;transition:opacity .25s}
.app-toast:hover span.emoji{opacity:.78}
.app-toast-success{color:#5eead4;border-color:rgba(94,234,212,.30)}
.app-toast-remove{color:#fca5a5;border-color:rgba(252,165,165,.30)}
.app-toast-info{color:#a5b4fc;border-color:rgba(165,180,252,.30)}
/* Overlay & mini confirm */
.app-toast-overlay{position:fixed;inset:0;z-index:80;display:flex;align-items:center;justify-content:center;background:rgba(15,23,42,.18);backdrop-filter:blur(2px);animation:overlay-fade-in .25s ease forwards}
.app-toast-overlay[data-leaving='true']{animation:overlay-fade-out .22s ease forwards}
.app-toast-confirm-mini{pointer-events:auto;min-width:260px;max-width:320px;font-size:.70rem;line-height:1.05rem;font-weight:500;border-radius:0.85rem;padding:.85rem .9rem .9rem .75rem;display:flex;align-items:flex-start;gap:.55rem;backdrop-filter:blur(10px);background:linear-gradient(to top,rgba(15,23,42,.90),rgba(30,41,59,.88));border:1px solid rgba(71,85,105,.45);box-shadow:0 10px 30px -8px rgba(0,0,0,.60),0 0 0 1px rgba(255,255,255,.04) inset;animation:toast-in .45s cubic-bezier(.22,.8,.32,1) forwards;position:relative}
.app-toast-confirm-mini[data-leaving='true']{animation:toast-out .35s ease forwards}
.app-toast-confirm-mini .emoji{font-size:1rem;opacity:.65;margin-top:.05rem}
.app-toast-confirm-mini h3{font-size:.78rem;margin:0 0 .3rem;font-weight:600;color:#e2e8f0}
.app-toast-confirm-mini p{font-size:.63rem;color:#94a3b8;margin:0 0 .55rem}
.app-toast-confirm-mini .actions{display:flex;gap:.4rem}
.app-toast-confirm-mini button{font-size:.6rem;letter-spacing:.4px;line-height:1}
.app-toast-confirm-mini [data-confirm]{background:rgba(244,63,94,.85);color:#fff;padding:.45rem .7rem;border-radius:.55rem;font-weight:600;border:1px solid rgba(248,113,113,.35);box-shadow:0 4px 14px -6px rgba(244,63,94,.55)}
.app-toast-confirm-mini [data-confirm]:hover{background:rgba(244,63,94,.78)}
.app-toast-confirm-mini [data-cancel]{background:rgba(51,65,85,.70);color:#e2e8f0;padding:.45rem .7rem;border-radius:.55rem;font-weight:500;border:1px solid rgba(71,85,105,.55)}
.app-toast-confirm-mini [data-cancel]:hover{background:rgba(71,85,105,.70)}
.app-toast-confirm-mini .close-x{position:absolute;top:.4rem;right:.4rem;width:24px;height:24px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;background:rgba(51,65,85,.55);border:1px solid rgba(71,85,105,.55);color:#cbd5e1;font-size:13px;font-weight:600;line-height:1}
.app-toast-confirm-mini .close-x:hover{background:rgba(71,85,105,.65)}
/* Variante centrada para confirm de Vaciar favoritos */
.app-toast-confirm-mini.centered{flex-direction:column;align-items:center;text-align:center;gap:.65rem;padding:1.05rem .95rem 1rem;}
.app-toast-confirm-mini.centered .emoji{margin-top:0;font-size:1.25rem;opacity:.75;}
.app-toast-confirm-mini.centered h3{margin:0 0 .55rem;font-size:.82rem;}
.app-toast-confirm-mini.centered .actions{justify-content:center;width:100%;}
@media (max-width:480px){.app-toast-confirm-mini{max-width:86%;}}
@media (prefers-contrast: more){.app-toast{background:rgba(15,23,42,.90);} .app-toast-confirm-mini{background:rgba(15,23,42,.94);} }
`;
  document.head.appendChild(style);
  if (!document.querySelector('.app-toast-container')) {
    const c = document.createElement('div');
    c.className = 'app-toast-container';
    c.setAttribute('aria-live','polite');
    c.setAttribute('role','status');
    document.body.appendChild(c);
  }
  __toastStylesInjected = true;
}

function showToast({message='', type='success', emoji='⭐', duration=2200}={}) {
  ensureToastStyles();
  const container = document.querySelector('.app-toast-container');
  if (!container) return;
  const div = document.createElement('div');
  const cls = type === 'success' ? 'app-toast-success' : type === 'remove' ? 'app-toast-remove' : 'app-toast-info';
  div.className = `app-toast ${cls}`;
  div.innerHTML = `<span class="emoji">${emoji}</span><p class="flex-1">${message}</p>`;
  container.appendChild(div);
  // auto remove
  setTimeout(() => {
    div.dataset.leaving = 'true';
    setTimeout(() => div.remove(), 420);
  }, duration);
}

function showConfirmClearFavoritesToast(){
  ensureToastStyles();
  document.querySelectorAll('.app-toast-overlay').forEach(o=>o.remove());
  const overlay = document.createElement('div');
  overlay.className = 'app-toast-overlay';
  overlay.innerHTML = `
    <div class="app-toast-confirm-mini centered" role="dialog" aria-modal="true" aria-labelledby="cfavs-title">
      <span class="emoji">🗑️</span>
      <div class="flex-1 flex flex-col items-center">
        <h3 id="cfavs-title">Vaciar favoritos</h3>
        <div class="actions">
          <button type="button" data-confirm>Eliminar</button>
          <button type="button" data-cancel>Cancelar</button>
        </div>
      </div>
    </div>`;
  const panel = overlay.querySelector('.app-toast-confirm-mini');
  const leave = () => {
    overlay.setAttribute('data-leaving','true');
    panel.setAttribute('data-leaving','true');
    setTimeout(()=>overlay.remove(),230);
  };
  const cancelAction = () => { leave(); showToast({message:'Cancelado', type:'info', emoji:'↩️', duration:1500}); };
  overlay.addEventListener('click', e => { if(e.target===overlay) cancelAction(); });
  overlay.querySelectorAll('[data-cancel]').forEach(b=>b.addEventListener('click', cancelAction));
  overlay.querySelector('[data-confirm]').addEventListener('click', () => {
    try {
      localStorage.setItem('favoriteChamps','[]');
      if (typeof currentFiltered !== 'undefined') currentFiltered = [];
      if (typeof showChamps === 'function') showChamps(''); else location.reload();
      showToast({message:'Favoritos borrados', type:'remove', emoji:'🗑️', duration:2000});
    } catch(err){ console.error(err); }
    leave();
  });
  setTimeout(()=>{ if(document.body.contains(overlay)) cancelAction(); }, 9000); // auto-cancel
  document.body.appendChild(overlay);
}

/////////////////////////////////////////////////////////////////////////////////////////////


// manejo de favoritos
function getFavorites() {
  return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
}  

function setFavorites(list) {
  localStorage.setItem(FAVORITES_KEY, JSON.stringify(list));
}

function isFavorite(id) {
  const favs = getFavorites();
  return favs.some(f => f.id === id);
}

// remover sin alerts
function removeFavorite(id) {
  const favs = getFavorites();
  const next = favs.filter(f => f.id !== id);
  setFavorites(next);
  updateStats();
}

function toggleFavorite(id, favButton) {
  try {
    const favs = getFavorites();
    const exists = favs.some(f => f.id === id);
    if (exists) {
      removeFavorite(id);
      showToast({message: 'Eliminado de favoritos', type: 'remove', emoji: '💔', duration: 2300});
    } else {
      const all = champsApi.length ? champsApi : (JSON.parse(localStorage.getItem(ALL_CHAMPS_KEY)) || []);
      const champ = all.find(c => c.id === id);
      if (!champ) return;
      favs.push({
        id: champ.id,
        name: champ.name,
        title: champ.title,
        tags: champ.tags || [],
        img: `${IMG_LOADING}${champ.id}_0.jpg`
      });
      setFavorites(favs);
      showToast({message: 'Agregado a favoritos', type: 'success', emoji: '❤️', duration: 2300});
    }
    // actualizar ícono si se pasó referencia
    if (favButton) {
      const active = isFavorite(id);
      favButton.setAttribute('data-active', active ? 'true' : 'false');
      favButton.innerHTML = heartIconSvg(active);
    }

    if (isFavoritesPage()) {
      // mantener query y página actuales pero reconstruir la lista
      currentFiltered = currentFiltered.filter(c => isFavorite(c.id));
      showChamps(currentQuery);
    } else {
      // actualiza íconos duplicados del mismo champ
      document.querySelectorAll(`[data-heart-id="${id}"]`).forEach(node => {
        const active = isFavorite(id);
        node.setAttribute('data-active', active ? 'true' : 'false');
        node.innerHTML = heartIconSvg(active);
      });
    }
  } catch (e) {
    console.error(e);
  }
}

function heartIconSvg(active) {
  return active
    ? `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-red-500 drop-shadow transition-transform duration-200 scale-100"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037 .033l.034 -.03a6 6 0 0 1 4.733 -1.44l.246 .036a6 6 0 0 1 3.364 10.008l-.18 .185l-.048 .041l-7.45 7.379a1 1 0 0 1 -1.313 .082l-.094 -.082l-7.493 -7.422a6 6 0 0 1 3.176 -10.215z"/></svg>`
    : `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-slate-300/80 group-hover:text-red-300 transition-colors"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>`;
}

const normalize = (s = '') => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
/*
  Normaliza un string para comparaciones más simples:
 * - Convierte todo a minúsculas.
 * - Descompone caracteres con tilde o diéresis usando Unicode NFD
 *   (ejemplo: "á" → "a" + "́◌́").
 * - Elimina esas marcas diacríticas (tildes, diéresis, etc.) con replace
*/



function isFavoritesPage() {
  return location.pathname.endsWith('favorites.html') || location.pathname.endsWith('favorites');
}

function isHomePage() {
  return location.pathname.endsWith('index.html') || location.pathname === '/' || location.pathname === '';
}

const getAllChamps = async () => {
  
  if (isFavoritesPage()) {
    return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
  }

  
  if (champsApi.length) return champsApi;

  const json = await fetchData('/champion.json');
  champsApi = json ? Object.values(json.data) : [];
  localStorage.setItem(ALL_CHAMPS_KEY, JSON.stringify(champsApi));
  return champsApi;
};



async function updateStats() {
  const total = document.getElementById('stat-total');
  const fav = document.getElementById('stat-favs');
  const versionEl = document.getElementById('stat-version');
  const footerVersion = document.getElementById('footer-version');
  try {
    const v = await getLatestVersion();
    const all = champsApi.length ? champsApi : (JSON.parse(localStorage.getItem(ALL_CHAMPS_KEY)) || []);
    const favs = JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
    if (total) total.textContent = all.length ? all.length : '—';
    if (fav) fav.textContent = favs.length;
    if (versionEl) versionEl.textContent = v || '—';
    if (footerVersion) footerVersion.textContent = `Versión API: ${v || '—'}`;
  } catch (e) {
    console.error(e);
    if (footerVersion) footerVersion.textContent = 'Versión API: —';
  }
}

async function loadChamps() {
  try {
    const allChamps = await getAllChamps();
    console.log(allChamps);
  } catch (error) {
    console.error("Error:", error);
  }
}

/* loadChamps(); */


const IMG_LOADING = 'https://ddragon.leagueoflegends.com/cdn/img/champion/loading/';
const IMG_SPLASH = 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/';

let IMG_SQUARE = ''; 

async function ImgSquare() {
    const v = await getLatestVersion();
    IMG_SQUARE = `${API}/cdn/${v}/img/champion/`;
}

ImgSquare();


const setupSearch = () => {
  const input = document.getElementById('search');
  if (!input) return;
  // en champs o favoritos: filtra en vivo, en home: redirige al listado completo
  if (isHomePage()) {
    input.addEventListener('input', () => {
      showChamps(input.value);
    });
    // redirección al presionar Enter
    input.form?.addEventListener('submit', e => {
      e.preventDefault();
      const q = input.value.trim();
      const url = `./pages/champs.html${q ? `?q=${encodeURIComponent(q)}` : ''}`;
      location.href = url; //voy a esa pagina
    });
  } else {
    // páginas que muestran resultados directamente
    input.addEventListener('input', () => {
      showChamps(input.value);
    });
    input.form?.addEventListener('submit', e => {
      e.preventDefault();
      showChamps(input.value);
    });
  }
};

//query del buscador
const setQuery = () => {
  const params = new URLSearchParams(location.search);
  const q = params.get('q') || '';

  if (q) {
    const input = document.getElementById('search');
    if (input) input.value = q;
    showChamps(q);
  } else {
    showChamps();
  }
};


// estado de filtros (roles multi, difficulty single, sort single) ====
let championFilterState = {
  rols: new Set(),       // set de strings (tags) 
  difficulty: null,
  sort: null
};

// Setter externo llamado desde filters.js
function setChampionFilters({ rols, difficulty, sort }) {
  if (rols) championFilterState.rols = rols; 
  championFilterState.difficulty = difficulty ?? null;
  championFilterState.sort = sort ?? null;
  currentPage = 1; // reset página al cambiar filtros
  showChamps(currentQuery);
}
window.setChampionFilters = setChampionFilters; // exponer global

// Aplica todos los filtros + búsqueda + orden.
function applyChampionFilters(list, query) {
  let result = list;

  
  if (championFilterState.rols.size) {
    result = result.filter(c => (c.tags || []).some(t => championFilterState.rols.has(t)));
  }
 
  if (championFilterState.difficulty) {
    const bucket = championFilterState.difficulty;
    result = result.filter(c => {
      const v = c.info?.difficulty || 0;
      if (bucket === 'low') return v <= 3;
      if (bucket === 'medium') return v > 3 && v <= 6;
      if (bucket === 'high') return v > 6;
      return true;
    });
  }
 
  const q = normalize((query || '').trim());
  if (q) {
    result = result.filter(c => {
      const name = normalize(c.name);
      const title = normalize(c.title);
      const tags = (c.tags || []).map(normalize).join(' ');
      return name.includes(q) || title.includes(q) || tags.includes(q);
    });
  }
  
  if (championFilterState.sort === 'name') {
    result = [...result].sort((a,b) => a.name.localeCompare(b.name));
  } else if (championFilterState.sort === 'difficulty') {
    result = [...result].sort((a,b) => (a.info?.difficulty||0) - (b.info?.difficulty||0));
  }
  return result;
}

const renderChamps = (list = []) => {
 
  const gridContainer = document.querySelector('.champs');
  const featuredContainer = document.querySelector('.champs-featured');

  const onFavs = isFavoritesPage();

  if (gridContainer) {
    // no limpiar si se agregan más páginas; solo limpiar cuando page=1
    if (currentPage === 1) {
      gridContainer.innerHTML = '';
    }
    if (!list.length && currentPage === 1) {
      gridContainer.innerHTML = '<p class="text-slate-300 col-span-full p-6 text-center w-full">No se encontraron campeones.</p>';
    } else {
      list.forEach(champ => {
        const roles = (champ.tags || []).join(', ');
        const roleChips = (champ.tags || []).map(r => `<span class=\"px-2 py-0.5 rounded-full bg-slate-700/70 text-[10px] tracking-wide uppercase text-indigo-300 border border-slate-600\">${r}</span>`).join(' ');
        const card = document.createElement('div');
        card.className = 'w-full sm:w-1/2 md:w-1/3 xl:w-1/5 p-2';
        const favActive = isFavorite(champ.id);
        card.innerHTML = `
          <div class="group relative bg-slate-800/70 border border-blue-800/20 rounded-2xl overflow-hidden flex flex-col h-full backdrop-blur shadow hover:shadow-lg hover:border-indigo-500/30 transition">
            <div class="relative">
              <img class="w-full h-[230px] object-cover object-top select-none pointer-events-none opacity-90 group-hover:opacity-100 transition" src="${IMG_LOADING}${champ.id}_0.jpg" alt="${champ.name}">
              <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/20 to-transparent"></div>
              <div class="absolute top-2 left-2 flex flex-wrap gap-1">${roleChips}</div>
            </div>
            <div class="flex-1 flex flex-col p-4">
              <h3 class="text-base font-semibold text-slate-100 mb-0.5 leading-snug line-clamp-1">${champ.name}</h3>
              <p class="text-xs text-slate-400 italic line-clamp-1">${champ.title}</p>
              <div class="mt-auto pt-3 flex items-center gap-1">
                <a href="./champ.html?id=${champ.id}" class="flex-1 inline-flex justify-center items-center gap-1.5 py-1.5 rounded-l-lg bg-indigo-600/90 hover:bg-indigo-500 text-white text-xs font-medium shadow shadow-indigo-900/30 transition" aria-label="Ver página de ${champ.name}">Detalles</a>
                <button type="button" data-heart-id="${champ.id}" data-active="${favActive}" aria-label="${favActive ? 'Quitar de favoritos' : 'Agregar a favoritos'}" class="shrink-0 p-2 rounded-r-lg bg-slate-700/70 hover:bg-slate-600 border border-slate-400/10 hover:border-red-500/60 transition w-10 h-7 flex items-center justify-center" onclick="toggleFavorite('${champ.id}', this)">
                  ${heartIconSvg(favActive)}
                  <span class="sr-only">${favActive ? 'Quitar de favoritos' : 'Agregar a favoritos'}</span>
                </button>
              </div>
            </div>
          </div>`;
        gridContainer.appendChild(card);
      });
    }
  }

  
  if (featuredContainer) {
    featuredContainer.innerHTML = '';
    //sort compara de a pares y Math.random() da un valor entre 0 y 1, entonces restarle 0.5 da un valor positivo o negativo al azar haciendo que sort compara y ponga a el que le toco numero positivo primer y al de numero negativo lo pone despues y asi sigue con los demas hasta terminar todo el array
    const sample = [...list].sort(() => Math.random() - 0.5).slice(0, 5);
    
    if (!sample.length) {
      featuredContainer.innerHTML = '<li class="text-slate-400 text-sm">Sin resultados.</li>';
      return;
    }
    sample.forEach(champ => {
      const li = document.createElement('li');
      li.innerHTML = `
        <a class="group w-full flex items-center gap-3 text-left bg-slate-800/60 hover:bg-slate-700/70 border border-slate-700 hover:border-indigo-500/60 transition rounded-xl p-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500" href="./pages/champ.html?id=${champ.id}" aria-label="Ir a la página de ${champ.name}">
          <img src="${IMG_SQUARE}${champ.id}.png" alt="${champ.name}" class="w-14 h-14 object-cover rounded-lg ring-1 ring-slate-600 group-hover:ring-indigo-400 transition" />
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-100 truncate">${champ.name}</p>
            <p class="text-xs text-slate-400 truncate">${champ.title}</p>
          </div>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-slate-400 group-hover:text-indigo-300 transition"><path d="M9 18l6-6-6-6"/></svg>
        </a>`;
      featuredContainer.appendChild(li);
    });
  }
};

const showChamps = async (query = '', { append = false } = {}) => {
  try {
    isLoading(true);

    
    const champs = await getAllChamps();

    // resetea la pagina si no se toco ver mas
    if (!append) {
      currentQuery = query;
      currentFiltered = applyChampionFilters(champs, query);
      currentPage = 1;
    }
    
    //aca basicamente cuando le das a ver mas aumenta la pagina y en visible con current filtered recortas lo que se hace en sliceEnd
    const sliceEnd = currentPage * PAGE_SIZE;
    const visible = currentFiltered.slice(0, sliceEnd);
    
    renderChamps(visible);
    updateLoadMoreButton();
    updateStats();

  } catch (err) {
    console.error(err);
  } finally {
    isLoading(false);
  }
};

function updateLoadMoreButton() {
  const btn = document.getElementById('load-more');
  if (!btn) return;
  const total = currentFiltered.length;
  const shown = Math.min(currentPage * PAGE_SIZE, total);
  if (shown >= total) {
    btn.classList.add('hidden');
  } else {
    btn.classList.remove('hidden');
    btn.querySelector('[data-count]')?.replaceChildren(document.createTextNode(`${shown}/${total}`));
  }
}

function loadMoreChamps() {
  currentPage += 1;
  const champs = document.querySelector('.champs');
  champs.innerHTML = '';
  showChamps(currentQuery, { append: true });
}


const addFavorite = (id) => {
  try {
    const favorites = JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];

    
    if (favorites.some(f => f.id === id)) {
      champAlreadyAdded();
      return;
    }
    

    
    const all = champsApi.length ? champsApi : (JSON.parse(localStorage.getItem(ALL_CHAMPS_KEY)) || []);

 
    const champ = all.find(c => c.id === id);
    if (!champ) {
  showToast({message:'Campeón no encontrado', type:'info', emoji:'🔍', duration:2400});
      return;
    }

    favorites.push({
      id: champ.id,
      name: champ.name,
      title: champ.title,
      tags: champ.tags || [],
      img: `${IMG_LOADING}${champ.id}_0.jpg`
    });

    localStorage.setItem(FAVORITES_KEY, JSON.stringify(favorites));
    updateStats();
  } catch (err) {
    console.error(err);
  showToast({message:'Error al guardar favorito', type:'info', emoji:'⚠️', duration:2400});
  }
};


document.addEventListener('DOMContentLoaded', () => {
  setupSearch();
  setQuery();
  updateStats();
  document.getElementById('load-more')?.addEventListener('click', loadMoreChamps);
  // botón de vaciar favoritos
  document.getElementById('clear-all-favorites')?.addEventListener('click', showConfirmClearFavoritesToast);
});



//champ.html
(function(){
  if (!location.pathname.endsWith('champ.html')) return;
  const params = new URLSearchParams(location.search);
  const id = params.get('id');
  const container = document.getElementById('champ-root');
  if(!container) return;
  if(!id){
    container.innerHTML = '<p class="text-center text-slate-300 py-10">Sin campeón seleccionado.</p>';
    return;
  }

  async function ensureVersion(){
    const res = await fetch('https://ddragon.leagueoflegends.com/api/versions.json');
    const data = await res.json();
    return data[0];
  }
  async function getChampion(){
    const v = await ensureVersion();
    const r = await fetch(`https://ddragon.leagueoflegends.com/cdn/${v}/data/es_ES/champion/${id}.json`);
    if(!r.ok) throw new Error('No encontrado');
    const json = await r.json();
    return json.data[id];
  }
  function pctBar(label, value, color){
    return `<div class="space-y-1"><div class="flex justify-between text-xs text-slate-400"><span>${label}</span><span>${value}%</span></div><div class="h-2 rounded bg-slate-700/70 overflow-hidden"><div class="h-full ${color}" style="width:${value}%"></div></div></div>`;
  }
  function laneDistribution(lanes){
    return Object.entries(lanes).map(([lane,val])=>pctBar(lane,val,'bg-indigo-500/80')).join('\n');
  }
  function buildList(build){
    return build.map(b=>`<li class="flex items-start gap-2 p-2 rounded-lg bg-slate-800/60 border border-slate-700/60"><span class="text-indigo-300 text-sm font-medium">${b.item}</span><span class="text-[11px] text-slate-400">${b.note||''}</span></li>`).join('');
  }
  function chip(text){
    return `<span class="px-2 py-0.5 rounded-full bg-slate-800/70 border border-slate-600 text-[10px] tracking-wide uppercase text-indigo-300">${text}</span>`;
  }
  function render(champ){
    const stats = window.__CHAMP_MOCK_STATS__ ? window.__CHAMP_MOCK_STATS__[champ.id] : null;
    const roles = (champ.tags||[]).map(chip).join(' ');
    const splash = `https://ddragon.leagueoflegends.com/cdn/img/champion/splash/${champ.id}_0.jpg`;
    container.innerHTML = `
      <section class="relative min-h-[340px] flex items-end overflow-hidden rounded-3xl border border-slate-700/60 bg-slate-900/50">
        <img src="${splash}" alt="${champ.name}" class="absolute inset-0 w-full h-full object-cover object-top opacity-40" />
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/70 to-slate-900/40"></div>
        <div class="relative p-8 w-full flex flex-col md:flex-row md:items-end gap-6">
          <div class="flex-1">
            <h1 class="text-3xl font-bold mb-1">${champ.name} <span class="text-indigo-300 font-medium text-xl">${champ.title}</span></h1>
            <div class="flex flex-wrap gap-2 mb-4">${roles}</div>
            <p class="text-slate-300 max-w-xl text-sm leading-relaxed line-clamp-4">${champ.lore||''}</p>
          </div>
          <div class="flex items-center gap-3">
            <button data-heart-id="${champ.id}" data-active="${isFavorite(champ.id)}" onclick="toggleFavorite('${champ.id}', this)" class="p-3 rounded-full bg-slate-900/70 border border-slate-600 hover:border-red-500/60 hover:bg-slate-800 transition" aria-label="Favorito">
              ${heartIconSvg(isFavorite(champ.id))}
            </button>
          </div>
        </div>
      </section>

      <section class="mt-10 grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
          <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-700/60 shadow">
            <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">Estadísticas Base</h2>
            <ul class="grid sm:grid-cols-2 gap-3 text-sm text-slate-300">
              <li><span class="text-slate-400">Vida:</span> ${champ.stats.hp}</li>
              <li><span class="text-slate-400">Armadura:</span> ${champ.stats.armor}</li>
              <li><span class="text-slate-400">Daño:</span> ${champ.stats.attackdamage}</li>
              <li><span class="text-slate-400">Vel. Ataque:</span> ${champ.stats.attackspeed}</li>
              <li><span class="text-slate-400">Mana / Energía:</span> ${champ.partype}</li>
              <li><span class="text-slate-400">Rango:</span> ${champ.stats.attackrange}</li>
            </ul>
          </div>
          ${stats ? `
          <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-700/60 shadow space-y-5">
            <div class="flex items-center justify-between">
              <h2 class="text-lg font-semibold">Resumen</h2>
              <span class="text-[11px] text-slate-500">Datos</span>
            </div>
            <div class="grid sm:grid-cols-3 gap-4 text-center">
              <div class="p-3 rounded-xl bg-slate-800/60 border border-slate-600/60">
                <p class="text-xs text-slate-400">Win Rate</p>
                <p class="text-xl font-semibold text-emerald-400">${stats.winRate}%</p>
              </div>
              <div class="p-3 rounded-xl bg-slate-800/60 border border-slate-600/60">
                <p class="text-xs text-slate-400">Pick Rate</p>
                <p class="text-xl font-semibold text-indigo-300">${stats.pickRate}%</p>
              </div>
              <div class="p-3 rounded-xl bg-slate-800/60 border border-slate-600/60">
                <p class="text-xs text-slate-400">Ban Rate</p>
                <p class="text-xl font-semibold text-rose-400">${stats.banRate}%</p>
              </div>
            </div>
            <div class="space-y-3">
              <h3 class="font-medium">Líneas Jugadas</h3>
              ${laneDistribution(stats.lanes)}
            </div>
            <div class="grid md:grid-cols-2 gap-6">
              <div class="space-y-3">
                <h3 class="font-medium">Build Principal</h3>
                <ul class="space-y-2">${buildList(stats.build)}</ul>
              </div>
              <div class="space-y-4">
                <div>
                  <h3 class="font-medium mb-1">Inicio</h3>
                  <p class="text-xs text-slate-300">${stats.starter.join(' + ')}</p>
                </div>
                <div>
                  <h3 class="font-medium mb-1">Botas</h3>
                  <p class="text-xs text-slate-300">${stats.boots.join(' o ')}</p>
                </div>
                <div>
                  <h3 class="font-medium mb-1">Orden de Habilidades</h3>
                  <p class="text-xs text-slate-300">${stats.skillOrder}</p>
                </div>
                <div>
                  <h3 class="font-medium mb-1">Runas</h3>
                  <p class="text-xs text-slate-300"><span class="block">${stats.runes.primary}</span><span class="block mt-1">${stats.runes.secondary}</span></p>
                </div>
              </div>
            </div>
          </div>` : `<div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-700/60 text-sm text-slate-400">Sin datos simulados para este campeón todavía.</div>`}
        </div>
        <aside class="space-y-8">
          <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-700/60 shadow space-y-4">
            <h2 class="text-lg font-semibold">Dificultad</h2>
            <p class="text-sm text-slate-300">Valor base: <span class="text-indigo-300 font-medium">${champ.info.difficulty}</span></p>
            <div class="h-2 rounded bg-slate-700/60 overflow-hidden"><div class="h-full bg-indigo-500" style="width:${(champ.info.difficulty/10)*100}%"></div></div>
          </div>
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-700/60 shadow space-y-3">
            <h2 class="text-lg font-semibold">Tags</h2>
            <div class="flex flex-wrap gap-2">${roles || '<span class="text-slate-400 text-xs">—</span>'}</div>
          </div>
        </aside>
      </section>
    `;
  }
  getChampion()
    .then(render)
    .catch(err => {
      console.error(err);
      container.innerHTML = '<p class="text-center text-slate-400 py-10">No se pudo cargar el campeón.</p>';
    });
})();




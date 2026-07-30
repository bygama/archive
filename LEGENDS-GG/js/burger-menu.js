const setupNavActive = () => {
  const path = location.pathname.replace(/\\/g, '/');
  const items = document.querySelectorAll('.nav-item[data-nav]');
  const span = document.querySelector('#span-a'); // Solo existe en index.html
  items.forEach(a => {
    const id = a.getAttribute('data-nav');
    let match = false;
    if (id === 'home' && /index\.html?$/.test(path)) match = true;
    if (id === 'champs' && path.includes('champs')) match = true;
    if (id === 'favorites' && path.includes('favorites')) match = true;
    if (match) {
      a.classList.add('bg-slate-800/70', 'text-white', 'ring-1', 'ring-indigo-500/40');
      span?.classList.remove('bg-slate-800/60');
      const icon = a.querySelector('.nav-icon');
      icon?.classList.add('text-indigo-300');
    }
  });
};

const setupOffcanvas = () => {
  const btn = document.getElementById('menu-btn');
  const panel = document.getElementById('app-sidebar');
  const overlay = document.getElementById('offcanvas-overlay');
  const closeBtn = document.getElementById('close-sidebar');
  const topbar = document.getElementById('mobile-topbar');
  if (!btn || !panel) return;

  const open = () => {
    btn.classList.add('open');
    panel.classList.remove('-translate-x-full');
    overlay?.classList.remove('hidden');
    btn.setAttribute('aria-expanded','true');
    panel.focus?.();
    topbar?.classList.add('-translate-y-full');
  };
  const close = () => {
    btn.classList.remove('open');
    panel.classList.add('-translate-x-full');
    overlay?.classList.add('hidden');
    btn.setAttribute('aria-expanded','false');
    topbar?.classList.remove('-translate-y-full');
  };
  btn.addEventListener('click', () => btn.classList.contains('open') ? close() : open());
  closeBtn?.addEventListener('click', close);
  overlay?.addEventListener('click', close);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });

  // Cerrar al hacer click en un enlace en mobile
  const links = panel.querySelectorAll('a[data-nav]');
  links.forEach(l => l.addEventListener('click', () => {
    if (window.innerWidth < 1024) close();
  }));

  // restaurar estados al cambiar a escritorio
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
      panel.classList.remove('-translate-x-full');
      overlay?.classList.add('hidden');
      btn.classList.remove('open');
      btn.setAttribute('aria-expanded','false');
      topbar?.classList.remove('-translate-y-full');
    } else if (!btn.classList.contains('open')) {
      // sidebar oculto
      panel.classList.add('-translate-x-full');
    }
  });
};

document.addEventListener('DOMContentLoaded', () => {
  setupNavActive();
  setupOffcanvas();
});

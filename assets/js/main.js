// burger menu

(function () {
    const btn = document.getElementById('menuBtn');
    const menu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('overlay');
    const [topBar, midBar, botBar] = btn.querySelectorAll('.bar');
  const backBtn = document.querySelector('#mobileMenu [data-action="back"]');
  const closeMenuBtn = document.querySelector('#mobileMenu [data-action="close-menu"]');

    
    [menu, overlay, topBar, midBar, botBar].forEach(el => {
      if (!el) return;
      el.style.transitionProperty = 'transform, background-color, width, opacity';
      el.style.transitionDuration = '140ms'; 
      el.style.transitionTimingFunction = 'cubic-bezier(0.4,0,0.2,1)';
    });

    const open = () => {
      menu.classList.remove('-translate-x-full');
      overlay.classList.remove('opacity-0', 'pointer-events-none');
      btn.setAttribute('aria-expanded', 'true');
      topBar.classList.add('-translate-y-1', 'bg-[#b13225]');
      midBar.classList.add('w-6', 'bg-[#b13225]');
      botBar.classList.add('translate-y-1', 'bg-[#b13225]');
    };

    const close = () => {
      menu.classList.add('-translate-x-full');
      overlay.classList.add('opacity-0', 'pointer-events-none');
      btn.setAttribute('aria-expanded', 'false');
      topBar.classList.remove('-translate-y-1', 'bg-[#b13225]');
      midBar.classList.remove('w-6', 'bg-[#b13225]');
      botBar.classList.remove('translate-y-1', 'bg-[#b13225]');
    };

    const toggle = () => (btn.getAttribute('aria-expanded') === 'true' ? close() : open());

    btn.addEventListener('click', toggle);
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') close();
    });
    if(backBtn){
      backBtn.addEventListener('click', (e)=>{
        e.preventDefault();
        close();
        setTimeout(()=>{ window.history.back(); }, 260); 
      });
    }
    if(closeMenuBtn){
      closeMenuBtn.addEventListener('click', (e)=>{
        e.preventDefault();
        close();
      });
    }
  })();

  // end burger menu


  // filtros JS:

  (() => {
  const filters = document.querySelectorAll('[data-filter]');

  const closeAll = () => {
    document.querySelectorAll('[data-filter] .menu').forEach(m =>
      m.classList.add('opacity-0','scale-95','pointer-events-none')
    );
    document.querySelectorAll('[data-filter] .filter-btn').forEach(b =>
      b.setAttribute('aria-expanded','false')
    );
  };

  const updateBadge = (root) => {
    const type = root.dataset.type || 'multi';
    const badge = root.querySelector('[data-badge]');
    if (!badge) return;

    if (type === 'single') {
      const checked = root.querySelector('input[type="radio"]:checked');
      if (checked) {
        badge.textContent = checked.dataset.short || checked.value;
        badge.classList.remove('hidden');
      } else {
        badge.classList.add('hidden');
      }
    } else {
      const count = root.querySelectorAll('input[type="checkbox"]:checked').length;
      if (count > 0) {
        badge.textContent = count;
        badge.classList.remove('hidden');
      } else {
        badge.classList.add('hidden');
      }
    }
  };

  
  filters.forEach(root => {
    const btn = root.querySelector('.filter-btn');
    const menu = root.querySelector('.menu');

    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = btn.getAttribute('aria-expanded') === 'true';
      closeAll();
      if (!isOpen) {
        menu.classList.remove('opacity-0','scale-95','pointer-events-none');
        btn.setAttribute('aria-expanded','true');
      }
    });

    root.querySelectorAll('input').forEach(inp => {
      inp.addEventListener('change', () => {
        updateBadge(root);
        
        if (inp.type === 'radio') closeAll();
      });
    });

    
    updateBadge(root);
  });

  document.addEventListener('click', closeAll);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAll(); });
})();

// end filters

// filters.js - solo maneja UI de dropdowns y comunica filtros a api.js

const filterStateUI = {
  rols: new Set(),
  difficulty: null,
  sort: null
};

function closeAllDropdowns(except = null) {
  document.querySelectorAll('.dropdown').forEach(d => {
    if (d !== except) {
      const trigger = d.querySelector('.dropdown-trigger');
      const panel = d.querySelector('.dropdown-panel');
      if (trigger && panel && !panel.classList.contains('hidden')) {
        panel.classList.add('hidden');
        trigger.setAttribute('aria-expanded','false');
        trigger.querySelector('svg')?.classList.remove('rotate-180');
      }
    }
  });
}

function toggleDropdown(drop) {
  const trigger = drop.querySelector('.dropdown-trigger');
  const panel = drop.querySelector('.dropdown-panel');
  if (!trigger || !panel) return;
  const open = panel.classList.contains('hidden');
  closeAllDropdowns(open ? drop : null);
  if (open) {
    panel.classList.remove('hidden');
    trigger.setAttribute('aria-expanded','true');
    trigger.querySelector('svg')?.classList.add('rotate-180');
  } else {
    panel.classList.add('hidden');
    trigger.setAttribute('aria-expanded','false');
    trigger.querySelector('svg')?.classList.remove('rotate-180');
  }
}

function updateActiveVisual() {
  document.querySelectorAll('.filter-chip').forEach(btn => btn.classList.remove('bg-indigo-600/70','text-white'));
  document.querySelectorAll('[data-filter-role]').forEach(btn => {
    if (filterStateUI.rols.has(btn.dataset.filterRole)) btn.classList.add('bg-indigo-600/70','text-white');
  });
  document.querySelectorAll('[data-filter-diff]').forEach(btn => {
    if (filterStateUI.difficulty === btn.dataset.filterDiff) btn.classList.add('bg-indigo-600/70','text-white');
  });
  document.querySelectorAll('[data-sort]').forEach(btn => {
    if (filterStateUI.sort === btn.dataset.sort) btn.classList.add('bg-indigo-600/70','text-white');
  });
  const clearBtn = document.getElementById('clear-filters');
  if (clearBtn) {
    const has = filterStateUI.rols.size || filterStateUI.difficulty || filterStateUI.sort;
    clearBtn.classList.toggle('opacity-0', !has);
    clearBtn.classList.toggle('pointer-events-none', !has);
    clearBtn.classList.toggle('scale-95', !has);
    clearBtn.classList.toggle('opacity-100', !!has);
    clearBtn.classList.toggle('scale-100', !!has);
  }
}

// Llama a la función expuesta en api.js
function pushFiltersToApi() {
  window.setChampionFilters?.({
    rols: filterStateUI.rols,
    difficulty: filterStateUI.difficulty,
    sort: filterStateUI.sort
  });
}

function clearAllFilters() {
  filterStateUI.rols.clear();
  filterStateUI.difficulty = null;
  filterStateUI.sort = null;
  updateActiveVisual();
  pushFiltersToApi();
  showToast?.({message:'Filtros reiniciados', type:'info', emoji:'🧹', duration:2000});
}

function handleChipClick(btn) {
  if (btn.dataset.filterRole) {
    const r = btn.dataset.filterRole;
    filterStateUI.rols.has(r) ? filterStateUI.rols.delete(r) : filterStateUI.rols.add(r);
  } else if (btn.dataset.filterDiff) {
    const d = btn.dataset.filterDiff;
    filterStateUI.difficulty = filterStateUI.difficulty === d ? null : d;
  } else if (btn.dataset.sort) {
    const s = btn.dataset.sort;
    filterStateUI.sort = filterStateUI.sort === s ? null : s;
  }
  updateActiveVisual();
  pushFiltersToApi();
}

function setupFiltersUI() {
  // Dropdown toggles
  document.querySelectorAll('.dropdown').forEach(drop => {
    const trigger = drop.querySelector('.dropdown-trigger');
    if (trigger) {
      trigger.addEventListener('click', () => toggleDropdown(drop));
    }
  });
  // Outside click
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown')) closeAllDropdowns();
  });
  // ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAllDropdowns();
  });

  // Chips delegation
  document.getElementById('filters-bar')?.addEventListener('click', (e) => {
    const btn = e.target.closest('.filter-chip');
    if (btn) handleChipClick(btn);
  });

  // Clear button
  document.getElementById('clear-filters')?.addEventListener('click', clearAllFilters);
}

document.addEventListener('DOMContentLoaded', () => {
  setupFiltersUI();
  updateActiveVisual();
});

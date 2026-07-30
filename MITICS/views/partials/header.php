<header class="sticky top-0 z-50">
  <nav class="text-black/90 backdrop-blur-md bg-white/70 border-b border-white/40 shadow-sm">
    <div class="mx-auto max-w-[1500px] px-4 sm:px-6 lg:px-8">
      
      <div class="grid grid-cols-3 h-16 items-center">
        
        <div class="justify-self-start">
          <button id="menuBtn"
                  class="inline-flex items-center justify-center p-2 rounded-md hover:bg-white/10 transition focus:outline-none focus:ring-2 focus:ring-white/40"
                  aria-label="Abrir menú" aria-controls="mobileMenu" aria-expanded="false">
            <span class="sr-only">Abrir menú</span>
            
            <span class="block w-6 h-4 relative">
              <span class="bar absolute left-1/2 top-0 h-0.5 w-6 bg-current transition-transform duration-300 ease-in-out origin-center -translate-x-1/2"></span>
              <span class="bar absolute left-1/3 top-1/2 -translate-y-1/2 h-0.5 w-4 bg-current transition-opacity duration-200 -translate-x-1/2"></span>
              <span class="bar absolute left-1/2 bottom-0 h-0.5 w-6 bg-current transition-transform duration-300 ease-in-out origin-center -translate-x-1/2"></span>
            </span>
          </button>
        </div>

        
        <h1 class="justify-self-center text-2xl sm:text-3xl font-normal tracking-wider select-none font-display">
          <a href="index.php?section=home" class="heading-red gold-gradient-text drop-shadow-sm">MITICS</a>
        </h1>

        
        <div class="justify-self-end hidden sm:flex items-center gap-3">
          
          <a href="/PII-GARCIA-PARCIAL_1/index.php?section=products" aria-label="Catálogo de Reliquias" class="group relative w-10 h-10 inline-flex items-center justify-center rounded-md bg-white/30 hover:bg-white/40 transition border border-white/40">
            <svg class="w-5 h-5 text-[#b13225] group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 7.5l8-4 8 4-8 4-8-4Z"/>
              <path d="M4 12l8 4 8-4"/>
              <path d="M4 16.5l8 4 8-4"/>
            </svg>
          </a>
          
          <a href="/PII-GARCIA-PARCIAL_1/index.php?section=alumn" aria-label="Archivo del Custodio" class="group relative w-10 h-10 inline-flex items-center justify-center rounded-md bg-white/30 hover:bg-white/40 transition border border-white/40">
            <svg class="w-5 h-5 text-[#b13225] group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 20V7.5L12 4l7 3.5V20"/>
              <path d="M9 14.5L12 16l3-1.5"/>
              <path d="M9 10.5L12 12l3-1.5"/>
            </svg>
          </a>
          
            <a href="/PII-GARCIA-PARCIAL_1/index.php?section=contact" aria-label="Contacto / Ofrecer Reliquia" class="group relative w-10 h-10 inline-flex items-center justify-center rounded-md bg-white/30 hover:bg-white/40 transition border border-white/40">
            <svg class="w-5 h-5 text-[#b13225] group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 6.5 12 12l8-5.5"/>
              <path d="M4 17.5V6.5L12 12l8-5.5v11"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black/50 opacity-0 pointer-events-none transition-opacity duration-300 z-40"></div>

  <!-- Off-canvas Menu -->
  <?php $current = $_GET['section'] ?? 'home'; ?>
  <aside id="mobileMenu"
         class="fixed inset-y-0 left-0 w-[20rem] sm:w-[22rem] bg-neutral-950/95 backdrop-blur-md text-white -translate-x-full transition-transform duration-300 ease-out z-50 border-r border-neutral-800 flex flex-col">
    <div class="p-7 flex-1 overflow-y-auto space-y-10">
      
      <div class="space-y-3">
        <div class="flex items-start justify-between gap-4">
          <a href="/PII-GARCIA-PARCIAL_1/index.php?section=home" class="text-4xl font-display heading-red gold-gradient-text" data-deco="blade">MITICS</a>
          <button data-action="close-menu" aria-label="Cerrar menú"
            class="mt-1 inline-flex items-center justify-center w-9 h-9 rounded-md bg-white/5 hover:bg-white/10 border border-white/10 transition group">
            <span class="relative flex items-center justify-center w-4 h-4">
              <span class="absolute w-full h-0.5 bg-white rotate-45 group-hover:bg-[#b13225] transition"></span>
              <span class="absolute w-full h-0.5 bg-white -rotate-45 group-hover:bg-[#b13225] transition"></span>
            </span>
          </button>
        </div>
        <p class="text-xs  text-neutral-400 leading-relaxed pt-3 pr-4">Archivo selecto de reliquias y artefactos: catalogación simbólica, procedencia y rareza en un ecosistema académico.</p>
      </div>

      
      <nav class="space-y-2 font-display text-lg tracking-wide">
        <?php
          $links = [
            'home' => 'Inicio',
            'alumn' => 'El Custodio',
            'products' => 'Productos',
            'contact' => 'Contacto'
          ];
          foreach ($links as $slug => $label):
            $active = $current === $slug;
        ?>
          <a href="/PII-GARCIA-PARCIAL_1/index.php?section=<?= $slug ?>"
             class="group flex items-center gap-3 px-4 py-2 rounded-md transition relative <?= $active ? 'bg-white/10 text-[#b13225]' : 'text-neutral-200 hover:bg-white/5 hover:text-[#b13225]' ?>">
            <span class="w-1 h-6 rounded-full bg-gradient-to-b from-[#b13225] to-[#7f2017] opacity-<?= $active ? '100' : '0 group-hover:opacity-60' ?> transition"></span>
            <span><?= $label ?></span>
          </a>
        <?php endforeach; ?>
      </nav>

      <!-- Separador -->
      <div class="h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

  
      <div class="space-y-4">
        <h3 class="text-sm tracking-widest font-display text-neutral-400">ACCIONES</h3>
        <div class="flex flex-col gap-3">
          <a href="/PII-GARCIA-PARCIAL_1/index.php?section=products" class="btn-mythic-red !w-full justify-center">Catálogo Completo</a>
          <a href="/PII-GARCIA-PARCIAL_1/index.php?section=contact" class="btn-outline-gold !w-full justify-center">Ofrecer Reliquia</a>
        </div>
      </div>

      
      <div class="space-y-3">
        <h3 class="text-sm tracking-widest font-display text-neutral-400">MUY PRONTO</h3>
        <ul class="text-xs text-neutral-400 space-y-1">
          <li>Orbe Crepuscular de Helios</li>
          <li>Fragmento Abisal del Egeo</li>
          <li>Lira Susurrante de Calíope</li>
          <li>Pluma Carbonizada de Ícaro</li>
        </ul>
      </div>
    </div>
    
    <div class="p-5 border-t border-neutral-800 text-[10px] text-neutral-500 flex items-center justify-between">
      <span>&copy; <?= date('Y'); ?> MITICS</span>
      <span class="uppercase tracking-widest">Archivo</span>
    </div>
  </aside>
</header>


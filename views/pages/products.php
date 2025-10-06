<main class="pt-16">
  <header class="max-w-6xl mx-auto px-6 mb-10 text-center">
    <h1 class="text-5xl font-display heading-red" data-deco="blade">Catálogo</h1>
    <p class="mt-4 text-neutral-600 max-w-2xl mx-auto">Explora reliquias con atributos únicos. Cada pieza incluye procedencia, rareza y certificación simbólica.</p>
  </header>


  <?php

  require_once "classes/Products.php";

  $allCategories = Mitics::obtainCategories();
  $allRarities = Mitics::obtainRarities();
  $allOrigins = Mitics::obtainOrigins();



  $categorySel  = isset($_GET['category']) ? explode(',', $_GET['category']) : [];
  $raritySel = isset($_GET['rarity']) ? explode(',', $_GET['rarity']) : [];
  $originSel   = isset($_GET['origin']) ? explode(',', $_GET['origin']) : [];

  /* $categoryFilter = Mitics::miticsByCategory($categorySel);
  $rarityFilter = Mitics::miticsByRarity($raritySel);
  $originFilter = Mitics::miticsByOrigin($originSel);

  $mFiltrados = [];

  
  $mFiltrados = array_merge($categoryFilter, $rarityFilter, $originFilter);

  $uniqueMFiltrados = Mitics::deleteDuplicates($mFiltrados); */

  
  $strictFiltered = Mitics::filterMitics($categorySel, $raritySel, $originSel);


  

  ?>




  <form id="filtersForm" method="GET" action="actionFilters.php" class="relative">
    <section class="">
      <div class="mx-auto mb-3 max-w-5xl px-4 sm:px-6 lg:px-8 pt-5 pb-4">
        <div class="flex flex-wrap items-center justify-center items-stretch gap-3 md:gap-4">



          <!-- Categoría -->
          <div class="relative" data-filter data-type="multi">
            <button type="button" aria-expanded="false"
              class="filter-btn group inline-flex items-center gap-2 rounded-xl px-4 py-2 bg-[var(--mythic-panel)] border border-[#d2cbc5] shadow-sm hover:shadow-md hover:border-[var(--mythic-red)] transition text-sm font-medium">
              <span class="font-display tracking-wide text-[var(--mythic-red)]">Categoría</span>
              <span data-badge class="hidden text-[10px] font-semibold leading-none rounded bg-[var(--mythic-bg)] text-[var(--mythic-gold-soft)] px-2 py-0.5 shadow"></span>
              <svg class="size-4 text-[var(--mythic-red)] transition" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5.5 7.5L10 12l4.5-4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
            <div class="menu absolute z-50 mt-2 rounded-2xl bg-[#1d1a1a] border border-[var(--mythic-red)]/40 shadow-2xl shadow-black/40 p-2 opacity-0 scale-95 pointer-events-none transition duration-150 origin-top-left max-h-[60vh] overflow-auto w-fit max-w-[92vw] left-1/2 -translate-x-1/2 sm:w-60 sm:max-w-none sm:left-0 sm:translate-x-0">
              <?php foreach ($allCategories as $category) { ?>
              <label class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-sm cursor-pointer">
                <input class="size-4 accent-[var(--mythic-red)]" type="checkbox" name="category[]" value="<?php echo $category; ?>">
                <span class="text-[var(--mythic-gold-soft)]"><?php echo $category; ?></span>
              </label>
              <?php } ?>
            </div>
          </div>

          <!-- Rareza -->
          <div class="relative" data-filter data-type="multi">
            <button type="button" aria-expanded="false"
              class="filter-btn group inline-flex items-center gap-2 rounded-xl px-4 py-2 bg-[var(--mythic-panel)] border border-[#d2cbc5] shadow-sm hover:shadow-md hover:border-[var(--mythic-red)] transition text-sm font-medium">
              <span class="font-display tracking-wide text-[var(--mythic-red)]">Rareza</span>
              <span data-badge class="hidden text-[10px] font-semibold leading-none rounded bg-[var(--mythic-bg)] text-[var(--mythic-gold-soft)] px-2 py-0.5 shadow"></span>
              <svg class="size-4 text-[var(--mythic-red)] transition" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5.5 7.5L10 12l4.5-4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
            <div class="menu absolute z-50 mt-2 rounded-2xl bg-[#1d1a1a] border border-[var(--mythic-red)]/40 shadow-2xl shadow-black/40 p-2 opacity-0 scale-95 pointer-events-none transition duration-150 origin-top-left max-h-[60vh] overflow-auto w-fit max-w-[92vw] left-1/2 -translate-x-1/2 sm:w-60 sm:max-w-none sm:left-0 sm:translate-x-0">
              <?php foreach ($allRarities as $rarity) { ?>
              <label class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-sm cursor-pointer">
                <input class="size-4 accent-[var(--mythic-red)]" type="checkbox" name="rarity[]" value="<?php echo $rarity; ?>">
                <span class="text-[var(--mythic-gold-soft)]"><?php echo $rarity; ?></span>
              </label>
              <?php } ?>
            </div>
          </div>

          <!-- Origen -->
          <div class="relative" data-filter data-type="multi">
            <button type="button" aria-expanded="false"
              class="filter-btn group inline-flex items-center gap-2 rounded-xl px-4 py-2 bg-[var(--mythic-panel)] border border-[#d2cbc5] shadow-sm hover:shadow-md hover:border-[var(--mythic-red)] transition text-sm font-medium">
              <span class="font-display tracking-wide text-[var(--mythic-red)]">Origen</span>
              <span data-badge class="hidden text-[10px] font-semibold leading-none rounded bg-[var(--mythic-bg)] text-[var(--mythic-gold-soft)] px-2 py-0.5 shadow"></span>
              <svg class="size-4 text-[var(--mythic-red)] transition" viewBox="0 0 20 20" fill="currentColor">
                <path d="M5.5 7.5L10 12l4.5-4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
            <div class="menu absolute z-50 mt-2 rounded-2xl bg-[#1d1a1a] border border-[var(--mythic-red)]/40 shadow-2xl shadow-black/40 p-2 opacity-0 scale-95 pointer-events-none transition duration-150 origin-top-left max-h-[60vh] overflow-auto w-fit max-w-[92vw] left-1/2 -translate-x-1/2 sm:w-60 sm:max-w-none sm:left-0 sm:translate-x-0">
              <?php foreach ($allOrigins as $origin) { ?>
              <label class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 text-sm cursor-pointer">
                <input class="size-4 accent-[var(--mythic-red)]" type="checkbox" name="origin[]" value="<?php echo $origin; ?>">
                <span class="text-[var(--mythic-gold-soft)]"><?php echo $origin; ?></span>
              </label>
              <?php } ?>
            </div>
          </div>

          <!-- Acciones -->
          <div class="flex items-center justify-center gap-3 ml-auto order-last w-full md:w-auto pt-1 md:pt-0 ">
            <button type="submit" class="btn-mythic-red text-xs tracking-wide">Aplicar</button>
            <a href="/PII-GARCIA-PARCIAL_1/index.php?section=products" class="text-[13px] font-medium text-[var(--mythic-red)] hover:underline underline-offset-4">Borrar</a>
          </div>

        </div>
      </div>
    </section>
  </form>

  <section class="mx-auto max-w-[1500px] px-6 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">





    <?php
    $products = Mitics::Mitics();

    
    $hasFilters = !empty($categorySel) || !empty($raritySel) || !empty($originSel);
    if (!$hasFilters) {
      foreach ($products as $p) {
    ?>
        <article class="group mythic-card overflow-hidden flex flex-col">
          <div class="relative h-[400px]">
            <img src="<?= $p->get_image() ?>" alt="<?= $p->get_name() ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            <div class="absolute top-3 left-3 text-xs px-2 py-1 rounded bg-black/50 text-white tracking-wide"><?= strtoupper($p->get_rarity()) ?></div>
          </div>
          <div class="flex flex-col flex-1 p-5 gap-3">
            <h2 class="text-2xl font-display heading-red leading-snug">
              <?= $p->get_name() ?>
            </h2>
            <p class="text-sm text-neutral-700 clamp-2 flex-1"><?= $p->get_description() ?></p>
            <div class="flex items-center justify-between mt-2">
              <span class="font-display text-lg heading-red">$<?= $p->get_price() ?></span>
              <a href="index.php?section=product&id=<?= $p->get_id() ?>" class="btn-outline-gold text-xs">Detalle</a>
            </div>
          </div>
        </article>
      <?php
      }
    } else {
      foreach ($strictFiltered as $p) {

      ?>

        <article class="group mythic-card overflow-hidden flex flex-col">
          <div class="relative h-[400px]">
            <img src="<?= $p->get_image() ?>" alt="<?= $p->get_name() ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            <div class="absolute top-3 left-3 text-xs px-2 py-1 rounded bg-black/50 text-white tracking-wide"><?= strtoupper($p->get_rarity()) ?></div>
          </div>
          <div class="flex flex-col flex-1 p-5 gap-3">
            <h2 class="text-2xl font-display heading-red leading-snug">
              <?= $p->get_name() ?>
            </h2>
            <p class="text-sm text-neutral-700 clamp-2 flex-1"><?= $p->get_description() ?></p>
            <div class="flex items-center justify-between mt-2">
              <span class="font-display text-lg heading-red">$<?= $p->get_price() ?></span>
              <a href="index.php?section=product&id=<?= $p->get_id() ?>" class="btn-outline-gold text-xs">Detalle</a>
            </div>
          </div>
        </article>

    <?php

      } if (empty($strictFiltered)) {
        echo "<p class='text-center text-neutral-600 col-span-full'>No se encontraron reliquias que coincidan con los filtros seleccionados.</p>";
      }
    }
    ?>
  </section>

</main>
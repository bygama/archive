# Design System — Umbrella Corporation

> *"Our business is life itself."*
> *"Obedience breeds discipline. Discipline breeds unity. Unity breeds power. Power is life."*

Sistema de diseño basado en la identidad visual real de Umbrella Corporation (Resident Evil / Capcom, 1996–presente). Colores extraídos directamente del logo oficial; tipografías documentadas en la franquicia.

---

## 🎨 Paleta de Colores (extraída del logo oficial)

Los siguientes valores HEX provienen de un análisis directo del logo oficial de Umbrella Corporation, ignorando antialiasing y sombras suaves.

| Token | Nombre | HEX | RGB | Rol |
|-------|--------|-----|-----|-----|
| `--umbrella-red` | Cherry Red | `#ED1C24` | `237, 28, 36` | **Color primario.** Rojo principal del logo. CTAs, énfasis crítico, marca. |
| `--crimson-shadow` | Indian Red | `#830F14` | `131, 15, 20` | Rojo oscuro de sombra/profundidad del logo. Hover states, gradientes, texto destacado sobre fondo claro. |
| `--obsidian-brown` | Dark Brown | `#2F1B15` | `47, 27, 21` | Casi-negro cálido. Fondos oscuros, tipografía principal sobre claro, paneles corporativos. |
| `--slate-steel` | Slate Grey | `#5D6E6E` | `93, 110, 110` | Gris medio frío. Bordes, divisores, iconografía secundaria, instrumentación. |
| `--cool-grey` | Cool Grey | `#9CACAD` | `156, 172, 173` | Gris claro estéril. Texto secundario, fondos de tarjetas, superficies de laboratorio. |
| `--void` | Void Black | `#050505` | `5, 5, 5` | Negro absoluto. Fondo base global del sitio. |
| `--void-2` | Void Black 2 | `#0A0A0A` | `10, 10, 10` | Negro suave para secciones alternadas y banners. |

> 💡 **Color complementario obligatorio:** blanco puro `#FFFFFF` — el logo de Umbrella es **oficialmente rojo + blanco** alternados en 8 segmentos. Usar como contraparte de `--umbrella-red`.

### Aplicación recomendada

- **Marca pura** → `--umbrella-red` + `#FFFFFF` (la combinación canónica del paraguas)
- **Modo oscuro** → fondo `--void`, texto `--cool-grey`, acento `--umbrella-red`
- **Modo claro / clínico** → fondo `#FFFFFF`, texto `--void`, acento `--umbrella-red`
- **Profundidad** → degradar entre `--umbrella-red` y `--crimson-shadow` (igual que el gradiente radial del logo real)

---

## 🔤 Tipografías

### 1. Michroma — *Display / Wordmark*
**Diseñador:** Vernon Adams (Google Fonts)
Reemplazo libre de Eurostile Bold Extended. Sans-serif geométrica extendida con el aire futurista-corporativo característico del wordmark Umbrella.

- **Pesos:** Regular (400) — la única disponible
- **Uso:** Logo, H1, H2, H3, banners institucionales, navegación, badges
- **Fallback:** `'Eurostile Extended', 'Bank Gothic', sans-serif`

### 2. Inter — *Corporate Body*
**Diseñador:** Rasmus Andersson (2017)
Sans-serif neutra y autoritaria — la voz oficial de cualquier multinacional. Reemplaza a Helvetica Black manteniendo la legibilidad y el peso.

- **Pesos:** 400 / 500 / 600 / 700 / 900
- **Uso:** Cuerpo de texto, UI, tablas, formularios, navegación, descripciones
- **Fallback:** `'Helvetica Neue', 'Arial', sans-serif`

### 3. Special Elite — *Typewriter / Documents*
**Inspiración:** la icónica máquina de escribir que aparece en toda la saga Resident Evil como sistema de guardado, junto con los documentos clasificados, archivos médicos y notas de investigadores que aparecen dentro del juego.

- **Pesos:** Regular
- **Uso:** Documentos internos, archivos clasificados, notas de laboratorio, eyebrows, IDs de muestras, etiquetas técnicas, tactical-bar
- **Fallback:** `'Courier Prime', 'Courier New', monospace`

> ⚠️ **Nota de alineación:** Special Elite tiene un baseline más alto que Inter. Cualquier inline-flex que combine icono + texto en esta fuente requiere `line-height: 1` en el contenedor + `transform: translateY(-1px)` en el SVG (o `translateY(2px)` en el span del texto si el icono no se puede mover).

---

## 🧩 Variables CSS

```css
:root {
  /* Colores oficiales extraídos del logo */
  --umbrella-red:    #ED1C24;
  --crimson-shadow:  #830F14;
  --obsidian-brown:  #2F1B15;
  --slate-steel:     #5D6E6E;
  --cool-grey:       #9CACAD;
  --pure-white:      #FFFFFF;
  --void:            #050505;
  --void-2:          #0A0A0A;

  /* Tipografías */
  --font-display:    'Michroma', 'Eurostile Extended', 'Bank Gothic', sans-serif;
  --font-body:       'Inter', 'Helvetica Neue', 'Arial', sans-serif;
  --font-classified: 'Special Elite', 'Courier Prime', 'Courier New', monospace;

  /* Tamaños fluidos */
  --fs-h1: clamp(2.25rem, 4vw + 1rem, 4.25rem);
  --fs-h2: clamp(1.5rem, 1.5vw + 1rem, 2.25rem);
  --fs-h3: clamp(1.125rem, 0.5vw + 1rem, 1.5rem);
  --fs-body: 1rem;
  --fs-small: 0.8125rem;
  --fs-tiny: 0.6875rem;

  /* Espaciado fluido */
  --section-y: clamp(4rem, 6vw, 7rem);
  --gutter:    clamp(1.25rem, 2vw, 2rem);
}

/* Importar tipografías */
@import url('https://fonts.googleapis.com/css2?family=Michroma&family=Inter:wght@400;500;600;700;900&family=Special+Elite&display=swap');
```

---

## 📐 Principios de Diseño

1. **Autoridad fría** — Espaciado generoso, jerarquía clara, ningún elemento decorativo gratuito.
2. **Contraste binario** — Rojo + blanco / rojo + negro. Sin gradientes suaves entre colores no relacionados; las transiciones son nítidas.
3. **Modularidad clínica** — Grids estrictos, bordes definidos, esquinas rectas o ligeramente redondeadas (máx. 2px).
4. **Iconografía simbólica** — El paraguas octagonal de 8 segmentos como marca recurrente; iconos de línea fina (Tabler Icons), nunca ilustraciones orgánicas.
5. **Tipografía como señal** — Michroma en mayúsculas para títulos institucionales, Inter para cuerpo, máquina de escribir SOLO para "documentos clasificados".
6. **Capas técnicas (HUD)** — Cada bloque importante usa al menos una de: corner-marks rojas, scanlines, hex pattern, glass ribs, vignette radial.
7. **Ritmo de fondos** — Alternar dark/inverted-white/dark a lo largo del scroll para mantener cadencia visual.

---

## ⚠️ Estados y Niveles de Seguridad

| Nivel | Color base | Uso |
|-------|------------|-----|
| **CRITICAL / BIOHAZARD** | `--umbrella-red` `#ED1C24` | Alertas máximas, errores fatales, contención fallida |
| **RESTRICTED / RESTRINGIDO** | `--crimson-shadow` `#830F14` | Acceso restringido, advertencias serias |
| **NOMINAL** | Verde menta `#6cd396` (tinted) | Operación normal, sistemas en estado óptimo |
| **STANDBY / EN ESPERA** | `--cool-grey` `#9CACAD` | Estado en espera, datos inactivos |
| **CLASSIFIED / CLASIFICADO** | `--void` `#050505` + borde rojo | Sobre fondos claros u oscuros, máxima jerarquía |
| **CLEAR / LIBRE** | Outline blanco/negro | Sin restricciones, acceso público |

Los badges adoptan estilo *outlined + tinted* en secciones blancas (categorías, control de acceso, hitos) y *solid + glow* en secciones oscuras (registros, catálogo).

---

## 🧱 Componentes implementados

### Heroes y consolas

| Componente | Archivo / Clase | Uso |
|------------|----------------|-----|
| **`hero-cinema`** | `home.blade.php` | Hero full-bleed con banner cinemático, HUD frame y copy editorial. Único del home. |
| **`catalog-hero`** + **`catalog-hero__bg`** | Patrón compartido | Hero de catálogo, bitácora, acerca y contacto. Imagen base + veil + grid técnica. |
| **`catalog-console`** | Catálogo, bitácora, acerca | Panel diagnóstico lateral con corner-marks, header, filas de datos y footer con barcode. |
| **`access-hero`** + **`access-doc`** | Contacto | Hero compacto + documento clasificado con secciones numeradas, sello rotatorio y botones. |
| **`scientist-card`** | Acerca | Card grande con foto + bio para Mateo Spencer (Director Científico). |

### Tarjetas (cards)

| Componente | Archivo / Clase | Uso |
|------------|----------------|-----|
| **`specimen-card`** + **`specimen-chamber`** | `partials/product-card.blade.php` | Cámara de contención de espécimen biológico. Imagen del virus al 100% + scan beam, hex pattern, glass ribs, top cap (categoría + ID + badge), readout (instalación + temp), barra de RIESGO. Aspect 1/1, alturas uniformes. |
| **`log-card`** | `partials/blog-card.blade.php` | Card de entrada de bitácora. Header con categoría + badge, título line-clamped, footer con FECHA + ID DOC + CTA "Abrir Archivo". |
| **`category-card`** | `home.blade.php` (sección categorías blanca) | Tile de categoría con icono cuadrado rojo, contador `XX / 04`, título line-clamped, descripción y CTA `EXPLORAR →`. |
| **`protocol-step`** | `contact.blade.php` | Card de paso de revisión con número rojo grande, título, descripción y meta tag rojo. |

### Filtros y formularios

| Componente | Archivo / Clase | Uso |
|------------|----------------|-----|
| **`catalog-filters`** | `products/index.blade.php` | Form GET con dos `<select>` nativos (categoría / autorización). Auto-submit en change con `<noscript>` fallback. Lógica server-side en `ProductController::filterProducts()`. |
| **`access-doc__section`** + **`access-doc__legend`** | `contact.blade.php` | Fieldset numerado de formulario clasificado (`01 · Identidad`, `02 · Asignación`, `03 · Justificación`). |
| **`input-control`** + **`input-label`** | Reusables | Inputs y selects con borde gris translúcido y hover/focus rojo. |

### Badges (sistema unificado)

```html
<span class="badge badge-{variant}">
  <icon /> LABEL
</span>
```

Variants: `badge-critical`, `badge-restricted`, `badge-nominal`, `badge-classified`, `badge-clear`, `badge-standby`, `badge-executive` (corona, fondo negro + borde rojo).

El componente `partials/security-badge.blade.php` traduce automáticamente del key inglés (`CLASSIFIED`, `CRITICAL / BIOHAZARD`, etc.) a label en español.

### Detalles HUD reutilizables

- **`corner-mark`** (4 variantes: `tl`, `tr`, `bl`, `br`) — esquinas rojas que enmarcan cualquier contenedor.
- **`hairline-red`** — línea horizontal con gradiente rojo, separador entre secciones.
- **`status-dot`** + **`status-dot-nominal`** — punto pulsante rojo o verde para indicadores de estado.
- **`barcode-line`** — código de barras decorativo.
- **`scanlines`** — overlay con líneas horizontales sutiles para sensación de pantalla CRT.
- **`signal-bars`** — 4 barras verticales rojas que pulsan en secuencia (estilo señal celular).
- **`waveform`** — 28 barras animadas tipo oscilloscope.

---

## 🗂️ Arquitectura de páginas

| Ruta | Archivo | Eyebrow | H1 | Estructura |
|------|---------|---------|----|----|
| `/` | `pages/home.blade.php` | — | Umbrella / Corporation | Hero cinemático → Categorías (blanco) → Featured (bento) → Control de Acceso (blanco) → Registros (oscuro) → Aviso Final |
| `/products` | `products/index.blade.php` | Inventario Biológico | Bioagentes | Hero con storage + console DIAGNÓSTICO BIOLÓGICO → Filtros PHP → Grid 3 cols specimen-cards |
| `/products/{slug}` | `products/show.blade.php` | — | Nombre del virus | Hero con cámara + meta + tabla técnica + relacionados |
| `/blog` | `blog/index.blade.php` | Canal Restringido | Bitácora | Hero con archives + console MONITOR DE TRANSMISIÓN → Grid 3 cols log-cards |
| `/blog/{slug}` | `blog/show.blade.php` | — | Título del post | Artículo + sidebar metadata + relacionados |
| `/about` | `pages/about.blade.php` | — | Mateo Spencer (en card) | Hero con lobby + scientist-card → El Conglomerado (oscuro + console) → Hitos (blanco) → Pilares (oscuro) |
| `/contact` | `pages/contact.blade.php` | Control de Acceso · Protocolo Activo | Solicitar / Acceso | Hero + access-doc form → Pasos de Revisión (3 cards) |
| `/cart` | `pages/cart.blade.php` | Inventario Actual | Carrito de Autorización | Hero + lista de items + sidebar resumen |

---

## 🖼️ Biblioteca de assets

### Banners de hero (`public/images/hero/`)

| Archivo | Página | Concepto |
|---------|--------|----------|
| `umbrella-labs.webp` | Home | Sala de laboratorio con cilindro de contención central y logo Umbrella |
| `umbrella-storage.webp` | Catálogo (Bioagentes) | Almacén con frascos de virus de colores en estanterías |
| `umbrella-archives.webp` | Bitácora (Registros) | Sala de archivos oscura con gabinetes y monitores azules |
| `umbrella-about.webp` | Acerca | Lobby corporativo con escritorio de recepción |
| `umbrella-access.webp` | Solicitar Acceso | Lobby con tiras LED rojas y logo grande en pared |

Todos en WebP **lossless** (formato fuente PNG también incluido por respaldo).

### Productos (`public/images/products/`)

16 imágenes de virus/parásitos/mutágenos en WebP lossless. Cada una se renderiza al 100% del contenedor de la `specimen-chamber` con `object-fit: cover`.

### Equipo (`public/images/team/`)

- `mateo-spencer.webp` — Director Científico, retrato en bata blanca

### Marca (`public/images/`)

- `umbrella-logo.webp` — Logo oficial. Usado en `partials/umbrella-mark.blade.php` (reemplazó al SVG inline).

---

## 🎬 Animaciones

Stack: **GSAP + ScrollTrigger** (registrado en `resources/js/animations.js`), con sincronización a Lenis para smooth scroll.

| Hook | Comportamiento |
|------|----------------|
| `data-hero-reveal` + `data-hero-item` | Stagger de fade + translate-y al cargar el hero |
| `data-animate="fade-up"` | Fade + translate-y triggered por scroll |
| `data-animate="line"` | Scale-x para hairlines rojas |
| `data-animate="panel"` | Slide-in para paneles laterales |
| `data-animate-table` + `data-animate="table-row"` | Stagger de filas de tabla |
| `data-stagger` + `data-stagger-item` | Stagger genérico para grids de cards. **Usa `gsap.fromTo` con `clearProps: 'transform,opacity'`** para evitar que cards queden con `transform` inline si la animación se interrumpe. |
| `data-card-hover` | Lift en mouseenter (no aplicar a cards que ya tienen `:hover` CSS — genera doble transform). |

Todas las animaciones respetan `prefers-reduced-motion: reduce`.

---

## 🌐 Localización

Todo el sitio está en **español (es)** con `<html lang="es">`. Los strings de marca se mantienen en su forma original:
- `Umbrella Corporation`
- `Mateo Spencer`
- Nombres de virus (`T-Virus`, `G-Virus`, `Las Plagas`, etc.) — son nombres propios de la franquicia.

Identificadores técnicos en mayúsculas inglesas (`CLR 04`, `UC-1968-A`, `SYNC OK`, `REC`) se conservan como códigos de instrumentación tipo HUD.

---

## 🧪 Stack técnico

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend bundling:** Vite 7
- **CSS:** Tailwind CSS v4 + variables CSS personalizadas en `resources/css/app.css`
- **JS:** GSAP + ScrollTrigger + Lenis (smooth scroll)
- **Iconos:** Tabler Icons (vía `blade-tabler-icons`)
- **Datos:** Eloquent + MySQL. Tablas `categories`, `products`, `posts` creadas con Migrations y cargadas con Seeders (`Category/Product/PostSeeder` invocados desde `DatabaseSeeder`). Models en `app/Models/` con relación `Product belongsTo Category`, scopes (`Product::scopeFiltered`, `Post::scopeLatestPublished`) y trait compartido `HasSecurityLevel`.

---

## 🔬 Fuentes de inspiración

- **Colores:** análisis directo del logo oficial mediante herramienta de extracción.
- **Tipografías:** Michroma, Inter y Special Elite (todas Google Fonts) como reemplazos libres de Eurostile Bold Extended, Helvetica Black y máquina de escribir clásica.
- **Lore:** el paraguas oficial está formado por **8 segmentos triangulares alternos rojo/blanco** dispuestos en octágono, basado en el símbolo octogonal hallado por Oswell E. Spencer y Mother Miranda (canónico en *Resident Evil Village*).
- **Visual:** Resident Evil 2 Remake / RE3 Remake (HUD, terminales de NEST y Spencer Mansion), Resident Evil Village (UI clasificada), franchise documentation (RE Wiki).

---

*Property of Umbrella Corporation. Unauthorized reproduction is strictly prohibited.*

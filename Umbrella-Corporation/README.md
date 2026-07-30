# Umbrella Research Division

## Stack

| Capa        | Tooling                                                  |
| ----------- | -------------------------------------------------------- |
| Framework   | Laravel 13, PHP ^8.3                                     |
| Vistas      | Blade (`@extends` + `@section`)                          |
| Estilo      | Tailwind CSS v4 + variables CSS personalizadas           |
| Motion      | GSAP 3 + ScrollTrigger, Lenis smooth scroll              |
| Iconografía | `secondnetwork/blade-tabler-icons`                       |
| Build       | Vite 7                                                   |
| Datos       | MySQL via Eloquent — Migrations + Seeders en `database/` |

---

## Levantar el proyecto

```bash
composer install
npm install
cp .env.example .env
# Crear la base de datos MySQL (umbrella_research por default)
mysql -uroot -e "CREATE DATABASE umbrella_research CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php artisan key:generate
php artisan migrate --seed
npm run build           # o `npm run dev` para hot reload con Vite
php artisan serve
```

Abrir `http://localhost:8000`.

> Si tu MySQL usa otra credencial, ajustá `DB_USERNAME` / `DB_PASSWORD` en `.env` antes del `migrate --seed`.

---

## Rutas

| URL                | Vista                   | Controller                      |
| ------------------ | ----------------------- | ------------------------------- |
| `/`                | `pages/home`            | `HomeController@index`          |
| `/products`        | `products/index`        | `ProductController@index`       |
| `/products/{slug}` | `products/show`         | `ProductController@show`        |
| `/blog`            | `blog/index`            | `BlogController@index`          |
| `/blog/{slug}`     | `blog/show`             | `BlogController@show`           |
| `/security-levels` | `pages/security-levels` | `PageController@securityLevels` |
| `/about`           | `pages/about`           | `PageController@about`          |
| `/contact`         | `pages/contact`         | `PageController@contact`        |
| `/cart`            | `pages/cart`            | `PageController@cart`           |

---

## Estructura

```
app/
├── Http/Controllers/
│   ├── HomeController.php
│   ├── ProductController.php
│   ├── BlogController.php
│   └── PageController.php
└── Models/
    ├── Category.php          # hasMany(Product)
    ├── Product.php           # belongsTo(Category) + scope filtered + dossier JSON
    ├── Post.php              # scopeLatestPublished + scopeByCategory
    └── Concerns/
        └── HasSecurityLevel.php   # trait compartido por Product y Post

database/
├── migrations/
│   ├── 0001_01_01_*           # Laravel default (users, cache, jobs)
│   ├── ..._create_categories_table.php
│   ├── ..._create_products_table.php   # FK a categories + dossier JSON
│   └── ..._create_posts_table.php      # 13 campos propios
└── seeders/
    ├── DatabaseSeeder.php
    ├── CategorySeeder.php
    ├── ProductSeeder.php
    └── PostSeeder.php

resources/
├── css/app.css               # design system + componentes Tailwind v4
├── js/
│   ├── app.js                # entrypoint
│   ├── animations.js         # reveals GSAP + ScrollTrigger
│   ├── lenis.js              # smooth scroll
│   ├── navigation.js         # menú mobile accesible
│   └── filters.js            # filtros visuales del catálogo
└── views/
    ├── layouts/app.blade.php
    ├── partials/             # header, footer, cards, panels, badges, mark
    ├── pages/                # home, security-levels, about, contact, cart
    ├── products/             # index, show
    └── blog/                 # index, show

routes/web.php                # 9 rutas thin
```

---

## Design system

### Paleta

| Token              | Hex       | Uso                                |
| ------------------ | --------- | ---------------------------------- |
| `--umbrella-red`   | `#ED1C24` | Marca, CTAs, alertas críticas      |
| `--crimson-shadow` | `#830F14` | Hover, profundidad, restricted     |
| `--obsidian-brown` | `#2F1B15` | Fondos cálidos casi-negros         |
| `--slate-steel`    | `#5D6E6E` | Bordes, divisores, instrumentación |
| `--cool-grey`      | `#9CACAD` | Texto secundario, superficies      |
| `--pure-white`     | `#FFFFFF` | Contraste, fondos clínicos         |
| `--void`           | `#050505` | Fondo del sitio                    |

### Tipografía

- **Display:** Michroma (uppercase, letter-spacing técnico)
- **Body:** Inter
- **Classified:** Special Elite (IDs, fechas, badges, metadata)

Importadas vía Google Fonts en `resources/css/app.css`.

### Componentes (`@layer components`)

`btn-primary` · `btn-secondary` · `btn-ghost` · `btn-restricted` · `card-classified` · `card-technical` · `clearance-panel` · `technical-panel` · `badge-critical` · `badge-restricted` · `badge-nominal` · `badge-standby` · `badge-classified` · `badge-clear` · `data-table` · `input-control` · `icon-frame` · `barcode-line` · `corner-frame` · `scan-frame`.

---

## Animaciones

Todas se aplican via `data-animate` en HTML para no acoplar markup con JS:

| Atributo                              | Efecto                         |
| ------------------------------------- | ------------------------------ |
| `data-hero-reveal` + `data-hero-item` | Stagger de hero al cargar      |
| `data-animate="fade-up"`              | Reveal con ScrollTrigger       |
| `data-animate="line"`                 | scaleX de líneas técnicas      |
| `data-animate="panel"`                | Slide lateral sutil            |
| `data-animate="table-row"`            | Reveal por fila                |
| `data-stagger` + `data-stagger-item`  | Cards con stagger              |
| `data-card-hover`                     | TranslateY al pasar mouse/foco |

Lenis se desactiva automáticamente con `prefers-reduced-motion`.

---

## Scripts

```bash
npm run dev      # Vite watch
npm run build    # build de producción
composer test    # PHPUnit
```

**Property of Umbrella Corporation · Internal Use Only**

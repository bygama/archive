@extends('layouts.app')

@section('title', 'Bioagentes')
@section('description', 'Interfaz de ecommerce clasificada para archivos de investigación ficticios, equipo de contención y activos de simulación biológica.')

@section('content')

{{-- el hero --}}
<section class="hero-cinema relative overflow-hidden" data-hero-reveal aria-labelledby="hero-heading">
    <div class="hero-cinema__media" aria-hidden="true">
        <img
            src="{{ asset('images/hero/umbrella-labs.webp') }}"
            alt=""
            class="hero-cinema__image"
            loading="eager"
            fetchpriority="high"
            decoding="async"
        />
        <div class="hero-cinema__veil"></div>
        <div class="hero-cinema__grid"></div>
        <div class="hero-cinema__scan"></div>
        <div class="hero-cinema__vignette"></div>
    </div>

    {{-- las esquinitas del hud --}}
    <div class="hero-cinema__hud" aria-hidden="true">
        <span class="hero-corner hero-corner--tl"></span>
        <span class="hero-corner hero-corner--tr"></span>
        <span class="hero-corner hero-corner--bl"></span>
        <span class="hero-corner hero-corner--br"></span>
    </div>

    {{-- el cuerpo --}}
    <div class="hero-cinema__body container-tech relative">
        <div class="hero-cinema__stage" data-hero-item>
            <p class="hero-cinema__eyebrow">
                <span class="hero-cinema__eyebrow-tick" aria-hidden="true"></span>
                Farmacéutica · Biotecnología · Contención
            </p>

            <h1 id="hero-heading" class="hero-cinema__title">
                <span class="hero-cinema__title-row">
                    <span class="hero-cinema__title-word">Umbrella</span>
                </span>
                <span class="hero-cinema__title-row hero-cinema__title-row--alt">
                    <span class="hero-cinema__title-word hero-cinema__title-word--accent">Corporation</span>
                </span>
            </h1>

            <p class="hero-cinema__tagline">
                <span class="hero-cinema__quote">&ldquo;Nuestro negocio es la vida misma.&rdquo;</span>
                Inventario interno de bioagentes clasificados, equipo de contención y activos de simulación biológica &mdash; restringido a personal autorizado.
            </p>

            <div class="hero-cinema__cta">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <x-tabler-grid-dots class="size-4" aria-hidden="true" />
                    Ingresar a Bioagentes
                </a>
                <a href="{{ route('about') }}" class="btn btn-secondary">
                    <x-tabler-building class="size-4" aria-hidden="true" />
                    La Corporación
                </a>
            </div>
        </div>
    </div>

</section>

{{-- categorias --}}
<section class="section-shell categories-section py-20" aria-labelledby="categories-heading">
    <div class="container-tech">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-12">
            <div class="section-heading">
                <span class="section-heading-eyebrow categories-section__eyebrow" data-animate="fade-up">Mapa de Inventario</span>
                <h2 id="categories-heading" class="categories-section__title" data-animate="fade-up">Categorías Clasificadas</h2>
                <p class="categories-section__desc text-base max-w-xl" data-animate="fade-up">
                    Cuatro pilares del inventario dirigen los ítems narrativos clasificados hacia su archivo de destino.
                </p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-ghost categories-section__btn self-start lg:self-end">
                <x-tabler-arrow-up-right class="size-4" aria-hidden="true" />
                Explorar Bioagentes
            </a>
        </div>

        @php $totalCats = str_pad((string) count($categories), 2, '0', STR_PAD_LEFT); @endphp
        <ul class="grid gap-6 lg:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4" data-stagger>
            @foreach ($categories as $index => $category)
                <li>
                    <a
                        href="{{ route('products.index') }}#{{ $category['slug'] }}"
                        class="category-card"
                        data-stagger-item
                    >
                        <div class="category-card__top">
                            <span class="category-card__icon">
                                <x-dynamic-component
                                    :component="'tabler-' . $category['icon']"
                                    class="size-5"
                                    aria-hidden="true"
                                />
                            </span>
                            <span class="category-card__num">
                                {{ str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) }} / {{ $totalCats }}
                            </span>
                        </div>

                        <div class="category-card__body">
                            <h3 class="category-card__title">{{ $category['name'] }}</h3>
                            <p class="category-card__desc">{{ $category['description'] }}</p>
                        </div>

                        <span class="category-card__cta">
                            EXPLORAR
                            <x-tabler-chevron-right class="size-3.5" aria-hidden="true" />
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- destacados, el bento --}}
<section class="section-shell pb-24" aria-labelledby="featured-heading">
    <div class="container-tech">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-10">
            <div class="section-heading">
                <span class="section-heading-eyebrow" data-animate="fade-up">Inventario Seleccionado</span>
                <h2 id="featured-heading" data-animate="fade-up">Ítems Destacados</h2>
                <p class="text-[#9CACAD] max-w-xl" data-animate="fade-up">
                    Artefactos destacados del archivo activo. Todas las entradas son activos de simulación ficticios.
                </p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary self-start lg:self-end">
                <x-tabler-grid-dots class="size-4" aria-hidden="true" />
                Ver Todos los Ítems
            </a>
        </div>

        @php
            $heroProduct = $featured->first();
            $bentoProducts = $featured->slice(1, 3)->values();
        @endphp

        <div class="bento-grid" data-stagger>
            @if ($heroProduct)
                <div class="bento-hero">
                    @include('partials.product-card', ['product' => $heroProduct])
                </div>
            @endif

            @foreach ($bentoProducts as $bentoIndex => $product)
                <div class="bento-cell {{ $bentoIndex === 0 ? 'bento-cell-tall' : '' }}">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- el banner de acceso --}}
<section class="section-shell access-section py-20" aria-labelledby="clearance-heading">
    <div class="container-tech grid gap-10 lg:grid-cols-12 items-center">
        <div class="lg:col-span-7 flex flex-col gap-5">
            <span class="section-heading-eyebrow access-section__eyebrow" data-animate="fade-up">Control de Acceso</span>
            <h2 id="clearance-heading" class="access-section__title" data-animate="fade-up">Se Requiere Autorización de Seguridad</h2>
            <p class="access-section__desc max-w-xl" data-animate="fade-up">
                Cada transacción está sujeta a revisión manual. Los ítems por encima del nivel cuatro de autorización requieren aprobación ejecutiva y trazabilidad completa en el archivo.
            </p>
            <div class="flex flex-col sm:flex-row gap-3" data-animate="fade-up">
                <a href="{{ route('contact') }}" class="btn btn-primary">
                    <x-tabler-id-badge-2 class="size-4" aria-hidden="true" />
                    Solicitar Acceso
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary access-section__btn-secondary">
                    <x-tabler-grid-dots class="size-4" aria-hidden="true" />
                    Ver Bioagentes
                </a>
            </div>
        </div>

        <div class="lg:col-span-5 grid gap-3" data-animate="panel">
            @php
                $teaserLevels = [
                    ['code' => '03', 'name' => 'NOMINAL', 'badge' => 'badge-nominal', 'icon' => 'shield-check'],
                    ['code' => '04', 'name' => 'RESTRINGIDO', 'badge' => 'badge-restricted', 'icon' => 'lock'],
                    ['code' => '05', 'name' => 'CRÍTICO', 'badge' => 'badge-critical', 'icon' => 'alert-triangle'],
                    ['code' => '05', 'name' => 'CLASIFICADO', 'badge' => 'badge-classified', 'icon' => 'eye'],
                ];
            @endphp
            @foreach ($teaserLevels as $teaser)
                <div class="access-section__row">
                    <span class="flex items-center gap-3">
                        <span class="font-classified text-[0.7rem] tracking-[0.28em] access-section__row-code">LV {{ $teaser['code'] }}</span>
                        <span class="font-display text-[0.85rem] tracking-[0.2em] access-section__row-name">{{ $teaser['name'] }}</span>
                    </span>
                    <span class="badge {{ $teaser['badge'] }}">
                        <x-dynamic-component
                            :component="'tabler-' . $teaser['icon']"
                            class="size-3.5"
                            aria-hidden="true"
                        />
                        {{ $teaser['name'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- registros --}}
<section class="section-shell logs-section py-20 bg-[#0A0A0A] border-y border-[#5D6E6E]/20" aria-labelledby="logs-heading">
    <div class="container-tech">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-10">
            <div class="section-heading">
                <span class="section-heading-eyebrow" data-animate="fade-up">Comunicaciones Internas</span>
                <h2 id="logs-heading" data-animate="fade-up">Registros de Investigación</h2>
                <p class="text-[#9CACAD] max-w-xl" data-animate="fade-up">
                    Comunicaciones clasificadas recientes, actualizaciones de contención y notas del archivo narrativo.
                </p>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-secondary self-start lg:self-end">
                <x-tabler-archive class="size-4" aria-hidden="true" />
                Abrir Archivo
            </a>
        </div>

        <div class="grid gap-5 md:grid-cols-3" data-stagger>
            @foreach ($latestPosts as $post)
                @include('partials.blog-card', ['post' => $post])
            @endforeach
        </div>
    </div>
</section>

{{-- cta del final --}}
<section class="section-shell py-20" aria-labelledby="final-cta-heading">
    <div class="container-tech">
        <div class="technical-panel relative overflow-hidden p-10 lg:p-14">
            <div class="grid gap-10 lg:grid-cols-12 items-center relative z-10">
                <div class="lg:col-span-7 flex flex-col gap-5">
                    <span class="section-heading-eyebrow" data-animate="fade-up">Aviso Final</span>
                    <h2 id="final-cta-heading" class="text-[clamp(1.4rem,1.5vw+0.9rem,2rem)] leading-[1.2]" data-animate="fade-up">Autorización requerida.</h2>
                    <p class="text-[#9CACAD] max-w-xl" data-animate="fade-up">
                        Envíe una solicitud de acceso para desbloquear el inventario autorizado. La revisión manual es obligatoria para todo material crítico o clasificado.
                    </p>
                </div>
                <div class="lg:col-span-5 flex flex-col items-stretch gap-3" data-animate="panel">
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-block">
                        <x-tabler-fingerprint class="size-4" aria-hidden="true" />
                        Enviar Solicitud de Acceso
                    </a>
                    <a href="{{ route('cart') }}" class="btn btn-secondary btn-block">
                        <x-tabler-shopping-cart class="size-4" aria-hidden="true" />
                        Revisar Carrito Actual
                    </a>
                    <p class="font-classified text-[0.7rem] tracking-[0.28em] text-[#5D6E6E] text-center mt-2">
                        Revisión Manual · Solo Uso Interno
                    </p>
                </div>
            </div>

            <div class="absolute -right-24 -top-24 opacity-[0.04]" aria-hidden="true">
                @include('partials.umbrella-mark', ['size' => 320])
            </div>
        </div>
    </div>
</section>

@endsection

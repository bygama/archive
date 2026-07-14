@extends('layouts.app')

@section('title', 'Bioagentes Clasificados')
@section('description', 'Inventario privado para personal autorizado de Umbrella. Todos los ítems son activos de simulación ficticios, réplicas o documentos de investigación clasificados.')

@section('content')
<section class="section-shell catalog-hero pt-12 pb-12" aria-labelledby="catalog-heading">
    <div class="catalog-hero__bg" aria-hidden="true">
        <img
            src="{{ asset('images/hero/umbrella-storage.webp') }}"
            alt=""
            loading="eager"
            fetchpriority="high"
            decoding="async"
        />
        <div class="catalog-hero__bg-veil"></div>
        <div class="catalog-hero__bg-grid"></div>
    </div>

    <div class="container-tech relative">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Bioagentes'],
        ]])

        <div class="catalog-hero__grid">
            {{-- columna del texto --}}
            <div class="catalog-hero__copy">
                <span class="catalog-hero__eyebrow" data-animate="fade-up">Inventario Biológico</span>
                <h1 id="catalog-heading" class="catalog-hero__title" data-animate="fade-up">
                    Bioagentes
                </h1>
                <p class="catalog-hero__desc" data-animate="fade-up">
                    Inventario privado para personal autorizado. Todos los ítems son activos de simulación ficticios, réplicas o documentos clasificados del archivo narrativo de Umbrella Corporation.
                </p>
            </div>

            {{-- la consola de diagnostico --}}
            <aside class="catalog-console" data-animate="panel" aria-label="Estado del archivo">
                <span class="corner-mark tl" aria-hidden="true"></span>
                <span class="corner-mark tr" aria-hidden="true"></span>
                <span class="corner-mark bl" aria-hidden="true"></span>
                <span class="corner-mark br" aria-hidden="true"></span>

                <header class="catalog-console__head">
                    <span class="catalog-console__label">
                        <x-tabler-biohazard class="size-3.5 text-[#ED1C24]" aria-hidden="true" />
                        DIAGNÓSTICO BIOLÓGICO
                    </span>
                    <span class="catalog-console__status">
                        <span class="status-dot status-dot-nominal" aria-hidden="true"></span>
                        EN VIVO
                    </span>
                </header>

                <dl class="catalog-console__rows">
                    <div class="catalog-console__row">
                        <dt>Especímenes</dt>
                        <dd>
                            <span class="catalog-console__value">{{ str_pad((string) $totalAll, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="catalog-console__unit">ACTIVOS</span>
                        </dd>
                    </div>
                    <div class="catalog-console__row">
                        <dt>Categorías</dt>
                        <dd>
                            <span class="catalog-console__value">{{ str_pad((string) count($categories), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="catalog-console__unit">PILARES</span>
                        </dd>
                    </div>
                    <div class="catalog-console__row">
                        <dt>Contención</dt>
                        <dd>
                            <span class="catalog-console__value catalog-console__value--accent">SELLADA</span>
                        </dd>
                    </div>
                    <div class="catalog-console__row">
                        <dt>Última revisión</dt>
                        <dd>
                            <span class="catalog-console__value catalog-console__value--mono">{{ now()->format('Y-m-d') }}</span>
                        </dd>
                    </div>
                </dl>

                <footer class="catalog-console__foot">
                    <div class="barcode-line">
                        @php $bars = [3, 1, 2, 1, 4, 2, 1, 3, 1, 4, 2, 3, 1, 2, 4, 1, 2, 3]; @endphp
                        @foreach ($bars as $weight)
                            <span style="width: {{ $weight }}px;"></span>
                        @endforeach
                    </div>
                    <span class="catalog-console__node">UC-1968-A</span>
                </footer>
            </aside>
        </div>

        <span class="hairline-red block mt-10" data-animate="line"></span>
    </div>
</section>

<section class="section-shell pt-2 pb-24">
    <div class="container-tech flex flex-col gap-10">
        @include('partials.flash')

        {{-- filtros --}}
        <form
            method="GET"
            action="{{ route('products.index') }}"
            class="catalog-filters"
            aria-label="Filtros de bioagentes"
            data-animate="fade-up"
        >
            <div class="catalog-filters__row">
                <div class="catalog-filters__field">
                    <label for="categoria" class="catalog-filters__label">CATEGORÍA</label>
                    <div class="catalog-filters__select-wrap">
                        <select
                            id="categoria"
                            name="categoria"
                            class="catalog-filters__select"
                            onchange="this.form.submit()"
                        >
                            <option value="">Todas las categorías</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-tabler-chevron-down class="catalog-filters__chevron size-4" aria-hidden="true" />
                    </div>
                </div>

                <div class="catalog-filters__field">
                    <label for="autorizacion" class="catalog-filters__label">AUTORIZACIÓN</label>
                    <div class="catalog-filters__select-wrap">
                        <select
                            id="autorizacion"
                            name="autorizacion"
                            class="catalog-filters__select"
                            onchange="this.form.submit()"
                        >
                            <option value="">Todas las autorizaciones</option>
                            @foreach ($clearanceFilters as $filter)
                                <option value="{{ $filter['key'] }}" @selected($selectedClearance === $filter['key'])>
                                    {{ $filter['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <x-tabler-chevron-down class="catalog-filters__chevron size-4" aria-hidden="true" />
                    </div>
                </div>

                <div class="catalog-filters__actions">
                    <noscript>
                        <button type="submit" class="btn btn-primary text-[0.72rem]">
                            <x-tabler-filter class="size-4" aria-hidden="true" />
                            Aplicar
                        </button>
                    </noscript>
                    @if ($selectedCategory !== '' || $selectedClearance !== '')
                        <a href="{{ route('products.index') }}" class="btn btn-ghost text-[0.72rem]">
                            <x-tabler-x class="size-3.5" aria-hidden="true" />
                            Limpiar filtros
                        </a>
                    @endif
                </div>
            </div>

            <p class="catalog-filters__count" aria-live="polite">
                Mostrando <strong>{{ count($products) }}</strong> de <strong>{{ $totalAll }}</strong> ítems
            </p>
        </form>

        {{-- la grilla de items --}}
        @if (count($products) > 0)
            <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3" data-stagger>
                @foreach ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @else
            <div class="catalog-empty" role="status">
                <x-tabler-search-off class="size-10 text-[#5D6E6E]" aria-hidden="true" />
                <p class="catalog-empty__title">SIN RESULTADOS</p>
                <p class="catalog-empty__desc">
                    No se encontraron ítems con los filtros seleccionados.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-secondary text-[0.72rem]">
                    <x-tabler-refresh class="size-3.5" aria-hidden="true" />
                    Restablecer filtros
                </a>
            </div>
        @endif

        <p class="font-classified text-[0.7rem] tracking-[0.28em] text-[#5D6E6E] text-center" data-animate="fade-up">
            FIN DEL INVENTARIO BIOLÓGICO · CONTENCIÓN ACTIVA
        </p>
    </div>
</section>
@endsection

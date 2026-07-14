@props([
    'product',
])

@php
    $riskPct = 0;
    if (! empty($product['risk_index']) && preg_match('/(\d+)\s*\/\s*(\d+)/', $product['risk_index'], $m)) {
        $riskPct = (int) round(((int) $m[1] / max(1, (int) $m[2])) * 100);
    }
    $isCritical = ($product['status_key'] ?? '') === 'critical';
    $categoryIcon = $product->category?->icon ?? 'biohazard';
    $categoryName = $product->category?->name ?? '';
    $categorySlug = $product->category?->slug ?? '';
@endphp

<article
    class="specimen-card"
    data-card-hover
    data-stagger-item
    data-filter-item
    data-filter-category="{{ $categorySlug }}"
    data-filter-clearance="{{ $product['clearance_key'] }}"
>
    {{-- la camara --}}
    <div class="specimen-chamber">
        <div class="specimen-chamber__hex" aria-hidden="true"></div>
        <div class="specimen-chamber__rib specimen-chamber__rib--left" aria-hidden="true"></div>
        <div class="specimen-chamber__rib specimen-chamber__rib--right" aria-hidden="true"></div>
        <div class="specimen-chamber__glass" aria-hidden="true"></div>
        <div class="specimen-chamber__scan" aria-hidden="true"></div>

        <span class="corner-mark tl" aria-hidden="true"></span>
        <span class="corner-mark tr" aria-hidden="true"></span>
        <span class="corner-mark bl" aria-hidden="true"></span>
        <span class="corner-mark br" aria-hidden="true"></span>

        {{-- la tapa de arriba --}}
        <div class="specimen-cap">
            <span class="specimen-cap__id">
                <x-dynamic-component
                    :component="'tabler-' . $categoryIcon"
                    class="size-3.5 text-[#ED1C24]"
                    aria-hidden="true"
                />
                {{ $product['id_code'] }}
            </span>
            @include('partials.security-badge', ['level' => $product['status']])
        </div>

        {{-- la imagen --}}
        <figure class="specimen-chamber__figure" aria-hidden="true">
            <span class="specimen-chamber__halo"></span>
            @if (! empty($product['image']))
                <img
                    src="{{ asset($product['image']) }}"
                    alt=""
                    class="specimen-chamber__image"
                    loading="lazy"
                    decoding="async"
                />
            @else
                <x-dynamic-component
                    :component="'tabler-' . ($product['icon'] ?? 'flask')"
                    class="size-24 text-[#ED1C24] opacity-90"
                />
            @endif
        </figure>

        {{-- datos de lectura --}}
        <div class="specimen-readout">
            <div class="specimen-readout__col">
                <span class="specimen-readout__label">INSTALACIÓN</span>
                <span class="specimen-readout__value">{{ $product['facility'] }}</span>
            </div>
            <div class="specimen-readout__col specimen-readout__col--right">
                <span class="specimen-readout__label">TEMP</span>
                <span class="specimen-readout__value">
                    <span class="specimen-readout__dot" aria-hidden="true"></span>
                    -196°C
                </span>
            </div>
        </div>

        {{-- barra de riesgo --}}
        <div class="specimen-risk" aria-label="Índice de riesgo {{ $riskPct }} por ciento">
            <span class="specimen-risk__label">RIESGO</span>
            <span class="specimen-risk__track" aria-hidden="true">
                <span class="specimen-risk__fill" style="width: {{ $riskPct }}%"></span>
            </span>
            <span class="specimen-risk__value">{{ $product['risk_index'] ?? '0' }}</span>
        </div>
    </div>

    {{-- los datos del item --}}
    <div class="specimen-card__meta">
        <div class="flex items-center justify-between gap-3">
            <span class="font-classified text-[0.62rem] tracking-[0.32em] text-[#9CACAD]">{{ strtoupper($categoryName) }}</span>
            <span class="font-classified text-[0.62rem] tracking-[0.32em] text-[#ED1C24]">{{ strtoupper($product['clearance']) }}</span>
        </div>

        <h3 class="specimen-card__title">
            <a href="{{ route('products.show', $product['slug']) }}">
                {{ $product['name'] }}
            </a>
        </h3>

        <p class="specimen-card__desc">{{ $product['description'] }}</p>

        <div class="specimen-card__price">
            <div>
                <p class="specimen-card__price-label">PRECIO UNIT.</p>
                <p class="specimen-card__price-value">${{ number_format($product['price'], 0, ',', '.') }}</p>
            </div>
            <a
                href="{{ route('products.show', $product['slug']) }}"
                class="btn btn-secondary text-[0.7rem] py-2 px-3 whitespace-nowrap"
                aria-label="Ver detalle de {{ $product['name'] }}"
            >
                <x-tabler-arrow-up-right class="size-3.5" aria-hidden="true" />
                Detalle
            </a>
        </div>

        <form method="POST" action="{{ route('cart.add', $product) }}">
            @csrf
            <button
                type="submit"
                class="btn btn-restricted btn-block text-[0.7rem]"
                aria-label="Agregar {{ $product['name'] }} al carrito"
            >
                <x-tabler-shopping-cart class="size-3.5" aria-hidden="true" />
                Agregar al Carrito
            </button>
        </form>
    </div>
</article>

@extends('layouts.app')

@section('title', 'Registros de Investigación')
@section('description', 'Comunicaciones internas, actualizaciones de contención y notas de investigación clasificadas de las instalaciones de Umbrella.')

@section('content')
<section class="section-shell catalog-hero pt-12 pb-12" aria-labelledby="logs-heading">
    <div class="catalog-hero__bg" aria-hidden="true">
        <img
            src="{{ asset('images/hero/umbrella-archives.webp') }}"
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
            ['label' => 'Registros de Investigación'],
        ]])

        <div class="catalog-hero__grid">
            {{-- columna del texto --}}
            <div class="catalog-hero__copy">
                <span class="catalog-hero__eyebrow" data-animate="fade-up">Canal Restringido</span>
                <h1 id="logs-heading" class="catalog-hero__title" data-animate="fade-up">
                    Bitácora
                </h1>
                <p class="catalog-hero__desc" data-animate="fade-up">
                    Comunicaciones internas, actualizaciones de contención y notas de investigación clasificadas. Los documentos se presentan como material de archivo narrativo.
                </p>
            </div>

            {{-- el monitor de transmision --}}
            <aside class="catalog-console" data-animate="panel" aria-label="Monitor de transmisión">
                <span class="corner-mark tl" aria-hidden="true"></span>
                <span class="corner-mark tr" aria-hidden="true"></span>
                <span class="corner-mark bl" aria-hidden="true"></span>
                <span class="corner-mark br" aria-hidden="true"></span>

                <header class="catalog-console__head">
                    <span class="catalog-console__label">
                        <x-tabler-broadcast class="size-3.5 text-[#ED1C24]" aria-hidden="true" />
                        MONITOR DE TRANSMISIÓN
                    </span>
                    <span class="catalog-console__status">
                        <span class="signal-bars" aria-hidden="true">
                            <span></span><span></span><span></span><span></span>
                        </span>
                        EN VIVO
                    </span>
                </header>

                <dl class="catalog-console__rows">
                    <div class="catalog-console__row">
                        <dt>Entradas</dt>
                        <dd>
                            <span class="catalog-console__value">{{ str_pad((string) count($posts), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="catalog-console__unit">ARCHIVOS</span>
                        </dd>
                    </div>
                    <div class="catalog-console__row">
                        <dt>Canal</dt>
                        <dd>
                            <span class="catalog-console__value catalog-console__value--mono">109.7&nbsp;MHz</span>
                        </dd>
                    </div>
                    <div class="catalog-console__row">
                        <dt>Cifrado</dt>
                        <dd>
                            <span class="catalog-console__value catalog-console__value--accent">AES-256</span>
                        </dd>
                    </div>
                    <div class="catalog-console__row">
                        <dt>Última señal</dt>
                        <dd>
                            <span class="catalog-console__value catalog-console__value--mono">{{ now()->format('H:i:s') }}</span>
                        </dd>
                    </div>
                </dl>

                <footer class="catalog-console__foot">
                    <span class="waveform" aria-hidden="true">
                        @for ($i = 0; $i < 28; $i++)
                            <span style="--i:{{ $i }}"></span>
                        @endfor
                    </span>
                    <span class="catalog-console__node">UC-1968-A</span>
                </footer>
            </aside>
        </div>

        <span class="hairline-red block mt-10" data-animate="line"></span>
    </div>
</section>

<section class="section-shell pt-2 pb-24">
    <div class="container-tech grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-stagger>
        @foreach ($posts as $post)
            @include('partials.blog-card', ['post' => $post])
        @endforeach
    </div>
</section>
@endsection

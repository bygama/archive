@extends('layouts.app')

@section('title', 'Director Científico')
@section('description', 'Perfil clasificado del Director Científico de Umbrella Corporation — Mateo Spencer.')

@section('content')

@php
    $scientistPhotoExists = file_exists(public_path('images/team/mateo-spencer.webp'));
    $scientist2PhotoExists = file_exists(public_path('images/team/facundo-alvarez.webp'));
@endphp

{{-- el hero con el director cientifico --}}
<section class="section-shell catalog-hero pt-12 pb-16" aria-labelledby="scientist-heading">
    <div class="catalog-hero__bg" aria-hidden="true">
        <img
            src="{{ asset('images/hero/umbrella-about.webp') }}"
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
            ['label' => 'Acerca'],
        ]])

        <article class="scientist-card mt-8" data-animate="panel">
            {{-- a la izquierda la foto --}}
            <figure class="scientist-card__photo">
                <span class="corner-mark tl" aria-hidden="true"></span>
                <span class="corner-mark tr" aria-hidden="true"></span>
                <span class="corner-mark bl" aria-hidden="true"></span>
                <span class="corner-mark br" aria-hidden="true"></span>

                @if ($scientistPhotoExists)
                    <img
                        src="{{ asset('images/team/mateo-spencer.webp') }}"
                        alt="Mateo Spencer — Director Científico"
                        class="scientist-card__img"
                        loading="eager"
                        fetchpriority="high"
                        decoding="async"
                    />
                @else
                    <div class="scientist-card__placeholder" aria-hidden="true">
                        <x-tabler-user-circle class="size-20 text-[#5D6E6E]" />
                        <p class="font-classified text-[0.65rem] tracking-[0.32em] text-[#5D6E6E] mt-3 text-center">
                            ARCHIVO FOTOGRÁFICO<br />PENDIENTE
                        </p>
                    </div>
                @endif
            </figure>

            {{-- a la derecha la bio --}}
            <div class="scientist-card__bio">
                <div class="flex items-center justify-between gap-3">
                    <span class="font-classified text-[0.7rem] tracking-[0.32em] text-[#9CACAD]">DIVISIÓN&nbsp;RACCOON</span>
                    <span class="badge badge-executive">
                        <x-tabler-crown class="size-3.5" aria-hidden="true" />
                        BIOINGENIERO JEFE
                    </span>
                </div>

                <h1 id="scientist-heading" class="scientist-card__name">Mateo Spencer</h1>
                <p class="scientist-card__role">// DIRECTOR CIENTÍFICO · BIOINGENIERÍA</p>

                <p class="scientist-card__intro">
                    Tres décadas dirigiendo programas de selección biológica avanzada dentro de Umbrella Corporation. Responsable de la continuidad operativa entre los archivos clásicos y los nuevos pilares de investigación.
                </p>

                <p class="scientist-card__intro">
                    Bajo su supervisión se consolidaron los protocolos de contención que rigen actualmente todo el inventario biológico del archivo, incluyendo los desarrollos derivados del Progenitor.
                </p>

                <dl class="scientist-card__data">
                    <div>
                        <dt>ID&nbsp;PERSONAL</dt>
                        <dd>UC-1968-A</dd>
                    </div>
                    <div>
                        <dt>AUTORIZACIÓN</dt>
                        <dd class="text-[#ED1C24]">Nivel&nbsp;05</dd>
                    </div>
                    <div>
                        <dt>INSTALACIÓN</dt>
                        <dd>NEST · Arklay</dd>
                    </div>
                    <div>
                        <dt>ESTADO</dt>
                        <dd>
                            <span class="status-dot status-dot-nominal" aria-hidden="true"></span>
                            Activo
                        </dd>
                    </div>
                </dl>

                <blockquote class="scientist-card__quote">
                    <p>"La disciplina genera unidad. La unidad, poder. El poder es vida."</p>
                </blockquote>
            </div>
        </article>
    </div>
</section>

{{-- segundo cientifico: misma tarjeta pero al reves, la foto va a la derecha y el texto a la izquierda --}}
<section class="section-shell pt-2 pb-16" aria-labelledby="scientist-2-heading">
    <div class="container-tech">
        <article class="scientist-card scientist-card--reverse" data-animate="panel">
            {{-- a la izquierda la bio --}}
            <div class="scientist-card__bio">
                <div class="flex items-center justify-between gap-3">
                    <span class="font-classified text-[0.7rem] tracking-[0.32em] text-[#9CACAD]">DIVISIÓN&nbsp;RACCOON</span>
                    <span class="badge badge-executive">
                        <x-tabler-crown class="size-3.5" aria-hidden="true" />
                        BIOINGENIERO ADJUNTO
                    </span>
                </div>

                <h2 id="scientist-2-heading" class="scientist-card__name">Facundo Marcus</h2>
                <p class="scientist-card__role">// DIRECTOR CIENTÍFICO · BIOINGENIERÍA</p>

                <p class="scientist-card__intro">
                    Tres décadas dirigiendo programas de selección biológica avanzada dentro de Umbrella Corporation. Responsable de la continuidad operativa entre los archivos clásicos y los nuevos pilares de investigación.
                </p>

                <p class="scientist-card__intro">
                    Bajo su supervisión se consolidaron los protocolos de contención que rigen actualmente todo el inventario biológico del archivo, incluyendo los desarrollos derivados del Progenitor.
                </p>

                <dl class="scientist-card__data">
                    <div>
                        <dt>ID&nbsp;PERSONAL</dt>
                        <dd>UC-1968-B</dd>
                    </div>
                    <div>
                        <dt>AUTORIZACIÓN</dt>
                        <dd class="text-[#ED1C24]">Nivel&nbsp;05</dd>
                    </div>
                    <div>
                        <dt>INSTALACIÓN</dt>
                        <dd>NEST · Arklay</dd>
                    </div>
                    <div>
                        <dt>ESTADO</dt>
                        <dd>
                            <span class="status-dot status-dot-nominal" aria-hidden="true"></span>
                            Activo
                        </dd>
                    </div>
                </dl>

                <blockquote class="scientist-card__quote">
                    <p>"La disciplina genera unidad. La unidad, poder. El poder es vida."</p>
                </blockquote>
            </div>

            {{-- a la derecha la foto --}}
            <figure class="scientist-card__photo">
                <span class="corner-mark tl" aria-hidden="true"></span>
                <span class="corner-mark tr" aria-hidden="true"></span>
                <span class="corner-mark bl" aria-hidden="true"></span>
                <span class="corner-mark br" aria-hidden="true"></span>

                @if ($scientist2PhotoExists)
                    <img
                        src="{{ asset('images/team/facundo-alvarez.webp') }}"
                        alt="Facundo Marcus — Director Científico"
                        class="scientist-card__img"
                        loading="lazy"
                        decoding="async"
                    />
                @else
                    <div class="scientist-card__placeholder" aria-hidden="true">
                        <x-tabler-user-circle class="size-20 text-[#5D6E6E]" />
                        <p class="font-classified text-[0.65rem] tracking-[0.32em] text-[#5D6E6E] mt-3 text-center">
                            ARCHIVO FOTOGRÁFICO<br />PENDIENTE
                        </p>
                    </div>
                @endif
            </figure>
        </article>
    </div>
</section>

{{-- la corporacion: descripcion y las operaciones de ahora --}}
<section class="section-shell py-20 bg-[#0A0A0A] border-y border-[#5D6E6E]/20" aria-labelledby="overview-heading">
    <div class="container-tech grid gap-12 lg:grid-cols-12 items-start">
        <div class="lg:col-span-7 flex flex-col gap-5">
            <span class="section-heading-eyebrow" data-animate="fade-up">El Conglomerado</span>
            <h2 id="overview-heading" data-animate="fade-up">Bioingeniería bajo disciplina.</h2>
            <p class="text-[#9CACAD] text-base leading-relaxed max-w-2xl" data-animate="fade-up">
                Umbrella Corporation opera como un conglomerado farmacéutico privado dedicado a la bioingeniería, los sistemas de contención y el desarrollo de tecnología propietaria. Toda la operación está estructurada en torno a un único principio rector: el dominio absoluto sobre la materia viva.
            </p>
            <p class="text-[#9CACAD] text-base leading-relaxed max-w-2xl" data-animate="fade-up">
                Actualmente la División Raccoon mantiene activos los programas derivados del Progenitor, el archivo de Bio-Agentes Parasitarios y los pilares experimentales abiertos en la última década. Cada bioagente del archivo opera bajo supervisión ejecutiva directa y con contención sellada las veinticuatro horas.
            </p>
        </div>

        <aside class="catalog-console lg:col-span-5" data-animate="panel" aria-label="Operaciones actuales">
            <span class="corner-mark tl" aria-hidden="true"></span>
            <span class="corner-mark tr" aria-hidden="true"></span>
            <span class="corner-mark bl" aria-hidden="true"></span>
            <span class="corner-mark br" aria-hidden="true"></span>

            <header class="catalog-console__head">
                <span class="catalog-console__label">
                    <x-tabler-activity class="size-3.5 text-[#ED1C24]" aria-hidden="true" />
                    OPERACIONES ACTUALES
                </span>
                <span class="catalog-console__status">
                    <span class="status-dot status-dot-nominal" aria-hidden="true"></span>
                    EN VIVO
                </span>
            </header>

            <dl class="catalog-console__rows">
                <div class="catalog-console__row">
                    <dt>Fundación</dt>
                    <dd>
                        <span class="catalog-console__value">1968</span>
                        <span class="catalog-console__unit">{{ now()->year - 1968 }}&nbsp;AÑOS</span>
                    </dd>
                </div>
                <div class="catalog-console__row">
                    <dt>Sede</dt>
                    <dd>
                        <span class="catalog-console__value catalog-console__value--mono">RACCOON&nbsp;CITY</span>
                    </dd>
                </div>
                <div class="catalog-console__row">
                    <dt>Programas activos</dt>
                    <dd>
                        <span class="catalog-console__value">16</span>
                        <span class="catalog-console__unit">BIOAGENTES</span>
                    </dd>
                </div>
                <div class="catalog-console__row">
                    <dt>Carta corporativa</dt>
                    <dd>
                        <span class="catalog-console__value catalog-console__value--accent">REV.&nbsp;08</span>
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
</section>

{{-- la trayectoria --}}
<section class="section-shell timeline-section py-20" aria-labelledby="timeline-heading">
    <div class="container-tech grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-4 flex flex-col gap-4">
            <span class="section-heading-eyebrow timeline-section__eyebrow" data-animate="fade-up">Cronología Interna</span>
            <h2 id="timeline-heading" class="timeline-section__title" data-animate="fade-up">Hitos de Referencia</h2>
            <p class="timeline-section__desc max-w-md" data-animate="fade-up">
                Anclajes narrativos que marcan la trayectoria operativa de Umbrella Corporation y la continuidad del archivo.
            </p>
        </div>

        <ol class="lg:col-span-8 flex flex-col" data-animate-table>
            @foreach ($timeline as $entry)
                <li class="timeline-section__row" data-animate="table-row">
                    <span class="timeline-section__year">{{ $entry['year'] }}</span>
                    <div class="flex flex-col gap-1">
                        <span class="timeline-section__entry-title">{{ $entry['title'] }}</span>
                        <p class="timeline-section__entry-note">{{ $entry['note'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- los pilares operativos --}}
<section class="section-shell py-20" aria-labelledby="divisions-heading">
    <div class="container-tech">
        <div class="section-heading mb-10">
            <span class="section-heading-eyebrow" data-animate="fade-up">Estructura Interna</span>
            <h2 id="divisions-heading" data-animate="fade-up">Pilares Operativos</h2>
            <p class="text-[#9CACAD] max-w-xl" data-animate="fade-up">
                Cinco divisiones componen la espina dorsal de la corporación. Cada una refuerza la continuidad narrativa del archivo y enmarca un dominio operativo específico.
            </p>
        </div>

        <ul class="grid gap-5 md:grid-cols-2 lg:grid-cols-3" data-stagger>
            @foreach ($divisions as $division)
                <li class="card-technical h-full flex flex-col gap-4" data-stagger-item>
                    <span class="icon-frame icon-frame-lg">
                        <x-dynamic-component
                            :component="'tabler-' . $division['icon']"
                            class="size-6"
                            aria-hidden="true"
                        />
                    </span>
                    <h3 class="text-[1.05rem] tracking-[0.06em] text-[#FFFFFF]">{{ $division['name'] }}</h3>
                    <p class="text-sm text-[#9CACAD]">{{ $division['description'] }}</p>
                    <p class="font-classified text-[0.7rem] tracking-[0.28em] text-[#5D6E6E] mt-auto">DIVISIÓN ACTIVA</p>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endsection

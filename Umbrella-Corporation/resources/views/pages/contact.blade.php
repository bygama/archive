@extends('layouts.app')

@section('title', 'Solicitar Acceso')
@section('description', 'Envíe una solicitud de acceso para acceder al inventario clasificado del archivo de Umbrella Corporation.')

@section('content')

{{-- el hero y el documento comparten la imagen de fondo --}}
<section class="section-shell catalog-hero pt-12 pb-16" aria-labelledby="contact-heading">
    <div class="catalog-hero__bg" aria-hidden="true">
        <img
            src="{{ asset('images/hero/umbrella-access.webp') }}"
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
            ['label' => 'Solicitar Acceso'],
        ]])

        <div class="access-hero" data-animate="fade-up">
            <span class="access-hero__eyebrow">Control de Acceso · Protocolo Activo</span>
            <h1 id="contact-heading" class="access-hero__title">Solicitar<br />Acceso</h1>
            <p class="access-hero__desc">
                Envíe sus credenciales y el nivel de acceso solicitado. Toda solicitud queda registrada y auditada por la División de Asuntos Internos.
            </p>
        </div>

        <span class="hairline-red block mt-10 mb-10" data-animate="line"></span>

        @if (session('contact_status') === 'received')
            <div class="access-doc__alert access-doc__alert--success" role="status" data-animate="fade-up">
                <x-tabler-shield-check class="size-5" aria-hidden="true" />
                <p>
                    Solicitud recibida{{ session('contact_full_name') ? ', ' . session('contact_full_name') : '' }}. Tu credencial fue registrada en el archivo y será revisada por la División de Asuntos Internos.
                </p>
            </div>
        @endif

        @if ($errors->any())
            <div class="access-doc__alert access-doc__alert--error" role="alert" data-animate="fade-up">
                <x-tabler-alert-triangle class="size-5" aria-hidden="true" />
                <div>
                    <p class="font-classified tracking-[0.2em] uppercase text-xs mb-1">Solicitud rechazada — corregí los siguientes campos:</p>
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form
            action="{{ route('contact.submit') }}"
            method="POST"
            class="access-doc"
            aria-labelledby="contact-heading"
            data-animate="fade-up"
        >
            @csrf
            <span class="corner-mark tl" aria-hidden="true"></span>
            <span class="corner-mark tr" aria-hidden="true"></span>
            <span class="corner-mark bl" aria-hidden="true"></span>
            <span class="corner-mark br" aria-hidden="true"></span>

            {{-- encabezado del doc --}}
            <header class="access-doc__head">
                <div class="access-doc__head-left">
                    <span class="badge badge-classified">
                        <x-tabler-eye class="size-3.5" aria-hidden="true" />
                        CLASIFICADO
                    </span>
                    <span class="access-doc__head-id">UC-FORM-04 · v8</span>
                </div>
                <div class="access-doc__head-right">
                    <span class="status-dot" aria-hidden="true"></span>
                    EN GRABACIÓN
                </div>
            </header>

            {{-- seccion 01, identidad --}}
            <fieldset class="access-doc__section">
                <legend class="access-doc__legend">
                    <span class="access-doc__legend-num">01</span>
                    <span class="access-doc__legend-text">Identidad</span>
                    <span class="access-doc__legend-line" aria-hidden="true"></span>
                </legend>

                <div class="access-doc__grid">
                    <div class="flex flex-col">
                        <label for="full_name" class="input-label">Nombre Completo</label>
                        <input type="text" id="full_name" name="full_name" class="input-control @error('full_name') is-invalid @enderror" placeholder="J. Wesker" autocomplete="name" value="{{ old('full_name') }}" required />
                        @error('full_name') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col">
                        <label for="email" class="input-label">Correo Electrónico</label>
                        <input type="email" id="email" name="email" class="input-control @error('email') is-invalid @enderror" placeholder="agente@umbrella.corp" autocomplete="email" value="{{ old('email') }}" required />
                        @error('email') <p class="input-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- seccion 02, asignacion --}}
            <fieldset class="access-doc__section">
                <legend class="access-doc__legend">
                    <span class="access-doc__legend-num">02</span>
                    <span class="access-doc__legend-text">Asignación</span>
                    <span class="access-doc__legend-line" aria-hidden="true"></span>
                </legend>

                <div class="access-doc__grid">
                    <div class="flex flex-col">
                        <label for="department" class="input-label">Departamento</label>
                        <select id="department" name="department" class="input-control @error('department') is-invalid @enderror" required>
                            <option value="">Seleccione departamento</option>
                            @foreach (\App\Http\Requests\ContactRequest::DEPARTMENTS as $dept)
                                <option @selected(old('department') === $dept)>{{ $dept }}</option>
                            @endforeach
                        </select>
                        @error('department') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col">
                        <label for="clearance" class="input-label">Nivel Solicitado</label>
                        <select id="clearance" name="clearance" class="input-control @error('clearance') is-invalid @enderror" required>
                            <option value="">Seleccione nivel</option>
                            @foreach (\App\Http\Requests\ContactRequest::CLEARANCE_LEVELS as $level)
                                <option @selected(old('clearance') === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                        @error('clearance') <p class="input-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </fieldset>

            {{-- seccion 03, justificacion --}}
            <fieldset class="access-doc__section">
                <legend class="access-doc__legend">
                    <span class="access-doc__legend-num">03</span>
                    <span class="access-doc__legend-text">Justificación</span>
                    <span class="access-doc__legend-line" aria-hidden="true"></span>
                </legend>

                <div class="flex flex-col">
                    <label for="reason" class="input-label">Motivo del Acceso</label>
                    <textarea id="reason" name="reason" rows="5" class="input-control @error('reason') is-invalid @enderror" placeholder="Proporcione una justificación narrativa para el acceso al archivo. Solo contexto académico y ficticio.">{{ old('reason') }}</textarea>
                    <p class="input-helper">Todas las solicitudes se revisan manualmente. Evite información personal sensible.</p>
                    @error('reason') <p class="input-error">{{ $message }}</p> @enderror
                </div>

                <label for="agree" class="access-doc__agree">
                    <input type="checkbox" id="agree" name="agree" value="1" class="checkbox-control mt-1" {{ old('agree') ? 'checked' : '' }} required />
                    <span>
                        Acepto el protocolo interno de Umbrella y reconozco que esta solicitud forma parte de un proyecto académico ficticio de ecommerce.
                    </span>
                </label>
                @error('agree') <p class="input-error">{{ $message }}</p> @enderror
            </fieldset>

            {{-- pie del doc --}}
            <footer class="access-doc__foot">
                <div class="access-doc__stamp" aria-hidden="true">
                    <span class="access-doc__stamp-ring">
                        UMBRELLA · UMBRELLA · UMBRELLA · UMBRELLA ·
                    </span>
                    <x-tabler-fingerprint class="size-7 text-[#ED1C24]" />
                </div>

                <div class="access-doc__foot-right">
                    <button type="submit" class="btn btn-primary">
                        <x-tabler-fingerprint class="size-4" aria-hidden="true" />
                        Transmitir Solicitud
                    </button>
                    <button type="reset" class="btn btn-ghost">
                        <x-tabler-x class="size-4" aria-hidden="true" />
                        Limpiar
                    </button>
                </div>
            </footer>

            <p class="access-doc__disclaimer">
                Las solicitudes se validan en el servidor pero no se almacenan ni se reenvían. // FIN&nbsp;DEL&nbsp;DOCUMENTO
            </p>
        </form>
    </div>
</section>

{{-- protocolo, 3 paneles en fila --}}
<section class="section-shell pb-24" aria-labelledby="protocol-heading">
    <div class="container-tech">
        <div class="section-heading mb-8">
            <span class="section-heading-eyebrow" data-animate="fade-up">Protocolo de Solicitud</span>
            <h2 id="protocol-heading" data-animate="fade-up">Pasos de revisión</h2>
        </div>

        <div class="grid gap-5 md:grid-cols-3" data-stagger>
            <article class="protocol-step" data-stagger-item>
                <span class="protocol-step__num">01</span>
                <h3 class="protocol-step__title">Recepción</h3>
                <p class="protocol-step__desc">
                    Indique credenciales, departamento de registro y nivel de acceso solicitado.
                </p>
                <span class="protocol-step__meta">
                    <x-tabler-id-badge-2 class="size-3.5" aria-hidden="true" />
                    <span class="protocol-step__meta-text">INGRESO</span>
                </span>
            </article>

            <article class="protocol-step" data-stagger-item>
                <span class="protocol-step__num">02</span>
                <h3 class="protocol-step__title">Revisión Manual</h3>
                <p class="protocol-step__desc">
                    La División de Asuntos Internos audita la justificación narrativa y el nivel solicitado.
                </p>
                <span class="protocol-step__meta">
                    <x-tabler-eye class="size-3.5" aria-hidden="true" />
                    <span class="protocol-step__meta-text">AUDITORÍA</span>
                </span>
            </article>

            <article class="protocol-step" data-stagger-item>
                <span class="protocol-step__num">03</span>
                <h3 class="protocol-step__title">Confirmación</h3>
                <p class="protocol-step__desc">
                    El comité ejecutivo emite la autorización formal o solicita información adicional.
                </p>
                <span class="protocol-step__meta">
                    <x-tabler-shield-check class="size-3.5" aria-hidden="true" />
                    <span class="protocol-step__meta-text">EMISIÓN</span>
                </span>
            </article>
        </div>

        <p class="font-classified text-[0.7rem] tracking-[0.28em] text-[#5D6E6E] text-center" style="margin-top: 5rem;" data-animate="fade-up">
            CANAL INTERNO · UC-1968-A
        </p>
    </div>
</section>
@endsection

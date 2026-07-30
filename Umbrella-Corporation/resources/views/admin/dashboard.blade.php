@extends('layouts.admin')

@section('admin-title', 'Dashboard')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-head__eyebrow">Centro de Operaciones</span>
            <h1 class="admin-page-head__title">Dashboard</h1>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <x-tabler-plus class="size-4" aria-hidden="true" />
            Nueva Entrada
        </a>
    </div>

    @include('admin.partials.flash')

    {{-- los numeritos de arriba --}}
    <div class="admin-stat-grid">
        <article class="admin-stat">
            <div class="admin-stat__top">
                <span class="admin-stat__label">Entradas</span>
                <x-tabler-file-text class="size-5 text-[#ED1C24]" aria-hidden="true" />
            </div>
            <span class="admin-stat__value stat-counter">{{ $stats['posts'] }}</span>
            <span class="admin-stat__foot">Bitácora · Registros</span>
        </article>

        <article class="admin-stat">
            <div class="admin-stat__top">
                <span class="admin-stat__label">Personal</span>
                <x-tabler-users class="size-5 text-[#ED1C24]" aria-hidden="true" />
            </div>
            <span class="admin-stat__value stat-counter">{{ $stats['users'] }}</span>
            <span class="admin-stat__foot">Cuentas Activas</span>
        </article>

        <article class="admin-stat">
            <div class="admin-stat__top">
                <span class="admin-stat__label">Bioagentes</span>
                <x-tabler-virus class="size-5 text-[#ED1C24]" aria-hidden="true" />
            </div>
            <span class="admin-stat__value stat-counter">{{ $stats['products'] }}</span>
            <span class="admin-stat__foot">Inventario Clasificado</span>
        </article>

        <article class="admin-stat">
            <div class="admin-stat__top">
                <span class="admin-stat__label">Categorías</span>
                <x-tabler-category class="size-5 text-[#ED1C24]" aria-hidden="true" />
            </div>
            <span class="admin-stat__value stat-counter">{{ $stats['categories'] }}</span>
            <span class="admin-stat__foot">Clasificación Biológica</span>
        </article>

        <article class="admin-stat">
            <div class="admin-stat__top">
                <span class="admin-stat__label">Pedidos</span>
                <x-tabler-shopping-cart class="size-5 text-[#ED1C24]" aria-hidden="true" />
            </div>
            <span class="admin-stat__value stat-counter">{{ $stats['orders'] }}</span>
            <span class="admin-stat__foot">Facturado: $ {{ number_format($stats['revenue'], 0, ',', '.') }}</span>
        </article>

        <article class="admin-stat">
            <div class="admin-stat__top">
                <span class="admin-stat__label">Más Vendido</span>
                <x-tabler-trending-up class="size-5 text-[#ED1C24]" aria-hidden="true" />
            </div>
            @if ($topProduct)
                <span class="admin-stat__value stat-counter text-[1.1rem]">{{ $topProduct->name }}</span>
                <span class="admin-stat__foot">{{ $topProductQty }} unidades vendidas</span>
            @else
                <span class="admin-stat__value stat-counter text-[1.1rem]">—</span>
                <span class="admin-stat__foot">Sin pedidos todavía</span>
            @endif
        </article>
    </div>

    <div class="grid gap-5 lg:grid-cols-[1.6fr_1fr]">
        {{-- ultimas entradas --}}
        <section class="admin-panel">
            <header class="admin-panel__head">
                <h2 class="admin-panel__title">Últimas Entradas</h2>
                <a href="{{ route('admin.posts.index') }}" class="auth-card__link">Ver todas →</a>
            </header>
            <div class="admin-panel__body--flush">
                <div class="admin-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Doc ID</th>
                                <th scope="col">Título</th>
                                <th scope="col">Seguridad</th>
                                <th scope="col">Publicación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPosts as $post)
                                <tr>
                                    <td class="mono">{{ $post->document_id }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>@include('partials.security-badge', ['level' => $post->security])</td>
                                    <td class="mono">{{ $post->published_at?->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <p class="font-classified text-[0.7rem] tracking-[0.24em] uppercase text-[#5D6E6E] py-4 text-center">Sin entradas registradas</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- accesos rapidos --}}
        <section class="admin-panel">
            <header class="admin-panel__head">
                <h2 class="admin-panel__title">Accesos Rápidos</h2>
            </header>
            <div class="admin-panel__body flex flex-col gap-3">
                <a href="{{ route('admin.posts.create') }}" class="btn btn-secondary btn-block">
                    <x-tabler-square-plus class="size-4" aria-hidden="true" />
                    Crear Entrada de Bitácora
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-secondary btn-block">
                    <x-tabler-virus class="size-4" aria-hidden="true" />
                    Cargar Bioagente
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">
                    <x-tabler-users class="size-4" aria-hidden="true" />
                    Gestionar Personal
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-block">
                    <x-tabler-flask class="size-4" aria-hidden="true" />
                    Ver Catálogo Público
                </a>

                <div class="clearance-panel mt-2">
                    <div class="clearance-panel-header">
                        <span class="font-classified text-[0.62rem] tracking-[0.28em] uppercase">Estado del Sistema</span>
                        <span class="status-dot status-dot-nominal" aria-hidden="true"></span>
                    </div>
                    <p class="font-classified text-[0.72rem] tracking-[0.1em] leading-relaxed text-[#9CACAD]">
                        Todos los subsistemas operativos. Última sincronización del archivo NEST nominal.
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection

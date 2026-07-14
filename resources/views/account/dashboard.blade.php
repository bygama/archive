@extends('layouts.app')

@section('title', 'Mi Credencial')
@section('description', 'Panel personal de acceso: credencial y adquisiciones en el catálogo de Umbrella Corporation.')

@php
    // el usuario lo manda AccountController con las subscriptions ya cargadas
    $u = $user ?? auth()->user();
@endphp

@section('content')
<section class="section-shell catalog-hero pt-12 pb-16" aria-labelledby="account-heading">
    <div class="catalog-hero__bg" aria-hidden="true">
        <img src="{{ asset('images/hero/umbrella-access.webp') }}" alt="" loading="eager" fetchpriority="high" decoding="async" />
        <div class="catalog-hero__bg-veil"></div>
        <div class="catalog-hero__bg-grid"></div>
    </div>

    <div class="container-tech relative">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Mi Cuenta'],
        ]])

        <div class="access-hero" data-animate="fade-up">
            <span class="access-hero__eyebrow">Acceso de Personal · Sesión Activa</span>
            <h1 id="account-heading" class="access-hero__title">Mi<br />Credencial</h1>
            <p class="access-hero__desc">
                Bienvenido, {{ $u->name }}. Desde aquí podés consultar tu credencial y tus adquisiciones en el catálogo.
            </p>
        </div>

        <span class="hairline-red block mt-10 mb-10" data-animate="line"></span>

        <div class="grid gap-5 lg:grid-cols-[1fr_1.5fr]" data-animate="fade-up">
            {{-- credencial --}}
            <section class="technical-panel">
                <div class="flex items-center justify-between gap-3 mb-5">
                    <span class="badge badge-classified">
                        <x-tabler-id class="size-3.5" aria-hidden="true" />
                        Credencial
                    </span>
                    <span class="font-classified text-[0.6rem] tracking-[0.28em] uppercase text-[#5D6E6E]">UC-ID</span>
                </div>

                <div class="flex items-center gap-4 mb-5">
                    <span class="admin-user__avatar admin-user__avatar--lg" aria-hidden="true">
                        {{ strtoupper(mb_substr($u->name, 0, 1)) }}
                    </span>
                    <div>
                        <p class="text-white font-semibold">{{ $u->name }}</p>
                        <p class="font-classified text-sm text-[#9CACAD]">{{ $u->email }}</p>
                    </div>
                </div>

                <dl class="admin-deflist">
                    <div class="admin-deflist__row">
                        <dt class="admin-deflist__dt">Rol</dt>
                        <dd class="admin-deflist__dd">{{ $u->role === 'admin' ? 'Administrador' : 'Usuario común' }}</dd>
                    </div>
                    <div class="admin-deflist__row">
                        <dt class="admin-deflist__dt">Adquisiciones</dt>
                        <dd class="admin-deflist__dd font-classified">{{ $u->subscriptions->count() }}</dd>
                    </div>
                    <div class="admin-deflist__row">
                        <dt class="admin-deflist__dt">Estado</dt>
                        <dd class="admin-deflist__dd">
                            <span class="inline-flex items-center gap-2">
                                <span class="status-dot status-dot-nominal" aria-hidden="true"></span>
                                Activo
                            </span>
                        </dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('logout') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-block">
                        <x-tabler-logout class="size-4" aria-hidden="true" />
                        Cerrar Sesión
                    </button>
                </form>
            </section>

            {{-- adquisiciones --}}
            <section class="technical-panel">
                <div class="flex items-center justify-between gap-3 mb-5">
                    <h2 class="font-display text-[0.95rem] tracking-[0.2em] uppercase text-white">Adquisiciones</h2>
                    <span class="font-classified text-[0.6rem] tracking-[0.28em] uppercase text-[#5D6E6E]">
                        {{ $u->subscriptions->count() }} {{ $u->subscriptions->count() === 1 ? 'activo' : 'activos' }}
                    </span>
                </div>

                @if ($u->subscriptions->isNotEmpty())
                    <div class="admin-table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th scope="col">Activo</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col" class="text-right">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($u->subscriptions as $subscription)
                                    <tr>
                                        <td><span class="text-[#FFFFFF] font-semibold">{{ $subscription->product->name }}</span></td>
                                        <td>
                                            <span class="badge badge-nominal">
                                                <x-tabler-circle-check class="size-3.5" aria-hidden="true" />
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                        <td class="mono">{{ $subscription->contracted_at?->format('d/m/Y') }}</td>
                                        <td class="text-right mono text-white">$ {{ number_format($subscription->price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="catalog-empty">
                        <p class="catalog-empty__title">Sin adquisiciones</p>
                        <p class="catalog-empty__desc">Todavía no hiciste ninguna adquisición. Explorá el catálogo para conseguir tu primer activo.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <x-tabler-flask class="size-4" aria-hidden="true" />
                            Ver Catálogo
                        </a>
                    </div>
                @endif
            </section>
        </div>

        {{-- historial de pedidos --}}
        <section class="technical-panel mt-5" data-animate="fade-up">
            <div class="flex items-center justify-between gap-3 mb-5">
                <h2 class="font-display text-[0.95rem] tracking-[0.2em] uppercase text-white">Historial de Pedidos</h2>
                <span class="font-classified text-[0.6rem] tracking-[0.28em] uppercase text-[#5D6E6E]">
                    {{ $u->orders->count() }} {{ $u->orders->count() === 1 ? 'pedido' : 'pedidos' }}
                </span>
            </div>

            @if ($u->orders->isNotEmpty())
                <div class="admin-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Pedido</th>
                                <th scope="col">Ítems</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Fecha</th>
                                <th scope="col" class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($u->orders->sortByDesc('created_at') as $order)
                                <tr>
                                    <td class="mono text-[#FFFFFF]">#{{ $order->id }}</td>
                                    <td>{{ $order->items->pluck('product.name')->join(', ') }}</td>
                                    <td>
                                        @php
                                            $orderBadge = match ($order->status) {
                                                'pagado' => 'badge-nominal',
                                                'rechazado' => 'badge-critical',
                                                default => 'badge-standby',
                                            };
                                        @endphp
                                        <span class="badge {{ $orderBadge }}">
                                            <x-tabler-circle-check class="size-3.5" aria-hidden="true" />
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="mono">{{ $order->created_at?->format('d/m/Y') }}</td>
                                    <td class="text-right mono text-white">$ {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="catalog-empty">
                    <p class="catalog-empty__title">Sin pedidos</p>
                    <p class="catalog-empty__desc">Todavía no confirmaste ningún pedido. Agregá ítems al carrito para empezar.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <x-tabler-shopping-cart class="size-4" aria-hidden="true" />
                        Ir al Catálogo
                    </a>
                </div>
            @endif
        </section>
    </div>
</section>
@endsection

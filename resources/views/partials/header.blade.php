@php
    $navItems = [
        ['route' => 'home', 'label' => 'Inicio', 'icon' => 'home'],
        ['route' => 'products.index', 'label' => 'Bioagentes', 'icon' => 'grid-dots'],
        ['route' => 'blog.index', 'label' => 'Registros', 'icon' => 'file-text'],
        ['route' => 'about', 'label' => 'Acerca', 'icon' => 'building'],
        ['route' => 'contact', 'label' => 'Solicitar Acceso', 'icon' => 'id'],
    ];
@endphp

<header class="sticky top-0 z-40 border-b border-[#5D6E6E]/20 bg-[#050505]/85 backdrop-blur">
    <div class="container-tech">
        <div class="flex h-16 items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Umbrella Corporation — Inicio">
                @include('partials.umbrella-mark', ['size' => 36])
                <span class="hidden sm:flex flex-col leading-tight">
                    <span class="font-display text-[0.7rem] tracking-[0.32em] text-[#FFFFFF]">Umbrella</span>
                    <span class="font-classified text-[0.65rem] tracking-[0.28em] text-[#9CACAD]">CORPORATION</span>
                </span>
            </a>

            <nav class="hidden lg:block" aria-label="Navegación principal">
                <ul class="flex items-center gap-7 text-[0.72rem] font-semibold uppercase tracking-[0.22em] text-[#9CACAD]">
                    @foreach ($navItems as $item)
                        <li>
                            <a
                                href="{{ route($item['route']) }}"
                                @class([
                                    'transition-colors hover:text-[#FFFFFF]',
                                    'text-[#FFFFFF]' => request()->routeIs($item['route']),
                                ])
                            >
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="flex items-center gap-3">
                {{-- estado de la sesion. esto lo maneja el auth del backend --}}
                @auth
                    <div class="hidden md:flex items-center gap-2">
                        @if ((auth()->user()->role ?? null) === 'admin')
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="inline-flex items-center gap-2 border border-[#ED1C24]/50 px-3 py-2 text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-[#ED1C24] transition-colors hover:bg-[#ED1C24] hover:text-white"
                            >
                                <x-tabler-layout-dashboard class="size-4" aria-hidden="true" />
                                <span class="hidden lg:inline">Panel</span>
                            </a>
                        @endif
                        <a
                            href="{{ route('account.dashboard') }}"
                            class="inline-flex items-center gap-2 border border-[#5D6E6E]/40 px-3 py-2 text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-[#FFFFFF] transition-colors hover:border-[#ED1C24] hover:text-[#ED1C24]"
                        >
                            <x-tabler-user class="size-4" aria-hidden="true" />
                            <span class="hidden lg:inline">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 border border-[#5D6E6E]/40 px-3 py-2 text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-[#9CACAD] transition-colors hover:border-[#ED1C24] hover:text-[#ED1C24]"
                            >
                                <x-tabler-logout class="size-4" aria-hidden="true" />
                                <span class="hidden lg:inline">Salir</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="hidden md:flex items-center gap-2">
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 border border-[#5D6E6E]/40 px-3 py-2 text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-[#FFFFFF] transition-colors hover:border-[#ED1C24] hover:text-[#ED1C24]"
                        >
                            <x-tabler-login-2 class="size-4" aria-hidden="true" />
                            Acceder
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 bg-[#ED1C24] px-3 py-2 text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-white transition-colors hover:bg-[#830F14]"
                        >
                            <x-tabler-user-plus class="size-4" aria-hidden="true" />
                            <span class="hidden lg:inline">Registrarse</span>
                        </a>
                    </div>
                @endauth

                @php $cartCount = count(session('cart', [])); @endphp
                <a
                    href="{{ route('cart') }}"
                    class="inline-flex items-center gap-2 border border-[#5D6E6E]/40 px-3 py-2 text-[0.7rem] font-semibold tracking-[0.2em] uppercase text-[#FFFFFF] transition-colors hover:border-[#ED1C24] hover:text-[#ED1C24]"
                    aria-label="Carrito · {{ $cartCount }} ítems"
                >
                    <x-tabler-shopping-cart class="size-4" aria-hidden="true" />
                    <span class="hidden sm:inline">Carrito</span>
                    <span class="font-classified text-[0.65rem] tracking-[0.18em] text-[#ED1C24]">{{ str_pad((string) $cartCount, 2, '0', STR_PAD_LEFT) }}</span>
                </a>

                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center border border-[#5D6E6E]/40 text-[#FFFFFF] lg:hidden"
                    aria-controls="primary-mobile-nav"
                    aria-expanded="false"
                    aria-label="Alternar navegación"
                    data-nav-toggle
                >
                    <x-tabler-menu-2 class="size-5" data-nav-icon-open aria-hidden="true" />
                    <x-tabler-x class="size-5 hidden" data-nav-icon-close aria-hidden="true" />
                </button>
            </div>
        </div>
    </div>

    <div
        id="primary-mobile-nav"
        class="lg:hidden border-t border-[#5D6E6E]/20 bg-[#050505] data-[open=false]:hidden"
        data-nav-menu
        data-open="false"
    >
        <nav aria-label="Navegación móvil" class="container-tech py-6">
            <ul class="flex flex-col divide-y divide-[#5D6E6E]/15">
                @foreach ($navItems as $item)
                    <li>
                        <a
                            href="{{ route($item['route']) }}"
                            @class([
                                'flex items-center justify-between py-4 text-sm font-semibold uppercase tracking-[0.22em] text-[#9CACAD] transition-colors hover:text-[#FFFFFF]',
                                'text-[#FFFFFF]' => request()->routeIs($item['route']),
                            ])
                        >
                            <span class="inline-flex items-center gap-3">
                                <x-dynamic-component
                                    :component="'tabler-' . $item['icon']"
                                    class="size-4 text-[#ED1C24]"
                                    aria-hidden="true"
                                />
                                {{ $item['label'] }}
                            </span>
                            <x-tabler-chevron-right class="size-4" aria-hidden="true" />
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 flex flex-col gap-2.5">
                @auth
                    @if ((auth()->user()->role ?? null) === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-restricted btn-block">
                            <x-tabler-layout-dashboard class="size-4" aria-hidden="true" />
                            Panel de Administración
                        </a>
                    @endif
                    <a href="{{ route('account.dashboard') }}" class="btn btn-secondary btn-block">
                        <x-tabler-user class="size-4" aria-hidden="true" />
                        Mi Cuenta
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-block">
                            <x-tabler-logout class="size-4" aria-hidden="true" />
                            Cerrar Sesión
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-block">
                        <x-tabler-login-2 class="size-4" aria-hidden="true" />
                        Acceder
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-block">
                        <x-tabler-user-plus class="size-4" aria-hidden="true" />
                        Registrarse
                    </a>
                @endauth
            </div>

            <p class="mt-6 inline-flex items-center gap-2 font-classified text-[0.65rem] tracking-[0.28em] text-[#9CACAD]">
                <span class="status-dot status-dot-nominal" aria-hidden="true"></span>
                Sistema Nominal — Red Interna
            </p>
        </nav>
    </div>
</header>

@php
    // el usuario logueado, para el nombre y el rol de abajo
    $u = auth()->user();
    $isAdmin = $u->isAdmin();
@endphp

<aside id="admin-sidebar" class="admin-sidebar" data-admin-sidebar data-open="false" aria-label="Navegación del panel">
    <div class="admin-brand">
        @include('partials.umbrella-mark', ['size' => 34])
        <span class="admin-brand__text">
            <span class="admin-brand__name">Umbrella</span>
            <span class="admin-brand__sub">NEST Console</span>
        </span>
    </div>

    <nav class="admin-nav" aria-label="Secciones del panel">
        <span class="admin-nav__label">Operaciones</span>

        <a href="{{ route('admin.dashboard') }}" @class(['admin-nav__item', 'is-active' => request()->routeIs('admin.dashboard')])>
            <x-tabler-layout-dashboard class="size-[1.15rem]" aria-hidden="true" />
            Dashboard
        </a>

        <a href="{{ route('admin.posts.index') }}" @class(['admin-nav__item', 'is-active' => request()->routeIs('admin.posts.*')])>
            <x-tabler-file-text class="size-[1.15rem]" aria-hidden="true" />
            Bitácora
        </a>

        <a href="{{ route('admin.products.index') }}" @class(['admin-nav__item', 'is-active' => request()->routeIs('admin.products.*')])>
            <x-tabler-virus class="size-[1.15rem]" aria-hidden="true" />
            Catálogo
        </a>

        <a href="{{ route('admin.users.index') }}" @class(['admin-nav__item', 'is-active' => request()->routeIs('admin.users.*')])>
            <x-tabler-users class="size-[1.15rem]" aria-hidden="true" />
            Personal
        </a>

        <span class="admin-nav__label">Sistema</span>

        <a href="{{ route('home') }}" class="admin-nav__item">
            <x-tabler-arrow-back-up class="size-[1.15rem]" aria-hidden="true" />
            Volver al sitio
        </a>
    </nav>

    <div class="admin-sidebar__foot">
        <div class="admin-user">
            <span class="admin-user__avatar" aria-hidden="true">{{ strtoupper(mb_substr($u->name ?? 'U', 0, 1)) }}</span>
            <span class="admin-user__meta">
                <span class="admin-user__name">{{ $u->name ?? 'Operador' }}</span>
                <span class="admin-user__role">{{ $isAdmin ? 'Administrador' : 'Usuario' }}</span>
            </span>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost btn-block">
                <x-tabler-logout class="size-4" aria-hidden="true" />
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

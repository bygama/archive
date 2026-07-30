@extends('layouts.admin')

@section('admin-title', 'Personal')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-head__eyebrow">Directorio de Acceso</span>
            <h1 class="admin-page-head__title">Personal</h1>
        </div>
        <span class="badge badge-classified">
            <x-tabler-users class="size-3.5" aria-hidden="true" />
            {{ count($users) }} cuentas
        </span>
    </div>

    @include('admin.partials.flash')

    <section class="admin-panel">
        <header class="admin-panel__head">
            <h2 class="admin-panel__title">Cuentas Registradas</h2>
        </header>

        <div class="admin-panel__body--flush">
            <div class="admin-table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Adquisiciones</th>
                            <th scope="col">Alta</th>
                            <th scope="col" class="text-right">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td><span class="text-[#FFFFFF] font-semibold">{{ $user->name }}</span></td>
                                <td class="mono">{{ $user->email }}</td>
                                <td>
                                    @if ($user->role === 'admin')
                                        <span class="badge badge-executive">
                                            <x-tabler-crown class="size-3.5" aria-hidden="true" />
                                            Administrador
                                        </span>
                                    @else
                                        <span class="badge badge-standby">
                                            <x-tabler-user class="size-3.5" aria-hidden="true" />
                                            Usuario
                                        </span>
                                    @endif
                                </td>
                                <td class="mono">{{ $user->subscriptions_count }}</td>
                                <td class="mono">{{ $user->created_at?->format('d/m/Y') }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a
                                            href="{{ route('admin.users.show', $user) }}"
                                            class="table-action"
                                            title="Ver ficha"
                                            aria-label="Ver ficha de {{ $user->name }}"
                                        >
                                            <x-tabler-arrow-right class="size-4" aria-hidden="true" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

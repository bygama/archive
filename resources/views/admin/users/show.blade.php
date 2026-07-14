@extends('layouts.admin')

@section('admin-title', 'Ficha de Personal')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-head__eyebrow">Ficha de Personal</span>
            <h1 class="admin-page-head__title">{{ $user->name }}</h1>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">
            <x-tabler-arrow-left class="size-4" aria-hidden="true" />
            Volver al directorio
        </a>
    </div>

    <div class="grid gap-5 lg:grid-cols-[1fr_1.5fr]">
        {{-- datos de la cuenta --}}
        <section class="admin-panel">
            <header class="admin-panel__head">
                <h2 class="admin-panel__title">Credencial</h2>
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
            </header>
            <div class="admin-panel__body">
                <div class="flex items-center gap-4 mb-5">
                    <span class="admin-user__avatar admin-user__avatar--lg" aria-hidden="true">
                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                    </span>
                    <div>
                        <p class="text-white font-semibold">{{ $user->name }}</p>
                        <p class="mono text-sm text-[#9CACAD]">{{ $user->email }}</p>
                    </div>
                </div>

                <dl class="admin-deflist">
                    <div class="admin-deflist__row">
                        <dt class="admin-deflist__dt">Rol</dt>
                        <dd class="admin-deflist__dd">{{ $user->role === 'admin' ? 'Administrador' : 'Usuario común' }}</dd>
                    </div>
                    <div class="admin-deflist__row">
                        <dt class="admin-deflist__dt">Adquisiciones</dt>
                        <dd class="admin-deflist__dd font-classified">{{ $user->subscriptions->count() }}</dd>
                    </div>
                    <div class="admin-deflist__row">
                        <dt class="admin-deflist__dt">Alta en el sistema</dt>
                        <dd class="admin-deflist__dd font-classified">{{ $user->created_at?->format('d/m/Y') }}</dd>
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
            </div>
        </section>

        {{-- adquisiciones --}}
        <section class="admin-panel">
            <header class="admin-panel__head">
                <h2 class="admin-panel__title">Adquisiciones</h2>
                <span class="font-classified text-[0.62rem] tracking-[0.28em] uppercase text-[#5D6E6E]">
                    {{ $user->subscriptions->count() }} {{ $user->subscriptions->count() === 1 ? 'registro' : 'registros' }}
                </span>
            </header>

            <div class="admin-panel__body--flush">
                @if ($user->subscriptions->isNotEmpty())
                    <div class="admin-table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th scope="col">Activo</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col" class="text-right">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->subscriptions as $subscription)
                                    <tr>
                                        <td><span class="text-[#FFFFFF] font-semibold">{{ $subscription->product->name }}</span></td>
                                        <td class="mono">{{ $subscription->product->id_code }}</td>
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
                    <div class="admin-empty">
                        <x-tabler-package-off class="size-8 text-[#5D6E6E]" aria-hidden="true" />
                        <p class="admin-empty__title">Sin adquisiciones</p>
                        <p class="text-sm text-[#9CACAD]">Este usuario todavía no registra adquisiciones.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    {{-- historial de pedidos --}}
    <section class="admin-panel mt-5">
        <header class="admin-panel__head">
            <h2 class="admin-panel__title">Historial de Pedidos</h2>
            <span class="font-classified text-[0.62rem] tracking-[0.28em] uppercase text-[#5D6E6E]">
                {{ $user->orders->count() }} {{ $user->orders->count() === 1 ? 'pedido' : 'pedidos' }}
            </span>
        </header>

        <div class="admin-panel__body--flush">
            @if ($user->orders->isNotEmpty())
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
                            @foreach ($user->orders->sortByDesc('created_at') as $order)
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
                <div class="admin-empty">
                    <x-tabler-package-off class="size-8 text-[#5D6E6E]" aria-hidden="true" />
                    <p class="admin-empty__title">Sin pedidos</p>
                    <p class="text-sm text-[#9CACAD]">Este usuario todavía no registra pedidos.</p>
                </div>
            @endif
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Carrito')
@section('description', 'Carrito de adquisición del catálogo clasificado de Umbrella Corporation.')

@section('content')
<section class="section-shell pt-12 pb-12" aria-labelledby="cart-heading">
    <div class="container-tech">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Carrito'],
        ]])

        <div class="grid gap-10 lg:grid-cols-12 mt-8 items-end">
            <div class="lg:col-span-8 flex flex-col gap-5">
                <span class="section-heading-eyebrow" data-animate="fade-up">Inventario Actual</span>
                <h1 id="cart-heading" data-animate="fade-up">Carrito de Adquisición</h1>
                <p class="text-[#9CACAD] max-w-2xl" data-animate="fade-up">
                    Ítems reservados para tu próxima adquisición. Confirmá el pedido para registrarlo y continuar con el pago.
                </p>
            </div>

            <div class="lg:col-span-4" data-animate="panel">
                <div class="clearance-panel">
                    <div class="clearance-panel-header">
                        <span class="font-display text-[0.7rem] tracking-[0.3em] text-[#FFFFFF]">Estado del Carrito</span>
                        <x-tabler-shopping-cart class="size-4 text-[#ED1C24]" aria-hidden="true" />
                    </div>
                    <p class="font-display text-[#FFFFFF] text-2xl tracking-[0.18em]">{{ count($items) }} ÍTEMS</p>
                    <p class="font-classified text-[0.7rem] tracking-[0.24em] text-[#ED1C24] mt-2">
                        {{ count($items) > 0 ? 'LISTO PARA CONFIRMAR' : 'SIN ÍTEMS' }}
                    </p>
                </div>
            </div>
        </div>

        <span class="hairline-red block mt-12" data-animate="line"></span>

        <div class="mt-8">
            @include('partials.flash')
        </div>
    </div>
</section>

<section class="section-shell pt-2 pb-24">
    <div class="container-tech grid gap-10 lg:grid-cols-12 items-start">
        @if ($items->isEmpty())
            <div class="lg:col-span-12">
                <div class="catalog-empty">
                    <x-tabler-shopping-cart-off class="size-10 text-[#5D6E6E]" aria-hidden="true" />
                    <p class="catalog-empty__title">CARRITO VACÍO</p>
                    <p class="catalog-empty__desc">Todavía no agregaste ningún ítem del catálogo.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary text-[0.72rem]">
                        <x-tabler-flask class="size-3.5" aria-hidden="true" />
                        Ver Catálogo
                    </a>
                </div>
            </div>
        @else
            <div class="lg:col-span-8 flex flex-col gap-4" data-animate-table>
                @foreach ($items as $entry)
                    @php $item = $entry['product']; @endphp
                    <article class="grid gap-4 md:grid-cols-12 items-center border border-[#5D6E6E]/25 bg-[#0A0A0A] p-5 hover:border-[#ED1C24] transition-colors" data-animate="table-row">
                        <div class="md:col-span-2 min-w-0">
                            <figure class="cart-thumb">
                                <span class="cart-thumb-grid" aria-hidden="true"></span>
                                @if (! empty($item->image))
                                    <img
                                        src="{{ asset($item->image) }}"
                                        alt=""
                                        loading="lazy"
                                        decoding="async"
                                        class="cart-thumb-image"
                                    />
                                @else
                                    <x-dynamic-component
                                        :component="'tabler-' . ($item->icon ?? 'flask')"
                                        class="size-7 text-[#ED1C24]"
                                        aria-hidden="true"
                                    />
                                @endif
                            </figure>
                        </div>

                        <div class="md:col-span-4 min-w-0 flex flex-col gap-1.5">
                            <span class="font-classified text-[0.7rem] tracking-[0.24em] text-[#9CACAD]">{{ $item->id_code }}</span>
                            <h2 class="text-[0.95rem] tracking-[0.06em] text-[#FFFFFF] break-words">
                                <a href="{{ route('products.show', $item->slug) }}" class="transition-colors hover:text-[#ED1C24]">{{ $item->name }}</a>
                            </h2>
                            <div class="flex">
                                @include('partials.security-badge', ['level' => $item->status, 'class' => 'cart-item-badge'])
                            </div>
                        </div>

                        <div class="md:col-span-3 min-w-0">
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <label for="qty-{{ $item->id }}" class="sr-only">Cantidad de {{ $item->name }}</label>
                                <input
                                    type="number"
                                    id="qty-{{ $item->id }}"
                                    name="quantity"
                                    value="{{ $entry['qty'] }}"
                                    min="1"
                                    max="99"
                                    class="input-control w-16 text-center"
                                />
                                <button type="submit" class="btn btn-ghost text-[0.65rem] px-2 py-2" aria-label="Actualizar cantidad de {{ $item->name }}">
                                    <x-tabler-refresh class="size-3.5" aria-hidden="true" />
                                </button>
                            </form>
                            @error('quantity')
                                <p class="text-[#ED1C24] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 min-w-0 flex flex-col gap-1 md:text-right">
                            <span class="font-classified text-[0.65rem] tracking-[0.28em] text-[#5D6E6E]">SUBTOTAL</span>
                            <span class="font-display text-[#ED1C24] tracking-[0.16em] text-[0.95rem] whitespace-nowrap">${{ number_format($entry['subtotal'], 0, ',', '.') }}</span>
                        </div>

                        <div class="md:col-span-1 flex md:justify-end">
                            <form method="POST" action="{{ route('cart.remove', $item) }}" onsubmit="return confirm('¿Quitar {{ $item->name }} del carrito?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost text-[0.65rem] px-2 py-2" aria-label="Quitar {{ $item->name }} del carrito">
                                    <x-tabler-trash class="size-4 text-[#ED1C24]" aria-hidden="true" />
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="lg:col-span-4 flex flex-col gap-5" data-animate="panel">
                <div class="technical-panel">
                    <p class="font-classified text-[0.7rem] tracking-[0.3em] text-[#9CACAD]">Resumen del Pedido</p>
                    <dl class="mt-4 flex flex-col gap-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-[#9CACAD]">Ítems</dt>
                            <dd class="text-[#FFFFFF]">{{ count($items) }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-[#5D6E6E]/25 pt-3 mt-1">
                            <dt class="font-display text-[#FFFFFF] tracking-[0.2em]">Total</dt>
                            <dd class="font-display text-[#ED1C24] tracking-[0.18em] text-lg">${{ number_format($subtotal, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>

                @auth
                    <form method="POST" action="{{ route('checkout.store') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block">
                            <x-tabler-lock class="size-4" aria-hidden="true" />
                            Confirmar Pedido
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-block">
                        <x-tabler-fingerprint class="size-4" aria-hidden="true" />
                        Iniciar Sesión para Confirmar
                    </a>
                @endauth

                <div class="clearance-panel">
                    <div class="clearance-panel-header">
                        <span class="font-display text-[0.7rem] tracking-[0.3em] text-[#FFFFFF]">Aviso de Seguridad</span>
                        <x-tabler-shield-lock class="size-4 text-[#ED1C24]" aria-hidden="true" />
                    </div>
                    <p class="text-sm text-[#9CACAD]">
                        Al confirmar, el pedido queda registrado y serás redirigido a la pasarela de pago segura de MercadoPago.
                    </p>
                </div>
            </aside>
        @endif
    </div>
</section>
@endsection

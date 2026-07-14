@extends('layouts.app')

@section('title', 'Pago Rechazado')
@section('description', 'El pago del pedido no pudo procesarse.')

@section('content')
<section class="section-shell pt-16 pb-24" aria-labelledby="checkout-heading">
    <div class="container-tech">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Carrito', 'url' => route('cart')],
            ['label' => 'Pago Rechazado'],
        ]])

        <div class="max-w-2xl mt-10" data-animate="fade-up">
            <div class="clearance-panel">
                <div class="clearance-panel-header">
                    <span class="font-display text-[0.7rem] tracking-[0.3em] text-[#FFFFFF]">Pago Rechazado</span>
                    <x-tabler-alert-triangle class="size-5 text-[#ED1C24]" aria-hidden="true" />
                </div>
                <h1 id="checkout-heading" class="text-[clamp(1.5rem,2vw+1rem,2.2rem)] mt-2">No pudimos procesar el pago</h1>
                <p class="text-[#9CACAD] mt-3">
                    @if ($order)
                        El pedido #{{ $order->id }} quedó registrado pero el pago fue rechazado por MercadoPago. Podés intentar de nuevo desde tu carrito.
                    @else
                        El pago fue rechazado o cancelado. Podés intentar de nuevo desde tu carrito.
                    @endif
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                <a href="{{ route('cart') }}" class="btn btn-primary">
                    <x-tabler-shopping-cart class="size-4" aria-hidden="true" />
                    Volver al Carrito
                </a>
                <a href="{{ route('contact') }}" class="btn btn-ghost">
                    <x-tabler-fingerprint class="size-4" aria-hidden="true" />
                    Contactar Soporte
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

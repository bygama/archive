@extends('layouts.app')

@section('title', 'Pago Pendiente')
@section('description', 'El pago del pedido está pendiente de acreditación.')

@section('content')
<section class="section-shell pt-16 pb-24" aria-labelledby="checkout-heading">
    <div class="container-tech">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Carrito', 'url' => route('cart')],
            ['label' => 'Pago Pendiente'],
        ]])

        <div class="max-w-2xl mt-10" data-animate="fade-up">
            <div class="clearance-panel">
                <div class="clearance-panel-header">
                    <span class="font-display text-[0.7rem] tracking-[0.3em] text-[#FFFFFF]">Pago Pendiente</span>
                    <x-tabler-clock class="size-5 text-[#ED1C24]" aria-hidden="true" />
                </div>
                <h1 id="checkout-heading" class="text-[clamp(1.5rem,2vw+1rem,2.2rem)] mt-2">Tu pago está en proceso</h1>
                <p class="text-[#9CACAD] mt-3">
                    @if ($order)
                        El pedido #{{ $order->id }} quedó registrado. MercadoPago todavía está procesando el pago (por ejemplo, si elegiste Rapipago o Pago Fácil); en cuanto se acredite vas a ver el cambio de estado en tu perfil.
                    @else
                        MercadoPago todavía está procesando tu pago. En cuanto se acredite vas a ver el cambio de estado en tu perfil.
                    @endif
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                <a href="{{ route('account.dashboard') }}" class="btn btn-primary">
                    <x-tabler-id class="size-4" aria-hidden="true" />
                    Ver Mi Credencial
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <x-tabler-flask class="size-4" aria-hidden="true" />
                    Seguir Explorando
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

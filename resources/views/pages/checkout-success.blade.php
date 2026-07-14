@extends('layouts.app')

@section('title', 'Pedido Confirmado')
@section('description', 'Confirmación de pago del pedido en Umbrella Corporation.')

@section('content')
<section class="section-shell pt-16 pb-24" aria-labelledby="checkout-heading">
    <div class="container-tech">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Inicio', 'url' => route('home')],
            ['label' => 'Carrito', 'url' => route('cart')],
            ['label' => 'Pedido Confirmado'],
        ]])

        <div class="grid gap-10 lg:grid-cols-12 mt-10">
            <div class="lg:col-span-7 flex flex-col gap-6" data-animate="fade-up">
                <div class="clearance-panel">
                    <div class="clearance-panel-header">
                        <span class="font-display text-[0.7rem] tracking-[0.3em] text-[#FFFFFF]">Pago Aprobado</span>
                        <x-tabler-circle-check class="size-5 text-[#ED1C24]" aria-hidden="true" />
                    </div>
                    <h1 id="checkout-heading" class="text-[clamp(1.5rem,2vw+1rem,2.2rem)] mt-2">Pedido Registrado</h1>
                    <p class="text-[#9CACAD] mt-3">
                        Tu pedido quedó registrado en el sistema y el pago fue aprobado por MercadoPago.
                        @if ($paymentId)
                            ID de pago: <span class="font-classified text-[#FFFFFF]">{{ $paymentId }}</span>.
                        @endif
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
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

            <aside class="lg:col-span-5" data-animate="panel">
                @if ($order)
                    <div class="technical-panel">
                        <p class="font-classified text-[0.7rem] tracking-[0.3em] text-[#9CACAD]">Pedido #{{ $order->id }}</p>
                        <div class="admin-table-wrap mt-4">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Ítem</th>
                                        <th scope="col">Cant.</th>
                                        <th scope="col" class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $line)
                                        <tr>
                                            <td class="text-[#FFFFFF]">{{ $line->product->name }}</td>
                                            <td class="mono">{{ $line->quantity }}</td>
                                            <td class="text-right mono text-white">${{ number_format($line->unit_price * $line->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-between border-t border-[#5D6E6E]/25 pt-3 mt-4">
                            <span class="font-display text-[#FFFFFF] tracking-[0.2em]">Total</span>
                            <span class="font-display text-[#ED1C24] tracking-[0.18em] text-lg">${{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</section>
@endsection

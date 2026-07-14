<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoWebhookController extends Controller
{
    /**
     * mp notifica los cambios de estado de un pago via webhook/IPN.
     * consultamos el pago por id y actualizamos el pedido asociado.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        $paymentId = $request->input('data.id') ?? $request->query('id');
        if ($paymentId === null) {
            return response()->json(['ok' => false], 400);
        }

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
        $payment = (new PaymentClient())->get($paymentId);

        $order = Order::find($payment->external_reference);
        if ($order !== null) {
            $order->update([
                'status' => $payment->status === 'approved' ? 'pagado' : $payment->status,
                'mp_payment_id' => (string) $payment->id,
                'paid_at' => $payment->status === 'approved' ? now() : null,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}

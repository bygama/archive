<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use Throwable;

class OrderController extends Controller
{
    private const SESSION_KEY = 'cart';

    /**
     * confirma el pedido creando el Order + OrderItems desde el carrito en
     * sesion, limpia el carrito y redirige al checkout de MercadoPago
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = session(self::SESSION_KEY, []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'El carrito está vacío.');
        }

        $order = DB::transaction(function () use ($cart): Order {
            $lines = collect($cart)
                ->map(fn (int $qty, int $productId): array => [
                    'product' => Product::find($productId),
                    'qty' => $qty,
                ])
                ->filter(fn (array $line): bool => $line['product'] !== null)
                ->values();

            $total = $lines->sum(fn (array $line): int => $line['product']->price * $line['qty']);

            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => 'pendiente',
            ]);

            foreach ($lines as $line) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product']->id,
                    'quantity' => $line['qty'],
                    'unit_price' => $line['product']->price,
                ]);
            }

            return $order;
        });

        session()->forget(self::SESSION_KEY);

        return $this->redirectToMercadoPago($order);
    }

    /**
     * arma la preferencia de pago en MercadoPago y redirige al checkout. el
     * pedido ya quedo registrado en la BD antes de llegar aca, asi que si mp
     * falla (por ejemplo, credenciales de prueba sin configurar) avisamos
     * con un mensaje claro en vez de romper con un 500
     *
     * @param  Order  $order
     * @return RedirectResponse
     */
    private function redirectToMercadoPago(Order $order): RedirectResponse
    {
        if (empty(config('services.mercadopago.access_token'))) {
            return redirect()->route('cart')->with(
                'error',
                "Pedido #{$order->id} registrado, pero MercadoPago no está configurado todavía. Contactá al administrador para completar el pago."
            );
        }

        try {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

            $client = new PreferenceClient();

            // sin auto_return: MP rechaza "auto_return=approved" cuando back_urls
            // no son https/publicas (caso local con 127.0.0.1). el usuario vuelve
            // igual clickeando "Volver al sitio" en la pagina de resultado de MP.
            $items = $order->items->map(fn (OrderItem $item): array => [
                'title' => $item->product->name,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'currency_id' => 'ARS',
            ])->all();

            $preferenceData = [
                'items' => $items,
                'back_urls' => [
                    'success' => route('checkout.success'),
                    'failure' => route('checkout.failure'),
                    'pending' => route('checkout.pending'),
                ],
                'external_reference' => (string) $order->id,
            ];

            // MP rechaza notification_url si no es una url https publica (no
            // acepta localhost/127.0.0.1). solo la mandamos si APP_URL es https.
            if (str_starts_with(route('mercadopago.webhook'), 'https://')) {
                $preferenceData['notification_url'] = route('mercadopago.webhook');
            }

            $preference = $client->create($preferenceData);

            return redirect()->away($preference->sandbox_init_point ?: $preference->init_point);
        } catch (MPApiException $exception) {
            Log::error('MercadoPago rechazó la preferencia', [
                'order_id' => $order->id,
                'status' => $exception->getStatusCode(),
                'body' => $exception->getApiResponse()->getContent(),
            ]);

            return redirect()->route('cart')->with(
                'error',
                "Pedido #{$order->id} registrado, pero no pudimos iniciar el pago con MercadoPago. Intentá de nuevo más tarde."
            );
        } catch (Throwable $exception) {
            Log::error('No se pudo crear la preferencia de MercadoPago', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);

            return redirect()->route('cart')->with(
                'error',
                "Pedido #{$order->id} registrado, pero no pudimos iniciar el pago con MercadoPago. Intentá de nuevo más tarde."
            );
        }
    }

    /**
     * mp redirige aca cuando el pago se aprueba
     *
     * @param  Request  $request
     * @return View
     */
    public function success(Request $request): View
    {
        $this->syncOrderStatus($request, 'pagado');

        return view('pages.checkout-success', [
            'order' => $this->findOrder($request),
            'paymentId' => $request->query('payment_id'),
        ]);
    }

    /**
     * mp redirige aca cuando el pago falla
     *
     * @param  Request  $request
     * @return View
     */
    public function failure(Request $request): View
    {
        $this->syncOrderStatus($request, 'rechazado');

        return view('pages.checkout-failure', [
            'order' => $this->findOrder($request),
        ]);
    }

    /**
     * mp redirige aca cuando el pago queda pendiente
     *
     * @param  Request  $request
     * @return View
     */
    public function pending(Request $request): View
    {
        $this->syncOrderStatus($request, 'pendiente');

        return view('pages.checkout-pending', [
            'order' => $this->findOrder($request),
        ]);
    }

    /**
     * busca el pedido a partir del external_reference que devuelve mp
     *
     * @param  Request  $request
     * @return Order|null
     */
    private function findOrder(Request $request): ?Order
    {
        $orderId = $request->query('external_reference');
        if ($orderId === null) {
            return null;
        }

        return Order::with('items.product')->find($orderId);
    }

    /**
     * guarda el estado que informa mp contra el pedido, usando el
     * external_reference que mandamos al crear la preferencia
     *
     * @param  Request  $request
     * @param  string   $status
     * @return void
     */
    private function syncOrderStatus(Request $request, string $status): void
    {
        $orderId = $request->query('external_reference');
        if ($orderId === null) {
            return;
        }

        Order::whereKey($orderId)->update([
            'status' => $status,
            'mp_payment_id' => $request->query('payment_id'),
            'paid_at' => $status === 'pagado' ? now() : null,
        ]);
    }
}

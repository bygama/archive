<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private const SESSION_KEY = 'cart';

    /**
     * arma el carrito real desde la sesion a cara de nalga y por cada product_id busca el
     * producto y calcula el subtotal
     *
     * @return View
     */
    public function index(): View
    {
        $cart = session(self::SESSION_KEY, []);

        $items = collect($cart)
            ->map(function (int $qty, int $productId): ?array {
                $product = Product::find($productId);
                if ($product === null) {
                    return null;
                }

                return [
                    'product' => $product,
                    'qty' => $qty,
                    'subtotal' => $product->price * $qty,
                ];
            })
            ->filter()
            ->values();

        return view('pages.cart', [
            'items' => $items,
            'subtotal' => $items->sum('subtotal'),
        ]);
    }

    /**
     * agrega producto al carrito o suma si ya habia
     *
     * @param  Request  $request
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session(self::SESSION_KEY, []);
        $qty = $data['quantity'] ?? 1;
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $qty;

        session([self::SESSION_KEY => $cart]);

        return back()->with('status', $product->name . ' agregado al carrito.');
    }

    /**
     * actualiza la cantidad de un item
     *
     * @param  Request  $request
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session(self::SESSION_KEY, []);
        $cart[$product->id] = $data['quantity'];

        session([self::SESSION_KEY => $cart]);

        return back()->with('status', 'Cantidad actualizada.');
    }

    /**
     * saca un producto del carrito
     *
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function remove(Product $product): RedirectResponse
    {
        $cart = session(self::SESSION_KEY, []);
        unset($cart[$product->id]);

        session([self::SESSION_KEY => $cart]);

        return back()->with('status', 'Ítem eliminado del carrito.');
    }
}

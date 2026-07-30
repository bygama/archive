<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * los filtros de autorizacion. los dejo fijos aca porque son una lista
     * cerrada del front, no salen del catalogo.
     *
     * @var array<int, array<string, string>>
     */
    private const CLEARANCE_FILTERS = [
        ['key' => 'restricted', 'label' => 'RESTRINGIDO'],
        ['key' => 'critical', 'label' => 'CRÍTICO'],
        ['key' => 'classified', 'label' => 'CLASIFICADO'],
    ];

    /**
     * listado del catalogo con los filtros por categoria y autorizacion
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        $categorySlug = trim((string) $request->query('categoria', ''));
        $clearanceKey = trim((string) $request->query('autorizacion', ''));

        $products = Product::with('category')
            ->filtered($categorySlug ?: null, $clearanceKey ?: null)
            ->orderBy('id')
            ->get();

        return view('products.index', [
            'products' => $products,
            'categories' => Category::orderBy('id')->get(),
            'clearanceFilters' => self::CLEARANCE_FILTERS,
            'selectedCategory' => $categorySlug,
            'selectedClearance' => $clearanceKey,
            'totalAll' => Product::count(),
        ]);
    }

    /**
     * ficha de un producto + algunos relacionados para que no quede solo
     *
     * @param  Product  $product
     * @return View
     */
    public function show(Product $product): View
    {
        $product->load('category');

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(3)
            ->get();

        if ($related->count() < 3) {
            $extra = Product::with('category')
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->take(3 - $related->count())
                ->get();

            $related = $related->concat($extra);
        }

        return view('products.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}

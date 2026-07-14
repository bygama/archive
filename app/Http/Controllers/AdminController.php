<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * panel del admin con los totales y las ultimas entradas
     *
     * @return View
     */
    public function dashboard(): View
    {
        $topProductLine = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->first();

        $topBillingMonth = Order::where('status', 'pagado')
            ->select(DB::raw("DATE_FORMAT(paid_at, '%Y-%m') as month"), DB::raw('SUM(total) as total'))
            ->groupBy('month')
            ->orderByDesc('total')
            ->first();

        return view('admin.dashboard', [
            'stats' => [
                'posts' => Post::count(),
                'users' => User::count(),
                'products' => Product::count(),
                'categories' => Category::count(),
                'orders' => Order::count(),
                'revenue' => Order::where('status', 'pagado')->sum('total'),
            ],
            'recentPosts' => Post::latestPublished()->take(4)->get(),
            'topProduct' => $topProductLine?->product,
            'topProductQty' => $topProductLine?->total_qty ?? 0,
            'topBillingMonth' => $topBillingMonth?->month,
            'topBillingTotal' => $topBillingMonth?->total ?? 0,
        ]);
    }
}

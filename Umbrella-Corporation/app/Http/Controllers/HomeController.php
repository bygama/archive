<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * arma la home con las categorias, los destacados y los ultimos posts
     *
     * @return View
     */
    public function index(): View
    {
        return view('pages.home', [
            'categories' => Category::orderBy('id')->get(),
            'featured' => Product::with('category')->orderBy('id')->take(4)->get(),
            'latestPosts' => Post::latestPublished()->take(3)->get(),
        ]);
    }
}

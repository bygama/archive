<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    /**
     * listado de las entradas ya publicadas
     *
     * @return View
     */
    public function index(): View
    {
        return view('blog.index', [
            'posts' => Post::latestPublished()->get(),
        ]);
    }

    /**
     * muestra una entrada y dos relacionadas abajo
     *
     * @param  Post  $post
     * @return View
     */
    public function show(Post $post): View
    {
        $related = Post::latestPublished()
            ->where('id', '!=', $post->id)
            ->take(2)
            ->get();

        return view('blog.show', [
            'post' => $post,
            'related' => $related,
        ]);
    }
}

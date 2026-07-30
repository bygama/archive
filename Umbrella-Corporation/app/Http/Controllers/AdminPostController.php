<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    /**
     * listado de entradas para el abm
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.posts.index', [
            'posts' => Post::latestPublished()->get(),
        ]);
    }

    /**
     * muestra el form para cargar una entrada nueva
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.posts.create');
    }

    /**
     * guarda una entrada nueva con la imagen si vino
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePost($request);

        // los campos que el form no toma los derivamos aca
        $data['slug'] = Str::slug($data['title']);
        $data['security_key'] = $data['security'];
        $data['document_id'] = 'DOC-' . now()->format('Y') . '-' . strtoupper(Str::random(4));
        $data['icon'] = 'file-text';
        $data['last_revision'] = now();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Entrada creada correctamente.');
    }

    /**
     * muestra el form para editar una entrada
     *
     * @param  Post  $post
     * @return View
     */
    public function edit(Post $post): View
    {
        return view('admin.posts.edit', ['post' => $post]);
    }

    /**
     * actualiza una entrada y reemplaza la imagen si subieron otra
     *
     * @param  Request  $request
     * @param  Post  $post
     * @return RedirectResponse
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validatePost($request);
        $data['security_key'] = $data['security'];

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Entrada actualizada correctamente.');
    }

    /**
     * borra una entrada y de paso su imagen del disco
     *
     * @param  Post  $post
     * @return RedirectResponse
     */
    public function destroy(Post $post): RedirectResponse
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Entrada eliminada correctamente.');
    }

    /**
     * reglas de validacion que comparten store() y update()
     *
     * @param  Request  $request
     * @return array
     */
    private function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'security' => 'required|in:NOMINAL,RESTRICTED,CLASSIFIED,CRITICAL',
            'author' => 'required|string|max:255',
            'facility' => 'nullable|string|max:255',
            'published_at' => 'required|date',
            'excerpt' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ], [
            'title.required' => 'El título es obligatorio.',
            'category.required' => 'La categoría es obligatoria.',
            'security.required' => 'Elegí un nivel de seguridad.',
            'author.required' => 'El autor es obligatorio.',
            'published_at.required' => 'La fecha es obligatoria.',
            'excerpt.required' => 'El extracto es obligatorio.',
            'body.required' => 'El cuerpo es obligatorio.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no puede superar los 2 MB.',
        ]);
    }
}

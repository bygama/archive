<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class AdminUserController extends Controller
{
    /**
     * listado de usuarios con cuantas suscripciones tiene cada uno
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::withCount('subscriptions')->get(),
        ]);
    }

    /**
     * ficha de un usuario con sus servicios contratados
     *
     * @param  User  $user
     * @return View
     */
    public function show(User $user): View
    {
        $user->load('subscriptions.product', 'orders.items.product');   // trae los servicios y pedidos + su producto

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }
}

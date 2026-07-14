<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * panel de la cuenta del usuario con sus servicios
     *
     * @return View
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('subscriptions.product', 'orders.items.product');

        return view('account.dashboard', [
            'user' => $user,
        ]);
    }
}

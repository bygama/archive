<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MercadoPagoWebhookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// sitio publico
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

// carrito (publico, vive en sesion)
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');

// checkout (requiere login)
Route::post('/checkout', [OrderController::class, 'store'])
    ->middleware('auth')
    ->name('checkout.store');
Route::get('/checkout/success', [OrderController::class, 'success'])->name('checkout.success');
Route::get('/checkout/failure', [OrderController::class, 'failure'])->name('checkout.failure');
Route::get('/checkout/pending', [OrderController::class, 'pending'])->name('checkout.pending');

// webhook de MercadoPago (sin auth, lo llama MP server-to-server)
Route::post('/webhooks/mercadopago', [MercadoPagoWebhookController::class, 'handle'])->name('mercadopago.webhook');

// autenticacion
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// area del usuario logueado (pide login)
Route::get('/mi-cuenta', [AccountController::class, 'index'])
    ->middleware('auth')
    ->name('account.dashboard');

// panel de admin (login + rol admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // ABM de la bitacora
    Route::get('/bitacora', [AdminPostController::class, 'index'])->name('posts.index');
    Route::get('/bitacora/nueva', [AdminPostController::class, 'create'])->name('posts.create');
    Route::post('/bitacora', [AdminPostController::class, 'store'])->name('posts.store');
    Route::get('/bitacora/{post}/editar', [AdminPostController::class, 'edit'])->name('posts.edit');
    Route::put('/bitacora/{post}', [AdminPostController::class, 'update'])->name('posts.update');
    Route::delete('/bitacora/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');

    // ABM del catalogo de bioagentes
    Route::get('/catalogo', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/catalogo/nuevo', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/catalogo', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/catalogo/{product}/editar', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/catalogo/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/catalogo/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // personal
    Route::get('/personal', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/personal/{user}', [AdminUserController::class, 'show'])->name('users.show');
});

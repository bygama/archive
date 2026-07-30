<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::where('email', 'cliente@umbrella.corp')->first();

        if ($cliente === null) {
            return;
        }

        // le contratamos 2 servicios al cliente (los 2 primeros del catalogo)
        $productos = Product::take(2)->get();

        foreach ($productos as $producto) {
            Subscription::create([
                'user_id' => $cliente->id,
                'product_id' => $producto->id,
                'status' => 'activo',
                'price' => $producto->price,
                'contracted_at' => now(),
            ]);
        }
    }
}

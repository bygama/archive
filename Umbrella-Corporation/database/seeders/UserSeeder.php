<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Albert Wesker',
                'email' => 'admin@umbrella.corp',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jill Valentine',
                'email' => 'cliente@umbrella.corp',
                'password' => Hash::make('password'),
                'role' => 'cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

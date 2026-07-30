<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cepas Clásicas de Umbrella',
                'slug' => 'cepas-clasicas-de-umbrella',
                'icon' => 'flask',
                'description' => 'Cepas fundacionales de Umbrella Corporation. La base sobre la que se construyó toda la investigación viral.',
            ],
            [
                'name' => 'Cepas Virales Avanzadas',
                'slug' => 'cepas-virales-avanzadas',
                'icon' => 'biohazard',
                'description' => 'Cepas evolucionadas con comportamiento mutagénico avanzado y adaptaciones biológicas extremas.',
            ],
            [
                'name' => 'Bio-Agentes Parasitarios',
                'slug' => 'bio-agentes-parasitarios',
                'icon' => 'bug',
                'description' => 'Agentes parasitarios diseñados para control de huésped, comportamiento simbiótico y dominio biológico.',
            ],
            [
                'name' => 'Mutágenos Experimentales',
                'slug' => 'mutagenos-experimentales',
                'icon' => 'atom',
                'description' => 'Mutágenos híbridos, fúngicos y experimentales del archivo restringido de programas atípicos.',
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}

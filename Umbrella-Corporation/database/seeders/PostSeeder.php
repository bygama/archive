<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Incidente de Raccoon City',
                'slug' => 'raccoon-city-incident-report',
                'category' => 'Incidente',
                'security' => 'CLASSIFIED',
                'security_key' => 'classified',
                'author' => 'División de Asuntos Internos',
                'document_id' => 'RC-1998-IR',
                'icon' => 'file-alert',
                'excerpt' => 'Revisión interna de un escenario ficticio de fallo de contención en el perímetro urbano.',
                'body' => 'El siguiente documento es una revisión interna ficticia que resume hallazgos operativos, estimaciones de impacto civil y protocolos de comunicación corporativa tras el escenario de contención simulado en Raccoon City. Todas las referencias se presentan como material narrativo para fines de archivo dentro de Umbrella Corporation.',
                'facility' => 'División Raccoon',
                'published_at' => '1998-09-30',
                'last_revision' => '1998-10-02',
            ],
            [
                'title' => 'Contención Clase V',
                'slug' => 'containment-protocols-class-v',
                'category' => 'Protocolo',
                'security' => 'CRITICAL / BIOHAZARD',
                'security_key' => 'critical',
                'author' => 'Unidad de Control Biohazard',
                'document_id' => 'CV-004-P',
                'icon' => 'biohazard',
                'excerpt' => 'Protocolo de manejo actualizado para muestras ficticias de Clase V dentro de entornos de laboratorio sellados.',
                'body' => 'Este protocolo narrativo consolida los procedimientos internos de manejo aplicados a muestras ficticias Clase V, incluyendo segmentación de flujo de aire, ciclos de descontaminación de trajes y rutas de escalamiento revisadas. El procedimiento reemplaza todos los memorandos previos al Q3 fiscal y opera únicamente como documentación dentro del universo narrativo.',
                'facility' => 'Laboratorio NEST',
                'published_at' => '1998-10-04',
                'last_revision' => '1998-10-09',
            ],
            [
                'title' => 'Almacenamiento Criogénico',
                'slug' => 'cryogenic-storage-standards-update',
                'category' => 'Laboratorio',
                'security' => 'NOMINAL',
                'security_key' => 'nominal',
                'author' => 'Sistemas Médicos',
                'document_id' => 'CS-112-S',
                'icon' => 'snowflake',
                'excerpt' => 'Líneas base operativas para unidades ficticias de almacenamiento criogénico en las alas de investigación de Umbrella.',
                'body' => 'Parámetros estandarizados para unidades ficticias de almacenamiento criogénico en todas las alas de investigación. Las ventanas de calibración interna, los umbrales de redundancia y la cadencia de auditoría se describen como parte de la línea base operativa narrativa.',
                'facility' => 'Ala Médica',
                'published_at' => '1998-10-12',
                'last_revision' => '1998-10-12',
            ],
            [
                'title' => 'B.O.W. vs Convencionales',
                'slug' => 'bow-and-conventional-assets',
                'category' => 'Investigación',
                'security' => 'RESTRICTED',
                'security_key' => 'restricted',
                'author' => 'Desarrollo de Armamento',
                'document_id' => 'BW-778-N',
                'icon' => 'dna',
                'excerpt' => 'Nota narrativa interna que enmarca las diferencias conceptuales entre las categorías ficticias de activos.',
                'body' => 'Un briefing exclusivamente narrativo destinado a diferenciar las categorías ficticias de activos bio-orgánicos del inventario logístico convencional. El documento se enmarca estrictamente como material dentro del universo, con fines de diseño y archivo.',
                'facility' => 'División Europea',
                'published_at' => '1998-10-18',
                'last_revision' => '1998-10-21',
            ],
            [
                'title' => 'Mansión Spencer',
                'slug' => 'spencer-mansion-archive-internal-review',
                'category' => 'Histórico',
                'security' => 'CLASSIFIED',
                'security_key' => 'classified',
                'author' => 'Supervisión Ejecutiva',
                'document_id' => 'SM-001-A',
                'icon' => 'archive',
                'excerpt' => 'Revisión ficticia de la colección del archivo de la Mansión Spencer y su importancia narrativa.',
                'body' => 'Una revisión ficticia de la colección del archivo de la Mansión Spencer, incluyendo extractos de correspondencia, referencias a planos y memorandos internos. El material se preserva como artefacto narrativo dentro de Umbrella Corporation.',
                'facility' => 'Archivos Arklay',
                'published_at' => '1998-11-01',
                'last_revision' => '1998-11-04',
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }
}

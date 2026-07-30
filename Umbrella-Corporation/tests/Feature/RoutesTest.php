<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Smoke test de las rutas públicas. Cada caso garantiza:
 * 1. La ruta resuelve sin error 500.
 * 2. La vista renderiza un texto característico.
 * 3. (En catálogo y blog) los datos sembrados aparecen en la respuesta.
 */
class RoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_renderiza_con_datos_sembrados(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Umbrella')
            ->assertSee('Progenitor Virus');
    }

    public function test_catalogo_lista_productos(): void
    {
        $this->get('/products')
            ->assertOk()
            ->assertSee('Bioagentes')
            ->assertSee('T-Virus')
            ->assertSee('Las Plagas');
    }

    public function test_filtro_por_categoria_devuelve_solo_la_categoria(): void
    {
        $response = $this->get('/products?categoria=cepas-clasicas-de-umbrella')->assertOk();

        $response->assertSee('Progenitor Virus');
        $response->assertSee('T-Virus');
        $response->assertDontSee('Mold / Mutamycete');
    }

    public function test_filtro_por_autorizacion_devuelve_solo_ese_nivel(): void
    {
        $response = $this->get('/products?autorizacion=critical')->assertOk();

        $response->assertSee('T-Virus');
        $response->assertDontSee('Las Plagas');
    }

    public function test_detalle_producto_existente(): void
    {
        $this->get('/products/t-virus')
            ->assertOk()
            ->assertSee('Tyrant Virus')
            ->assertSee('TV-002');
    }

    public function test_detalle_producto_inexistente_devuelve_404(): void
    {
        $this->get('/products/no-existe')->assertNotFound();
    }

    public function test_blog_lista_posts(): void
    {
        $this->get('/blog')
            ->assertOk()
            ->assertSee('Raccoon City');
    }

    public function test_detalle_post_existente(): void
    {
        $this->get('/blog/raccoon-city-incident-report')
            ->assertOk()
            ->assertSee('División de Asuntos Internos');
    }

    public function test_detalle_post_inexistente_devuelve_404(): void
    {
        $this->get('/blog/no-existe')->assertNotFound();
    }

    public function test_about_renderiza(): void
    {
        $this->get('/about')->assertOk()->assertSee('Mateo Spencer');
    }

    public function test_contact_renderiza_formulario(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('Solicitar')
            ->assertSee('Departamento');
    }

    public function test_cart_renderiza_items_demo(): void
    {
        $this->get('/cart')
            ->assertOk()
            ->assertSee('T-Virus');
    }
}

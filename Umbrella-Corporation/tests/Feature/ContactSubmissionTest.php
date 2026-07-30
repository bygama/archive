<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Garantiza que ContactRequest valida los campos críticos del formulario
 * y que un POST válido redirige al formulario con flag de éxito en sesión.
 */
class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private const VALID_PAYLOAD = [
        'full_name' => 'Albert Wesker',
        'email' => 'wesker@umbrella.corp',
        'department' => 'Bioingeniería',
        'clearance' => 'Nivel 5 — Crítico / Biohazard',
        'reason' => 'Validación de protocolo de contención clase V para investigación interna.',
        'agree' => '1',
    ];

    public function test_solicitud_valida_redirige_con_status_recibido(): void
    {
        $this->post('/contact', self::VALID_PAYLOAD)
            ->assertRedirect(route('contact'))
            ->assertSessionHas('contact_status', 'received')
            ->assertSessionHas('contact_full_name', 'Albert Wesker');
    }

    public function test_falta_nombre_dispara_error_de_validacion(): void
    {
        $payload = self::VALID_PAYLOAD;
        unset($payload['full_name']);

        $this->post('/contact', $payload)
            ->assertSessionHasErrors(['full_name']);
    }

    public function test_email_invalido_es_rechazado(): void
    {
        $payload = array_merge(self::VALID_PAYLOAD, ['email' => 'no-es-un-email']);

        $this->post('/contact', $payload)
            ->assertSessionHasErrors(['email']);
    }

    public function test_departamento_fuera_de_lista_es_rechazado(): void
    {
        $payload = array_merge(self::VALID_PAYLOAD, ['department' => 'Ventas']);

        $this->post('/contact', $payload)
            ->assertSessionHasErrors(['department']);
    }

    public function test_clearance_fuera_de_lista_es_rechazado(): void
    {
        $payload = array_merge(self::VALID_PAYLOAD, ['clearance' => 'Nivel 9']);

        $this->post('/contact', $payload)
            ->assertSessionHasErrors(['clearance']);
    }

    public function test_sin_aceptar_protocolo_es_rechazado(): void
    {
        $payload = self::VALID_PAYLOAD;
        unset($payload['agree']);

        $this->post('/contact', $payload)
            ->assertSessionHasErrors(['agree']);
    }
}

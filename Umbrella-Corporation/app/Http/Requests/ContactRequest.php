<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * los departamentos validos. son los mismos que el select del form
     * y los que aparecen en el about.
     *
     * @var array<int, string>
     */
    public const DEPARTMENTS = [
        'Bioingeniería',
        'Investigación Farmacéutica',
        'Sistemas de Contención',
        'Seguridad Privada',
        'Supervisión Corporativa',
    ];

    /** @var array<int, string> */
    public const CLEARANCE_LEVELS = [
        'Nivel 3 — Nominal',
        'Nivel 4 — Restringido',
        'Nivel 5 — Crítico / Biohazard',
        'Nivel 5 — Clasificado',
    ];

    /**
     * cualquiera puede mandar el form, no hace falta estar logueado
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * las reglas de cada campo del form
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:160'],
            'department' => ['required', 'string', 'in:' . implode(',', self::DEPARTMENTS)],
            'clearance' => ['required', 'string', 'in:' . implode(',', self::CLEARANCE_LEVELS)],
            'reason' => ['nullable', 'string', 'max:1500'],
            'agree' => ['accepted'],
        ];
    }

    /**
     * los mensajes en español para cada error
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'El nombre completo es obligatorio.',
            'full_name.min' => 'Ingresá al menos 3 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresá un correo electrónico válido.',
            'department.required' => 'Seleccioná un departamento.',
            'department.in' => 'Seleccioná un departamento de la lista.',
            'clearance.required' => 'Seleccioná un nivel de autorización.',
            'clearance.in' => 'Seleccioná un nivel de la lista.',
            'reason.max' => 'El motivo no puede superar los 1500 caracteres.',
            'agree.accepted' => 'Debés aceptar el protocolo interno para enviar la solicitud.',
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    /**
     * texto institucional que va solo en el about. no es una entidad del
     * dominio (catalogo / blog) asi que lo dejo como constante, no en tabla.
     *
     * @var array<int, array<string, string>>
     */
    private const DIVISIONS = [
        ['name' => 'Bioingeniería', 'icon' => 'dna', 'description' => 'Marco de investigación narrativa en torno al diseño ficticio de muestras y escenarios de replicación.'],
        ['name' => 'Investigación Farmacéutica', 'icon' => 'flask', 'description' => 'Pipeline farmacéutico ficticio usado para narrativa visual y referencias de diseño.'],
        ['name' => 'Sistemas de Contención', 'icon' => 'shield-lock', 'description' => 'Infraestructura imaginada de contención representada mediante props, planos e informes.'],
        ['name' => 'Seguridad Privada', 'icon' => 'user-shield', 'description' => 'Operativos y protocolos de seguridad ficticios documentados como parte del lore corporativo.'],
        ['name' => 'Supervisión Corporativa', 'icon' => 'building', 'description' => 'Capa ejecutiva responsable de la continuidad narrativa y la gobernanza de acceso.'],
    ];

    /** @var array<int, array<string, string>> */
    private const TIMELINE = [
        ['year' => '1968', 'title' => 'Fundación', 'note' => 'Constitución de Umbrella como conglomerado farmacéutico dentro del lore del proyecto.'],
        ['year' => '1978', 'title' => 'Laboratorio NEST', 'note' => 'Instalación subterránea de investigación puesta en marcha en la continuidad narrativa.'],
        ['year' => '1988', 'title' => 'Operaciones Arklay', 'note' => 'Expansión de los terrenos ficticios de investigación y consolidación del archivo.'],
        ['year' => '1998', 'title' => 'Incidente Raccoon', 'note' => 'Evento de referencia usado como ancla narrativa canónica en todo el archivo.'],
        ['year' => '2003', 'title' => 'Reestructuración', 'note' => 'Reorganización interna que enmarca el layout actual del archivo.'],
    ];

    /**
     * pagina institucional con las divisiones y la linea de tiempo
     *
     * @return View
     */
    public function about(): View
    {
        return view('pages.about', [
            'divisions' => self::DIVISIONS,
            'timeline' => self::TIMELINE,
        ]);
    }

    /**
     * muestra el form de solicitud de acceso
     *
     * @return View
     */
    public function contact(): View
    {
        return view('pages.contact');
    }

    /**
     * procesa la solicitud de acceso. la validacion la maneja ContactRequest;
     * si pasa lo devolvemos al form con un flag de exito en la session. no lo
     * guardamos porque la consigna no pide envio real.
     *
     * @param  ContactRequest  $request
     * @return RedirectResponse
     */
    public function submitContact(ContactRequest $request): RedirectResponse
    {
        $request->validated();

        return redirect()
            ->route('contact')
            ->with('contact_status', 'received')
            ->with('contact_full_name', $request->string('full_name')->toString());
    }
}

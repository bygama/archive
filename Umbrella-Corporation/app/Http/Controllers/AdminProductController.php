<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminProductController extends Controller
{
    /**
     * listado del catalogo para el abm
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::with('category')->latest()->get(),
        ]);
    }

    /**
     * muestra el form para cargar un bioagente nuevo
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * guarda un bioagente nuevo con la imagen si vino
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        $fields = $this->mappedFields($data);
        // estos campos no van en el form, los pongo por defecto
        $fields['icon'] = 'flask';
        $fields['dossier'] = [];
        $fields['last_revision'] = now();
        // si no suben imagen guardo string vacio y la vista muestra el icono
        $fields['image'] = $request->hasFile('image')
            ? 'storage/' . $request->file('image')->store('products', 'public')
            : '';

        Product::create($fields);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Bioagente creado correctamente.');
    }

    /**
     * muestra el form para editar un bioagente
     *
     * @param  Product  $product
     * @return View
     */
    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * actualiza un bioagente y reemplaza la imagen si subieron otra.
     * el dossier y el icono no los toco asi no piso lo que ya tenia
     *
     * @param  Request  $request
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request, $product);

        $fields = $this->mappedFields($data);

        if ($request->hasFile('image')) {
            // si la imagen vieja era una subida la borro del disco
            if ($product->image && str_starts_with($product->image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
            }
            $fields['image'] = 'storage/' . $request->file('image')->store('products', 'public');
        }

        $product->update($fields);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Bioagente actualizado correctamente.');
    }

    /**
     * borra un bioagente y de paso su imagen si la habian subido
     *
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && str_starts_with($product->image, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Bioagente eliminado correctamente.');
    }

    /**
     * arma el arreglo de columnas a partir de lo que vino del form.
     * de un solo nivel de seguridad derivo el estado y la autorizacion
     * asi el form queda corto y los datos quedan coherentes
     *
     * @param  array  $data
     * @return array
     */
    private function mappedFields(array $data): array
    {
        $level = $data['level'];

        // el estado que se ve en el badge del catalogo
        $statusMap = [
            'NOMINAL' => 'NOMINAL',
            'RESTRICTED' => 'RESTRICTED',
            'CLASSIFIED' => 'CLASSIFIED',
            'CRITICAL' => 'CRITICAL / BIOHAZARD',
        ];

        // el nivel de clearance que pide la ficha
        $clearanceMap = [
            'NOMINAL' => 'Nivel 1',
            'RESTRICTED' => 'Nivel 4',
            'CLASSIFIED' => 'Nivel 5',
            'CRITICAL' => 'Nivel 5',
        ];

        $key = strtolower($level);

        return [
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'category_id' => $data['category_id'],
            'type' => $data['type'],
            'id_code' => $data['id_code'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'facility' => $data['facility'],
            'risk_index' => $data['risk_index'],
            'containment_class' => $data['containment_class'],
            'format' => $data['format'],
            'color_visual' => $data['color_visual'] ?? null,
            'description' => $data['description'],
            'body' => $data['body'],
            'status' => $statusMap[$level],
            'status_key' => $key,
            'clearance' => $clearanceMap[$level],
            'clearance_key' => $key,
        ];
    }

    /**
     * reglas de validacion que comparten store() y update().
     * en update le paso el producto para que el id_code unico se ignore a si mismo
     *
     * @param  Request       $request
     * @param  Product|null  $product
     * @return array
     */
    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|string|max:255',
            'id_code' => ['required', 'string', 'max:255', Rule::unique('products', 'id_code')->ignore($product?->id)],
            'price' => 'required|integer|min:0',
            'stock' => 'required|string|max:255',
            'level' => 'required|in:NOMINAL,RESTRICTED,CLASSIFIED,CRITICAL',
            'facility' => 'required|string|max:255',
            'risk_index' => ['required', 'string', 'max:20', 'regex:/^\d{1,3}\s*\/\s*\d{1,3}$/'],
            'containment_class' => 'required|string|max:255',
            'format' => 'required|string|max:255',
            'color_visual' => 'nullable|string|max:255',
            'description' => 'required|string|max:1000',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'category_id.required' => 'Elegí una categoría.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'type.required' => 'El tipo es obligatorio.',
            'id_code.required' => 'El código de identificación es obligatorio.',
            'id_code.unique' => 'Ya existe un bioagente con ese código.',
            'price.required' => 'El precio es obligatorio.',
            'price.integer' => 'El precio tiene que ser un número entero.',
            'stock.required' => 'El stock es obligatorio.',
            'level.required' => 'Elegí un nivel de seguridad.',
            'facility.required' => 'La instalación es obligatoria.',
            'risk_index.required' => 'El índice de riesgo es obligatorio.',
            'risk_index.regex' => 'El índice de riesgo va en formato 96/100.',
            'containment_class.required' => 'La clase de contención es obligatoria.',
            'format.required' => 'El formato es obligatorio.',
            'description.required' => 'La descripción es obligatoria.',
            'body.required' => 'El cuerpo es obligatorio.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.max' => 'La imagen no puede superar los 2 MB.',
        ]);
    }
}

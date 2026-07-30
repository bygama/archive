@php
    // $action, $method, $product, $categories y $submitLabel los manda la vista que incluye este form
    $isEdit = ($method ?? 'POST') === 'PUT';
    $img = ! empty($product?->image) ? asset($product->image) : null;
    // el nivel sale del clearance_key que ya tiene guardado el producto
    $selectedLevel = old('level', strtoupper($product?->clearance_key ?? 'NOMINAL'));
    $levels = [
        'NOMINAL' => 'Nominal',
        'RESTRICTED' => 'Restringido',
        'CLASSIFIED' => 'Clasificado',
        'CRITICAL' => 'Crítico / Biohazard',
    ];
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="admin-form">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- imagen --}}
    <div class="admin-form__field">
        <span class="input-label">Imagen del Bioagente</span>
        <div class="image-uploader">
            <div class="image-uploader__preview">
                <img id="image-preview" src="{{ $img ?? '' }}" alt="Vista previa de la imagen" @unless ($img) style="display:none" @endunless />
                <span class="image-uploader__placeholder" id="image-placeholder" @if ($img) style="display:none" @endif>
                    <x-tabler-photo class="size-6" aria-hidden="true" />
                    Sin imagen
                </span>
            </div>
            <div class="image-uploader__controls">
                <label for="image" class="image-uploader__btn">
                    <x-tabler-upload class="size-4" aria-hidden="true" />
                    {{ $isEdit ? 'Cambiar imagen' : 'Seleccionar imagen' }}
                </label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/jpeg,image/png,image/webp"
                    class="sr-only"
                    aria-describedby="image-name"
                />
                <span class="image-uploader__name" id="image-name">
                    {{ $isEdit && $img ? 'Imagen actual cargada — subí una nueva para reemplazarla' : 'JPG, PNG o WEBP · máximo 2 MB' }}
                </span>
                @error('image') <p class="input-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- nombre y codigo --}}
    <div class="admin-form__grid admin-form__grid--2">
        <div class="admin-form__field">
            <label for="name" class="input-label">Nombre</label>
            <input
                type="text"
                id="name"
                name="name"
                class="input-control @error('name') is-invalid @enderror"
                placeholder="T-Virus"
                value="{{ old('name', $product?->name) }}"
                required
            />
            @error('name') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="id_code" class="input-label">Código de Identificación</label>
            <input
                type="text"
                id="id_code"
                name="id_code"
                class="input-control @error('id_code') is-invalid @enderror"
                placeholder="TV-002"
                value="{{ old('id_code', $product?->id_code) }}"
                required
            />
            @error('id_code') <p class="input-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- categoria y tipo --}}
    <div class="admin-form__grid admin-form__grid--2">
        <div class="admin-form__field">
            <label for="category_id" class="input-label">Categoría</label>
            <select id="category_id" name="category_id" class="input-control @error('category_id') is-invalid @enderror" required>
                <option value="" disabled @selected(! old('category_id', $product?->category_id))>Elegí una categoría…</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('category_id', $product?->category_id) === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="type" class="input-label">Tipo</label>
            <input
                type="text"
                id="type"
                name="type"
                class="input-control @error('type') is-invalid @enderror"
                placeholder="Cepa Viral · Parásito · Mutágeno"
                value="{{ old('type', $product?->type) }}"
                required
            />
            @error('type') <p class="input-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- seguridad, precio y stock --}}
    <div class="admin-form__grid admin-form__grid--3">
        <div class="admin-form__field">
            <label for="level" class="input-label">Nivel de Seguridad</label>
            <select id="level" name="level" class="input-control @error('level') is-invalid @enderror" required>
                @foreach ($levels as $value => $label)
                    <option value="{{ $value }}" @selected($selectedLevel === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('level') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="price" class="input-label">Precio (sin puntos)</label>
            <input
                type="number"
                id="price"
                name="price"
                min="0"
                step="1"
                class="input-control @error('price') is-invalid @enderror"
                placeholder="6500000"
                value="{{ old('price', $product?->price) }}"
                required
            />
            @error('price') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="stock" class="input-label">Stock</label>
            <input
                type="text"
                id="stock"
                name="stock"
                class="input-control @error('stock') is-invalid @enderror"
                placeholder="12 muestras"
                value="{{ old('stock', $product?->stock) }}"
                required
            />
            @error('stock') <p class="input-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- instalacion, contencion y formato --}}
    <div class="admin-form__grid admin-form__grid--3">
        <div class="admin-form__field">
            <label for="facility" class="input-label">Instalación</label>
            <input
                type="text"
                id="facility"
                name="facility"
                class="input-control @error('facility') is-invalid @enderror"
                placeholder="Laboratorio Arklay"
                value="{{ old('facility', $product?->facility) }}"
                required
            />
            @error('facility') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="containment_class" class="input-label">Clase de Contención</label>
            <input
                type="text"
                id="containment_class"
                name="containment_class"
                class="input-control @error('containment_class') is-invalid @enderror"
                placeholder="Máxima · Absoluta · Criogénica"
                value="{{ old('containment_class', $product?->containment_class) }}"
                required
            />
            @error('containment_class') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="format" class="input-label">Formato</label>
            <input
                type="text"
                id="format"
                name="format"
                class="input-control @error('format') is-invalid @enderror"
                placeholder="Cilindro viral sellado"
                value="{{ old('format', $product?->format) }}"
                required
            />
            @error('format') <p class="input-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- riesgo y color --}}
    <div class="admin-form__grid admin-form__grid--2">
        <div class="admin-form__field">
            <label for="risk_index" class="input-label">Índice de Riesgo</label>
            <input
                type="text"
                id="risk_index"
                name="risk_index"
                class="input-control @error('risk_index') is-invalid @enderror"
                placeholder="96/100"
                value="{{ old('risk_index', $product?->risk_index) }}"
                required
            />
            <p class="input-helper">Va en formato puntaje/total, por ejemplo 96/100.</p>
            @error('risk_index') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="color_visual" class="input-label">Color Visual <span class="text-[#5D6E6E]">(opcional)</span></label>
            <input
                type="text"
                id="color_visual"
                name="color_visual"
                class="input-control @error('color_visual') is-invalid @enderror"
                placeholder="Rojo intenso"
                value="{{ old('color_visual', $product?->color_visual) }}"
            />
            @error('color_visual') <p class="input-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- descripcion --}}
    <div class="admin-form__field">
        <label for="description" class="input-label">Descripción</label>
        <textarea
            id="description"
            name="description"
            rows="2"
            class="input-control @error('description') is-invalid @enderror"
            placeholder="Resumen breve que aparece en las tarjetas del catálogo."
            required
        >{{ old('description', $product?->description) }}</textarea>
        @error('description') <p class="input-error">{{ $message }}</p> @enderror
    </div>

    {{-- cuerpo --}}
    <div class="admin-form__field">
        <label for="body" class="input-label">Ficha Completa</label>
        <textarea
            id="body"
            name="body"
            rows="8"
            class="input-control @error('body') is-invalid @enderror"
            placeholder="Descripción larga que se ve en el detalle del bioagente."
            required
        >{{ old('body', $product?->body) }}</textarea>
        @error('body') <p class="input-error">{{ $message }}</p> @enderror
    </div>

    {{-- botones --}}
    <div class="admin-form__foot">
        <button type="submit" class="btn btn-primary">
            <x-tabler-device-floppy class="size-4" aria-hidden="true" />
            {{ $submitLabel ?? 'Guardar' }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
            <x-tabler-x class="size-4" aria-hidden="true" />
            Cancelar
        </a>
    </div>
</form>

<script>
    (function () {
        var input = document.getElementById('image');
        var preview = document.getElementById('image-preview');
        var placeholder = document.getElementById('image-placeholder');
        var nameLabel = document.getElementById('image-name');
        if (!input) return;

        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            if (!file) return;
            var url = URL.createObjectURL(file);
            preview.src = url;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
            if (nameLabel) nameLabel.textContent = file.name;
        });
    })();
</script>

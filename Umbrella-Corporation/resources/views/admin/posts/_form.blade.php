@php
    // $action, $method, $post y $submitLabel los manda la vista que incluye este form
    $isEdit = ($method ?? 'POST') === 'PUT';
    $img = $post?->image ? asset('storage/' . $post->image) : null;
    $securityLevels = [
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
        <span class="input-label">Imagen de Portada</span>
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

    {{-- titulo y categoria --}}
    <div class="admin-form__grid admin-form__grid--2">
        <div class="admin-form__field">
            <label for="title" class="input-label">Título de la Entrada</label>
            <input
                type="text"
                id="title"
                name="title"
                class="input-control @error('title') is-invalid @enderror"
                placeholder="Brote contenido en Sector Arklay"
                value="{{ old('title', $post?->title) }}"
                required
            />
            @error('title') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="category" class="input-label">Categoría</label>
            <input
                type="text"
                id="category"
                name="category"
                class="input-control @error('category') is-invalid @enderror"
                placeholder="Contención · Investigación · Incidente"
                value="{{ old('category', $post?->category) }}"
                list="category-suggestions"
                required
            />
            <datalist id="category-suggestions">
                <option value="Contención"></option>
                <option value="Investigación"></option>
                <option value="Incidente"></option>
                <option value="Protocolo"></option>
            </datalist>
            @error('category') <p class="input-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- seguridad, autor y fecha --}}
    <div class="admin-form__grid admin-form__grid--3">
        <div class="admin-form__field">
            <label for="security" class="input-label">Nivel de Seguridad</label>
            <select id="security" name="security" class="input-control @error('security') is-invalid @enderror" required>
                @foreach ($securityLevels as $value => $label)
                    <option value="{{ $value }}" @selected(old('security', $post?->security ?? 'NOMINAL') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('security') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="author" class="input-label">Autor</label>
            <input
                type="text"
                id="author"
                name="author"
                class="input-control @error('author') is-invalid @enderror"
                placeholder="Dr. W. Birkin"
                value="{{ old('author', $post?->author) }}"
                required
            />
            @error('author') <p class="input-error">{{ $message }}</p> @enderror
        </div>

        <div class="admin-form__field">
            <label for="published_at" class="input-label">Fecha de Publicación</label>
            <input
                type="date"
                id="published_at"
                name="published_at"
                class="input-control @error('published_at') is-invalid @enderror"
                value="{{ old('published_at', $post?->published_at?->format('Y-m-d')) }}"
                required
            />
            @error('published_at') <p class="input-error">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- instalacion --}}
    <div class="admin-form__field">
        <label for="facility" class="input-label">Instalación</label>
        <input
            type="text"
            id="facility"
            name="facility"
            class="input-control @error('facility') is-invalid @enderror"
            placeholder="NEST · Laboratorio Arklay"
            value="{{ old('facility', $post?->facility) }}"
        />
        @error('facility') <p class="input-error">{{ $message }}</p> @enderror
    </div>

    {{-- extracto --}}
    <div class="admin-form__field">
        <label for="excerpt" class="input-label">Extracto</label>
        <textarea
            id="excerpt"
            name="excerpt"
            rows="2"
            class="input-control @error('excerpt') is-invalid @enderror"
            placeholder="Resumen breve que aparece en el listado de la bitácora."
            required
        >{{ old('excerpt', $post?->excerpt) }}</textarea>
        <p class="input-helper">Aparece en las tarjetas del listado público. Máximo recomendado 200 caracteres.</p>
        @error('excerpt') <p class="input-error">{{ $message }}</p> @enderror
    </div>

    {{-- cuerpo --}}
    <div class="admin-form__field">
        <label for="body" class="input-label">Cuerpo del Documento</label>
        <textarea
            id="body"
            name="body"
            rows="9"
            class="input-control @error('body') is-invalid @enderror"
            placeholder="Contenido completo de la entrada de bitácora."
            required
        >{{ old('body', $post?->body) }}</textarea>
        @error('body') <p class="input-error">{{ $message }}</p> @enderror
    </div>

    {{-- botones --}}
    <div class="admin-form__foot">
        <button type="submit" class="btn btn-primary">
            <x-tabler-device-floppy class="size-4" aria-hidden="true" />
            {{ $submitLabel ?? 'Guardar' }}
        </button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost">
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

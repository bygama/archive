@extends('layouts.admin')

@section('admin-title', 'Editar Bioagente')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-head__eyebrow">Catálogo · Edición</span>
            <h1 class="admin-page-head__title">Editar Bioagente</h1>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">
            <x-tabler-arrow-left class="size-4" aria-hidden="true" />
            Volver al catálogo
        </a>
    </div>

    @if ($errors->any())
        <div class="admin-flash admin-flash--preview" role="alert">
            <x-tabler-alert-triangle class="size-5" aria-hidden="true" />
            <p>Hay errores en el formulario. Revisá los campos marcados en rojo.</p>
        </div>
    @endif

    <section class="admin-panel">
        <header class="admin-panel__head">
            <h2 class="admin-panel__title">{{ $product->id_code }} · {{ $product->name }}</h2>
            @include('partials.security-badge', ['level' => $product->status])
        </header>
        <div class="admin-panel__body">
            @include('admin.products._form', [
                'action' => route('admin.products.update', $product),
                'method' => 'PUT',
                'product' => $product,
                'categories' => $categories,
                'submitLabel' => 'Guardar Cambios',
            ])
        </div>
    </section>

    {{-- zona de eliminacion --}}
    <section class="admin-panel admin-panel--danger mt-5">
        <div class="admin-panel__body flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-display text-[0.85rem] tracking-[0.2em] uppercase text-white">Eliminar Bioagente</h2>
                <p class="text-sm text-[#9CACAD] mt-1">Esta acción retira el activo del catálogo de forma permanente.</p>
            </div>
            <form
                method="POST"
                action="{{ route('admin.products.destroy', $product) }}"
                onsubmit="return confirm('¿Eliminar definitivamente el bioagente «{{ $product->name }}»?');"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-restricted">
                    <x-tabler-trash class="size-4" aria-hidden="true" />
                    Eliminar
                </button>
            </form>
        </div>
    </section>
@endsection

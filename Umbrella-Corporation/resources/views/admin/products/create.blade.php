@extends('layouts.admin')

@section('admin-title', 'Nuevo Bioagente')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-head__eyebrow">Catálogo · Alta</span>
            <h1 class="admin-page-head__title">Nuevo Bioagente</h1>
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
            <h2 class="admin-panel__title">Datos del Bioagente</h2>
            <span class="badge badge-restricted">
                <x-tabler-virus class="size-3.5" aria-hidden="true" />
                Borrador
            </span>
        </header>
        <div class="admin-panel__body">
            @include('admin.products._form', [
                'action' => route('admin.products.store'),
                'method' => 'POST',
                'product' => null,
                'categories' => $categories,
                'submitLabel' => 'Cargar Bioagente',
            ])
        </div>
    </section>
@endsection

@extends('layouts.admin')

@section('admin-title', 'Catálogo')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-head__eyebrow">Inventario Clasificado</span>
            <h1 class="admin-page-head__title">Catálogo</h1>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <x-tabler-plus class="size-4" aria-hidden="true" />
            Nuevo Bioagente
        </a>
    </div>

    @include('admin.partials.flash')

    <section class="admin-panel">
        <header class="admin-panel__head">
            <h2 class="admin-panel__title">Bioagentes Registrados</h2>
            <span class="font-classified text-[0.62rem] tracking-[0.28em] uppercase text-[#5D6E6E]">
                {{ count($products) }} {{ count($products) === 1 ? 'activo' : 'activos' }}
            </span>
        </header>

        <div class="admin-panel__body--flush">
            @if (count($products) > 0)
                <div class="admin-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Estado</th>
                                <th scope="col" class="text-right">Precio</th>
                                <th scope="col" class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td class="mono">{{ $product->id_code }}</td>
                                    <td>
                                        <span class="text-[#FFFFFF] font-semibold">{{ $product->name }}</span>
                                    </td>
                                    <td>{{ $product->category?->name }}</td>
                                    <td>@include('partials.security-badge', ['level' => $product->status])</td>
                                    <td class="text-right mono text-white">$ {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a
                                                href="{{ route('products.show', $product->slug) }}"
                                                class="table-action"
                                                title="Ver en el sitio"
                                                aria-label="Ver bioagente en el sitio público"
                                            >
                                                <x-tabler-eye class="size-4" aria-hidden="true" />
                                            </a>
                                            <a
                                                href="{{ route('admin.products.edit', $product) }}"
                                                class="table-action"
                                                title="Editar"
                                                aria-label="Editar bioagente"
                                            >
                                                <x-tabler-pencil class="size-4" aria-hidden="true" />
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route('admin.products.destroy', $product) }}"
                                                onsubmit="return confirm('¿Eliminar el bioagente «{{ $product->name }}»? Esta acción no se puede deshacer.');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="table-action table-action--danger"
                                                    title="Eliminar"
                                                    aria-label="Eliminar bioagente"
                                                >
                                                    <x-tabler-trash class="size-4" aria-hidden="true" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="admin-empty">
                    <x-tabler-virus-off class="size-8 text-[#5D6E6E]" aria-hidden="true" />
                    <p class="admin-empty__title">Sin bioagentes en el catálogo</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <x-tabler-plus class="size-4" aria-hidden="true" />
                        Cargar el primero
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection

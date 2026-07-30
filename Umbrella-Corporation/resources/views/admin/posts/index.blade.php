@extends('layouts.admin')

@section('admin-title', 'Bitácora')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-head__eyebrow">Gestión de Contenido</span>
            <h1 class="admin-page-head__title">Bitácora</h1>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <x-tabler-plus class="size-4" aria-hidden="true" />
            Nueva Entrada
        </a>
    </div>

    @include('admin.partials.flash')

    <section class="admin-panel">
        <header class="admin-panel__head">
            <h2 class="admin-panel__title">Registros del Archivo</h2>
            <span class="font-classified text-[0.62rem] tracking-[0.28em] uppercase text-[#5D6E6E]">
                {{ count($posts) }} {{ count($posts) === 1 ? 'entrada' : 'entradas' }}
            </span>
        </header>

        <div class="admin-panel__body--flush">
            @if (count($posts) > 0)
                <div class="admin-table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Doc ID</th>
                                <th scope="col">Título</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Seguridad</th>
                                <th scope="col">Publicación</th>
                                <th scope="col" class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td class="mono">{{ $post->document_id }}</td>
                                    <td>
                                        <span class="text-[#FFFFFF] font-semibold">{{ $post->title }}</span>
                                    </td>
                                    <td>{{ $post->category }}</td>
                                    <td>@include('partials.security-badge', ['level' => $post->security])</td>
                                    <td class="mono">{{ $post->published_at?->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <a
                                                href="{{ route('blog.show', $post->slug) }}"
                                                class="table-action"
                                                title="Ver en el sitio"
                                                aria-label="Ver entrada en el sitio público"
                                            >
                                                <x-tabler-eye class="size-4" aria-hidden="true" />
                                            </a>
                                            <a
                                                href="{{ route('admin.posts.edit', $post) }}"
                                                class="table-action"
                                                title="Editar"
                                                aria-label="Editar entrada"
                                            >
                                                <x-tabler-pencil class="size-4" aria-hidden="true" />
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route('admin.posts.destroy', $post) }}"
                                                onsubmit="return confirm('¿Eliminar la entrada «{{ $post->title }}»? Esta acción no se puede deshacer.');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="table-action table-action--danger"
                                                    title="Eliminar"
                                                    aria-label="Eliminar entrada"
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
                    <x-tabler-file-off class="size-8 text-[#5D6E6E]" aria-hidden="true" />
                    <p class="admin-empty__title">Sin entradas en la bitácora</p>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                        <x-tabler-plus class="size-4" aria-hidden="true" />
                        Crear la primera entrada
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection

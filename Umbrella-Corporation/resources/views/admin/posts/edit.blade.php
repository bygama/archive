@extends('layouts.admin')

@section('admin-title', 'Editar Entrada')

@section('content')
    <div class="admin-page-head">
        <div>
            <span class="admin-page-head__eyebrow">Bitácora · Edición</span>
            <h1 class="admin-page-head__title">Editar Entrada</h1>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-ghost">
            <x-tabler-arrow-left class="size-4" aria-hidden="true" />
            Volver al listado
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
            <h2 class="admin-panel__title">{{ $post->document_id }} · {{ $post->title }}</h2>
            @include('partials.security-badge', ['level' => $post->security])
        </header>
        <div class="admin-panel__body">
            @include('admin.posts._form', [
                'action' => route('admin.posts.update', $post),
                'method' => 'PUT',
                'post' => $post,
                'submitLabel' => 'Guardar Cambios',
            ])
        </div>
    </section>

    {{-- zona de eliminacion --}}
    <section class="admin-panel admin-panel--danger mt-5">
        <div class="admin-panel__body flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-display text-[0.85rem] tracking-[0.2em] uppercase text-white">Eliminar Entrada</h2>
                <p class="text-sm text-[#9CACAD] mt-1">Esta acción retira el registro del archivo de forma permanente.</p>
            </div>
            <form
                method="POST"
                action="{{ route('admin.posts.destroy', $post) }}"
                onsubmit="return confirm('¿Eliminar definitivamente la entrada «{{ $post->title }}»?');"
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

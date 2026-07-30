@extends('errors.layout')

@section('code', '404')
@section('title', 'Documento No Encontrado')
@section('docid', 'UC-ARC-404')

@section('badge')
    <span class="badge badge-classified">
        <x-tabler-file-off class="size-3.5" aria-hidden="true" />
        Sin Registro
    </span>
@endsection

@section('icon')
    <x-tabler-file-search class="size-8" aria-hidden="true" />
@endsection

@section('message')
    El registro solicitado no existe en el archivo o fue purgado del sistema. Verificá el identificador del documento e intentá nuevamente.
@endsection

@section('actions')
    <a href="{{ route('home') }}" class="btn btn-primary">
        <x-tabler-home class="size-4" aria-hidden="true" />
        Volver al inicio
    </a>
    <a href="{{ route('blog.index') }}" class="btn btn-secondary">
        <x-tabler-file-text class="size-4" aria-hidden="true" />
        Ver bitácora
    </a>
@endsection

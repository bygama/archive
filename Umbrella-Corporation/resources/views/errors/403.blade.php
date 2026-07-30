@extends('errors.layout')

@section('code', '403')
@section('title', 'Acceso Denegado')
@section('docid', 'UC-SEC-403')

@section('badge')
    <span class="badge badge-restricted">
        <x-tabler-lock class="size-3.5" aria-hidden="true" />
        Restringido
    </span>
@endsection

@section('icon')
    <x-tabler-shield-lock class="size-8" aria-hidden="true" />
@endsection

@section('message')
    No contás con el nivel de autorización requerido para acceder a este sector del archivo. El intento quedó registrado por la División de Asuntos Internos.
@endsection

@section('actions')
    <a href="{{ route('home') }}" class="btn btn-primary">
        <x-tabler-home class="size-4" aria-hidden="true" />
        Volver al inicio
    </a>
    <a href="{{ route('login') }}" class="btn btn-secondary">
        <x-tabler-login-2 class="size-4" aria-hidden="true" />
        Autenticarse
    </a>
@endsection

@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesión Expirada')
@section('docid', 'UC-SEC-419')

@section('badge')
    <span class="badge badge-standby">
        <x-tabler-clock class="size-3.5" aria-hidden="true" />
        Caducado
    </span>
@endsection

@section('icon')
    <x-tabler-clock-exclamation class="size-8" aria-hidden="true" />
@endsection

@section('message')
    Tu sesión caducó por inactividad o el token de seguridad expiró. Volvé a la página anterior y reintentá la operación.
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

@extends('errors.layout')

@section('code', '500')
@section('title', 'Falla del Sistema')
@section('docid', 'UC-SYS-500')

@section('badge')
    <span class="badge badge-critical">
        <x-tabler-alert-triangle class="size-3.5" aria-hidden="true" />
        Crítico
    </span>
@endsection

@section('icon')
    <x-tabler-server-bolt class="size-8" aria-hidden="true" />
@endsection

@section('message')
    Se produjo una falla en un subsistema del archivo. El equipo técnico de Umbrella fue notificado automáticamente. Reintentá en unos instantes.
@endsection

@section('actions')
    <a href="{{ route('home') }}" class="btn btn-primary">
        <x-tabler-home class="size-4" aria-hidden="true" />
        Volver al inicio
    </a>
@endsection

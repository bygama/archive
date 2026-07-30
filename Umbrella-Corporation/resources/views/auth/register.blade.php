@extends('layouts.app')

@section('title', 'Alta de Credencial')
@section('description', 'Registro de nuevo personal para el archivo clasificado de Umbrella Corporation.')

@section('content')
<section class="section-shell" aria-labelledby="register-heading">
    <div class="container-tech">
        <div class="auth-wrap">
            <div class="auth-card corner-frame" data-animate="fade-up">
                <header class="auth-card__head">
                    <div class="auth-card__tag">
                        <span class="badge badge-restricted">
                            <x-tabler-user-plus class="size-3.5" aria-hidden="true" />
                            Alta de Personal
                        </span>
                        <span class="auth-card__id">UC-AUTH-02</span>
                    </div>
                    <h1 id="register-heading" class="auth-card__title">Alta de<br />Credencial</h1>
                    <p class="auth-card__sub">
                        Complete el formulario para generar una credencial de acceso. La cuenta se crea con nivel de usuario común.
                    </p>
                </header>

                @if ($errors->any())
                    <div class="access-doc__alert access-doc__alert--error" role="alert">
                        <x-tabler-alert-triangle class="size-5" aria-hidden="true" />
                        <div>
                            <p class="font-classified tracking-[0.2em] uppercase text-xs mb-1">Registro rechazado — corrija los siguientes campos:</p>
                            <ul class="list-disc pl-5 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="auth-card__form">
                    @csrf

                    <div class="auth-card__field">
                        <label for="name" class="input-label">Nombre Completo</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="input-control @error('name') is-invalid @enderror"
                            placeholder="J. Valentine"
                            value="{{ old('name') }}"
                            autocomplete="name"
                            required
                            autofocus
                        />
                        @error('name') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="auth-card__field">
                        <label for="email" class="input-label">Correo Corporativo</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="input-control @error('email') is-invalid @enderror"
                            placeholder="agente@umbrella.corp"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                        />
                        @error('email') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="auth-card__field">
                        <label for="password" class="input-label">Clave de Acceso</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="input-control @error('password') is-invalid @enderror"
                            placeholder="Mínimo 8 caracteres"
                            autocomplete="new-password"
                            required
                        />
                        @error('password') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="auth-card__field">
                        <label for="password_confirmation" class="input-label">Confirmar Clave</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="input-control"
                            placeholder="Repita la clave de acceso"
                            autocomplete="new-password"
                            required
                        />
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <x-tabler-id class="size-4" aria-hidden="true" />
                        Crear Credencial
                    </button>
                </form>

                <footer class="auth-card__foot">
                    <span>¿Ya posee credencial?</span>
                    <a href="{{ route('login') }}" class="auth-card__link">Autenticarse →</a>
                </footer>
            </div>
        </div>
    </div>
</section>
@endsection

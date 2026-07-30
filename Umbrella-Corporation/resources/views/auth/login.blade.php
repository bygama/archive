@extends('layouts.app')

@section('title', 'Autenticación de Personal')
@section('description', 'Acceso de personal autorizado al archivo clasificado de Umbrella Corporation.')

@section('content')
<section class="section-shell" aria-labelledby="login-heading">
    <div class="container-tech">
        <div class="auth-wrap">
            <div class="auth-card corner-frame" data-animate="fade-up">
                <header class="auth-card__head">
                    <div class="auth-card__tag">
                        <span class="badge badge-classified">
                            <x-tabler-lock class="size-3.5" aria-hidden="true" />
                            Acceso Restringido
                        </span>
                        <span class="auth-card__id">UC-AUTH-01</span>
                    </div>
                    <h1 id="login-heading" class="auth-card__title">Autenticación<br />de Personal</h1>
                    <p class="auth-card__sub">
                        Ingrese sus credenciales para acceder a los sistemas internos. Todo acceso queda registrado y auditado.
                    </p>
                </header>

                @if (session('status'))
                    <div class="access-doc__alert access-doc__alert--success" role="status">
                        <x-tabler-shield-check class="size-5" aria-hidden="true" />
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="access-doc__alert access-doc__alert--error" role="alert">
                        <x-tabler-alert-triangle class="size-5" aria-hidden="true" />
                        <div>
                            <p class="font-classified tracking-[0.2em] uppercase text-xs mb-1">Acceso denegado — verifique sus credenciales:</p>
                            <ul class="list-disc pl-5 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="auth-card__form">
                    @csrf

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
                            autofocus
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
                            placeholder="••••••••••"
                            autocomplete="current-password"
                            required
                        />
                        @error('password') <p class="input-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="auth-card__row">
                        <label for="remember" class="auth-card__remember">
                            <input type="checkbox" id="remember" name="remember" value="1" class="checkbox-control" {{ old('remember') ? 'checked' : '' }} />
                            <span>Mantener sesión activa</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <x-tabler-fingerprint class="size-4" aria-hidden="true" />
                        Autenticar
                    </button>
                </form>

                <footer class="auth-card__foot">
                    <span>¿Sin credencial asignada?</span>
                    <a href="{{ route('register') }}" class="auth-card__link">Solicitar alta →</a>
                </footer>
            </div>
        </div>
    </div>
</section>
@endsection

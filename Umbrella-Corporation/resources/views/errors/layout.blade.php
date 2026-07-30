<!DOCTYPE html>
<html lang="es" class="bg-[#050505] text-[#9CACAD]">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#050505" />
    <meta name="robots" content="noindex,nofollow" />
    <title>@yield('code') · @yield('title') :: Umbrella Corporation</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/umbrella-logo.webp') }}" />
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen">
    <div class="grain-overlay" aria-hidden="true"></div>

    <main class="error-screen scanlines">
        <section class="error-card">
            <span class="corner-mark tl" aria-hidden="true"></span>
            <span class="corner-mark tr" aria-hidden="true"></span>
            <span class="corner-mark bl" aria-hidden="true"></span>
            <span class="corner-mark br" aria-hidden="true"></span>

            <header class="error-card__head">
                @yield('badge')
                <span class="error-card__id">@yield('docid', 'UC-SYS')</span>
            </header>

            <span class="error-card__icon" aria-hidden="true">@yield('icon')</span>

            <p class="error-card__code">@yield('code')</p>
            <h1 class="error-card__title">@yield('title')</h1>
            <p class="error-card__msg">@yield('message')</p>

            <div class="error-card__actions">
                @yield('actions')
            </div>

            <footer class="error-card__foot">
                <span class="inline-flex items-center gap-2">
                    <span class="status-dot" aria-hidden="true"></span>
                    Incidente Registrado
                </span>
                <span>Umbrella · NEST</span>
            </footer>
        </section>
    </main>
</body>
</html>

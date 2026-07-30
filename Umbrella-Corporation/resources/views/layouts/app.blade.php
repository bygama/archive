<!DOCTYPE html>
<html lang="es" class="bg-[#050505] text-[#9CACAD]">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#050505" />
    <meta name="robots" content="noindex,nofollow" />
    <title>@yield('title', 'Bioagentes') :: Umbrella Corporation</title>
    <meta name="description" content="@yield('description', 'Interfaz de ecommerce clasificada para archivos de investigación ficticios, equipo de contención y activos de simulación biológica.')" />
    <link rel="icon" type="image/webp" href="{{ asset('images/umbrella-logo.webp') }}" />
    <link rel="apple-touch-icon" href="{{ asset('images/umbrella-logo.webp') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
    {{-- el grano que va encima de todo --}}
    <div class="grain-overlay" aria-hidden="true"></div>

    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-50 focus:bg-[#ED1C24] focus:text-white focus:px-4 focus:py-2 focus:font-semibold focus:tracking-[0.18em] focus:uppercase focus:text-xs">
        Saltar al contenido
    </a>

    @include('partials.tactical-bar')
    @include('partials.header')

    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>

<!DOCTYPE html>
<html lang="es" class="bg-[#050505] text-[#9CACAD]">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#050505" />
    <meta name="robots" content="noindex,nofollow" />
    <title>@yield('admin-title', 'Panel') :: Umbrella · NEST Console</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/umbrella-logo.webp') }}" />
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen">
    {{-- el granulado de fondo --}}
    <div class="grain-overlay" aria-hidden="true"></div>

    <a href="#admin-content" class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-[60] focus:bg-[#ED1C24] focus:text-white focus:px-4 focus:py-2 focus:font-semibold focus:tracking-[0.18em] focus:uppercase focus:text-xs">
        Saltar al contenido
    </a>

    <div class="admin-shell">
        @include('admin.partials.sidebar')

        <div class="admin-scrim" data-admin-scrim aria-hidden="true"></div>

        <div class="admin-main">
            <header class="admin-topbar">
                <div class="admin-topbar__left">
                    <button
                        type="button"
                        class="admin-burger"
                        data-admin-toggle
                        aria-label="Alternar navegación"
                        aria-controls="admin-sidebar"
                        aria-expanded="false"
                    >
                        <x-tabler-menu-2 class="size-5" aria-hidden="true" />
                    </button>
                    <span class="admin-topbar__crumb">
                        NEST CONSOLE / <b>@yield('admin-title', 'Panel')</b>
                    </span>
                </div>

                <span class="admin-topbar__status">
                    <span class="status-dot status-dot-nominal" aria-hidden="true"></span>
                    Sistema Nominal
                </span>
            </header>

            <main id="admin-content" class="admin-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function () {
            var toggle = document.querySelector('[data-admin-toggle]');
            var sidebar = document.getElementById('admin-sidebar');
            var scrim = document.querySelector('[data-admin-scrim]');

            function setOpen(open) {
                if (!sidebar || !scrim) return;
                sidebar.dataset.open = open ? 'true' : 'false';
                scrim.dataset.open = open ? 'true' : 'false';
                if (toggle) toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    setOpen(sidebar.dataset.open !== 'true');
                });
            }
            if (scrim) {
                scrim.addEventListener('click', function () { setOpen(false); });
            }
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') setOpen(false);
            });
        })();
    </script>
</body>
</html>

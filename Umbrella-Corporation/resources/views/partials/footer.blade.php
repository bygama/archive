<footer class="border-t border-[#5D6E6E]/25 bg-[#0A0A0A]">
    <div class="container-tech grid gap-10 py-14 md:grid-cols-4">
        <div class="md:col-span-2 flex flex-col gap-5">
            <div class="flex items-center gap-3">
                @include('partials.umbrella-mark', ['size' => 40])
                <div>
                    <p class="font-display text-sm tracking-[0.3em] text-[#FFFFFF]">Umbrella</p>
                    <p class="font-classified text-[0.7rem] tracking-[0.28em] text-[#9CACAD]">CORPORATION</p>
                </div>
            </div>
            <p class="max-w-md text-sm text-[#9CACAD]">
                Interfaz interna de ecommerce clasificada para artefactos de investigación ficticios, réplicas y documentación narrativa. Todo el inventario existe únicamente como material de diseño visual y académico.
            </p>

            <div class="barcode-line w-44">
                @php
                    $bars = [3, 1, 2, 4, 1, 2, 3, 1, 4, 2, 1, 3, 1, 2, 4, 1, 3, 2];
                @endphp
                @foreach ($bars as $weight)
                    <span style="width: {{ $weight }}px;"></span>
                @endforeach
            </div>
            <p class="font-classified text-[0.7rem] tracking-[0.3em] text-[#5D6E6E]">UC-1968-A · SOLO USO INTERNO</p>
        </div>

        <nav aria-label="Navegación del pie" class="flex flex-col gap-3">
            <p class="font-display text-[0.7rem] tracking-[0.28em] text-[#FFFFFF]">Secciones</p>
            <ul class="flex flex-col gap-2 text-sm text-[#9CACAD]">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-[#FFFFFF]">Inicio</a></li>
                <li><a href="{{ route('products.index') }}" class="transition-colors hover:text-[#FFFFFF]">Bioagentes</a></li>
                <li><a href="{{ route('blog.index') }}" class="transition-colors hover:text-[#FFFFFF]">Registros de Investigación</a></li>
                <li><a href="{{ route('about') }}" class="transition-colors hover:text-[#FFFFFF]">Acerca</a></li>
                <li><a href="{{ route('contact') }}" class="transition-colors hover:text-[#FFFFFF]">Solicitar Acceso</a></li>
            </ul>
        </nav>

        <div class="flex flex-col gap-3">
            <p class="font-display text-[0.7rem] tracking-[0.28em] text-[#FFFFFF]">Estado</p>
            <ul class="flex flex-col gap-2 text-sm text-[#9CACAD]">
                <li class="flex items-center gap-2">
                    <span class="status-dot status-dot-nominal" aria-hidden="true"></span>
                    Sistema Nominal
                </li>
                <li class="footer-status-item">
                    <x-tabler-shield-lock class="size-4 text-[#ED1C24]" aria-hidden="true" />
                    <span>Contención Activa</span>
                </li>
                <li class="footer-status-item">
                    <x-tabler-database class="size-4 text-[#9CACAD]" aria-hidden="true" />
                    <span>Inventario&nbsp;v2.4</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-[#5D6E6E]/20">
        <div class="container-tech flex flex-col items-start justify-between gap-3 py-5 text-[0.72rem] font-classified tracking-[0.28em] text-[#5D6E6E] sm:flex-row sm:items-center">
            <p>Propiedad de Umbrella Corporation · Reproducción no autorizada estrictamente prohibida.</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 border border-[#5D6E6E]/40 px-2.5 py-1.5 text-[#9CACAD] transition-colors hover:border-[#ED1C24] hover:text-[#ED1C24]" title="Acceso interno al panel">
                    <x-tabler-shield-lock class="size-3.5" aria-hidden="true" />
                    Acceso Interno
                </a>
                <p>{{ date('Y') }} · Solo Uso Interno</p>
            </div>
        </div>
    </div>
</footer>

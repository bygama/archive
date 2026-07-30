@props([
    'badgeId' => 'UC-1968-A',
    'level' => '4',
    'facility' => 'División Raccoon',
    'status' => 'Solo Personal Autorizado',
])

<aside class="clearance-panel" data-animate="panel">
    <div class="clearance-panel-header">
        <span class="font-display text-[0.7rem] tracking-[0.32em] text-[#FFFFFF]">Propiedad de Umbrella</span>
        <span class="badge badge-classified">CLASIFICADO</span>
    </div>

    <dl class="grid gap-3 text-sm">
        <div class="flex items-center justify-between gap-4">
            <dt class="font-classified text-[0.7rem] tracking-[0.24em] text-[#9CACAD]">ID</dt>
            <dd class="font-classified text-[#FFFFFF] text-base tracking-[0.18em]">{{ $badgeId }}</dd>
        </div>
        <div class="flex items-center justify-between gap-4">
            <dt class="font-classified text-[0.7rem] tracking-[0.24em] text-[#9CACAD]">Autorización</dt>
            <dd class="font-display text-[#ED1C24] tracking-[0.24em]">Nivel {{ $level }}</dd>
        </div>
        <div class="flex items-center justify-between gap-4">
            <dt class="font-classified text-[0.7rem] tracking-[0.24em] text-[#9CACAD]">Instalación</dt>
            <dd class="text-[#FFFFFF]">{{ $facility }}</dd>
        </div>
        <div class="flex items-center justify-between gap-4">
            <dt class="font-classified text-[0.7rem] tracking-[0.24em] text-[#9CACAD]">Estado</dt>
            <dd class="text-[#FFFFFF]">{{ $status }}</dd>
        </div>
    </dl>

    <div class="mt-5 border-t border-dashed border-[#9CACAD]/30 pt-5">
        <div class="flex items-end justify-between gap-4">
            <div class="barcode-line">
                @php $bars = [3, 1, 2, 1, 4, 2, 1, 3, 1, 4, 2, 3, 1, 2, 4, 1]; @endphp
                @foreach ($bars as $weight)
                    <span style="width: {{ $weight }}px;"></span>
                @endforeach
            </div>
            <span class="font-classified text-[0.65rem] tracking-[0.32em] text-[#9CACAD]">CREDENCIAL</span>
        </div>
        <p class="mt-3 font-classified text-[0.7rem] tracking-[0.32em] text-[#9CACAD]">SOLO USO INTERNO</p>
    </div>
</aside>

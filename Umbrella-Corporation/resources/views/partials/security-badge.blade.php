@props([
    'level' => 'NOMINAL',
    'icon' => null,
])

@php
    $key = strtolower(trim(explode('/', $level)[0] ?? 'nominal'));
    $map = [
        'critical' => ['class' => 'badge-critical', 'icon' => 'alert-triangle', 'label' => 'CRÍTICO'],
        'biohazard' => ['class' => 'badge-critical', 'icon' => 'biohazard', 'label' => 'BIOHAZARD'],
        'restricted' => ['class' => 'badge-restricted', 'icon' => 'lock', 'label' => 'RESTRINGIDO'],
        'nominal' => ['class' => 'badge-nominal', 'icon' => 'circle-check', 'label' => 'NOMINAL'],
        'standby' => ['class' => 'badge-standby', 'icon' => 'activity', 'label' => 'EN ESPERA'],
        'classified' => ['class' => 'badge-classified', 'icon' => 'eye', 'label' => 'CLASIFICADO'],
        'clear' => ['class' => 'badge-clear', 'icon' => 'circle-check', 'label' => 'LIBRE'],
    ];
    $config = $map[$key] ?? $map['nominal'];
    $iconName = $icon ?? $config['icon'];
    $displayLabel = $config['label'];
    if (str_contains($level, '/')) {
        $parts = array_map(static fn ($p) => strtoupper(trim($p)), explode('/', $level));
        $translated = array_map(static fn ($p) => $map[strtolower($p)]['label'] ?? $p, $parts);
        $displayLabel = implode(' / ', $translated);
    }
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $config['class']]) }}>
    <x-dynamic-component :component="'tabler-' . $iconName" class="size-3.5" aria-hidden="true" />
    <span>{{ $displayLabel }}</span>
</span>

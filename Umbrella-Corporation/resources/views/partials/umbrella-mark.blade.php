@props([
    'size' => 48,
    'invert' => false,
])

<span
    {{ $attributes->merge(['class' => 'umbrella-mark']) }}
    style="width: {{ $size }}px; height: {{ $size }}px;"
    aria-hidden="true"
>
    <img
        src="{{ asset('images/umbrella-logo.webp') }}"
        alt=""
        width="{{ $size }}"
        height="{{ $size }}"
        loading="lazy"
        decoding="async"
        @class(['umbrella-mark__img', 'umbrella-mark__img--invert' => $invert])
    />
</span>

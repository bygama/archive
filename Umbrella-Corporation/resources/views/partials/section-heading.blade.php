@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'align' => 'left',
])

<div
    {{ $attributes->merge([
        'class' => 'flex flex-col gap-3 max-w-3xl ' . ($align === 'center' ? 'mx-auto text-center items-center' : ''),
    ]) }}
>
    @if ($eyebrow)
        <span class="section-heading-eyebrow" data-animate="fade-up">{{ $eyebrow }}</span>
    @endif

    <h2 class="text-[#FFFFFF]" data-animate="fade-up">{{ $title }}</h2>

    @if ($description)
        <p class="text-[#9CACAD] text-base leading-relaxed" data-animate="fade-up">{{ $description }}</p>
    @endif
</div>

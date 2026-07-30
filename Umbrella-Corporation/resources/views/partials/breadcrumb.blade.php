@props([
    'items' => [],
])

<nav aria-label="Breadcrumb" class="font-classified text-[0.7rem] tracking-[0.24em] text-[#5D6E6E]">
    <ol class="flex flex-wrap items-center gap-2">
        @foreach ($items as $index => $item)
            @php $isLast = $loop->last; @endphp
            <li class="flex items-center gap-2">
                @if (! empty($item['url']) && ! $isLast)
                    <a href="{{ $item['url'] }}" class="transition-colors hover:text-[#ED1C24]">{{ $item['label'] }}</a>
                @else
                    <span class="text-[#FFFFFF]">{{ $item['label'] }}</span>
                @endif

                @unless ($isLast)
                    <span class="text-[#5D6E6E]" aria-hidden="true">/</span>
                @endunless
            </li>
        @endforeach
    </ol>
</nav>

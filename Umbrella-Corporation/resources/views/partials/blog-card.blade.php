@props([
    'post',
])

<article
    class="log-card"
    data-stagger-item
>
    <header class="log-card__head">
        <span class="log-card__category">
            <x-dynamic-component
                :component="'tabler-' . $post['icon']"
                class="size-4 text-[#ED1C24]"
                aria-hidden="true"
            />
            {{ strtoupper($post['category']) }}
        </span>
        @include('partials.security-badge', ['level' => $post['security']])
    </header>

    <div class="log-card__body">
        <h3 class="log-card__title">
            <a href="{{ route('blog.show', $post['slug']) }}">
                {{ $post['title'] }}
            </a>
        </h3>
        <p class="log-card__excerpt">{{ $post['excerpt'] }}</p>
    </div>

    <footer class="log-card__footer">
        <dl class="log-card__meta">
            <div>
                <dt>FECHA</dt>
                <dd>{{ $post->published_at?->format('Y-m-d') }}</dd>
            </div>
            <div>
                <dt>ID&nbsp;DOC</dt>
                <dd>{{ $post['document_id'] }}</dd>
            </div>
        </dl>

        <a href="{{ route('blog.show', $post['slug']) }}" class="log-card__cta">
            Abrir Archivo
            <x-tabler-arrow-up-right class="size-3.5" aria-hidden="true" />
        </a>
    </footer>
</article>

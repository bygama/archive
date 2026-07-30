@if (session('status'))
    <div class="admin-flash" role="status">
        <x-tabler-circle-check class="size-5" aria-hidden="true" />
        <p>{{ session('status') }}</p>
    </div>
@endif

@if (session('preview'))
    <div class="admin-flash admin-flash--preview" role="status">
        <x-tabler-info-circle class="size-5" aria-hidden="true" />
        <p>{{ session('preview') }}</p>
    </div>
@endif

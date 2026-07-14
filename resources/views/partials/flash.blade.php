@if (session('status'))
    <div class="admin-flash" role="status">
        <x-tabler-circle-check class="size-5" aria-hidden="true" />
        <p>{{ session('status') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="admin-flash admin-flash--preview" role="alert">
        <x-tabler-alert-triangle class="size-5" aria-hidden="true" />
        <p>{{ session('error') }}</p>
    </div>
@endif

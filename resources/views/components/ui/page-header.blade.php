@props([
    'title',
])

<div class="page-heading">
    <h1 class="page-title">{{ $title }}</h1>

    @isset($actions)
        <div class="page-toolbar">
            {{ $actions }}
        </div>
    @endisset
</div>

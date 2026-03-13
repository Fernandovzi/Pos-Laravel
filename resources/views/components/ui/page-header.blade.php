@props([
    'title',
    'subtitle' => null,
])

<div class="page-heading">
    <div>
        <h1 class="page-title">{{ $title }}</h1>
        @if($subtitle)
            <p class="page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="page-toolbar">
            {{ $actions }}
        </div>
    @endisset
</div>

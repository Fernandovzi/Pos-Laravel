@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<section {{ $attributes->merge(['class' => 'ui-card']) }}>
    @if($title || $subtitle)
        <header class="ui-card__header">
            @if($title)
                <h2 class="ui-card__title">{{ $title }}</h2>
            @endif
            @if($subtitle)
                <p class="ui-card__subtitle">{{ $subtitle }}</p>
            @endif
        </header>
    @endif

    <div class="{{ $padding ? 'ui-card__body' : '' }}">
        {{ $slot }}
    </div>
</section>

@props([
    'title' => null,
    'description' => null,
])

<section class="ui-form-section" {{ $attributes }}>
    @if($title)
        <h3 class="ui-form-section__title">{{ $title }}</h3>
    @endif
    @if($description)
        <p class="ui-form-section__description">{{ $description }}</p>
    @endif
    <div class="row g-4">
        {{ $slot }}
    </div>
</section>

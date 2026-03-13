@props([
    'variant' => 'primary',
    'type' => 'button',
    'icon' => null,
])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'ui-btn ui-btn--' . $variant]) }}>
    @if($icon)
        <i class="{{ $icon }}"></i>
    @endif
    <span>{{ $slot }}</span>
</button>

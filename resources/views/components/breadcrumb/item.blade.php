@props([
'active' => false,
'href' => null,
'content'
])

<li {{ $attributes->merge(['class' => 'breadcrumb-item ' . ($active ? 'active' : '')]) }} @if($active) aria-current="page" @endif>
    @if ($href)
    <a href="{{ $href }}" class="breadcrumb-link">
        {{ $content }}
    </a>
    @else
    {{ $content }}
    @endif
</li>

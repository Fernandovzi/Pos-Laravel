@props(['items' => []])

<nav aria-label="breadcrumb" class="ui-breadcrumbs">
    <ol class="breadcrumb mb-0">
        @foreach($items as $item)
            <li class="breadcrumb-item {{ !empty($item['active']) ? 'active' : '' }}" @if(!empty($item['active'])) aria-current="page" @endif>
                @if(empty($item['active']) && !empty($item['href']))
                    <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                @else
                    {{ $item['label'] }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>

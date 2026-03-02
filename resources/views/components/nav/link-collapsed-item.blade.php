<a class="nav-link {{ request()->url() == $href ? 'active' : '' }}" href="{{ $href }}">
    {{$content}}
</a>
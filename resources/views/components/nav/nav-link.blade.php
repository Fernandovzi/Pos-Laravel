<a class="nav-link {{ request()->url() == $href ? 'active' : '' }}" href="{{ $href }}">
    <div class="sb-nav-link-icon"><i class="{{$icon}}"></i></div>
    {{$content}}
</a>
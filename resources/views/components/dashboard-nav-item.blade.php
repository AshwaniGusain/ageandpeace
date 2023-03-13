<li class="nav-item {{ Request::url() === $url ? 'active' : '' }} {{ $class ?? '' }}">
    <div class="color-block"></div>

    <a href="{{ $url }}" class="nav-link">{{ $slot }}</a>
</li>

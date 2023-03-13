<a href="{{ $href }}"
   class="btn btn-arrow btn-primary {{ $classes ?? '' }}">
    {{ $slot }}
    @svg('assets/svg/icon_arrow.svg', 'icon')
</a>

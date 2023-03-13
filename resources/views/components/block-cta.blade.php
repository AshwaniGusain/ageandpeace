<div class="block-cta {{ $class ?? ''}}">
    <a href="{{ $link ?? '#' }}" class="bg-link"><span class="sr-only">{{ $copy }} link</span></a>
    <div class="block-cta-content">
        @if(isset($icon))
            <div class="block-cta-icon">
                {{ $icon }}
            </div>
        @endif

        <div>
            <h4 class="mb-0">
                <a href="{{ $link ?? '#' }}" class="link-arrow text-inherit">
                    {{ $header }}
                    <span class="icon d-inline-block">@svg('assets/svg/icon_arrow.svg', 'icon') </span>
                </a>
            </h4>

            @if(isset($copy))
                <p class="small mb-0">{{ $copy }}</p>
            @endif
        </div>
    </div>
</div>

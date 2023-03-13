@if ($header || !empty($img->src) || $body || $title || $text || $button || $list->items->count() || $footer)
<div class="{{ $class }} card">

    @if (!empty($header))
        <div class="card-header">
            {!! $header !!}
        </div>
    @endif

    @if (!empty($img->src))
        <div class="p-3 text-center">
            {!! $img->render() !!}
        </div>
    @endif
    @if ($body || $title || $text || $button)
    <div class="card-body">

        @if (!empty($body))
            {!! $body !!}
        @endif

        @if (!empty($title))
            <h5 class="card-title">{{ $title }}</h5>
        @endif

        @if (!empty($text))
            <p class="card-text">{!! $text !!}</p>
        @endif

        @if (!empty($button))
            {!! $button->render() !!}
        @endif
    </div>
    @endif
    @if ($list->items->count())
        {!! $list->setFlush(true)->render() !!}
    @endif

    @if (!empty($footer))
        <div class="card-footer">
            {!! $footer !!}
        </div>
    @endif

</div>
@endif
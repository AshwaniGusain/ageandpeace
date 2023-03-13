@if(isset($type) && $type === 'form')
<form method="{{ $method ?? 'POST' }}" action="{{ $action }}">
@endif

    @csrf
    <div class="card card-modal border-0">
        <div class="card-body p-6">
            {{ $slot }}
        </div>

        <div class="card-footer p-0">
            @if(isset($type) && $type === 'form')
                <button type="submit" class="btn btn-arrow btn-block btn-primary">
                    {{ $buttonText }}
                    @svg('assets/svg/icon_arrow.svg', 'icon')
                </button>
            @else
                <a href="{{ $href }}" class="btn btn-arrow btn-block btn-primary">
                    {{ $buttonText }}
                    @svg('assets/svg/icon_arrow.svg', 'icon')
                </a>
            @endif

        </div>
    </div>

@if(isset($type) && $type === 'form')
</form>
@endif

@if ($input->hasData('no_template'))
    {{ $slot }}
@elseif ($input->isInline())
    <div class="form-check">
        <label class="form-check-label">

            {{ $slot }}

            <div class="text-danger">
                @foreach ($input->getErrors() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>

        </label>
    </div>
@else
    @if ($input->getLabel()->isVisible() && $input->isVisible())
        <div class="form-label-col">
            {!! $input->getLabel()->render() !!}
        </div>
    @endif

    <div class="form-input-col">

        <?php if ($input->hasError() && $input instanceof \Snap\Form\Contracts\AttrsInterface) : $input->appendAttr('class', 'form-control is-invalid'); endif; ?>

        {{ $slot }}

        @if ($input->hasError())
            <ul class="error text-danger">
                @foreach ($input->getErrors() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
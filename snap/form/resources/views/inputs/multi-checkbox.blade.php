@component('form::element', ['input' => $self])
    <div class="multi-checkbox-field">
        @if (!empty($checkboxes))
            @foreach ($checkboxes as $checkbox)
                @if ($checkbox instanceof \Snap\Form\Inputs\Checkbox)
                    <div>
                        {!! $checkbox !!}
                    </div>
                @else
                    <strong>{!! $checkbox !!}</strong>
                @endif
            @endforeach
        @else
            <div>{{ trans('form::inputs.no_items') }}</div>
        @endif
    </div>
@endcomponent
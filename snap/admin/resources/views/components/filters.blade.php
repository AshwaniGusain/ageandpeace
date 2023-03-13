@if (!empty($groups))
<snap-resource-filters inline-template>
    <div id="snap-resource-filters" class="collapse{{ $show ? ' show' : '' }}">
        <div class="form-wrapper">

            @foreach ($groups as $group)

            <div class="form-row">

                @foreach ($group as $i => $input)
                <div class="col">
                    <div class="form-group">
                        {!! $input->render() !!}
                    </div>
                </div>
               @endforeach

            </div>

            @endforeach

            <div class="buttons">
                @foreach ($buttons as $name => $button)
					{!! $button->render() !!}
				@endforeach
            </div>

        </div>
    </div>
</snap-resource-filters>
@endif
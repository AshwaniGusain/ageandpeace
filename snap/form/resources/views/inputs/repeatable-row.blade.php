<snap-repeatable-row ref="row" class="list-group-item repeatable-row" :data-depth="depth" inline-template>
    <div :data-index="index">
        <div class="grabber ui-sortable-handle"></div>

        <div class="text-right"><a href="javascript:;" class="text-secondary repeatable-remove" @click="remove()" :index="index"><i class="fa fa-remove"></i></a></div>

        @if ( ! empty($row_label))
        <div class="form-group repeat-header" @click="toggleRowDisplay()">
            <div class="input-row">
                <i class="toggle-indicator fa" :class="displayed ? 'fa-toggle-up' : 'fa-toggle-down'"></i>
                <span v-cloak>{{ $row_label }}</span>
            </div>
        </div>
        @endif

        <div class="repeatable-inputs" v-show="displayed">
            <?php $hidden = []; ?>
            @foreach($inputs as $input)
                @if ($input->type == 'hidden')
                    $hidden[] = $input;
                    continue;
                @endif
{{--            <div class="form-group">--}}

{{--                <div class="input-row">--}}

{{--                    @if ($input->isInline())--}}
{{--                    <div class="form-check">--}}
{{--                        <label class="form-check-label" data-input-depth="{{ $input->getAttr('data-input-depth') }}"{!!  ($comment = $input->getComment()) ? ' data-toggle="tooltip" title="'.$comment.'"' : '' !!}>--}}

{{--                            {!! $input->render() !!}--}}


{{--                        </label>--}}
{{--                    </div>--}}
{{--                    @else
                    <div class="form-label-col">--}}

{{--                        {!! $input->getLabel()->setAttr('data-input-depth', $input->getAttr('data-input-depth'))->render() !!}
                    </div>
                    <div class="form-input-col">--}}
                        <?php  $input->getLabel()->setAttr('data-input-depth', $input->getAttr('data-input-depth')) ?>
                        {!! $input->render() !!}

                    {{--             </div>
                               @endif--}}

{{--                </div>--}}
{{--            </div>--}}

            @endforeach

            @foreach ($hidden as $h)
			{!! $h->render() !!}
			@endforeach

        </div>

    </div>

</snap-repeatable-row>
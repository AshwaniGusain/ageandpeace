@if ($others->count())
    <snap-others-navigation inline-template>
        <select id="snap-others" class="form-control" name="snap-others" ref="others" @change="go()" v-model="others">
            <option value="">{{ trans('admin::resources.others_navigation') }}</option>
            @foreach ($others as $key => $val)
                <option value="{{ $key }}"{{ $current == $key ? ' disabled' : ''}}>{{ $val }}</option>
            @endforeach
        </select>
    </snap-others-navigation>
@endif

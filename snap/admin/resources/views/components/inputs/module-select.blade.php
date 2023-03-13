@if ($hide_if_one && count($options) < 2)
    <input type="hidden" name="{{ $attrs['name'] }}" value="{{ $value }}">
@else
@component('form::element', ['input' => $self])
<?php $value = isset($value) ? (array) $value : []; ?>
    @if ($module_url)
        <snap-create-edit module-url="{{ $module_url }}" active-url="{{ $active_url }}" url-params="{{ $url_params }}">
    @endif
{{--    <div class="row">--}}
{{--        <div class="col-md-9">--}}

            {{-- This is used for multiple selects when none are selected, something still gets posted --}}
            @if (!empty($attrs['multiple']))
            <input type="hidden" name="{{ $attrs['name'] }}" value="">
            @endif
            <select{!! html_attrs($attrs) !!}>
            @foreach ($options as $key => $val)
                @if (is_array($val))
                <optgroup label="{{ $key }}">
                    @foreach ($val as $k => $v)
                    <option value="{{ $k }}"{{ in_array($k, $value) ? ' selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </optgroup>
                @else
                <option value="{{ $key }}"{{ in_array($key, $value) ? ' selected' : '' }}>{{ $val }}</option>
                @endif
            @endforeach
            </select>
{{--        </div>--}}
{{--        <div class="col-md-3">--}}
{{--            <div class="btn-group mt-1">--}}
{{--                <a :href="editUrl" @click.prevent="showModal(editUrl, false)" class="btn btn-secondary btn-sm edit-inline-button border" :class="{ disabled: editStatus }"><i class="fa fa-pencil"></i></a>--}}
{{--                <a :href="createUrl" @click.prevent="showModal(createUrl, true)" class="btn btn-secondary btn-sm add-inline-button border"><i class="fa fa-plus"></i></a>--}}
{{--            </div>--}}
{{--            <snap-create-edit module-url="{{ $module_url }}" active-url="{{ $active_url }}" url-params="{{ $url_params }}"></snap-create-edit>--}}
{{--        </div>--}}
{{--    </div>--}}
    @if ($module_url)
        </snap-create-edit>
    @endif
@endcomponent
@endif

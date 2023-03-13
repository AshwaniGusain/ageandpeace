@if ($hide_if_one && count($options) < 2)
    <input type="hidden" name="{{ $attrs['name'] }}" value="{{ $value }}">
@else
@component('form::element', ['input' => $self])
<?php
$disabled_options = !empty($disabled_options) ? (array) $disabled_options : [];
$value = isset($value) ? (array) $value : []; ?>
@if (!empty($attrs['multiple']))
<input type="hidden" name="{{ $attrs['name'] }}" value="">
@endif
<select{!! html_attrs($attrs) !!}>
@foreach ($options as $key => $val)
    @if (is_iterable($val))
    <optgroup label="{{ $key }}">
        @foreach ($val as $k => $v)
        <option value="{{ $k }}"{{ in_array($k, $value) ? ' selected' : '' }}{{ in_array($k, $disabled_options) ? ' disabled' : '' }}>{{ $v }}</option>
        @endforeach
    </optgroup>
    @else
    <option value="{{ $key }}"{{ in_array($key, $value) ? ' selected' : '' }}{{ in_array($key, $disabled_options) ? ' disabled' : '' }}>{{ $val }}</option>
    @endif
@endforeach
</select>
@endcomponent
@endif

@if ($hide_if_one && count($options) < 2)
    <input type="hidden" name="{{ $attrs['name'] }}" value="{{ $value }}">
@else
@component('form::element', ['input' => $self])
<snap-dual-multi-select-input{!! html_attrs($attrs) !!} :options="{{ json_encode($options, JSON_FORCE_OBJECT) }}" :value="{{ json_encode($value) }}"></snap-dual-multi-select-input>
@endcomponent
@endif
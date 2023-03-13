@component('form::element', ['input' => $self])
<textarea{!! html_attrs($attrs) !!}>{{ $value }}</textarea>
@endcomponent
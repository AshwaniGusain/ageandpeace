@component('form::element', ['input' => $self])
<snap-time-input id="{{ $id }}" :display-seconds="{{ $display_seconds }}" placeholder-hr="{{ $placeholder_hr }}" placeholder-min="{{ $placeholder_min }}" placeholder-sec="{{ $placeholder_sec }}"></snap-time-input>
@endcomponent
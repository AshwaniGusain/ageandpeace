@component('form::element', ['input' => $self])
    <snap-toggle-input {!! html_attrs($attrs) !!}></snap-toggle-input>
    {!! $self->getLabel()->setUseTag(false)->render() !!}
@endcomponent
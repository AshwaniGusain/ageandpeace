<div class="custom-control custom-switch">
    @if ($enforce_if_empty)
    <input type="hidden" name="{{ $attrs['name'] }}" value="0">
    @endif
    <input{!! html_attrs($attrs) !!}>
    <label class="custom-control-label" for="{{ $self->getId() }}">
        {!! $self->getLabel()->setUseTag(false)->render() !!}
    </label>
</div>
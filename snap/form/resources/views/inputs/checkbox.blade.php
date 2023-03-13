<div class="form-check">
<label>
@if ($enforce_if_empty)
    <input type="hidden" name="{{ $attrs['name'] }}" value="0">
@endif
<input{!! html_attrs($attrs) !!}>
{!! $self->getLabel()->setUseTag(false)->render() !!}
</label>
</div>
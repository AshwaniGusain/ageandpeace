<div class="form-label-col">
    <label for="{{ $self->getId() }}"{!! ($comment = $self->getComment()) ? ' data-toggle="tooltip" title="'.$comment.'"' : '' !!}>
        {{ $self->getLabel()->getText() }}:
    </label>

    {!! $self->getDisplayValue() !!}
</div>
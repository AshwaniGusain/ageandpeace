<div class="form-check">
    <label class="form-check-label">

        <input{!! html_attrs($attrs) !!}>
        {!! $self->getLabel()->setUseTag(false)->render() !!}

        <div class="text-danger">
            @foreach ($self->getErrors() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>

    </label>
</div>
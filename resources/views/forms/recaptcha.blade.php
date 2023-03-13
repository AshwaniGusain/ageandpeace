{!!  htmlFormSnippet() !!}

@if ($self->hasError())
    <ul class="error text-danger">
        @foreach ($self->getErrors() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

@component('form::element', ['input' => $self])
<div class="row">
    <div class="col">
        {!! $input1 !!}
    </div>
    <div style="margin-top: 5px">{{ $divider }}</div>
    <div class="col">
        {!! $input2 !!}
    </div>
</div>
@endcomponent
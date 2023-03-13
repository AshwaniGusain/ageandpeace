<{{ $tag }} class="list-group{{ $flush ? ' list-group-flush' : '' }}">
@foreach($items as $item)

    <{{ $item_tag }} class="list-group-item @if ($active) active @endif">
    {!! $item !!}
    </{{ $item_tag }}>

@endforeach
</{{ $tag }}>
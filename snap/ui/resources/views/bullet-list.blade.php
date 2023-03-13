<ul class="{{ $class }}">
@foreach($items as $item)

    <li class="{{ $item_class }}">
    {!! $item !!}
    </li>

@endforeach
</ul>
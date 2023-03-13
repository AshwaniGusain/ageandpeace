@if (!empty($options))
@if (!empty($container))
<div class="dropdown {{ $class }}"{!!  ($id) ? ' id="' . $id . '"' : '' !!}>
@endif
    <button class="btn btn-{{ $size }} btn-{{ $btn_class }} dropdown-toggle" type="button" id="{{ $id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         {!! $label !!}
    </button>

    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $id }}">
    @foreach ($options as $key => $val)
        @if (strpos($val, '--') === 0)
        <li role="separator" class="divider"></li>
        @elseif (is_int($key))
        <li>{!! $val !!}</li>
        @else
        <li><a href="{{ $key }}" class="dropdown-item dropdown-action-{{ strtolower(str_replace(' ', '-', $val)) }}">{{ $val }}</a></li>
        @endif
    @endforeach
    </ul>
@if (!empty($container))
</div>
@endif
@endif
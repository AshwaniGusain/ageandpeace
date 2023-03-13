@extends('admin::module.resource.index')

@section('main')

    <div id="snap-main-display" class="snap-main-display-with-actions">

        @if ($module->hasTrait('filters'))
            {!! $filters !!}
        @endif

        @if ($module->hasTrait('scopes'))
            {!! $scopes !!}
        @endif
            
        {!! $listing !!}

    </div>
@endsection
@extends('admin::module.resource.index')

@section('main')

    <div id="snap-main-display" class="snap-main-display-with-actions snap-main-display-with-footer">

        @if ($module->hasTrait('filters'))
            {!! $filters !!}
        @endif

        @if ($module->hasTrait('scopes'))
            {!! $scopes !!}
        @endif

        {!! $table->render() !!}

    </div>
    <div id="snap-main-footer" class="bg-light">

        @if ($table->pagination)

            @include('admin::components.pagination-limits', ['items' => $table, 'limit' => $table->limit])

        @endif
    </div>
@endsection
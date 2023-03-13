<div class="resource-grid-container">
    @if ($has_data)
        <div class="resource-grid-items m-4">
            @foreach($items->chunk($cols) as $row)
                <div class="row">
                    @foreach($row as $item)
                        <div class="col col-{{ floor(12/$cols) }}">
                            {!! $module->service('grid')->renderItem($item); !!}
                        </div>
                    @endforeach
                </div>
            @endforeach

        </div>
    @else
        <div>
            <div class="p-4 text-center">{{ trans('admin::resources.no_data') }}</div>
        </div>
    @endif
</div>

@if (!empty($groups))
    @foreach ($groups as $group)
        @if (isset($group['group']))
            <div class="resource-list-group">
                <h3 class="mb-0">
                    <span id="group-{{ $group['group']->id }}" data-refresh-selector="#name">{{ $group['group']->display_name }}</span>
                    @if ($group_module)
                        <a href="{{ $group_module->url('edit_inline', ['id' => $group['group']->id]) }}" class="btn btn-sm p-0" is="snap-modal-link" refresh-selector="#group-{{ $group['group']->id }}"><i class="fa fa-edit"></i></a>
                    @endif
                </h3>
            </div>
        @endif
        <div class="resource-list-container" is="snap-hierarchical-listing" update-url="{{  $module->url($update_uri) }}" :sortable="{{ ($sortable) ? 'true' : 'false' }}" :nesting-depth="{{ $nesting_depth }}" inline-template>
            <div class="resource-list-items">
                {!! $group['listing']->render() !!}
            </div>
        </div>
    @endforeach
@else
    <div>
        <div class="p-4 text-center">{{ trans('admin::resources.no_data') }}</div>
    </div>
@endif
<snap-map-list :init-items='{!! \Snap\Support\Helpers\HtmlHelper::encodeJsonAttribute($items, JSON_FORCE_OBJECT) !!}' class="container-fluid mt-3" inline-template>
    <div id="snap-resource-map" class="row">
        @if (!empty($items))
            <div class="col-md-3">
                <div style="height: 381px; overflow: auto;">
                    <ul class="list-group">
                        @foreach($items as $item)
                            <li class="list-group-item clearfix">
                                <div class="form-check">
                                    <input type="checkbox" @click="$parent.toggleMultiSelect()" value="{{ $item->id }}" data-id="{{ $item->id }}" data-display-name="{{ e($item->display_name) }}" class="form-check-input multiselect">
                                    <a href="{{ $module->url('edit', ['resource' => $item->id]) }}">{{ $item->display_name }}</a>
                                    @if ($item->has_lat_and_lng)
                                        <a @click="edit({{ $item->id }})" style="position: absolute; top: 0px; right: -5px; display: block" href="javascript:;"><i class="fa fa-map-marker"></i></a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    {{--<div class="text-center">{{ $pagination }}</div>--}}
                </div>
            </div>
            <div class="col-md-9">
                <snap-map ref="map" url="{{ $map_url }}"{!! !empty($latitude) ? ' :latitude="'.$latitude.'"' : '' !!}{!! !empty($longitude) ? ' :longitude="'.$longitude.'"' : '' !!} :zoom="{{ $zoom }}" width="100%" height="380px"></snap-map>
            </div>

            {{--<script type="text/x-template" ref="mapitems">{!! json_encode($items) !!}</script>--}}
        @else
            <div class="p-4 text-center">{{ trans('admin::resources.no_data') }}</div>
        @endif

    </div>
</snap-map-list>
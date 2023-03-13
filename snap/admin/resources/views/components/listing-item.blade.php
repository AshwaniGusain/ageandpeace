<div data-id="{{ $item->id }}" key="item-{{ $item->id }}">
	@if ( ! empty($sortable))
	<div class="grabber"></div>
	@endif
	<div class="list-group-item-select float-left">
		<label class="c-input c-checkbox">
			<input type="checkbox" @click="$parent.toggleMultiSelect()" value="{{ $item->id }}" data-display-name="{{ e($item->display_name) }}" class="multiselect">
		{{--<input type="checkbox" name="multi[{{ $item->id }}]" data-id="{{ $item->id }}" data-display-name="{{ e($item->display_name) }}" class="multi">--}}
		<span class="c-indicator"></span>
		<span class="sr-only">Select</span>
		</label>
	</div>
	@if ($item->getAttribute('active') )
	{{--<div class="list-group-item-status">
		@if ($item->is_active)
		<span class="icon-status icon-active"><span class="sr-only">Active</span></span>
		@else
		<span class="icon-status icon-inactive"><span class="sr-only">Inactive</span></span>
		@endif
	</div>--}}
	@endif

	<a href="{{ $module->url('edit', ['resource' => $item]) }}" class="list-group-item-details">
		<div class="list-group-item-heading">{{ $item->display_name }}</div>
		@if (method_exists($item, 'getMetaInfo'))
		<p class="list-group-item-text list-group-item-meta">{!! $item->getMetaInfo() !!}</p>
		@endif
	</a>

</div>
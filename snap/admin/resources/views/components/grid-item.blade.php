<div class="card mt-4">
	<div class="card-body">
		<div class="text-center">

			@if ($item instanceof \Snap\Admin\Models\Contracts\DisplayImageInterface)
				<div class="grid-image mb-2" style="background-image: url({{ (is_object($item->display_image) && method_exists($item->display_image, 'getUrl')) ? $item->display_image->getUrl() : $item->display_image }})">
					<a href="{{ $module->url('edit', ['resource' => $item->id])}}" class="d-block" style="min-height: 200px;"></a>
				</div>
			@endif
			<input type="checkbox" @click="toggleMultiSelect()" value="{{ $item->id }}" data-display-name="{{ e($item->display_name) }}" class="form-check-input multiselect">
			<a href="{{ $module->url('edit', ['resource' => $item->id])}}">{{ $item->display_name }}</a>

		</div>
	</div>
</div>
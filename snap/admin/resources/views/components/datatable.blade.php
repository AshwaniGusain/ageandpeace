<div id="{{ $table->getId() }}-wrapper" class="datatable-wrapper table-responsive">

	<snap-data-table url="{{ $url }}"<?php if ($inline) : ?> :inline="{{ $inline }}"<?php endif; ?> inline-template>

		<div>
			@if ($has_data)
				{!! $table->render() !!}
			@else
				<div class="p-4 text-center">{{ trans('admin::resources.no_data') }}</div>
			@endif
		</div>

	</snap-data-table>

</div>
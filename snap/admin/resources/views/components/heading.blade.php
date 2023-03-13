<header id="snap-module-title" <?php if ( ! empty($modal)) : ?> class="modal-header"<?php endif; ?>>

	{!! $preview_button !!}

	<div class="btn-toolbar float-left">
		<div class="btn-group">

			@if ( !is_null($back) && $back !== false)
				<a href="{{ $back }}" class="btn btn-dark border float-left">
					<i class="fa fa-chevron-left"></i>
				</a>
			@endif

			@if ( ! empty($create) && $module->hasPermission('create'))
				<a href="{{ $module->url('create') }}" class="btn btn-primary border float-left">
					@if ( ! empty($icon))
						<i class="{{ $icon }}"></i>
					@endif
					<i class="fa fa-plus"></i>
				</a>
			@elseif ( ! empty($icon))
				<div class="snap-module-icon btn bg-dark border float-left">
					<i class="{{ $icon }}"></i>
				</div>
			@endif
		</div>

		<h1>
			{{ $title }}
		</h1>
	</div>

	@if ( ! empty($modal))
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
	@endif
</header>
@extends('admin::layouts.admin')

@section('body')
<div is="snap-resource-index" ref="resource-index" module-url="<?=$module->url()?>"  module-uri="<?=$module->uri()?>" inline-template>

	<div id="snap-resource-index">

		<form id="snap-form" class="form" action="" method="get">

			{!! $heading !!}

			@if ($buttons->buttons->count() || $module->hasTrait('search') || $dropdown->options->count())
			<nav id="snap-actions" class="navbar navbar-light bg-light justify-content-between">

				{!! $buttons !!}

				<div id="snap-actions-right" class="form-inline">

					@if ($module->hasTrait('search'))
					<div id="snap-resource-search" class="input-group" role="group">
						{!! $search !!}
					</div>
					@endif

					<div id="snap-resource-dropdown" class="btn-group ml-3" role="group">
						{!! $dropdown !!}
					</div>

				</div>
			</nav>
			@endif

			@yield('main')

		</form>

	</div>

</div>
@endsection('body')
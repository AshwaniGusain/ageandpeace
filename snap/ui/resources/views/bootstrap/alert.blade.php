<div<?php if ( ! empty($id)) : ?>id="<?=$id?>"<?php endif; ?> class="alert alert-{{ ($type == 'error') ? 'danger' : $type }}" role="alert">
	@if ($dismissable)
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	@endif

	@if ($ref_id)
	<a href="#{{ $ref_id }}" class="text-{{ ($type == 'error') ? 'danger' : $type }}">{!! $text !!}</a>
	@else
	{!! $text !!}
	@endif
</div>
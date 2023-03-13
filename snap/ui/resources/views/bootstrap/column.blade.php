<div class="{{ $class }}"{!! html_attrs(['id' => $id]) !!}>
	@if ($content instanceof \Snap\Support\Contracts\ToString)
	{!! $content->render() !!}
	@else
	{!! $content !!}
	@endif
</div>

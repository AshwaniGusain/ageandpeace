<div class="row {{ $class }}">
	@foreach($cols as $col)
		{!! $col->render() !!}
	@endforeach
</div>

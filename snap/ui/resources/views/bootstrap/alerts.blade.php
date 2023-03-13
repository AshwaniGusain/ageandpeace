@if (!empty($alerts))
<div class="alerts">
	@foreach ($alerts as $type => $typeAlerts)
		@foreach ($typeAlerts as $alert)
			{!! $alert !!}
		@endforeach
	@endforeach
</div>
@endif
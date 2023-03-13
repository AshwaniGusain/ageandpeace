<div class="container-fluid {{ $class }}"{!! html_attrs(['role' => $role, 'id' => $id]) !!}>
@foreach($rows as $row)
	{!! $row->render() !!}
@endforeach
</div>
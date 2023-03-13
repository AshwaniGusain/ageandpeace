@extends(($inline) ? 'admin::layouts.admin-inline' : 'admin::layouts.admin' )


@section('body')
<div id="snap-resource">

	{!! $heading !!}

	@yield('main')

</div>

@endsection('body')
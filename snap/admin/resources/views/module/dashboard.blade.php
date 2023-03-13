@extends(($inline) ? 'admin::layouts.admin-inline' : 'admin::layouts.admin' )


@section('body')
    <div>

        <div class="dashboard-grid">
            {!! $grid->render() !!}
        </div>

    </div>
@endsection('body')

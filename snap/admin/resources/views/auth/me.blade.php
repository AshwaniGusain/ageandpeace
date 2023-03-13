@extends(($inline) ? 'admin::layouts.admin-inline' : 'admin::layouts.admin' )


@section('body')
    <div>

        <form id="snap-form" class="form" action="" method="post" enctype="multipart/form-data">

            {!! $heading !!}

            <div id="snap-main-display" class="fuel-main-display-with-footer">

                {!! $alerts !!}

                <div class="container-fluid" style="padding: 1.25rem">
                    <div class="row">
                        <div class="col-9">
                            {!! $form->render() !!}
                        </div>
                    </div>
                </div>

            </div>

            <div id="snap-main-footer" class="bg-light">

                <div class="btn-toolbar bg-light">
                    {{ $buttons }}
                </div>

            </div>

            <input type="hidden" name="__redirect__" id="__redirect__" value="">


            {{ method_field('PATCH') }}
            {{ csrf_field() }}
        </form>

    </div>
@endsection('body')

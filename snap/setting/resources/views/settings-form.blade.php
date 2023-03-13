@extends('admin::layouts.admin' )

@section('body')

    {!! $heading !!}

    <form id="snap-form" class="form form-{{ $module->handle() }}" ref="form" action="" method="post" enctype="multipart/form-data">
        <div id="snap-main-display" class="snap-main-display-with-footer">

            {!! $alerts !!}

            <div class="container-fluid" style="padding: 1.25rem">
                <div class="row">
                    <div class="col-12">
                        {!! $form->render() !!}
                    </div>
                </div>
            </div>
        </div>

        <div id="snap-main-footer" class="bg-light">

            <div class="btn-toolbar bg-light">
                <input type="submit" value="{{ trans('admin::resources.btn_save') }}" class="btn btn-primary">
            </div>

        </div>

        {{ method_field('PATCH') }}
        {{ csrf_field() }}

    </form>

@endsection
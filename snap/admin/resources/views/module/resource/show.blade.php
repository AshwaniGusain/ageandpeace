@extends('admin::module.resource.form')

@section('main')
    <div>
        <div>
            <div id="snap-main-display">

                <div class="container-fluid p-4">
                    <div class="row">
                        <div class="col-md-9">
                            {!! $form->render() !!}
                        </div>
                        <div class="col-md-3" id="snap-resource-related">
                            {!! $related_panel->render() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@extends('admin::module.resource.form')

@section('main')

    <snap-inline inline-template>
            <div>

                <div id="snap-main-display" class="snap-main-display-with-footer">

                    <snap-progressbar id="fuel-progress" ref="progressbar"></snap-progressbar>

                    {{ $alerts }}

                    <div class="container-fluid" style="padding: 1.25rem">
                        <div class="row">
                            <div class="col">
                                {{ $form }}
                            </div>

                        </div>
                    </div>
                </div>
                <div id="snap-main-footer" class="modal-footer bg-light" style="padding-left: 1.25rem; padding-right: 1.25rem;">
                    <div class="bg-light" role="toolbar" aria-label="Toolbar">
                        <div class="btn-group mr-2" role="group" aria-label="Save">
                            <input type="submit" name="save" id="btn-close" class="btn btn-primary text-light" value="{{ trans('admin::resources.btn_close') }}">
                        </div>
                    </div>
                </div>

            </div>
    </snap-inline>
@endsection
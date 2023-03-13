@extends('admin::module.resource.form')

@section('main')

    <snap-inline inline-template>
        <div is="{{ $form_component }}" id="snap-resource-form" inline-template>
            <div>

                <form id="snap-form" class="form form-{{ $module->handle() }}" ref="form" action="{{ $module->url('update', ['resource' => $resource]) }}" method="post">

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
                                <input type="submit" name="save" id="btn-save" class="btn btn-primary text-light" value="{{ trans('admin::resources.btn_save') }}">
                            </div>
                            <div class="btn-group mr-2" role="group" aria-label="Save & Close">
                                <input type="submit" name="save_close" id="btn-save-close" @click="$parent.saveAndClose()" class="btn btn-primary text-light" value="{{ trans('admin::resources.btn_save_and_close') }}">
                            </div>
                        </div>
                    </div>

                    @if (\Request::input('__close__'))
                        <input type="hidden" name="__close__" id="__close__" value="1">
                    @endif

                    <input type="hidden" name="__redirect__" id="__redirect__" value="{{ $module->url('edit_inline', $resource) }}">

                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </snap-inline>
@endsection
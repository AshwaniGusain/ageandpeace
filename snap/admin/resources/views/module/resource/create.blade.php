@extends('admin::module.resource.form')

@section('main')

    <div is="{{ $form_component }}" id="snap-resource-form" inline-template>
        <div>

            <form id="snap-form" class="form form-{{ $module->handle() }}" ref="form" action="{{ $module->url('insert') }}" method="post" enctype="multipart/form-data">
                <div id="snap-main-display" class="snap-main-display-with-footer">

                    <?php /* ?><snap-progressbar id="snap-progress" ref="progressbar"></snap-progressbar><?php */ ?>

                    {!! $alerts !!}

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

                <div id="snap-main-footer" class="bg-light">
                    <div class="bg-light" role="toolbar" aria-label="Toolbar">
                        {{ $buttons }}
                    </div>
                </div>

                <input type="hidden" name="__redirect__" id="__redirect__" value="">

                {{ method_field('POST') }}
                {{ csrf_field() }}

                {{ $preview }}

                @if ($module->hasTrait('preview'))
                    {{--<snap-resource-preview url="{{ url('') }}" slug-field="{{ $module->getPreviewSlugInput() }}" id="snap-resource-preview" ref="snap-resource-preview"></snap-resource-preview>--}}
                @endif

            </form>

        </div>
    </div>


@endsection

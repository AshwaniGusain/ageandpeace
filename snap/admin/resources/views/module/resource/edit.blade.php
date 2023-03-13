@extends('admin::module.resource.form')

@section('main')
<div>
    <div is="{{ $form_component }}" id="snap-resource-form" inline-template>
        <div>

            <form id="snap-form" class="form form-{{ $module->handle() }}" ref="form" action="{{ $module->url('update', [$resource->id]) }}" method="post" enctype="multipart/form-data">
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

                    @if ($module->hasTrait('delete') && $module->service('deletable')->canDelete)
                        <div class="btn-group float-right" role="group">
                            <a href="{{ $module->url('delete', ['id' => $resource->id]) }}" class="border btn btn-danger float-right">Delete</a>
                            @if (method_exists($resource, 'isSoftDelete') && $resource->isSoftDelete() && $resource->trashed())
                            <a href="{{ $module->url('untrash', ['id' => $resource->id]) }}" id="btn-untrash" class="border btn btn-danger float-right">Restore</a>
                            @endif
                        </div>
                    @endif

                    <div class="btn-toolbar bg-light">
                        {{ $buttons }}

                        @if ($morebuttons = $form->buttons())
                        <div class="btn-group ml-2">
                        @foreach ($morebuttons as $button)
                            {!! $button !!}
                        @endforeach
                        </div>
                        @endif
                        {{--<div class="btn-group mr-2" role="group" aria-label="Save">
                            <input type="submit" name="save" class="btn btn-primary text-light" value="{{ trans('admin::resources.btn_save') }}">
                        </div>
                        <div class="btn-group mr-2" role="group" aria-label="Save & Exit">
                            <input type="submit" name="save_exit" class="btn btn-primary text-light" value="{{ trans('admin::resources.btn_save_and_exit') }}">
                        </div>
                        <div class="btn-group mr-2" role="group" aria-label="Save & Exit">
                            <input type="submit" name="save_exit" class="btn btn-primary text-light" value="{{ trans('admin::resources.btn_save_and_create') }}">
                        </div>--}}
                    </div>

                </div>

                <input type="hidden" name="__redirect__" id="__redirect__" value="">

                {{ method_field('PATCH') }}
                {{ csrf_field() }}

                {{ $preview }}
                @if ($module->hasTrait('preview'))
                {{--<snap-resource-preview url="{{ url('') }}" slug-field="{{ $module->getPreviewSlugInput() }}" id="snap-resource-preview" ref="snap-resource-preview"></snap-resource-preview>--}}
                @endif
            </form>



        </div>

    </div>
</div>


@endsection

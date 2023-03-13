@extends('admin::module.resource.form')

@section('main')

    <snap-inline inline-template>

        <div class="container-fluid" style="padding: 1.25rem">
            <div class="row">
                <div class="col">
                    <form id="snap-form" class="form form-{{ $module->handle() }}" action="{{ $module->url('doDelete') }}" method="POST">
                        @if ( ! empty($resources))
                            <p>{{ trans('admin::resources.to_delete') }}</p>

                            <ul>
                                <?php foreach ($resources as $resource) : ?>
                                <li><a href="{{ $module->url('edit', ['resource' => $resource]) }}" class="text-danger">{{ $resource->display_name }}</a></li>
                                <?php endforeach; ?>
                            </ul>

                            <input type="hidden" name="ids" value="{{ implode(',', $ids) }}">
                        @else
                            <p>{{ trans('admin::resources.error_no_delete') }}</p>
                        @endif
                        <input type="submit" class="btn btn-danger mr-2" value="{{ trans('admin::resources.btn_delete') }}">
                        <a href="{{ $module->url() }}" class="btn btn-secondary">Cancel</a>

                        <input type="hidden" name="__inline__" value="1">

                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                    </form>
                </div>

            </div>
        </div>


    </snap-inline>
@endsection
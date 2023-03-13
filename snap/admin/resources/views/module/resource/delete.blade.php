@extends('admin::layouts.admin')

@section('body')
    <div id="fuel-resource-delete">
        <div>
            {!! $heading !!}

            <div id="fuel-main-display" class="mt-3 ml-2">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <form  id="snap-form" class="form form-{{ $module->handle() }}" action="{{ $module->url('doDelete') }}" method="POST">
                                @if ( ! empty($resources))
                                    <p>{{ trans('admin::resources.to_delete') }}</p>

                                    <ul>
                                        @foreach ($resources as $resource)
                                            <li>
                                                <a href="{{ $module->url('edit', ['resource' => $resource]) }}" class="text-danger">{{ $resource->display_name }}</a>
                                                @if ($resource->isSoftDelete() && $resource->trashed())
                                                    (force delete from trash)
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>

                                    <input type="hidden" name="ids" value="{{ implode(',', $ids) }}">
                                @else
                                    <p>{{ trans('admin::resources.error_no_delete') }}</p>
                                @endif
                                <input type="submit" class="btn btn-danger mr-2" value="{{ trans('admin::resources.btn_delete') }}">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>

                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                            </form>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
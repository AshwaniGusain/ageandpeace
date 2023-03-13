@extends('admin::layouts.admin')


@section('body')
    <div id="snap-resource">

        {!! $heading !!}

        <div>
            <div id="snap-resource-form">
                <div>

                    <form id="snap-form" class="form" action="" method="post">
                        <div id="snap-main-display">


                            {!! $alerts !!}

                            <div class="container-fluid p-4">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="p-2">
                                            <p>{{ $message }}</p>
                                            <input type="submit" value="{{ trans('cache::messages.cache_btn') }}" class="btn btn-primary">
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{ csrf_field() }}

                    </form>

                </div>

            </div>
        </div>

    </div>

@endsection('body')

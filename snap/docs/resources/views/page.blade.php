@extends('admin::layouts.admin' )

@section('body')
    <div>
        {!! $heading !!}

        <div id="snap-main-display">
            <div class="container-fluid" style="padding: 1.25rem">
                <div class="row">
                    <div class="col-12">
                    {!! $content !!}
                    </div>
                </div>
            </div>


        </div>

    </div>

@endsection
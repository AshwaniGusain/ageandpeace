@extends('admin::layouts.admin' )

@section('body')
    <div>
        {!! $heading !!}

        <div id="snap-main-display">
            <div class="container-fluid" style="padding: 1.25rem">
                <div class="row">
                    <div class="col-12">
                        @foreach ($sections as $section => $packages)
                            <h2>{{ $section }}</h2>
                            <ul>
                            @foreach ($packages as $package)
                                <li><a href="{{ $package->url() }}">{{ $package->label }}</a></li>
                            @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>


        </div>

    </div>

@endsection
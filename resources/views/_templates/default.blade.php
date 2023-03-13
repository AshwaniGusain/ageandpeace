@extends('layouts.app')

@section('page-title'){{ $page_title ?? $heading ?? '' }}@endsection
@section('meta-description'){{ $meta_description ?? ''  }}@endsection
@section('og:title'){{ $og_title ?? $page_title ?? config('app.name', 'Age & Peace') }}@endsection
@section('og:description'){{ $og_description ?? $meta_description ?? config('app.name', 'Age & Peace') }}@endsection
@section('og:image'){{ $og_image ?? asset('assets/images/og_image.png') }}@endsection

@section('content')
    <div class="container pt-9 pb-7">
        <div class="row">
            <div class="col">

                @if (!empty($heading))
                <h1>{!! $heading !!}</h1>
                @endif

                {!! $body !!}
            </div>
        </div>
    </div>
@endsection
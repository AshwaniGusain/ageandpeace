@extends('layouts.app')
@section('page-title', 'Page Not Found')

@section('content')
    <div class="container">
        <div class="row py-8">
            <div class="col error-404">
                <h1>404</h1>
                <span>Sorry, we couldn't find that page.</span>
            </div>
        </div>
    </div>
@endsection

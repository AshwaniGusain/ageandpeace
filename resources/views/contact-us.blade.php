@extends('layouts.app')
@section('page-title', page_title(['Contact'], ' | '))

@section('content')
    <div class="container pt-9 pb-7">
        <div class="row">
            <div class="col">

                @if ($error = Session::get('error'))
                <div class="alert alert-danger">
                {{ $error }}
                </div>
                @elseif ($success = Session::get('success'))
                <div class="alert alert-info">
                    {{ $success }}
                </div>
                @endif

                <h1>Contact Us</h1>
                <p>Our team is happy to answer your questions.  Fill out the form below and will be in touch with you as soon as possible.</p>

                {!! $form->render() !!}
            </div>
        </div>
    </div>
@endsection
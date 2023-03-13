@extends('layouts.app')

@section('content')
    {{--{{dd($post)}}--}}
    <section class="post-hero pt-9 pb-7">
        <div class="container">
            <div class="row mb-2">
                <div class="col">
                    <h1>{{ $category->name }}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <img src="{{ $category->img_url }}" alt="{{ $category->name }}">
{{--                    <img src="//placehold.it/1200X475" alt="placeholder hero">--}}
                </div>
            </div>
        </div>
    </section>

    <section class="pb-10">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8 post-content">
                    {!! $category->description !!}

                    @if ($category->providerTypes->count())
                    <h5>{{ $category->name }} Categories</h5>

                    <ul class="col-count-2" style="list-style-position:inside;">
                        @foreach ($category->providerTypes as $type)
                            <li><a href="{{ url('providers') . '?type=' . $type->id }}">{{ $type->name }}</a></li>
                        @endforeach
                    </ul>

                    @endif
                </div>

                <aside class="col-12 col-md-4 post-sidebar">

                    @include('components.callout-get-started')

                    @include('components.callout-find-provider')


                    @if ($category->posts->count())
                    <h5 class="mt-3">Related Articles</h5>
                    <ul class="list-unstyled related-articles">
                        @foreach ($category->posts as $post)
                        <li><a href="{{ $post->url }}">{{ $post->title }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </aside>
            </div>
        </div>
    </section>
@endsection

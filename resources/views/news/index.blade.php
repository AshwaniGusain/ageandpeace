@extends('layouts.app')
@section('page-title', page_title(['News & Articles'], ' | '))

@section('content')
    @component('layouts.partials.title-bar')
        @slot('header')
            <div class="col-12 col-sm-6 align-self-end">
                <h1 class="mb-0">News & Articles</h1>
            </div>

            <div class="col-12 col-sm-6 text-right  align-self-end">
                <h5 class="mb-0">{{ $category->name ?? 'All Categories'}}</h5>
            </div>
        @endslot

        @slot('searchbar')
            <form action="" method="get">
                <div class="row justify-content-between align-items-end form-row">
                    <div class="col-md mb-1 mb-md-0 form-group mb-0">
                        <label for="category">Search by Category</label>
                        <select name="category" id="" class="custom-select">
                            <option value="">All</option>
                            @foreach ($categories as $id => $name)
                            <option value="{{ $id }}"{{ $category && $category->id == $id ? ' selected': ''}}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md mb-1 mb-md-0 form-group mb-0">
                        <label for="keyword">Search by Keyword</label>
                        <input type="text" name="keyword" value="{{ $keyword }}" id="" class="form-control">
                    </div>

                    <div class="col-sm-2 form-group mb-0">
                        <button type="submit" class="btn btn-arrow btn-block btn-primary">
                            Search
                            @svg('assets/svg/icon_arrow.svg', 'icon')
                        </button>
                    </div>
                </div>
            </form>
        @endslot
    @endcomponent

    @if (!empty($featured))
    <section class="featured-articles mb-9">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-6 col-lg-7 order-sm-2">
                    <a href="{{$featured->url}}"><img src="{{ $featured->md_image_url }}" alt="{{ $featured->title }}" class="d-block"></a>
                </div>
                <div class="col-12 col-sm-6 col-lg-5">
                    <span class="text-gray-md xsmall text-uppercase">{{ $featured->category->name }}</span>

                    <h3 class="my-1"><a href="{{$featured->url}}">{{$featured->title}}</a></h3>

                    <p>
                        {{ $featured->excerpt }}
                    </p>

                    <a href="{{ route('news.detail', $featured)}}" class="link-arrow">Read Article
                        @svg('assets/svg/icon_arrow.svg', 'icon')
                    </a>
                </div>

            </div>
        </div>
    </section>
    @endif

    <section class="posts pb-5 pb-md-10">
        <div class="container">
            <div class="row">
                @if ($posts->count())
                @foreach ($posts as $post)
                    @component('components.results-list-item', ['class' => 'mb-2 mb-8 post'])
                        @slot('image')
                            <a href="{{$post->url}}"><img src="{{ $post->sm_image_url }}" alt="{{ $post->title }}" class="d-block"></a>
                        @endslot
                        <span class="text-gray-md xsmall text-uppercase">{{ $post->category->name }}</span>

                        <h4 class="my-1"><a href="{{$post->url}}">{{$post->title}}</a></h4>

                        <p class="d-none d-md-block">
                            {{ $post->excerpt }}
                        </p>

                        <a href="{{ $post->url }}" class="link-arrow">Read Article
                            @svg('assets/svg/icon_arrow.svg', 'icon')
                        </a>
                    @endcomponent
                @endforeach
                @else
                    <div class="col-12">
                        <div class="row align-items-center">
                            There are news items or articles matching your filtering criteria.
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="pagination justify-content-center">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')
@section('page-title', page_title([$post->title, 'News & Articles'], ' | '))
@section('og:title', $post->title)
@section('og:type', 'article')
@section('og:description', str_limit(strip_tags($post->body), 100))
@if ($post->image->count())
    @section('og:image', $post->image[0]->getFullUrl())
@endif

@section('content')
    <section class="post-hero pt-9 pb-8">
        <div class="container">
            <div class="row mb-4">
                <div class="col">
                    <h1 class="mb-0">{{$post->title}}</h1>
                    <span class="post-meta-info">{{ $post->author_name }} | {{ $post->category->name }} | {{$post->created_at->format('F d, Y')}}</span>
                </div>
            </div>

            @if ($post->hasImage())
            <div class="row">
                <div class="col">
                    <img src="{{ $post->lg_image_url }}" alt="{{ $post->image_alt }}">
                </div>
            </div>
            @endif

        </div>
    </section>

    <section class="pb-10">
        <div class="container">
            <div class="row">
                <article class="col-12 col-md-8 post-content">
                    {{--<div>
                        <h3>Topics Covered in This Post</h3>

                        <div class="article-topics-links mb-5">
                            <ul>
                                <li><a class="topic-link" href="#">Header 1</a></li>
                                <li><a class="topic-link" href="#">Header 2</a></li>
                                <li><a class="topic-link" href="#">Header 3</a></li>
                            </ul>

                            <ul>
                                <li><a class="topic-link" href="#">Header 4</a></li>
                                <li><a class="topic-link" href="#">Header 5</a></li>
                            </ul>
                        </div>
                    </div>--}}
                    {!! $post->body !!}
                    {{--<p>
                        Sed tristique, metus et pharetra elementum, nibh justo finibus libero, vel molestie massa magna
                        nec nisi. Curabitur sagittis dui neque, vitae varius arcu consequat faucibus. Suspendisse
                        vulputate cursus ligula, ac mollis orci. Nunc convallis, elit ac venenatis condimentum, risus
                        urna varius ex, at finibus odio mauris in erat. Proin non auctor ipsum. Nam ut lobortis elit.
                        Cras sit amet metus ac risus ullamcorper varius eu cursus purus. Vivamus eros erat, ultrices et
                        elit consectetur, pulvinar tristique mauris. Praesent rutrum odio nibh, vel pulvinar est
                        porttitor dapibus.
                    </p>

                    <h4>Idependent Living</h4>
                    <p>
                        Sed tristique, metus et pharetra elementum, nibh justo finibus libero, vel molestie massa magna
                        nec nisi. Curabitur sagittis dui neque, vitae varius arcu consequat faucibus. Suspendisse
                        vulputate cursus ligula, ac mollis orci. Nunc convallis, elit ac venenatis condimentum, risus
                        urna varius ex, at finibus odio mauris in erat. Proin non auctor ipsum. Nam ut lobortis elit.
                        Cras sit amet metus ac risus ullamcorper varius eu cursus purus. Vivamus eros erat, ultrices et
                        elit consectetur, pulvinar tristique mauris. Praesent rutrum odio nibh, vel pulvinar est
                        porttitor dapibus.
                    </p>

                    <blockquote>
                        “Blockquote, Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Vestibulum sem risus, gravida id mi eget, sodales vehicula ex.
                        In orci nibh, rutrum quis egestas et, pretium id nunc.”
                    </blockquote>

                    <h4>Assisted Living</h4>
                    <p>
                        Sed tristique, metus et pharetra elementum, nibh justo finibus libero, vel molestie massa magna
                        nec nisi. Curabitur sagittis dui neque, vitae varius arcu consequat faucibus. Suspendisse
                        vulputate cursus ligula, ac mollis orci. Nunc convallis, elit ac venenatis condimentum, risus
                        urna varius ex, at finibus odio mauris in erat. Proin non auctor ipsum. Nam ut lobortis elit.
                        Cras sit amet metus ac risus ullamcorper varius eu cursus purus. Vivamus eros erat, ultrices et
                        elit consectetur, pulvinar tristique mauris. Praesent rutrum odio nibh, vel pulvinar est
                        porttitor dapibus.
                    </p>--}}

                    <div class="mt-8 mb-6 mb-2-sm">
                        <div class="col">
                            <div class="row align-items-center justify-content-center">
                                <div class="d-flex align-items-center share-wrapper">
                                    <span class="small mr-1"><strong>Share This</strong></span>
                                    <ul class="nav share-links mr-2">
                                        @foreach (['twitter', 'facebook', 'linkedin', 'email'] as $shareType)
                                            <li class="ml-1 nav-item">
                                                @include('components.share-link', [
                                                    'type' => $shareType,
                                                    'url' => route('news.detail', $post),
                                                    'title' => $post->title
                                                    ])
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>


                                <div class="ml-sm-auto">
                                    @component('components.btn-arrow', ['href' => route('news')])
                                        All Articles
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="col-12 col-md-4 post-sidebar">
                    @include('components.callout-get-started')

                    @include('components.callout-find-provider')

                    @if ($related->count())
                    <h5 class="mt-3">Related Articles</h5>
                    <ul class="list-unstyled related-articles small">
                        @foreach ($related as $rpost)
                        <li><a href="{{ $rpost->url }}">{{ $rpost->title }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </aside>
            </div>
        </div>
    </section>
@endsection

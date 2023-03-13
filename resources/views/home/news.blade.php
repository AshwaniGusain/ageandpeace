<section class="news posts bg-dark py-7">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h3 class="mb-7 text-white">Featured Articles</h3>
            </div>
        </div>
        <div class="row">
            @foreach ($featuredPosts as $post)
                @component('components.results-list-item', ['class' => 'post'])
                    @slot('image')
                        <a href="{{$post->url}}"><img src="{{ $post->sm_image_url }}" alt="{{ $post->title }}" class="d-block"></a>
                    @endslot

                    <span class="text-gray-lt xsmall text-uppercase">{{ $post->category ? $post->category->highestParent()->name : ''}}</span>
                    <h4><a href="{{ $post->url }}" class="text-white">{{$post->title}}</a></h4>
                @endcomponent
            @endforeach
        </div>
    </div>
</section>

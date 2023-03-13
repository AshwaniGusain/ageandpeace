<section class="recent-posts posts py-8">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h3 class="mb-7">Recent Articles & News</h3>
            </div>
        </div>
        <div class="row">
            @foreach ($recentPosts as $post)
                @component('components.results-list-item', ['class' => 'post'])
                    @slot('image')
                        <a href="{{$post->url}}"><img src="{{ $post->sm_image_url }}" alt="{{ $post->title }}" class="d-block"></a>
                    @endslot


                    <h4 class="my-1"><a href="{{$post->url}}">{{$post->title}}</a></h4>

                    <p class="d-none d-md-block">
                        {{ str_limit(strip_tags($post->body), 70) }}
                    </p>

                    <a href="{{ $post->url }}" class="link-arrow">Read Article @svg('assets/svg/icon_arrow.svg', 'icon icon-sm')</a>
                @endcomponent
            @endforeach
        </div>
    </div>
</section>

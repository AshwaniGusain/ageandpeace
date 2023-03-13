<div class="megamenu-content">
    <div class="megamenu-content-row">

        @foreach ($category->childrenCategories as $subCategory)
            <div class="megamenu-content-col">
                <h5 class="nav-category-title text-nowrap">
                    <a href="{{ route('subcategory', $subCategory) }}">{{ $subCategory->name }} ></a>
                </h5>

                <ul class="nav-category-list">
                    @foreach ($subCategory->providerTypes()->orderBy('pivot_precedence')->get() as $providerType)
                        <li>
                            <a href="{{ url('providers') . '?category=' . $category->id .'&subcategory=' . $subCategory->id . '&type=' . $providerType->id }}">
                                {{ $providerType->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <div class="megamenu-content-col articles">

            <?php $childPosts = $category->childPosts();?>
            @if ($childPosts->count())
            <h5 class="nav-category-title">Related News & Articles</h5>

            <ul class="nav-category-list">
                @foreach ($childPosts as $post)
                    <li class="media mb-2">
                        @if ($post->hasImage())
                            <a href="{{ $post->url }}" class="d-block mr-2"><img class="mr-3" src="{{ $post->sm_image_url }}" alt="{{ e($post->title) }}"></a>
                        @endif
                        <div class="align-self-center media-body">
                            <h6 class="mt-0"><a href="{{ $post->url }}">{{ $post->title }}</a></h6>
                        </div>
                    </li>
                @endforeach
            </ul>
            @endif

{{--            @if(Auth::user())--}}
{{--                <a href="{{route('dashboard')}}" class="checklist-link btn btn-primary btn-arrow btn-block">--}}
{{--                    Continue To Checklist--}}
{{--                    @svg('assets/svg/icon_arrow.svg', 'icon')--}}
{{--                </a>--}}
{{--            @else--}}
                <a href="{{route('pages', ['checklist']).'#signup'}}" class="checklist-link btn btn-primary btn-arrow btn-block">
                    Start Checklist Now
                    @svg('assets/svg/icon_arrow.svg', 'icon')
                </a>
{{--            @endif--}}

        </div>
    </div>


</div>

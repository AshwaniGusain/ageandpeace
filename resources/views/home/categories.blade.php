<section class="categories pb-5 pt-8">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 text-center mb-5">
                <h3>We’ve Got The Basis Covered</h3>

                <p>Stay informed, get connected, and be prepared. Our provider network has a wide range of vetted advisors and organizations you can trust to meet your needs. We’ve done the research so you can start making things happen.</p>
            </div>
        </div>

        <div class="row">
            @foreach (App\Models\Category::topLevel()->with('childrenCategories')->get() as $category)
                @component('components.category-list', ['class' => $category->slug, 'category' => $category])
                    @slot('icon')
                        @switch($category->slug)
                            @case('health-and-wellness')
                                @svg('assets/svg/icon_category_health_wellness.svg', 'icon icon-3x')
                            @break

                            @case('financial-and-insurance')
                                @svg('assets/svg/icon_category_financial_insurance.svg', 'icon icon-3x')
                            @break

                            @case('legal-and-legacy')
                                @svg('assets/svg/icon_category_legal_choices.svg', 'icon icon-3x')
                            @break

                            @case('social-and-spiritual')
                                @svg('assets/svg/icon_category_social_spiritual.svg', 'icon icon-3x')
                            @break

                            @case('home-and-care')
                                @svg('assets/svg/icon_category_home_care.svg', 'icon icon-3x')
                            @break

                            @case('care-giving-and-tech')
                                @svg('assets/svg/icon_category_caregiving.svg', 'icon icon-3x')
                            @break

                        @endswitch
                    @endslot

                    @slot('name')
                            {{ $category->name }}
                    @endslot
                @endcomponent
            @endforeach
        </div>
    </div>
</section>

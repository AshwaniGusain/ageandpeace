@extends('layouts.app')
@section('page-title', page_title(['Planning'], ' | '))

@section('content')
    <section class="hero hero-planning">
        <div class="container">
            <div class="row hero-text align-items-center">
                <div class="col-md-7 col-12">
                    <h1>Planning Headline</h1>
                    <p>optional short description</p>
{{--                    <a href="#" class="play-link">--}}
{{--                        <span>@svg('assets/svg/icon_video.svg', 'icon icon-2x v-align-middle mr-2')</span> Watch the video</a>--}}
                </div>
            </div>
        </div>
    </section>

    @component('components.intro')
        <h3>Intro Headline</h3>

        <p>
            Article Post Excerpt copy that describes the article content in short.
            Candy canes bonbon cake cupcake bear claw soufflé candy canes chupa.
        </p>

        @component('components.btn-arrow', ['href' => '#'])
            Begin Assessment
        @endcomponent
    @endcomponent

    <section class="py-1 pb-sm-8">
        <div class="container">
            <div class="row">
                @for ($i=0; $i < 3; $i++)
                    <div class="col-12 col-md-4 text-center mb-4 mb-md-0">
                        <img src="http://placehold.it/370X200" alt="">

                        <h4 class="mt-2">Take Assessment</h4>

                        <p class="small">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi accumsan pretium nulla,  pellent esque ante porta.
                        </p>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <section class="bg-light py-1 py-sm-2">
        <div class="container">
            @for($i = 0; $i < 3; $i++)
            <div class="row align-items-center my-6 my-sm-9">
                <div class="col-12 col-sm-6 col-lg-5 @if($i === 0 || $i === 2) order-sm-3 @endif">
                    <h3>Why Sign Up?</h3>

                    <p>
                        Description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Morbi accumsan pretium nulla, et pellent esque ante porta in.
                        Interdum et malesuada fames ac ante ipsum primis in faucibus. Integer erat ipsum.
                    </p>
                </div>

                <div class="col-12 col-sm-6 col-lg-7">
                    <img src="http://placehold.it/680X400" alt="">
                </div>
            </div>
            @endfor
        </div>
    </section>

    <section class="signup pb-8 pb-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <h3>What You Get By Signing Up</h3>

                    <p>
                        Describe what you get by signing up.
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Morbi accumsan pretium nulla, et pellent esque ante porta in.
                        Interdum et malesuada fames ac ante ipsum primis in faucibus.
                        Integer erat ipsum, rhoncus id ornare ut, finibus non massa.
                    </p>
                </div>

                <div class="col-12 col-md-6">
                    <form action="{{ route('register') }}" method="POST" class="p-4 p-lg-7 bg-white shadow">
                        @csrf

                        <div class="form-group form-row align-items-center">
                            <label for="firstName" class="col-sm-3 p-0 col-form-label">First Name</label>
                            <div class="col-sm-9 pr-0">
                                <input type="text" class="form-control" id="firstName" placeholder="Enter your first name">
                            </div>
                        </div>

                        <div class="form-group form-row align-items-center">
                            <label for="lastName" class="col-sm-3 p-0 col-form-label">Last Name</label>
                            <div class="col-sm-9 pr-0">
                                <input type="text" class="form-control" id="lastName" placeholder="Enter your last name">
                            </div>
                        </div>

                        <div class="form-group form-row align-items-center">
                            <label for="email" class="col-sm-3 p-0 col-form-label">Email</label>
                            <div class="col-sm-9 pr-0">
                                <input type="email" class="form-control" id="email" placeholder="Enter your email address">
                            </div>
                        </div>

                        <div class="form-group form-row align-items-center">
                            <label for="zipCode" class="col-sm-3 p-0 col-form-label">Zip Code</label>
                            <div class="col-sm-9 pr-0">
                                <input type="text" class="form-control" id="zipCode" placeholder="Enter your zip code">
                            </div>
                        </div>

                        <div class="form-row justify-content-end">
                            <div class="col-sm-9 pr-0">
                                <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <section class="categories pb-5 pt-8">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 text-center mb-5">
                    <h3>We’ve Got The Bases Covered</h3>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi accumsan pretium nulla.</p>
                </div>
            </div>

            <div class="row">
                @foreach (App\Models\Category::topLevel()->get() as $category)
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

    <section class="testimonials py-8">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col col-md-8">
                    <p class="text-center">
                        @svg('assets/svg/icon_testimonial.svg', 'icon icon-2x')
                    </p>

                  <blockquote class="text-white text-center">
                      Testimonial. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Suspendisse vehicula in tellus ac ornare.
                      Donec vel justo ut nunc interdum mollis a ac leo.
                      Mauris at nisi imperdiet, egestas lacus sit amet.

                      <cite class="text-center text-white d-block mt-2">
                          <strong>— Name, Title</strong>
                      </cite>
                  </blockquote>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.partials.prefooter')


@endsection

@extends('layouts.app')

@section('page-title', 'Age & Peace Checklist')
@section('og:title', 'Age & Peace Checklist')

@section('content')
    <section class="hero hero-planning">
        <div class="container">
            <div class="row hero-text align-items-center">
                <div class="hero-text-box col-md-7 col-12">
                    <h1>A little planning goes a very long way</h1>
                    <p>Make your choices now, so they aren't made for you later.</p>
                    {{--                    <a href="#" class="play-link">--}}
                    {{--                        <span>@svg('assets/svg/icon_video.svg', 'icon icon-2x v-align-middle mr-2')</span> Watch the video</a>--}}
                </div>
            </div>
        </div>
    </section>

    @component('components.intro')
        <h3>Get the most out of this site</h3>

        <p>
            We have the checklists, resources, and guidance for what you need, now and in the future.
        </p>

    @endcomponent

    <section class="py-1">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 text-center mb-4 mb-md-0">
                    {{--                    <img src="http://placehold.it/370X200" alt="">--}}
                    <img src="{{ asset('assets/images/about_step1.png') }}" alt="Checklist">

                    <h4 class="mt-2">Run through checklists to take away the uncertainty</h4>

                    <p class="small">
                        We have checklists in six key categories to steer you through the tasks that matter in navigating the issue at hand.
                    </p>
                </div>
                <div class="col-12 col-md-4 text-center mb-4 mb-md-0">
                    <img src="{{ asset('assets/images/about_step2.png') }}" alt="Prepare">

                    <h4 class="mt-2">Find out what you’ll need for each task</h4>

                    <p class="small">
                        We have put together a task guide with all the information and documents you will need to complete each task.
                    </p>
                </div>
                <div class="col-12 col-md-4 text-center mb-4 mb-md-0">
                    <img src="{{ asset('assets/images/about_step3.png') }}" alt="Connect">

                    <h4 class="mt-2">Connect to trusted, vetted providers</h4>

                    <p class="small">
                        Our council of industry experts have come up with a vetting process for each type of provider, so you don’t have to sort through thousands of options to find a highly qualified resource.
                    </p>
                </div>
            </div>
        </div>
    </section>



    <section class="bg-light py-1 py-sm-2">
        <div class="container">
            <div class="row align-items-center my-6 my-sm-9">
                <div class="col-12 col-sm-6 col-lg-5 order-sm-3">
                    <h3>Tomorrow you’ll wish you started today</h3>

                    <p>
                        When you take the steps to create a plan, which we’ve made easy for you, you and your loved ones can move forward in life with less anxiety and more confidence — and if there ever is an emergency situation, the ripple effect of stress will be greatly reduced.
                    </p>
                </div>

                <div class="col-12 col-sm-6 col-lg-7">
                    <img src="{{ asset('assets/images/why1_checklist.jpg') }}" alt="">
                </div>
            </div>
            <div class="row align-items-center my-6 my-sm-9">
                <div class="col-12 col-sm-6 col-lg-5">
                    <h3>Planning is the antidote to future conflict between loved ones</h3>

                    <p>
                        When there isn’t a documented plan, caretakers often disagree on hard choices. This creates tension, and worse, that can last a lifetime between those closest to you.
                    </p>
                </div>

                <div class="col-12 col-sm-6 col-lg-7">
                    <img src="{{ asset('assets/images/why2_checklist.jpg') }}" alt="">
                </div>
            </div>
            <div class="row align-items-center my-6 my-sm-9">
                <div class="col-12 col-sm-6 col-lg-5 order-sm-3">
                    <h3>Save money by considering key decisions now</h3>

                    <p>
                        When we have to make quick decisions about things we’ve avoided thinking about, it usually costs us far more than it needed to, emotionally and financially.
                    </p>
                </div>

                <div class="col-12 col-sm-6 col-lg-7">
                    <img src="{{ asset('assets/images/why3_checklist.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </section>

    @include('home.signup')

    <section class="categories pb-4 pt-8">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 text-center mb-7">
                    <h3>We’ve Got The Bases Covered</h3>

                    {{-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi accumsan pretium nulla.</p>--}}
                </div>
            </div>

            <div class="row">
                @include('components.categories')
            </div>
        </div>
    </section>


    <section class="testimonials pt-8 pb-6">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col col-md-8">
                    <p class="text-center">
                        @svg('assets/svg/icon_testimonial.svg', 'icon icon-2x')
                    </p>

                    <div id="quotes-carousel" class="carousel slide" data-ride="carousel" style="min-height: 300px;">
                        <ol class="carousel-indicators">
                            <li data-target="#quotes-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#quotes-carousel" data-slide-to="1"></li>
                            <li data-target="#quotes-carousel" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <blockquote class="text-white text-center">
                                    By going through and checking off the tasks, my mom has control, and she feels it. She can decide the choices others might have had to make on her behalf in the future. I can see the stress it’s just erased from her life.

                                    <cite class="text-center text-white d-block mt-2">
                                        <strong>— Lisa Springer, daughter</strong>
                                    </cite>
                                </blockquote>
                            </div>
                            <div class="carousel-item">
                                <blockquote class="text-white text-center">
                                    I always worried about what would happen between my siblings when my parents couldn’t make decisions on their own any longer and we all had our own views of what would be best for them. But when we went through these tasks with our parents it cleared up the tension, and we were able to follow their wishes instead of arguing over what we might have thought they’d want.

                                    <cite class="text-center text-white d-block mt-2">
                                        <strong>— Mark Dekker, son</strong>
                                    </cite>
                                </blockquote>
                            </div>
                            <div class="carousel-item">
                                <blockquote class="text-white text-center">
                                    My family was forced into making living arrangements for my dad after a medical situation, and in one weekend we had to make a bunch of big decisions we weren’t prepared for.  It had a huge impact on my parents financially, and looking back we didn't make the best decision either because of the time pressure. These checklists and tasks will keep us from repeating that situation again, and it feels like we’re prepared for the big issues that could arise.

                                    <cite class="text-center text-white d-block mt-2">
                                        <strong>— Connie Walker, daughter</strong>
                                    </cite>
                                </blockquote>
                            </div>
                        </div>
                        <a class="carousel-control carousel-control-prev" href="#quotes-carousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control carousel-control-next" href="#quotes-carousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @include('layouts.partials.prefooter')
@endsection
@extends('layouts.dashboard')
@section('page-title', $category->name . ' Tasks')

@section('main')
    @include('dashboard.back-dash-link')

    <section class="pt-4 pt-lg-8 mb-5">
        <div class="container">
            <div class="row">
                <div class="col col-lg-10">
                    <div class="media align-items-center">
                        <div class="icon-holder icon-holder-lg mr-3 mr-lg-7">
                            @switch ($category->slug)
                                @case('health-and-wellness')
                                    @svg('assets/svg/icon_category_health_wellness.svg', 'icon icon-3x')
                                    @break
                                @case('financial-and-insurance')
                                    @svg('assets/svg/icon_category_financial_insurance.svg', 'icon icon-3x')
                                    @break
                                @case('care-giving')
                                    @svg('assets/svg/icon_category_caregiving.svg', 'icon icon-3x')
                                    @break
                                @case('legal-and-legacy')
                                    @svg('assets/svg/icon_category_legal_choices.svg', 'icon icon-3x')
                                    @break
                                @case('home-and-care')
                                    @svg('assets/svg/icon_category_home_care.svg', 'icon icon-3x')
                                    @break
                                @case('social-and-spiritual')
                                    @svg('assets/svg/icon_category_social_spiritual.svg', 'icon icon-3x')
                                    @break
                            @endswitch
                        </div>

                        <div class="media-body">
                            <h1 class="mt-0">{{$category->name}}</h1>

{{--                            <p class="category-intro">--}}
{{--                                This is placeholder copy for the moment. We will need to add a way to associate this description with the category.--}}
{{--                            </p>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div>
        <div class="container" v-if="false">
            <div class="row hero-text align-items-center">
                <div class="loader"></div>
            </div>
        </div>
        <section class="pb-8" v-cloak>
            <task-list :initial-tasks="{{$tasks}}"
                       :show-progress="true"
                       :show-filters="true"
                       print-url="{{route('dashboard.print', [$category->slug])}}"
            >
                <p slot="empty-list-message">
                    You haven't completed any of the tasks in this category yet.
                </p>
            </task-list>
        </section>
    </div>
@endsection

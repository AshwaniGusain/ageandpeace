@extends('layouts.dashboard')

@section('main')

    <section class="hero hero-dashboard">
        <div class="container">
            <div class="row hero-text align-items-center">
                <div class="hero-text-box col-md-9 col-12">
                    <h1>The Age and Peace Checklist</h1>
                    <p>We’re here to guide you through every step of any situation, from unforeseen health issues to proactive life planning.</p>
                    @if (!$hasProgress)
                    <a href="#" class="btn btn-primary btn-arrow">Guide to Checklist @svg('assets/svg/icon_arrow.svg', 'icon')</a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div>
        <!-- https://stackoverflow.com/questions/46682000/is-there-a-v-cloak-inverse-->
        <div class="container" v-if="false">
            <div class="row hero-text align-items-center">
                <div class="loader"></div>
            </div>
        </div>

        <div v-cloak>
            <dashboard-index :initial-progress="{{json_encode($progress)}}" :tasks="{{$upcoming}}">
                <div class="row m-0" slot="progress-icons" slot-scope="{progress}">
                    @foreach($progress as $category)
                        <div class="col-sm-6 col-lg-4 category-progress {{$category['slug']}}">
                            <a href="{{ route('dashboard.category', $category['slug']) }}" class="bg-link"></a>
                            <progress-icon title="{{ $category['name'] }}" :progress="progress['{{ $category['slug'] }}'].progress">
                                @switch ($category['slug'])
                                    @case('health-and-wellness')
                                    @svg('assets/svg/icon_category_health_wellness.svg', 'icon icon-2x')
                                    @break
                                    @case('financial-and-insurance')
                                    @svg('assets/svg/icon_category_financial_insurance.svg', 'icon icon-2x')
                                    @break
                                    @case('care-giving')
                                    @svg('assets/svg/icon_category_caregiving.svg', 'icon icon-2x')
                                    @break
                                    @case('legal-and-legacy')
                                    @svg('assets/svg/icon_category_legal_choices.svg', 'icon icon-2x')
                                    @break
                                    @case('home-and-care')
                                    @svg('assets/svg/icon_category_home_care.svg', 'icon icon-2x')
                                    @break
                                    @case('social-and-spiritual')
                                    @svg('assets/svg/icon_category_social_spiritual.svg', 'icon icon-2x')
                                    @break
                                    @case('social-and-spiritual')
                                    @svg('assets/svg/icon_category_caregiving.svg', 'icon icon-2x')
                                    @break
                                    @case('care-giving-and-tech')
                                    @svg('assets/svg/icon_category_caregiving.svg', 'icon icon-2x')
                                    @break
                                @endswitch
                            </progress-icon>
                        </div>
                    @endforeach
                </div>
            </dashboard-index>
        </div>
    </div>
@endsection


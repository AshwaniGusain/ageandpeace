@extends('layouts.app')

@section('content')
    <section class="provider-hero pt-8 pb-4">
        <div class="container">
            <div class="row mb-7">
                <div class="col">
                    <img src="{{ $provider->hero_image_url }}" alt="{{ $provider->name }} Hero">
                </div>
            </div>


            <div class="row">
                <div class="col">
                    <h1 class="mb-0">{{ $provider->name }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-10">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-7 col-lg-8 post-content mb-2">


                    {!! $provider->description !!}
                </div>

                <aside class="col-12 col-md-5 col-lg-4 provider-sidebar">
                    <div class="bg-light">
                        <div class="p-5">
                            @if($provider->logo_url)
                                <div class="text-center mb-4">
                                    <img src="{{ $provider->logo_url }}" alt="{{ $provider->name }}">
                                </div>
                            @endif

                            <div class="mb-2">
                            @if ($provider->rating || (Auth::user() && Auth::user()->hasRole('customer')))
                                <h6 class="mb-0 d-inline-block mr-1">Rating</h6>
                                <modal-trigger class="d-inline-block">
                                    <a href="#"
                                       slot="openButton"
                                       slot-scope="{show}"
                                       @click.prevent="show"
                                       class="tiny-link">

                                        @if (Auth::user() && Auth::user()->hasRole('customer'))
                                            {{$provider->rating ? '(Rate this provider)' : '(Be the first to rate this provider)'}}
                                        @endif
                                    </a>

                                    @include('modals.rate-provider')
                                </modal-trigger>

                                @if ($provider->rating)
                                <ratings :rating="{{$provider->rating ? $provider->rating : 0}}" :lg="true"></ratings>
                                @endif
                            @endif
                            </div>
                            <div itemscope itemtype="http://schema.org/Organization">
                                <h4 itemprop="name" class="d-none">{{ $provider->name }}</h4>
                                <address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                    <span itemprop="streetAddress">{{ $provider->street }}</span><br>
                                    <span itemprop="addressLocality">{{ $provider->city }}</span>,
                                    <span itemprop="addressRegion">{{ $provider->state }}</span>
                                    <span itemprop="postalCode">{{ $provider->zip }}</span>
                                </address>


                                @if (!empty($provider->website))
                                <h6 class="mb-0">Website</h6>
                                <p itemprop="location" itemscope itemtype="http://schema.org/Place">
                                    <a href="{{ $provider->website }}" target="_blank" itemprop="url">
                                        {{ $provider->pretty_website }}
                                    </a>
                                </p>
                                @endif

                                @if (!empty($provider->phone))
                                    <h6 class="mb-0">Phone</h6>
                                    <p itemprop="telephone">
                                        <a href="tel:{{ $provider->formatted_phone }}">{{ $provider->formatted_phone }}</a>
                                    </p>
                                @endif
                            </div>

                            @if ($provider->user->valid_email)
                            <a href='mailto:{!! safe_email($provider->user->email) !!}' target="_blank" class="btn btn-arrow btn-primary btn-block rounded-0">
                                Contact Provider
                                @svg('assets/svg/icon_arrow.svg', 'icon')
                            </a>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="additional-providers bg-light py-8">
        <div class="container">
            @if(count($relatedProviders) > 0)
                <div class="row">
                    <div class="col-12 text-center">
                        <h3 class="mb-5">Additional Providers</h3>
                    </div>
                </div>

                <div class="row">
                    @foreach ($relatedProviders as $p)
                        @component('components.results-list-item', ['class' => 'provider-listing'])

{{--                            @slot('image')--}}
{{--                                <a href="{{ route('provider.detail', ['provider' => $p]) }}"><img src="{{ $p->display_image }}" alt="{{ $p->name }}"></a>--}}
{{--                            @endslot--}}

                            <h5 class="mb-0"><a href="{{ route('provider.detail', $p) }}">{{ $p->displayName }}</a></h5>

                            @if ($p->rating)
                            <div class="provider-rating">
                                <ratings :rating="{{$p->rating ? $p->rating : 0}}" :lg="true"></ratings>
                            </div>
                            @endif

                            <p class="small">
                                {!!  $p->excerpt  !!}
                            </p>
                        @endcomponent
                    @endforeach
                </div>
            @endif

            <div class="row">
                <div class="col text-center">
                    <a href="{{ route('providers') }}" class="btn btn-primary">View All Providers</a>
                </div>
            </div>
        </div>
    </section>
@endsection

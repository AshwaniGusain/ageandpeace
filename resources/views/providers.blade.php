@extends('layouts.app')
@section('page-title', page_title(['Providers'], ' | '))

@section('content')
    @component('layouts.partials.title-bar')
        @slot('header')
            <div class="col-12 col-sm-6">
                <h1 class="mb-1 mb-sm-0">Find a Provider</h1>
            </div>

            <div class="col-12 col-sm-6">
                <div v-cloak>
                    <provider-filters
                        :categories="{{ $categories }}"
                        zip="{{ $zip }}"
                        :valid="{{ $zipValid ? 'true' : 'false' }}"
                        :initial-filters="{{ $filters }}"
                        active-tab="{{ $activeTab ?? '' }}"
                        init-search="{{ $search ?? '' }}"
                    ></provider-filters>
                </div>
            </div>
        @endslot

        @slot('searchbar')
            <portal-target name="search-toolbar"></portal-target>
        @endslot
    @endcomponent

    <section class="results pb-5 pb-md-10">
        <div class="container">
            @if( request()->get('s'))
                <div class="row">
                    <div class="col-12"><p><strong>Search Results For: {{request()->input('s')}}</strong></p></div>
                </div>
            @endif
            <div class="row">
                @foreach ($providers as $provider )
                    @component('components.results-list-item', ['class' => 'mb-2 mb-md-7 provider-listing'])
                        @if($loop->index < 6)
                            @slot('image')
                                {{--TODO need provider image transform of logo for this?
                                <img src="//placehold.it/360X200" alt="" class="d-block"> --}}
{{--                                <a href="{{ route('provider.detail', ['provider' => $provider]) }}"><img src="{{ $provider->display_image }}" alt="{{ $provider->name }}"></a>--}}
                            @endslot
                        @endif

                        <h5 class="mb-0">
                            <a href="{{ route('provider.detail', ['provider' => $provider]) }}">{{ $provider->displayName }}</a>
                        </h5>

                        @if($provider->rating)
                            <div class="provider-rating">
                                <ratings :rating="{{$provider->rating}}"></ratings>
                            </div>
                        @endif


                        <p class="small">
                            {!! $provider->excerpt !!}
                        </p>
                    @endcomponent
                @endforeach

                @if($providers->count() < 1)
                    <div class="col-12">
                        <p>No results found.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="pagination justify-content-center">
            {{$providers->appends(request()->except('page'))->links()}}
        </div>
    </section>

@endsection

@extends('layouts.dashboard')
@section('page-title', $title ?? 'All Tasks')

@section('main')
    @include('dashboard.back-dash-link')

    <section class="pt-5 pt-md-6 mb-3 mb-lg-8">
        <div class="container">
            <div class="row">
                <div class="col col-lg-10">
                    <h1 class="mt-0">{{ $title ?? 'All Tasks'}}</h1>
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
            <task-list :initial-tasks="{{ $tasks }}"
                       @if (isset($providerTypes))
                        :provider-types="{{$providerTypes}}"
                       @endif
                       @if (!isset($category))
                       :allow-filter-by-category="true"
                       @endif
                       :show-filters="true"
                       initial-provider-filter="{{ app('request')->input('type')}}"
                       print-url="{{route('dashboard.print',['category' => null, 'upcoming' => $upcoming ?? '0'])}}"
            >
    {{--            <p slot="empty-list-message">--}}
    {{--                We didn't find any matching tasks.--}}
    {{--            </p>--}}
            </task-list>
        </section>
    </div>
@endsection

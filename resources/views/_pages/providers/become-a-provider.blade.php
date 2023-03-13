@extends('layouts.app')
@section('page-title', page_title(['Become a Provider'], ' | '))

@section('content')
    <section class="intro pt-6 pt-md-8">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1>Become a Qualified Provider on Age and Peace</h1>
                    <p class="mb-0">
                        Connecting a limited amount of Qualified Providers with a focused group of users and communities.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-1 pb-sm-2">
        <div class="container">
                <div class="row align-items-center my-6 my-sm-9 justify-content-between">
                    <div class="d-flex col-12 col-sm-6 col-lg-5 justify-content-sm-end order-sm-3">
                        <div class="ml-sm-5">
                            <h3>Custom Tools</h3>

                            <p>
                                From your free-profile to a dedicated team to assist you along the way, we have the platform to increase your business exposure.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-7">
{{--                        <img src="http://placehold.it/680X400" alt="">--}}
                        <img src="{{ asset('assets/images/provider_point1.jpg') }}" alt="Custom Tools">
                    </div>
                </div>

                <div class="row align-items-center my-6 my-sm-9 justify-content-between">
                    <div class="d-flex col-12 col-sm-6 col-lg-5">
                        <div class="mr-sm-5">
                            <h3>Targeted Users</h3>

                            <p>
                                Our user demographic are those who are specifically in the older adult population your business serves.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-7">
                        <img src="{{ asset('assets/images/provider_point2.jpg') }}" alt="Targeted Users">
                    </div>
                </div>

                <div class="row align-items-center my-6 my-sm-9 justify-content-between">
                    <div class="d-flex col-12 col-sm-6 col-lg-5 justify-content-sm-end order-sm-3">
                        <div class="ml-sm-5">
                            <h3>Community Engagement</h3>

                            <p>
                                Gain access to local targeted communities for speaking engagements to share more about your services
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-7">
                        <img src="{{ asset('assets/images/provider_point3.jpg') }}" alt="Community Engagement">
                    </div>
                </div>
        </div>
    </section>

    <section class="bg-light py-4 pt-md-7 pb-md-8">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <h3 class="mb-2">Ready to establish your Age and Peace presence today?</h3>
                    @component('components.btn-arrow', ['href' => route('contact')])
                        Contact Us
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection


<div class="container" v-cloak>
    <div class="row justify-content-center mt-md-6">
        <div class="col-12 col-sm-6 col-lg-5 mb-2 mb-sm-0 d-flex">
            @component('components.card-form',[
                'type' => 'plain',
                'href' => route('customer-invite'),
                'buttonText' => 'Sign Up to Start your Checklist'
            ])
                <div class="row">
                    <div class="col-12 text-center">
                        <h3 class="mt-1">Get Help</h3>
                        <p class="mb-0">Call us at <a href="tel:503-395-2272">(503) 395-2272</a>  or begin the <a href="{{route('pages', ['checklist'])}}" class="nav-link">Age &amp; Peace Checklist</a> to get started.</p>
                    </div>
                </div>
            @endcomponent
        </div>

        <div class="col-12 col-sm-6 col-lg-5 d-flex">
            @component('components.card-form',[
                            'type' => 'plain',
                            'href' => route('pages', ['providers']),
                            'buttonText' => 'Find a Provider'
                        ])
                <div class="row">
                    <div class="col-12 text-center">
                        <h3 class="mt-1">Find Providers</h3>
                        <p class="mb-0">Use our Provider page to find qualified and vetted providers in your local area.</p>
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
</div>

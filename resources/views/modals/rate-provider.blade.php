<div class="container" v-cloak>
    <div class="row justify-content-center">
        <div class="col-sm-8 col-lg-6">
            @component('components.card-form',[
                'type' => 'form',
                'action' => route('provider.rate', $provider),
                'buttonText' => 'Submit Rating',
            ])
                <div class="form-group row">
                    <div class="col-12 text-center">
                        <h3>Rate Provider</h3>
                    </div>
                </div>

                <div class="form-group row">
                    <rating-input></rating-input>
                </div>
            @endcomponent
        </div>
    </div>
</div>

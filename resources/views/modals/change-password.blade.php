<div class="container" v-cloak>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            @component('components.card-form',[
                'type' => 'form',
                'action' => '/me',
                'buttonText' => 'Submit'
            ])
                <div class="form-group row">
                    <div class="col-12 text-center">
                        <h3>Change Password</h3>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <label for="password">Password</label>

                        <input id="password" type="password"
                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                               name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <label for="password-confirm">Confirm Password</label>

                        <input id="password-confirm" type="password" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                               name="password_confirmation" required>

                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
</div>

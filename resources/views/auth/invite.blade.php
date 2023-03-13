@extends('layouts.sign-in')

@section('main')


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @component('components.card-form', [
                    'type' => 'form',
                    'action' => route('customer.invite-post'),
                    'buttonText' => 'Sign Up'
                ])
                    <div class="form-group row">
                        <div class="col-12">
                            <h3 class="text-center">Sign Up</h3>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">

                            <label for="email">E-Mail Address</label>

                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   name="email" value="{{ old('email') ??  $invite->email ?? ''}}" required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif

                        </div>
                    </div>

                    <div style="display:none;">
                        <label for="random">A Random Field</label>
                        <input type="text" name="random" id="random" />
                    </div>

                    <input type="hidden" id="role" name="role" value="customer">

                @endcomponent
            </div>
        </div>
    </div>

@endsection

@extends('layouts.sign-in')

@section('main')

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    @component('components.card-form', [
                        'type' => 'form',
                        'action' => route('register'),
                        'buttonText' => 'Sign Up'
                    ])
                        <div class="form-group row">
                            <div class="col-12">
                                <h3 class="text-center">Complete Sign Up</h3>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <label for="name">Name</label>
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">

                                <label for="email">E-Mail Address</label>

                                <input id="email" type="email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email" value="{{ old('email') ?? $invite->email }}" required readonly>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">

                                <label for="zip">Zip Code</label>
                                <input id="zip" type="text"
                                       class="form-control{{ $errors->has('zip') ? ' is-invalid' : '' }}"
                                       name="zip" value="{{ old('zip') }}" required>

                                @if ($errors->has('zip'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('zip') }}</strong>
                                    </span>
                                @endif

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

                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required>
                            </div>
                        </div>

                        <input type="hidden" id="token" name="token" value={{$invite->token}}>
                    @endcomponent
                </div>
            </div>
        </div>

@endsection

@extends('layouts.sign-in')

@section('main')
    <div class="container" v-cloak>
        <div class="row justify-content-center">
            <div class="col-sm-8 col-md-8 col-lg-8">
                @component('components.card-form',[
                    'type' => 'form',
                    'action' => route('login'),
                    'buttonText' => 'Login'
                ])
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <h3>Login and Start Living With Peace of Mind</h3>
                            <p>Advice, checklists, qualified providers, and an individual plan to guide your journey.</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <label for="email">E-Mail Address</label>

                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                   value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
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

                    <div class="form-group row mb-0">
                        <div class="col-12">
                            <div class="checkbox">
                                <label id="remember" class="form-check-inline">
                                <input type="checkbox"
                                       aria-labelledby="remember"
                                       class="form-check-inline mr-1"
                                       name="remember" {{ old('remember') ? 'checked' : '' }}>

                                Remember Me</label>
                            </div>
                        </div>
                    </div>
                @endcomponent
            </div>

            <div class="col-12">
                <div class="text-center py-2 small">
                    <a href="{{ route('password.request') }}">
                        <strong>Forgot Your Password?</strong>
                    </a>

                    <span>|</span>

                    <span>New around here?
                        <a href="{{ route('customer-invite') }}">
                            <strong>Sign Up.</strong>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>

@endsection

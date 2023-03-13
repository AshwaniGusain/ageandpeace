@extends('layouts.sign-in')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                @component('components.card-form', [
                    'type' => 'form',
                    'action' => route('password.email'),
                    'buttonText' => 'Send Password Reset Link'
                ])

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <h3>Reset Password</h3>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12">
                            <label for="email">E-Mail Address</label>

                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                   value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
    </div>
@endsection

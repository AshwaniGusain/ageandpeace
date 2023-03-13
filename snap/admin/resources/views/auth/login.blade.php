@extends('admin::layouts.login')

@section('body')

    <div class="card">
        <div class="card-header text-center">
            <h3>{{ trans('admin::auth.login_heading', ['name' => config('snap.admin.title')]) }}</h3>
            {{--<img src="{{ asset('assets/snap/images/logo_snap_dark_web.png') }}" height="50">--}}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin/login') }}">
                @csrf

                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label text-md-right">{{ trans('admin::auth.label_email') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ trans('admin::auth.label_password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ trans('admin::auth.label_remember') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

                        <a class="btn btn-link" href="{{ route('admin/password/reset') }}">
                            {{ trans('admin::auth.label_forgot_password') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </main>
@endsection
@extends('layouts.app')
@section('page-title', page_title(['Provider'], ' | '))

@section('content')
    <div class="container py-10">
        <div class="row">
            <div class="col-12">
                <h1>Update Profile</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{url('me')}}">
                            @csrf

                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" placeholder="Enter name" value="{{$user->name}}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" placeholder="{{$user->email}}" value="{{$user->email}}" class="form-control">
                                </div>
                            </div>

                            @if ($user->hasRole('customer'))

                                @include('profiles.customer-profile-form')

                            @endif

                            @if ($user->hasRole('provider'))

                                @include('profiles.provider-profile-form')

                            @endif

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <modal-trigger {{ $errors->has('password_confirmation') || $errors->has('password') ? 'start-open': '' }}>
                    <a href="#" slot="openButton" slot-scope="{show}" @click.prevent="show">Change Password</a>
                    @include('modals.change-password')
                </modal-trigger>
            </div>
        </div>
    </div>
@endsection

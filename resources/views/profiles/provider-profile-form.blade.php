<div class="form-group row">
    <div class="col-6">
        <label for="name">Street</label>
        <input type="text" name="street" placeholder="" value="{{$user->provider->street}}"
               class="form-control {{ $errors->has('street') ? ' is-invalid' : '' }}">

        @if ($errors->has('street'))
            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('street') }}</strong>
                                        </span>
        @endif
    </div>

    <div class="col-6">
        <label for="name">City</label>
        <input type="text" name="city" placeholder="" value="{{$user->provider->city}}"
               class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}">

        @if ($errors->has('city'))
            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <div class="col-6">
        <label for="name">State</label>
        <input type="text" name="state" placeholder="" value="{{$user->provider->state}}"
               class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}">

        @if ($errors->has('state'))
            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
        @endif
    </div>

    <div class="col-6">
        <label for="name">Zip</label>
        <input type="number" name="zip" placeholder="" value="{{$user->provider->zip}}"
               class="form-control {{ $errors->has('zip') ? ' is-invalid' : '' }}">

        @if ($errors->has('zip'))
            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('zip') }}</strong>
                                        </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <div class="col-6">
        <label for="name">Phone</label>
        <input type="text" name="phone" placeholder="" value="{{$user->provider->phone}}"
               class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}">

        @if ($errors->has('phone'))
            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
        @endif
    </div>

    <div class="col-6">
        <label for="name">Website</label>
        <input type="text" name="website" placeholder=""
               value="{{$user->provider->website}}"
               class="form-control {{ $errors->has('website') ? ' is-invalid' : '' }}">

        @if ($errors->has('website'))
            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('website') }}</strong>
                                        </span>
        @endif
    </div>
</div>
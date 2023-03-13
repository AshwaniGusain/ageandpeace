<div class="form-group row">
    <div class="col-6">
        <label for="name">Age</label>
        <input type="number" name="age" placeholder="" value="{{$user->age}}" class="form-control">
    </div>

    <div class="col-6">
        <label for="name">Zip Code</label>
        <input type="number" name="zip" placeholder="" value="{{$user->zip}}" class="form-control {{ $errors->has('zip') ? ' is-invalid' : '' }}">

        @if ($errors->has('zip'))
            <span class="invalid-feedback">
                                            <strong>{{ $errors->first('zip') }}</strong>
                                        </span>
        @endif
    </div>
</div>
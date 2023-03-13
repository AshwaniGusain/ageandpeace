<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdate;
use App\Http\Requests\ProviderProfileUpdate;
use App\Notifications\EmailChange;
use App\Notifications\PasswordChange;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Snap\Support\Helpers\GoogleHelper;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {

        $user = $request->user();

        return view('profile', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        if ($request->password) {
            $this->updatePassword($user, $request->password);
            session()->flash('success_message', 'Password updated successfully.');
        } else {
            if ($user->hasRole('customer')) {
                $this->updateCustomerProfile($request, $user);
            } elseif ($user->hasRole('provider')) {
                $this->updateProviderProfile($request, $user);
            }
            session()->flash('success_message', 'Profile Updated');
        }

        return view('profile', compact('user'));
    }

    public function destroy()
    {

    }

    public function updatePassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();
        $user->notify(new PasswordChange($user));
    }

    public function updateCustomerProfile(Request $request, $user)
    {
        $formRequest = new ProfileUpdate();
        $validator = Validator::make($request->all(), $formRequest->rules());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $customer = $user->customer;
        $user->name = $request->name;
        if ($request->email !== $user->email) {
            $user->notify(new EmailChange($user, $request->email));
            $user->email = $request->email;
        }
        $customer->age = $request->age;
        $customer->zip = $request->zip;

        if (isset($request->zip)){
            $coords = GoogleHelper::geoLocate($request->zip);

            if ($coords['latitude'] != 0 && $coords['longitude'] != 0) {
                $customer->geo_point = new Point($coords['latitude'], $coords['longitude']);
            }
        }

        $user->save();
        $user->customer->save();
    }

    public function updateProviderProfile(Request $request, $user)
    {
        $formRequest = new ProviderProfileUpdate();
        $validator   = Validator::make($request->all(), $formRequest->rules());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $user->name = $request->name;
        $provider   = $user->provider;
        if ($request->email !== $user->email) {
            $user->notify(new EmailChange($user, $request->email));
            $user->email = $request->email;
        }
        $provider->street  = $request->street;
        $provider->city    = $request->city;
        $provider->state   = $request->state;
        $provider->zip     = $request->zip;
        $provider->phone   = $request->phone;
        $provider->website = $request->website;

        $user->save();
        $user->provider->save();
    }
}

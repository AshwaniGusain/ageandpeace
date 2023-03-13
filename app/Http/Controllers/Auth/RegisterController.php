<?php

namespace App\Http\Controllers\Auth;

use App\Models\Customer;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Zip;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Invite;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';


    protected $invite = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if (! $this->invite = Invite::where('token', $data['token'])->first()) {
            abort(404);
        }

        $data['email'] = $this->invite->email;

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:snap_users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
	    $user = User::create([
		    'name' => $data['name'],
		    'email' => $this->invite->email, // <!-- Don't let them change their email since it's already been validated
		    'password' => Hash::make($data['password']),
	    ]);

	    $user->assignRole('customer');

	    $customerModel = new Customer();

	    $zip = Zip::where('zipcode', $data['zip'])->first();

        if ($zip) {
            $customerModel->zip = $zip->zipcode;
        } else {
            $customerModel->zip = 99999;
        }

        $customerModel->user()->associate($user);
	    $customerModel->save();

        $this->invite->delete();

        return $user;
    }
}

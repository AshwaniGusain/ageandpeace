<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteInviteRequest;
use App\Mail\InviteCreated;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class InviteController extends Controller
{
    public function create(){
    	$roles = Role::where('name', '!=', 'customer')->get();
    	return view('invites.create', compact('roles'));
    }

    public function store(Request $request){
		do {
			$token = Str::random();
		}
		while (Invite::where('token', $token)->first());

		$invite = Invite::create([
			'email' => $request->get('email'),
			'token' => $token,
			'role' => $request->get('role'),
		]);

		Mail::to($request->get('email'))->send(new InviteCreated($invite));

		return redirect()->back();
    }

    public function accept($token){
	    $invite = Invite::where('token', $token)->first();
		return view('invites.accept', compact('invite'));

    }

	public function complete(CompleteInviteRequest $request, $token){
		if (!$invite = Invite::where('token', $token)->first()){
			abort(404);
		}

		// todo validate the request passwords and email address unique
		$user = User::create([
			'email' => $invite->email,
			'name' => $request->get('name'),
			'password' => bcrypt($request->get('password'))
			]);

		$user->assignRole($invite->role);

		$invite->delete();

		return 'Good job!  Invite Accepted';

	}


}

<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Invite;
use App\Mail\CustomerInviteCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerInviteController extends Controller
{
    public function invite()
    {
        return view('auth.invite');
    }

    public function store(Request $request)
    {
        /* STEPS
         * 1. Low-fi way to check if it's a bot check with honey-pot random redirect
         * 2. Validate the email address
         * 3. Check if the email is already a registered user
         * 4. Check if an invite has already been sent, and if so use that or create a new one with updated token
         * */

        /*
         * 1. Low-fi way to check if it's a bot check with honey-pot random redirect
         * */
        if ($request->input('random')) {
            return redirect()->home();
        }

        /*
         * 2. Validate the email address
         * */
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            session()->flash('error_message', 'Invalid email');
            return redirect('/#signup');
        }

        /*
         * 3. Check if the email is already a registered user
         * */
        if (User::where('email', $request->get('email'))->exists()) {
            session()->flash('error_message', 'Already a user');
            return redirect('/#signup');
        }

        /*
         * 4. Check if the email is already a registered user
         * */
        do {
            $token = Str::random();
        } while (Invite::where('token', $token)->first());

        $invite = Invite::firstOrCreate([
            'email' => $request->get('email'),
            'role' => 'customer'
        ]);

        $invite->token = $token;
        $invite->save();

        Mail::to($request->get('email'))->send(new CustomerInviteCreated($invite));

        session()->flash('success_message', 'Invite sent to your email');

        return redirect()->home();
    }

    public function accept($token)
    {
        $invite = Invite::where('token', $token)->first();

        return view('auth.register', compact('invite'));
    }
}

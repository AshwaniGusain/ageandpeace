<?php

namespace Snap\Admin\Http\Controllers;

use App\Notifications\PasswordChange;
use Illuminate\Http\Request;

class MeController extends AdminBaseController
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return ui('me', ['user' => $request->user()], function ($ui) {
        })->render();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('password')) {
            $user->password = $request->input('password');
            $user->addRule('password', 'min:8');
        }

        // We will validate before calling save and changing the password to bcrypt
        // to make sure it has the minimum length.
        if ( ! $user->validate()) {
            return redirect()->back()->withErrors($user->getErrors());
        }

        if ($request->input('password')) {
            $user->password = bcrypt($user->password);
        }

        // No need to validate on save again since we validated above.
        $user->save(['validate' => false]);

        return back()->with('success', trans('admin::resources.save_success'));
    }

}

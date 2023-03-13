<?php

namespace Tests\Feature\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait AdminAuthTrait
{
    protected $adminUser;

    protected function login()
    {
        Session::start();

        $adminUser = Factory(\App\Models\User::class)->create();
        $adminUser->assignRole('admin');
        $this->be($adminUser);
        $this->adminUser = $adminUser;
    }

    protected function logout()
    {
        Auth::logout();
    }
}

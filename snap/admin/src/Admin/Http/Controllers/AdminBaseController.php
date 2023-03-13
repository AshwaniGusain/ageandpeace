<?php

namespace Snap\Admin\Http\Controllers;

use Snap\Admin\AdminManager;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

abstract class AdminBaseController extends Controller
{
    use AuthenticatesUsers;

    protected $app;
    protected $admin;
    protected $request;

    public function __construct(Request $request, AdminManager $admin, Application $app)
    {
        $this->app = $app;
        $this->admin = $admin;
        $this->request = $request;
        $this->initialize();
    }

    protected function initialize()
    {

    }

}

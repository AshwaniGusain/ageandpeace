<?php

namespace Snap\Cache\Http\Controllers;

use Cache;
use Artisan;
use Illuminate\Http\Request;
use Snap\Admin\Http\Controllers\ModuleController;
use Snap\Cache\Ui\CacheIndexPage;

class CacheController extends ModuleController
{
    public function index()
    {
        return new CacheIndexPage();
    }

    public function doClear(Request $request)
    {
        Cache::flush();
        Artisan::call('view:clear');

        //Artisan::call('cache:clear');
        //Artisan::call('route:cache');
        //Artisan::call('config:cache');

        $url = url()->previous();

        return redirect($url)->with('success', trans('cache::messages.cache_cleared'));
    }


}
<?php

namespace Snap\Menu;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        // Setup singleton binding for the admin object.
        $this->app->bind('snap.menu', function ($app) {
            return new MenuBuilder();
        });

        $this->app->alias('snap.menu', MenuBuilder::class);
    }

    public function boot()
    {

    }
}
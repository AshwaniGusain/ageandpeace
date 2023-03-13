<?php

namespace Snap\Cache;

use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {

    }

    public function boot()
    {
        // Load translations from the page namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cache');

        // Register the admin view namespace.
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cache');
    }


}
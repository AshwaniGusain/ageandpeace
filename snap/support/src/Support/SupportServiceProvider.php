<?php

namespace Snap\Support;

use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {

    }

    public function boot()
    {
        // Load translations from the admin namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'support');
    }

}
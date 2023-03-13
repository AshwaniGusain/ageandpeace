<?php

namespace Snap\DataTable;

use Illuminate\Support\ServiceProvider;

class DataTableServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        // Load translations from the datatable namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'datatable');

        // Publish the admin config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../resource/lang/en/datatable.php' => base_path('resources/lang/vendor/datatable/datatable.php')
            ], 'datatable');
        }
    }
}
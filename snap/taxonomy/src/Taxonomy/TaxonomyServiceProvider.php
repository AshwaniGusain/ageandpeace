<?php

namespace Snap\Taxonomy;

use Illuminate\Support\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/taxonomy.php', 'snap.taxonomy'
        );

        $this->app->singleton('snap.taxonomy', function ($app) {
            return new TaxonomyManager(config('snap.taxonomy.meta', []), config('snap.taxonomy.default'));
        });
    }

    public function boot()
    {
        //// Register the admin view namespace.
        //$this->loadViewsFrom(__DIR__.'/../../resources/views', 'content');

        // Load the taxonomy specific routes.
        //$this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        //
        //// Publish the ui config to the config/snap folder.
        //if ($this->app->runningInConsole()) {
        //    $this->publishes([
        //        __DIR__.'/../../config/ui.php' => config_path('snap/ui.php'),
        //    ]);
        //}
    }

}
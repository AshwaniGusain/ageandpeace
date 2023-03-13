<?php 

namespace Snap\Core;

use Snap\Decorator\DecoratorServiceProvider;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class SNAPServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
        $this->app->singleton('snap', function($app) {
            return new SNAPManager();
        });

        $this->app->alias('snap', SNAPManager::class);
    }

    public function boot()
    {
        //// Register the admin view namespace.
        //$this->loadViewsFrom(__DIR__.'/../../resources/views', 'component');
        //
        //// Publish the ui config to the config/snap folder.
        //if ($this->app->runningInConsole()) {
        //    $this->publishes([
        //        __DIR__.'/../../config/ui.php' => config_path('snap/ui.php'),
        //    ]);
        //}
    }

}
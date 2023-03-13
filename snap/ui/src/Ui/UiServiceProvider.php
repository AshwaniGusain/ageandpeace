<?php 

namespace Snap\Ui;

use Snap\Decorator\DecoratorServiceProvider;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class UiServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
        $this->app->singleton('snap.ui', function($app) {
            return new UiFactory($this->app, $this->app['view']);
        });

        $this->app->alias('snap.ui', UiFactory::class);

        // Bind any general UI elements found in the general snap/ui config
        \UI::bind(config('snap.ui.bindings'))->addDataTypes(config('snap.ui.dataTypes'));

        //event('docs.add', ['ui', __DIR__.'/../docs/', 'SNAP']);
    }

    public function boot()
    {
        // Register the admin view namespace.
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'component');

        // Publish the ui config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/ui.php' => config_path('snap/ui.php'),
            ]);
        }
    }

}
<?php 

namespace Snap\Decorator;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class DecoratorServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
    	// Handled in composer.json
        // Load helpers.
        // include_once(__DIR__.'/../helpers.php');

        // Merge published config with the default.
        $this->mergeConfigFrom(
            __DIR__.'/../../config/decorators.php', 'snap.decorators'
        );

        // Setup singleton binding for the admin object.
        $this->app->singleton('snap.decorator', function ($app) {
            return new \Snap\Decorator\DecoratorFactory(config('snap.decorators'));
        });

        $this->app->alias('snap.decorator', DecoratorFactory::class);

        // Handled in composer.json
        // Create facade (e.g. Admin::modules()).
        // $aliasLoader = AliasLoader::getInstance();
        // $aliasLoader->alias('Decorator', DecoratorFacade::class);
    }

    public function boot()
    {
        // Publish the admin config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/decorators.php' => config_path('snap/decorators.php'),
            ]);
        }
    }

}

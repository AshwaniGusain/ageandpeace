<?php 

namespace Snap\Asset;

use Snap\Support\Interpolator;
use Illuminate\Support\ServiceProvider;
// use Illuminate\Foundation\AliasLoader;

class AssetServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
        // Handled in composer.json
        // Load helpers.
        // include_once(__DIR__.'/../helpers.php');

        // Merge published config with the default.
        $this->mergeConfigFrom(
            __DIR__.'/../../config/assets.php', 'snap.assets'
        );

        // Setup singleton binding for the admin object.
        $this->app->singleton('snap.asset', function ($app) {
            return new Asset(config('snap.assets'), new Interpolator());
        });

        // $this->app->alias('asset', \Snap\Asset\Asset::class);

        // Handled in composer.json
        // $aliasLoader = AliasLoader::getInstance();
        // $aliasLoader->alias('Asset', AssetFacade::class);
    }

    public function boot()
    {
        // Publish the asset config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/assets.php' => config_path('snap/assets.php'),
            ]);
        }
    }

}
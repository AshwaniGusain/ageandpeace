<?php 

namespace Snap\Media;

use Illuminate\Support\ServiceProvider;
use Snap\Media\MediaManager;
use Snap\Ui\UiFactory;

class MediaServiceProvider extends ServiceProvider
{
    protected $defer = false;

    protected $providers = [];

    public function register()
    {
        // Handled in composer.json
        // include_once(__DIR__.'/../helpers.php');

        // Merge published config with the default.
        $this->mergeConfigFrom(__DIR__.'/../../config/media.php', 'snap.media');

        // Setup singleton binding for the admin object.
        $this->app->singleton('snap.media', function ($app) {
            return new MediaManager(config('snap.media.meta', []));
        });

        $this->app->alias('snap.media', MediaManager::class);

    }

    public function boot()
    {
        // Load translations from the admin namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'media');

        // Register the admin view namespace.
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'media');

        // Publish the admin config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/media.php' => config_path('snap/media.php')
            ], 'admin');
        }
    }
}
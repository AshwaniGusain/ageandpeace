<?php 

namespace Snap\Analytics;

use Snap\Decorator\DecoratorServiceProvider;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Snap\GoogleAnalytics\Facades\Analytics;

class AnalyticsServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
        $this->app->singleton('snap.google_analytics', function($app) {
            return new AnalyticsManager();
        });

        $this->app->alias('snap.google_analytics', AnalyticsManager::class);
    }

    public function boot()
    {
        snap()->attach('google_analytics');

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
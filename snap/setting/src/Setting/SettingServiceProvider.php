<?php 

namespace Snap\Setting;

use Illuminate\Support\ServiceProvider;
use Snap\Menu\MenuBuilder;

class SettingServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/settings.php', 'snap.settings'
        );

        $this->app->singleton('snap.setting', function ($app) {

            return new SettingsManager(\Form::make());
        });


    }

    public function boot()
    {
        // Load translations from the nav namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'settings');

        // Register the nav view namespace.
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'settings');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../database/migrations/2017_01_01_000000_create_snap_settings_table.stub' => database_path('migrations/2017_01_01_000000_create_snap_settings_table.php'),
                __DIR__.'/../../config/settings.php'                                                   => config_path('snap/settings.php'),
            ], 'settings');
        }

    }

}
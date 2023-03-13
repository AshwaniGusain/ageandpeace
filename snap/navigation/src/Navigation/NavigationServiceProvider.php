<?php 

namespace Snap\Navigation;

use Illuminate\Support\ServiceProvider;
use Snap\Menu\MenuBuilder;

class NavigationServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/navigation.php', 'snap.navigation'
        );

        $this->app->singleton('snap.nav', function ($app) {

            return new NavBuilder(new MenuBuilder(), config('snap.navigation.check_db', false));
        });


    }

    public function boot()
    {
        // Load translations from the nav namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'nav');

        // Register the nav view namespace.
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'nav');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../database/migrations/2017_01_01_000000_create_snap_navigation_table.stub' => database_path('migrations/2017_01_01_000000_create_snap_navigation_table.php'),
                __DIR__.'/../../database/migrations/2017_01_01_000000_create_snap_navigation_groups_table.stub' => database_path('migrations/2017_01_01_000000_create_snap_navigation_groups_table.php'),
                __DIR__.'/../../config/navigation.php'                                                   => config_path('snap/navigation.php'),
            ], 'nav');
        }

    }

}
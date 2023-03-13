<?php 

namespace Snap\Database;

use DB;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
        // Merge published config with the default.
        $this->mergeConfigFrom(
            __DIR__.'/../../config/db.php', 'snap.db'
        );
    }

    public function boot()
    {
        // Load translations from the admin namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'db');

        if (config('snap.db.log_queries')) {
            \DB::enableQueryLog();
        }

        // @TODO - put into base service provider 
        foreach (glob(__DIR__.'/Model/Macros/*.macro.php') as $filename) { 
            require_once($filename);
        }

        // Publish the asset config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/db.php' => config_path('snap/db.php'),
            ],'database');
        }
    }

}
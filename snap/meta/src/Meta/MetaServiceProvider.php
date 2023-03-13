<?php 

namespace Snap\Meta;

use Illuminate\Support\ServiceProvider;

class MetaServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
    }

    public function boot()
    {
        // Publish the asset config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../database/migrations/2017_01_01_000000_create_snap_meta_table.stub' => database_path('migrations/2017_01_01_000000_create_snap_meta_table.php'),
            ]);
        }
    }

}
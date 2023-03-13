<?php 

namespace Snap\Admin;

use Illuminate\Support\ServiceProvider;
use App\Admin\Providers\AdminServiceProvider as AppAdminServiceProvider;
use Snap\Admin\Modules\ModuleManager;
use Snap\Ui\UiFactory;

class AdminServiceProvider extends ServiceProvider
{
    protected $defer = false;

    protected $providers = [];

    public function register()
    {
        // Handled in composer.json
        // include_once(__DIR__.'/../helpers.php');

        // Merge published config with the default.
        $this->mergeConfigFrom(__DIR__.'/../../config/admin.php', 'snap.admin');
        //$this->mergeConfigFrom(__DIR__.'/../../config/media.php', 'snap.media');

        // Load Admin specific service providers.
        $this->app->register(AppAdminServiceProvider::class);

        // Setup singleton binding for the admin object.
        $this->app->singleton('snap.admin', function ($app) {
            $modules = $app[ModuleManager::class];
            $ui = $app[UiFactory::class];

            return new AdminManager($modules, $ui);
        });

        $this->app->alias('snap.admin', AdminManager::class);

        // Setup singleton binding for the modules object.
        $this->app->singleton('snap.modules', function ($app) {
            return new ModuleManager(config('snap.admin.modules'));
        });

        $this->app->alias('snap.modules', ModuleManager::class);

        // Setup admin console commands to generate Modules.
        $this->commands([
            Console\Commands\ModuleGenerator::class,
            Console\Commands\ModelGenerator::class,
            Console\Commands\ModuleControllerGenerator::class,
            //Console\Commands\ModuleInstaller::class
        ]);
    }

    public function boot()
    {
        snap()->attach(['admin', 'modules']);

        // Load translations from the admin namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'admin');

        // Register the admin view namespace.
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin');

        // Load the admin specific routes.
        if (file_exists($routes = admin_path('routes.php'))) {
            $this->loadRoutesFrom($routes);
        }

        // Publish the admin config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/admin.php' => config_path('snap/admin.php'),
            ], 'admin');
        }

    }
}
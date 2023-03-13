<?php 

namespace Snap\Docs;

use Illuminate\Support\ServiceProvider;

class DocsServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        // Merge published config with the default.
        $this->mergeConfigFrom(
            __DIR__.'/../../config/docs.php', 'snap.docs'
        );

        // Setup singleton binding for the admin object.
        $this->app->singleton('docs', function ($app) {
            return new DocsManager(config('snap.docs'), $app['markdown'], $app['markdown.environment'], new FuncReplacer(config('snap.docs.allowed_funcs') ? : []));
        });

        $this->app->alias('docs', DocsManager::class);

        \Event::listen('docs.loaded', function($callback){
            $callback();
        });

        //\Event::listen('docs.add', function($handle, $dir, $label = null, $section = null){
        //    \Docs::add($handle, $dir, $label, $section);
        //});

    }

    public function boot()
    {
        //$this->loadRoutesFrom();
        // Load translations from the admin namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'docs');

        // Register the admin view namespace.
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'docs');

        // Publish the asset config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/docs.php' => config_path('snap/docs.php'),
            ]);
        }

        snap()->attach('docs');

    }

}
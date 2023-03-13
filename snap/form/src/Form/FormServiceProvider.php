<?php 

namespace Snap\Form;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        // Merge published config with the default.
        $this->mergeConfigFrom(
            __DIR__.'/../../config/forms.php', 'snap.forms'
        );

        // Setup singleton binding for the admin object.
        $this->app->singleton('snap.form', function ($app) {
            return new FormFactory(config('snap.forms'), $app);
        });

        $this->app->alias('snap.form', \Snap\Form\FormFactory::class);
    }

    public function boot()
    {
        // Add the form_element function as one of the allowed functions
        // to execute when reviewing the form documentation.
        event('docs.loaded', function(){
            \Docs::funcs()->add('form_element');
        });

        // Load translations from the admin namesapce.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'form');

        // Register the admin view namespace.
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'form');

        // Publish the asset config to the config/snap folder.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/forms.php' => config_path('snap/forms.php'),
            ]);
        }
    }

}
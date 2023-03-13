<?php

namespace Snap\Page;

use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/pages.php', 'snap.pages'
        );

        $this->app->singleton('snap.page', function ($app) {

            return new PageManager();
        });

        $this->app->singleton('snap.template', function ($app) {

            return new TemplateManager(config('snap.pages.templates'));
        });

        $this->app->singleton('snap.urls', function ($app) {

            return new UrlManager();
        });

        //$this->routeUrlsMacro();
        $this->routePublicResourceMacro();
        $this->routePagesMacro();
    }

    public function boot()
    {
        // Load translations from the page namespace.
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'page');

        // Register the admin page namespace.
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'page');

        // Load the pages specific routes.
        //$this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../database/migrations/2017_01_01_000000_create_snap_pages_table.stub' => database_path('migrations/2017_01_01_000000_create_snap_pages_table.php'),
                __DIR__.'/../../config/pages.php' => config_path('snap/pages.php'),
            ], 'page');
        }
    }

    //protected function routeUrlsMacro()
    //{
    //    $router = $this->app['Illuminate\Routing\Router'];
    //
    //    $router->macro('urls', function ($url, $label = null) {
    //        app('snap.urls')->add($url, $label);
    //
    //        return $this;
    //    });
    //}

    protected function routePublicResourceMacro()
    {
        $router = $this->app['Illuminate\Routing\Router'];

        $router->macro('resourcePages', function ($prefix, $controller, $only = ['listing', 'slug', 'archive', 'search', 'taxonomy']) use ($router) {
            $router->group([
                'prefix' => $prefix,
                //'middleware' => ['shopping'],
            ], function () use ($router, $prefix, $controller, $only) {
                $verbs = ['get', 'post'];

                if (in_array('archive', $only)) {
                    $whereConstraints = ['year' => '[0-9]{4}', 'month' => '[0-9]{2}', 'day' => '[0-9]{2}'];
                    $router->match($verbs, '/{year}/{month?}/{day?}', $controller.'@listing')->where($whereConstraints)->name($prefix.'/archive');
                    $router->match($verbs, '/{year}/{month}/{day}/{model}', $controller.'@post')->where($whereConstraints)->name($prefix.'/archive/slug');
                }

                if (in_array('taxonomy', $only)) {
                    $router->match($verbs, '/{taxonomy}/{term}', $controller.'@taxonomy')->name($prefix.'/taxonomy');
                }

                if (in_array('search', $only)) {
                    $router->match($verbs, '/search', $controller.'@search')->name($prefix.'/search');
                }

                if (in_array('listing', $only)) {
                    $router->match($verbs, '/', $controller.'@listing')->name($prefix.'/listing');
                }

                if (in_array('slug', $only)) {
                    $router->match($verbs, '/{model}', $controller.'@slug')->name($prefix.'/slug');
                }

                return $this;
            });
        });
    }

    protected function routePagesMacro()
    {
        $router = $this->app['Illuminate\Routing\Router'];

        $router->macro('pages', function ($uri = null, $middleware = []) use ($router) {

            if (is_null($uri)) {
                $where = '.*';
                $uri = '/{uri?}';
            } else {
                $where = $uri;
                $uri = '/{uri}';
            }

            $router->group([], function () use ($router, $uri, $where, $middleware) {
                $verbs = ['get', 'post'];

                $router->match($verbs, $uri, [
                    'uses' => '\Snap\Page\Http\Controllers\PageController@pages',
                ])
                       ->where(['uri' => $where])
                       ->middleware($middleware)
                       ->name('pages');

            });

            return $this;
        });
    }

}
<?php

namespace Snap\Page\Modules\Services;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Snap\Page\Http\Controllers\PublicResourcePageController;

/*
$public = PublicService::make();
$public
    ->prefix('/about/')
    ->fromInput('slug')
;
 * */
class PublicRoutesService
{
    //public $prefix = '';
    public $slugInput = 'slug';
    public $prefix = '';
    public $controller = PublicResourcePageController::class;
    public $routes = [
        'listing',
        'slug',
        'archive',
        'search',
        'taxonomy'
    ];
    protected $module;
    protected $request;
    protected $router;
    protected $urls;
    protected $urlsCallback;

    public function __construct($module, Request $request, Router $router)
    {
        $this->module = $module;
        $this->request = $request;
        $this->router = $router;
        $this->urls[] = function($model){
            if (method_exists($model, 'getPublicUrlAttribute')) {
                return $model->public_url;
            }

            return null;
        };
        //$this->prefix = '/'.$this->module->handle();
    }

    public static function make($module)
    {
        $service = new static($module, request(), app('router'));

        return $service;
    }

    public function slugInput($name)
    {
        $this->slugInput = $name;

        return $this;
    }

    public function prefix($prefix)
    {
        $this->prefix = rtrim($prefix, '/');

        return $this;
    }

    public function controller($controller)
    {
        $this->controller = $controller;

        return $this;
    }


    public function route($controller = null, $routes = null)
    {
        if ($controller) {
            $this->controller($controller);
        }

        if ($routes) {
            $this->routes($routes);
        }

        $this->router->resourcePages($this->prefix, $this->controller, $this->routes);

    }

    public function urls($urls)
    {
        $urls = is_array($urls) ? $urls : func_get_args();

        $this->urls = $urls;

        return $this;
    }

    public function getUrls()
    {
        $urls = $this->urls;
        if ($this->controller && $this->routes) {

            $urlClosures = [];

            foreach ($urls as $i => $url) {
                if ($url instanceof \Closure) {
                   $urlClosures[] = $url;
                   unset($urls[$i]);
                }
            }

            if (!empty($urlClosures)) {
                $models = $this->module->getModel()->get();

                foreach ($models as $model) {

                    foreach ($urlClosures as $closure) {
                        $modelUrls = call_user_func($closure, $model);
                        if ($modelUrls) {
                            if (!is_array($modelUrls)) {
                                $modelUrls = [$modelUrls];
                            }

                            $urls = array_merge($urls, $modelUrls);
                        }
                    }
                }
            }
        }

        $urls = array_unique($urls);

        sort($urls, SORT_REGULAR);

        return $urls;
    }

}
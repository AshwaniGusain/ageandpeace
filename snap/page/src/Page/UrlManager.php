<?php

namespace Snap\Page;

use Snap\Page\Models\Page as PageModel;
use Snap\Support\Helpers\FileHelper;

class UrlManager
{
    protected $urls = [];

    public function __construct()
    {
        $this->add($this->getViewPages());
        $this->add($this->getDBPages());
        $this->add($this->getModulePages());
    }

    public function getDBPages()
    {
        $urls = PageModel::onlyPublished()->lists('uri', 'uri')->mapWithKeys(function($uri){
            return [url($uri) => $uri];
        });

        return $urls->toArray();
    }

    public function getViewPages()
    {
        $basePath = resource_path('views');
        $viewFolder = $basePath.'/'.config('snap.pages.auto_page_view_folder');

        $files = FileHelper::filenames($viewFolder, true);
        $urls = [];
        foreach ($files as $path) {
            $path = trim(str_after($path, $viewFolder), '/');
            $path = str_before($path, '.php');
            $uri = str_before($path, '.blade');

            $urls[url($uri)] = $uri;
        }

        return $urls;
    }

    public function getModulePages()
    {
        $modules = (array) \Module::all();
        $urls = [];
        foreach ($modules as $module) {
            if ($module->hasService('publicRoutes')) {
                $moduleUrls = $module->service('publicRoutes')->getUrls();
                if ($moduleUrls) {
                    $urls = array_merge($urls, $moduleUrls);
                }
            }
        }

        return $urls;
    }

    public function add($url)
    {
        if (is_array($url)) {
            foreach ($url as $val) {
                $this->add($val);
            }
        } else {
            $url = trim(str_after($url, url('')), '/');
            if (!in_array($url, $this->urls)) {
                $this->urls[] = $url;
            }
        }

        return $this;
    }

    public function get()
    {
        return collect($this->urls)->sort();
    }

    public function asList()
    {
        $urls = $this->get();
        return $urls->combine($urls);
    }
}
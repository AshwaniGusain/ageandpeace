<?php

namespace Snap\Page;

use Cache;
use Snap\Page\Models\Page as PageModel;
use Snap\Support\Contracts\ToString;

class Page implements ToString
{
    protected $uri;

    protected $data = [];

    protected $template;

    protected $cache;

    protected $cacheKey;

    protected $exists = false;

    public function __construct($uri = null, $data = [])
    {
        $this->uri = $uri;
        $this->data = $data;
    }

    public static function make($uri, $data = [])
    {
        return new static($uri, $data);
    }

    public function with($key, $data = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $data;
        }

        return $this;
    }

    public function cache(bool $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    public function cacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }

    public function getCacheKey()
    {
        return is_null($this->cacheKey) ? 'page_'.$this->uri : $this->cacheKey;
    }

    public function isCacheable()
    {
        $cache = is_null($this->cache) ? config('snap.pages.cache') : $this->cache;

        // If you are logged in, then we will never pull from the cache.
        $loggedIn = ! empty(\Auth::user());

        return ! $loggedIn && $cache;
    }

    public function get()
    {
        $page = $this->getFromDB();

        if (! $page) {
            $page = $this->getFromView();
        }

        return $page;
    }

    public function getFromDB()
    {
        $segments = explode('/', $this->uri);
        $params = [];
        while (count($segments) >= 1) {

            $uri = implode('/', $segments);
            $page = PageModel::where('uri', $uri)->first();

            if (! empty($page) && count($params) <= $page->numUriParams) {
                return $page->ui($this->data);
            }

            // We reduce the number of segments on the URI and keep the loop going.
            $params[] = array_pop($segments);
        }

        return null;
    }

    public function getFromView()
    {
        $viewPath = config('snap.pages.auto_page_view_folder').'.'.str_replace('/', '.', $this->uri);
        if (view()->exists($viewPath)) {
            return ui($viewPath, $this->data);
        }

        return null;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        $cacheKey = $this->getCacheKey();
        $cacheable = $this->isCacheable();

        if ($cacheable && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $page = $this->get();

        if ($page) {
            $output = $page->render();
            if ($cacheable) {
                Cache::forever($cacheKey, $output);
            }

            return $output;
        }

        return null;
    }
}
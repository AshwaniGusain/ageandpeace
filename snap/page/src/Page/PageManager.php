<?php

namespace Snap\Page;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Snap\Website\Models\Page;
use Snap\Website\Templates\Contracts\RoutableTemplateInterface;

class PageManager
{
    protected $multiSite = false;

    public function __construct()
    {

    }

    public function findPage($uri, $data = [])
    {
        $page = $this->findDBPage($uri, $data);

        if (!$page) {
            $page = $this->findViewPage($uri, $data);
        }

        return $page;
    }

    public function findDBPage($uri, $data = [])
    {
        $segments = explode('/', $uri);
        $params = [];
        while(count($segments) >= 1) {

            $uri = implode('/', $segments);
            $page = Page::where('uri', $uri)->first();

            if (!empty($page) && count($params) <= $page->numUriParams) {
                $page->fill($data);
                return $page->ui();
            }

            // We reduce the number of segments on the URI and keep the loop going.
            $params[] = array_pop($segments);
        }

        return null;
    }

    public function findViewPage($uri, $data = [])
    {
        $viewPath = config('snap.website.auto_page_view_folder').'.'.str_replace('/', '.', $uri);
        if (view()->exists($viewPath)) {
            return ui($viewPath)->with($data);
        }

        return null;
    }

    public function isMultiSite()
    {
        return $this->multiSite;
    }
}
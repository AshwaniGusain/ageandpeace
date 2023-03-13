<?php

namespace Snap\Page;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Snap\Website\Templates\Contracts\RoutableTemplateInterface;

class TemplateManager
{
    protected $templates = null;
    protected $defaultGroup = 'default';

    public function __construct($templates = [])
    {
        //$this->templates = new Collection($templates);
        if ($templates) {
            $this->addMultiple($templates);
        }
    }

    public function addMultiple($templates, $group = null)
    {
        foreach ($templates as $g => $template) {
            if (is_string($g) && is_array($template)) {
                $this->addMultiple($template, $g);
            } else {
                $this->add($template, $group);
            }
        }

        return $this;
    }

    public function add($template = null, $group = null)
    {
        if (!$group) {
            $group = $this->defaultGroup;
        }

        $template = new $template();
        $this->templates[$group][$template->handle()] = $template;

        return $this;
    }

    public function get($handle)
    {
        $templates = $this->all();
        foreach ($templates as $template) {
            if ($template->handle() == $handle) {
                return $template;
            }
        }

        return null;
        //if ($key) {
        //    if (isset($this->templates[$key])) {
        //        return $this->templates[$key];
        //    }
        //    return null;
        //}
        //
        //return $this->templates;
    }

    public function group($group)
    {
        if (isset($this->templates[$group])) {
            return collect($this->templates[$group]);
        }

        return collect([]);
    }

    public function all()
    {
        $templates = collect($this->templates)->flatten();

        return $templates;
    }

    public function listing($group = null)
    {
        if ($group) {
            $templates = $this->templates[$group];
        }
        dd($templates);
        return $this->templates->filter(function ($item) use ($tags) {
            return array_intersect((array)$tags, $item->tags);
        })->map(function ($item) {
            return $item->name();
        });
    }
    //
    //public function listingOLD($tags = [])
    //{
    //    return $this->templates->filter(function ($item) use ($tags) {
    //        return array_intersect((array)$tags, $item->tags);
    //    })->map(function ($item) {
    //        return $item->name();
    //    });
    //}
    //
    //public function pageListing()
    //{
    //    return $this->templates->filter(function ($item) {
    //        return ($item instanceof RoutableTemplateInterface);
    //    })->map(function ($item) {
    //        return $item->name();
    //    });
    //    //return $this->listing()->filter(function ($item) {
    //    //    return ($item instanceof RoutableTemplateInterface);
    //    //});
    //}
    //
    //public function blockListing()
    //{
    //    return $this->templates->filter(function ($item) {
    //        return (!$item instanceof RoutableTemplateInterface);
    //    })->map(function ($item) {
    //        return $item->name();
    //    });
    //}
    //
    //public function routes(Router $router)
    //{
    //    if ($this->templates) {
    //        foreach ($this->templates as $template) {
    //            if ($template instanceof RoutableTemplateInterface) {
    //                $template->routes($router);
    //            }
    //        }
    //    }
    //
    //    return $this;
    //}
}
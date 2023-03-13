<?php

namespace Snap\Navigation;

use Snap\Menu\MenuBuilder;
use Snap\Navigation\Models\Navigation;

class NavBuilder
{
    protected $menu;
    protected $checkDB;

    public function __construct(MenuBuilder $menu, $checkDB = false)
    {
        $this->menu = $menu;
        $this->checkDB = $checkDB;
        $this->group();
    }

    public function menu()
    {
        return $this->menu;
    }

    public function checkDB(bool $checkDB)
    {
        $this->checkDB = $checkDB;

        return $this;
    }

    public function getFromDB($group = null)
    {
        $items = [];

        if (is_null($group)) {

            // If no group is passed, we will look for any navigation
            // items associated with a group_id = 1.
            $data = Navigation::whereHas('group', function ($query) use ($group) {
                $query->where('id', '=', 1);
            })->get();

        } else {

            $data = Navigation::whereHas('group', function ($query) use ($group) {
                $query->where('name', '=', $group);
            })->get();
        }

        foreach ($data as $key => $nav) {
            $items[$key] = $this->translateModel($nav);
        }

        return $items;
    }

    protected function translateModel($nav)
    {
        $item = [];
        $item['id'] = $nav->id;
        $item['label'] = $nav->label;
        $item['link'] = $nav->url;
        $item['parent'] = $nav->parent_id;
        $item['linkAttributes'] = $nav->attributes;
        $item['active'] = $nav->active;
        return $item;
    }

    public function group($group = null)
    {
        // If checkDB is true, we'll first check there for any navigation menu items.
        if ($this->checkDB) {
            $items = $this->getFromDB($group);
        }

        // If no items are found in the database we will check the navigation config.
        if (empty($items)) {
            if (empty($group)) {
                $group = 'default';
            }
            $items = config('snap.navigation.menus.'.$group);
        }

        $this->menu->load($items, false);

        return $this;
    }

    public function basic($options = [])
    {
        if ($options) {
            $this->menu->setOptions($options);
        }
        return $this->menu->renderBasic();
    }

    public function breadcrumb($options = [])
    {
        if ($options) {
            $this->menu->setOptions($options);
        }

        return $this->menu->renderBreadcrumb();
    }

    public function collapsible($options = [])
    {
        if ($options) {
            $this->menu->setOptions($options);
        }

        return $this->menu->renderCollapsible();
    }

    public function title($options = [])
    {
        if ($options) {
            $this->menu->setOptions($options);
        }

        return $this->menu->renderTitle();
    }

    public function delimited($options = [])
    {
        if ($options) {
            $this->menu->setOptions($options);
        }

        return $this->menu->renderDelimited();
    }

    public function data($options = [])
    {
        if ($options) {
            $this->menu->setOptions($options);
        }

        return $this->menu ->renderArray();
    }

    public function __toString()
    {
        return $this->menu->__toString();
    }

    public function __call($name, $arguments)
    {
        call_user_func_array([$this->menu, $name], $arguments);

        return $this;
    }

}
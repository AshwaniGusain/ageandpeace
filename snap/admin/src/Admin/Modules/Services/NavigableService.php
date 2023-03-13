<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Snap\Admin\Ui\Layout\Menu;

/*
$navigable = NavigableService::make();

;
*/
class NavigableService
{
    public $handle;
    public $label;
    public $parent;

    protected $module;
    protected $request;

    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function menu($menu, $parent = null)
    {
        //if ($parentHandle = $this->module->menuParent()) {
        //    $label = ucwords($parentHandle);
        //    $menu->add($parentHandle, ['label' => $label, 'link' => false, 'parent' => null]);
        //}
        //

        if ($this->hasAccess($this->module)) {
            if ($label = $this->module->menuLabel()) {
                $handle = $this->module->menuHandle();
                $menu->add($handle, ['label' => '<i class="'.$this->module->icon().'"></i> '.$label, 'link' => $this->module->url(), 'link_attributes' => [], 'parent' => $this->module->menuParent()]);
            }

            foreach ($this->module->modules() as $module) {
                if ($module->hasService('navigable') && $this->hasAccess($module)) {
                    $parent = ($this->module->parent()) ? $this->module->fullHandle() : null;
                    $menu = $module->service('navigable')->menu($menu, $parent);
                }
            }
        }

        return $menu;
    }

    public function hasAccess($module)
    {
        return app('auth')->user()->can($this->module->defaultPermission()) && $module->hasService('navigable');
    }


    public function createMenuItem($handle, $params = [])
    {
        Event::listen(Menu::eventName('initializing'), function($ui) use ($handle, $params){
            $ui->menu->add($handle, $params);
        });
    }


}
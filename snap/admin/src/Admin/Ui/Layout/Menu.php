<?php

namespace Snap\Admin\Ui\Layout;

use Snap\Ui\UiComponent;
use Snap\Menu\MenuBuilder;

class Menu extends UiComponent
{
    //protected $menu;
    protected $items = [];

    protected $data = [
        'object:menu' => MenuBuilder::class,
    ];

    protected $renderType = 'collapsible';

    public function initialize()
    {
        $this->menu->load(config('snap.admin.menu', []));

        // Setup menu items from modules.
        $modules = \Admin::modules()->all();
        foreach ($modules as $key => $module) {
            if (strpos('.', $key) === false) {
                $module->service('navigable')->menu($this->menu);
            }
        }

        // Setup main menu root items if they don't already exist.
        foreach ($modules as $key => $module) {
            if ($this->hasAccess($module)) {
                $parentHandle = $module->menuParent();
                if (!$this->menu->has($parentHandle)) {
                    $label = ucwords($parentHandle);
                    $this->menu->add($parentHandle, ['label' => $label, 'link' => false, 'parent' => null]);
                }
            }
        }

        $tagClasses = [
            // 0 => 'mainnav-level-item',
            // 1 => 'mainnav-level2-item',
            // 'default' => 'nav-item'
            // 'list-group-item'
            0 => 'nav-item',
            1 => 'nav-item',
        ];

        $options = [
            'baseUrl'             => '/',
            // 'itemLinkClasses' => [ 1 => 'nav-link'],
            //'itemLinkClasses'     => [1 => 'nav-link', 2 => 'nav-link', 3 => 'nav-link'],
            'itemLinkClasses'     => [1 => 'nav-link', 2 => 'nav-link', 3 => 'nav-link'],
            // 'autoParent' => true,
            //'containerTag'        => 'div',
            //'containerTagId'      => 'menu',
            //'itemIdPrefix'        => 'menu_',
            // 'containerTagClasses' => ['nav nav-stacked'], // list-group
            // 'containerTagClasses' => ['list-group'], // 
            'containerTagClasses' => 'nav nav-pills flex-column',
            //'itemTag'             => 'div',
            'itemTagClasses'      => $tagClasses,
            //'activeClass'         => 'selected',
            //'preRenderFunc'       => function($label){
            //  return '<i>icon</i> '.$label;
            //},
            //'cascadeSelected'     => true,
            //'depth'               => 2,
            //'firstClass'          => 'begin',
            //'lastClass'           => 'end',
            'childClass'          => 'nav nav-child',
            'collapsibleDepth'     => 1,

            // 'itemTemplate' => 'snap::admin.partials.layout.menu-item',
        ];

        // $this->items = [
        // 'dashboard' => ['link_attributes' => ['class' =>'fa fa-dashboard']],
        // // 'content' => ['label' => 'Course Content', 'link_attributes' => ['class' =>'nav-link-parent nav-link-icon ion-clipboard']],
        // // 'curriculum' => ['link' => 'module/curriculum'],
        // // 'reports' => ['label' => 'Reports', 'link_attributes' => ['class' =>'nav-link-parent nav-link-icon ion-arrow-graph-up-right']],
        // 'users' => [
        //          'label' => 'Users',
        //          'link' => 'module/user',
        //          'link_attributes' => ['class' =>'fa fa-user'],
        //          // 'children' =>[
        //          //  'all' => ['link_attributes' => ['class' =>'nav-link']],
        //          //  'invite' => ['link_attributes' => ['class' =>'nav-link']],
        //          //  'groups' => ['link_attributes' => ['class' =>'nav-link']],
        //          // ]
        //  ],
        // ];

        $this->menu->setAutoParent(false)->setOptions($options);
        if ($currentModule = \Admin::modules()->current()) {
            $this->menu->setActive($currentModule->fullHandle());
        }
    }

    public function hasAccess($module)
    {
        return app('auth')->user()->can($module->defaultPermission()) && $module->hasService('navigable');
    }

    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setRenderType($type)
    {
        $this->renderType = $type;

        return $this;
    }

    public function getRenderType()
    {
        return $this->renderType;
    }

    protected function _render()
    {
        $renderType = 'render'.title_case($this->renderType);

        //return $this->menu->load($this->items, true)->renderBasic();
        return $this->menu->load($this->items, true)->$renderType();
    }

}
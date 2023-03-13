<?php

namespace Snap\Admin\Ui\Components;

use Snap\Menu\Item;
use Snap\Menu\MenuBuilder;
use Snap\Ui\Traits\JsTrait;
use Snap\Ui\UiComponent;

class HierarchicalListing extends UiComponent
{
    use JsTrait;
    //use AjaxRendererTrait;

    protected $view = 'admin::components.hierarchical-listing';

    protected $scripts = [
        'assets/snap/js/components/resource/HierarchicalListing.js',
        'assets/snap/vendor/jquery-ui/sortable.js',
        'assets/snap/vendor/jquery/plugins/jquery.mjs.nestedSortable.js',
    ];

    protected $data = [
        //'object:menu'       => '\Snap\Menu\MenuBuilder',
        'module'        => null,
        'sortable'      => null,
        'update_uri'    => 'sort',
        'nesting_depth' => 3,
        'items'         => null,
        'id_column'     => 'id',
        'root_value'    => null,
        'group_by'      => null,
        'groups'        => [],
        'group_module'  => null,
        'inline'        => false,
    ];

    protected $itemClass = Item::class;

    // Must be a property with getter and setter because it's a closure
    protected $itemTemplate = null;

    public function initialize()
    {

    }

    public function getItemClass()
    {
        return $this->itemClass;
    }

    public function setItemClass($class)
    {
        $this->itemClass = $class;

        return $this;
    }

    public function getItemTemplate()
    {
        return $this->itemTemplate;
    }

    public function setItemTemplate($class)
    {
        $this->itemTemplate = $class;

        return $this;
    }

    public function isSortable()
    {
        return $this->data['sortable'];
    }

    public function getGroups()
    {
        $groups = $this->groupListingItems($this->items, $this->getGroupBy());

        foreach ($groups as $key => $group) {
            $groups[$key]['listing'] = $this->initializeListing($group['listing']);
        }
        $this->data['groups'] = $groups;

        return $this->data['groups'];
    }

    public function getGroupModule()
    {
        if (empty($this->data['group_module'])) {
            $this->data['group_module'] = \Admin::modules()->get($this->getGroupBy());
        }

        return $this->data['group_module'];
    }

    protected function groupListingItems($items, $groupBy = null)
    {
        $groups = [];

        foreach ($items as $item) {
            if ($groupBy) {
                if (! isset($groups[$item->{$groupBy}->id])) {
                    $groups[$item->{$groupBy}->id] = ['group' => $item->{$groupBy}, 'listing' => null];
                }

                $groups[$item->{$groupBy}->id]['listing'][] = $item;
            } else {
                $groups['default']['listing'][] = $item;
            }
        }

        return $groups;
    }

    protected function initializeListing($items)
    {
        $menu = new MenuBuilder();

        $tagClasses = [
            'default' => 'list-group-item list-group-item-flush',
        ];

        $options = [
            'baseUrl'             => url(\Admin::url()),
            'containerTagId'      => 'resource-list-items',
            'itemIdPrefix'        => 'resource-item-',
            'containerTagClasses' => ['resource-list-items'],
            'itemTagClasses'      => $tagClasses,
            'itemTemplate'        => $this->item_template,
        ];

        $items = $this->normalizeItems($items);

        $menu->load($items, true)->setAutoParent(false)->setRootValue($this->root_value)
             ->setOptions($options)// ->add($this->normalizeItems())
        ;

        return $menu;
    }

    protected function normalizeItems($items)
    {
        $menuItems = [];

        foreach ($items as $item) {
            $menuItem = $this->createListItem($item);
            $menuItems[$item->id] = $menuItem;
        }

        return $menuItems;
    }

    // used to override the Menu classes' items() method that get's caught up by the proxy object

    protected function createListItem($item)
    {
        $idKey = $this->id_column;
        $menuItem = new $this->itemClass($item->{$idKey});
        $menuItem->setLabel($this->detectLabel($item));
        $menuItem->setLink($this->module->url('edit', ['resource' => $item]));
        $menuItem->setParent($this->detectParent($item));

        return $menuItem;
    }

    protected function detectLabel($item)
    {
        if (method_exists($item, 'getDisplayNameAttribute')) {
            return $item->getDisplayNameAttribute();
        } elseif (isset($item->label)) {
            return $item->label;
        } elseif (isset($item->name)) {
            return $item->name;
        } elseif (isset($item->title)) {
            return $item->title;
        }

        return '';
    }

    protected function detectParent($item)
    {
        // @TODO... need to look at service values
        if ((method_exists($this->module, 'isSearched') && $this->module->isSearched()) || (method_exists($this->module, 'isFiltered') && $this->module->isFiltered())) {
            return null;
        } elseif (method_exists($item, 'getParentColumnValue')) {
            return $item->getParentColumnValue();
        } elseif (isset($item->parent_id)) {
            return $item->parent_id;
        } elseif (isset($item->parent)) {
            if (is_object($item->parent)) {
                return $item->parent->getKey();
            }
            return $item->parent;
        } elseif (isset($item->name)) {
            $nameParts = explode('/', $item->name);
            array_pop($nameParts);
            $parent = implode('/', $nameParts);
            if (! empty($parent)) {
                return $parent;
            }
        }

        return null;
    }

    protected function _render()
    {
        $this->data['groups'] = $this->getGroups();
        $this->data['group_module'] = $this->getGroupModule();

        return parent::_render();
    }

    //public function _renderAjax()
    //{
    //    $this->initTable();
    //
    //    $output = '<div>';
    //    if ($this->inline) {
    //        $output .= parent::_render();
    //        $output .= $this->getHiddenParams();
    //    } else {
    //        $output .= $this->table->render();
    //        $output .= $this->getHiddenParams();
    //    }
    //    $output .= '</div>';
    //
    //    return $output;
    //}
}
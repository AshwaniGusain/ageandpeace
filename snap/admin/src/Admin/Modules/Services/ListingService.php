<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Ui\Components\ListingItem;

/*
$listing = Listingervice::make();
$listing->itemTemplate(function($item, $id, $level){
    return view('my-item-view', ['item' => $item]);
})
->sortable(true)
->nestingDepth(1)
->parentColumn('parent')
->rootValue(0)
;
*/
class ListingService
{
    public $idColumn = 'id';
    public $parentColumn = null;
    public $precedenceColumn = null;
    public $rootValue = null;
    public $sortable = true;
    public $updateUri = 'sort';
    public $nestingDepth = 3;
    public $itemTemplate = null;
    public $items;
    public $groupBy;

    //public $options = [];

    protected $module;
    protected $request;
    protected $resource;
    protected $model;
    protected $query;
    protected $fields;


    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
        $this->model = $this->module->getModel();
        $this->query = $this->module->getQuery();
        $this->idColumn = $this->model->getKeyName();

        $this->sortable = $this->module->hasTrait('precedence') || method_exists($this->model, 'getParentColumn');

        if (method_exists($this->model, 'scopeWithPrecedence')) {
            $this->query->withPrecedence();
        }

        if (method_exists($this->model, 'getParentColumn')) {
            $this->parentColumn = $this->model->getParentColumn();
        }

        if (method_exists($this->model, 'getPrecedenceColumn')) {
            $this->precedenceColumn = $this->model->getPrecedenceColumn();
        }

        $this->itemTemplate = function($item, $id, $level) {
            $items = $this->getItems();
            $obj = $items[$item->id];
            return new ListingItem(['item' => $obj, 'module' => $this->module, 'sortable' => $this->sortable]);
        };
    }

    /**
     * Static make function for generating new service.
     *
     * @param $module
     * @return \Snap\Admin\Modules\Services\ListingService
     */
    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    /**
     * Manipulate the query for listing items.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function query(\Closure $callback)
    {
        call_user_func($callback, $this->query);

        return $this;
    }

    /**
     * Sets the items to display and will overwrite whatever is set for the query.
     *
     * @return $this
     */
    public function items($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Returns a collection of items to display in a list.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getItems()
    {
        if ($this->items) {
            return $this->items;
        }

        return $this->query->get()->keyBy($this->idColumn);
    }

    /**
     * Determines if the items on the list can be sorted.
     *
     * @param $sortable
     * @return $this
     */
    public function sortable($sortable)
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * Determines the hierarchical depth in which items may be sorted.
     *
     * @param $nestingDepth
     * @return $this
     */
    public function nestingDepth($nestingDepth)
    {
        $this->nestingDepth = (int) $nestingDepth;

        return $this;
    }

    /**
     * The item's property to be used as the unique identifier. The default is the model's key name (e.g. `id`).
     *
     * @param string
     * @return $this
     */
    public function idColumn($column)
    {
        $this->idColumn = $column;

        return $this;
    }

    /**
     * The model's column name to use for building the parent/child hierarchical relationship.
     *
     * @param string
     * @return $this
     */
    public function parentColumn($column)
    {
        $this->parentColumn = $column;

        return $this;
    }

    /**
     * The model's column name that will help determine the ordering of the
     * list items. The default value is `precedence`.
     *
     * @param string
     * @return $this
     */
    public function precedenceColumn($column)
    {
        $this->precedenceColumn = $column;

        return $this;
    }

    /**
     * The value that determines the root of the listing hierarchy.
     *
     * @param mixed
     * @return $this
     */
    public function rootValue($value)
    {
        $this->rootValue = $value;

        return $this;
    }

    /**
     * Sets the template to be used for each item
     *
     * @param \Closure $itemTemplate
     * @return $this
     */
    public function itemTemplate(\Closure $itemTemplate)
    {
        $this->itemTemplate = $itemTemplate;

        return $this;
    }

    /**
     * If set, it will group the items into separate groups.
     *
     * @param $groupBy
     * @return $this
     */
    public function groupBy($groupBy)
    {
        $this->groupBy = $groupBy;

        return $this;
    }

}
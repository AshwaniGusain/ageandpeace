<?php

namespace Snap\Admin\Ui\Components;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Snap\Ui\Traits\CssTrait;
use Snap\Ui\Traits\JsTrait;
use Snap\Ui\UiComponent;

class GridList extends UiComponent
{
    use JsTrait;
    use CssTrait;

    protected $view = 'admin::components.grid-list';
    protected $scripts = [
    ];

    protected $data = [
        'items'      => [],
        'pagination' => null,
        'limit'      => null,
        'cols'       => 4,
        'module'     => null,
    ];

    // Must be a property with getter and setter because it's a closure
    protected $itemTemplate = null;


    public function initialize(Request $request)
    {
        $this->request = $request;
        if ($limit = $request->input('limit')) {
            $this->setLimit($limit);
        }
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

    public function getItems()
    {
        $this->pagination = $this->module->getQuery()->paginate($this->limit);
        return $this->pagination->getCollection()->keyBy('id');
    }

    public function getPagination()
    {
        if (empty($this->data['pagination'])) {
            $data = $this->getItemData();
            if (!empty($data)) {
                $this->data['pagination'] = $data->paginate($this->limit);
            }
        }

        return $this->data['pagination'];
    }

    public function getItemData()
    {
        if (isset($this->data['query'])) {
            $query = $this->query
                // ->orderBy($this->column, $this->order)
            ;

            return $query;
        }

        return [];
    }

    protected function initializeGrid()
    {
        $items = $this->getItems();

        if ($this->pagination) {
            $queryString = Arr::except($this->request->query(), $this->pagination->getPageName());

            if (! $this->request->ajax()) {
                $this->pagination->appends($queryString);
            } else {
                //$this->pagination = '';
            }
        }

        $this->data['items'] = $items;
        $this->data['has_data'] = (!empty($items) && $items->count() > 0) ? true : false;

        return $this;
    }

    protected function _render()
    {
        $this->initializeGrid();

        return parent::_render();
    }

}
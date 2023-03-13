<?php

namespace Snap\Admin\Ui\Components;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Snap\Database\DBUtil;
use Snap\Support\Helpers\ArrayHelper;
use Snap\Ui\Components\Bootstrap\Dropdown;
use Snap\Ui\Traits\AjaxRendererTrait;
use Snap\Ui\Traits\JsTrait;
use Snap\Ui\UiComponent;

class DataTable extends UiComponent
{
    use JsTrait;
    use AjaxRendererTrait;

    protected $view = 'admin::components.datatable';

    protected $scripts = [
        'assets/snap/js/components/resource/DataTable.js',
    ];

    protected $data = [
        'module'        => null,
        ':table'        => \Snap\DataTable\DataTable::class,
        'items'         => null,
        'columns'       => [],
        'order'         => 'asc',
        'custom_sort' => null,
        'col'           => null,
        'sort'          => null,
        'sortable'      => [],
        'non_sortable'  => [],
        'limit'         => null,
        'pagination'    => null,
        'inline'        => false,
        'formatters'    => [],
        'actions'       => [
            '' => 'show',
            'edit' => 'form',
            'delete' => 'delete',
        ],
        'url'           => null,
    ];

    protected $request;

    public function initialize(Request $request)
    {
        $this->request = $request;

        $this->setUrl($this->module->url('table'));
    }

    public function setSort($col, $order = null)
    {
        if (Str::startsWith($col, '-')) {
            $order = '-';
            $col = ltrim($col, '-');
        }
        $this->data['sort'] = $order.$col;
        $this->col = $col;
        $this->order = ($order == '-') ? 'desc' : 'asc';

        return $this;
    }

    public function setCustomSort($callback)
    {
        $this->data['custom_sort'] = $callback;

        return $this;
    }
    public function setColumns($columns)
    {
        $this->data['columns'] = ArrayHelper::normalize($columns);

        return $this;
    }

    public function getItems()
    {
        if (is_null($this->data['items']) || $this->data['items'] instanceof Builder) {

            if (is_null($this->data['items'])) {
                $query = $this->module->getQuery();
            } elseif ($this->data['items'] instanceof Builder) {
                $query = $this->data['items'];
            }

            if ($this->custom_sort instanceof \Closure) {
                $this->data['items'] = call_user_func($this->custom_sort, $query, $this->col, $this->order, $this->limit);
            } else {
                $this->pagination = $query->paginate($this->limit);
                $this->data['items'] = $this->pagination->getCollection();
            }
        }

        return $this->data['items'];
    }

    //public function setItems()
    //{
    //    if (is_null($this->data['items'])) {
    //        $this->data['items'] = $this->module->getTableData();
    //        $this->pagination = $this->module->getPagination();
    //    }
    //
    //    if ($this->data['items'] instanceof Builder) {
    //        $this->pagination = $this->data['items']->paginate($this->limit);
    //
    //        $this->data['items'] = $this->pagination->getCollection();
    //    }
    //
    //    return $this->data['items'];
    //}

    public function getColumns()
    {
        //if (empty($this->data['columns'])) {
        //    $this->data['columns'] = $this->module->getTableColumns();
        //}

        if (! empty($this->data['columns'])) {
            $keyName = $this->module->getModel()->getKeyName();
            if (! in_array($keyName, $this->data['columns'])) {
                $this->data['columns'][] = $keyName;
            }
        }

        return $this->data['columns'];
    }

    public function getHiddenParams()
    {
        $output = '<input name="order" type="hidden" value="'.$this->sort.'" class="data-table-sort">';
        if ($this->inline) {
            $output .= '<input name="inline" type="hidden" value="1" class="data-table-inline">';
        }

        return $output;
    }

    protected function initRowActions($actions)
    {
        if ($this->request->input('inline')) {
            $this->setInline($this->request->input('inline') == 1);
        }

        $primaryKey = $this->module->getModel()->getKeyName();

        //$actions = ['edit' => 'form', 'show' => 'view'];
        foreach ($actions as $action => $trait) {
            if ($trait instanceof \Closure) {

                $this->table->addAction($trait);

            } elseif ($action == 'delete') {
                if ($this->module->hasTrait('delete') && $this->module->hasPermission('delete')) {
                    $this->table->addAction(function ($values) use ($primaryKey) {
                        if (empty($values['deleted_at']) && $this->module->service('deletable')->canDelete) {
                            $url = ($this->inline) ? $values[$primaryKey].'/delete_inline' : $values[$primaryKey].'/delete';
                            return '<a href="'.$this->module->url($url).'" class="btn btn-sm btn-secondary table-action">'.__('admin::resources.action_delete').'</a>';
                        }

                        return '';
                    });

                    if (! $this->inline) {
                        $this->table->addAction(function ($values) {
                            if (empty($values['deleted_at'])) {
                                return '<input type="checkbox" @click="$parent.toggleMultiSelect()" value="'.$values['id'].'" data-id="'.$values['id'].'" class="multiselect">';
                            }

                            return '';
                        });
                    }
                }
            } else {
                if ($this->module->hasTrait($trait) && $this->module->hasPermission($action)) {
                    $url = ($this->inline && $action) ? '{'.$primaryKey.'}/'.$action.'_inline' : '{'.$primaryKey.'}/'.$action;
                    $this->table->addAction($this->module->url($url), __('admin::resources.action_'.$trait), ['class' => 'btn btn-sm btn-secondary table-action']);
                }
            }
        }

        if (count($this->table->getActions()) > 0) {
            $this->table->setClass('table table-hover table-striped datatable');
        }
    }

    protected function initRequestParams()
    {
        //if ($this->request->input('inline')) {
        //    $this->setInline($this->request->input('inline') == 1);
        //}

        if ($this->request->input('sort')) {
            $this->setSort($this->request->input('sort'));
        }

        if ($this->request->input('fields')) {
            $this->setColumns($this->request->input('fields'));
        }

        if ($this->request->input('limit')) {
            $this->setLimit($this->request->input('limit'));
        }
    }

    protected function initDropdown()
    {
        $dropdown = new Dropdown(['id' => 'snap-resource-table-columns', 'class' => 'float-right', 'size' => 'sm']);

        foreach ($this->table->getColumns() as $column) {
            if (!$this->isPrimaryKeyColumn($column)) {
                $dropdown->add('<label class="dropdown-item"><input type="checkbox" value="'.$column->getKey().'" class="column-toggle" data-column="'.$column->getKey().'" checked> '.$column->getTitle().'</label>');
            }
        }

        $this->table->setActionColumnTitle($dropdown);
    }

    protected function initSort()
    {
        $query = $this->module->getQuery();
        $col = $this->col;
        $order = $this->order;
        if ($col) {
            if (strpos($col, '.') === false) {
                $query->orderBy($col, $order);
            } else {
                $model = $this->module->getModel();

                $relationshipParts = explode('.', $col);
                $orderCol = $col;
                while ($relationship = current($relationshipParts)) {
                    $orderCol = $relationship;
                    if (method_exists($model, $relationship)) {
                        $foreignModel = $model->$relationship()->getModel();
                        $foreignTable = $foreignModel->getTable();

                        if ($foreignTable != $model->getTable() && ! DBUtil::joinExists($query, $foreignTable)) {
                            // This select is needed primarily to get the correct ID for the row.
                            $query->addSelect([$this->module->getModel()->getTable().'.*']);
                            $query->leftJoin($foreignTable, $foreignTable.'.'.$foreignModel->getKeyName(), '=', $model->getTable().'.'.$model->$relationship()->getForeignKeyName());
                        }

                        $model = $foreignModel;
                    }

                    array_shift($relationshipParts);
                }

                if (!empty($foreignTable)) {
                    $query->orderBy($foreignTable.'.'.$orderCol, $order);
                }
            }
        }

    }

    protected function initTable()
    {
        $this->initRowActions($this->actions);
        $this->initRequestParams();

        if (!$this->custom_sort) {
            $this->initSort();
        }

        $items = $this->getItems();

        $columns = $this->getColumns();

        if ($columns && ! in_array($this->module->getModel()->getKeyName(), $columns)) {
            $columns[] = $this->module->getModel()->getKeyName();
        }

        if ($this->pagination) {
            $queryString = Arr::except($this->request->query(), $this->pagination->getPageName());

            if (! $this->request->ajax()) {
                $this->pagination->appends($queryString);
            } else {
                //$this->pagination = '';
            }
        }

        $this->data['has_data'] = (! empty($items) && count($items) > 0) ? true : false;

        $this->table->load($items, $columns)->setSortedColumn($this->col, $this->order);

        $this->initDropdown();

        if (method_exists($this->module->getModel(), 'getDeletedAtColumn')) {
            $this->table->addIgnored($this->module->getModel()->getDeletedAtColumn());
        }

        if ($this->formatters) {
            foreach ($this->formatters as $formatter) {
                call_user_func_array([$this->table, 'addFormatter'], $formatter);
            }
        }

        if ($this->sortable) {
            foreach ($this->table->getColumns() as $column) {
                if (!in_array($column->getKey(), $this->sortable)) {
                    $column->setSortable(false);
                }
            }
        }

        if ($this->non_sortable) {
            foreach ($this->table->getColumns() as $column) {
                if (in_array($column->getKey(), $this->non_sortable)) {
                    $column->setSortable(false);
                }
            }
        }
    }

    protected function isPrimaryKeyColumn($column)
    {
        if ($column->getKey() == $this->module->getModel()->getKeyName()
            || $column->getKey() == $this->module->getModel()->getTable() . '.' . $this->module->getModel()->getKeyName()) {
            return true;
        }

        return false;
    }

    protected function _render()
    {
        $this->initDropdown();
        $this->initTable();

        return parent::_render();
    }

    public function _renderAjax()
    {
        $this->initTable();

        $output = '<div>';
        if ($this->inline) {
            $output .= parent::_render();
            $output .= $this->getHiddenParams();
        } else {
            $output .= $this->table->render();
            $output .= $this->getHiddenParams();
        }
        $output .= '</div>';

        return $output;
    }

    public function __call($method, $args)
    {
        if (method_exists($this->table, $method)) {
            return call_user_func_array([$this->table, $method], $args);
        }

        return parent::__call($method, $args);
    }
}
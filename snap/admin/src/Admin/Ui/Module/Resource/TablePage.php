<?php

namespace Snap\Admin\Ui\Module\Resource;

use Illuminate\Http\Request;
use Snap\Ui\Traits\AjaxRendererTrait;

class TablePage extends IndexPage
{
    use AjaxRendererTrait;

    protected $view = 'admin::module.resource.table';

    protected $data = [
        ':table'        => '\Snap\Admin\Ui\Components\DataTable[module]',
        ':filters'      => '\Snap\Admin\Ui\Components\Filters[module]',
        ':scopes'       => '\Snap\Admin\Ui\Components\Scopes[module]',
        'limit_options' => [
            50,
            100,
            200,
        ],
        'limit'         => null,
    ];

    public function initialize(Request $request)
    {
        parent::initialize($request);
        $this->buttons->setActive('table');
        //$this->setLimit($this->request->input('limit'));
        //$this->setLimitOptions($this->module->getPaginationLimits());
    }

    public function _renderAjax()
    {
        return $this->table->render();
    }
}
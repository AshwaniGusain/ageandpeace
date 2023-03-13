<?php

namespace Snap\Admin\Ui\Module\Resource;

use Illuminate\Http\Request;

class GridPage extends IndexPage
{
    protected $view = 'admin::module.resource.grid';

    protected $data = [
        ':grid'         => '\Snap\Admin\Ui\Components\GridList[module]',
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
        $this->buttons->setActive('grid');
        //$this->setLimit($this->request->input('limit'));
        //$this->setLimitOptions($this->module->getPaginationLimits());
    }
}
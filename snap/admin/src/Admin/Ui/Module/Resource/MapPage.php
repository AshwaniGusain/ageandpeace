<?php

namespace Snap\Admin\Ui\Module\Resource;

use Illuminate\Http\Request;

/**
 * A Map view for a modules index.
 * @autodoc true
 */
class MapPage extends IndexPage
{
    protected $view = 'admin::module.resource.map';

    protected $data = [
        ':map'          => '\Snap\Admin\Ui\Components\MapList[module]',
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
        $this->buttons->setActive('map');
        //$this->setLimit($this->request->input('limit'));
        //$this->setLimitOptions($this->module->getPaginationLimits());
    }
}
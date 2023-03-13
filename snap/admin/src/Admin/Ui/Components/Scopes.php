<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\UiComponent;
use Illuminate\Http\Request;

class Scopes extends UiComponent
{
    protected $view = 'admin::components.scopes';

    protected $data = [
        'scopes' => [],
        'active' => null,
        'pagination_limit' => 0,
    ];

    public function initialize(Request $request) {
        //if ($this->module->hasTrait('scopes')) {
        //    $this->scopes = $this->module->getScopes();
        //}

        //$this->active = $request->input('scope');
    }
}
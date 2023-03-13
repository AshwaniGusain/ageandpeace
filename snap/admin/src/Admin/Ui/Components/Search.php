<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\UiComponent;
use Illuminate\Http\Request;

class Search extends UiComponent
{
    protected $view = 'admin::components.search';

    protected $data = [
        'q' => null,
    ];

    public function initialize(Request $request) {
        $this->q = $request->get('q');
    }
}
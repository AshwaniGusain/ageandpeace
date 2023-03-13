<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\UiComponent;

class GridItem extends UiComponent
{
    protected $view = 'admin::components.grid-item';

    protected $data = [
        'module' => null,
        'item' => null,
    ];
}
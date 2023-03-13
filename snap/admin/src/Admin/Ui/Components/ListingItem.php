<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\UiComponent;

class ListingItem extends UiComponent
{
    protected $view = 'admin::components.listing-item';

    protected $data = [
        'module' => null,
        'item' => null,
        'parent' => null,
        'sortable' => false
    ];
}
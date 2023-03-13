<?php

namespace Snap\Admin\Ui\Dashboard;

use Snap\Ui\UiComponent;

class Placeholder extends UiComponent
{
    protected $view = 'admin::components.ajax-placeholder';

    protected $data = [
        'width' => '100%',
        'height' => '200px',
        'url' => '',
        'params' => [],
    ];

}
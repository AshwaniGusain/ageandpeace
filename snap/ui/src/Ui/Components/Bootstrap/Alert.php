<?php 

namespace Snap\Ui\Components\Bootstrap;

use Snap\Ui\UiComponent;

class Alert extends UiComponent {

    protected $view = 'component::bootstrap.alert';
    protected $data = [
        'text' => '',
        'type' => 'info',
        'id' => null,
        'refId' => null,
        'dismissable' => false,
    ];
}
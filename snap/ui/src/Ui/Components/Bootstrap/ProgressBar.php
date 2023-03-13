<?php

namespace Snap\Ui\Components\Bootstrap;

use Snap\Ui\UiComponent;

class ProgressBar extends UiComponent {

    protected $view = 'component::bootstrap.progressbar';
    protected $data = [
        'id' => null,
        'striped' => true,
        'animated' => true,
        'percent' => 100,
    ];
}
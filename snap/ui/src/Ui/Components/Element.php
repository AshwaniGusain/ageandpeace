<?php

namespace Snap\Ui\Components;

use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class Element extends UiComponent
{
    use CssClassesTrait;
    use AttrsTrait;

    protected $view = 'component::element';

    protected $data = [
        'tag'      => '',
        'content'     => '',
    ];

}
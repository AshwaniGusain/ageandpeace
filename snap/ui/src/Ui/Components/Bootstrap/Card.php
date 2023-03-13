<?php

namespace Snap\Ui\Components\Bootstrap;

use Snap\Ui\Components\Image;
use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class Card extends UiComponent
{
    use CssClassesTrait;

    protected $view = 'component::bootstrap.card';

    protected $data = [
        'header' => '',
        'footer' => null,
        'class'  => 'mb-3',
        'title'  => '',
        'text'   => '',
        'button' => null,
        ':img'   => Image::class,
        ':list'  => ListGroup::class,
        'body'   => '',
    ];
}
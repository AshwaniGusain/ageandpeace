<?php namespace Snap\Ui\Components;

use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class Link extends UiComponent
{
    use CssClassesTrait;
    use AttrsTrait;

    protected $view = 'component::link';

    protected $data = [
        'attrs' => [
            'href'   => '',
        ],
        'content' => '',
        'icon' => '',
        'label' => '',
    ];

    public function setHref($src)
    {
        $this->data['attrs']['href'] = $src;

        return $this;
    }

    public function getHref()
    {
        return $this->data['attrs']['href'] ?? null;
    }

}
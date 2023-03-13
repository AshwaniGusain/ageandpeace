<?php namespace Snap\Ui\Components;

use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class Image extends UiComponent
{
    use CssClassesTrait;
    use AttrsTrait;

    protected $view = 'component::image';

    protected $data = [
        'attrs' => [
            'src'   => '',
            'alt'   => '',
            'class' => 'img-fluid',
        ],
        ':link' => Link::class,
    ];

    public function setSrc($src)
    {
        $this->data['attrs']['src'] = $src;

        return $this;
    }

    public function getSrc()
    {
        return $this->data['attrs']['src'] ?? null;
    }

    public function setAlt($alt)
    {
        $this->data['attrs']['alt'] = $alt;

        return $this;
    }

    public function getAlt()
    {
        return $this->data['attrs']['alt'] ?? null;
    }

    protected function _render()
    {
        $content = parent::_render();

        if ($this->link->href) {
            $this->link->setContent($content);
            return $this->link;
        }

        return $content;
    }

}
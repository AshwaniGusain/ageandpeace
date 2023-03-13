<?php

namespace Snap\Ui\Components\Bootstrap;

use Snap\Ui\Components\Icon;
use Snap\Ui\UiComponent;
use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\Traits\AttrsTrait;

class Button extends UiComponent
{
    use CssClassesTrait;
    use AttrsTrait;

    protected $view = 'component::bootstrap.button';
    protected $data = [
        'label' => '',
        'attrs' => [],
        'type'  => 'secondary',
        'size'  => '',
        'id'    => null,
        'icon'  => '',
        'submit' => true,
    ];

    protected function _render()
    {
        $this->addClass('btn');

        $this->addClass('btn-' . $this->type);

        if ($this->size) {
            $this->addClass('btn-' . $this->size);
        }

        return parent::_render();
    }

    public function setId($id)
    {
        $this->setAttr('id', $id);

        return $id;
    }

    public function getId()
    {
        return $this->getAttr('id');
    }

    public function setIcon($icon)
    {
        if (is_string($icon)) {
            $this->data['icon'] = new Icon(['icon' => $icon]);
        }

        return $this;
    }

    public function setSubmit($submit)
    {
        if ($submit) {
            $this->setAttr('type', 'submit');
        } else {
            $this->setAttr('type', 'button');
        }

        return $this;
    }
}
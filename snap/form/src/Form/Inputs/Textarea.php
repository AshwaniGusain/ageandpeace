<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\Traits\VueTrait;
use Snap\Ui\Traits\AttrsTrait;

class Textarea extends BaseInput
{
    use VueTrait;
    use AttrsTrait;
    use CssClassesTrait;

    protected $vue = 'snap-textarea-input';

    protected $scripts = [
        'assets/snap/js/components/form/TextareaInput.js',
    ];

    protected $view = 'form::inputs.textarea';
    protected $data = [
        'attrs' => [
            ':autosize'   => "false",
            ':min-height' => 100,
            ':max-height' => null,
        ],
    ];

    protected function _render()
    {
        $this->data['value'] = $this->getValue();

        $this->setAttrs(
            [
                'name'  => $this->getName(),
                'id'    => $this->getId(),
                'class' => ! is_null($this->getAttr('class')) ? $this->getAttr('class').' form-control' : 'form-control',
                //'value' => $this->getValue(), // For Vue.js component
            ]
        );

        // No need to render vue attributes if not autosized.
        if (!$this->isAutoSized()) {
            $this->removeAttr(':autosize', ':min-height', ':max-height', 'is');
        }

        return parent::_render();
    }

    public function setAutoSize($bool)
    {
        $this->setAttr(':autosize', ($bool ? 'true' : 'false'));

        return $this;
    }

    public function getAutoSize()
    {
        return $this->getAttr(':autosize');
    }

    public function isAutoSized()
    {
        return $this->getAttr(':autosize') == 'true' ? true : false;
    }

    public function setMinHeight($height)
    {
        $this->setAttr(':min-height', $height);

        return $this;
    }

    public function getMinHeight()
    {
        return $this->getAttr(':min-height');
    }

    public function setMaxHeight($height)
    {
        $this->setAttr(':max-height', $height);

        return $this;
    }

    public function getMaxHeight()
    {
        return $this->getAttr(':max-height');
    }
}
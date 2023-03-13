<?php

namespace Snap\Form\Inputs;

use Snap\Form\Contracts\AttrsInterface;
use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\Traits\CssClassesTrait;

class Input extends BaseInput implements AttrsInterface
{
    use AttrsTrait;
    use CssClassesTrait;

    protected $inputType;

    protected $placeholder;

    protected $pattern;

    protected $title;

    protected $view = 'form::inputs.input';

    protected $data = [];

    protected function _render()
    {
        $value = $this->getValue();

        if (is_array($value)) {
            $value = json_encode($value);
        }

        if (empty($this->id)) {
            $this->setId(uniqid('input-'));
        }

        $this->setAttrs([
            'type'  => $this->getInputType(),
            'name'  => $this->getName(),
            'value' => is_null($value) ? null : (string) $value,
            'id'    => $this->getId(),
            'class' => ! is_null($this->getAttr('class')) ? $this->getAttr('class') : 'form-control',
            'placeholder'  => $this->getPlaceholder(),
            'pattern'  => $this->getPattern(),
            'title'  => $this->getTitle(),
        ]);

        $this->with(['displayValue' => $this->getDisplayValue()]);

        return parent::_render();
    }

    public function getInputType()
    {
        return $this->inputType;
    }

    public function setInputType($inputType)
    {
        $this->inputType = $inputType;

        return $this;
    }

    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function convertFromModel($props, $form)
    {
        $this->setAttr('maxlength', $props['length']);
    }
}
<?php

namespace Snap\Form;

use Snap\Form\Inputs\BaseInput;
use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\UiComponent;

class Label extends UiComponent
{

    use AttrsTrait;
    protected $view = 'form::label';

    protected $data = [
        'for'      => '',
        'text'    => '',
        'comment'  => '',
        'required' => false,
        'use_tag'  => true,
        'attrs'    => [],
    ];

    public static function make($label, $for = null, $required = null)
    {
        if ($label instanceof BaseInput) {
            $label = new static([]);
            $label->fromInput($label);

            return $label;
        } elseif (is_array($label)) {

            return new static($label);
        } else {
            $data['text'] = $label;
            $data['for'] = $for;
            $data['required'] = $required;

            return new static($data);
        }
    }

    public static function convertNameToLabel($name)
    {
        return humanize(decamelize(preg_replace('#(.+)_id$#', '$1', $name)));
    }

    public function fromInput($input)
    {
        if (empty($this->text)) {
            $this->convertNameToLabel($input->name);
        }

        if ($input->id) {
            $this->setFor($input->id);
        } else {
            $this->setFor($input->key);
        }

        if ($input->required) {
            $this->setRequired(true);
        }

        if ($input->comment) {
            $this->setComment($input->comment);
        }

        return $this;
    }

    public function isRequired()
    {
        return $this->required;
    }

    public function isVisible()
    {
        return !empty($this->text);
    }

    protected function _render()
    {
        // To prevent possible collision with other attirbutes.
        $this->removeAttr(['for', 'data-toggle', 'title']);

        return parent::_render();
    }
}
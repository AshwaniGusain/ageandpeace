<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\Traits\VueTrait;

/**
 * An HTML text input field.
 * <code><input type="text"></code>
 * @autodoc true
 */
class Text extends Input
{
    use VueTrait;

    /**
     * The view file used for the input.
     *
     * @var string
     */
    protected $view = 'form::inputs.text';

    /**
     * @var array
     */
    protected $scripts = [
        'assets/snap/js/components/form/TextInput.js',
    ];

    /**
     * @var string
     */
    protected $inputType = 'text';

    /**
     * @var array
     */
    protected $data = [
        'remaining' => true,
    ];

    /**
     * @return string
     */
    protected function _render()
    {
        $this->data['show_remaining'] = $this->canShowRemaining();

        if ($this->data['show_remaining']) {
            $this->setAttrs(
                [
                    'v-model' => 'val',
                ]
            );
        }

        return parent::_render();
    }

    /**
     * Sets the max length of the field.
     *
     * @return $this
     */
    public function setMaxLength($max)
    {
        $this->setAttr('maxlength', $max);

        return $this;
    }

    /**
     * Returns the max length of the field.
     *
     * @return mixed
     */
    public function getMaxLength()
    {
        return $this->getAttr('maxlength');
    }


    /**
     * Determines whether the remaining number of characters displays should appear with the displayed input.
     *
     * @return bool
     */
    public function canShowRemaining()
    {
        if ($this->remaining !== false && $this->getAttr('maxlength')) {
            return true;
        }

        return false;
    }

    /**
     * Provides instructions on how to convert from a model's table properties to the input.
     *
     * @param $props
     * @param $form
     */
    public function convertFromModel($props, $form)
    {
        $this->setMaxLength($props['length']);
    }

}
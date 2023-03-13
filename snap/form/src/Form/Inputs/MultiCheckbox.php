<?php

namespace Snap\Form\Inputs;

use Snap\Admin\Ui\Components\Inputs\BelongsToMany;
use Snap\Form\Fields\Traits\HasOptions;
use Snap\Menu\Item;
use Snap\Menu\MenuBuilder;
use Snap\Ui\Traits\AttrsTrait;

class MultiCheckbox extends BelongsToMany
{
    use HasOptions;
    use AttrsTrait;

    protected $view = 'form::inputs.multi-checkbox';
    protected $data = [
        'enforce_if_empty' => true,
        'checkboxes' => [],
        'options' => [],
    ];

    public function initialize()
    {
        $this->setPostProcess(function($value, $input, $request){
            $values = $request->get($this->key);
            $newValues = [];
            if ($values) {
                foreach ($values as $key => $value) {
                    if ($value == "1") {
                        $newValues[] = $key;
                    }
                }
            }
            $request->request->set($this->key, $newValues);
        }, 'beforeValidation', -1);

        parent::initialize();
    }

    public function convertFromModel($props, $form)
    {
        if (!empty($props['options'])) {
            $this->setOptions($props['options']);
        }
    }

    protected function _render()
    {
        $checkboxes = [];

        foreach ($this->getOptions() as $label => $value) {
            if (!$value instanceof Checkbox) {
                if (substr((string) $label, 0, 1) === '*' && substr((string) $label, -1, 1) === '*') {
                    $checkbox = $this->createGroupLabel($value);
                } else {
                    $checkbox = $this->createCheckbox(humanize($value), $label);
                }
            } else {
                $checkbox = $value;
            }

            $checkboxes[] = $checkbox;
        }

        $this->setCheckboxes($checkboxes);

        return parent::_render();
    }

    protected function createCheckbox($label, $id = null)
    {
        $values = (array) $this->getValue();
        $checked = (in_array($id, $values)) ? true : false;
        $checkbox = new Checkbox($this->getName(). '[' . $id . ']', ['class' => 'form-check-input', 'value' => $id, 'label' => $label, 'enforce_if_empty' => $this->enforce_if_empty, 'checked' => $checked]);

        return $checkbox;
    }

    protected function createGroupLabel($label)
    {
        return '<div class="form-check-group-label">'.$label.'</div>';
    }

}
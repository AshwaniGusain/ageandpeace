<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\Traits\VueTrait;

class Slug extends BaseInput
{
    use VueTrait;
    use AttrsTrait;
    use CssClassesTrait;

    //protected $vue = 'snap-slug-input';

    protected $view = 'form::inputs.slug';

    protected $scripts = [
        'assets/snap/js/components/form/SlugInput.js',
    ];

    protected $data = [
        'bound_to' => 'title',
        'prefix' => null,
        'context' => null,
    ];

    public function initialize()
    {
        $this->setPostProcess(function ($value, $input, $request) {
            if ($request->input($this->key)) {
                $request->request->set($this->key, str_slug($value));
            }
        });
    }

    protected function _render()
    {
        $this->setAttrs([
            'bound-to' => $this->bound_to,
            'name'  => $this->getName(),
            'value' => is_null($this->getValue()) ? null : (string) $this->getValue(),
            'id'    => $this->getId(),
            'class' => ! is_null($this->getAttr('class')) ? $this->getAttr('class') : 'form-control',
            'prefix' => $this->prefix,
            'context' => $this->context,
        ]);

        $this->convertAttrsToJson(['prefix', 'bound-to', 'context']);
        $this->with(['displayValue' => $this->getDisplayValue()]);

        return parent::_render();
    }

    public function convertFromModel($props, $form)
    {
        $model = $form->getModel();
        if (method_exists($model, 'getDisplayNameKey')) {
            $this->setBoundTo($model->getDisplayNameKey());
        }
    }
}
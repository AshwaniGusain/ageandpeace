<?php

namespace Snap\Admin\Ui\Module\Resource;

use Snap\Admin\Ui\BasePage;

class InputPage extends BasePage
{
    protected $view = 'admin::module.resource.input';

    protected $data = [
        'resource' => null,
        'input'    => null,
        'value'    => null,
        'form'     => null,
    ];

    public function setInput($input)
    {
        $element = $this->getForm()->get($input);

        if (strpos($input, '.') !== false) {
            $element->setDepth(substr_count($input, '.'));
        }
        if ($element) {
            if ($this->getValue()) {
                $element->setValue($this->getValue());
            }
            $element->setNoTemplate(true);
            $this->data['input'] = $element;
        } else{
            $this->data['input'] = null;
        }

        return $this;
    }

    public function getForm()
    {
        if (empty($this->data['form'])) {

            // Grab the form from the edit view.
            $form = ($this->resource) ? 'edit' : 'create';
            $ui = $this->module->ui($form, ['resource' => $this->resource]);
            $this->data['form'] = $ui->form->form;
        }

        return $this->data['form'];
    }

}
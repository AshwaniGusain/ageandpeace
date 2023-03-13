<?php

namespace Snap\Admin\Ui\Components;

use ArrayAccess;
use Snap\Ui\UiComponent;
use Snap\Ui\Traits\JsTrait;
use \Snap\Form\Form as FormBuilder;

class Form extends UiComponent implements ArrayAccess
{
    use JsTrait;

    protected $scripts = [
        'assets/snap/js/components/form/Form.js',
    ];
    protected $data = [
        'object:form' => '\Form::make',
        //'form' => null, // Set by trait
        'module' => null,
        'resource' => null,
    ];

    public function initialize(FormBuilder $form)
    {
        //$this->form = \Form::make();
        //$this->form = $this->module->getForm(request(), $this->resource);

        //if ($this->module && $this->module->getScaffoldForm()) {
        //    $model = $this->resource ?? $this->module->getModel();
        //    $this->form = $this->form->model($model, ['hints' => $this->module->getFormFieldHints(), 'fields' => $this->module->getFormFields()]);
        //}
    }

    public function __toString()
    {
        return $this->_render();
    }

    protected function _render()
    {
        return $this->form->render();
    }

    public function __call($method, $args)
    {
        if ($this->data['form']) {
            return call_user_func_array([$this->data['form'], $method], $args);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Determine if an element exists at an offset.
     *
     * @access  public
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->form->get($key) != null;
    }

    // --------------------------------------------------------------------

    /**
     * Get a row.
     *
     * @access  public
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->form->get($key);
    }

    // --------------------------------------------------------------------

    /**
     * Set the data for a row.
     *
     * @access  public
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->form->get($key)->setData($value);
    }

    // --------------------------------------------------------------------

    /**
     * Unset the data at a given offset.
     *
     * @access  public
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->form->remove($key);
    }


}
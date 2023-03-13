<?php

namespace Snap\Page\Templates;

use Illuminate\Routing\Router;
use Snap\Decorator\Contracts\DecoratorInterface;
use Snap\Form\Contracts\FormElementInterface;
use JsonSerializable;
use Snap\Form\Contracts\NestableInterface;
use Snap\Form\FormElement;

abstract class TemplateBase implements JsonSerializable, TemplateInteface
{
    public $numUriParams = 0;

    protected $name = '';

    protected $handle = 'default';

    protected $data = [];

    protected $ui = '';

    protected $prefix = '';

    public function __construct()
    {
        //$this->handle = $handle;
        //$this->form = $form;
        //$this->router = $router;
    }

    public function initialize()
    {

    }

    public function handle()
    {
        return $this->handle;
    }

    public function name()
    {
        if ($this->name) {
            return $this->name;
        }

        return class_basename($this);
    }

    public function getPrefix()
    {
        return trim($this->prefix, '/');
    }

    public function getForm()
    {
        $form = \Form::make();
        $inputs = $this->inputs();

        if ($inputs) {
            foreach ($inputs as $input) {
                $form->add($input);
            }
        }

        return $form;
    }

    public function process($request)
    {

    }

    public function getRules()
    {
        return $this->form->getRules();
    }

    public function with($data)
    {
        $data = $this->castData($data);
        $this->data = $data;

        return $this;
    }

    protected function castData($data)
    {
        foreach ($this->inputs() as $input) {
            if ($input instanceof FormElementInterface) {
                $key = $input->key;

                if ($input instanceof NestableInterface) {
                    foreach ($input as $k => $i) {
                        if (isset($data[$key][$k])) {
                            $data[$key][$i] = $this->castFromInput($i, $k, $data[$key][$k]);
                        }
                    }
                } else {
                    if (isset($data[$key])) {
                        $data[$key] = $this->castFromInput($input, $key, $data[$key]);
                    }
                }
            }
        }

        return $data;
    }

    protected function castFromInput($input, $key, $val)
    {
        $cast = $input->getCast();
        if ($cast instanceof \Closure) {
            return $cast($val, $key);
        } elseif (is_subclass_of($cast, DecoratorInterface::class)) {
            return new $cast($val, $key);
        }

        return null;
    }

    public function inputs()
    {
        //$inputs = [];
        //foreach ($this->fields() as $field) {
        //    if ($field instanceof FormElementInterface) {
        //        $inputs[$field->key] = $field;
        //    } else {
        //        $inputs[$field] = $field;
        //    }
        //}
        //
        //return collect($inputs);
    }

    public function ui()
    {
        return ui($this->ui)->setData($this->data);
    }

    public function render()
    {
       return $this->ui()->render();
        //return $this->ui;
    }

    public function toArray()
    {
        return [
            'handle' => $this->handle(),
            'name' => $this->name(),
            'form' => $this->getForm()
                           ->withValues($this->data)
                           ->setTemplate('admin::components.form')
                           ->assign('scope', 'vars[]', '*')
                           ->render(),
            //'ui' => $this->render(),
            //'fields' => $this->inputs(),
            'prefix' => $this->getPrefix(),
        ];
    }

    public function jsonSerialize()
    {
        return json_encode($this->toArray());
    }
}
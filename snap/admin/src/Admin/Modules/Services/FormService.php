<?php

namespace Snap\Admin\Modules\Services;

use ArrayAccess;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationRuleParser;
use Snap\Form\Form;
/*
$form = FormService::make($resource);
$form
    ->scaffold($resource)
    ->rules($rules, $messages)
    ->inputs(
        Text::make('test', [])->castAs('string'),
);
 * */
class FormService implements ArrayAccess
{
    public $form;
    public $resource;

    protected $module;
    protected $request;
    protected $rules = [];
    protected $duplicate;
    protected $validationValues = [];
    protected $validationMessages = [];
    protected $validationAttributes = [];

    public function __construct($module, Request $request, Form $form)
    {
        $this->module = $module;
        $this->request = $request;
        $this->form = $form;
    }

    public static function make($module, $form, $resource = null)
    {
        $service = new static($module, request(), $form);
        if ($resource) {
            $service->resource($resource);
        }

        return $service;
    }

    public function resource($resource)
    {
        if ($resource) {
            $this->resource = $resource;
            $this->form
                ->withValues($this->getValues())
                ->addHidden('id', ['value' => $resource->getKey()])
            ;
        }

        return $this;
    }

    public function inputs($inputs)
    {
        if (!empty($inputs)) {
            $this->form
                ->add($inputs)
                ->withValues($this->getValues())
                //->setValues($this->getValues())
            ;
        }

        return $this;
    }

    public function scaffold($options = [])
    {
        $inputs = [];
        $model = $this->resource ?? $this->module->getModel();
        $formModel = \Form::model($model, $options)->build();
        $currentInputs = $this->form->inputs();

        foreach ($formModel->elements() as $input) {
            // Use any inputs that have already been established and give them the order value based on the model.
            if (isset($currentInputs[$input->getKey()])) {
                $inputs[] = $currentInputs[$input->getKey()]->setOrder($input->order);
            } else {
                $inputs[] = $input;
            }
        }

        $this->form->add($inputs);

        //$this->form->only($only);

        return $this;
    }

    public function rule($key, $rule, $message = null, $attribute = null)
    {
        $this->rules[$key] = $rule;

        if ($message) {
            $this->validationMessage($key, $message);
        }

        if ($attribute) {
            $this->validationAttribute($key, $attribute);
        }

        return $this;
    }

    public function rules($rules, $messages = [], $attributes = [])
    {
        $this->rules = array_merge($this->rules, $rules);
        $this->validationMessages = array_merge($this->validationMessages, $messages);
        $this->validationAttributes = array_merge($this->validationAttributes, $attributes);

        return $this;
    }

    public function validationMessage($key, $msg)
    {
        $this->validationMessages[$key] = $msg;

        return $this;
    }

    public function validationMessages($messages)
    {
        foreach ($messages as $key => $msg) {
            $this->validationMessage($key, $msg);
        }

        return $this;
    }

    public function validationAttribute($key, $msg)
    {
        $this->validationAttributes[$key] = $msg;

        return $this;
    }

    public function validationAttributes($attributes)
    {
        foreach ($attributes as $key => $msg) {
            $this->validationAttribute($key, $msg);
        }

        return $this;
    }


    public function validationValue($key, $value)
    {
        $this->validationValues[$key] = $value;

        return $this;
    }

    public function validationValues($values)
    {
        foreach ($values as $key => $val) {
            $this->validationValue($key, $val);
        }

        return $this;
    }

    public function duplicate(\Closure $callback)
    {
        $this->duplicate = $callback;

        return $this;
    }

    public function resolveDuplicate($resource, $request)
    {
        if (empty($this->duplicate)) {
            $relationships = $resource->getRelationshipTypes();
            foreach ($relationships as $type) {
                foreach ($type as $method) {
                    $resource->load($method);
                }
            }
            return $resource->replicate();
        }

        return call_user_func($this->duplicate, $resource, $request);
    }

    public function getValues()
    {
        $values = $this->getResourceValues();
        $values = array_merge($values, $this->request->all());
        $values = array_merge($values, $this->module->formValues($this->request, $this->resource));
        $values = array_merge($values, old());

        return $values;
    }

    protected function getResourceValues()
    {
        $values = [];

        if ($this->resource) {

            $values = $this->resource->getAttributes();

            $relationships = $this->resource->getRelationshipTypes();

            if (! empty($relationships)) {

                $formFactory = $this->form->getFactory();
                $manyRelationshipTypes = ['HasMany', 'BelongsToMany', 'MorphToMany', 'MorphMany'];

                foreach ($relationships as $type => $val) {

                    foreach ($val as $method) {

                        $inputType = strtolower($type);
                        if ($formFactory->hasBinding($inputType)) {
                            if (in_array($type, $manyRelationshipTypes)) {
                                if ($rel = $this->resource->{$method}) {
                                    if (method_exists($rel, 'pluck')) {
                                        $values[$method] = $rel->pluck($this->resource->getKeyName())->toArray();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $values;
    }

    //public function buildForm()
    //{
    //    if (!empty($this->module->scaffoldForm)) {
    //        $this->scaffold();
    //    }
    //    $this->inputs($this->module->inputs());
    //    $this->module->form($this, $this->request);
    //
    //}

    public function getValidationRules()
    {
        if (method_exists($this->resource, 'autoValidate')) {
            $this->resource->autoValidate();
        }

        $rules = array_map(function($item){
            if (is_string($item)) {
                $item = [$item];
            }
            return array_unique($item);
        }, array_merge_recursive(
            method_exists($this->resource, 'getRules') ? $this->resource->getRules() : [],
            $this->form->getRules(),
            $this->rules
        ));

        $newRules = [];

        foreach ($rules as $key => $attrRules) {

            if (is_string($attrRules)) {
                $attrRules = [$attrRules];
            }

            $cleanedRules = [];
            foreach ($attrRules as $rule) {

                // Here we swap out placeholder values with attribute values.
                // We'll add an empty primary key to help with instances like unique when inserting.
                $attrs = array_merge([$this->resource->getKeyName() => ''], $this->resource->getAttributes());
                $rule = $this->resource->replaceRulePlaceholders($rule, $attrs);

                // If the model contains a "mime" rule AND their is a valid file upload,
                // then we need to attach the request's file object to the attributes
                // that get validated or it will throw an error.
                $parsedRule = ValidationRuleParser::parse($rule);
                if (($parsedRule[0] == 'Mimes' && $this->request->file($key)) || $parsedRule[0] != 'Mimes') {
                    $cleanedRules[] = $rule;
                }
            }

            if (! empty($cleanedRules)) {
                $newRules[$key] = $cleanedRules;
            }
        }

        return $newRules;
    }

    public function getValidationMessages()
    {
        return array_merge($this->form->getValidationMessages(), $this->validationMessages);
    }

    public function getValidationAttributes()
    {
        return array_merge($this->form->getValidationAttributes(), $this->validationAttributes);
    }

    public function getValidationValues()
    {
        $this->validationValues = $this->module->validationValues($this->request, $this->resource);

        if (empty($this->validationValues)) {
            $this->validationValues = $this->request->all();
        }

        return $this->validationValues;
    }
    //
    //protected function replaceRulePlaceholders($rule, $key, $val = null)
    //{
    //    if (is_array($key)) {
    //        $val = $key;
    //    }
    //
    //    if (is_array($val)) {
    //        foreach ($val as $k => $v) {
    //            $rule = $this->replaceRulePlaceholders($rule, $k, $v);
    //        }
    //    } else {
    //        $rule = str_replace('{'.$key.'}', $val, $rule);
    //    }
    //
    //    return $rule;
    //}

    public function __call($method, $args)
    {
        //if (method_exists($this->form->form, $method)) {
        return call_user_func_array([$this->form, $method], $args);

        //}

        //throw new \BadMethodCallException("Method " . get_class($this) . "::{$method} does not exist.");
    }

    // --------------------------------------------------------------------

    /**
     * @access  public
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->get($key) != null;
    }

    // --------------------------------------------------------------------

    /**
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
     * @access  public
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->form->remove($key);
    }

}
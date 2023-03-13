<?php

namespace Snap\Form;

use ArrayAccess;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Snap\Form\Contracts\ButtonInterface;
use Snap\Form\Contracts\FormElementInterface;
use Snap\Form\Contracts\HiddenInterface;
use Snap\Form\Contracts\InputInterface;
use Snap\Form\Contracts\NestableInterface;
use Snap\Form\Ui\FormTemplate;
use Snap\Support\Contracts\ToString;

class Form implements ToString, Htmlable, ArrayAccess
{
    /**
     * @var \Illuminate\Foundation\Application|mixed|\Snap\Form\FormFactory
     */
    protected $factory;

    /**
     * @var
     */
    protected $groups;

    /**
     * @var \Snap\Form\FormElements
     */
    protected $elements;

    /**
     * @var string
     */
    protected $action = '';

    /**
     * @var string
     */
    protected $method = 'POST';

    /**
     * @var array
     */
    protected $formAttrs = ['class' => 'form', 'enctype' => 'multipart/form-data'];

    /**
     * @var array
     */
    protected $only = [];

    /**
     * @var array
     */
    protected $except = [];

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var bool
     */
    protected $autoIdInputs = true;

    /**
     * @var bool
     */
    protected $useFormTags = true;

    /**
     * @var string
     */
    protected $template = FormTemplate::class;

    /**
     * @var null
     */
    protected $activeGroup = null;

    /**
     * @var string
     */
    protected $defaultGroup = 'Info';

    /**
     * @var null
     */
    protected $scope = null;

    /**
     * @var null
     */
    protected $errors = null;

    /**
     * Form constructor.
     *
     * @param \Snap\Form\FormFactory|null $factory
     */
    public function __construct(FormFactory $factory = null)
    {
        // Not injecting to simplify things with UIComponents and not needing to use Form::make
        //$this->factory = $factory;
        $this->factory = $factory ?? app('form');
        $this->elements = new FormElements();
        $this->setTemplate($this->template);
    }

    /**
     * Creates a valid element ID based on a passed string (generally the name of the input).
     *
     * @param $name
     * @return string
     */
    public static function createId($name)
    {
        return trim(str_replace(['[]', '[', ']', ' ', '{', '}', '/', '.'], ['', '_', '', '_', '', '', '', '_'], $name), '-');
    }

    /**
     * Creates a key value that is usually the same as an element's name value unless the
     * name uses a scoped array syntax in which case the returned value is a dot syntax
     * that represents the array and can be used with the function Arr::get().
     *
     * @param $name
     * @return mixed
     */
    public static function createKey($name)
    {
        return str_replace(['[]', '[', ']', '{', '}'], ['', '.', '', '', ''], $name);
    }

    /**
     * Creates a label based on the name associated with the element.
     *
     * @param $text
     * @return null|string|string[]
     */
    public static function createLabelText($text)
    {
        if (strpos($text, '[') !== false) {
            $text = preg_replace('#.+\[(\w+)\]#', '$1', $text);
        }

        $text = ucfirst(str_replace(['_id', '_'], ['', ' '], $text));

        return $text;
    }

    /**
     * Adds an element to the form instance. There are a number of ways to add a form element:
     * ```
     * $form->add('name', 'text', ['placeholder' => 'Enter In Your First & Last Name...'], 'Basic');
     *
     * // With the inputs type as a key in the properties array
     * $form->add('name', ['type' => 'text', 'placeholder' => 'Enter In Your First & Last Name...'], 'Basic');
     *
     * // With the input's name and type as keys in the properties array
     * $form->add(['name' => 'name', 'type' => 'text', 'placeholder' => 'Enter In Your First & Last Name...'], 'Basic');
     *
     * // With the input's group as a key in the properties array
     * $form->add(['name' => 'name', 'type' => 'text', 'placeholder' => 'Enter In Your First & Last Name...', 'group' => 'Basic']);
     *
     * // With a provided element
     * $element = new \Snap\Form\Elements\Text('name', ['placeholder' => 'Enter In Your First & Last Name...']);
     * $form->add($element, 'Basic');
     * ```
     *
     * @param $element
     * @param null $type
     * @param array $props
     * @param null $group
     * @return $this
     */
    public function add($element, $type = null, $props = [], $group = null)
    {
        if (is_iterable($element) && ! $element instanceof NestableInterface) {
            foreach ($element as $key => $val) {
                if ($val instanceof FormElementInterface) {
                    $this->add($val);
                } else {
                    $this->add($key, $val);
                }
            }
        } else {

            // If the $element is just a string, we'll treat it as a Group value and set the active group.
            if (is_string($element) && empty($type)) {
                $this->activeGroup = $element;

                return $this;
            } elseif (! $element instanceof FormElementInterface) {
                $element = $this->create($element, $type, $props);
            }

            if ($element instanceof InputInterface) {

                // Automatically set an ID on the element if configured to do so.
                if (! empty($this->autoIdInputs) && ! $element->getId()) {
                    $element->setId(Form::createId($element->getKey()));
                }

                // Automatically set the scope if it is set on the form already.
                if (! empty($this->scope) && ! $element->getScope()) {
                    $element->setScope($this->scope);
                }

                // Automatically set a value on an element if it is set on the form and is null on the input.
                if (is_null($element->getValue())) {
                    $element->setValue($this->getValue($element->getKey()));
                }
            }

            if (! $element) {
                throw new InvalidArgumentException("The form element of type \"$type\" could not be identified.");
            }

            if ($this->activeGroup) {
                $group = $this->activeGroup;
            } elseif (is_null($group)) {
                $group = $this->defaultGroup;
            }

            if (empty($element->getGroup())) {
                $element->setGroup($group);
            }

            $this->elements[$element->getKey()] = $element;

            // If added after an only has been set, we'll add it to the only array.
            if (! empty($this->only)) {
                $this->only[] = $element->getKey();
            }

            // Or, if except, we'll remove it from the except.
            if (! empty($this->except)) {
                $this->except = array_remove_value($this->except, $element->getKey());
            }
        }

        return $this;
    }

    /**
     * Creates an element that can be added to the form.
     * ```
     * $input = $form->create('name', 'text', ['placeholder' => 'Enter In Your First & Last Name...']);
     *
     * // With the inputs type as a key in the properties array
     * $input = $form->create('name', ['type' => 'text', 'placeholder' => 'Enter In Your First & Last Name...']);
     *
     * // With the input's name and type as keys in the properties array
     * $input = $form->create(['name' => 'name', 'type' => 'text', 'placeholder' => 'Enter In Your First & Last Name...']);
     * ```
     *
     * @param $name
     * @param null $type
     * @param array $props
     * @return mixed
     */
    public function create($name, $type = null, $props = [])
    {
        $element = $this->factory->element($name, $type, $props);

        if ($element instanceof InputInterface) {
            $this->initializeInput($element, $props);
        }

        return $element;
    }

    /**
     * Returns an element that has been added to the form.
     *
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        return Arr::get($this->elements, $name);
    }

    /**
     * Returns a collection of elements that have been added to the form.
     *
     * @param $names
     * @return \Snap\Form\FormElements
     */
    public function getMany($names)
    {
        $names = is_array($names) ? $names : func_get_args();
        $inputs = [];
        foreach ($names as $name) {
            if ($input = $this->get($name)) {
                $inputs[] = $input;
            }
        }

        return new FormElements($inputs);
    }

    /**
     * Removes one or more elements from the form.
     * ```
     * // Passing an array
     * $form->remove(['created_at', 'deleted_at']);
     *
     * // Passing multiple arguments
     * $form->remove('created_at', 'deleted_at');
     * ```
     *
     * @param array $inputs
     * @return $this
     */
    public function remove($inputs = [])
    {
        $inputs = is_array($inputs) ? $inputs : func_get_args();

        $this->elements->forget($inputs);

        return $this;
    }

    /**
     * Clears the elements, values, and any element rendering "only" or "except" values
     * from the form insance.
     *
     * @return $this
     */
    public function clear()
    {
        $this->elements = new FormElements();
        $this->values = [];
        $this->only = [];
        $this->except = [];

        return $this;
    }

    /**
     * Returns a collection of form elements. Passing in a $filter parameter, which can
     * be either an array of element names or a callback closure function, will reduce
     * the returned elements returned.
     *
     * @param null $filter
     * @return \Snap\Form\FormElements
     */
    public function elements($filter = null)
    {
        $elements = $this->elements->filter(function ($input) {
            return $this->isAllowedInput($input->key);
        });

        if ($filter) {
            return $elements->filter($filter);
        }

        return $elements;
    }

    /**
     * Filters the elements based on either an array of element names or a closure
     * function that returns either "true" or "false" based on conditions.
     *
     * @param $filter
     * @return \Snap\Form\FormElements
     */
    public function filter($filter)
    {
        if (is_array($filter)) {
            $elements = $this->elements->filter(function ($element, $key) use ($filter) {
                foreach ($filter as $k => $v) {
                    if (is_int($k) || (! is_int($k) && isset($element->$k) && $element->$k == $v)) {
                        return true;
                    }
                }

                return false;
            });
        } elseif (is_callable($filter)) {
            $elements = $this->elements->filter($filter);
        } else {
            $elements = new FormElements($this->elements->all());
        }

        return $elements;
    }

    /**
     * Returns only the elements that are inputs (implements Snap\Form\Contracts\InputInterface)
     * and not hidden (\Snap\Form\Contracts\HiddenInterface).
     *
     * @return \Snap\Form\FormElements
     */
    public function inputs()
    {
        $inputs = $this->filter(function ($input) {
            return $this->isAllowedInput($input->key) && ($input instanceof InputInterface && ! ($input instanceof HiddenInterface && $input->isHidden()));
        });

        return $inputs;
    }

    /**
     * Returns only the elements that are buttons (implements Snap\Form\Contracts\ButtonInterface).
     *
     * @return \Snap\Form\FormElements
     */
    public function buttons()
    {
        $buttons = $this->filter(function ($input) {
            return $input instanceof ButtonInterface;
        });

        return $buttons;
    }

    /**
     * Returns only the elements that are hidden (implements Snap\Form\Contracts\HiddenInterface).
     *
     * @return \Snap\Form\FormElements
     */
    public function hidden()
    {
        $hidden = $this->filter(function ($input) {
            return $input instanceof HiddenInterface && $input->isHidden();
        });

        return $hidden;
    }

    /**
     * Sets all input elements assigned to the form at that time to be display only.
     *
     * @return $this
     */
    public function displayOnly()
    {
        foreach ($this->inputs() as $input) {
            $input->displayOnly(true);
        }

        return $this;
    }

    /**
     * Returns "true" or "false" as to whether the input should be rendered based on
     * the "only" and "except" values associated with the form instance.
     *
     * @param $input
     * @return bool
     */
    public function isAllowedInput($input)
    {
        return ! ((! empty($this->only) && ! in_array($input, $this->only)) || (! empty($this->except) && in_array($input, $this->except)));
    }

    /**
     * Returns the form elements grouped.
     *
     * @param array $groups
     * @param null $filter
     * @return mixed
     */
    public function groups(array $groups = [], $filter = null)
    {
        $this->groupOrder($groups);
        $inputs = ($filter) ? $this->filter($filter) : $this->inputs();

        foreach ($inputs as $input) {

            if (! isset($this->groups[$input->getGroup()])) {
                $this->groups[$input->getGroup()] = new FormElements();
            }

            $this->groups[$input->getGroup()][$input->getKey()] = $input;
        }

        return $this->groups;
    }

    /**
     * Sets the active group for which all subsequent elements that are added to the
     * form will be assigned.
     *
     * @param $group
     * @param null $inputs
     * @return $this
     */
    public function group($group, $inputs = null)
    {
        //if (is_null($inputs)) {
        //
        //    foreach ($group as $g => $f) {
        //        $this->group($g, $f);
        //    }
        //} else {
        if (empty($inputs)) {
            $this->activeGroup = $group;
        } else {
            $newGroups = [];

            foreach ($inputs as $input) {
                $newGroups[$group] = $input;

                if (isset($this->groups[$group])) {
                    $newGroups = array_merge($this->groups, $newGroups);
                }

                if ($element = $this->get($input)) {
                    $element->setGroup($group);
                }
            }
        }

        return $this;
    }

    /**
     * Specifies the ordering of the groups.
     *
     * @param $order
     * @return $this
     */
    public function groupOrder($order)
    {
        foreach ($order as $group) {

            // Unset if it exists already to give it a new associative order in the array.
            if (isset($this->groups[$group])) {
                unset($this->groups[$group]);
            }

            $this->groups[$group] = [];
        }

        return $this;
    }

    /**
     * Returns the values assigned to the form inputs.
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Sets the values for the form inputs.
     *
     * @param $values
     * @return $this
     */
    public function withValues($values)
    {
        // $this->values = $values;
        //$oldInput = old();
        //$values = array_merge($values, $oldInput);

        // Convenience if a model is passed instead of an array.
        if (is_object($values) && method_exists($values, 'toArray')) {
            $values = $values->toArray();
        }

        if ($values) {
            foreach ($values as $key => $val) {
                $this->setValue($key, $val);
            }
        }

        return $this;
    }

    /**
     * Sets the value for a single form input.
     *
     * @param $key
     * @param $val
     * @return $this
     */
    public function setValue($key, $val)
    {
        $this->values[$key] = $val;

        $input = ($key instanceof InputInterface) ? $key : $this->get($key);

        if ($input instanceof InputInterface) {
            $input->setValue($val);
        }

        return $this;
    }

    /**
     * Returns the values for a single form input.
     *
     * @param $key
     * @return mixed|null
     */
    public function getValue($key)
    {
        //$field = $this->get($key);
        //if ($field) {
        //    return $field->getValue();
        //}

        //return null;

        if (is_string($key) && array_key_exists($key, $this->values)) {
            return $this->values[$key];
        }

        return null;
    }

    /**
     * Returns the rules assigned to the inputs.
     *
     * @param array $inputs
     * @param string $prefix
     * @return array
     */
    public function getRules($inputs = [], $prefix = '')
    {
        if (empty($inputs)) {
            $inputs = $this->inputs();
        }

        if (! empty($prefix)) {
            $prefix = $prefix.'.*';
        }

        $rules = [];
        foreach ($inputs as $input) {
            $rules[$prefix.$input->key] = (array) $input->getRules();
            if ($input instanceof NestableInterface) {
                $this->getRules($input->getInputs(), $input->key);
            }
        }

        return $rules;
    }

    /**
     * Returns all the custom validation messages for the form.
     *
     * @return array
     */
    public function getValidationMessages()
    {
        $messages = [];
        foreach ($this->inputs() as $input) {
            foreach ($input->getValidationMessages() as $rule => $message) {
                $messages[$input->key.'.'.$rule] = $message;
            }
        }

        return $messages;
    }

    /**
     * Returns all the custom validation attributes for the form.
     *
     * @return array
     */
    public function getValidationAttributes()
    {
        $attributes = [];
        foreach ($this->inputs() as $input) {
            $attributes[$input->key] = $input->getValidationAttribute();
        }

        return $attributes;
    }

    /**
     * Returns any validation errors.
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Runs the validation on the form.
     *
     * @return mixed
     */
    public function validate($values = null)
    {
        if (is_null($values)) {
            $values = request()->all();
        }

        $validator = \Validator::make($values, $this->getRules(), $this->getValidationMessages());
        $passed = $validator->passes();

        $this->errors = $validator->errors()->all();

        return $passed;
    }

    /**
     * Moves one or more form inputs from one position to another.
     * ```
     * // Single input move.
     * $form->move('email', 'after:name');
     *
     * // Passing multiple inputs to move.
     * $form->move(['email', 'address', 'city', 'state', 'zip'], 'after:name');
     * ```
     *
     * @param $inputs
     * @param $to
     * @return $this|void
     */
    public function move($inputs, $to)
    {
        $inputs = $this->getMany($inputs);

        $positionParts = explode(':', $to);

        // Must contain a colon in second parameter or we don't do anything.
        if (count($positionParts) < 2) {
            return;
        }

        $position = $positionParts[0];
        $toInput = $this->get($positionParts[1]);

        if ($toInput) {
            if ($position == 'before') {
                $order = $toInput->getOrder();
                $newOrder = (float) $order - .01;
            } else {
                $order = $toInput->getOrder();
                $newOrder = (float) $order + .01;
            }

            foreach ($inputs as $input) {
                $input->setOrder($newOrder);
            }
        }

        $this->order();

        return $this;
    }

    /**
     * @param $prop
     * @param null $val
     * @param null $inputs
     * @return $this
     */
    public function assign($prop, $val = null, $inputs = null)
    {
        $this->elements->assign($prop, $val, $inputs);
        //if (! is_callable($prop)) {
        //    $filter = function ($input) use ($prop, $val, $inputs) {
        //        if (empty($inputs) || (! empty($inputs)) && in_array($input->getKey(), $inputs)) {
        //            //$method = 'set'.ucfirst(camel_case($prop));
        //            $input->{$prop} = $val;
        //        }
        //    };
        //} else {
        //    $filter = $prop;
        //}
        //
        //$this->elements->each($filter);

        return $this;
    }

    /**
     * @param $auto
     * @return $this
     */
    public function setAutoIdInputs($auto)
    {
        $this->autoIdInputs = (bool) $auto;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasAutoIdInputs()
    {
        return $this->autoIdInputs;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function useFormTags(bool $bool)
    {
        $this->useFormTags = $bool;

        return $this;
    }

    /**
     * @param $action
     * @param array $attrs
     * @return $this
     */
    public function open($action, $attrs = [])
    {
        $this->opened = true;
        $this->setAction($action);
        $this->setOpenAttrs($attrs);

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return array
     */
    public function getFormAttrs()
    {
        return $this->formAttrs;
    }

    /**
     * @param array $attrs
     * @return $this
     */
    public function setFormAttrs($attrs = [])
    {
        unset($attrs['action'], $attrs['method']);
        $this->formAttrs = array_merge($this->formAttrs, $attrs);

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param $keys
     * @return $this
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        $this->order($keys);
        $this->only = $keys;

        return $this;
    }

    /**
     * @param $keys
     * @return $this
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        $this->order($keys);
        $this->except = $keys;

        return $this;
    }

    /**
     * @param array $order
     * @return $this
     */
    public function order($order = [])
    {
        $order = is_array($order) ? $order : func_get_args();

        if (! empty($order)) {
            $num = count($order);

            for ($i = 0; $i < $num; $i++) {
                if (isset($this->elements[$order[$i]])) {
                    $this->elements[$order[$i]]->setOrder($i);
                }
            }
        }

        $inputs = $this->elements->all();

        uasort($inputs, function ($a, $b) {
            if ($a === $b) {
                return 0;
            }

            return ($a->getOrder() > $b->getOrder()) ? 1 : -1;
        });

        $this->elements = new FormElements($inputs);

        return $this;
    }

    /**
     * @param $scope
     * @return $this
     */
    public function scope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultGroup()
    {
        return $this->defaultGroup;
    }

    /**
     * @param string $defaultGroup
     * @return $this
     */
    public function setDefaultGroup(string $defaultGroup)
    {
        foreach ($this->elements as $element) {
            if ($element->getGroup() == $this->defaultGroup) {
                $element->setGroup($defaultGroup);
            }
        }

        $this->defaultGroup = $defaultGroup;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $template
     * @return $this
     */
    public function setTemplate($template)
    {
        if (is_string($template)) {
            $template = ui($template);
        }

        $this->template = $template;

        return $this;
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed|null|\Snap\Form\FormFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return mixed|string
     */
    public function toHtml()
    {
        return $this->render();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $this->order();

        $method = $this->getMethod();
        $data = [
            'use_form_tag' => $this->useFormTags,
            'form_attrs'   => $this->getFormAttrs(),
            'action'       => $this->getAction(),
            'method'       => strtoupper($method) != 'GET' ? 'POST' : 'GET',
            '_method'      => $method,
            'inputs'       => $this->inputs(),
            'buttons'      => $this->buttons(),
            'groups'       => $this->groups([]),
            'hidden'       => $this->hidden(),
            // 'ref' => $this->getRef(),
        ];

        $this->template->with($data);

        return $this->template->render();
    }

    /**
     * @param $type
     * @param $request
     * @param null $resource
     */
    public function runPostProcessors($type, $request, $resource = null)
    {
        foreach ($this->inputs() as $input) {
            $input->runPostProcessor($type, $request, $resource);
        }
    }

    /**
     * @param $method
     * @param $args
     * @return $this|\Snap\Form\Form
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        if (preg_match("/^add(.*)/", $method, $found)) {
            $params = $args[1] ?? [];
            $group = $args[2] ?? null;

            return $this->add($args[0], strtolower($found[1]), $params, $group);
        } else {
            throw new \Exception("Invalid method call '$method' on Form");
        }
    }

    /**
     * @access  public
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->get($key) != null;
    }

    /**
     * @access  public
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * @access  public
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->get($key)->setData($value);
    }

    /**
     * @access  public
     * @param string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * Initializes the main properties of the input.
     *
     * @param $input
     * @param $props
     * @return mixed
     */
    protected function initializeInput($input, $props)
    {
        if (! isset($input->value) && ! array_key_exists('value', $props)) {
            $value = Arr::get($this->values, $input->getKey()) ?? $this->getValue($input->getKey());
            $input->setValue($value);
        }

        if (! empty($this->scope) && ! array_key_exists('scope', $props) && ! $input->scope) {
            $input->setScope($this->scope);
        }

        // Set the order of the input based on the length of the inputs array.
        if (is_null($input->getOrder())) {
            $order = $this->elements->get($input->getKey()) ? $this->elements->get($input->getKey())
                                                                             ->getOrder() : $this->elements->count();
            $input->setOrder($order);
        }

        return $input;
    }
}
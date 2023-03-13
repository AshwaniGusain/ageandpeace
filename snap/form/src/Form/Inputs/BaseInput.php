<?php

namespace Snap\Form\Inputs;

use Snap\Form\Contracts\InlineLabelInterface;
use Snap\Form\Contracts\InputInterface;
use Snap\Form\Contracts\InputProcessorInterface;
use Snap\Form\FormElement;
use Snap\Form\Label;
use Snap\Support\Helpers\UtilHelper;
use Snap\Ui\Traits\CssTrait;
use Snap\Ui\Traits\JsTrait;

abstract class BaseInput extends FormElement implements InputInterface
{
    use JsTrait;
    use CssTrait;

    /**
     * @var
     */
    protected $name;

    /**
     * @var
     */
    protected $label;

    /**
     * @var
     */
    protected $comment;

    /**
     * @var
     */
    protected $value;

    /**
     * @var
     */
    protected $scope;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $validationMessages = [];

    /**
     * @var
     */
    protected $validationAttribute;

    /**
     * @var array
     */
    protected $postProcess = [];

    /**
     * @var
     */
    protected $scripts;

    /**
     * @var
     */
    protected $styles;

    /**
     * @var
     */
    protected $displayValue;

    /**
     * @var bool
     */
    protected $displayOnly = false;

    /**
     * @var
     */
    protected $displayOnlyView = 'form::display-only';

    // Use mostly for meta fields where you need to know what type of data to save as

    /**
     * @var
     */
    protected $cast;

    /**
     * @var
     */
    protected $beforeHtml = '';

    /**
     * @var
     */
    protected $afterHtml = '';

    /**
     * BaseInput constructor.
     *
     * @param $name
     * @param array $data
     */
    public function __construct($name, $data = [])
    {
        // Set this above the constructor so it can be overwritten by the data parameter.
        if (is_string($data)) {
            $this->setLabel($data);
            $data = ['label' => $data];
        } else {
            $transKey = 'form::labels.'.$name;
            $label = (app('translator')->has($transKey)) ? app('translator')->get($transKey) : Label::convertNameToLabel($name);
            $this->setLabel($label);
        }

        $this->setName($name);
        $this->setKey($this->getName());
        parent::__construct($data);

        $this->label->fromInput($this);

        $this->addRenderer(function () {
            return ! empty($this->displayOnly);
        }, '_renderDisplayValue');
    }

    /**
     * Placeholder.
     *
     * @return void
     */
    public function initialize()
    {

    }

    /**
     * Static function for creating the form input.
     *
     * @param $name
     * @param array $data
     * @param null $group
     * @return \Snap\Form\Inputs\BaseInput
     */
    public static function make($name, $data = [], $group = null)
    {
        if ($group) {
            $data['group'] = $group;
        }

        return new static($name, $data, $group);
    }

    /**
     * Returns the name of the field.
     *
     * @param bool $scoped
     * @return null|string|string[]
     */
    public function getName($scoped = true)
    {
        $name = $this->name;
        if ($scoped && ! empty($this->scope)) {
            if ($this->isArrayScope()) {
                $name = preg_replace('#(.+)(\[\])$#U', '$1['.$name.']', $this->getScope());
            } else {
                $name = $this->getScope().$name;
            }
        }

        return $name;
    }

    /**
     * Sets the name of the input.
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the scope for the name of the field.
     *
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * The scope applies to the name of the field (e.g. vars[] would yield vars['my_input'])
     *
     * @param $scope
     * @return $this
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        $oldKey = $this->key;
        $this->setKey($this->getName(true));
        if ($this->label->for == $oldKey) {
            $this->label->setFor($this->key);
        }

        // $this->setPostKey(Form::createValueKey($scopedName));

        return $this;
    }

    /**
     * Determines if the scope for the input name is an array or not (i.e. does it contain []).
     *
     * @return bool
     */
    public function isArrayScope()
    {
        return $this->getScope() && preg_match('#.+(\[\])$#U', $this->getScope()) && strpos($this->name, '[') === false;
    }

    /**
     * Returns the value of the input.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the input.
     *
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Returns the label of the input.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the label of the input which can be either a string or a \Snap\Form\Label class instance.
     *
     * @param $label
     * @return $this
     */
    public function setLabel($label)
    {
        if (! $label instanceof Label) {
            $label = Label::make($label);
        }

        $this->label = $label;

        return $this;
    }

    /**
     * Returns the comment of an input.
     *
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the comment of an input which can appear as a tooltip on the input's label denoted by [?].
     *
     * @param $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        $this->label->setComment($comment);

        return $this;
    }

    /**
     * Returns a boolean value based on if the input is required or not.
     *
     * @return bool
     */
    public function isRequired()
    {
        return (bool) $this->required;
    }

    /**
     * Sets whether the input is required or not.
     *
     * @param bool $required
     * @return $this
     */
    public function setRequired($required = true)
    {
        $this->required = (bool) $required;

        if (! in_array('required', $this->rules)) {
            $this->rules[] = 'required';
        }

        $this->label->setRequired($required);

        return $this;
    }

    /**
     * Sets the group the input belongs to.
     * This correlates to what tab the input belongs to in the base form template output.
     *
     * @param $group
     * @return $this|\Snap\Form\FormElement
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Returns the group the input belongs to.
     *
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Returns an error (if any) associated with the input.
     *
     * @param int $key
     * @return mixed
     */
    public function getError($key = 0)
    {
        $errors = $this->getErrors();

        return array_get($errors, $key);
    }

    /**
     * Returns all arrays associated with the input.
     *
     * @return array|\Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    public function getErrors()
    {
        $errors = session('errors', []);

        if ($errors) {

            if (is_string($errors)) {
                return [$errors];
            } elseif (is_array($errors)) {
                return $errors;
            } else {
                return $errors->get($this->key);
            }
        }

        return [];
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return (! empty($this->getError())) ? true : false;
    }

    /**
     * Returns whether the input should be displayed with the label next to it
     * instead of above and is generally associated with checkbox and radio inputs.
     *
     * @return bool
     */
    public function isInline()
    {
        return $this instanceof InlineLabelInterface;
    }

    /**
     * Alias to setDisplayOnly().
     * Determines whether the output should be rendered in "display only" mode
     * which generally means it shows the value without the input.
     *
     * @param $display
     * @return \Snap\Form\Inputs\BaseInput
     */
    public function displayOnly($display)
    {
        return $this->setDisplayOnly($display);
    }

    /**
     * Returns a boolean as to whether the input should be rendered in
     * "display only" mode or not.
     *
     * @return bool
     */
    public function isDisplayOnly()
    {
        return $this->displayOnly;
    }

    /**
     * Sets the "display only" mode which by default renders the value
     * of the input instead of the input.
     *
     * @param $display
     * @return $this
     */
    public function setDisplayOnly($display)
    {
        $this->displayOnly = $display;

        return $this;
    }

    /**
     * Alias to setDisplayValue.
     *
     * @param $display
     * @return \Snap\Form\Inputs\BaseInput
     */
    public function displayAs($display)
    {
        return $this->setDisplayValue($display);
    }

    /**
     * Returns the display value.
     *
     * @return mixed|string
     */
    public function getDisplayValue()
    {
        if ($this->displayValue instanceof \Closure) {
            $closure = $this->displayValue;

            return $closure($this->value, $this);
        }

        if ($this->displayValue) {
            return $this->displayValue;
        } else {
            return is_array($this->value) ? json_encode($this->value) : $this->value;
        }
    }

    /**
     * Sets what the display only value should be in the form.
     * A closure can be used and it will be rendered instead of the value.
     *
     * @param $display
     * @return $this
     */
    public function setDisplayValue($display)
    {
        $this->displayValue = $display;

        return $this;
    }

    /**
     * Alias of setCast.
     *
     * @param $cast
     * @return \Snap\Form\Inputs\BaseInput
     */
    public function castAs($cast)
    {
        return $this->setCast($cast);
    }

    /**
     * Returns the cast associated with the input.
     *
     * @return mixed
     */
    public function getCast()
    {
        return $this->cast;
    }

    /**
     * The cast value is used to transform the values that were
     * determined by the inputs into their eventual output
     * (e.g. Wysiwyg::make('body')->castAs(StringDecorator::class)).
     *
     * @param $cast
     * @return $this
     */
    public function setCast($cast)
    {
        $this->cast = $cast;

        return $this;
    }


    /**
     * Returns the HTML that will be placed right before the rendered html.
     *
     * @return string
     */
    public function getBeforeHtml()
    {
        return $this->beforeHtml;
    }

    /**
     * Sets HTML to be placed right before the rendered html.
     *
     * @param $html
     * @return string
     */
    public function setBeforeHtml($html)
    {
        $this->beforeHtml = $html;

        return $this;
    }

    /**
     * Returns the HTML that will be placed right after the rendered html.
     *
     * @return string
     */
    public function getAfterHtml()
    {
        return $this->afterHtml;
    }

    /**
     * Sets HTML to be placed right after the rendered html.
     *
     * @param $html
     * @return string
     */
    public function setAfterHtml($html)
    {
        $this->afterHtml = $html;

        return $this;
    }



    /**
     * Some inputs required processing on post. This involves setting
     * callback function and a hook ($type) of when to execute it in
     * the post request.
     *
     * @param $callback
     * @param string $type
     * @param null $order
     */
    public function setPostProcess($callback, $type = 'beforeValidation', $order = null)
    {
        if (empty($this->postProcess[$type]) || (! in_array($callback, $this->postProcess[$type]) && is_callable($callback))) {
            if ($order) {
                $this->postProcess[$type][$order] = $callback;
            } else {
                $this->postProcess[$type][] = $callback;
            }
        }
    }

    /**
     * Runs all the post processes associated with the input.
     *
     * @param $type
     * @param $request
     * @param null $resource
     */
    public function runPostProcessor($type, $request, $resource = null)
    {
        if (is_string($type)) {
            $type = [$type];
        }

        foreach ($type as $t) {
            if (isset($this->postProcess[$t])) {
                ksort($this->postProcess[$t]);
                foreach ($this->postProcess[$t] as $process) {
                    $value = array_get($request->all(), $this->key);

                    if (UtilHelper::isInstanceOf($process, InputProcessorInterface::class)) {
                        (new $process($this))->run($request, $resource);
                    } else {
                        call_user_func($process, $value, $this, $request, $resource);
                    }
                }
            }
        }
    }

    /**
     * Returns the validation rules associated with the input.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Sets one or more validation rules with the input.
     *
     * @param $rules
     * @return $this
     */
    public function setRules($rules)
    {
        $this->rules = is_string($rules) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * Adds additional rules to the input.
     *
     * @param $rule
     * @return $this
     */
    public function addRule($rule)
    {
        if (! in_array($rule, $this->rules)) {
            $this->rules[] = $rule;
        }

        return $this;
    }

    /**
     * Returns any custom validation messages associated with the input.
     *
     * @return array
     */
    public function getValidationMessages()
    {
        return $this->validationMessages;
    }

    /**
     * Sets custom validation messages on the input.
     *
     * @param $validationMessages
     * @return $this
     */
    public function setValidationMessages($validationMessages)
    {
        foreach ($validationMessages as $rule => $message) {
            $this->validationMessages[$rule] = $message;
        }

        return $this;
    }

    /**
     * Returns any custom validation attributes associated with the input.
     *
     * @return mixed
     */
    public function getValidationAttribute()
    {
        if (! isset($this->validationAttribute)) {
            $this->validationAttribute = $this->getLabel()->text;
        }

        return $this->validationAttribute;
    }

    /**
     * Sets a custom validation attribute on the input.
     *
     * @param $attribute
     * @return $this
     */
    public function setValidationAttribute($attribute)
    {
        $this->validationAttribute = $attribute;

        return $this;
    }

    /**
     * A method to be overwritten by inputs to transform database field information into an input.
     * An example of what is passed as the props is an array:
     * ```
     * [
     * "name" => "id",
     * "type" => "int",
     * "length" => "10",
     * "options" => null,
     * "default" => null,
     * "primary_key" => true,
     * "comment" => null,
     * "null" => true,
     * "auto_increment" => true,
     * "unsigned" => false,
     * ]
     * ```
     *
     * @param $props
     * @param $form
     */
    public function convertFromModel($props, $form)
    {

    }

    /**
     * Returns the display value and is used when set to "display only" mode.
     *
     * @return mixed|string
     */
    public function _renderDisplayValue()
    {
        return view($this->displayOnlyView, $this->gatherData());
    }

    protected function _render()
    {
        return $this->getBeforeHtml().parent::_render().$this->getAfterHtml();
    }
}
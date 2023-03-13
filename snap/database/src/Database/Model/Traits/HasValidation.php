<?php

namespace Snap\Database\Model\Traits;

use Illuminate\Validation\ValidationRuleParser;
use Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Snap\Database\Model\Exceptions\ValidationException;
use Snap\Database\Model\Traits\Observables\ValidationObserver;

// Borrowed from:
// https://raw.githubusercontent.com/JeffreyWay/Laravel-Model-Validation/master/src/Way/Database/Model.php
trait HasValidation {

    use HasAutoValidation;

    //  /**
    //   * Error message bag
    //   *
    //   * @var Illuminate\Support\MessageBag
    //   */
    //  protected $errors;

    //  /**
    //   * Validation rules
    //   *
    //   * @var array
    //   */
    //  public static $rules = [];

    //  /**
    //   * Custom messages
    //   *
    //   * @var array
    //   */
    //  protected static $messages = [];

    //  /**
    //   * Validator instance
    //   *
    //   * @var Illuminate\Validation\Validators
    //   */
    //  protected $validator;


    //  /**
    //   * Determines whether to run validation on save
    //   *
    //   * @var boolean
    //   */
    //  protected $validate = true;

    public static function bootHasValidation()
    {
        static::observe(new ValidationObserver);
    }

    public function skipValidation($validate) {
        $this->skipValidation = (bool) $validate;
        return $this;
    }

    public function shouldValidate() {
        return ! $this->skipValidation;
    }

    /**
     * Returns the validator object.
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validator()
    {
        if ( ! isset($this->validator)) {
            $this->validator = \App::make('validator');
        }

        return $this->validator;
    }

    /**
     * Validates current attributes against rules.
     */
    public function validate()
    {
        $attributes = $this->getAttributes();
        $rules = $this->getRules();
        $messages = $this->getValidationMessages();

        $newRules = [];
        foreach($rules as $key => $attrRules) {

            $cleanedRules = [];
            foreach ($attrRules as $rule) {

                // Here we swap out placeholder values with attribute values.
                $parsedRule = ValidationRuleParser::parse($rule);
                foreach($this->getAttributes() as $k => $v) {
                    if ( ! is_object($v)) {
                        $rule = static::replaceRulePlaceholders($rule, $k, $v);
                    }
                }

                // If the model contains a "mime" rule AND their is a valid file upload,
                // then we need to attach the request's file object to the attributes
                // that get validated or it will throw an error.
                if (($parsedRule[0] == 'Mimes' && request()->file($key)) || $parsedRule[0] != 'Mimes') {
                    $cleanedRules[] = $rule;
                }
            }

            if (!empty($cleanedRules)) {
                $newRules[$key] = $cleanedRules;
            }
        }

        $v = $this->validator()->make($attributes, $newRules, $messages);

        if ($v->passes()) {
            return true;
        }

        $this->setErrors($v->messages());

        return false;
    }

    /**
     * @param $rule
     * @param $key
     * @param $val
     * @return mixed
     */
    public static function replaceRulePlaceholders($rule, $key, $val = null)
    {
        if (is_array($key)) {
            $val = $key;
        }

        if (is_array($val)) {
            foreach ($val as $k => $v) {
                $rule = static::replaceRulePlaceholders($rule, $k, $v);
            }
        } elseif (is_string($val) || is_numeric($val)) {
            $rule = str_replace('{'.$key.'}', $val, $rule);
        }

        return trim($rule, ',');
    }

    /**
     * Set error message bag.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Adds an error to the model.
     *
     * @param  string  $msg
     * @param  string  $key
     * @return static
     */
    public function addError($msg, $key = null)
    {
        $errors = $this->getErrors();
        if ($msg instanceof MessageBag) {
            $errors->merge($msg);
        } else {
            if (is_null($key) && $errors) {
                $key = count($errors);
            }
            if (!$errors) {
                $errors = new MessageBag();
            }
            $errors->add($msg, $key);
        }
        $this->setErrors($errors);

        return $this;
    }

    /**
     * Retrieve error message bag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Inverse of wasSaved
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }

    /**
     * Add a basic validation rule. If a rule already exists, it will append to it.
     *
     * @param  string  $key
     * @param  mixed  $rule
     * @param  string  $message
     * @return static
     */
    public static function addRule($key, $rule, $message = '')
    {
        if ( ! empty(static::$rules[$key])) {

            if (is_string(static::$rules[$key])) {
                static::$rules[$key] = explode('|', static::$rules[$key]);
            }

            static::removeRule($key, $rule);
            $rules = static::$rules[$key];
            $rules[] = $rule;
            $rule = $rules;
        }

        if (is_string($rule)) {
            $rule = explode('|', $rule);
        }

        static::$rules[$key] = $rule;
        if (!empty($message)) {
            if (is_array($message)) {
                $key = key($message);
                $message = current($message);
            }
            static::$messages[$key] = $message;
        }

        return static::class;
    }

    /**
     * Adds multiple rules to the class.
     *
     * @param $rules
     * @return string
     */
    public static function addRules($rules)
    {
        foreach ($rules as $key => $rule) {
            static::addRule($key, $rule);
        }

        return static::class;
    }

    /**
     * Returns the array of rules to be run when a model is saved.
     *
     * @return array
     */
    public static function getRules()
    {
        $rules = [];
        foreach (static::$rules as $i => $attrRules) {
            if (!is_array($attrRules)) {
                $attrRules = explode('|', $attrRules);
            }

            $rules[$i] = $attrRules;
        }
        return $rules;
    }

    /**
     * Removes a basic validation rule.
     *
     * @param  string  $key
     * @param  string  $rule
     * @return static
     */
    public static function removeRule($key, $rule = null)
    {
        if (empty($rule)) {
            unset(static::$rules[$key]);

        } else {

            static::$rules[$key] = (is_string(static::$rules[$key])) ? explode('|', static::$rules[$key]) : static::$rules[$key];

            foreach(static::$rules[$key] as $i => $r) {

                if ($rule == $r) {
                    unset(static::$rules[$key][$i]);
                    static::$rules[$key] = array_values(static::$rules[$key]);
                }
            }
        }

        return static::class;
    }

    /**
     * Clears all validation rules
     *
     * @return string
     */
    public function clearRules()
    {
        static::$rules = [];

        return static::class;
    }

    /**
     * Adds a validation message
     *
     * @param $key
     * @param $message
     * @return string
     */
    public static function addValidationMessage($key, $message)
    {
        if (is_array($key)) {
            static::$messages[$key] = array_merge(static::$messages[$key], $key);
        } else {
            static::$messages[$key] = $message;
        }

        return static::class;
    }

    /**
     * Returns a single validation message based on the passed key value.
     *
     * @param string $key
     * @param string $default
     * @return array
     */
    public static function getValidationMessage($key, $default = '')
    {
        $message = (isset(static::$messages[$key])) ? static::$messages[$key] : $default;

        return [$key => $message];
    }

    /**
     * Returns all validation messages.
     *
     * @return array
     */
    public static function getValidationMessages()
    {
        return static::$messages;
    }

    /**
     * Returns all columns with a specfic validation rule.
     *
     * @param  string  $rule
     * @return array
     */
    public static function columnsWithRule($rule)
    {
        $rules = static::getRules();
        $has = [];
        if ($rules) {
            foreach($rules as $key => $val) {
                if (static::hasRule($rule, $key)) {
                    $has[] = $key;
                }
            }
        }

        return $has;
    }

    /**
     * Returns a boolean value as to whether a column has a specfic validation rule.
     *
     * @param  string  $rule
     * @param  string  $column
     * @return bool
     */
    public static function hasRule($rule, $column)
    {
        $rules = static::getRules();

        if (isset($rules[$column])) {

            foreach($rules[$column] as $r) {
                if (is_array($r)) {
                    $r = implode('|', $r);
                }
                if (strpos($r, $rule) !== false) {
                    return true;
                }
            }

            return false;

        } else {
            return false;
        }
    }

    /**
     * Returns whether the model will raise an exception or
     * return a boolean when validating.
     *
     * @return bool
     */
    public function getThrowValidationExceptions()
    {
        return isset($this->throwValidationExceptions) ? $this->throwValidationExceptions : false;
    }

    /**
     * Set whether the model should raise an exception or
     * return a boolean on a failed validation.
     *
     * @param  bool $value
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setThrowValidationExceptions($value)
    {
        $this->throwValidationExceptions = (boolean) $value;

        return $this;
    }

    /**
     * Throw a validation exception.
     *
     * @throws \Snap\Database\Model\Exceptions\ValidationException
     */
    public function throwValidationException()
    {
        $exception = new ValidationException(get_class($this) . ' model could not be persisted as it failed validation.');

        $exception->setModel($this);
        $exception->setErrors($this->getErrors());

        throw $exception;
    }
    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $origValidate = $this->skipValidation;
        $origAutoValidate = $this->autoValidate;

        if (!Arr::get($options, 'validate', true)) {
            $this->skipValidation = true;
            $this->autoValidate = false;
        }

        if (!Arr::get($options, 'autoValidate', true)) {
            $this->autoValidate = false;
        }
        $saved = parent::save($options);

        $this->skipValidation = $origValidate;
        $this->autoValidate = $origAutoValidate;

        return $saved;
    }

}

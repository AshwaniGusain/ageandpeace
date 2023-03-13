<?php

namespace Snap\Form;

use Illuminate\Foundation\Application;
use InvalidArgumentException;

/**
 * Class FormFactory
 *
 * @package Snap\Form
 */
class FormFactory
{
    /**
     * The array key that determines what class to use for the input type.
     * (only if an array syntax is used in the admin.forms.types config)
     */
    const CLASS_KEY = 'class';

    /**
     * The config key that determines what additional initialization
     * properties should be passed to a form input.
     * (only if an array syntax is used in the admin.forms.types config)
     */
    const CONFIG_KEY = 'config';

    /**
     * The default input to use if no bindings are found.
     */
    const DEFAULT_INPUT = 'text';

    /**
     * Map of input types to a class.
     * @var array
     */
    protected $bindings = [];

    /**
     * Configuration parameters to pass to inputs upon instantiation.
     * @var array
     */
    protected $config = [];

    /**
     * Hints when mapping model fields to input types.
     * @var array
     */
    protected $hints = [];

    /**
     * FormFactory constructor.
     *
     * @param array $config
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(array $config, Application $app)
    {
        $this->app = $app;
        $this->config = $config;

        if (! empty($config['types'])) {
            $this->bind($config['types']);
        }

        if (! empty($config['hints'])) {
            $this->normalizeHints($config['hints']);
        }
    }

    /**
     * Creates a Form class in which you can add additional inputs.
     *
     * @param array $elements
     * @return \Snap\Form\Form
     */
    public function make($elements = [])
    {
        $instance = new Form($this);

        if (! empty($props)) {
            $instance->add($elements);
        }

        return $instance;
    }

    /**
     * Generates a form based on a model.
     *
     * @param $model
     * @param array $options
     * @return \Snap\Form\FormModel
     */
    public function model($model, $options = [])
    {
        // $hints = array_merge($this->hints, $hints);
        // $instance = new FormModel($model, $this->make(), $hints);
        //$instance = $this->make()->model($model, $options);
        return FormModel::make($model, $options);
    }

    /**
     * Creates a form element.
     *
     * @param $name
     * @param string $type
     * @param array $props
     * @return mixed
     */
    public function element($name, $type = 'text', $props = [])
    {
        if (empty($type)) {
            $type = static::DEFAULT_INPUT;
        }

        list($name, $type, $props) = $this->normalizeProps($name, $type, $props);

        $class = $this->getBoundClass($type);
        if (! class_exists($class)) {
            $class = $this->getBoundClass(static::DEFAULT_INPUT);
        }

        $props = array_merge($this->getDefaultProps($type), $props);

        return new $class($name, $props);
    }

    /**
     * Returns the class bound to a specified type.
     *
     * @param $type
     * @return null
     */
    public function getBoundClass($type)
    {
        if (isset($this->bindings[$type][static::CLASS_KEY])) {
            return $this->bindings[$type][static::CLASS_KEY];
        }

        return null;
    }

    /**
     * Determines whether a binding exists for a specified type.
     *
     * @param $type
     * @return bool
     */
    public function hasBinding($type)
    {
        return (isset($this->bindings[$type]));
    }

    /**
     * Returns the default properties to be used for initialization.
     *
     * @param $type
     * @return array
     */
    public function getDefaultProps($type)
    {
        if (isset($this->bindings[$type][static::CONFIG_KEY])) {
            return $this->bindings[$type][static::CONFIG_KEY];
        }

        return [];
    }

    /**
     * Adds a hint for a generating form inputs based on a model.
     *
     * @param $hints
     * @param null $val
     * @param string $type
     * @return $this
     */
    public function hint($hints, $val = null, $type = 'name')
    {
        if (! is_array($hints)) {
            $hints = [$type => [$hints => $val]];
        }

        $this->normalizeHints($hints);

        return $this;
    }

    /**
     * Returns the hints set for generating forms based on models.
     *
     * @return array
     */
    public function hints()
    {
        return $this->hints;
    }

    /**
     * Binds a "type" (or handle) to a specified class.
     *
     * @param $type
     * @param null $config
     * @return $this
     */
    public function bind($type, $config = null)
    {
        if (is_array($type)) {

            foreach ($type as $k => $v) {
                $this->bind($k, $v);
            }
        } else {

            if (is_string($config)) {
                $config = [static::CLASS_KEY => $config, static::CONFIG_KEY => []];
            }

            if (! isset($config[static::CLASS_KEY])) {

                $className = get_class($this);
                throw new InvalidArgumentException("Error in class $className. You must provide a ".static::CLASS_KEY." key in the array form element type if you are using the array format");
            }

            $this->bindings[$type] = $config;
        }

        return $this;
    }

    /**
     * Normalizes the input property information.
     *
     * @param $name
     * @param $type
     * @param array $props
     * @return array
     */
    protected function normalizeProps($name, $type, $props = [])
    {
        if (is_array($name)) {
            $props = $name;
            $name = (! empty($props['name'])) ? $props['name'] : '';
            $type = (! empty($props['type'])) ? $props['type'] : '';
        } elseif (is_array($type)) {
            $props = $type;

            if (! empty($props['name'])) {
                $name = $props['name'];
            }

            $type = (! empty($props['type'])) ? $props['type'] : '';
        }

        if ($type instanceof Closure) {
            $props['output'] = call_user_func($type, $props, $this);
            $props['value'] = $props['output']; // value is set for readonly
            $type = 'custom';
        }

        return [$name, $type, $props];
    }

    /**
     * Normalizes the hint information for creating inputs based on models.
     *
     * @param $hints
     */
    protected function normalizeHints($hints)
    {
        foreach ($hints as $key => $val) {

            foreach ($val as $k => $v) {
                $this->hints[$key][$k] = is_string($v) ? explode('|', $v) : $v;
            }
        }
    }
}
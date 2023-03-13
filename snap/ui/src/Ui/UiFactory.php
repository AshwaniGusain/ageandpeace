<?php

namespace Snap\Ui;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\View\Factory as ViewFactory;

class UiFactory {
    
    protected $app;
    protected $viewFactory;
    protected static $bindedNamespace = 'ui.';
    protected $bindings = [];
    protected static $dataTypes = [
        ''       => 'Snap\Ui\DataTypes\UiComponentType',
        'ui'     => 'Snap\Ui\DataTypes\UiComponentType',
        'object' => 'Snap\Ui\DataTypes\ObjectType',
        'config' => 'Snap\Ui\DataTypes\ConfigType',
        'view'   => 'Snap\Ui\DataTypes\ViewType',
        'trans'   => 'Snap\Ui\DataTypes\TransType',
    ];

    const BINDING_CLASS_KEY = 'class';

    public function __construct(Application $app, ViewFactory $viewFactory)
    {
        $this->app = $app;
        $this->viewFactory = $viewFactory;
    }

    public function make($name = null, $data = [], $parent = null)
    {
        if ($name instanceof UiComponent) {
            $name->with($data);
            return $name;
        }

        if (is_array($name)) {
            $data = $name;
            $name = null;
        }

        if ($data instanceof Closure) {
            $callback = $data;
            $data = [];
        }

        $class = $name;

        if ($this->isBound($name)) {
            $class = $this->resolveName($name);

        // If the name is not bound, then we check if there is a view file
        // that exists and if so, we create the UiComponent based on that view.
        } else if ( ! $this->isBound($name)) {

            if (! is_subclass_of('\\'.ltrim($name, '\\'), UiComponent::class)) {
                if (is_string($name) && $this->viewFactory->exists($name)) {
                    $data['view'] = $name;
                }

                // If the name is not bound to a class and it is not a subclass of
                // UiComponent then we simply set it to the UiComponent.
                $class = UiComponent::class;
            }
        }

        $component = $this->app->make($class, ['data' => $data]);

        if (!empty($callback)) {
            $callback($component);
        }

        return $component;
    }

    public function bind($name, $class = null)
    {
        if (is_array($name)) {
            foreach($name as $n => $c) {
                $this->bind($n, $c);
            }
        } else {
            if (!empty($name)) {
                list($class, $data) = $this->normalizeClassAndProperties($class);
                $this->bindings[$name] = $class;
                app()->bind($this->resolveName($name), function($app, $props) use ($class, $data) {
                    $data = array_merge($data, $props);
                    return $this->app->make($class, $data);
                });
            }
        }

        return $this;
    }

    public function isBound($name)
    {
        return $this->app->bound($this->resolveName($name));
    }

    public function bindings()
    {
        return $this->bindings;
    }

    public function boundClass($name)
    {
        if (isset($this->bindings[$name])) {
            return $this->bindings[$name];
        }

        return null;
    }
    
    protected function resolveName($name)
    {
        if (!is_subclass_of($name, UiComponent::class) && strncmp($name, static::$bindedNamespace, strlen(static::$bindedNamespace)) !== 0) {
            return static::$bindedNamespace . $name;
        }
        
        return $name;
    }

    public function getBindedNamespace()
    {
        return static::$bindedNamespace;
    }

    public function addDataTypes($dataTypes, $val = null)
    {
        if (is_string($dataTypes)) {
            $dataTypes = [$dataTypes => $val];
        }

        static::$dataTypes = array_merge(static::$dataTypes, (array) $dataTypes);

        return $this;
    }

    public static function getDataTypes()
    {
        return static::$dataTypes;
    }

    protected function normalizeClassAndProperties($class)
    {
        $props = [];
        if (is_array($class)) {
            if (isset($class[self::BINDING_CLASS_KEY])) {
                $props = $this->extractClassProperties($class);
                $class = $class[self::BINDING_CLASS_KEY];
            }
        }
        
        return [$class, $props];
    }

    protected function extractClassProperties($props)
    {
        if (is_array($props)) {
            unset($props[self::BINDING_CLASS_KEY]);
            return $props;
        }

        return [];
    }

    public function __call($method, $args)
    {
        $method = snake_case($method);
        if (empty($args[0])) {
            $args[0] = [];
        }
        
        return $this->make($method, $args[0]);
    }

}


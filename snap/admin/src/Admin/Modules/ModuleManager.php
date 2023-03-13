<?php

namespace Snap\Admin\Modules;

use ArrayAccess;
use ArrayIterator;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use IteratorAggregate;
use Snap\Admin\Modules\Contracts\ModuleInterface;
use Snap\Database\Model\Model;

class ModuleManager implements ArrayAccess, IteratorAggregate
{
    /**
     * @var array
     */
    protected $modules = [];

    /**
     * @var
     */
    protected $current;

    /**
     * @var
     */
    protected $modelToResourceCache = [];

    /**
     * ModuleManager constructor.
     *
     * @param array $modules
     */
    public function __construct($modules = [])
    {
        if ($modules) {
            $this->load($modules);
        } else {
            $this->loadDefaultModules();
            $this->discoverFromAdminFolder();
        }
    }

    /**
     * Loads one or more modules from an array.
     *
     * @param $modules
     * @return $this
     */
    public function load($modules)
    {
        if ( ! is_array($modules)) {
            $modules = [$modules];
        }

        foreach ($modules as $key => $module) {
            $this->add($module);
        }

        return $this;
    }

    /**
     * Adds a single module.
     *
     * @param $module
     * @return $this
     */
    public function add($module)
    {
        if ($module instanceof Module) {
            $this->modules[$module->handle()] = $module;
        } else {
            $module = static::make($module);
            $this->modules[$module->handle()] = $module;
        }

        return $this;
    }

    /**
     * Loads the default modules and is used only if no values are set in the config/snap/admin.php
     */
    protected function loadDefaultModules()
    {
        $defaultModules = [
            DashboardModule::class,
            SearchModule::class,
            UserModule::class,
        ];

        $this->add($defaultModules);
    }

    /**
     * @return void
     */
    protected function discoverFromAdminFolder()
    {
        $adminModulePath = admin_path('Modules');
        $filesInFolder = \File::files($adminModulePath);
        foreach ($filesInFolder as $file) {
            $class = 'App\Admin\Modules\\'.pathinfo($file->getFilename(), PATHINFO_FILENAME);
            if (class_exists($class) && is_subclass_of($class, Module::class)) {
                $module = new $class();
                $this->modules[$module->name()] = $module;
            }
        }
    }

    /**
     * @param $module
     * @return mixed
     * @throws \Exception
     */
    public static function make($module, $parent = null)
    {
        $class = null;
        $props = [];

        if (is_string($module)) {
            $class = $module;
        } elseif (is_array($module)) {

            // If the value is not a string, we'll check for key
            // of "class" or "0" to determine the class to map to.
            if (isset($module['class'])) {
                $class = $module['class'];
            } elseif (isset($module[0])) {
                $class = $module[0];
            }

            // Check for key of "config" or "1" to determine the config
            // array to pass for class module object instantiation.
            if (isset($module['props'])) {
                $props = $module['props'];
            } elseif (isset($module[1])) {
                $props = $module[1];
            }
        }

        // Ensure that the class string implements the ModuleInterface class.
        if (is_subclass_of($class, ModuleInterface::class)) {
            $module = new $class($props, $parent);
        } else {
            throw new \Exception('Invalid module class specified: '.((string) $module));
        }

        return $module;
    }

    /**
     * Returns an array of all the modules loaded.
     *
     * @param null $modules
     * @return array
     */
    public function all($modules = null)
    {
        $modules = is_array($modules) ? $modules : func_get_args();

        if (empty($modules)) {
            $modules = $this->modules;
        }

        if (!isset($allModules)) {
            static $allModules = [];
        }

        foreach ($modules as $module) {
            $allModules[$module->fullHandle()] = $module;
            //$modules[$handle] = $this->get($handle);

            if ($module->modules()) {
                $this->all($module->modules());
            }
        }

        return $allModules;
    }

    /**
     * Returns a single module based on the passed $handle parameter.
     *
     * @param $handle
     * @return mixed
     */
    public function get($handle)
    {
        if ($handle instanceof Module) {
            return $handle;
        }

        $handle = $this->resolveModuleHandle($handle);
        $module = Arr::get($this->modules, $handle);

        if (empty($module)) {
            throw new InvalidArgumentException("Invalid module instance '$handle' specified");
        }

        return $module;
    }

    /**
     * Returns an array of all the ResourceModule instance modules.
     *
     * @param null $modules
     * @return array
     */
    public function resources()
    {
        $moduleResources = [];
        foreach ($this->all() as $handle => $val) {
            $module = $this->get($handle);
            if ($module->isResource()) {
                $moduleResources[$module->fullHandle()] = $module;
            }
        }

        return $moduleResources;
    }

    /**
     * Returns a single resource module based on the $model parameter passed to it.
     *
     * @param $model
     * @return mixed
     */
    public function resource($model)
    {
        $class = ($model instanceof Model) ? get_class($model) : $model;

        if (isset($this->modelToResourceCache[$class])) {
            return $this->modelToResourceCache[$class];
        } else {
            foreach ($this->resources() as $module) {
                if (get_class($module->getModel()) == $class) {
                    $this->modelToResourceCache[$class] = $module;
                    return $module;
                }
            }
        }

        return null;
    }

    /**
     * Helper method to return a handle if the parameter is an instance of a Model.
     *
     * @param $handle
     * @return string
     */
    protected function resolveModuleHandle($handle)
    {
        if ($handle instanceof Model) {
            $handle = strtolower(class_basename($handle));
        }

        return $handle;
    }

    /**
     * Determines whether a module exists.
     *
     * @param $handle
     * @return bool
     */
    public function has($handle)
    {
        $handle = $this->resolveModuleHandle($handle);
        return array_key_exists($handle, $this->modules);
    }

    /**
     * Returns the currently active module in the admin based on the route (if there is one).
     *
     * @return mixed
     */
    public function current()
    {
        $route = request()->route();
        if ($route) {
            $actions = $route->getAction();
            if (isset($actions['module'])) {
                $this->setCurrent($actions['module']);
            }
        }

        return $this->current;
    }

    /**
     * Sets the currently active module in the admin.
     *
     * @param $module
     * @return $this
     */
    public function setCurrent($module)
    {
        if (! $module instanceof ModuleInterface) {
            $module = $this->get($module);
        }

        $this->current = $module;

        return $this;
    }

    /**
     * Runs all the Admin specific routes for the modules.
     *
     * @return $this
     */
    public function routes()
    {
        // Then we load all the module specific routes.
        foreach ($this->modules as $module) {
            $module->routes();
        }

        return $this;
    }

    /**
     * Runs all the public routes for the modules.
     *
     * @return $this
     */
    public function publicRoutes()
    {
        // Load any the module specific public routes.
        foreach ($this->modules as $module) {
            if (method_exists($module, 'publicRoutes')) {
                $module->publicRoutes();
            }
        }

        return $this;
    }


    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->modules);
    }

    /**
     * Determine if an element exists at an offset.
     *
     * @access  public
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->modules[$key]);
    }

    /**
     * Get a module.
     *
     * @access  public
     * @param  mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->modules[$key];
    }

    /**
     * Set the data for a module.
     *
     * @access  public
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->modules[$key] = $value;
    }

    /**
     * Unset the data at a given offset.
     *
     * @access  public
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->modules[$key]);
    }
}
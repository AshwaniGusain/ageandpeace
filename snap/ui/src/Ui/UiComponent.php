<?php

namespace Snap\Ui;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Snap\Support\Traits\HasBootableTraits;
use Snap\Support\Traits\IsObservable;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Snap\Support\Contracts\Observable;
use Snap\Support\Contracts\ToString;
use Snap\Support\Helpers\TextHelper;

class UiComponent implements UiInterface, ToString, Htmlable, Observable
{
    use HasBootableTraits;
    use IsObservable;

    const DATA_TYPE_DELIMITER = ':';

    const EVENT_INITIALIZING = 'initializing';
    const EVENT_INITIALIZED = 'initialized';
    const EVENT_VISIBLE = 'visible';
    const EVENT_INVISIBLE = 'invisible';
    const EVENT_BEFORE_RENDER = 'beforeRender';
    const EVENT_AFTER_RENDER = 'afterRender';

    protected static $observableEvents = [];

    protected static $rendererDefault = 'default';

    protected $view;

    protected $renderers = [];

    protected $visible = true;

    protected $data = [];

    protected $casts = [];

    protected $parent;

    public function __construct($data = [], $parent = null)
    {
        if ($parent) {
            $this->setParent($parent);
        }
        $this->boot($data);
    }

    protected function boot($data = [])
    {
        $this->addRenderer(true, '_render', static::$rendererDefault);

        $data = $this->formatIncomingData($data);

        // This is important to set the $data property here before the "with"
        // call below for objects that pass parameters to other objects.
        $data = array_merge($this->getParentData(), $this->data, $data);

        $this->extractKeysAndCasts($data);

        $this->with($this->data);

        $this->setView($this->view);

        $this->bootTraits();

        $this->fireEvent(static::EVENT_INITIALIZING);

        if (method_exists($this, 'initialize')) {
            app()->call([$this, 'initialize']);
        }

        $this->fireEvent(static::EVENT_INITIALIZED);
    }

    protected static function registeredObservableEvents()
    {
        return static::$observableEvents = [
            static::EVENT_INITIALIZING,
            static::EVENT_INITIALIZED,
            static::EVENT_VISIBLE,
            static::EVENT_INVISIBLE,
            static::EVENT_BEFORE_RENDER,
            static::EVENT_AFTER_RENDER,
        ];
    }

    protected function formatIncomingData($data)
    {
        return (array) $data;
    }

    protected function extractKeysAndCasts($data)
    {
        $processedData = [];

        foreach ($data as $k => $val) {
            $keyParts = explode(static::DATA_TYPE_DELIMITER, $k);

            if (count($keyParts) > 1) {
                $type = $keyParts[0];
                $k = $keyParts[1];
                $this->casts[$k] = $type;
            }

            if (!array_key_exists($k, $processedData)) {
                $processedData[$k] = $val;
            }
        }

        $this->data = $processedData;
    }

    public function getParentData()
    {
        $data = [];

        $reflection = new \ReflectionClass($this);
        $parentClass = $reflection->getParentClass();

        while ($parentClass) {
            $data = array_merge($data, $parentClass->getDefaultProperties()['data']);
            $parentClass = $parentClass->getParentClass();
        }

        return $data;
    }

    public function root()
    {
        $root = null;
        $parent = $this->parent();

        $i = 0;
        $max = 6;

        // The counter is simply just to prevent runaway infinite loops
        while ($parent && $root != $parent && $parent != $this && $i < $max) {
            $root = $parent;
            $parent = $parent->parent();
            $i++;
        }

        // If no parents were found, we'll assume it's a root level object and will return our self.
        if (is_null($root)) {
            $root = $this;
        }

        return $root;
    }

    public function parent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        // Only set it if it is null and not equal to itself.
        if (is_null($this->parent) && $parent != $this) {
            $this->parent = $parent;
        }

        return $this;
    }

    public function visible(bool $visible)
    {
        if ($visible instanceof Closure) {
            $visible = $visible();
        }

        $this->visible = (bool) $visible;

        $this->fireEvent(($visible ? static::EVENT_VISIBLE : static::EVENT_INVISIBLE));

        return $this;
    }

    public function isVisible()
    {
        return $this->visible;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setView($view)
    {
        if (is_string($view) && view()->exists($view)) {
            $view = view($view);
        }

        $this->view = $view;

        return $this;
    }

    public function getData()
    {
        $data = [];

        // Must ignore 'view' and 'parent' or it will cause a recursion with the getView() method.
        $ignore = ['view', 'parent'];
        foreach ($this->data as $key => $val) {
            if (!in_array($key, $ignore)) {
                $data[$key] = $this->$key;
            }
        }

        return $data;
    }

    public function setData($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->resolveData($k, $v);
            }
        } else {
            $this->resolveData($key, $value);
        }

        return $this;
    }

    public function hasData($key)
    {
        $data = $this->getData();
        return isset($data[$key]);
    }

    protected function resolveData($key, $value)
    {
        // First, check if a "set" method exists, and if so, we'll use that first.
        $method = 'set'.ucfirst(Str::camel($key));
        if (method_exists($this, $method)) {
            $this->$method($value);

        } else {
            // Second, we'll check any casts on the UIComponent and convert them.
            if (isset($this->casts[$key])) {
                $value = $this->cast($this->casts[$key], $value);

                // Third, if the value is a closure, then resolve it.
            } elseif ($value instanceof Closure) {
                $value = call_user_func($value);
            }

            $this->data[$key] = $value;
        }
    }

    protected function cast($cast, $value)
    {
        $dataTypes = UiFactory::getDataTypes();
        if (isset($dataTypes[$cast])) {
            $dataTypes = UiFactory::getDataTypes();
            $dataType = new $dataTypes[$cast];
            $value = $dataType->cast($value, $this);
        }

        return $value;
    }

    // Alias to setData so it's a little more inline with the view method signature.
    public function with($key, $value = null)
    {
        return $this->setData($key, $value);
    }


    protected function gatherData()
    {
        $data = $this->getData();

        $data['self'] = $this;

        return $data;
    }

    protected function _render()
    {
        $data = $this->gatherData();

        $view = $this->getView();

        if ($view) {

            if (is_string($view)) {
                return $view;
            }

            $view->with($data);

            return $view->render();
        }

        return '';
    }

    public function render()
    {
        if ($this->isVisible()) {
            $this->fireEvent(static::EVENT_BEFORE_RENDER);
            $output = $this->runRenderers();
            $this->fireEvent(static::EVENT_AFTER_RENDER, [$output]);

            return $output;
        }

        return '';
    }

    public function addRenderer($condition, $callback, $index = null)
    {
        $conditionCallback = [$condition, $callback];

        if (isset($index)) {
            if ($index == -1) {
                array_unshift($this->renderers, $conditionCallback);
            } else {
                $this->renderers[$index] = $conditionCallback;
            }
        } else {
            $this->renderers[] = $conditionCallback;
        }

        return $this;
    }

    protected function runRenderers()
    {
        // Loop through the renderers and if a condition is true, display it.
        if (! empty($this->renderers)) {

            foreach ($this->renderers as $index => $renderer) {

                if ($index === static::$rendererDefault) {
                    continue;
                }

                $output = $this->callRenderer($index);

                if ($output !== false) {

                    return $output;
                }
            }

            if (isset($this->renderers[static::$rendererDefault])) {

                return $this->callRenderer(static::$rendererDefault);
            }
        }
    }

    protected function callRenderer($index)
    {
        if (isset($this->renderers[$index])) {
            $condition = $this->renderers[$index][0];
            $callback = $this->renderers[$index][1];
        }

        if (is_callable($condition)) {
            $condition = call_user_func($condition);
        }

        if ($condition === true) {
            if (is_string($callback)) {
                $callback = [$this, $callback];
            }

            return call_user_func($callback, $this);

        }

        return false;
    }

    public function removeRenderer($index)
    {
        unset($this->renderers[$index]);

        return $this;
    }

    public function compose(View $view)
    {
        $this->with($view->getData());

        return $this->render();
    }

    public function toHtml()
    {
        return $this->render();
    }

    public static function eventName($name)
    {
        return 'ui.'.$name.': '.static::class;
    }

    protected function fireEvent($name, $params = [])
    {
        $params = array_merge([$this], $params);
        event(static::eventName($name), $params);
    }

    public function __toString()
    {
        $output = $this->render();

        return (string) $output;
    }

    public function __call($method, $args)
    {
        if (isset($args[0]) && $args[0] instanceof Closure) {
            $args[0]($this);

            return $this;
        } elseif (preg_match("/^get(.*)/", $method, $found)) {
            $name = Str::snake(TextHelper::decamelize($found[1]));

            if (array_key_exists($name, $this->data)) {

                return $this->data[$name];
            }
        } elseif (preg_match("/^set(.*)/", $method, $found)) {
            $name = Str::snake(TextHelper::decamelize($found[1]));

            return $this->with($name, $args[0]);
        }

        throw new \BadMethodCallException("Method ".get_class($this)."::{$method} or \$data[".lcfirst(str_replace('get', '', $method))."] property does not exist.");
    }

    public function __get($name)
    {
        $method = 'get'.ucfirst(Str::camel($name));

        if (method_exists($this, $method)) {

            return $this->$method();
        } elseif (array_key_exists($name, $this->data)) {

            return $this->data[$name];
        }
    }

    public function __set($name, $val)
    {
        $method = 'set'.ucfirst(Str::camel($name));

        if (method_exists($this, $method)) {
            $this->$method($val);

            return $this;
        } else {
            $this->data[$name] = $val;

            return $this;
        }
    }

    public function __isset($name)
    {
        $name = $this->__get($name);

        return isset($name);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
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
     * Get the data for a key.
     *
     * @access  public
     * @param  mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->data[$key];
    }

    /**
     * Set the data for a key.
     *
     * @access  public
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->data[$key] = $value;
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
        unset($this->data[$key]);
    }

}

    
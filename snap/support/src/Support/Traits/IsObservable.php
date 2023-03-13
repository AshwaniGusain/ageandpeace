<?php

namespace Snap\Support\Traits;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use InvalidArgumentException;

trait IsObservable
{
    /**
     * Register observers with the model.
     *
     * @param  object|array|string  $classes
     * @return void
     *
     * @throws \RuntimeException
     */
    public static function observe($classes)
    {
        foreach (Arr::wrap($classes) as $class) {
            static::registerObserver($class);
        }
    }

    /**
     * Register a single observer with the model.
     *
     * @param  object|string  $class
     * @return void
     *
     * @throws \RuntimeException
     */
    protected static function registerObserver($class)
    {
        $className = static::resolveObserverClassName($class);

        // When registering a model observer, we will spin through the possible events
        // and determine if this observer has that method. If it does, we will hook
        // it into the model's event system, making it convenient to watch these.
        foreach (static::registeredObservableEvents() as $event) {
            if (method_exists($class, $event)) {
                static::registerEvent($event, $className.'@'.$event);
            }
        }
    }

    /**
     * Resolve the observer's class name from an object or string.
     *
     * @param  object|string  $class
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private static function resolveObserverClassName($class)
    {
        if (is_object($class)) {
            return get_class($class);
        }

        if (class_exists($class)) {
            return $class;
        }

        throw new InvalidArgumentException('Unable to find observer: '.$class);
    }

    /**
     * Get the observable event names.
     *
     * @return array
     */
    public static function getObservableEvents()
    {
        return property_exists(get_called_class(), 'observableEvents') ? static::$observableEvents : [];
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param  string  $event
     * @param  \Closure|string  $callback
     * @return void
     */
    protected static function registerEvent($event, $callback)
    {
        \Event::listen(static::eventName($event), $callback);
    }

    public static function eventName($name)
    {
        return $name.': '.static::class;
    }
}

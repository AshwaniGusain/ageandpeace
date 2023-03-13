<?php

namespace Snap\Menu\Renderer;

use InvalidArgumentException;
use Snap\Menu\MenuBuilder;

abstract class BaseRenderer
{
    /**
     * The item tag.
     *
     * @var string
     */
    protected $builder = null;

    /**
     * Create a new Renderer for the MenuBuilder.
     *
     * @param  \Snap\Menu\MenuBuilder $builder
     * @return void
     */
    public function __construct(MenuBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Sets render option properties (any method with "set" prefix).
     *
     * @access  public
     * @param  array    An array with the key being the protected properties name and the value being the value to set
     * @return  $this
     */
    public function setOptions(array $options)
    {
        foreach ($options as $method => $value) {
            $methodName = 'set'.ucfirst($method);

            if (method_exists($this, $methodName)) {
                $this->$methodName($value);

                return $this;
            } else {
                $className = get_class($this);
                throw new InvalidArgumentException("Invalid option {$method} passed to {$className}::setOptions()");
            }
        }

        return $this;
    }
}
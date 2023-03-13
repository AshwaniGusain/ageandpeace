<?php

namespace Snap\Support;

use ArrayAccess;

class Version implements ArrayAccess
{
    public $version = '0';

    public function __construct($version = 0)
    {
        $this->set($version);
    }

    public function set($version)
    {
        $this->version = $version;

        return $this;
    }

    public function full()
    {
        return $this->version;
    }

    public function major()
    {
        return $this->part(0);
    }

    public function minor()
    {
        return $this->part(1);
    }

    public function patch()
    {
        return $this->part(2);
    }

    public function __toString()
    {
        return $this->full();
    }

    public function parts()
    {
        return explode('.', $this->version);
    }

    protected function part($index)
    {
        $parts = $this->parts();

        if (isset($parts[$index])) {
            return $parts[$index];
        }

        return '0';
    }

    public function offsetExists($index)
    {
        $parts = $this->parts();

        return isset($parts[$index]);
    }

    public function offsetGet($index)
    {
        return $this->part($index);
    }

    public function offsetSet($key, $value)
    {
        $parts = $this->parts();
        if (isset($parts[$key])) {
            $parts[$key] = $value;
        }
    }

    public function offsetUnset($key)
    {
        $parts = $this->parts();
        if (isset($parts[$key])) {
            $parts[$key] = 0;
        }
        $this->version = implode('.', $parts);
    }
}
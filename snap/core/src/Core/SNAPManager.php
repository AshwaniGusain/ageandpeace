<?php

namespace Snap\Core;

class SNAPManager
{
    protected $attached = [];

    public function __construct($attach = null)
    {
        if ($attach) {
            $this->attach($attach);
        }
    }

    public function attach($key, $obj = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->attach($k, $v);
            }
        } else {
            if (app()->bound('snap.'.$key)) {
                $obj = app()['snap.'.$key];
            } elseif (app()->bound($key)) {
                $obj = app()[$key];
            } elseif (is_string($obj) && class_exists($obj)) {
                $obj = new $obj();
            }

            $this->attached[$key] = $obj;
        }

        return $this;
    }

    public function detach($key)
    {
        unset($this->attached[$key]);

        return $this;
    }

    public function has($key)
    {
        return array_key_exists($this->attached, $key);
    }
}
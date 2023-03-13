<?php

namespace Snap\Asset;

use Snap\Support\Contracts\ToString;
use Illuminate\Contracts\Support\Htmlable;

abstract class AssetFile implements Htmlable, ToString {

    protected $path;
    protected $version;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function url($version = false)
    {
        $url = asset($this->path);

        if ($version === true || $this->version) {
            $url .= '?'.$this->version;
        }

        return $url;
    }

    public function name()
    {
        return pathinfo($this->path, PATHINFO_FILENAME);
    }

    public function dir()
    {
        return pathinfo($this->path, PATHINFO_DIRNAME);
    }

    public function basename()
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    public function ext()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function timestamp()
    {
        $path = $this->path();
        if (!preg_match('#^(//|https?://)#', $path)) {
            return filemtime($path);
        }
    }

    public function path()
    {
        if ( ! \URL::isValidUrl($this->path)) {
            return public_path($this->path);
        }

        return $this->path;
    }

    public function content()
    {
        return file_get_contents($this->path());
    }

    public function version($value = true)
    {
        if ($value === true) {
            $this->version = md5($this->timestamp());
        } elseif ($value === false) {
            $this->version = '';
        } else {
            $this->version = $value;
        }

        return $this;
    }

    public function toHtml($attrs = null)
    {
        return $this->tag($attrs);
    }

    abstract public function tag($attrs = null);
    abstract public function inline($attrs = null);

    public function __toString()
    {
        return $this->toHtml();
    }
}
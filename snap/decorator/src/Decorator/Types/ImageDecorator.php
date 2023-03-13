<?php

namespace Snap\Decorator\Types;

use HTML;
use Snap\Asset\Resource;
use Snap\Decorator\AbstractDecorator;

class ImageDecorator extends AbstractDecorator {

    protected static $decorators = [
                            'html',

                            ];

    protected static $exts = 'jpg|jpeg|jpe|gif|png|svg';
    protected $resource;

    public function __construct($value, $name = null, $props = [])
    {
        parent::__construct($value, $name, $props);
        $this->resource = new Resource($this->path());
    }
    public function html($attrs = [], $size = null)
    {
        $alt = '';
        if (is_string($attrs)) {
            $alt = $attrs;
        } elseif (isset($attrs['alt'])) {
            $alt = $attrs['alt'];
        }

        $src = $this->path($size);

        if ($s = $this->size($size)) {
            $attrs['width'] = $s['width'];
            $attrs['height'] = $s['height'];
        }

        return HTML::image($src, $alt, $attrs);
    }

    public function path($size = null)
    {
        $filename = $this->filename();

        if (isset($this->props['size'][$size])) {
            $filename = $filename . '_' . $this->props['size'][$size];
        }
        $filename = $filename.'.'.$this->ext();
        $path = (!empty($this->props['folder'])) ? trim($this->props['folder'], '/').'/'.$filename : $filename;

        return $path;
    }

    public function size($size)
    {
        if (isset($this->props['size'][$size])) {
            list($s['width'], $s['height']) = explode('x', $this->props['size'][$size]);
            return $s;
        }
    }

    public static function detect($value, $name = null)
    {
        return preg_match("/.+\\.".static::$exts."$/i", $value);
    }

    public function find($name, $args = [])
    {
        if (method_exists($this->resource, $name)) {
            return call_user_func_array([$this->resource, $name], $args);
        } else {
            return parent::find($name, $args);  
        }
    }
}

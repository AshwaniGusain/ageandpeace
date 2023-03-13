<?php

namespace Snap\Decorator\Types;

use HTML;
use Snap\Asset\Resource;
use Snap\Decorator\AbstractDecorator;

class MediaDecorator extends AbstractDecorator {

    protected static $decorators = [
                            'html',
                            ];

    protected static $exts = [
                            'image' => 'jpg|jpeg|jpe|gif|png|svg',
                            'video' => 'mov|mp4|ogg|webm',
                            'audio' => 'mp3|wav'
                            ];
    protected $resource;

    public function __construct($value, $name = null, $props = [])
    {
        parent::__construct($value, $name, $props);
        $this->resource = new Resource($this->path());
    }
    public function html($attrs = [], $size = null)
    {
        $type = static::detectType($this->value);
        switch($type) {
            case 'image':
                $html = $this->imageHtml($attrs, $size);
                break;
            case 'video':
                $html = $this->videoHtml($attrs, $size);
                break;
            case 'audio':
                $html = $this->audioHtml($attrs, $size);
                break;
            default:
                $html = $this->value;
        }
        return $html;
    }

    public function imageHtml($attrs, $size = null)
    {
        $alt = '';
        if (is_string($attrs)) {
            $alt = $attrs;
        } elseif (isset($attrs['alt'])) {
            $alt = $attrs['alt'];
        }

        $src = $this->path($size);
        $attrs = $this->sizeAttrs($attrs, $size);
        
        return HTML::image($src, $alt, $attrs);
    }

    public function audioHtml($attrs, $size = null)
    {
        $attrs = $this->sizeAttrs($attrs, $size);
        return $this->mediaHtml('audo', $attrs);
    }

    public function videoHtml($attrs, $size = null)
    {
        $attrs = $this->sizeAttrs($attrs, $size);
        return $this->mediaHtml('video', $attrs);
    }

    protected function mediaHtml($tag, $attrs)
    {
        $src = $this->path();
        $attrs = HTML::attributes($attrs);
        $mime = $this->mime();
        return '<'.$tag.$attrs.' controls><source src="'.$src.'" type="'.$mime.'">Your browser does not support the '.$tag.' tag.</'.$tag.'>';
    }

    protected function sizeAttrs($attrs, $size)
    {
        if (empty($size) && !empty($attrs['size'])) {
            $size = $attrs['size'];
        }

        if ($s = $this->size($size)) {
            $attrs = array_merge($s, $attrs);
        }
        return $attrs;
    }

    public function size($size)
    {
        $s = null;

        if (isset($this->props['sizes'])) {
            if (isset($this->props['sizes'][$size])) {
                list($s['width'], $s['height']) = explode('x', $this->props['sizes'][$size]);
                $s['class'] = $size;
            } elseif (in_array($size, $this->props['sizes'])) {
                $s['class'] = $size;
            }
        }
        return $s;
    }

    public function path($size = null)
    {
        $filename = pathinfo($this->value, PATHINFO_FILENAME);
        if (isset($this->props['sizes'])) {
            if (isset($this->props['sizes'][$size])) {
                $filename = $filename . '_' . $this->props['sizes'][$size];
            } elseif (in_array($size, $this->props['sizes'])) {
                $filename = $filename . '_' . $size;
            }
        }

        $filename = $filename.'.'.pathinfo($this->value, PATHINFO_EXTENSION);
        $path = (!empty($this->props['folder'])) ? trim($this->props['folder'], '/').'/'.$filename : $filename;

        // change the resource to the new path
        $this->resource = new Resource($path);
        return $path;
    }

    public static function setTypeExtensions($type, $exts)
    {
        static::$exts[$type] = $exts;
    }

    protected static function detectType($value)
    {
        if (! is_string($value)) return $value;
        
        $type = null;
        if (preg_match("/.+\\.".static::$exts['image']."$/i", $value)) {
            $type = 'image';
        } elseif (preg_match("/.+\\.".static::$exts['video']."$/i", $value)) {
            $type = 'video';
        } elseif(preg_match("/.+\\.".static::$exts['audio']."$/i", $value)) {
            $type = 'audio';
        }
        
        return $type;
    }

    public function find($name, $args = [])
    {
        if (method_exists($this->resource, $name)) {
            return call_user_func_array([$this->resource, $name], $args);
        } else {
            return parent::find($name, $args);  
        }
    }

    public static function detect($value, $name = null)
    {
        return static::detectType($value) != null;
    }



}

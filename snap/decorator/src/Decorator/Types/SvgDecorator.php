<?php

namespace Snap\Decorator\Types;

use HTML;
use Snap\Decorator\AbstractDecorator;

class SvgDecorator extends AbstractDecorator {

    protected static $decorators = [
                            'html',
                            ];

    public function path()
    {
        $this->value = (!empty($this->props['folder'])) ? trim($this->props['folder'], '/').'/'.$this->value : $this->value;
        return $this;
    }

    public function html($alt = null, $attrs = array())
    {
        $path = $this->path();
        return HTML::image($path, $alt, $attrs);
    }

    public function detect($value, $name = null)
    {
        return (preg_match("/.+\\.(svg)$/i", $value));
    }
}

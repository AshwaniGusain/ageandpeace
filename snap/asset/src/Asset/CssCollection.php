<?php

namespace Snap\Asset;

use Illuminate\Contracts\Support\Htmlable;

class CssCollection extends AssetCollection implements Htmlable {

    protected $fileClass = CssFile::class;

    public function toHtml($attrs = [])
    {
        return $this->links($attrs);
    }

    public function links($attrs = [])
    {
        $output = [];
        foreach ($this->files as $file) {
            $output[] = $file->tag($attrs);
        }

        return implode("\n\t", $output);
    }

    public function inject($attrs = array())
    {
        return $this->injector('link', 'href', 'tag.getAttribute("type") == "text/css"', array('rel' => 'stylesheet', 'type' => 'text/css'), $attrs);
    }
}
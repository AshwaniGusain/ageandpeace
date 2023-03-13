<?php

namespace Snap\Asset;

use Illuminate\Contracts\Support\Htmlable;

class JsCollection extends AssetCollection implements Htmlable {

    protected $fileClass = JsFile::class;

    public function toHtml($attrs = [])
    {
        return $this->scripts($attrs);
    }

    public function scripts($attrs = [])
    {
        $output = [];
        foreach ($this->files as $file) {
            $output[] = $file->tag($attrs);
        }

        return implode("\n\t", $output);
    }

    public function inject($attrs = array())
    {
        return $this->injector('script', 'src', 'tag.getAttribute("src")', array(), $attrs);
    }

}
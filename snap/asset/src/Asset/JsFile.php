<?php

namespace Snap\Asset;

use Snap\Support\Helpers\HtmlHelper;

class JsFile extends AssetFile {

    const EXT = '.js';

    public function tag($attrs = null)
    {
        $attrs['src'] = $this->url();

        return script_tag($attrs) . "\n";
    }

    public function inline($attrs = null)
    {
        $output = "<script>";
        if (!empty($attrs)) {
            $output .= HtmlHelper::attrs($attrs);
        }
        $output .= ">";
        $output .= "\n" . $this->content() . "\n";
        $output .= "</script>\n";

        return $output;
    }
}
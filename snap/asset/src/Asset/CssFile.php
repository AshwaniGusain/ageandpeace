<?php

namespace Snap\Asset;

use Snap\Support\Helpers\HtmlHelper;

class CssFile extends AssetFile {

    const EXT = '.css';

    public function tag($attrs = null)
    {
        $defaults = ['media' => 'all'];
        $attrs['rel'] = 'stylesheet';
        $attrs['href'] = $this->url();
        $attrs = array_merge($defaults, $attrs);

        return link_tag($attrs) . "\n";
    }

    public function inline($attrs = null)
    {
        $output = "<style";
        if (!empty($attrs)) {
            $output .= HtmlHelper::attrs($attrs);
        }
        $output .= ">";
        $output .= "\n" . $this->content() . "\n";
        $output .= "</style>\n";

        return $output;
    }
}
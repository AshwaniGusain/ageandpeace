<?php

if (!function_exists('css')) {

    function css($name, $files = [])
    {
        return \Asset::css($name, $files);
    }
}


if (!function_exists('js')) {
    
    function js($name, $files = [])
    {
        return \Asset::js($name, $files);
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('link_tag'))
{
    /**
     * "Borrowed" from the Almighty CodeIgniter
     *
     * Generates link to a CSS file
     *
     * @param   mixed   $href       Stylesheet hrefs or an array
     * @param   string  $rel
     * @param   string  $type
     * @param   string  $title
     * @param   string  $media
     * @return  string
     */
    function link_tag($href = '', string $rel = 'stylesheet', string $type = 'text/css', string $title = '', string $media = ''): string
    {
        $link = '<link ';
        if (is_array($href)){

            foreach ($href as $k => $v) {
                if ($k === 'href' && ! preg_match('#^([a-z]+:)?//#i', $v)) {
                    $link .= 'href="'.asset($v).'" ';
                } else {
                    $link .= $k.'="'.$v.'" ';
                }
            }

        } else {

            if (preg_match('#^([a-z]+:)?//#i', $href)) {
                $link .= 'href="'.$href.'" ';
            } else {
                $link .= 'href="'.asset($href).'" ';
            }

            $link .= 'rel="'.$rel.'" type="'.$type.'" ';

            if ($media !== '') {
                $link .= 'media="'.$media.'" ';
            }

            if ($title !== '') {
                $link .= 'title="'.$title.'" ';
            }
        }

        return trim($link).">";
    }
}

if ( ! function_exists('script_tag'))
{
    /**
     * "Borrowed" from the Almighty CodeIgniter
     *
     * Generates link to a JS file
     *
     * @param   mixed   $src        Script source or an array
     * @return  string
     */
    function script_tag($src = '', $attrs = []): string
    {
        $script = '<script ';

        if (is_array($src)) {
            foreach ($src as $k => $v) {
                if ($k === 'src' && ! preg_match('#^([a-z]+:)?//#i', $v)) {
                    $script .= 'src="'.asset($v).'" ';
                } else {
                    $script .= $k.'="'.$v.'" ';
                }
            }
        } else {
            if (preg_match('#^([a-z]+:)?//#i', $src)) {
                $script .= 'src="'.$src.'" ';
            } else {
                $script .= 'src="'.asset($src).'" ';
            }
        }

        return $script.'type="text/javascript"></script>';
    }

}


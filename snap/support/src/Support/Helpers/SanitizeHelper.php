<?php

namespace Snap\Support\Helpers;

use Illuminate\Support\Traits\Macroable;
use Snap\Support\Helpers\Classes\Purifier;
use Laminas\Escaper\Escaper;

class SanitizeHelper {

    use Macroable;

    // Borrowed from CodeIgniter
    protected static $filenameBadChars = [
        '../', '<!--', '-->', '<', '>',
        "'", '"', '&', '$', '#',
        '{', '}', '[', ']', '=',
        ';', '?', '%20', '%22',
        '%3c',		// <
        '%253c',	// <
        '%3e',		// >
        '%0e',		// >
        '%28',		// (
        '%29',		// )
        '%2528',	// (
        '%26',		// &
        '%24',		// $
        '%3f',		// ?
        '%3b',		// ;
        '%3d'		// =
    ];

    /**
     * Wrapper around the trim function but will check to make sure it's a string first
     * https://github.com/mewebstudio/Purifier
     *
     * @param 	string 	string to remove extra whitespace
     * @return	string
     */
    public static function trim($value, $type = 'both')
    {
        if (is_string($value)) {
            switch($type) {
                case 'ltrim': case 'left': case 'l':
                return ltrim($value);
                case 'rtrim': case 'right': case 'r':
                return rtrim($value);
                default:
                    return trim($value);
            }
        }

        return $value;
    }

    /**
     * Uses the Purify library to sanitze the HTML
     * https://github.com/mewebstudio/Purifier
     * https://github.com/xemlock/htmlpurifier-html5
     *
     * @param 	string 	string to remove javascript
     * @param 	array  	options
     * @param 	boolean whether to replace options or merge
     * @return	string
     */
    public static function clean($str, $options = [], $replace = false)
    {
        if (is_string($options)) {
            $options = config('purifier.settings.').$options;
            //$options = ['HTML.Allowed' => $options];
        }

        $options = ($replace) ? $options : array_merge(config('purifier.settings.default'), $options);

        // This is no bueno when sanitizing data so we make sure it's not set unless explicitly passed.
        if (!isset($options['AutoFormat.AutoParagraph'])) {
            $options['AutoFormat.AutoParagraph'] = false;
        }

        $encodeAmpersands = true;

        if (isset($options['HTML.EncodeAmpersand']) && $options['HTML.EncodeAmpersand'] === false) {
            $encodeAmpersands = false;
            unset($options['HTML.EncodeAmpersand']);
        }

        if ($encodeAmpersands) {
            $str = preg_replace('/&(?![a-z#]+;)/i', '__TEMP_AMP__', $str);
        }

        $purifier = new Purifier(app('files'), app('config'));
        $str =  $purifier->clean($str, $options);

        if ($encodeAmpersands) {
            $str = str_replace('__TEMP_AMP__', '&', $str);
        }

        return $str;
    }

    /**
     * Removes javascript from a string
     *
     * @param 	string 	string to remove javascript
     * @return	string
     */
    public static function stripJavascript($str)
    {
        $str = preg_replace('#<script[^>]*>.*?</script>#is', '', $str);
        return $str;
    }

    /**
     * Strip image tags
     *
     * @param	string	$str
     * @return	string
     */
    public static function stripImages($str)
    {
        return preg_replace(array('#<img[\s/]+.*?src\s*=\s*["\'](.+?)["\'].*?\>#', '#<img[\s/]+.*?src\s*=\s*(.+?).*?\>#'), '\\1', $str);
    }

    /**
     * Strip anything but numbers
     *
     * @param	string	$str
     * @return	string
     */
    public static function cleanNumbers($str)
    {
        return preg_replace('/[^0-9\.]/', '', $str);
    }

    /**
     * Safely converts a string's entities without encoding HTML tags and quotes
     *
     * @param 	string 	string to evaluate
     * @param 	boolean determines whether to encode the ampersand or not
     * @return	string
     */
    public static function safeHtmlEntities($str, $protect_amp = false)
    {
        // convert all hex single quotes to numeric ...
        // this was due to an issue we saw with htmlentities still encoding it's ampersand again'...
        // but was inconsistent across different environments and versions... not sure the issue
        // may need to look into other hex characters
        $str = str_replace('&#x27;', '&#39;', $str);

        // setup temp markers for existing encoded tag brackets
        $find = array('&lt;','&gt;');
        $replace = array('__TEMP_LT__','__TEMP_GT__');
        $str = str_replace($find,$replace, $str);

        // encode just &
        if ($protect_amp) {
            $str = preg_replace('/&(?![a-z#]+;)/i', '__TEMP_AMP__', $str);
        }

        // safely translate now
        $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8', false);

        // translate everything back
        $str = str_replace($find, array('<','>'), $str);
        $str = str_replace($replace, $find, $str);
        if ($protect_amp) {
            $str = str_replace('__TEMP_AMP__', '&', $str);
        }
        return $str;
    }

    /**
     * Sanitize Filename
     *
     * @param	string	$str		Input file name
     * @param 	bool	$relativePath	Whether to preserve paths
     * @return	string
     */
    public static function cleanFilename($str, $relativePath = false)
    {
        $bad = static::$filenameBadChars;

        if ( ! $relativePath) {
            $bad[] = './';
            $bad[] = '/';
        }

        $str = static::removeInvisibleCharacters($str, false);

        do {
            $old = $str;
            $str = str_replace($bad, '', $str);
        }
        while ($old !== $str);

        return trim(stripslashes($str));
    }

    /**
     * Remove Invisible Characters
     *
     * This prevents sandwiching null characters
     * between ascii characters, like Java\0script.
     *
     * @param	string
     * @param	bool
     * @return	string
     */
    public static function removeInvisibleCharacters($str, $urlEncoded = true)
    {
        $nonDisplayables = [];

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($urlEncoded) {
            $nonDisplayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
            $nonDisplayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
        }

        $nonDisplayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($nonDisplayables, '', $str, -1, $count);
        }
        while ($count);

        return $str;
    }

    /**
     * Is ASCII?
     *
     * Tests if a string is standard 7-bit ASCII or not.
     *
     * @param	string	$str	String to check
     * @return	bool
     */
    public static function isAscii($str)
    {
        return (preg_match('/[^\x00-\x7F]/S', $str) === 0);
    }

    /**
     * Converts a string to UTF8
     *
     * @param	string
     * @return	string
     */
    public static function utf8($str)
    {
        if (static::isAscii($str) === FALSE) {
            if (MB_ENABLED) {
                $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
            } elseif (ICONV_ENABLED) {
                $str = @iconv('UTF-8', 'UTF-8//IGNORE', $str);
            }
        }

        return $str;
    }

    /**
     * Escapes HTML data using the Zend Escaper:
     * https://github.com/zendframework/zend-escaper
     *
     * @param $str
     * @return string
     */
    public static function escapeHtml($str)
    {
        $escaper = static::escaper();
        return $escaper->escapeHtml($str);
    }

    /**
     * Escapes Javascript data using the Zend Escaper:
     * https://github.com/zendframework/zend-escaper
     *
     * @param $str
     * @return string
     */
    public static function escapeJs($str)
    {
        $escaper = static::escaper();
        return $escaper->escapeJs($str);
    }

    /**
     * Escapes CSS data using the Zend Escaper:
     * https://github.com/zendframework/zend-escaper
     *
     * @param $str
     * @return string
     */
    public static function escapeCss($str)
    {
        $escaper = static::escaper();
        return $escaper->escapeCss($str);
    }

    /**
     * Escapes a URL using the Zend Escaper:
     * https://github.com/zendframework/zend-escaper
     *
     * @param $str
     * @return string
     */
    public static function escapeUrl($str)
    {
        $escaper = static::escaper();
        return $escaper->escapeHtmlAttr($str);
    }

    /**
     * Escapes HTML attributes using the Zend Escaper:
     * https://github.com/zendframework/zend-escaper
     *
     * @param $str
     * @return string
     */
    public static function escapeAttr($str)
    {
        $escaper = static::escaper();
        return $escaper->escapeHtmlAttr($str);
    }

    /**
     * Returns the Zend Escaper object (assumes it's UTF-8):
     * https://github.com/zendframework/zend-escaper
     *
     * @return \Zend\Escaper\Escaper
     */
    public static function escaper()
    {
        static $escaper;
        if (is_null($escaper)) {
            $escaper = Escaper('utf-8');
        }
        return $escaper;
    }

    /*
     * XSS filter
     * https://gist.github.com/mbijon/1098477/4e245ab3844ddbd0e7c6f9fdf360501517e121cf
     * This was built from numerous sources
     * (thanks all, sorry I didn't track to credit you)
     *
     * It was tested against *most* exploits here: http://ha.ckers.org/xss.html
     * WARNING: Some weren't tested!!!
     * Those include the Actionscript and SSI samples, or any newer than Jan 2011
     *
     *
     * TO-DO: compare to SymphonyCMS filter:
     * https://github.com/symphonycms/xssfilter/blob/master/extension.driver.php
     * (Symphony's is probably faster than my hack)
     */
    public static function xssClean($data)
    {
        // Fix &entity\n;
        $data = str_replace(['&amp;', '&lt;', '&gt;'], ['&amp;amp;', '&amp;lt;', '&amp;gt;'], $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        // we are done...
        return $data;
    }
}

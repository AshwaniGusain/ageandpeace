<?php

namespace Snap\Support\Helpers;

use HTML;
use Illuminate\Support\Traits\Macroable;
use Request;

class UrlHelper
{
    use Macroable;

    public static function uri($to = null, $from = null)
    {
        if (! empty($from) || ! empty($to)) {
            $segments = request()->segments();
            if (empty($to)) {
                $to = count($segments);
            }
            $s = [];
            for ($i = (int) $from; $i < (int) $to; $i++) {
                if (! empty($segments[$i])) {
                    $s[] = $segments[$i];
                }
            }

            return implode('/', $s);
        } else {
            return request()->path();
        }
    }

    public static function currentUrl($query = false, $to = null)
    {
        $uri = static::uri($to);
        if ($query) {
            $query = static::query($query);

            if (! empty($query)) {
                $uri .= '?'.$query;
            }
        }

        return url($uri);
    }

    public static function segment($n, $default = false)
    {
        return request()->segment($n, $default);
    }

    public static function isHttpPath($path)
    {
        return (preg_match('!^(\w+:)?//! i', $path));
    }

    public static function isAbsoluteUrl($link)
    {
        return (preg_match('#^(http(s)?:)?//#i', $link));
    }

    public static function isExternalUrl($link, $exts = [])
    {
        $parts = parse_url($link);

        $test = $_SERVER['SERVER_NAME'];
        $domain = '';
        if (isset($parts['host'])) {
            if ($parts['host'] == $test) {
                return '';
            }

            $host = explode('.', $parts['host']);

            $index = count($host) - 1;
            if (isset($host[$index - 1])) {
                $domain = $host[$index - 1];
                $domain .= '.';
                $domain .= $host[$index];
            } else {
                if (count($host) == 1) {
                    $domain = $host[0];
                }
            }
        }

        // get the extension to check
        if (is_string($exts)) {
            $exts = [$exts];
        }
        $link_parts = explode('.', $link);
        $ext = end($link_parts);

        // check if an http path and that it is from a different domain
        if (static::isAbsoluteUrl($link) && $test != $domain || (! empty($exts) && in_array($ext, $exts))) {
            return true;
        }

        return false;
    }

    public static function prep($str = '')
    {
        if ($str == 'http://' || $str == '') {
            return '';
        }

        $url = parse_url($str);

        if (! $url || ! isset($url['scheme'])) {
            $str = 'http://'.$str;
        }

        return $str;
    }

    public static function unprep($str = '')
    {
        return preg_replace("(^https?://)", "", $str);
    }

    public static function anchor($link, $title = null, $attrs = [], $target = null)
    {
        $link = self::prep($link);

        if (empty($title)) {
            $title = $link;
        }

        if ($target === true || (is_null($target) && self::isExternalUrl($link))) {
            $attrs['target'] = '_blank';
        }

        return '<a href="'.$link.'"'.HtmlHelper::attrs($attrs).'>'.e($title).'</a>';
    }

    /**
     * Borrowed From CodeIgniter: Auto-linker
     *
     * Automatically links URL and Email addresses.
     * Note: There's a bit of extra code here to deal with
     * URLs or emails that end in a period. We'll strip these
     * off and add them after the link.
     *
     * @param    string    the string
     * @param    string    the type: email, url, or both
     * @param    bool    whether to create pop-up links
     * @return    string
     */
    public static function autoLink($str, $type = 'both', $popup = false)
    {
        // Find and replace any URLs.
        if ($type !== 'email' && preg_match_all('#(\w*://|www\.)[^\s()<>;]+\w#i', $str, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) {
            // Set our target HTML if using popup links.
            $target = ($popup) ? ' target="_blank"' : '';

            // We process the links in reverse order (last -> first) so that
            // the returned string offsets from preg_match_all() are not
            // moved as we add more HTML.
            foreach (array_reverse($matches) as $match) {
                // $match[0] is the matched string/link
                // $match[1] is either a protocol prefix or 'www.'
                //
                // With PREG_OFFSET_CAPTURE, both of the above is an array,
                // where the actual value is held in [0] and its offset at the [1] index.
                $a = '<a href="'.(strpos($match[1][0], '/') ? '' : 'http://').$match[0][0].'"'.$target.'>'.$match[0][0].'</a>';
                $str = substr_replace($str, $a, $match[0][1], strlen($match[0][0]));
            }
        }

        // Find and replace any emails.
        if ($type !== 'url' && preg_match_all('#([\w\.\-\+]+@[a-z0-9\-]+\.[a-z0-9\-\.]+[^[:punct:]\s])#i', $str, $matches, PREG_OFFSET_CAPTURE)) {
            foreach (array_reverse($matches[0]) as $match) {
                if (filter_var($match[0], FILTER_VALIDATE_EMAIL) !== false) {
                    $str = substr_replace($str, safe_mailto($match[0]), $match[1], strlen($match[0]));
                }
            }
        }

        return $str;
    }

    public static function target($link, $exts = [])
    {
        $urlParts = parse_url($link);

        $testDomain = $_SERVER['SERVER_NAME'];
        $domain = '';

        // get the extension to check
        if (is_string($exts)) {
            $exts = [$exts];
        }
        $linkParts = explode('.', $link);
        $ext = end($linkParts);

        if (isset($urlParts['host'])) {
            if ($urlParts['host'] == $testDomain && ! in_array($ext, $exts)) {
                return '';
            }

            $hostParts = explode('.', $urlParts['host']);

            $index = count($hostParts) - 1;
            if (isset($hostParts[$index - 1])) {
                $domain = $hostParts[$index - 1];
                $domain .= '.';
                $domain .= $hostParts[$index];
            } else {
                if (count($hostParts) == 1) {
                    $domain = $hostParts[0];
                }
            }
        }

        // check if an http path and that it is from a different domain
        if (self::isHttpPath($link) && $testDomain != $domain || (! empty($exts) && in_array($ext, $exts))) {
            return ' target="_blank"';
        }

        return '';
    }

    public static function query($query = true, $q = true)
    {
        if ($query === true || empty($query)) {
            $query = request()->getQueryString();
        } // if an array, it will exclude those query parameters with the matching key values
        elseif (is_array($query)) {
            $query = array_except(\Input::query(), $query);
            $query = http_build_query($query);
        }

        $query = trim($query, '?');

        if ($q) {
            $query = '?'.$query;
        }

        return $query;
    }

    public static function routeParameters($uri)
    {
        preg_match_all('#\{(.*?)\??\}#', $uri, $matches);
        if (! empty($matches[1])) {
            return $matches[1];
        }

        return [];
    }
}
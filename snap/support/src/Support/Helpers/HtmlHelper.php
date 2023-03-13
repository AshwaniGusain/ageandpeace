<?php

namespace Snap\Support\Helpers;

use Illuminate\Support\Traits\Macroable;

class HtmlHelper
{
    use Macroable;

    public static function attrs($attrs)
    {
        if (is_string($attrs)) {
            $attrs = new SimpleXMLElement("<element $attrs />");
        }

        if ($attrs instanceof Collection) {
            $attrs = $attrs->toArray();
        }

        if (isset($attrs['data']) && is_array($attrs['data'])) {

            foreach ($attrs['data'] as $key => $val) {
                if (! is_null($val)) {
                    $attrs['data-'.$key] = (string) $val;
                }
            }

            unset($attrs['data']);
        }
        $html = [];

        foreach ((array) $attrs as $key => $value) {

            $element = static::attributeElement($key, $value);

            if (! empty($element)) {
                $html[] = $element;
            }
        }

        // return implode(' ', $html);
        return count($html) > 0 ? ' '.trim(implode(' ', $html)) : '';
    }

    public static function attributeElement($key, $value)
    {
        // Changed from original
        if ($value === true) {
            return $key;
        }

        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        if (is_numeric($key)) {
            $key = (string) $value;
        }

        if (! is_null($value)) {

            // Changed from original to put encoded JSON in an attribute with single quotes
            if (is_array($value)) {
                return $key."=\"".static::encodeJsonAttribute($value)."\"";

            } elseif (is_string($value) || is_numeric($value)) {

                return $key.'="'.e($value).'"';

                // We encode then decode to cleanup escaped quotes that may cause issues.
            }/* elseif (is_json($value)) {
                return $key.'="'.static::encodeJsonAttribute(json_decode($value, true)).'"';
            }*/
        }

        return '';
    }

    public static function encodeJsonAttribute($value, $jsonParams = null)
    {
        if (is_array($value)) {
            $value = json_encode($value, $jsonParams);
        }

        //return $value;

        return static::entities($value, true);
    }

    /**
     * Obfuscate a string to prevent spam-bots from sniffing it.
     *
     * @param  string $value
     * @return string
     */
    public static function obfuscate($value)
    {
        $safe = '';

        foreach (str_split($value) as $letter) {
            if (ord($letter) > 128) {
                return $letter;
            }

            // To properly obfuscate the value, we will randomly convert each letter to
            // its entity or hexadecimal representation, keeping a bot from sniffing
            // the randomly obfuscated letters out of the string on the responses.
            switch (rand(1, 3)) {
                case 1:
                    $safe .= '&#'.ord($letter).';';
                    break;

                case 2:
                    $safe .= '&#x'.dechex(ord($letter)).';';
                    break;

                case 3:
                    $safe .= $letter;
            }
        }

        return $safe;
    }

    /**
     * Obfuscate an e-mail address to prevent spam-bots from sniffing it.
     *
     * @param  string  $email
     * @return string
     */
    public static function email($email)
    {
        return str_replace('@', '&#64;', static::obfuscate($email));
    }

    /**
     * Generate a HTML link to an email address.
     *
     * @param  string  $email
     * @param  string  $title
     * @param  array   $attributes
     * @return string
     */
    public static function mailto($email, $title = null, $attrs = [])
    {
        $email = static::email($email);

        $title = $title ?: $email;

        $email = static::obfuscate('mailto:') . $email;

        return '<a href="'.$email.'"'.static::attrs($attrs).'>'.static::entities($title).'</a>';
    }

    /**
     * Convert an HTML string to entities.
     *
     * @param  string  $value
     * @param  boolean $double
     * @return string
     */
    public static function entities($value, $double = false)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', $double);
    }

}


<?php

namespace Snap\Support\Helpers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Traits\Macroable;
use Snap\Form\Contracts\InputProcessorInterface;

class UtilHelper {

    /**
     * Evaluates a strings PHP code.
     *
     * @param 	string 	string to evaluate
     * @param 	mixed 	variables to pass to the string
     * @return	string
     */
    public static function evalStr($str, $vars = array())
    {
        extract($vars);

        // fix XML
        $str = str_replace('<?xml', '<@xml', $str);

        ob_start();
        if ((bool) @ini_get('short_open_tag') === FALSE)
        {
            $str = eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $str)).'<?php ');
        }
        else
        {
            $str = eval('?>'.$str.'<?php ');
        }
        $str = ob_get_clean();

        // change XML back
        $str = str_replace('<@xml', '<?xml', $str);
        return $str;
    }

    /**
     * Capture content via an output buffer
     *
     * @param	boolean	turn on output buffering
     * @return 	string	return buffered content
     */
    public static function capture($on = TRUE)
    {
        if ($on){
            ob_start();
        } else {
            return ob_get_clean();
        }
    }

    /**
     * Capture content via an output buffer
     *
     * @param   $cacheKey
     * @param   $limit
     * @return  string    return buffered content
     * @throws \Exception
     */
    public static function cacheCaptured($cacheKey, $limit = null)
    {
        $data = capture(false);
        \Cache::put($cacheKey, $data, $limit);
        return $data;
    }

    /**
     * Determine if a given value resembles a "TRUE" value
     *
     * @param	mixed	possible true value
     * @return 	boolean
     */
    public static function isTrue($val)
    {
        $val = strtolower($val);
        return ($val == 'y' || $val == 'yes' || $val === 1  || $val == '1' || $val == 'true');
    }

    /**
     * Determine if a given value resembles a "FALSE" value
     *
     * @param	mixed	possible false value
     * @return 	boolean
     */
    public static function isFalse($val)
    {
        return ! static::isTrue($val);
    }

    /**
     * Boolean check to determine string content is serialized
     *
     * @param	mixed	possible serialized string
     * @return 	boolean
     */
    public static function isSerialized($data)
    {
        if ( !is_string($data))
            return false;
        $data = trim($data);
        if ( 'N;' == $data )
            return true;
        if ( !preg_match('/^([adObis]):/', $data, $badions))
            return false;
        switch ( $badions[1] ) :
            case 'a' :
            case 'O' :
            case 's' :
                if ( preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if ( preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                    return true;
                break;
        endswitch;
        return false;
    }

    /**
     * Boolean check to determine string content is a JSON object string
     *
     * @param	mixed	possible serialized string
     * @return 	boolean
     */
    public static function isJson($data)
    {
        if (is_string($data)) {
            $json = json_decode($data, true);

            // Doesn't quite work the way I wanted.
            //return $json !== NULL && $data != $json && json_last_error() == JSON_ERROR_NONE;
            return ($json !== NULL && $data != $json && is_array($json));
        }

        return false;
    }

    /**
     * Boolean check to determine if a string is a float
     *
     * @param	string
     * @return 	boolean
     */
    public static function isFloat($data)
    {
        return ($data == (string)(float)$data);
    }

    /**
     * Returns an array of classes that an object uses including parent objects
     *
     * @param	mixed	$class
     * @param	bool	$autoload
     * @return 	boolean
     */
    public static function classUsesDeep($class, $autoload = true)
    {
        $traits = [];
        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while($class = get_parent_class($class));
        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }
        return array_unique($traits);
    }

    /**
     * A more reliable way of getting the HOST
     * http://stackoverflow.com/questions/1459739/php-serverhttp-host-vs-serverserver-name-am-i-understanding-the-ma
     *
     * @return 	string
     */
    public static function getHost()
    {
        $possibleHostSources = ['HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR'];
        $sourceTransformations = [
            "HTTP_X_FORWARDED_HOST" => function($value)
            {
                $elements = explode(',', $value);
                return trim(end($elements));
            }
        ];
        $host = '';
        foreach ($possibleHostSources as $source)
        {
            if (!empty($host)) break;
            if (empty($_SERVER[$source])) continue;

            $host = $_SERVER[$source];
            if (array_key_exists($source, $sourceTransformations))
            {
                $host = $sourceTransformations[$source]($host);
            }
        }

        // Remove port number from host
        $host = preg_replace('/:\d+$/', '', $host);

        return trim($host);
    }

    /**
     * Returns the last functions from the backtrace
     *
     * @param	int		$i = how far back in the backtrace stack you want to look to grab the function
     * @return 	string
     */
    public static function calledFunc($i = 2)
    {
        return debug_backtrace()[$i]['function'];
    }

    /**
     * Returns boolean value as to whether the variable passed is a closure or not
     *
     * @param	mixed	$var
     * @return 	boolean
     */
    public static function isClosure($var)
    {
        return $var instanceof \Closure;
    }

    /**
     * Creates a class instance and allows you to pass an array for the arguments
     * http://stackoverflow.com/questions/1929108/is-there-a-call-user-func-equivalent-to-create-a-new-class-instance
     *
     * @param $className
     * @param array $args
     * @return bool|mixed
     * @throws \ReflectionException
     */
    public static function createInstance($className, array $args = [])
    {
        if ( class_exists($className)) {
            return call_user_func_array([new \ReflectionClass($className), 'newInstance'], $args);
        }

        return false;
    }

    /**
     * Returns a boolean value to determine if a string OR object is an instance of a particular class.
     * https://stackoverflow.com/questions/20169805/php-check-if-class-name-stored-in-a-string-is-implementing-an-interface
     *
     * @param mixed $obj
     * @param $of
     * @return bool
     */
    public static function isInstanceOf($obj, $of)
    {
        if (is_string($obj)) {
            $interfaces = class_implements($obj);
            return ($interfaces && in_array($of, $interfaces));
        }

        return $obj instanceof $of;
    }

    /**
     * Creates a message bag.
     *
     * @param $msg
     * @return array|MessageBag|string
     */
    public static function bag($msg = [])
    {
        if (is_null($msg)) {
            return $msg;
        }

        if (is_string($msg)) {
            $msg = [$msg];
        }

        if (!$msg instanceof MessageBag && !$msg instanceof ViewErrorBag) {
            $msg = new MessageBag($msg);
        }

        return $msg;
    }
}
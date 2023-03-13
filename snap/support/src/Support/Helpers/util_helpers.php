<?php use Snap\Support\Helpers\UtilHelper;

if ( ! function_exists('eval_str'))
{
    /**
     * Evaluates a strings PHP code.
     *
     * @param 	string 	string to evaluate
     * @param 	mixed 	variables to pass to the string
     * @return	string
     */
    function eval_str($str)
    {
        return UtilHelper::evalStr($str, $vars = []);
    }
}

if ( ! function_exists('capture'))
{
    /**
     * Capture content via an output buffer
     *
     * @param	boolean	turn on output buffering
     * @param	string	if set to 'all', will clear end the buffer and clean it
     * @return 	string	return buffered content
     */
    function capture($on = TRUE)
    {
        return UtilHelper::capture($on);
    }
}

if ( ! function_exists('cache_captured'))
{
    /**
     * Capture content via an output buffer
     *
     * @param   $cacheKey
     * @param   $limit
     * @return  string    return buffered content
     * @throws \Exception
     */
    function cache_captured($cacheKey, $limit = null)
    {
        return UtilHelper::cacheCaptured($cacheKey, $limit);
    }
}

if ( ! function_exists('is_true'))
{
    /**
     * Format true value
     *
     * @param	mixed	possible true value
     * @return 	string	formatted true value
     */
    function is_true($val)
    {
        return UtilHelper::isTrue($val);
    }
}

if ( ! function_exists('is_false'))
{
    /**
     * Determine if a given value resembles a "FALSE" value
     *
     * @param	mixed	possible false value
     * @return 	boolean
     */
    function is_false($val)
    {
        return UtilHelper::isFalse($val);
    }
}

if ( ! function_exists('is_serialized'))
{
    /**
     * Boolean check to determine string content is serialized
     *
     * @param	mixed	possible serialized string
     * @return 	boolean
     */
    function is_serialized($data)
    {
        return UtilHelper::isSerialized($data);
    }
}

if ( ! function_exists('is_json'))
{
    /**
     * Boolean check to determine string content is a JSON object string
     *
     * @param	mixed	possible serialized string
     * @return 	boolean
     */
    function is_json($data)
    {
        return UtilHelper::isJson($data);
    }
}

if ( ! function_exists('is_float'))
{
    /**
     * Boolean check to determine if a string is a float
     *
     * @param	string
     * @return 	boolean
     */
    function is_float($data)
    {
        return UtilHelper::isFloat($data);
    }
}

if ( ! function_exists('class_uses_deep'))
{
    /**
     * Returns an array of classes that an object uses including parent objects
     *
     * @param	mixed	$class
     * @param	bool	$autoload
     * @return 	boolean
     */
    function class_uses_deep($class, $autoload = true)
    {
        return UtilHelper::classUsesDeep($class, $autoload);
    }
}

if ( ! function_exists('get_host'))
{
    /**
     * A more reliable way of getting the HOST
     * http://stackoverflow.com/questions/1459739/php-serverhttp-host-vs-serverserver-name-am-i-understanding-the-ma
     *
     * @return 	string
     */
    function get_host()
    {
        return UtilHelper::get_host();
    }
}

if ( ! function_exists('called_func'))
{
    /**
     * Returns the last functions from the backtrace
     *
     * @param	int		$i = how far back in the backtrace stack you want to look to grab the function
     * @return 	string
     */
    function called_func($i = 3)
    {
        return UtilHelper::calledFunc($i);
    }
}

if ( ! function_exists('is_closure'))
{
    /**
     * Returns boolean value as to whether the variable passed is a closure or not
     *
     * @param	mixed	$var
     * @return 	boolean
     */
    function is_closure($var)
    {
        return UtilHelper::isClosure($var);
    }
}

if ( ! function_exists('create_instance'))
{
    /**
     * Creates a class instance and allows you to pass an array for the arguments
     * http://stackoverflow.com/questions/1929108/is-there-a-call-user-func-equivalent-to-create-a-new-class-instance
     *
     * @param $className
     * @param array $args
     * @return bool|mixed
     * @throws \ReflectionException
     */
    function create_instance($className, $args = [])
    {
        return UtilHelper::createInstance($className, $args);
    }
}

if ( ! function_exists('bag'))
{
    /**
     * Creates a message bag.
     *
     * @param $msg
     * @return array|MessageBag|string
     */
    function bag($msg = [])
    {
        return UtilHelper::bag($msg);
    }
}
<?php 

use Snap\Support\Helpers\ArrayHelper;

if ( ! function_exists('is_iterable'))
{
	function is_iterable($var) 
	{
	    return ArrayHelper::isIterable($var);
	}
}

if ( ! function_exists('array_group'))
{
	/**
	 * Returns an array of arrays.
	 *
	 * @access	public
	 * @param	array an array to be divided
	 * @param	int number of groups to divide the array into
	 * @return	array
	 */	
	function array_group($array, $groups)
	{
		return ArrayHelper::group($array, $groups);
	}
}

if ( ! function_exists('array_collapse'))
{
	function array_collapse($array, $prefix = '')
	{
	    return ArrayHelper::group($array, $prefix);
	}
}

if ( ! function_exists('dot_to_array'))
{
	function dot_to_array(&$array, $path, $value) 
	{
	    return ArrayHelper::dotToArray($array, $path, $value);
	}
}

if ( ! function_exists('array_remove_value'))
{
	function array_remove_value($array, $value)
	{
		return ArrayHelper::remove($array, $value);
	}
}

if ( ! function_exists('csv_to_array'))
{

	/**
	 * Converts a .csv file to an associative array. Must have header row.
	 *
	 * @access	public
	 * @param	string  file name
	 * @param	string  the delimiter that separates each column
	 * @param	int     the index for where the header row starts
	 * @param	int     must be greater then the maximum line length. Setting to 0 is slightly slower, but works for any length
	 * @return	array
	 */	
	function csv_to_array($filename = '', $delimiter =  ',', $header_row = 0, $length = 0)
	{
		return ArrayHelper::csvToArray($filename, $delimiter, $header_row, $length);
	}
}

if ( ! function_exists('array_normalize'))
{
	function array_normalize($value, $splitter = '#\s*,\s*|\s+#', $removeEmpty = false)
	{
		return ArrayHelper::normalize($value, $splitter, $removeEmpty);
	}
}

if ( ! function_exists('array_exists'))
{
    function array_exists($value, $callback = 'is_null')
    {
        return ArrayHelper::exists($value, $callback);
    }
}

if ( ! function_exists('array_format'))
{
    function array_format($value, $glue = ', ')
    {
        return ArrayHelper::format($value, $glue);
    }
}

<?php

namespace Snap\Support\Helpers;

use Illuminate\Support\Traits\Macroable;

class ArrayHelper {

	use Macroable;

    /**
     * Check if a variable is iterable (e.g. can use foreach).
     *
     * @param $var
     * @return bool
     */
    public static function isIterable($var)
	{
	    return (is_array($var) || $var instanceof \Traversable);
	}


	/**
	 * Returns an array of arrays.
	 *
	 * @access	public
	 * @param	array an array to be divided
	 * @param	int number of groups to divide the array into
	 * @return	array
	 */	
	public static function group($array, $groups)
	{
		if (empty($array))
		{
			return array();
		}
		$items_in_each_group = ceil(count($array)/$groups);
		
		return array_chunk($array, $items_in_each_group);
	}


	// public static function array_collapse($array, $prefix = '')
	// {
	//     $result = array();

	//     foreach ($array as $key => $value)
	//     {
	//         $new_key = $prefix . (empty($prefix) ? '' : '.') . $key;

	//         if (is_array($value))
	//         {
	//             $result = array_merge($result, array_collapse($value, $new_key));
	//         }
	//         else
	//         {
	//             $result[$new_key] = $value;
	//         }
	//     }

	//     return $result;
	// }

    /**
     * Converts a dot syntax to an array.
     *
     * @param $array
     * @param $path
     * @param $value
     */
    public static function dotToArray(&$array, $path, $value)
	{
	    $keys = explode('.', $path);

	    while ($key = array_shift($keys)) {
	        $array = &$array[$key];
	    }

	    $array = $value;
	}

    /**
     * Searches an array and removes an item from it.
     *
     * @param $array
     * @param $value
     * @return mixed
     */
    public static function remove(&$array, $value)
	{
		if (($key = array_search($value, $array)) !== false) {
			unset($array[$key]);
		}

		return $array;
	}

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
	public static function csvToArray($filename = '', $delimiter =  ',', $header_row = 0, $length = 0)
	{
		if(!file_exists($filename) || !is_readable($filename)) {
			return FALSE;
		}

		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			$i = -1;
			while (($row = fgetcsv($handle, $length, $delimiter)) !== FALSE)
			{
				$i++;
				if ($i >= $header_row) {
					if(!$header)
					{
						$header = $row;
					}
					else
					{
						$data[] = array_combine($header, $row);
					}
				}
			}
			fclose($handle);
		}
		return $data;
	}

    /**
     * Converts a delimited string into an array.
     *
     * @param $value
     * @param string $splitter
     * @param bool $removeEmpty
     * @return array
     */
    public static function normalize($value, $splitter = '#\s*,\s*|\s+#', $removeEmpty = false)
	{
		if (is_string($value)) {

			if (UtilHelper::isJson($value)) {
				$value = json_decode($value, true);	
			} else {
				$value = preg_split($splitter, $value);
			}
			
		} elseif (is_object($value)) {

			if ($value instanceof JsonSerializable) {
				$value = $value->jsonSerialize();

			} elseif ($value instanceof Jsonable) {
				$value = json_decode($value->toJson(), true);

			} elseif ($value instanceof Arrayable) {
				$value = $value->toArray();
				
			} else {
				$value = get_object_vars($value);
			}
		}

		if ($removeEmpty)
		{
			$value = (array) $value;
			return array_filter($value);	
		}

		return (array) $value;
		
	}

    /**
     * Determines if a value exists in an array.
     *
     * @param $value
     * @param string $callback
     * @return bool
     */
    public static function exists($value, $callback = 'is_null')
    {
        if(count(array_filter($value, $callback)) == count($value)) {
            return false;
        }

        return true;
    }

    /**
     * Creates a readable format of array data.
     *
     * @param $data
     * @param string $glue
     * @param array $newColumnData
     * @return string
     */
    public static function format($data, $glue = '<br>', $newColumnData = [])
    {
        if (is_json($data)) {
            $data = json_decode($data, true);
        }
        if (is_array($data)) {
            foreach ($data as $key => $val) {

                if (is_json($val)) {
                    $val = json_decode($val, true);
                }

                if (is_array($val)) {
                    static::format($val, $glue, $newColumnData);
                } else {
                    $newColumnData[] = $key.': '.$val;
                }
            }

            return implode($glue, $newColumnData);
        }

    }

}


<?php 
if ( ! function_exists('query_escape'))
{
	function query_escape($str)
	{
		return \Snap\Database\DBUtil::quote($str);
	}
}

if ( ! function_exists('debug_query'))
{
	function debug_query($all = false, $format = 'screen', $return = false)
	{
		return \Snap\Database\DBUtil::debug($all, $format, $return);
	}
}
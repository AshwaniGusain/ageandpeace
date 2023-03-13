<?php 

use Snap\Support\Helpers\TextHelper;

if ( ! function_exists('humanize'))
{
	function humanize($str)
	{
		return TextHelper::humanize($str);
	}
}

if ( ! function_exists('decamelize'))
{
	function decamelize($str)
	{
		return TextHelper::decamelize($str);
	}
}

if ( ! function_exists('htmlify'))
{
	function htmlify($str, $reduce_linebreaks = FALSE)
	{
		return TextHelper::htmlify($str, $reduce_linebreaks);
	}
}

if ( ! function_exists('highlight'))
{
	function highlight($str, $phrase, $tag_open = '<mark>', $tag_close = '</mark>')
	{
		return TextHelper::highlight($str, $phrase, $tag_open, $tag_close);
	}
}

if ( ! function_exists('ellipsize'))
{
	function ellipsize($str, $max_length, $position = 1, $ellipsis = '&hellip;')
	{
		return TextHelper::ellipsize($str, $max_length, $position, $ellipsis);
	}
}


if ( ! function_exists('word_wrap'))
{
 	function word_wrap($str, $charlim = 76)
	{
		return TextHelper::wrap($str, $charlim);
	}
}

if ( ! function_exists('word_limiter'))
{
	function word_limiter($str, $limit = 100, $end_char = '&#8230;')
	{
		return TextHelper::wordLimiter($str, $limit, $end_char);
	}
}


if ( ! function_exists('character_limiter'))
{
 	function character_limiter($str, $n = 500, $end_char = '&#8230;')
	{ 
		return TextHelper::characterLimiter($str, $n, $end_char);
	}
}

if ( ! function_exists('strip_whitespace'))
{
 	function strip_whitespace($str)
	{ 
		return TextHelper::stripWhitespace($str);
	}
}

if ( ! function_exists('trim_multiline'))
{
 	function trim_multiline($str)
	{ 
		return TextHelper::trimMultiline($str);
	}
}

if ( ! function_exists('starts_with_upper'))
{
 	function starts_with_upper($str)
	{ 
		return TextHelper::startsWithUpper($str);
	}
}

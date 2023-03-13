<?php

namespace Snap\Support\Helpers;

use Illuminate\Support\Traits\Macroable;

class TextHelper
{
    use Macroable;

    /**
     * Character Limiter
     *
     * Limits the string based on the character count.  Preserves complete words
     * so the character count may not be exactly as specified.
     *
     * @param string
     * @param int
     * @param string    the end character. Usually an ellipsis
     * @return    string
     */
    public static function characterLimiter($str, $n = 500, $end_char = '&#8230;')
    {
        if (mb_strlen($str) < $n) {
            return $str;
        }

        // a bit complicated, but faster than preg_replace with \s+
        $str = preg_replace('/ {2,}/', ' ', str_replace(["\r", "\n", "\t", "\x0B", "\x0C"], ' ', $str));

        if (mb_strlen($str) <= $n) {
            return $str;
        }

        $out = '';
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val.' ';

            if (mb_strlen($out) >= $n) {
                $out = trim($out);

                return (mb_strlen($out) === mb_strlen($str)) ? $out : $out.$end_char;
            }
        }
    }

    /**
     * Converts a camelized string to a human readable spaced string
     * https://gist.github.com/proudlygeek/1888108
     *
     * @param string
     * @return    string
     */
    public static function decamelize(string $str)
    {
        $matches = [];
        preg_match("/.*(?=([A-Z].*))/", $str, $matches);

        return ($matches) ? trim(implode(' ', $matches)) : $str;
    }

    /**
     * Ellipsize String
     *
     * This function will strip tags from a string, split it at its max_length and ellipsize
     *
     * @param string    string to ellipsize
     * @param int    max length of string
     * @param mixed    int (1|0) or float, .5, .2, etc for position to split
     * @param string    ellipsis ; Default '...'
     * @return string    ellipsized string
     */
    public static function ellipsize($str, $maxLength, $position = 1, $ellipsis = '…')
    {
        // Strip tags
        $str = trim(strip_tags($str));

        // Is the string long enough to ellipsize?
        if (mb_strlen($str) <= $maxLength) {
            return $str;
        }

        $beg = mb_substr($str, 0, floor($maxLength * $position));
        $position = ($position > 1) ? 1 : $position;

        if ($position === 1) {
            $end = mb_substr($str, 0, -($maxLength - mb_strlen($beg)));
        } else {
            $end = mb_substr($str, -($maxLength - mb_strlen($beg)));
        }

        return $beg.$ellipsis.$end;
    }

    /**
     * Uses CodeIgniter's Typography class
     *
     * @param string
     * @param bool
     * @return    string
     */
    public static function htmlify(string $str, $reduce_linebreaks = false)
    {
        require_once('Classes/Typography.php');
        $typography = new \CI_Typography;

        return $typography->auto_typography($str, $reduce_linebreaks);
    }

    /**
     * Phrase Highlighter (From CodeIgniter)
     *
     * Highlights a phrase within a text string
     *
     * @param string $str the text string
     * @param string $phrase the phrase you'd like to highlight
     * @param string $tag_open the openging tag to precede the phrase with
     * @param string $tag_close the closing tag to end the phrase with
     * @return    string
     */
    public static function highlight(string $str, string $phrase, $tag_open = '<mark>', string $tag_close = '</mark>')
    {
        return ($str !== '' && $phrase !== '') ? preg_replace('/('.preg_quote($phrase, '/').')/iu', $tag_open.'\\1'.$tag_close, $str) : $str;
    }

    /**
     * Converts a dashed/underscored string to human readable (from CodeIgniter almighty)
     *
     * @param string
     * @param string
     * @return    string
     */
    public static function humanize(string $str, string $separator = '_')
    {
        $replacement = trim($str);
        return ucwords(preg_replace('/['.$separator.']+/', ' ', $replacement));
    }

    /**
     * Strips extra whitespace from a string
     *
     * @param string
     * @return    string
     */
    public static function stripWhitespace($str)
    {
        return trim(preg_replace('/\s\s+|\n/m', '', $str));
    }

    /**
     * Checks if a string starts with an upper case
     *
     * @param string
     * @return    boolean
     */
    public static function startsWithUpper($str)
    {
        $chr = mb_substr($str, 0, 1, "UTF-8");

        return mb_strtolower($chr, "UTF-8") != $chr;
    }

    /**
     * Trims extra whitespace from the end and beginning of a string on multiple lines
     *
     * @param string
     * @return    string
     */
    public static function trimMultiline($str)
    {
        return trim(implode("\n", array_map('trim', explode("\n", $str))));
    }

    /**
     * Word Limiter
     *
     * Limits a string to X number of words.
     *
     * @param string
     * @param int
     * @param string    the end character. Usually an ellipsis
     * @return    string
     */
    public static function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) === '') {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

        if (strlen($str) === strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]).$end_char;
    }

    /**
     * Word Wrap
     *
     * Wraps text at the specified character. Maintains the integrity of words.
     * Anything placed between {unwrap}{/unwrap} will not be word wrapped, nor
     * will URLs.
     *
     * @param string $str the text string
     * @param int $charlim = 76    the number of characters to wrap at
     * @return    string
     */
    public static function wrap($str, $charlim = 76)
    {
        // Set the character limit
        is_numeric($charlim) || $charlim = 76;

        // Reduce multiple spaces
        $str = preg_replace('| +|', ' ', $str);

        // Standardize newlines
        if (strpos($str, "\r") !== false) {
            $str = str_replace(["\r\n", "\r"], "\n", $str);
        }

        // If the current word is surrounded by {unwrap} tags we'll
        // strip the entire chunk and replace it with a marker.
        $unwrap = [];
        if (preg_match_all('|\{unwrap\}(.+?)\{/unwrap\}|s', $str, $matches)) {
            for ($i = 0, $c = count($matches[0]); $i < $c; $i++) {
                $unwrap[] = $matches[1][$i];
                $str = str_replace($matches[0][$i], '{{unwrapped'.$i.'}}', $str);
            }
        }

        // Use PHP's native function to do the initial wordwrap.
        // We set the cut flag to FALSE so that any individual words that are
        // too long get left alone. In the next step we'll deal with them.
        $str = wordwrap($str, $charlim, "\n", false);

        // Split the string into individual lines of text and cycle through them
        $output = '';
        foreach (explode("\n", $str) as $line) {
            // Is the line within the allowed character count?
            // If so we'll join it to the output and continue
            if (mb_strlen($line) <= $charlim) {
                $output .= $line."\n";
                continue;
            }

            $temp = '';
            while (mb_strlen($line) > $charlim) {
                // If the over-length word is a URL we won't wrap it
                if (preg_match('!\[url.+\]|://|www\.!', $line)) {
                    break;
                }

                // Trim the word down
                $temp .= mb_substr($line, 0, $charlim - 1);
                $line = mb_substr($line, $charlim - 1);
            }

            // If $temp contains data it means we had to split up an over-length
            // word into smaller chunks so we'll add it back to our current line
            if ($temp !== '') {
                $output .= $temp."\n".$line."\n";
            } else {
                $output .= $line."\n";
            }
        }

        // Put our markers back
        if (count($unwrap) > 0) {
            foreach ($unwrap as $key => $val) {
                $output = str_replace('{{unwrapped'.$key.'}}', $val, $output);
            }
        }

        return $output;
    }

}

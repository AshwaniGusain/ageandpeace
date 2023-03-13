<?php

namespace Snap\Docs\Inspector;

class CommentInspector
{
    protected $comment;

    protected $tags = [];

    protected $description;

    protected $example;

    public function __construct($comment)
    {
        $this->comment = $comment;

        $this->initializeTags();
    }

    public function exists()
    {
        return !empty($this->comment);
    }

    /**
     * Initializes the comment
     *
     * @access    public
     * @param    string
     * @return    void
     */
    public function initializeTags()
    {
        $tags = [
            "access"     => '',
            "author"     => '',
            "copyright"  => '',
            "deprecated" => '',
            "example"    => '',
            "ignore"     => '',
            "internal"   => '',
            "link"       => '',
            "param"      => '',
            "return"     => '',
            "see"        => '',
            "since"      => '',
            "tutorial"   => '',
            "version"    => '',
            "autodoc"    => '', // specific to SNAP documentation
        ];

        preg_match_all('#^\s+\*\s+@(\w+)\s+(.+)\n#m', $this->comment, $matches);
        if (! empty($matches[2])) {
            foreach ($matches[1] as $key => $tag) {
                $value = $matches[2][$key];
                if ($tag == 'param') {
                    $this->tags['params'] = trim(str_replace("\t", ' ', $value));
                } else {
                    if (empty($this->tags[$tag])) {
                        $this->tags[$tag] = trim(str_replace("\t", ' ', $value));
                    }
                }
            }
        }
    }

    /**
     * Returns an array of all the comment tags.
     *
     * @access    public
     * @return    array
     */
    public function tags()
    {
        return $this->tags;
    }

    /**
     * Returns a single comment tag.
     *
     * @param $type
     * @return null
     */
    public function tag($type)
    {
        return $this->tags[$type] ?? null;
    }

    /**
     * Returns a parameter tag
     *
     * @access    public
     * @param    int        The index (order) of the parameter to retrieve
     * @param    string    The part of the parameter to retrieve. Options are 'type' and 'comment'
     * @return    boolean
     */
    public function param($index, $type = null)
    {
        if (! isset($this->tags['params'])) {
            return false;
        }

        $params = $this->tags['params'];
        if (! is_int($index) OR ! isset($params[$index])) {
            return false;
        }

        $value = $params[$index];

        if (isset($type)) {
            $split = preg_split('#\s#', $value);
            if ($type == 'type') {
                $value = $split[0];
            } else {
                if ($type == 'comment' AND isset($split[1])) {
                    array_shift($split);
                    $value = implode(' ', $split);
                } else {
                    return false;
                }
            }
        }

        return $value;
    }

    /**
     * Returns a parameter tag
     *
     * @access    public
     * @param    int        The index (order) of the parameter to retrieve
     * @param    string    The part of the parameter to retrieve. Options are 'type' and 'comment'
     * @return    boolean
     */
    public function returnValue($type = null)
    {
        if (! isset($this->tags['return'])) {
            return false;
        }
        $value = $this->tags['return'];

        if (isset($type)) {
            $split = preg_split('#\s#', $value);
            if ($type == 'type') {
                $value = $split[0];
            } else {
                if ($type == 'comment' AND isset($split[1])) {
                    array_shift($split);
                    $value = implode(' ', $split);
                } else {
                    return false;
                }
            }
        }

        return $value;
    }


    // --------------------------------------------------------------------

    /**
     * Returns the comment description. You can pass it an array of formatting parameters which include:
     *
     * @access    public
     * @param     $strip
     * @return    boolean
     */
    public function description($strip = true)
    {
        if (! isset($this->description)) {
            preg_match('#/\*\*\s*(.+ )(@|\*\/)#Ums', $this->comment, $matches);
            if (isset($matches[1])) {
                $this->description = $matches[1];

                // removing preceding * and tabs
                $this->description = preg_replace('#\* *#m', "", $matches[1]);
                $this->description = preg_replace("#^ +#m", "", $this->description);

                // remove code examples since they are handled by the example method
                $this->description = preg_replace('#<code>.+</code>#ms', '', $this->translateDescription($this->description));
                $this->description = trim($this->description);
            } else {
                $this->description = $this->comment;
            }

            $this->description = trim($this->description);
        }

        $desc = $this->description;
        if ($strip) {
            $desc = strip_tags($desc);
        }

        //$desc = $this->translateDescription($desc);

        return $desc;
    }

    public function translateDescription($desc)
    {
        $desc = preg_replace('#```(.+)```#Us', "<code>$1</code>", $desc);
        $desc = preg_replace('#`(.+)`#U', "<code>$1</code>", $desc);

        return $desc;
    }

    /**
     * Returns a code example. Must be wrapped in a "code" HTML tag
     *
     * @access   public
     * @param    string    The opening tag to wrap the example in.
     * @param    string    The closing tag to wrap the example in.
     * @return   string
     */
    public function example($opening = '', $closing = '')
    {
        $example = '';
        if (! isset($this->example)) {
            preg_match('#/\*\*\s*.+<code>(.+)</code>#Ums', $this->translateDescription($this->comment), $matches);
            if (isset($matches[1])) {
                $this->example = preg_replace('#\*+#ms', '', $matches[1]);
                $this->example = trim(implode("\n", array_map('trim', explode("\n", $this->example))));
            }
        }

        if ($this->example) {
            $this->example = htmlentities($this->example, ENT_NOQUOTES, 'UTF-8', false);
            $example = $opening.$this->example.$closing;
        }

        return $example;
    }
}
<?php

namespace Snap\Docs;

class DocsPackage
{
    /**
     * @var null
     */
    public $handle = null;

    /**
     * @var null
     */
    public $label = null;

    /**
     * @var null
     */
    public $path = null;

    /**
     * @var null
     */
    public $section = null;

    /**
     * DocsPackage constructor.
     *
     * @param $handle
     * @param $label
     * @param $path
     * @param $section
     */
    public function __construct($handle, $label, $path, $section)
    {
        $this->handle = $handle;
        $this->label = $label;
        $this->path = $path;
        $this->section = $section;
    }

    /**
     * @param null $page
     * @return string
     */
    public function url($page = null)
    {
        $url = admin_url('docs/'.$this->handle);
        if ($page) {
            $url .= '/'.$page;
        }
        return $url;
    }

}
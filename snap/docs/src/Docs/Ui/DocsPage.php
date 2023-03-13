<?php

namespace Snap\Docs\Ui;

use Snap\Admin\Ui\BasePage;
use Snap\Ui\Traits\CssTrait;

class DocsPage extends BasePage {

    use CssTrait;

    protected $view = 'docs::page';
    protected $styles = [
        'assets/snap/docs/css/docs.css',
    ];
    protected $data = [
        ':heading'       => '\Snap\Admin\Ui\Components\Heading[module]',
        'package' => null,
        'page' => null,
        'title' => null,
        'content' => null,
    ];

    public function initialize()
    {
        $this->module = \Admin::modules()->get('docs');
        $this->heading->icon = $this->module->icon();

        $this->content = \Docs::page($this->package, $this->page);

        // Create top title
        $this->title = ucwords(humanize($this->package));
        preg_match('#<h1[^>]*>(.+)<\/h1>#U', $this->content, $matches);
        if (!empty($matches[1])) {
            //$this->title .= ' > '.strip_tags($matches[1]);
        }


        $this->heading->title = $this->title;

        $this->heading->back = '/admin/docs';

        if (!empty($this->page) && $this->page != 'index') {
            //$this->heading->title = $this->page;
            $segs = \Request::segments();
            array_pop($segs);
            $segs = array_slice($segs, 2);

            $this->heading->back .= '/'.implode('/', $segs);

        }

        $this->setPageTitle([trans('docs::messages.page_title')]);
    }


}
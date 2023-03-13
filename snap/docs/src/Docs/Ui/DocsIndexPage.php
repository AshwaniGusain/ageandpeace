<?php

namespace Snap\Docs\Ui;

use Snap\Admin\Ui\BasePage;

class DocsIndexPage extends BasePage {

    protected $view = 'docs::index';

    protected $data = [
        'module' => null,
        ':heading'       => '\Snap\Admin\Ui\Components\Heading[module]',
        'sections' => null,
        'content' => null,
        'labels' => null,
    ];

    public function initialize()
    {
        $this->module = \Admin::modules()->get('docs');
        $this->heading->title = $this->module->name();
        $this->heading->icon = $this->module->icon();

        $this->setPageTitle([trans('docs::messages.page_title')]);
    }


}
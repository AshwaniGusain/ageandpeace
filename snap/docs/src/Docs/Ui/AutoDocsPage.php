<?php

namespace Snap\Docs\Ui;

use Snap\Docs\Inspector\Inspector;
use Snap\Ui\Traits\CssTrait;

class AutoDocsPage extends DocsPage {

    use CssTrait;

    protected $view = 'docs::auto';
    protected $styles = [
        'assets/snap/docs/css/docs.css',
    ];

    protected $data = [
        'class' => null,
        'methods' => null,
        'traits' => null,
        'interfaces' => null,
        'properties' => null,
        'comment' => null,
        'constants' => null,
    ];

    public function initialize()
    {
        if (Inspector::isInspectable($this->class)) {
            $this->class = new Inspector($this->class);
            $this->comment = $this->class->comment();
            $this->methods = $this->class->methods(['public']);
            $this->traits = $this->class->traits();
            $this->interfaces = $this->class->interfaces();
            $this->props = $this->class->props(['public']);
            $this->constants = $this->class->constants();
            $this->url = \Docs::url('auto');

            $this->heading->title = $this->class->name;

        } else {
            abort(404);
        }

        $this->heading->back = '/admin/docs';
        $this->setPageTitle([trans('docs::messages.page_title')]);
    }


}
<?php

namespace Snap\Admin\Ui\Module\Resource;

class EditPage extends FormPage
{
    protected $view = 'admin::module.resource.edit';
    protected $data = [
        'resource' => null
    ];

    public function initialize()
    {
        parent::initialize();

        $this->heading
            ->setTitle($this->resource->display_name)
            ->setCreate(true)
            ->setBack($this->module->url())
            ;

        $this->setPageTitle([$this->resource->display_name, $this->module->pluralName()]);
    }
}
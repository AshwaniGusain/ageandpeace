<?php

namespace Snap\Admin\Ui\Module\Resource;

class EditInlinePage extends FormInlinePage
{
    protected $view = 'admin::module.resource.edit-inline';
    protected $data = [
        'resource' => null
    ];

    public function initialize()
    {
        parent::initialize();

        $this->heading
            ->setTitle($this->resource->display_name)
            ->setCreate(false)
            ->setBack(false)
        ;

        $this->setPageTitle([$this->resource->display_name, $this->module->pluralName()]);
    }


}
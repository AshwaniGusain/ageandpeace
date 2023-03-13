<?php

namespace Snap\Admin\Ui\Module\Resource;

class DuplicatePage extends FormPage
{
    protected $view = 'admin::module.resource.create';

    protected $data = [
        'resource' => null
    ];

    public function initialize()
    {
        parent::initialize();

        $this->heading
            ->setTitle(trans('admin::resources.duplicate'))
            ->setCreate(true)
            ->setBack($this->module->url())
        ;

        $this->setPageTitle([trans('admin::resources.duplicate'), $this->module->pluralName()]);
    }


}
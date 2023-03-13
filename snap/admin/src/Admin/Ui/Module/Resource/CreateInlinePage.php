<?php

namespace Snap\Admin\Ui\Module\Resource;

class CreateInlinePage extends FormInlinePage
{
    protected $view = 'admin::module.resource.create-inline';
    protected $data = [
    ];

    public function initialize()
    {
        parent::initialize();

        $this->heading
            ->setTitle(trans('admin::resources.create'))
            ->setCreate(false)
            ->setBack(false)
        ;

        $this->setPageTitle([trans('admin::resources.create'), $this->module->pluralName()]);
    }


}
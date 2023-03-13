<?php

namespace Snap\Admin\Ui\Module\Resource;

class DuplicateInlinePage extends FormInlinePage
{
    protected $view = 'admin::module.resource.create-inline';

    protected $data = [
        'resource' => null
    ];

    public function initialize()
    {
        parent::initialize();

        $this->heading
            ->setTitle(trans('admin::resources.duplicate'))
            ->setCreate(false)
            ->setBack(false)
        ;

        $this->setPageTitle([trans('admin::resources.duplicate'), $this->module->pluralName()]);
    }


}
<?php

namespace Snap\Admin\Ui\Module\Resource;

class CreatePage extends FormPage
{
    protected $view = 'admin::module.resource.create';
    protected $data = [
    ];

    public function initialize()
    {
        parent::initialize();

        $this->heading
            ->setTitle(trans('admin::resources.create'))
            ->setCreate(false)
            ->setBack($this->module->url())
            ;

        $this->setPageTitle([trans('admin::resources.create'), $this->module->pluralName()]);

        // Allows prefilling of page variables if they are passed in the request.
        $this->form->withValues(\Request::except($this->module->getModel()->getKeyName()));

    }

   
}
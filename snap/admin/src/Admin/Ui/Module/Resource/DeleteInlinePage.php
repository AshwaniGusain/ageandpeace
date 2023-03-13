<?php

namespace Snap\Admin\Ui\Module\Resource;

class DeleteInlinePage extends DeletePage
{
    protected $view = 'admin::module.resource.delete-inline';

    public function initialize()
    {
        parent::initialize();

        $this->heading->setBack(false);

        $this->setInline(true);
    }

}